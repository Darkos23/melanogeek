<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        // Stats de la plateforme
        $stats = [
            'users_count'    => User::where('is_active', true)->count(),
            'creators_count' => User::where('role', 'creator')->count(),
            'posts_count'    => Post::published()->count(),
        ];

        // Créateurs mis en avant
        $featuredCreators = User::where('role', 'creator')
            ->where('is_active', true)
            ->withCount('followers')
            ->orderByDesc('followers_count')
            ->limit(6)
            ->get();

        // Posts récents en vedette
        $latestPosts = Post::published()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'stats'            => $stats,
            'featured_creators'=> UserResource::collection($featuredCreators),
            'latest_posts'     => PostResource::collection($latestPosts),
        ]);
    }
}
