<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->id === $user->id) {
            return response()->json(['message' => 'Vous ne pouvez pas vous suivre vous-même.'], 422);
        }

        if ($authUser->isFollowing($user)) {
            $authUser->following()->detach($user->id);
            $action = 'unfollowed';
        } else {
            $authUser->following()->attach($user->id);
            $action = 'followed';
        }

        return response()->json([
            'action'          => $action,
            'followers_count' => $user->fresh()->followers()->count(),
        ]);
    }
}
