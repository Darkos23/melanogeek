<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Web Push Notification Service (RFC 8030 + RFC 8291 + RFC 8292)
 * Pure PHP implementation — no external library needed.
 * Requires PHP 8.1+ (openssl_pkey_derive) and extensions: openssl, curl.
 */
class WebPushService
{
    private string $publicKeyB64;   // base64url raw 65-byte uncompressed EC point
    private string $privateKeyPem;  // PKCS8 PEM private key
    private string $subject;        // mailto: or https: URI

    public function __construct()
    {
        $this->publicKeyB64  = config('services.vapid.public_key');
        $this->privateKeyPem = str_replace('\n', "\n", config('services.vapid.private_key_pem'));
        $this->subject       = config('services.vapid.subject', 'mailto:contact@melanogeek.com');
    }

    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * Send a push notification to all subscriptions of a user.
     */
    public function notifyUser(User $user, string $title, string $body, string $url = '/'): void
    {
        $user->loadMissing('pushSubscriptions');

        foreach ($user->pushSubscriptions as $sub) {
            try {
                $this->sendToSubscription($sub, $title, $body, $url);
            } catch (\Throwable $e) {
                Log::warning("WebPush failed for sub #{$sub->id}: " . $e->getMessage());
                // Remove expired/invalid subscriptions (HTTP 404 or 410)
                if (str_contains($e->getMessage(), '404') || str_contains($e->getMessage(), '410')) {
                    $sub->delete();
                }
            }
        }
    }

    // ── Private: send one subscription ───────────────────────────────────────

    private function sendToSubscription(PushSubscription $sub, string $title, string $body, string $url): void
    {
        $payload = json_encode([
            'title' => $title,
            'body'  => $body,
            'url'   => $url,
            'icon'  => '/images/icons/icon-192.png',
            'badge' => '/images/icons/icon-192.png',
        ]);

        $encrypted = $this->encrypt($payload, $sub->public_key, $sub->auth_token);
        $jwt       = $this->createVapidJwt($sub->endpoint);

        $response = Http::withHeaders([
            'Authorization'   => 'vapid t=' . $jwt . ',k=' . $this->publicKeyB64,
            'Content-Type'    => 'application/octet-stream',
            'Content-Encoding'=> 'aes128gcm',
            'TTL'             => '2419200',
        ])->withBody($encrypted, 'application/octet-stream')
          ->post($sub->endpoint);

        if ($response->status() === 404 || $response->status() === 410) {
            throw new \RuntimeException("Expired subscription: HTTP {$response->status()}");
        }
        if ($response->failed()) {
            throw new \RuntimeException("Push failed: HTTP {$response->status()} — " . $response->body());
        }
    }

    // ── VAPID JWT (RFC 8292) ──────────────────────────────────────────────────

    private function createVapidJwt(string $endpoint): string
    {
        $parsed   = parse_url($endpoint);
        $audience = $parsed['scheme'] . '://' . $parsed['host'];

        $header  = $this->b64u(json_encode(['typ' => 'JWT', 'alg' => 'ES256']));
        $payload = $this->b64u(json_encode([
            'aud' => $audience,
            'exp' => time() + 43200, // 12 h
            'sub' => $this->subject,
        ]));

        $input      = "$header.$payload";
        $privateKey = openssl_pkey_get_private($this->privateKeyPem);

        openssl_sign($input, $derSig, $privateKey, OPENSSL_ALGO_SHA256);

        // ECDSA DER → raw 64-byte r||s
        $rawSig = $this->derToRawEcdsa($derSig);

        return "$input." . $this->b64u($rawSig);
    }

    // ── Payload Encryption (RFC 8291 / aes128gcm) ────────────────────────────

