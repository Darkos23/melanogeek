<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    // ── Publier une story ─────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'media' => ['required', 'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/webm',
                'max:51200'],
        ], [
            'media.required'  => 'Choisis un fichier à partager.',
            'media.mimetypes' => 'Seuls les formats JPG, PNG, GIF, WEBP, MP4, MOV et WEBM sont acceptés.',
            'media.max'       => 'Le fichier ne doit pas dépasser 50 Mo.',
        ]);

        $file      = $request->file('media');
        $mime      = $file->getMimeType();
        $isVideo   = str_starts_with($mime, 'video/');
        $mediaUrl  = $file->store($isVideo ? 'stories/videos' : 'stories/images', 'public');
        $mediaType = $isVideo ? 'video' : 'image';

        Story::create([
            'user_id'    => auth()->id(),
            'media_url'  => $mediaUrl,
            'media_type' => $mediaType,
            'expires_at' => now()->addHours(24),
        ]);

        return back()->with('story_created', true);
    }

    // ── Supprimer sa propre story ──────────────────────────────────
    public function destroy(Story $story)
    {
        abort_if($story->user_id !== auth()->id(), 403);

        Storage::disk('public')->delete($story->media_url);
        $story->delete();

        return back()->with('story_deleted', true);
    }

    // ── Stories d'un utilisateur (JSON pour le viewer) ────────────
    public function userStories(User $user)
    {
        $stories = Story::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->oldest()
            ->get()
            ->map(fn($s) => [
                'id'         => $s->id,
                'media_url'  => Storage::url($s->media_url),
                'media_type' => $s->media_type,
                'expires_at' => $s->expires_at->toIso8601String(),
                'created_at' => $s->created_at->diffForHumans(),
            ]);

        return response()->json([
            'user' => [
                'id'          => $user->id,
                'name'        => $user->name,
                'username'    => $user->username,
                'avatar'      => $user->avatar ? Storage::url($user->avatar) : null,
                'is_verified' => $user->is_verified,
            ],
            'stories' => $stories,
        ]);
    }
}
