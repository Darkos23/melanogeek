<?php

namespace App\Http\Controllers;

use App\Models\ForumThread;
use App\Models\Post;
use App\Models\SiteVisit;
use App\Models\User;
use App\Models\Comment;

class HomeController extends Controller
{
    public function index()
    {
        // Articles à la une : derniers posts publiés avec un titre
        $featured = Post::with('user')
            ->published()
            ->whereNotNull('title')
            ->where('title', '!=', '')
            ->latest()
            ->first();

        $side_posts = Post::with('user')
            ->published()
            ->whereNotNull('title')
            ->where('title', '!=', '')
            ->latest()
            ->when($featured, fn($q) => $q->where('id', '!=', $featured->id))
            ->take(3)
            ->get();

        // Discussions actives : posts les plus commentés
        $discussions = Post::with('user')
            ->published()
            ->orderByDesc('comments_count')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Comptage par catégorie
        $category_counts = Post::published()
            ->whereNotNull('category')
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Stats globales
        $stats = [
            'users'    => User::count(),
            'posts'    => Post::published()->count(),
            'comments' => Comment::count(),
            'visits'   => rescue(fn() => SiteVisit::total(), 0, false),
        ];

        $forum_cat_counts = ForumThread::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Activité récente pour le ticker
        $recentPosts   = Post::with('user')->published()->whereNotNull('title')->latest()->take(6)->get();
        $recentThreads = ForumThread::with('user')->latest()->take(4)->get();

        return view('welcome', compact(
            'featured', 'side_posts', 'discussions', 'stats',
            'category_counts', 'forum_cat_counts',
            'recentPosts', 'recentThreads'
        ));
    }
}
