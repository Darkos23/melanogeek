<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');   // all | month | week
        $niche  = $request->get('niche');

        $query = User::query()
            ->where('is_active', true)
            ->where('role', 'creator')
            ->withCount([
                'followers',
                'posts as published_posts' => fn($q) => $q->where('is_published', true),
            ]);

        // Abonnés gagnés sur la période sélectionnée
        if ($period === 'week') {
            $since = now()->subDays(7);
            $query->withCount([
                'followers as period_followers' => fn($q) => $q->where('follows.created_at', '>=', $since),
            ])->orderByDesc('period_followers');
        } elseif ($period === 'month') {
            $since = now()->subDays(30);
            $query->withCount([
                'followers as period_followers' => fn($q) => $q->where('follows.created_at', '>=', $since),
            ])->orderByDesc('period_followers');
        } else {
            $query->orderByDesc('followers_count');
        }

        if ($niche) {
            $query->where('niche', $niche);
        }

        $creators = $query->limit(50)->get();

        // Podium = top 3
        $podium   = $creators->take(3);
        $rest     = $creators->skip(3);

        $niches = collect([
            'Musique', 'Photographie', 'Mode & Style', 'Beauté & Soins',
            'Cuisine', 'Vidéo & Vlog', 'Art & Illustration', 'Danse',
            'Comédie & Humour', 'Business', 'Voyage & Culture', 'Sport & Fitness',
            'Artisanat', 'Éducation', 'Podcast', 'Lifestyle',
        ]);

        return view('ranking.index', compact('creators', 'podium', 'rest', 'niches', 'period', 'niche'));
    }
}
