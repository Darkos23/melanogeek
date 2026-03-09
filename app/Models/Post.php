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
        'likes_count', 'comments_count',
        'is_published', 'is_exclusive',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_exclusive' => 'boolean',
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
        return $query->where('is_published', true);
    }
}