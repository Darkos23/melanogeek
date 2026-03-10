<?php

namespace App\Models;

use App\Models\PushSubscription;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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
        'location',
        'website',
        'instagram',
        'tiktok',
        'youtube',
        'twitter',
        'country_type',
        'plan',
        'plan_expires_at',
        'is_verified',
        'is_active',
        'is_private',
        'is_available',
        'role',
        'wave_number',
        'orange_money_number',
        'status',
        'creator_category',
        'creator_bio',
        'creator_socials',
        'approved_at',
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
            'plan_expires_at'   => 'datetime',
            'password'          => 'hashed',
            'is_verified'       => 'boolean',
            'is_active'         => 'boolean',
            'is_private'        => 'boolean',
            'is_available'      => 'boolean',
            'creator_socials'   => 'array',
            'approved_at'       => 'datetime',
        ];
    }

    // ══════════════════════════════════════════
    // RELATIONS
    // ══════════════════════════════════════════

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function portfolioItems()
    {
        return $this->hasMany(PortfolioItem::class)->orderBy('sort_order')->orderByDesc('created_at');
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

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
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

    // ══════════════════════════════════════════
    // HELPERS
    // ══════════════════════════════════════════

    // Statut candidature
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    // Rôles
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    public function isCreator(): bool
    {
        return $this->role === 'creator';
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

    // Demande creator
    public function creatorRequest()
    {
        return $this->hasOne(CreatorRequest::class);
    }

    // Avis reçus
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewed_id');
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

    // A-t-il accès au contenu premium ?
    public function hasPremiumAccess(): bool
    {
        if ($this->country_type === 'senegal') return true;
        return $this->plan !== 'free' && $this->plan_expires_at?->isFuture();
    }

    // A-t-il un abonnement actif ? (utilisé pour l'accès au contenu exclusif)
    public function hasActiveSubscription(): bool
    {
        // Accès gratuit pendant la phase de lancement pour les utilisateurs sénégalais
        if ($this->country_type === 'senegal') return true;

        // Vérifier dans la table subscriptions
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();
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