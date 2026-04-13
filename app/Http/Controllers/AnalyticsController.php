<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // ── Stats globales ──────────────────────────────────────────
        $totalPosts     = $user->posts()->where('is_published', true)->count();
        $totalDrafts    = $user->posts()->where('is_published', false)->count();
        $totalLikes     = $user->posts()->sum('likes_count');
        $totalComments  = $user->posts()->sum('comments_count');
        $totalFollowers = $user->followers()->count();
        $totalFollowing = $user->following()->count();

        // ── Posts par mois (6 derniers mois) ────────────────────────
        $monthlyPosts = $user->posts()
            ->where('is_published', true)
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . str_pad($r->month, 2, '0', STR_PAD_LEFT));

        // Construire un tableau complet des 6 derniers mois
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i)->startOfMonth();
            $key  = $date->format('Y-m');
            $months[] = [
                'label' => $date->translatedFormat('M Y'),
                'count' => $monthlyPosts->get($key)?->count ?? 0,
            ];
        }

        // ── Top 5 posts les plus likés ───────────────────────────────
        $topPosts = $user->posts()
            ->where('is_published', true)
            ->orderByDesc('likes_count')
            ->limit(5)
            ->get(['id', 'content', 'likes_count', 'comments_count', 'created_at', 'media_type']);

        return view('analytics.index', compact(
            'totalPosts', 'totalDrafts', 'totalLikes', 'totalComments',
            'totalFollowers', 'totalFollowing',
            'months', 'topPosts'
        ));
    }
}
