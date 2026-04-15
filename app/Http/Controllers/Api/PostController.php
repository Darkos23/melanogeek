<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    // ── Créer un post ─────────────────────────────────────────────
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'      => ['nullable', 'string', 'max:255'],
            'body'       => ['required_without:media', 'nullable', 'string', 'max:5000'],
            'category'   => ['nullable', 'string', Rule::in(array_keys(Post::CATEGORIES))],
            'media'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,mp4,mov,webm', 'max:51200'],
            'media_type' => ['nullable', 'in:image,video,text'],
        ]);

        $mediaPath = null;
        $mediaType = $request->media_type ?? 'text';

        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('posts', 'public');
            $mediaType = str_starts_with($request->file('media')->getMimeType(), 'video/') ? 'video' : 'image';
        }

        $post = $request->user()->posts()->create([
            'title'      => $request->title,
            'body'       => $request->body,
            'category'   => $request->category,
            'media_url'  => $mediaPath,
            'media_type' => $mediaType,
            'is_published' => true,
        ]);

        return response()->json(new PostResource($post->load('user')), 201);
    }

    // ── Afficher un post ──────────────────────────────────────────
    public function show(Post $post): PostResource
    {
        abort_if(! $post->is_published, 404);
        return new PostResource($post->load('user'));
    }

    // ── Like / Unlike ─────────────────────────────────────────────
    public function like(Request $request, Post $post): JsonResponse
    {
        $user  = $request->user();
        $liked = $post->likes()->where('user_id', $user->id)->exists();

        if ($liked) {
            $post->likes()->where('user_id', $user->id)->delete();
            $post->decrement('likes_count');
            $action = 'unliked';
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $post->increment('likes_count');
            $action = 'liked';
        }

        return response()->json([
            'action'      => $action,
            'likes_count' => $post->fresh()->likes_count,
        ]);
    }

    // ── Publier / Dépublier ───────────────────────────────────────
    public function publish(Request $request, Post $post): JsonResponse
    {
        abort_if($post->user_id !== $request->user()->id, 403);
        $post->update(['is_published' => ! $post->is_published]);

        return response()->json([
            'is_published' => $post->is_published,
            'message'      => $post->is_published ? 'Post publié.' : 'Post mis en brouillon.',
        ]);
    }

    // ── Supprimer un post ─────────────────────────────────────────
    public function destroy(Request $request, Post $post): JsonResponse
    {
        abort_if($post->user_id !== $request->user()->id && ! $request->user()->isAdmin(), 403);

        if ($post->media_url) {
            Storage::disk('public')->delete($post->media_url);
        }

        $post->delete();

        return response()->json(['message' => 'Post supprimé.']);
    }
}
