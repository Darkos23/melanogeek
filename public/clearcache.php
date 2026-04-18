<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

Artisan::call('view:clear');
Artisan::call('cache:clear');
Artisan::call('config:clear');
Artisan::call('route:clear');

echo '<pre style="background:#111;color:#8f8;padding:20px">✅ Caches vidés.</pre>';
