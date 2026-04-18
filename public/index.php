<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// One-time cache clear — remove after deploy
$viewCache = __DIR__.'/../storage/framework/views';
if (is_dir($viewCache)) {
    foreach (glob($viewCache.'/*.php') as $f) { @unlink($f); }
}
@unlink(__DIR__.'/../bootstrap/cache/routes-v7.php');
@unlink(__DIR__.'/../bootstrap/cache/config.php');

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
