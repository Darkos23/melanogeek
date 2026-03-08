<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'service_id', 'buyer_id', 'seller_id',
        'price', 'currency', 'status', 'payment_status',
        'payment_method', 'transaction_id',
        'requirements', 'seller_note',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    const STATUS_LABELS = [
        'pending'     => ['label' => 'En attente',   'color' => '#D4A843', 'bg' => 'rgba(212,168,67,.12)'],
        'accepted'    => ['label' => 'Acceptée',     'color' => '#5B9CF6', 'bg' => 'rgba(91,156,246,.12)'],
        'in_progress' => ['label' => 'En cours',     'color' => '#E06030', 'bg' => 'rgba(224,96,48,.12)'],
        'delivered'   => ['label' => 'Livrée',       'color' => '#9B5FD1', 'bg' => 'rgba(155,95,209,.12)'],
        'completed'   => ['label' => 'Terminée',     'color' => '#2A7A48', 'bg' => 'rgba(42,122,72,.12)'],
        'cancelled'   => ['label' => 'Annulée',      'color' => '#E05555', 'bg' => 'rgba(224,85,85,.12)'],
    ];

    // ── Relations ──
    public function service()  { return $this->belongsTo(Service::class); }
    public function buyer()    { return $this->belongsTo(User::class, 'buyer_id'); }
    public function seller()   { return $this->belongsTo(User::class, 'seller_id'); }
    public function review()   { return $this->hasOne(Review::class); }

    // ── Helpers ──
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function getPriceFormattedAttribute(): string
    {
        return $this->currency === 'XOF'
            ? number_format($this->price, 0, ',', ' ') . ' FCFA'
            : number_format($this->price, 2, ',', ' ') . ' €';
    }

    public function canBeCancelledBy(User $user): bool
    {
        if (! in_array($this->status, ['pending', 'accepted'])) return false;
        return $this->buyer_id === $user->id || $this->seller_id === $user->id;
    }
}
