<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'instructor_id', 'title', 'slug', 'description',
        'what_you_learn', 'requirements', 'category', 'level',
        'language', 'thumbnail', 'preview_video', 'price',
        'currency', 'is_free', 'status', 'total_duration', 'total_lessons',
    ];

    protected $casts = [
        'what_you_learn' => 'array',
        'requirements'   => 'array',
        'is_free'        => 'boolean',
        'price'          => 'decimal:2',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Section::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function isEnrolledBy(User $user): bool
    {
        return $this->enrollments()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public function getProgressFor(User $user): int
    {
        $total = $this->total_lessons;
        if ($total === 0) return 0;

        $completed = UserProgress::whereIn('lesson_id', $this->lessons()->pluck('lessons.id'))
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();

        return (int) round(($completed / $total) * 100);
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
