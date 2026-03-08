<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // IDs des utilisateurs suivis
        $followingIds = $user->following()->pluck('users.id');

        // Posts du feed (mes suivis + moi-même)
        $posts = Post::whereIn('user_id', $followingIds->push($user->id))
            ->published()
            ->with('user')
            ->latest()
            ->paginate(20);

        // Si le feed est vide, on affiche des posts découverte (créateurs uniquement)
        if ($posts->isEmpty()) {
            $posts = Post::published()
                ->with('user')
                ->whereHas('user', fn ($q) => $q->where('role', 'creator'))
                ->inRandomOrder()
                ->paginate(20);
        }

        return response()->json([
            'data' => PostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page'    => $posts->lastPage(),
                'total'        => $posts->total(),
            ],
        ]);
    }
}
