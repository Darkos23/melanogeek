<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\PostMedia;
use App\Notifications\LikeNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    public function create(): View
    {
        return view('posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['nullable', 'string', 'max:150'],
            'body'         => ['nullable', 'string', 'max:5000'],
            'images'       => ['nullable', 'array', 'max:10'],
            'images.*'     => ['file', 'image', 'mimetypes:image/jpeg,image/png,image/gif,image/webp', 'max:20480'],
            'media'        => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:51200'],
            'thumbnail'    => ['nullable', 'file', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
            'audio'        => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/ogg,audio/wav,audio/mp4,audio/x-m4a', 'max:20480'],
            'is_published'  => ['nullable', 'boolean'],
            'is_exclusive'  => ['nullable', 'boolean'],
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
                $thumbnailUrl = $request->file('thumbnail')->store('posts/thumbnails', 'public');
            }
        }

        // Audio de fond
        if ($hasAudio) {
            $audioFile = $request->file('audio');
            $audioUrl  = $audioFile->store('posts/audio', 'public');
            $audioName = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME);
        }

        $post = $request->user()->posts()->create([
            'title'        => $data['title'] ?? null,
            'body'         => $data['body'] ?? null,
            'media_url'    => $mediaUrl,
            'media_type'   => $mediaType,
            'thumbnail'    => $thumbnailUrl,
            'audio_url'    => $audioUrl,
            'audio_name'   => $audioName,
            'is_published'  => $request->boolean('is_published', true),
            'is_exclusive'  => $request->boolean('is_exclusive', false),
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

        return redirect()->route('posts.show', $post->id)
                         ->with('status', 'post-created');
    }

    public function show(Post $post): View
    {
        $post->load(['user', 'mediaFiles']);
        return view('posts.show', compact('post'));
    }

    public function data(Post $post): JsonResponse
    {
        $post->load(['user', 'mediaFiles']);

        // Vérifier l'accès au contenu exclusif
        $viewer   = auth()->user();
        $canSee   = !$post->is_exclusive
            || ($viewer && (
                $viewer->id === $post->user_id
                || $viewer->hasActiveSubscription()
            ));

        $liked = $viewer
            ? Like::where('user_id', $viewer->id)->where('post_id', $post->id)->exists()
            : false;

        // Si contenu exclusif verrouillé : renvoyer des données minimales
        if (!$canSee) {
            return response()->json([
                'id'             => $post->id,
                'is_exclusive'   => true,
                'locked'         => true,
                'title'          => null,
                'body'           => null,
                'media_url'      => null,
                'media_type'     => 'text',
                'audio_url'      => null,
                'audio_name'     => null,
                'likes_count'    => $post->likes_count,
                'comments_count' => $post->comments_count,
                'liked'          => $liked,
                'created_at'     => $post->created_at->diffForHumans(),
                'media_files'    => [],
                'post_url'       => route('subscription.pricing'),
                'user'           => [
                    'name'        => $post->user->name,
                    'username'    => $post->user->username,
                    'avatar'      => $post->user->avatar ? Storage::url($post->user->avatar) : null,
                    'profile_url' => route('profile.show', $post->user->username),
                    'is_verified' => (bool) ($post->user->is_verified ?? false),
                ],
            ]);
        }

        return response()->json([
            'id'             => $post->id,
            'is_exclusive'   => (bool) $post->is_exclusive,
            'locked'         => false,
            'title'          => $post->title,
            'body'           => $post->body,
            'media_url'      => $post->media_url ? Storage::url($post->media_url) : null,
            'media_type'     => $post->media_type,
            'audio_url'      => $post->audio_url ? Storage::url($post->audio_url) : null,
            'audio_name'     => $post->audio_name,
            'likes_count'    => $post->likes_count,
            'comments_count' => $post->comments_count,
            'liked'          => $liked,
            'created_at'     => $post->created_at->diffForHumans(),
            'media_files'    => $post->mediaFiles->map(fn ($m) => Storage::url($m->media_url))->values(),
            'post_url'       => route('posts.show', $post->id),
            'user'           => [
                'name'        => $post->user->name,
                'username'    => $post->user->username,
                'avatar'      => $post->user->avatar ? Storage::url($post->user->avatar) : null,
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
}
