<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'username'        => $this->username,
            'email'           => $this->when($request->user()?->id === $this->id, $this->email),
            'avatar'          => $this->avatar ? Storage::disk('public')->url($this->avatar) : null,
            'cover_photo'     => $this->cover_photo ? Storage::disk('public')->url($this->cover_photo) : null,
            'bio'             => $this->bio,
            'niche'           => $this->niche,
            'location'        => $this->location,
            'website'         => $this->website,
            'role'            => $this->role,
            'is_verified'     => $this->is_verified,
            'is_private'      => $this->is_private,
            'country_type'    => $this->country_type,
            'plan'            => $this->plan,
            'social'          => [
                'instagram' => $this->instagram,
                'tiktok'    => $this->tiktok,
                'youtube'   => $this->youtube,
                'twitter'   => $this->twitter,
            ],
            'stats'           => [
                // Supporte withCount() (attribut _count) ET with()->count()
                'followers_count' => $this->followers_count
                    ?? $this->whenLoaded('followers', fn() => $this->followers->count(), 0),
                'following_count' => $this->following_count
                    ?? $this->whenLoaded('following', fn() => $this->following->count(), 0),
                'posts_count'     => $this->posts_count
                    ?? $this->whenLoaded('posts', fn() => $this->posts->count(), 0),
            ],
            'is_following'    => $this->when(
                $request->user() && $request->user()->id !== $this->id,
                fn() => $request->user()->isFollowing($this->resource)
            ),
            'created_at'      => $this->created_at->toIso8601String(),
        ];
    }
}
