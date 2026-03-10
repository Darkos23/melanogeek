<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'members'          => User::where('is_active', true)->where('role', '!=', 'owner')->count(),
            'creators'         => User::where('role', 'creator')->count(),
            'posts'            => Post::where('is_published', true)->whereHas('user', fn($q) => $q->where('role', 'creator')->where('is_active', true))->count(),
            'countries'        => User::where('is_active', true)->whereNotNull('country_type')->distinct('country_type')->count('country_type'),
            'new_members_month'=> User::where('is_active', true)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        $allCreators = User::where('role', 'creator')
            ->where('is_active', true)
            ->withCount(['followers', 'posts as published_posts' => fn($q) => $q->where('is_published', true)])
            ->orderByDesc('followers_count')
            ->limit(10)
            ->get();

        // Afficher la carte "Top créateur" dès qu'il y a au moins 1 abonné
        $topCreator       = $allCreators->first()?->followers_count >= 1
            ? $allCreators->first()
            : null;
        $featuredCreators = $allCreators; // tous dans la grille, y compris le top créateur

        $stories = [
            ['emoji' => '👩🏾', 'name' => 'Aminata'],
            ['emoji' => '🎵',  'name' => 'DjibrilBeat'],
            ['emoji' => '📸',  'name' => 'LensOfDakar'],
            ['emoji' => '🎨',  'name' => 'KhoulayArt'],
        ];

        $previewPosts = [
            [
                'emoji'    => '🎧',
                'username' => 'SoundKhëwël',
                'color'    => '#2D1B0E',
                'imgClass' => 'post-img-1',
                'category' => 'Musique',
                'title'    => 'Nouveau EP "Teranga Vibes" 🔥',
                'time'     => 'Il y a 2h',
                'city'     => 'Dakar',
                'likes'    => 2100,
                'comments' => 348,
            ],
            [
                'emoji'    => '📸',
                'username' => 'LensOfDakar',
                'color'    => '#1A2A1E',
                'imgClass' => 'post-img-2',
                'category' => 'Photo',
                'title'    => 'Coucher de soleil sur le Fleuve',
                'time'     => 'Il y a 5h',
                'city'     => 'Saint-Louis',
                'likes'    => 4700,
                'comments' => 512,
                'tagColor' => '#2D5A3D',
            ],
        ];

        $explorePosts = [
            ['emoji' => '🎵', 'title' => 'Afrobeat Session Vol.3',      'handle' => 'djkheweul',      'views' => 12000],
            ['emoji' => '📸', 'title' => 'Portraits de Dakar',           'handle' => 'lensofsenegal',  'views' => 8400],
            ['emoji' => '🎨', 'title' => 'Art digital Sénégalais',       'handle' => 'pixelxolof',     'views' => 21000],
            ['emoji' => '👗', 'title' => 'Collection Wax 2025',          'handle' => 'modedakar',      'views' => 6200],
            ['emoji' => '🎬', 'title' => 'Court-métrage: Nit Nitay',     'handle' => 'cinemateranga',  'views' => 34000],
            ['emoji' => '🍽️', 'title' => 'Recette Thiéboudienne',        'handle' => 'cuisinewolof',   'views' => 19000],
            ['emoji' => '✍️', 'title' => 'Poésie Wolof moderne',         'handle' => 'versesdusalaar', 'views' => 5100],
            ['emoji' => '💃🏾','title' => 'Sabar Dance Tutorial',         'handle' => 'sabartv',        'views' => 45000],
            ['emoji' => '🌿', 'title' => 'Architecture de Ziguinchor',   'handle' => 'archafrique',    'views' => 11000],
        ];

        $features = [
            ['icon' => '🎨', 'bg' => 'rgba(200,82,42,0.12)',   'title' => 'Profil Créateur Pro',    'desc' => 'Un portfolio complet avec galerie, statistiques d\'audience et liens de contact en temps réel.'],
            ['icon' => '💸', 'bg' => 'rgba(212,168,67,0.12)',  'title' => 'Monétisation Intégrée',  'desc' => 'Reçois des paiements via Wave, Orange Money ou carte. Les abonnés étrangers paient en euros.'],
            ['icon' => '🌍', 'bg' => 'rgba(45,90,61,0.2)',     'title' => 'Portée Africaine',       'desc' => 'Connecte-toi avec des créateurs de toute l\'Afrique. Une communauté panafricaine soudée.'],
            ['icon' => '📊', 'bg' => 'rgba(200,82,42,0.12)',   'title' => 'Analytics Avancés',      'desc' => 'Visualise tes stats, tes pics d\'engagement et les pays où ton contenu performe.'],
            ['icon' => '💬', 'bg' => 'rgba(212,168,67,0.12)',  'title' => 'Messagerie & Collab',    'desc' => 'Messagerie chiffrée et outils de collaboration pour monter des projets avec d\'autres créateurs.'],
            ['icon' => '🔒', 'bg' => 'rgba(100,100,200,0.12)', 'title' => 'Contenu Exclusif',       'desc' => 'Crée des espaces privés pour tes abonnés premium. Contrôle qui voit quoi sur ton profil.'],
        ];

        return view('welcome', compact('stats', 'stories', 'previewPosts', 'explorePosts', 'features', 'featuredCreators', 'topCreator'));
    }
}