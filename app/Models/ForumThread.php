<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumThread extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'category', 'title', 'body',
        'is_pinned', 'views_count', 'replies_count', 'last_reply_at',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned'     => 'boolean',
            'last_reply_at' => 'datetime',
        ];
    }

    public const CATEGORIES = [
        'manga-anime'    => ['label' => 'Manga & Animé',      'icon' => '🎌'],
        'gaming'         => ['label' => 'Gaming',              'icon' => '🎮'],
        'tech'           => ['label' => 'Tech & IA',           'icon' => '🤖'],
        'culture'        => ['label' => 'Afrofuturisme',        'icon' => '🚀'],
        'cosplay'        => ['label' => 'Cosplay',             'icon' => '🎭'],
        'off-topic'      => ['label' => 'Off-topic',           'icon' => '☕'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'thread_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category]['label'] ?? ucfirst($this->category);
    }

    public function getCategoryIconAttribute(): string
    {
        return self::CATEGORIES[$this->category]['icon'] ?? '💬';
    }
}
