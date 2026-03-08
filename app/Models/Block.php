<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    public $timestamps = false;

    protected $fillable = ['blocker_id', 'blocked_id'];

    protected $casts = ['created_at' => 'datetime'];

    public function blocker() { return $this->belongsTo(User::class, 'blocker_id'); }
    public function blocked() { return $this->belongsTo(User::class, 'blocked_id'); }
}
