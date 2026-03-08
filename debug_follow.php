<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Tous les users
$users = User::withTrashed()->get(['id','username','role','deleted_at']);
echo "=== USERS ===\n";
foreach ($users as $u) {
    echo $u->id . ' | ' . str_pad($u->username, 14) . ' | ' . str_pad($u->role, 8) . ' | deleted=' . ($u->deleted_at ? 'YES' : 'no') . "\n";
}

echo "\n=== TEST FOLLOW ===\n";
// Admin = babstaroi (id=3), tester avec ctght (creator)
$admin  = User::where('username', 'babstaroi')->first();
$ctght  = User::where('username', 'ctght')->first();

if (!$admin) { echo "Admin not found\n"; exit; }
if (!$ctght) { echo "Ctght not found\n"; exit; }

echo "Admin: " . $admin->username . " (id=" . $admin->id . ", role=" . $admin->role . ")\n";
echo "Target: " . $ctght->username . " (id=" . $ctght->id . ", role=" . $ctght->role . ")\n";

$isFollowing = $admin->isFollowing($ctght);
echo "Admin is following ctght: " . ($isFollowing ? 'YES' : 'NO') . "\n";

// Test de la table follows
try {
    $count = DB::table('follows')->count();
    echo "Follows table rows: " . $count . "\n";
} catch (Exception $e) {
    echo "Follows table ERROR: " . $e->getMessage() . "\n";
}

// Simuler le toggle
try {
    if ($isFollowing) {
        $admin->following()->detach($ctght->id);
        echo "Detach: OK\n";
        $admin->following()->attach($ctght->id);
        echo "Re-attach: OK\n";
    } else {
        $admin->following()->attach($ctght->id);
        echo "Attach: OK\n";
        $admin->following()->detach($ctght->id);
        echo "Detach: OK\n";
    }
    $newCount = $ctght->followers()->count();
    echo "Ctght followers after test: " . $newCount . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

// Vérifier le log Laravel
echo "\n=== LARAVEL LOG (last 30 lines) ===\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = file($logFile);
    $total = count($lines);
    $start = max(0, $total - 30);
    for ($i = $start; $i < $total; $i++) {
        echo $lines[$i];
    }
} else {
    echo "No log file found\n";
}
