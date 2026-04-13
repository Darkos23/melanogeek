<?php
/**
 * Cache clearing utility — DELETE THIS FILE after use!
 */
if (php_sapi_name() === 'cli' || $_SERVER['HTTP_HOST'] ?? '' === 'localhost') {
    die('Access denied.');
}

$secret = $_GET['key'] ?? '';
if ($secret !== 'mg_clear_2025') {
    http_response_code(403);
    die('Forbidden. Usage: /clear-cache.php?key=mg_clear_2025');
}

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$results = [];

// Config cache
try {
    Artisan::call('config:clear');
    $results[] = '✅ Config cache cleared';
} catch (Exception $e) {
    $results[] = '❌ Config cache: ' . $e->getMessage();
}

// App cache
try {
    Artisan::call('cache:clear');
    $results[] = '✅ App cache cleared';
} catch (Exception $e) {
    $results[] = '❌ App cache: ' . $e->getMessage();
}

// View cache
try {
    Artisan::call('view:clear');
    $results[] = '✅ View cache cleared';
} catch (Exception $e) {
    $results[] = '❌ View cache: ' . $e->getMessage();
}

// Route cache
try {
    Artisan::call('route:clear');
    $results[] = '✅ Route cache cleared';
} catch (Exception $e) {
    $results[] = '❌ Route cache: ' . $e->getMessage();
}

// Check storage permissions
$storagePath = __DIR__ . '/../storage';
$results[] = '';
$results[] = '📁 Storage writable: ' . (is_writable($storagePath) ? '✅ Yes' : '❌ NO — fix permissions!');
$results[] = '📁 Storage/logs writable: ' . (is_writable($storagePath . '/logs') ? '✅ Yes' : '❌ NO');
$results[] = '📁 Bootstrap/cache writable: ' . (is_writable(__DIR__ . '/../bootstrap/cache') ? '✅ Yes' : '❌ NO');

// Mail config check
$mailer = env('MAIL_MAILER', 'not set');
$mailHost = env('MAIL_HOST', 'not set');
$mailUser = env('MAIL_USERNAME', 'not set');
$results[] = '';
$results[] = '📧 MAIL_MAILER: ' . $mailer;
$results[] = '📧 MAIL_HOST: ' . $mailHost;
$results[] = '📧 MAIL_USERNAME: ' . $mailUser;
$results[] = '📧 APP_KEY set: ' . (env('APP_KEY') ? '✅ Yes' : '❌ NO');
$results[] = '📧 APP_URL: ' . env('APP_URL', 'not set');

echo '<pre style="font-family:monospace;font-size:14px;padding:20px;background:#1a1a1a;color:#f0f0f0;line-height:1.8">';
echo '<b style="color:#C4A254">MelanoGeek — Cache Utility</b>' . "\n\n";
foreach ($results as $r) {
    echo htmlspecialchars($r) . "\n";
}
echo "\n<span style='color:#aaa'>⚠ Delete this file after use: /public/clear-cache.php</span>";
echo '</pre>';
