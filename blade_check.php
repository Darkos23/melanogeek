<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();
$compiled = app('blade.compiler')->compileString(file_get_contents('resources/views/profile/show.blade.php'));
file_put_contents(sys_get_temp_dir() . '/blade_debug.php', $compiled);
echo 'Lines: ' . substr_count($compiled, "\n") . PHP_EOL;
echo 'Written.';
