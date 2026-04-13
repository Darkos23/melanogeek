<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    // ── GET /api/stories  — stories des comptes suivis ────────────
    public function index(Request $request)
    {
        $followingIds = $request->user()->following()->pluck('users.id');

        $groups = User::whereIn('id', $followingIds)
            ->whereHas('stories', fn($q) => $q->where('expires_at', '>', now()))
            ->with(['stories' => fn($q) => $q->where('expires_at', '>', now())->oldest()])
            ->get()
            ->map(function (User $user) {
                return [
                    'user'       => $this->formatUser($user),
                    'stories'    => $user->stories->map(fn($s) => $this->formatStory($s)),
                    'has_unseen' => true, // simplified — no seen tracking yet
                ];
            });

        return response()->json($groups);
    }

    // ── GET /api/stories/{username}  — stories d'un user ──────────
    public function show(User $user)
    {
        $stories = Story::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->oldest()
            ->get();

        return response()->json([
            'user'       => $this->formatUser($user),
            'stories'    => $stories->map(fn($s) => $this->formatStory($s)),
            'has_unseen' => true,
        ]);
    }

    // ── POST /api/stories ──────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'media' => ['required', 'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/webm',
                'max:51200'],
        ]);

        $file      = $request->file('media');
        $mime      = $file->getMimeType();
        $isVideo   = str_starts_with($mime, 'video/');
        $mediaUrl  = $file->store($isVideo ? 'stories/videos' : 'stories/images', 'public');

        $story = Story::create([
            'user_id'    => $request->user()->id,
            'media_url'  => $mediaUrl,
            'media_type' => $isVideo ? 'video' : 'image',
            'expires_at' => now()->addHours(24),
        ]);

        return response()->json($this->formatStory($story), 201);
    }

    // ── DELETE /api/stories/{story} ───────────────────────────────
    public function destroy(Request $request, Story $story)
    {
        abort_if($story->user_id !== $request->user()->id, 403);

        Storage::disk('public')->delete($story->media_url);
        $story->delete();

        return response()->json(['message' => 'Story supprimée.']);
    }

    // ── Helpers ───────────────────────────────────────────────────
    private function formatUser(User $user): array
    {
        return [
            'id'          => $user->id,
            'name'        => $user->name,
            'username'    => $user->username,
            'avatar'      => $user->avatar ? Storage::url($user->avatar) : null,
            'is_verified' => $user->is_verified,
        ];
    }

    private function formatStory(Story $story): array
    {
        return [
            'id'         => $story->id,
            'media_url'  => Storage::url($story->media_url),
            'media_type' => $story->media_type,
            'expires_at' => $story->expires_at->toIso8601String(),
            'created_at' => $story->created_at->toIso8601String(),
        ];
    }
}
