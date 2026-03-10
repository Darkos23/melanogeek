<?php

namespace App\Http\Controllers\CM;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
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
}
