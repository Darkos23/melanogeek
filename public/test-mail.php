<?php
/**
 * Mail test utility — DELETE THIS FILE after use!
 */
$secret = $_GET['key'] ?? '';
if ($secret !== 'mg_mail_2025') {
    http_response_code(403);
    die('Forbidden. Usage: /test-mail.php?key=mg_mail_2025&to=ton@email.com');
}

$to = $_GET['to'] ?? '';
if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
    die('Usage: /test-mail.php?key=mg_mail_2025&to=ton@email.com');
}

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo '<pre style="font-family:monospace;font-size:14px;padding:20px;background:#1a1a1a;color:#f0f0f0;line-height:1.8">';
echo '<b style="color:#C4A254">MelanoGeek — Mail Test</b>' . "\n\n";

$mailer  = env('MAIL_MAILER', '?');
$host    = env('MAIL_HOST', '?');
$port    = env('MAIL_PORT', '?');
$user    = env('MAIL_USERNAME', '?');
$from    = env('MAIL_FROM_ADDRESS', '?');
$enc     = env('MAIL_ENCRYPTION', '?');

echo "Config actuelle :\n";
echo "  MAIL_MAILER     = $mailer\n";
echo "  MAIL_HOST       = $host\n";
echo "  MAIL_PORT       = $port\n";
echo "  MAIL_USERNAME   = $user\n";
echo "  MAIL_FROM       = $from\n";
echo "  MAIL_ENCRYPTION = $enc\n\n";

echo "Envoi d'un email de test vers : $to\n";
echo str_repeat('-', 50) . "\n";

try {
    Illuminate\Support\Facades\Mail::raw(
        "Ceci est un email de test MelanoGeek.\n\nSi tu reçois ce message, la configuration SMTP fonctionne !",
        function ($message) use ($to) {
            $message->to($to)->subject('[MelanoGeek] Test email ✓');
        }
    );
    echo "\n✅ SUCCESS — Email envoyé sans erreur !\n";
    echo "Vérifie ta boîte mail (et le dossier spam).\n";
    if (env('MAIL_MAILER') === 'log') {
        echo "\n⚠ Note: MAIL_MAILER=log — l'email est dans storage/logs/laravel.log, pas envoyé réellement.\n";
    }
} catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
    echo "\n❌ ERREUR SMTP : " . $e->getMessage() . "\n";
    echo "\nSolution : Vérifier les credentials SMTP dans .env\n";
} catch (\Exception $e) {
    echo "\n❌ ERREUR : " . get_class($e) . "\n";
    echo $e->getMessage() . "\n";
    echo "\nFile: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo '</pre>';
