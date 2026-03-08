<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$creators = App\Models\User::where('role','creator')->get(['id','name','username','role','is_active','is_private']);
if ($creators->isEmpty()) {
    echo "NO CREATORS FOUND IN DB" . PHP_EOL;
} else {
    foreach ($creators as $c) {
        echo $c->name . ' | active=' . $c->is_active . ' | private=' . $c->is_private . ' | role=' . $c->role . PHP_EOL;
    }
}

// Also check what the CreatorController query returns
$query = App\Models\User::query()
    ->where('is_active', true)
    ->where('is_private', false)
    ->where('role', 'creator')
    ->withCount(['followers', 'posts']);
echo PHP_EOL . "CreatorController query result count: " . $query->count() . PHP_EOL;
