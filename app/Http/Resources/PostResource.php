<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'body'           => $this->body,
            'category'       => $this->category,
            'category_label' => $this->category_label,
            'media_url'      => $this->media_url ? asset('storage/' . $this->media_url) : null,
            'media_type'     => $this->media_type,
            'is_published'   => $this->is_published,
            'likes_count'    => $this->likes_count,
            'comments_count' => $this->comments_count,
            'user'           => new UserResource($this->whenLoaded('user')),
            'is_liked'       => $this->when(
                $request->user(),
                fn() => $this->likes()->where('user_id', $request->user()->id)->exists()
            ),
            'created_at'     => $this->created_at->toIso8601String(),
            'updated_at'     => $this->updated_at->toIso8601String(),
        ];
    }
}
