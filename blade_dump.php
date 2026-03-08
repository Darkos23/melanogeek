<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();
$compiled = app('blade.compiler')->compileString(file_get_contents('resources/views/profile/show.blade.php'));
// Write to a real PHP file
$tmpFile = 'C:/Users/K.L/AppData/Local/Temp/blade_full.php';
file_put_contents($tmpFile, $compiled);
echo "Written " . strlen($compiled) . " bytes, " . substr_count($compiled, "\n") . " lines\n";
// Show last 5 lines
$lines = file($tmpFile);
$total = count($lines);
echo "Actual line count from file(): $total\n";
for ($i = max(0,$total-5); $i < $total; $i++) {
    echo ($i+1) . ": " . rtrim($lines[$i]) . "\n";
}
