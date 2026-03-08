<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\FollowNotification;
use Illuminate\Http\JsonResponse;

class FollowController extends Controller
{
    public function toggle(User $user): JsonResponse
    {
        $current = auth()->user();

        if ($current->id === $user->id) {
            return response()->json(['error' => 'Action impossible'], 422);
        }

        if ($current->isFollowing($user)) {
            $current->following()->detach($user->id);
            $following = false;
        } else {
            $current->following()->attach($user->id);
            $following = true;

            // Notifier l'utilisateur suivi
            $user->notify(new FollowNotification($current));
        }

        return response()->json([
            'following' => $following,
            'count'     => $user->followers()->count(),
        ]);
    }
}
