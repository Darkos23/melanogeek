<?php

namespace App\Http\Controllers\CM;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class CMController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────────

    public function dashboard()
    {
        $stats = [
            'posts_today'    => Post::whereDate('created_at', today())->count(),
            'comments_today' => Comment::whereDate('created_at', today())->count(),
            'reports_pending'=> Report::where('status', 'pending')->count(),
            'users_total'    => User::count(),
        ];

        $recentReports = Report::with(['reporter', 'post.user'])
            ->where('status', 'pending')
            ->latest()
            ->take(6)
            ->get();

        $recentPosts = Post::with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('cm.dashboard', compact('stats', 'recentReports', 'recentPosts'));
    }

    // ── Posts ─────────────────────────────────────────────────────────

    public function posts(Request $request)
    {
        $q = Post::with('user')->withTrashed();

        if ($search = $request->get('q')) {
            $q->where(fn($query) =>
                $query->where('title', 'like', "%$search%")
                      ->orWhere('body', 'like', "%$search%")
                      ->orWhereHas('user', fn($u) => $u->where('username', 'like', "%$search%"))
            );
        }

        if ($filter = $request->get('filter')) {
            match ($filter) {
                'deleted'    => $q->onlyTrashed(),
                'exclusive'  => $q->where('is_exclusive', true),
                'unpublished'=> $q->where('is_published', false),
                default      => null,
            };
        }

        $posts = $q->latest()->paginate(20)->withQueryString();

        return view('cm.posts', compact('posts'));
    }

    public function deletePost(Post $post)
    {
        ActivityLog::record('cm_delete_post', "Publication supprimée : #{$post->id} de @{$post->user->username}", 'post', $post->id);
        $post->delete();
        return back()->with('success', 'Publication supprimée.');
    }

    public function restorePost($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();
        ActivityLog::record('cm_restore_post', "Publication restaurée : #{$post->id} de @{$post->user->username}", 'post', $post->id);
        return back()->with('success', 'Publication restaurée.');
    }

    // ── Signalements ──────────────────────────────────────────────────

    public function reports(Request $request)
    {
        $q = Report::with(['reporter', 'post.user']);

        if ($status = $request->get('status', 'pending')) {
            $q->where('status', $status);
        }

        $reports = $q->latest()->paginate(20)->withQueryString();

        return view('cm.reports', compact('reports'));
    }

    public function resolveReport(Request $request, Report $report)
    {
        $request->validate([
            'action' => 'required|in:resolved,dismissed',
            'note'   => 'nullable|string|max:500',
        ]);

        $report->update([
            'status'     => $request->action,
            'admin_note' => $request->note,
        ]);

        // Si résolu → supprimer le post signalé
        if ($request->action === 'resolved' && $report->post) {
            $report->post->delete();
            ActivityLog::record('cm_resolve_report', "Signalement résolu, post supprimé : #{$report->post_id}", 'report', $report->id);
        } else {
            ActivityLog::record('cm_dismiss_report', "Signalement ignoré : #{$report->id}", 'report', $report->id);
        }

        return back()->with('success', 'Signalement traité.');
    }

    // ── Commentaires ──────────────────────────────────────────────────

    public function comments(Request $request)
    {
        $q = Comment::with(['user', 'post']);

        if ($search = $request->get('q')) {
            $q->where('body', 'like', "%$search%")
              ->orWhereHas('user', fn($u) => $u->where('username', 'like', "%$search%"));
        }

        $comments = $q->latest()->paginate(25)->withQueryString();

        return view('cm.comments', compact('comments'));
    }

    public function deleteComment(Comment $comment)
    {
        ActivityLog::record('cm_delete_comment', "Commentaire supprimé : #{$comment->id} de @{$comment->user->username}", 'comment', $comment->id);
        $comment->delete();
        return back()->with('success', 'Commentaire supprimé.');
    }

    // ── Homepage ───────────────────────────────────────────────────────

    public function homepageEdit()
    {
        $defaults = self::homepageDefaults();
        $settings = [];
        foreach ($defaults as $key => $default) {
            $settings[$key] = Setting::get($key, $default);
        }
        return view('cm.homepage', compact('settings'));
    }

    public function homepageUpdate(Request $request)
    {
        $defaults = self::homepageDefaults();
        $validated = $request->validate(
            array_fill_keys(array_keys($defaults), 'nullable|string|max:500')
        );

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? $defaults[$key]);
        }

        ActivityLog::record('cm_homepage_update', "Textes de la page d'accueil mis à jour", 'settings', null);
        return back()->with('success', 'Page d\'accueil mise à jour.');
    }

    // ── À propos ──────────────────────────────────────────────────────

    public function aboutEdit()
    {
        $fields = ['about_title', 'about_tagline', 'about_mission', 'about_story', 'about_values'];
        $settings = [];
        foreach ($fields as $key) {
            $settings[$key] = Setting::get($key, '');
        }
        return view('cm.about', compact('settings'));
    }

    public function aboutUpdate(Request $request)
    {
        $validated = $request->validate([
            'about_title'   => 'nullable|string|max:200',
            'about_tagline' => 'nullable|string|max:400',
            'about_mission' => 'nullable|string|max:2000',
            'about_story'   => 'nullable|string|max:2000',
            'about_values'  => 'nullable|string|max:2000',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        ActivityLog::record('cm_about_update', "Page À propos mise à jour", 'settings', null);
        return back()->with('success', 'Page "À propos" mise à jour.');
    }

    private static function homepageDefaults(): array
    {
        return [
            'home_hero_pill'           => 'Réseau des créateurs africains',
            'home_hero_h1_1'           => 'Ta',
            'home_hero_h1_2'           => 'créativité',
            'home_hero_h1_3'           => 'mérite une',
            'home_hero_h1_4'           => 'vraie scène.',
            'home_hero_desc'           => 'MelanoGeek connecte photographes, musiciens, artistes et stylistes sénégalais. Partage ton art, construis ta communauté. 100% gratuit pour le Sénégal.',
            'home_manifeste_eye'       => 'Notre mission',
            'home_manifeste_quote'     => 'La créativité africaine mérite une plateforme à sa',
            'home_manifeste_quote_end' => 'hauteur.',
            'home_manifeste_sub'       => "Pas d'algorithme qui punit. Pas de barrières. Juste de la création pure, une vraie communauté, et un espace où le talent sénégalais rayonne au-delà des frontières.",
            'home_val1_icon'           => '🇸🇳',
            'home_val1_title'          => '100% Local',
            'home_val1_desc'           => 'Conçu à Dakar, pour les créateurs sénégalais en premier.',
            'home_val2_icon'           => '🎁',
            'home_val2_title'          => '1 mois gratuit',
            'home_val2_desc'           => "Essai gratuit d'un mois pour tous les Sénégalais.",
            'home_val3_icon'           => '⚡',
            'home_val3_title'          => 'Sans censure',
            'home_val3_desc'           => "Ton contenu est vu par ceux qui t'ont choisi.",
            'home_val4_icon'           => '💳',
            'home_val4_title'          => 'Paiement local',
            'home_val4_desc'           => 'Wave et Orange Money. Pas besoin de carte.',
            'home_cta_eye'             => 'Rejoins la communauté',
            'home_cta_h2_1'            => 'Prêt à faire',
            'home_cta_h2_2'            => 'rayonner',
            'home_cta_h2_3'            => 'ton art ?',
            'home_cta_sub'             => 'Inscription gratuite en 2 minutes. Aucune carte requise pour les Sénégalais.',
        ];
    }
}