    private function encrypt(string $plaintext, string $subscriberPubB64, string $authB64): string
    {
        // Decode subscriber keys
        $subPubRaw = $this->b64uDecode($subscriberPubB64); // 65-byte uncompressed EC point
        $authSecret = $this->b64uDecode($authB64);         // 16-byte auth secret

        // Generate ephemeral EC P-256 key pair (sender side)
        $ephKey = openssl_pkey_new(['curve_name' => 'prime256v1', 'private_key_type' => OPENSSL_KEYTYPE_EC]);
        $ephDetails = openssl_pkey_get_details($ephKey);

        // Ephemeral sender public key as uncompressed point (04 || x || y)
        $senderPubRaw = "\x04"
            . str_pad($ephDetails['ec']['x'], 32, "\x00", STR_PAD_LEFT)
            . str_pad($ephDetails['ec']['y'], 32, "\x00", STR_PAD_LEFT);

        // Subscriber public key as OpenSSL resource
        $subPubKey = $this->rawPublicKeyToOpenSSL($subPubRaw);

        // ECDH shared secret
        $sharedSecret = openssl_pkey_derive($subPubKey, $ephKey);
        if ($sharedSecret === false) {
            throw new \RuntimeException('ECDH derivation failed: ' . openssl_error_string());
        }

        // Salt (16 random bytes)
        $salt = random_bytes(16);

        // RFC 8291 key derivation:
        // IKM  = HKDF(IKM=sharedSecret, salt=authSecret, info="WebPush: info\0"||subPub||senderPub, L=32)
        $ikm = hash_hkdf('sha256', $sharedSecret, 32,
            "WebPush: info\x00" . $subPubRaw . $senderPubRaw,
            $authSecret
        );

        // Content Encryption Key (16 bytes)
        $cek = hash_hkdf('sha256', $ikm, 16, "Content-Encoding: aes128gcm\x00", $salt);

        // Nonce (12 bytes)
        $nonce = hash_hkdf('sha256', $ikm, 12, "Content-Encoding: nonce\x00", $salt);

        // Pad + delimiter (\x02 = last record)
        $record = $plaintext . "\x02";

        // AES-128-GCM encrypt
        $tag        = '';
        $ciphertext = openssl_encrypt($record, 'aes-128-gcm', $cek, OPENSSL_RAW_DATA, $nonce, $tag, '', 16);

        // RFC 8188 aes128gcm header: salt(16) + rs(4 BE) + idlen(1) + keyid(65)
        $rs     = strlen($record) + 16; // ciphertext + tag
        $header = $salt
            . pack('N', $rs)          // record size (big-endian uint32)
            . "\x41"                  // key ID length = 65
            . $senderPubRaw;          // key ID = ephemeral public key

        return $header . $ciphertext . $tag;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Convert raw 65-byte EC public key to OpenSSL resource via SPKI PEM. */
    private function rawPublicKeyToOpenSSL(string $raw): mixed
    {
        // SPKI DER prefix for EC P-256
        $spkiHeader = hex2bin('3059301306072a8648ce3d020106082a8648ce3d030107034200');
        $der = $spkiHeader . $raw;
        $pem = "-----BEGIN PUBLIC KEY-----\n"
            . chunk_split(base64_encode($der), 64, "\n")
            . "-----END PUBLIC KEY-----\n";
        return openssl_pkey_get_public($pem);
    }

    /** Convert DER-encoded ECDSA signature to raw r||s (64 bytes). */
    private function derToRawEcdsa(string $der): string
    {
        $offset = 2; // skip SEQUENCE tag + length

        // Read r
        $offset++; // skip INTEGER tag (0x02)
        $rLen    = ord($der[$offset++]);
        $r       = substr($der, $offset, $rLen);
        $offset += $rLen;

        // Read s
        $offset++; // skip INTEGER tag (0x02)
        $sLen    = ord($der[$offset++]);
        $s       = substr($der, $offset, $sLen);

        // Strip leading 0x00 padding and left-pad to 32 bytes
        $r = str_pad(ltrim($r, "\x00"), 32, "\x00", STR_PAD_LEFT);
        $s = str_pad(ltrim($s, "\x00"), 32, "\x00", STR_PAD_LEFT);

        return $r . $s;
    }

    private function b64u(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function b64uDecode(string $data): string
    {
        $padded = str_pad(strtr($data, '-_', '+/'), strlen($data) + (4 - strlen($data) % 4) % 4, '=');
        return base64_decode($padded);
    }
}
