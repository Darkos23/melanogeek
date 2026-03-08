<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'category',
        'price', 'currency', 'delivery_days',
        'cover_image', 'is_active', 'orders_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];

    const CATEGORIES = [
        'photo'    => ['label' => 'Photographie',     'icon' => '📸'],
        'music'    => ['label' => 'Musique / Prod',   'icon' => '🎵'],
        'design'   => ['label' => 'Design graphique', 'icon' => '🎨'],
        'fashion'  => ['label' => 'Mode / Styling',   'icon' => '👗'],
        'video'    => ['label' => 'Vidéo',            'icon' => '🎬'],
        'writing'  => ['label' => 'Rédaction',        'icon' => '✍️'],
        'coaching' => ['label' => 'Coaching',         'icon' => '🌟'],
        'other'    => ['label' => 'Autre',            'icon' => '💼'],
    ];

    // ── Relations ──
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ── Helpers ──
    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? Storage::url($this->cover_image)
            : 'https://placehold.co/600x400/1C1810/D4A843?text=' . urlencode($this->category_label);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category]['label'] ?? ucfirst($this->category);
    }

    public function getCategoryIconAttribute(): string
    {
        return self::CATEGORIES[$this->category]['icon'] ?? '💼';
    }

    public function getPriceFormattedAttribute(): string
    {
        return $this->currency === 'XOF'
            ? number_format($this->price, 0, ',', ' ') . ' FCFA'
            : number_format($this->price, 2, ',', ' ') . ' €';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
