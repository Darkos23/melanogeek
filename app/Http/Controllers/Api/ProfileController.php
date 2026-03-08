<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // ── Afficher un profil ────────────────────────────────────────
    public function show(Request $request, string $username): UserResource|JsonResponse
    {
        $user = User::where('username', $username)
            ->withCount([
                'followers',
                'following',
                'posts' => fn($q) => $q->published(),
            ])
            ->firstOrFail();

        return new UserResource($user);
    }

    // ── Posts d'un utilisateur ────────────────────────────────────
    public function posts(Request $request, string $username): JsonResponse
    {
        $profile = User::where('username', $username)->firstOrFail();
        $viewer  = $request->user();

        // ── Comportement Instagram pour les comptes privés ──────
        if ($profile->is_private) {
            $isOwn       = $viewer && $viewer->id === $profile->id;
            $isFollowing = $viewer && $viewer->isFollowing($profile);
            $isStaff     = $viewer && $viewer->isStaff();

            if (! $isOwn && ! $isFollowing && ! $isStaff) {
                return response()->json([
                    'private' => true,
                    'data'    => [],
                    'meta'    => [
                        'current_page' => 1,
                        'last_page'    => 1,
                        'total'        => 0,
                    ],
                ]);
            }
        }

        $posts = $profile->posts()
            ->published()
            ->with('user')
            ->latest()
            ->paginate(15);

        return response()->json([
            'data' => PostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page'    => $posts->lastPage(),
                'total'        => $posts->total(),
            ],
        ]);
    }

    // ── Mettre à jour son profil ──────────────────────────────────
    public function update(Request $request): UserResource
    {
        $user = $request->user();

        // ── Whitelist stricte : seuls ces champs sont modifiables ──
        // Ne JAMAIS utiliser $request->except() ici (risque de mass assignment)
        // Un utilisateur ne peut pas modifier : role, is_verified, is_active, plan, etc.
        $validated = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'username'    => ['sometimes', 'string', 'max:30', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'bio'         => ['nullable', 'string', 'max:500'],
            'niche'       => ['nullable', 'string', 'max:100'],
            'location'    => ['nullable', 'string', 'max:100'],
            'website'     => ['nullable', 'url', 'max:255'],
            'instagram'   => ['nullable', 'string', 'max:100'],
            'tiktok'      => ['nullable', 'string', 'max:100'],
            'youtube'     => ['nullable', 'string', 'max:100'],
            'twitter'     => ['nullable', 'string', 'max:100'],
            'is_private'  => ['nullable', 'boolean'],
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        // Partir des données validées uniquement (exclure les fichiers gérés séparément)
        $data = collect($validated)->except(['avatar', 'cover_photo'])->toArray();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('cover_photo')) {
            if ($user->cover_photo) Storage::disk('public')->delete($user->cover_photo);
            $data['cover_photo'] = $request->file('cover_photo')->store('covers', 'public');
        }

        $user->update($data);

        return new UserResource($user->fresh()->load(['followers', 'following']));
    }

    // ── Supprimer son compte ──────────────────────────────────────
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Compte supprimé.']);
    }
}
