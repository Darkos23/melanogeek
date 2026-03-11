<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExploreController extends Controller
{
    public function index(Request $request): View
    {
        $query  = $request->input('q');
        $type   = $request->input('type');   // image | video | text
        $niche  = $request->input('niche');
        $sort   = $request->input('sort', 'trending'); // trending | recent

        $posts = Post::with(['user'])
            ->where('is_published', true)
            ->whereHas('user', fn ($q) => $q
                ->where('is_active', true)
                ->where('is_private', false)
            )
            ->when($query, fn ($q) =>
                $q->where(fn ($sub) =>
                    $sub->where('title', 'like', "%{$query}%")
                        ->orWhere('body', 'like', "%{$query}%")
                )
            )
            ->when($type, fn ($q) => $q->where('media_type', $type))
            ->when($niche, fn ($q) =>
                $q->whereHas('user', fn ($u) => $u->where('niche', $niche))
            )
            ->when($sort === 'trending',
                fn ($q) => $q->orderByDesc('likes_count')->orderByDesc('comments_count'),
                fn ($q) => $q->latest()
            )
            ->paginate(24)
            ->withQueryString();

        // Niches disponibles pour les filtres
        $niches = User::where('is_active', true)
            ->where('is_private', false)
            ->whereNotNull('niche')
            ->distinct()
            ->pluck('niche')
            ->sort()
            ->values();

        // Utilisateurs avec des stories actives (requête directe sur users, plus efficace)
        $storyUsers = User::select('id', 'name', 'username', 'avatar', 'is_verified')
            ->whereHas('stories', fn ($q) => $q->where('expires_at', '>', now()))
            ->where('is_active', true)
            ->where('is_private', false)
            ->limit(30)
            ->get();

        return view('explore.index', compact('posts', 'niches', 'query', 'type', 'niche', 'sort', 'storyUsers'));
    }
}
