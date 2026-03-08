<?php
/**
 * Test direct HTTP request to the follow endpoint
 * (simule ce que fait le navigateur)
 */

// D'abord, obtenir un cookie de session en se connectant
$base = 'http://localhost:8000';

// 1. Récupérer la page de login admin pour avoir le CSRF token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/admin/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, '');
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
$response = curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);
curl_close($ch);

// Extraire le CSRF token du form
preg_match('/<input[^>]*name="_token"[^>]*value="([^"]+)"/i', $body, $matches);
$csrfToken = $matches[1] ?? null;
echo "1. GET /admin/login → CSRF token: " . ($csrfToken ? substr($csrfToken, 0, 20) . '...' : 'NOT FOUND') . "\n";

if (!$csrfToken) {
    echo "ERROR: No CSRF token found. Is the server running on port 8000?\n";
    exit(1);
}

// 2. Se connecter en tant que babstaroi (admin)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/admin/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $csrfToken,
    'email'  => 'babstaroi@melanogeek.com',  // ADJUST
    'password' => 'password',                  // ADJUST
]));
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Ne pas suivre les redirects
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$respHeaders = substr($response, 0, $headerSize);
curl_close($ch);

echo "2. POST /admin/login → HTTP $httpCode\n";
preg_match('/Location: (.+)/i', $respHeaders, $loc);
echo "   Redirect: " . ($loc[1] ?? 'none') . "\n";

if ($httpCode !== 302) {
    echo "Login may have failed. Check email/password in this script.\n";
}

// 3. Récupérer les cookies pour le CSRF (X-CSRF-TOKEN pour AJAX)
// Obtenir le meta tag csrf-token depuis une page
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/@ctght');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $headerSize);
curl_close($ch);

preg_match('/<meta name="csrf-token" content="([^"]+)"/i', $body, $matches);
$ajaxCsrf = $matches[1] ?? null;
echo "3. GET /@ctght → HTTP $httpCode, CSRF meta: " . ($ajaxCsrf ? substr($ajaxCsrf, 0, 20) . '...' : 'NOT FOUND') . "\n";

// Vérifier si on est bien connecté
$isLoggedIn = strpos($body, 'Déconnexion') !== false || strpos($body, 'logout') !== false;
echo "   Logged in: " . ($isLoggedIn ? 'YES' : 'NO') . "\n";

// 4. Envoyer la requête follow
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/users/1/follow');  // ctght id=1
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-CSRF-TOKEN: ' . $ajaxCsrf,
    'Content-Type: application/json',
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest',
]);
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $headerSize);
curl_close($ch);

echo "4. POST /users/1/follow → HTTP $httpCode\n";
echo "   Response body: " . $body . "\n";

// Cleanup - détacher si on a suivi
if ($httpCode === 200) {
    $data = json_decode($body, true);
    if ($data['following'] ?? false) {
        // Désabonner
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base . '/users/1/follow');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-CSRF-TOKEN: ' . $ajaxCsrf,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
        $r2 = curl_exec($ch);
        $c2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $b2 = substr($r2, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);
        echo "5. POST /users/1/follow (cleanup unfollow) → HTTP $c2, body: $b2\n";
    }
}
