<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // ── Profil public ──
    public function show(User $user): View
    {
        if ($user->isOwner()) {
            abort(404);
        }

        $viewer      = auth()->user();
        $isOwn       = $viewer && $viewer->id === $user->id;
        $isFollowing = $viewer && $viewer->isFollowing($user);
        $isStaff     = $viewer && $viewer->isStaff();
        $isBlocking  = $viewer && ! $isOwn && $viewer->isBlocking($user);
        $isBlockedBy = $viewer && ! $isOwn && $user->isBlocking($viewer);

        // Si l'utilisateur consulté a bloqué le visiteur → 404
        if ($isBlockedBy) {
            abort(404);
        }

        // Profil privé : on affiche la page mais on masque les posts
        $isLocked = $user->is_private && ! $isOwn && ! $isFollowing && ! $isStaff;

        $totalLikes       = $user->posts()->sum('likes_count');
        $myCreatorRequest = $isOwn ? $user->creatorRequest : null;

        $posts = $isLocked
            ? collect()
            : $user->posts()->with('mediaFiles')->where('is_published', true)->latest()->get();

        $stories = Story::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->oldest()
            ->get();

        return view('profile.show', [
            'user'             => $user,
            'posts'            => $posts,
            'isLocked'         => $isLocked,
            'totalLikes'       => $totalLikes,
            'myCreatorRequest' => $myCreatorRequest,
            'stories'          => $stories,
            'isBlocking'       => $isBlocking,
        ]);
    }

    // ── Formulaire d'édition ──
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // ── Sauvegarde ──
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Upload avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            unset($data['avatar']);
        }

        // Upload cover
        if ($request->hasFile('cover_photo')) {
            if ($user->cover_photo) Storage::disk('public')->delete($user->cover_photo);
            $data['cover_photo'] = $request->file('cover_photo')->store('covers', 'public');
        } else {
            unset($data['cover_photo']);
        }

        if (isset($data['email']) && $data['email'] !== $user->email) {
            $data['email_verified_at'] = null;
        }

        $data['is_private'] = $request->boolean('is_private');

        $user->fill($data)->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // ── Suppression ──
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}