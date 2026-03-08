<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->get('q');

        if ($query) {
            // Recherche dans les posts (créateurs uniquement)
            $posts = Post::published()
                ->with('user')
                ->whereHas('user', fn ($q) => $q->where('role', 'creator'))
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('body', 'like', "%{$query}%");
                })
                ->latest()
                ->paginate(20);

            // Recherche dans les créateurs uniquement
            $users = User::where('is_active', true)
                ->where('role', 'creator')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('username', 'like', "%{$query}%")
                      ->orWhere('niche', 'like', "%{$query}%");
                })
                ->withCount('followers')
                ->limit(10)
                ->get();

            return response()->json([
                'posts' => PostResource::collection($posts),
                'users' => UserResource::collection($users),
                'query' => $query,
            ]);
        }

        // Sans recherche : posts tendances (créateurs uniquement)
        $posts = Post::published()
            ->with('user')
            ->whereHas('user', fn ($q) => $q->where('role', 'creator'))
            ->orderByDesc('likes_count')
            ->orderByDesc('comments_count')
            ->paginate(20);

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
