<?php

namespace App\Models;

use App\Models\PushSubscription;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // ── Colonnes autorisées en masse assignment ──
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'google_id',
        'avatar',
        'cover_photo',
        'bio',
        'bio_wolof',
        'niche',
        'niche_wolof',
        'specialty',
        'location',
        'website',
        'instagram',
        'tiktok',
        'youtube',
        'twitter',
        'country_type',
        'is_verified',
        'is_active',
        'is_private',
        'role',
        'status',
    ];

    // ── Colonnes cachées ──
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ── Casts ──
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_verified'       => 'boolean',
            'is_active'         => 'boolean',
            'is_private'        => 'boolean',
        ];
    }

    // ══════════════════════════════════════════
    // RELATIONS
    // ══════════════════════════════════════════

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }

    // Abonnés (ceux qui me suivent)
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'following_id',
            'follower_id'
        )->withTimestamps();
    }

    // Abonnements (ceux que je suis)
    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id',
            'following_id'
        )->withTimestamps();
    }

    // ── Waxtu (e-learning — tables conservées bien que projet séparé) ──
    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function isEnrolledIn(Course $course): bool
    {
        return $this->enrollments()
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();
    }

    // ══════════════════════════════════════════
    // HELPERS
    // ══════════════════════════════════════════

    // Rôles
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    public function isCM(): bool
    {
        return $this->role === 'cm';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['cm', 'admin', 'owner']);
    }

    public function isAdminOrOwner(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    // Blocages
    public function blockedUsers()
    {
        return $this->hasMany(Block::class, 'blocker_id');
    }

    public function isBlocking(User $user): bool
    {
        return Block::where('blocker_id', $this->id)->where('blocked_id', $user->id)->exists();
    }

    public function blockedIds(): array
    {
        return $this->blockedUsers()->pluck('blocked_id')->toArray();
    }

    // Est-ce que je suis cet utilisateur ?
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    // Nombre de notifications non lues
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->unreadNotifications()->count();
    }

    // Route model binding par username
    public function getRouteKeyName(): string
    {
        return 'username';
    }
}
