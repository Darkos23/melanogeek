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
            <img src="{{ Storage::disk('public')->url($user->cover_photo) }}" alt="Cover">
        @endif
        <div class="profile-cover-overlay"></div>
        @auth
            @if(auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}" class="cover-edit-btn" style="display:inline-flex;align-items:center;gap:6px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg> Modifier la couverture</a>
            @endif
        @endauth
    </div>

    <!-- ══ HEADER ══ -->
    <div class="profile-header">

        <!-- Avatar -->
        <div class="profile-avatar-wrap">
            <div class="profile-avatar">
                @if($user->avatar)
                    <img src="{{ Storage::disk('public')->url($user->avatar) }}" alt="{{ $user->name }}">
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
                        <span class="badge-cm" style="display:inline-flex;align-items:center;gap:4px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2L2 6v6c0 5.55 3.84 10.74 10 12 6.16-1.26 10-6.45 10-12V6L12 2z"/></svg> CM</span>
                    @endif
                </div>
                @if($user->name !== $user->username)
                    <div class="profile-username">{{ $user->name }}</div>
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
                        <div class="profile-meta-item"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> <span>{{ $user->location }}</span></div>
                    @endif
                    @if($user->website)
                        <div class="profile-meta-item">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            <a href="{{ $user->website }}" target="_blank" rel="noopener">
                                {{ parse_url($user->website, PHP_URL_HOST) ?? $user->website }}
                            </a>
                        </div>
                    @endif
                    <div class="profile-meta-item"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> <span>Membre depuis {{ $user->created_at->translatedFormat('F Y') }}</span></div>
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
                        <a href="{{ route('profile.edit') }}" class="btn-edit" style="display:inline-flex;align-items:center;gap:6px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg> Modifier le profil</a>
                    @else
                        <button class="btn-follow {{ auth()->user()->isFollowing($user) ? 'following' : '' }}"
                            id="followBtn" onclick="toggleFollow('{{ $user->username }}', this)">
                            @if(auth()->user()->isFollowing($user))
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;"><polyline points="20 6 9 17 4 12"/></svg> Abonné
                            @else
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Suivre
                            @endif
                        </button>
                        <button class="btn-block {{ auth()->user()->isBlocking($user) ? 'blocking' : '' }}"
                            id="blockBtn" onclick="toggleBlock('{{ $user->username }}', this)">
                            @if(auth()->user()->isBlocking($user))
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg> Bloqué
                            @else
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg> Bloquer
                            @endif
                        </button>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-follow">+ Suivre</a>
                @endauth
            </div>
        </div>

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
    </div>

    <!-- ══ BODY ══ -->
    <div class="profile-body">

        <!-- Tabs -->
        <div class="profile-tabs">
            <button class="profile-tab active" data-tab="posts" style="display:inline-flex;align-items:center;gap:6px;">
                @if($isLocked)
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                @else
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                @endif
                Publications
                <span class="tab-count">{{ $isLocked ? '—' : $postsPublishedCount }}</span>
            </button>
            <button class="profile-tab" data-tab="about" style="display:inline-flex;align-items:center;gap:6px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                À propos
            </button>
        </div>

        <!-- Tab: Posts -->
        <div id="tab-posts">
            @if($isLocked)
            <div class="private-lock">
                <div class="private-lock-icon"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
                <div class="private-lock-title">Ce compte est privé</div>
                <div class="private-lock-desc">Suivez ce compte pour voir ses photos et publications.</div>
                <div class="private-lock-divider">ou</div>
                @auth
                    @if(auth()->id() !== $user->id)
                        <button class="btn-follow {{ auth()->user()->isFollowing($user) ? 'following' : '' }}"
                            id="followBtnLock" onclick="toggleFollow('{{ $user->username }}', this)">
                            @if(auth()->user()->isFollowing($user))
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;"><polyline points="20 6 9 17 4 12"/></svg> Abonné
                            @else
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Suivre
                            @endif
                        </button>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-follow">+ Suivre</a>
                @endauth
            </div>
            @else
            <div class="posts-grid">
                @forelse($posts as $post)
                    <div class="grid-post"
                         onclick="openPostModal({{ $post->id }})"
                         style="cursor:pointer;">

                        @auth @if(auth()->id() === $user->id)
                        <div class="grid-post-owner-actions" onclick="event.stopPropagation()">
                            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                  onsubmit="return confirm('Supprimer cette publication ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="gp-del-btn" title="Supprimer"><x-icon name="trash" :size="13"/></button>
                            </form>
                        </div>
                        @endif @endauth

                        <div class="grid-post-media">
                            @if($post->mediaFiles->isNotEmpty())
                                <img src="{{ Storage::disk('public')->url($post->mediaFiles->first()->media_url) }}"
                                     alt="" style="">
                                @if($post->mediaFiles->count() > 1)
                                    <div class="grid-post-badge"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><rect x="5" y="2" width="14" height="20" rx="2"/><rect x="2" y="5" width="14" height="20" rx="2" fill="rgba(0,0,0,.4)"/></svg></div>
                                @endif
                            @elseif($post->media_url && $post->media_type === 'image')
                                <img src="{{ Storage::disk('public')->url($post->media_url) }}" alt="{{ $post->title }}"
                                     style="">
                            @elseif($post->media_url && $post->media_type === 'video')
                                @if($post->thumbnail)
                                    <img src="{{ Storage::disk('public')->url($post->thumbnail) }}" alt="{{ $post->title }}"
                                         style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a2e,#2d1b3d);display:flex;align-items:center;justify-content:center;">
                                        <div style="width:40px;height:40px;background:rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;"><svg width="16" height="16" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></div>
                                    </div>
                                @endif
                                <div class="grid-post-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></div>
                            @else
                                <div style="width:100%;height:100%;background:var(--bg-card2);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:10px;gap:6px;">
                                    <span style="opacity:.25;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                                    <div style="font-size:.68rem;color:var(--text-muted);text-align:center;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;">
                                        {{ Str::limit($post->title ?: $post->body, 60) }}
                                    </div>
                                </div>
                            @endif
                        </div>
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
                    </div>
                @empty
                    <div class="empty-posts">
                        <div class="empty-posts-icon"><svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg></div>
                        <div class="empty-posts-title">Aucune publication</div>
                        <div class="empty-posts-desc">
                            @if(auth()->id() === $user->id)
                                Tu n'as pas encore publié de contenu.@if(auth()->user()->isAdmin()) <a href="{{ route('posts.create') }}" style="color:var(--terra);text-decoration:none;font-weight:600;">Créer ta première publication →</a>@endif
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
                    @if($user->location)
                    <div class="about-item">
                        <span class="about-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                        <span>Localisation</span>
                        <span class="about-item-value" style="margin-left:auto;">{{ $user->location }}</span>
                    </div>
                    @endif
                    <div class="about-item">
                        <span class="about-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                        <span>Membre depuis</span>
                        <span class="about-item-value" style="margin-left:auto;">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="about-item">
                        <span class="about-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></span>
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
                                Afrique
                            @else
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 2 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg> Diaspora
                            @endif
                        </span>
                    </div>
                    @if($user->website)
                    <div class="about-item">
                        <span class="about-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></span>
                        <span>Site web</span>
                        <span class="about-item-value" style="margin-left:auto;">
                            <a href="{{ $user->website }}" target="_blank">{{ parse_url($user->website, PHP_URL_HOST) }}</a>
                        </span>
                    </div>
                    @endif
                </div>
                <div class="about-card">
                    <div class="about-card-title">Réseaux sociaux</div>
                    @php
                    $socialIcons = [
                        'instagram' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>',
                        'tiktok'    => '<path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z"/>',
                        'youtube'   => '<path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/>',
                        'twitter'   => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
                    ];
                    @endphp
                    @forelse([
                        ['key'=>'instagram','label'=>'Instagram','val'=>$user->instagram,'url'=>'https://instagram.com/'],
                        ['key'=>'tiktok','label'=>'TikTok','val'=>$user->tiktok,'url'=>'https://tiktok.com/@'],
                        ['key'=>'youtube','label'=>'YouTube','val'=>$user->youtube,'url'=>'https://youtube.com/@'],
                        ['key'=>'twitter','label'=>'X / Twitter','val'=>$user->twitter,'url'=>'https://twitter.com/'],
                    ] as $social)
                        @if($social['val'])
                        <div class="about-item">
                            <span class="about-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">{!! $socialIcons[$social['key']] !!}</svg></span>
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


    </div>
</div>

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
                <button class="pm-close" onclick="closePostModal()"><x-icon name="x" :size="16"/></button>
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
