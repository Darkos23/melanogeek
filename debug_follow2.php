<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Qui suit qui ?
echo "=== FOLLOWS TABLE ===\n";
$follows = DB::table('follows')->get();
foreach ($follows as $f) {
    $follower  = User::find($f->follower_id);
    $following = User::find($f->following_id);
    echo "follower: " . ($follower?->username ?? '?') . " (id={$f->follower_id}) → following: " . ($following?->username ?? '?') . " (id={$f->following_id})\n";
}

echo "\n=== SIMULATION HTTP FOLLOW REQUEST ===\n";
// Simuler exactement ce que fait le navigateur
// On simule une requête POST /users/1/follow en tant que babstaroi (id=3)
$admin = User::where('username', 'babstaroi')->first();
$ctght = User::where('username', 'ctght')->first();

// Authenticer l'admin manuellement
Auth::login($admin);
echo "Auth user: " . auth()->user()->username . "\n";
echo "Is staff: " . (auth()->user()->isStaff() ? 'YES' : 'NO') . "\n";

// Simuler le contrôleur FollowController::toggle
$current = auth()->user();
echo "Current user: " . $current->username . " (id=" . $current->id . ")\n";
echo "Target user: " . $ctght->username . " (id=" . $ctght->id . ")\n";
echo "Same user check: " . ($current->id === $ctght->id ? 'TRUE (blocked)' : 'FALSE (ok)') . "\n";

$isFollowing = $current->isFollowing($ctght);
echo "Is following: " . ($isFollowing ? 'YES' : 'NO') . "\n";

try {
    if ($isFollowing) {
        $current->following()->detach($ctght->id);
        $following = false;
        echo "Detached → following=false\n";
    } else {
        $current->following()->attach($ctght->id);
        $following = true;
        echo "Attached → following=true\n";
    }

    $count = $ctght->fresh()->followers()->count();
    echo "Count: " . $count . "\n";
    echo "JSON response would be: " . json_encode(['following' => $following, 'count' => $count]) . "\n";

    // Cleanup: remettre en état initial
    if ($isFollowing) {
        $current->following()->attach($ctght->id);
    } else {
        $current->following()->detach($ctght->id);
    }
    echo "Cleanup: OK (state restored)\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== CHECK PROFILE PAGE VARIABLES ===\n";
// Simuler ce que fait ProfileController::show
$viewer = $admin;
$isOwn = $viewer && $viewer->id === $ctght->id;
$isFollowing2 = $viewer && $viewer->isFollowing($ctght);
$isStaff = $viewer && $viewer->isStaff();
$isLocked = $ctght->is_private && !$isOwn && !$isFollowing2 && !$isStaff;

echo "isOwn: " . ($isOwn ? 'true' : 'false') . "\n";
echo "isFollowing: " . ($isFollowing2 ? 'true' : 'false') . "\n";
echo "isStaff: " . ($isStaff ? 'true' : 'false') . "\n";
echo "isLocked: " . ($isLocked ? 'true' : 'false') . "\n";
echo "ctght->is_private: " . ($ctght->is_private ? 'true' : 'false') . "\n";
echo "profileIsLocked JS var would be: " . ($isLocked ? 'true' : 'false') . "\n";
