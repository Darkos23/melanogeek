@extends('layouts.app')

@section('title', $user->username)

@push('styles')
@include('profile.partials._styles')
@endpush

@section('content')
<div class="profile-page">

    <!-- ══ COVER ══ -->
    <div class="profile-cover">
        @if($user->cover_photo)
            <img src="{{ Storage::url($user->cover_photo) }}" alt="Cover">
        @endif
        <div class="profile-cover-overlay"></div>
        @auth
            @if(auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}" class="cover-edit-btn">✎ Modifier la couverture</a>
            @endif
        @endauth
    </div>

    <!-- ══ HEADER ══ -->
    <div class="profile-header">

        <!-- Avatar -->
        <div class="profile-avatar-wrap">
            <div class="profile-avatar">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                @else
                    <div class="profile-avatar-inner">{{ mb_strtoupper(mb_substr($user->name,0,1)) }}</div>
                @endif
            </div>
            @if($user->is_verified)
                <div class="profile-verified" title="Compte vérifié">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="12" fill="#3897F0"/>
                        <path d="M7 12.3L10.4 15.8L17 8.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
            @endif
        </div>

        <div class="profile-top-row">
            <!-- Identité -->
            <div class="profile-identity">
                <div class="profile-name">
                    {{ $user->username }}
                    @if($user->is_verified)
                        <span class="badge-verified" title="Compte vérifié">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="12" fill="#3897F0"/>
                                <path d="M7 12.3L10.4 15.8L17 8.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            </svg>
                        </span>
                    @endif
                    @if($user->isCM())
                        <span class="badge-cm">🛡️ CM</span>
                    @elseif($user->isCreator())
                        <span class="badge-creator">✦ Créateur</span>
                    @endif
                </div>
                @if($user->name !== $user->username)
                    <div class="profile-username">{{ $user->name }}</div>
                @endif

                @if($user->niche)
                    <div class="profile-niche">🎨 {{ $user->niche }}</div>
                @endif

                @if($user->isCreator())
                    <div id="availabilityBadge" style="display:inline-flex;align-items:center;gap:5px;font-size:.75rem;font-weight:600;padding:3px 10px;border-radius:100px;margin-bottom:12px;
                        {{ $user->is_available ? 'background:rgba(34,197,94,.1);color:#16a34a;border:1px solid rgba(34,197,94,.25);' : 'background:rgba(224,85,85,.08);color:#E05555;border:1px solid rgba(224,85,85,.2);' }}">
                        {{ $user->is_available ? '🟢 Disponible aux commandes' : '🔴 Non disponible' }}
                    </div>
                @endif

                @if($user->bio || $user->bio_wolof)
                    <div class="profile-bio-block">
                        @if($user->bio)
                            <p class="profile-bio">{{ $user->bio }}</p>
                        @endif
                        @if($user->bio_wolof)
                            <p class="profile-bio profile-bio-wolof" style="margin-top:4px;font-style:italic;color:var(--text-muted);">{{ $user->bio_wolof }}</p>
                        @endif
                    </div>
                @endif

                <!-- Meta -->
                <div class="profile-meta">
                    @if($user->location)
                        <div class="profile-meta-item">📍 <span>{{ $user->location }}</span></div>
                    @endif
                    @if($user->website)
                        <div class="profile-meta-item">
                            🔗 <a href="{{ $user->website }}" target="_blank" rel="noopener">
                                {{ parse_url($user->website, PHP_URL_HOST) ?? $user->website }}
                            </a>
                        </div>
                    @endif
                    <div class="profile-meta-item">📅 <span>Membre depuis {{ $user->created_at->translatedFormat('F Y') }}</span></div>
                </div>

                <!-- Réseaux sociaux -->
                @if($user->instagram || $user->tiktok || $user->youtube || $user->twitter)
                <div class="profile-socials">
                    @if($user->instagram)
                        <a href="https://instagram.com/{{ $user->instagram }}" target="_blank" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            Instagram
                        </a>
                    @endif
                    @if($user->tiktok)
                        <a href="https://tiktok.com/@{{ $user->tiktok }}" target="_blank" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z"/></svg>
                            TikTok
                        </a>
                    @endif
                    @if($user->youtube)
                        <a href="https://youtube.com/@{{ $user->youtube }}" target="_blank" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
                            YouTube
                        </a>
                    @endif
                    @if($user->twitter)
                        <a href="https://twitter.com/{{ $user->twitter }}" target="_blank" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            X / Twitter
                        </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Boutons action -->
            <div class="profile-actions">
                @auth
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}" class="btn-edit">✎ Modifier le profil</a>
                        @if($user->isCreator())
                        <button id="availabilityBtn" onclick="toggleAvailability(this)" class="btn-message"
                            style="{{ $user->is_available ? 'color:var(--text);' : 'color:#E05555;border-color:#E05555;' }}">
                            {{ $user->is_available ? '🟢 Disponible' : '🔴 Indisponible' }}
                        </button>
                        @endif
                    @else
                        <button class="btn-follow {{ auth()->user()->isFollowing($user) ? 'following' : '' }}"
                            id="followBtn" onclick="toggleFollow('{{ $user->username }}', this)">
                            {{ auth()->user()->isFollowing($user) ? 'Abonné ✓' : '+ Suivre' }}
                        </button>
                        <a href="{{ route('messages.show', $user->username) }}" class="btn-message">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            Message
                        </a>
                        <button class="btn-block {{ auth()->user()->isBlocking($user) ? 'blocking' : '' }}"
                            id="blockBtn" onclick="toggleBlock('{{ $user->username }}', this)">
                            {{ auth()->user()->isBlocking($user) ? '🚫 Bloqué' : '⊘ Bloquer' }}
                        </button>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-follow">+ Suivre</a>
                @endauth
            </div>
        </div>

        <!-- Stats (masqué pour le créateur sur son propre profil : il a déjà l'espace créateur) -->
        @if(!(auth()->id() === $user->id && $user->isCreator()))
        <div class="profile-stats">
            <div class="stat-item">
                <div class="stat-value">{{ number_format($postsPublishedCount) }}</div>
                <div class="stat-label">Publications</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="followersCount">{{ number_format($followersCount) }}</div>
                <div class="stat-label">Abonnés</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($followingCount) }}</div>
                <div class="stat-label">Abonnements</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($totalLikes) }}</div>
                <div class="stat-label">Likes reçus</div>
            </div>
        </div>
        @endif
    </div>

    {{-- ══ STORIES ══ --}}
    @if($stories->isNotEmpty())
    <div class="profile-stories">
        <div class="profile-stories-label">Stories actives</div>
        <div class="profile-stories-list">
            @foreach($stories as $i => $story)
            <button class="profile-story-thumb" type="button"
                onclick="openProfileStory({{ $i }})" title="Story {{ $i + 1 }}">
                <div class="profile-story-ring">
                    <div class="profile-story-ring-inner">
                        @if($story->media_type === 'image')
                            <img src="{{ Storage::url($story->media_url) }}" alt="Story">
                        @else
                            ▶
                        @endif
                    </div>
                </div>
                <div class="profile-story-video-badge">
                    {{ $story->media_type === 'video' ? 'vidéo' : $story->created_at->diffForHumans(null, true) }}
                </div>
            </button>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ══ BODY ══ -->
    <div class="profile-body">

        {{-- Creator panel --}}
        @if($user->isCreator() && auth()->id() === $user->id)
        <div class="creator-panel">
            <div class="creator-panel-title">✦ Espace Créateur</div>
            <div class="creator-quick-stats">
                <div class="creator-quick-stat">
                    <div class="creator-quick-stat-val">{{ number_format($postsAllCount) }}</div>
                    <div class="creator-quick-stat-lbl">Publications</div>
                </div>
                <div class="creator-quick-stat">
                    <div class="creator-quick-stat-val">{{ number_format($followersCount) }}</div>
                    <div class="creator-quick-stat-lbl">Abonnés</div>
                </div>
                <div class="creator-quick-stat">
                    <div class="creator-quick-stat-val">{{ number_format($totalLikes) }}</div>
                    <div class="creator-quick-stat-lbl">Likes reçus</div>
                </div>
                <div class="creator-quick-stat">
                    <div class="creator-quick-stat-val">{{ number_format($commentsCount) }}</div>
                    <div class="creator-quick-stat-lbl">Commentaires</div>
                </div>
            </div>
            <div class="creator-actions">
                <a href="{{ route('posts.create') }}" class="btn-creator-action primary">✎ Nouvelle publication</a>
                <a href="{{ route('portfolio.create') }}" class="btn-creator-action secondary">🎨 Ajouter au portfolio</a>
                <a href="{{ route('profile.edit') }}" class="btn-creator-action secondary">⚙ Modifier le profil</a>
            </div>
        </div>
        @endif

        {{-- Become creator --}}
        @if(!$user->isCreator() && !$user->isStaff() && auth()->id() === $user->id)
        <div class="become-creator-card">
            <div class="become-creator-icon">🎨</div>
            <div class="become-creator-text">
                @if($myCreatorRequest && $myCreatorRequest->isPending())
                    <div class="become-creator-title">Demande en attente</div>
                    <div class="become-creator-desc">Ta demande pour devenir créateur est en cours d'examen.</div>
                @elseif($myCreatorRequest && $myCreatorRequest->status === 'rejected')
                    <div class="become-creator-title">Demande refusée</div>
                    <div class="become-creator-desc">Tu peux soumettre une nouvelle demande avec plus de détails.</div>
                @else
                    <div class="become-creator-title">Deviens Créateur</div>
                    <div class="become-creator-desc">Partage ton contenu exclusif et rejoins la communauté des créateurs MelanoGeek.</div>
                @endif
            </div>
            <div>
                @if($myCreatorRequest && $myCreatorRequest->isPending())
                    <span style="font-size:.78rem;color:var(--gold);font-weight:600;padding:9px 18px;background:rgba(212,168,67,.1);border:1px solid rgba(212,168,67,.25);border-radius:100px;white-space:nowrap;">⏳ En attente</span>
                @else
                    <button onclick="document.getElementById('creator-request-modal').style.display='flex'" class="btn-creator-action primary" style="white-space:nowrap;">
                        {{ $myCreatorRequest ? '↻ Soumettre à nouveau' : '+ Postuler' }}
                    </button>
                @endif
            </div>
        </div>
        @endif

        <!-- Tabs -->
        <div class="profile-tabs">
            <button class="profile-tab active" data-tab="posts">
                @if($isLocked) 🔒 Publications @else ⊞ Publications @endif
                <span class="tab-count">{{ $isLocked ? '—' : $postsPublishedCount }}</span>
            </button>
            <button class="profile-tab" data-tab="about">👤 À propos</button>
            @if($user->isCreator())
            <button class="profile-tab" data-tab="creator">✦ Créateur</button>
            <button class="profile-tab" data-tab="portfolio">
                🖼️ Portfolio
                @if($portfolio->isNotEmpty())
                    <span class="tab-count">{{ $portfolio->count() }}</span>
                @endif
            </button>
            <button class="profile-tab" data-tab="services">
                🛍️ Services
                @if($services->isNotEmpty())
                    <span class="tab-count">{{ $services->count() }}</span>
                @endif
            </button>
            <button class="profile-tab" data-tab="avis">
                ⭐ Avis
                @if($totalReviews > 0)
                    <span class="tab-count">{{ $totalReviews }}</span>
                @endif
            </button>
            @endif
        </div>

        <!-- Tab: Posts -->
        <div id="tab-posts">
            @if($isLocked)
            <div class="private-lock">
                <div class="private-lock-icon">🔒</div>
                <div class="private-lock-title">Ce compte est privé</div>
                <div class="private-lock-desc">Suivez ce compte pour voir ses photos et publications.</div>
                <div class="private-lock-divider">ou</div>
                @auth
                    @if(auth()->id() !== $user->id)
                        <button class="btn-follow {{ auth()->user()->isFollowing($user) ? 'following' : '' }}"
                            id="followBtnLock" onclick="toggleFollow('{{ $user->username }}', this)">
                            {{ auth()->user()->isFollowing($user) ? 'Abonné ✓' : '+ Suivre' }}
                        </button>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-follow">+ Suivre</a>
                @endauth
            </div>
            @else
            @php
                $viewerCanSeeExclusive = auth()->check() && (
                    auth()->id() === $user->id
                    || auth()->user()->hasActiveSubscription()
                );
            @endphp
            <div class="posts-grid">
                @forelse($posts as $post)
                    @php $postLocked = $post->is_exclusive && !$viewerCanSeeExclusive; @endphp
                    <div class="grid-post"
                         onclick="{{ $postLocked ? 'window.location=\''.route('subscription.pricing').'\'' : 'openPostModal('.$post->id.')' }}"
                         style="cursor:pointer;">

                        @auth @if(auth()->id() === $user->id)
                        <div class="grid-post-owner-actions" onclick="event.stopPropagation()">
                            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                  onsubmit="return confirm('Supprimer cette publication ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="gp-del-btn" title="Supprimer">✕</button>
                            </form>
                        </div>
                        @endif @endauth

                        <div class="grid-post-media">
                            @if($post->mediaFiles->isNotEmpty())
                                <img src="{{ Storage::url($post->mediaFiles->first()->media_url) }}"
                                     alt="" style="{{ $postLocked ? 'filter:blur(6px);transform:scale(1.08);' : '' }}">
                                @if(!$postLocked && $post->mediaFiles->count() > 1)
                                    <div class="grid-post-badge">⧉</div>
                                @endif
                            @elseif($post->media_url && $post->media_type === 'image')
                                <img src="{{ Storage::url($post->media_url) }}" alt="{{ $post->title }}"
                                     style="{{ $postLocked ? 'filter:blur(6px);transform:scale(1.08);' : '' }}">
                            @elseif($post->media_url && $post->media_type === 'video')
                                @if($post->thumbnail)
                                    <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}"
                                         style="width:100%;height:100%;object-fit:cover;{{ $postLocked ? 'filter:blur(6px);transform:scale(1.08);' : '' }}">
                                @else
                                    <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a2e,#2d1b3d);display:flex;align-items:center;justify-content:center;{{ $postLocked ? 'filter:blur(4px);' : '' }}">
                                        <div style="width:40px;height:40px;background:rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">▶</div>
                                    </div>
                                @endif
                                @if(!$postLocked)<div class="grid-post-badge">▶</div>@endif
                            @else
                                <div style="width:100%;height:100%;background:var(--bg-card2);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:10px;gap:6px;{{ $postLocked ? 'filter:blur(4px);' : '' }}">
                                    <span style="font-size:1.2rem;opacity:.4;">📝</span>
                                    @if(!$postLocked)
                                    <div style="font-size:.68rem;color:var(--text-muted);text-align:center;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;">
                                        {{ Str::limit($post->title ?: $post->body, 60) }}
                                    </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @if($postLocked)
                            <div class="grid-post-lock">🔒 Exclusif</div>
                            <div class="grid-post-locked-overlay"><div class="grid-lock-icon">🔒</div></div>
                        @else
                            <div class="grid-post-overlay">
                                <div class="grid-post-stat">
                                    <svg viewBox="0 0 24 24" fill="white" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    {{ number_format($post->likes_count) }}
                                </div>
                                <div class="grid-post-stat">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    {{ number_format($post->comments_count) }}
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="empty-posts">
                        <div class="empty-posts-icon">📭</div>
                        <div class="empty-posts-title">Aucune publication</div>
                        <div class="empty-posts-desc">
                            @if(auth()->id() === $user->id)
                                Tu n'as pas encore publié de contenu. <a href="{{ route('posts.create') }}" style="color:var(--terra);text-decoration:none;font-weight:600;">Créer ta première publication →</a>
                            @else
                                {{ $user->username }} n'a pas encore publié de contenu.
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
            @endif
        </div>

        <!-- Tab: About -->
        <div id="tab-about" style="display:none;">
            <div class="about-grid">
                <div class="about-card">
                    <div class="about-card-title">Informations</div>
                    @if($user->niche)
                    <div class="about-item">
                        <span class="about-item-icon">🎨</span>
                        <span>Niche</span>
                        <span class="about-item-value" style="margin-left:auto;">{{ $user->niche }}</span>
                    </div>
                    @endif
                    @if($user->location)
                    <div class="about-item">
                        <span class="about-item-icon">📍</span>
                        <span>Localisation</span>
                        <span class="about-item-value" style="margin-left:auto;">{{ $user->location }}</span>
                    </div>
                    @endif
                    <div class="about-item">
                        <span class="about-item-icon">📅</span>
                        <span>Membre depuis</span>
                        <span class="about-item-value" style="margin-left:auto;">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="about-item">
                        <span class="about-item-icon">🌍</span>
                        <span>Origine</span>
                        <span class="about-item-value" style="margin-left:auto;">
                            @if($user->country_type === 'senegal')
                                <span style="display:inline-flex;align-items:center;gap:5px;">
                                    <svg width="16" height="11" viewBox="0 0 22 15" xmlns="http://www.w3.org/2000/svg" style="border-radius:2px;flex-shrink:0;vertical-align:middle;">
                                        <rect width="8" height="15" fill="#00A859"/>
                                        <rect x="7" width="8" height="15" fill="#FDEF42"/>
                                        <rect x="14" width="8" height="15" fill="#E31B23"/>
                                        <polygon points="11,3 11.7,5.2 14,5.2 12.2,6.5 12.9,8.7 11,7.4 9.1,8.7 9.8,6.5 8,5.2 10.3,5.2" fill="#00A859"/>
                                    </svg> Sénégal
                                </span>
                            @elseif($user->country_type === 'africa')
                                🌍 Afrique
                            @else
                                ✈️ Diaspora
                            @endif
                        </span>
                    </div>
                    @if($user->website)
                    <div class="about-item">
                        <span class="about-item-icon">🔗</span>
                        <span>Site web</span>
                        <span class="about-item-value" style="margin-left:auto;">
                            <a href="{{ $user->website }}" target="_blank">{{ parse_url($user->website, PHP_URL_HOST) }}</a>
                        </span>
                    </div>
                    @endif
                </div>
                <div class="about-card">
                    <div class="about-card-title">Réseaux sociaux</div>
                    @forelse([
                        ['icon'=>'📸','label'=>'Instagram','val'=>$user->instagram,'url'=>'https://instagram.com/'],
                        ['icon'=>'🎵','label'=>'TikTok','val'=>$user->tiktok,'url'=>'https://tiktok.com/@'],
                        ['icon'=>'▶️','label'=>'YouTube','val'=>$user->youtube,'url'=>'https://youtube.com/@'],
                        ['icon'=>'✖','label'=>'X / Twitter','val'=>$user->twitter,'url'=>'https://twitter.com/'],
                    ] as $social)
                        @if($social['val'])
                        <div class="about-item">
                            <span class="about-item-icon">{{ $social['icon'] }}</span>
                            <span>{{ $social['label'] }}</span>
                            <span class="about-item-value" style="margin-left:auto;">
                                <a href="{{ $social['url'].$social['val'] }}" target="_blank">{{ '@'.$social['val'] }}</a>
                            </span>
                        </div>
                        @endif
                    @empty
                        <div style="padding:20px 0;text-align:center;color:var(--text-muted);font-size:.86rem;">Aucun réseau social renseigné.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Tab: Créateur --}}
        @if($user->isCreator())
        <div id="tab-creator" style="display:none;">
            <div class="creator-info-grid">
                <div class="creator-info-card">
                    <div class="creator-info-card-title">📊 Statistiques</div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">📝 Publications</span>
                        <span class="creator-stat-row-value">{{ number_format($postsAllCount) }}</span>
                    </div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">👥 Abonnés</span>
                        <span class="creator-stat-row-value">{{ number_format($followersCount) }}</span>
                    </div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">❤️ Likes reçus</span>
                        <span class="creator-stat-row-value">{{ number_format($totalLikes) }}</span>
                    </div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">💬 Commentaires</span>
                        <span class="creator-stat-row-value">{{ number_format($commentsCount) }}</span>
                    </div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">📅 Créateur depuis</span>
                        <span class="creator-stat-row-value">{{ $user->created_at->translatedFormat('F Y') }}</span>
                    </div>
                </div>
                <div class="creator-info-card">
                    <div class="creator-info-card-title">🎨 Profil créateur</div>
                    @if($user->niche)
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">🎯 Niche</span>
                        <span class="creator-stat-row-value">{{ $user->niche }}</span>
                    </div>
                    @endif
                    @if($user->location)
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">📍 Localisation</span>
                        <span class="creator-stat-row-value">{{ $user->location }}</span>
                    </div>
                    @endif
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">🌍 Origine</span>
                        <span class="creator-stat-row-value">
                            @if($user->country_type === 'senegal') 🇸🇳 Sénégal
                            @elseif($user->country_type === 'africa') 🌍 Afrique
                            @else ✈️ Diaspora @endif
                        </span>
                    </div>
                    @if($user->website)
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">🔗 Site web</span>
                        <span class="creator-stat-row-value">
                            <a href="{{ $user->website }}" target="_blank" rel="noopener" style="color:var(--gold);text-decoration:none;">
                                {{ parse_url($user->website, PHP_URL_HOST) ?? $user->website }}
                            </a>
                        </span>
                    </div>
                    @endif
                    @if(!$user->niche && !$user->website)
                    <div style="padding:20px 0;text-align:center;color:var(--text-muted);font-size:.84rem;">
                        Aucune info créateur renseignée.
                        @if(auth()->id() === $user->id)
                            <br><a href="{{ route('profile.edit') }}" style="color:var(--terra);text-decoration:none;font-weight:600;">Compléter mon profil →</a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @auth
                @if(!auth()->user()->hasPremiumAccess() && auth()->id() !== $user->id)
                <div style="background:linear-gradient(135deg,rgba(200,82,42,.08),rgba(212,168,67,.06));border:1px solid rgba(200,82,42,.2);border-radius:16px;padding:24px;margin-top:20px;text-align:center;">
                    <div style="font-size:2rem;margin-bottom:10px;">🔒</div>
                    <div style="font-family:var(--font-head);font-size:1rem;font-weight:700;margin-bottom:8px;">Contenu exclusif</div>
                    <div style="font-size:.86rem;color:var(--text-muted);margin-bottom:16px;line-height:1.6;">
                        Abonne-toi à MelanoGeek pour accéder au contenu premium de {{ $user->username }}.
                    </div>
                    <a href="{{ route('feed') }}" style="display:inline-flex;align-items:center;gap:8px;background:var(--terra);color:white;padding:11px 24px;border-radius:100px;font-family:var(--font-head);font-size:.88rem;font-weight:700;text-decoration:none;">
                        ⭐ Voir les offres
                    </a>
                </div>
                @endif
            @else
                <div style="background:linear-gradient(135deg,rgba(200,82,42,.08),rgba(212,168,67,.06));border:1px solid rgba(200,82,42,.2);border-radius:16px;padding:24px;margin-top:20px;text-align:center;">
                    <div style="font-size:2rem;margin-bottom:10px;">🌟</div>
                    <div style="font-family:var(--font-head);font-size:1rem;font-weight:700;margin-bottom:8px;">Rejoins MelanoGeek</div>
                    <div style="font-size:.86rem;color:var(--text-muted);margin-bottom:16px;line-height:1.6;">
                        Crée un compte pour suivre {{ $user->username }} et accéder à tout le contenu.
                    </div>
                    <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:8px;background:var(--terra);color:white;padding:11px 24px;border-radius:100px;font-family:var(--font-head);font-size:.88rem;font-weight:700;text-decoration:none;">
                        S'inscrire gratuitement →
                    </a>
                </div>
            @endauth
        </div>

        {{-- Tab: Portfolio --}}
        @include('profile.partials._tab-portfolio')

        {{-- Tab: Services --}}
        <div id="tab-services" style="display:none;">
            @if($services->isEmpty())
            <div style="text-align:center;padding:60px 20px;color:var(--text-muted);">
                <div style="font-size:2.8rem;margin-bottom:12px;">🛍️</div>
                <div style="font-family:var(--font-head);font-size:1rem;font-weight:700;margin-bottom:6px;color:var(--text);">Aucun service disponible</div>
                <p style="font-size:.84rem;margin-bottom:16px;">
                    @if(auth()->id() === $user->id)
                        Propose tes premiers services sur le marketplace.
                    @else
                        {{ $user->name }} n'a pas encore de services disponibles.
                    @endif
                </p>
                @if(auth()->id() === $user->id)
                <a href="{{ route('services.create') }}" style="display:inline-block;padding:10px 22px;background:var(--terra);color:white;border-radius:10px;font-weight:700;text-decoration:none;font-size:.86rem;">+ Créer un service</a>
                @endif
            </div>
            @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;padding-top:8px;">
                @foreach($services as $service)
                <a href="{{ route('marketplace.show', $service) }}" style="background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden;text-decoration:none;transition:border-color .15s,transform .2s;display:flex;flex-direction:column;" onmouseover="this.style.borderColor='var(--border-hover)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
                    @if($service->cover_image)
                        <img src="{{ Storage::url($service->cover_image) }}" alt="{{ $service->title }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;">
                    @else
                        <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,var(--bg-card2),var(--bg-hover));display:flex;align-items:center;justify-content:center;font-size:2.2rem;">{{ $service->category_icon }}</div>
                    @endif
                    <div style="padding:14px;flex:1;display:flex;flex-direction:column;gap:8px;">
                        <div style="font-size:.7rem;font-weight:700;padding:2px 9px;border-radius:100px;background:var(--terra-soft);color:var(--terra);border:1px solid rgba(200,82,42,.2);width:fit-content;">{{ $service->category_label }}</div>
                        <div style="font-family:var(--font-head);font-size:.92rem;font-weight:700;color:var(--text);line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $service->title }}</div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;">
                            <div style="font-family:var(--font-head);font-size:1rem;font-weight:800;color:var(--terra);">{{ $service->price_formatted }}</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">⏱ {{ $service->delivery_days }}j</div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @if(auth()->id() === $user->id)
            <div style="text-align:center;margin-top:20px;">
                <a href="{{ route('services.manage') }}" style="display:inline-block;padding:9px 20px;border:1px solid var(--border);border-radius:10px;color:var(--text-muted);text-decoration:none;font-size:.84rem;font-weight:600;transition:border-color .15s;" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">⚙ Gérer mes services</a>
                <a href="{{ route('services.create') }}" style="display:inline-block;padding:9px 20px;background:var(--terra);border-radius:10px;color:white;text-decoration:none;font-size:.84rem;font-weight:700;margin-left:10px;transition:opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">+ Nouveau service</a>
            </div>
            @endif
            @endif
        </div>

        {{-- Tab: Avis --}}
        <div id="tab-avis" style="display:none;">
            @if($totalReviews === 0)
            <div style="text-align:center;padding:60px 20px;color:var(--text-muted);">
                <div style="font-size:2.8rem;margin-bottom:12px;">⭐</div>
                <div style="font-family:var(--font-head);font-size:1rem;font-weight:700;margin-bottom:6px;color:var(--text);">Aucun avis pour l'instant</div>
                <p style="font-size:.84rem;">Les avis apparaissent après avoir reçu et terminé des commandes.</p>
            </div>
            @else
            {{-- Résumé de la note --}}
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:20px 24px;margin-bottom:20px;display:flex;gap:24px;align-items:center;flex-wrap:wrap;">
                <div style="text-align:center;flex-shrink:0;">
                    <div style="font-family:var(--font-head);font-size:3rem;font-weight:800;color:var(--text);line-height:1;">{{ number_format($avgRating, 1) }}</div>
                    <div style="color:#F59E0B;font-size:1.1rem;letter-spacing:2px;margin:4px 0;">
                        @for($i=1;$i<=5;$i++){{ $i <= round($avgRating) ? '★' : '☆' }}@endfor
                    </div>
                    <div style="font-size:.78rem;color:var(--text-muted);">{{ $totalReviews }} avis</div>
                </div>
                <div style="flex:1;min-width:160px;">
                    @for($star=5;$star>=1;$star--)
                    @php $count = $reviews->where('rating', $star)->count(); $pct = $totalReviews ? round($count / $totalReviews * 100) : 0; @endphp
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                        <span style="font-size:.75rem;color:var(--text-muted);width:10px;text-align:right;">{{ $star }}</span>
                        <span style="color:#F59E0B;font-size:.8rem;">★</span>
                        <div style="flex:1;height:6px;background:var(--bg-card2);border-radius:100px;overflow:hidden;">
                            <div style="height:100%;width:{{ $pct }}%;background:#F59E0B;border-radius:100px;"></div>
                        </div>
                        <span style="font-size:.72rem;color:var(--text-muted);width:28px;">{{ $pct }}%</span>
                    </div>
                    @endfor
                </div>
            </div>
            {{-- Liste des avis --}}
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach($reviews as $review)
                <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:16px 18px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--terra),var(--gold));padding:1.5px;flex-shrink:0;">
                            <div style="width:100%;height:100%;border-radius:50%;background:var(--bg-card2);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;overflow:hidden;">
                                @if($review->reviewer->avatar)<img src="{{ Storage::url($review->reviewer->avatar) }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">@else{{ mb_strtoupper(mb_substr($review->reviewer->name,0,1)) }}@endif
                            </div>
                        </div>
                        <div>
                            <div style="font-size:.86rem;font-weight:600;color:var(--text);">{{ $review->reviewer->name }}</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                        <div style="margin-left:auto;color:#F59E0B;font-size:.95rem;letter-spacing:1px;">
                            @for($i=1;$i<=5;$i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                        </div>
                    </div>
                    @if($review->comment)
                    <p style="font-size:.84rem;color:var(--text-muted);margin:0;line-height:1.6;">{{ $review->comment }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

        @endif

    </div>
</div>

{{-- Modal : Demande créateur --}}
@if(auth()->id() === $user->id && !$user->isCreator() && !$user->isStaff())
<div id="creator-request-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.75);align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(4px);">
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:20px;padding:32px;max-width:480px;width:100%;position:relative;">
        <button onclick="document.getElementById('creator-request-modal').style.display='none'" style="position:absolute;top:16px;right:16px;background:none;border:none;color:var(--text-muted);font-size:1.2rem;cursor:pointer;line-height:1;">✕</button>
        <div style="font-size:2rem;margin-bottom:12px;">🎨</div>
        <div style="font-family:var(--font-head);font-size:1.1rem;font-weight:800;margin-bottom:6px;">Devenir Créateur</div>
        <div style="font-size:.84rem;color:var(--text-muted);margin-bottom:20px;line-height:1.6;">
            Explique-nous ta démarche créative, ton univers et ce que tu souhaites partager sur MelanoGeek.
        </div>
        <form method="POST" action="{{ route('creator-request.store') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);margin-bottom:8px;">Ta motivation</label>
                <textarea name="motivation" rows="5" required minlength="50" maxlength="1000"
                    placeholder="Décris ton univers créatif, tes projets, ce que tu vas partager…"
                    style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:12px;padding:12px 14px;color:var(--text);font-family:var(--font-body);font-size:.88rem;resize:vertical;outline:none;box-sizing:border-box;">{{ $myCreatorRequest?->motivation }}</textarea>
                <div style="font-size:.72rem;color:var(--text-faint);margin-top:4px;">Minimum 50 caractères.</div>
            </div>
            <button type="submit" class="btn-creator-action primary" style="width:100%;justify-content:center;padding:12px;">
                🎨 Soumettre ma demande
            </button>
        </form>
    </div>
</div>
@endif

@include('stories._viewer')

{{-- Post Modal --}}
<div class="pm-overlay" id="pmOverlay" onclick="pmClickOutside(event)">
    <div class="pm-box" id="pmBox">
        <div class="pm-left" id="pmLeft">
            <div style="color:rgba(255,255,255,.4); font-size:1.5rem;">⋯</div>
        </div>
        <div class="pm-right">
            <div class="pm-head" id="pmHead">
                <div class="pm-avi"><div class="pm-avi-inner" id="pmAviInner">?</div></div>
                <div class="pm-author-info">
                    <a class="pm-author-name" id="pmAuthorName" href="#">—</a>
                    <div class="pm-author-meta" id="pmAuthorMeta"></div>
                </div>
                <button class="pm-close" onclick="closePostModal()">✕</button>
            </div>
            <div class="pm-content" id="pmContent">
                <div class="pm-skeleton">
                    <div class="pm-skel-line" style="width:60%;"></div>
                    <div class="pm-skel-line" style="width:90%;"></div>
                    <div class="pm-skel-line" style="width:75%;"></div>
                    <div class="pm-skel-line" style="width:85%;"></div>
                    <div class="pm-skel-line" style="width:50%;"></div>
                </div>
            </div>
            <div class="pm-actions" id="pmActions" style="display:none;">
                <button class="pm-action-btn" id="pmLikeBtn" onclick="pmToggleLike()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <span id="pmLikeCount">0</span>
                </button>
                <span class="pm-action-btn" style="pointer-events:none;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span id="pmCommentCount">0</span>
                </span>
                <div class="pm-action-sep"></div>
                <a class="pm-full-link" id="pmFullLink" href="#">Voir le post →</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@include('profile.partials._scripts')
@endpush
