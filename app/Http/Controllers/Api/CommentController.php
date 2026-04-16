<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // ── Liste des commentaires d'un post ──────────────────────────
    public function index(Post $post): JsonResponse
    {
        $comments = $post->comments()
            ->with('user:id,name,username,avatar,is_verified,role')
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $comments->map(fn($c) => [
                'id'         => $c->id,
                'body'       => $c->body,
                'created_at' => $c->created_at->toIso8601String(),
                'user'       => [
                    'id'          => $c->user->id,
                    'name'        => $c->user->name,
                    'username'    => $c->user->username,
                    'avatar'      => $c->user->avatar ? asset('storage/' . $c->user->avatar) : null,
                    'is_verified' => $c->user->is_verified,
                    'role'        => $c->user->role,
                ],
            ]),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page'    => $comments->lastPage(),
                'total'        => $comments->total(),
            ],
        ]);
    }

    // ── Ajouter un commentaire ────────────────────────────────────
    public function store(Request $request, Post $post): JsonResponse
    {
        abort_if(! $post->is_published, 404);

        $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $request->body,
        ]);

        $post->increment('comments_count');

        // Notifier l'auteur du post (pas de notification sur son propre post)
        if ($post->user_id !== $request->user()->id) {
            $post->user->notify(new CommentNotification($request->user(), $post, $comment));
        }

        $comment->load('user:id,name,username,avatar,is_verified,role');

        return response()->json([
            'id'         => $comment->id,
            'body'       => $comment->body,
            'created_at' => $comment->created_at->toIso8601String(),
            'user'       => [
                'id'          => $comment->user->id,
                'name'        => $comment->user->name,
                'username'    => $comment->user->username,
                'avatar'      => $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : null,
                'is_verified' => $comment->user->is_verified,
                'role'        => $comment->user->role,
            ],
        ], 201);
    }

    // ── Modifier un commentaire ───────────────────────────────────
    public function update(Request $request, Comment $comment): JsonResponse
    {
        abort_if($comment->user_id !== $request->user()->id, 403);

        $request->validate(['body' => ['required', 'string', 'max:1000']]);
        $comment->update(['body' => $request->body]);

        return response()->json(['id' => $comment->id, 'body' => $comment->body]);
    }

    // ── Supprimer un commentaire ──────────────────────────────────
    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        abort_if(
            $comment->user_id !== $request->user()->id && ! $request->user()->isAdmin(),
            403
        );

        $comment->post->decrement('comments_count');
        $comment->delete();

        return response()->json(['message' => 'Commentaire supprimé.']);
    }
}
