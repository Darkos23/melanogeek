<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'body',
        'media_url', 'media_type', 'thumbnail',
        'audio_url', 'audio_name',
        'likes_count', 'comments_count', 'views_count',
        'is_published', 'pending_review', 'rejection_reason',
        'youtube_url',
        'category', 'tags',
    ];

    protected $casts = [
        'is_published'   => 'boolean',
        'pending_review' => 'boolean',
        'tags'           => 'array',
    ];

    const CATEGORIES = [
        'manga-anime'    => 'Animés & mangas',
        'gaming'         => 'Gaming & E-sport',
        'cinema-series'  => 'Cinéma & séries',
        'tech'           => 'Tech, geek & IA',
        'dev'            => 'Développement web',
        'web3-economie'  => 'Web3 & économie numérique',
        'lore-africain'  => 'Pop culture & lore africain',
        'hardware'       => 'Hardware & PC building',
        'carriere'       => 'Carrière & métiers',
        'culture'        => 'Mythos africains',
        'debat'          => 'Débats & pop culture',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function mediaFiles()
    {
        return $this->hasMany(PostMedia::class)->orderBy('sort_order');
    }

    // â”€â”€ Scopes â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('pending_review', false);
    }

    public function scopePending($query)
    {
        return $query->where('pending_review', true)->where('is_published', false);
    }

    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // â”€â”€ Helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? '';
    }

    public function getPrimaryImagePathAttribute(): ?string
    {
        if ($this->thumbnail) {
            return $this->thumbnail;
        }

        if ($this->media_url && $this->media_type === 'image') {
            return $this->media_url;
        }

        $firstImage = $this->relationLoaded('mediaFiles')
            ? $this->mediaFiles->first()
            : $this->mediaFiles()->orderBy('sort_order')->first();

        return $firstImage?->media_url;
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        if ($this->primary_image_path) {
            return Storage::disk('public')->url($this->primary_image_path);
        }

        if ($this->youtube_id) {
            return 'https://img.youtube.com/vi/'.$this->youtube_id.'/hqdefault.jpg';
        }

        return null;
    }

    /**
     * Extrait l'ID YouTube depuis une URL (youtu.be, youtube.com/watch, /embed, /shorts)
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if (! $this->youtube_url) return null;

        $url = trim($this->youtube_url);

        // youtu.be/ID
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m)) return $m[1];
        // youtube.com/watch?v=ID
        if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m)) return $m[1];
        // youtube.com/embed/ID
        if (preg_match('/embed\/([a-zA-Z0-9_-]{11})/', $url, $m)) return $m[1];
        // youtube.com/shorts/ID
        if (preg_match('/shorts\/([a-zA-Z0-9_-]{11})/', $url, $m)) return $m[1];

        return null;
    }
}

