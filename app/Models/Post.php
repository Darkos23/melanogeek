<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'body',
        'media_url', 'media_type', 'thumbnail',
        'audio_url', 'audio_name',
        'likes_count', 'comments_count', 'views_count',
        'is_published', 'pending_review', 'rejection_reason',
        'category', 'tags',
    ];

    protected $casts = [
        'is_published'   => 'boolean',
        'pending_review' => 'boolean',
        'tags'           => 'array',
    ];

    const CATEGORIES = [
        'manga-anime'    => 'Manga & Animé',
        'gaming'         => 'Gaming',
        'tech'           => 'Tech & IA',
        'dev'            => 'Développement',
        'cinema-series'  => 'Cinéma & Séries',
        'culture'        => 'Afrofuturisme',
        'debat'          => 'Débat',
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

    // ── Scopes ────────────────────────────────────────────────────
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

    // ── Helpers ───────────────────────────────────────────────────
    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? '';
    }
}
