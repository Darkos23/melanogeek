<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendNewPostNotifications;
use App\Models\Post;
use App\Notifications\PostApprovedNotification;
use App\Notifications\PostRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostAdminController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all'); // 'all' | 'pending'

        $query = Post::with('user')
            ->when($request->search, fn($q) => $q->where('title', 'like', '%'.Str::escapeLike($request->search).'%'));

        if ($tab === 'pending') {
            $query->pending();
        } else {
            $query->when(
                $request->has('published') && $request->published !== '',
                fn($q) => $q->where('is_published', $request->published)
            );
        }

        $posts        = $query->latest()->paginate(25)->withQueryString();
        $pendingCount = Post::pending()->count();

        return view('admin.posts.index', compact('posts', 'tab', 'pendingCount'));
    }

    public function toggle(Post $post)
    {
        $post->update(['is_published' => !$post->is_published]);
        return back()->with('success', 'Publication mise à jour.');
    }

    public function approve(Post $post)
    {
        $post->update([
            'is_published'   => true,
            'pending_review' => false,
            'rejection_reason' => null,
        ]);

        // Notifier l'auteur
        $post->user->notify(new PostApprovedNotification($post));

        // Notifier les abonnés
        SendNewPostNotifications::dispatch($post);

        return back()->with('success', "Article « {$post->title} » approuvé et publié.");
    }

    public function reject(Post $post)
    {
        $reason = request('reason', '');

        $post->update([
            'is_published'     => false,
            'pending_review'   => false,
            'rejection_reason' => $reason,
        ]);

        // Notifier l'auteur
        $post->user->notify(new PostRejectedNotification($post, $reason));

        return back()->with('success', "Article rejeté et auteur notifié.");
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Publication supprimée.');
    }
}
