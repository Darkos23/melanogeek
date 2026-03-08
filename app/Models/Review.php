<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'order_id',
        'reviewer_id',
        'reviewed_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function order()    { return $this->belongsTo(Order::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }
    public function reviewed() { return $this->belongsTo(User::class, 'reviewed_id'); }

    // Note moyenne d'un créateur (helper statique)
    public static function avgFor(int $userId): float
    {
        return (float) static::where('reviewed_id', $userId)->avg('rating');
    }

    public static function countFor(int $userId): int
    {
        return static::where('reviewed_id', $userId)->count();
    }
}
