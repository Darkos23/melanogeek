<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PortfolioItem extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'category',
        'cover_image', 'images', 'external_url', 'tags', 'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'tags'   => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? Storage::url($this->cover_image) : null;
    }

    public function getImageUrlsAttribute(): array
    {
        if (empty($this->images)) return [];
        return array_map(fn($p) => Storage::url($p), $this->images);
    }

    public static function categories(): array
    {
        return [
            'design'       => '🎨 Design graphique',
            'photo'        => '📸 Photographie',
            'video'        => '🎬 Vidéographie',
            'musique'      => '🎵 Musique',
            'mode'         => '👗 Mode & Textile',
            'illustration' => '✏️ Illustration',
            'artisanat'    => '🧶 Artisanat',
            'ecriture'     => '📝 Écriture',
            'autre'        => '✦ Autre',
        ];
    }
}
