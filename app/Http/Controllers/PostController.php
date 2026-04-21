<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Jobs\SendNewPostNotifications;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostMedia;
use App\Notifications\LikeNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostController extends Controller
{
    public function create(): View
    {
        $postCategories = Post::CATEGORIES;
        return view('posts.create', compact('postCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['nullable', 'string', 'max:150'],
            'body'         => ['nullable', 'string', 'max:5000'],
            'category'     => ['nullable', 'string', Rule::in(array_keys(Post::CATEGORIES))],
            'images'       => ['nullable', 'array', 'max:10'],
            'images.*'     => ['file', 'image', 'mimetypes:image/jpeg,image/png,image/gif,image/webp', 'max:20480'],
            'media'        => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:51200'],
            'thumbnail'    => ['nullable', 'file', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
            'audio'        => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/ogg,audio/wav,audio/mp4,audio/x-m4a', 'max:20480'],
            'youtube_url'  => ['nullable', 'string', 'max:255'],
            'is_published'  => ['nullable', 'boolean'],
        ], [
            'images.max'      => 'Tu peux ajouter au maximum 10 images.',
            'images.*.image'  => 'Seuls les formats JPG, PNG, GIF et WEBP sont acceptés.',
            'images.*.max'    => 'Chaque image ne doit pas dépasser 20 Mo.',
            'media.mimetypes' => 'Seuls les formats MP4, MOV et WEBM sont acceptés pour les vidéos.',
            'media.max'       => 'La vidéo ne doit pas dépasser 50 Mo.',
            'audio.mimetypes' => 'Seuls les formats MP3, OGG, WAV et M4A sont acceptés.',
            'audio.max'       => 'L\'audio ne doit pas dépasser 20 Mo.',
            'body.max'        => 'Le texte ne doit pas dépasser 5000 caractères.',
            'title.max'       => 'Le titre ne doit pas dépasser 150 caractères.',
        ]);

        $hasImages = $request->hasFile('images');
        $hasVideo  = $request->hasFile('media');
        $hasAudio  = $request->hasFile('audio');

        if (empty($data['title']) && empty($data['body']) && ! $hasImages && ! $hasVideo) {
            return back()->withErrors(['body' => 'Ajoute au moins un texte ou un média.'])->withInput();
        }

        $mediaUrl     = null;
        $mediaType    = 'text';
        $audioUrl     = null;
        $audioName    = null;
        $thumbnailUrl = null;

        // Vidéo (toujours une seule)
        if ($hasVideo) {
            $mediaUrl  = $request->file('media')->store('posts/videos', 'public');
            $mediaType = 'video';
            // Thumbnail optionnel pour les vidéos
            if ($request->hasFile('thumbnail')) {
                $raw = $request->file('thumbnail')->store('posts/thumbnails', 'public');
                $thumbnailUrl = ImageHelper::cropAndResizePath($raw);
            }
        }

        // Audio de fond
        if ($hasAudio) {
            $audioFile = $request->file('audio');
            $audioUrl  = $audioFile->store('posts/audio', 'public');
            $audioName = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME);
        }

        // Thumbnail pour les posts texte/article (hors vidéo déjà traité au-dessus)
        if (! $hasVideo && $request->hasFile('thumbnail')) {
            $raw = $request->file('thumbnail')->store('posts/thumbnails', 'public');
            $thumbnailUrl = ImageHelper::cropAndResizePath($raw);
        }

        $isAdmin       = $request->user()->isAdminOrOwner();
        $pendingReview = ! $isAdmin; // Les non-admins passent en attente de validation

        $post = $request->user()->posts()->create([
            'title'          => $data['title'] ?? null,
            'body'           => $data['body'] ?? null,
            'category'       => $data['category'] ?? null,
            'media_url'      => $mediaUrl,
            'media_type'     => $mediaType,
            'thumbnail'      => $thumbnailUrl,
            'audio_url'      => $audioUrl,
            'audio_name'     => $audioName,
            'youtube_url'    => $data['youtube_url'] ?? null,
            'is_published'   => $isAdmin ? $request->boolean('is_published', true) : false,
            'pending_review' => $pendingReview,
        ]);

        // Images multiples → post_media
        if ($hasImages) {
            foreach ($request->file('images') as $order => $file) {
                PostMedia::create([
                    'post_id'    => $post->id,
                    'media_url'  => $file->store('posts/images', 'public'),
                    'sort_order' => $order,
                ]);
            }
        }

        // Notifier les abonnés si le post est publié directement (admin)
        if ($post->is_published && ! $pendingReview) {
            SendNewPostNotifications::dispatch($post);
        }

        // Notifier les admins qu'un article est en attente de validation
        if ($pendingReview) {
            $admins = \App\Models\User::whereIn('role', ['admin', 'owner'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\PostPendingNotification($post));
            }
        }

        $status = $pendingReview ? 'post-pending' : 'post-created';
        return redirect()->route('posts.show', $post->id)
                         ->with('status', $status);
    }

    public function show(Post $post): View
    {
        $post->load(['user', 'mediaFiles']);

        // Compter la vue — une fois par session, jamais pour l'auteur lui-même
        $sessionKey = 'viewed_post_' . $post->id;
        if (! session()->has($sessionKey) && auth()->id() !== $post->user_id) {
            try { $post->increment('views_count'); } catch (\Throwable $e) {}
            session()->put($sessionKey, true);
        }

        $liked = auth()->check()
            ? Like::where('user_id', auth()->id())->where('post_id', $post->id)->exists()
            : false;

        return view('posts.show', compact('post', 'liked'));
    }

    public function data(Post $post): JsonResponse
    {
        $post->load(['user', 'mediaFiles']);

        $viewer = auth()->user();
        $liked  = $viewer
            ? Like::where('user_id', $viewer->id)->where('post_id', $post->id)->exists()
            : false;

        return response()->json([
            'id'             => $post->id,
            'title'          => $post->title,
            'body'           => $post->body,
            'category'       => $post->category,
            'category_label' => $post->category_label,
            'media_url'      => $post->media_url ? Storage::disk('public')->url($post->media_url) : null,
            'media_type'     => $post->media_type,
            'audio_url'      => $post->audio_url ? Storage::disk('public')->url($post->audio_url) : null,
            'audio_name'     => $post->audio_name,
            'likes_count'    => $post->likes_count,
            'comments_count' => $post->comments_count,
            'liked'          => $liked,
            'created_at'     => $post->created_at->diffForHumans(),
            'media_files'    => $post->mediaFiles->map(fn ($m) => Storage::disk('public')->url($m->media_url))->values(),
            'post_url'       => route('posts.show', $post->id),
            'user'           => [
                'name'        => $post->user->name,
                'username'    => $post->user->username,
                'avatar'      => $post->user->avatar ? Storage::disk('public')->url($post->user->avatar) : null,
                'profile_url' => route('profile.show', $post->user->username),
                'is_verified' => (bool) ($post->user->is_verified ?? false),
            ],
        ]);
    }

    public function like(Post $post): JsonResponse
    {
        $user = auth()->user();
        $like = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            Like::create(['user_id' => $user->id, 'post_id' => $post->id]);
            $post->increment('likes_count');
            $liked = true;

            // Notifier l'auteur du post (pas de notification sur son propre post)
            if ($post->user_id !== $user->id) {
                $post->user->notify(new LikeNotification($user, $post));
            }
        }

        return response()->json(['liked' => $liked, 'count' => $post->fresh()->likes_count]);
    }

    public function edit(Post $post): View
    {
        abort_if(auth()->id() !== $post->user_id, 403);
        $postCategories = Post::CATEGORIES;
        return view('posts.edit', compact('post', 'postCategories'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        abort_if(auth()->id() !== $post->user_id, 403);

        $data = $request->validate([
            'title'       => ['nullable', 'string', 'max:150'],
            'body'        => ['nullable', 'string', 'max:5000'],
            'category'    => ['nullable', 'string', Rule::in(array_keys(Post::CATEGORIES))],
            'thumbnail'   => ['nullable', 'file', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
            'youtube_url' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) Storage::disk('public')->delete($post->thumbnail);
            $raw = $request->file('thumbnail')->store('posts/thumbnails', 'public');
            $data['thumbnail'] = ImageHelper::cropAndResizePath($raw);
        }

        $update = ['title' => $data['title'] ?? null, 'body' => $data['body'] ?? null, 'category' => $data['category'] ?? null, 'youtube_url' => $data['youtube_url'] ?? null];
        if (isset($data['thumbnail']) && is_string($data['thumbnail'])) {
            $update['thumbnail'] = $data['thumbnail'];
        }
        $post->update($update);

        return redirect()->route('posts.show', $post->id)->with('status', 'post-updated');
    }

    public function publish(Post $post): RedirectResponse
    {
        abort_if(auth()->id() !== $post->user_id, 403);
        $post->update(['is_published' => true]);
        SendNewPostNotifications::dispatch($post);
        return redirect()->route('posts.show', $post->id)->with('status', 'post-published');
    }

    public function destroy(Post $post): RedirectResponse
    {
        abort_if(auth()->id() !== $post->user_id && !auth()->user()->isAdmin(), 403);

        // Delete associated media files
        foreach ($post->mediaFiles as $media) {
            Storage::disk('public')->delete($media->media_url);
        }
        $post->mediaFiles()->delete();

        if ($post->media_url) Storage::disk('public')->delete($post->media_url);
        if ($post->thumbnail) Storage::disk('public')->delete($post->thumbnail);
        if ($post->audio_url) Storage::disk('public')->delete($post->audio_url);

        $post->delete();

        return redirect()->route('profile.show', auth()->user()->username)
                         ->with('status', 'post-deleted');
    }
}
