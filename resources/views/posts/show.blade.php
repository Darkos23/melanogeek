@extends('layouts.app')

@section('title', $post->title ?? 'Publication de ' . $post->user->name)

@push('styles')
<style>
    .post-page { padding-top: calc(80px + env(safe-area-inset-top)); min-height: 100vh; }

    .post-wrap {
        max-width: 680px;
        margin: 0 auto;
        padding: 32px 24px 60px;
    }

    /* ── BACK ── */
    .post-back {
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; color: var(--text-muted);
        font-size: .84rem; margin-bottom: 24px;
        transition: color .2s; cursor: pointer;
    }
    .post-back:hover { color: var(--text); }

    /* ── CARD ── */
    .post-card-full {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
    }

    /* ── AUTHOR ── */
    .post-author-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 24px 16px; gap: 12px;
    }
    .post-author-left { display: flex; align-items: center; gap: 12px; }
    .post-avatar {
        width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px; overflow: hidden; text-decoration: none;
    }
    .post-avatar-inner {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; font-weight: 700; overflow: hidden;
    }
    .post-avatar-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .post-author-name {
        font-family: var(--font-head); font-size: .92rem; font-weight: 700;
        color: var(--text); text-decoration: none;
    }
    .post-author-name:hover { color: var(--terra); }
    .post-author-meta { font-size: .76rem; color: var(--text-muted); margin-top: 2px; }
    .post-author-niche {
        display: inline-flex; align-items: center; gap: 5px;
        background: var(--terra-soft); border: 1px solid rgba(200,82,42,.2);
        color: var(--terra); font-size: .7rem; font-weight: 600;
        padding: 3px 10px; border-radius: 100px;
    }

    /* ── CONTENU ── */
    .post-title {
        font-family: var(--font-head);
        font-size: 1.35rem; font-weight: 800; letter-spacing: -.02em;
        color: var(--text); line-height: 1.3;
        padding: 0 24px 12px;
    }
    .post-body {
        font-size: .95rem; line-height: 1.75;
        color: var(--text-muted);
        padding: 0 24px 20px;
        white-space: pre-wrap;
    }

    /* ── MÉDIA ── */
    .post-media {
        width: 100%;
        background: var(--bg-card2);
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        overflow: hidden;
    }
    .post-media img, .post-media video {
        width: 100%; max-height: 540px;
        object-fit: cover; display: block;
    }

    /* ── CAROUSEL ── */
    .post-carousel {
        width: 100%;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        background: var(--bg-card2);
        position: relative;
        overflow: hidden;
    }
    .carousel-track-wrap {
        overflow: hidden;
    }
    .carousel-track {
        display: flex;
        transition: transform .3s ease;
    }
    .carousel-slide {
        min-width: 100%;
        display: flex; align-items: center; justify-content: center;
        background: var(--bg-card2);
        max-height: 540px;
        overflow: hidden;
    }
    .carousel-slide img {
        width: 100%; max-height: 540px;
        object-fit: cover; display: block;
    }
    /* Prev / Next arrows */
    .carousel-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 34px; height: 34px; border-radius: 50%;
        background: rgba(0,0,0,.55); border: 1px solid rgba(255,255,255,.15);
        color: white; font-size: .9rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 2; transition: background .2s;
    }
    .carousel-btn:hover { background: rgba(0,0,0,.8); }
    .carousel-btn.prev { left: 12px; }
    .carousel-btn.next { right: 12px; }
    /* Dots */
    .carousel-dots {
        position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);
        display: flex; gap: 5px; z-index: 2;
    }
    .carousel-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: rgba(255,255,255,.45); transition: background .2s, width .2s;
        cursor: pointer;
    }
    .carousel-dot.active {
        background: white; width: 18px; border-radius: 100px;
    }
    /* Counter badge */
    .carousel-counter {
        position: absolute; top: 10px; right: 12px;
        background: rgba(0,0,0,.55); color: white;
        font-size: .72rem; font-weight: 600; letter-spacing: .03em;
        padding: 3px 9px; border-radius: 100px; z-index: 2;
    }

    /* ── ACTIONS ── */
    .post-actions {
        display: flex; align-items: center; gap: 6px;
        padding: 14px 20px;
        border-top: 1px solid var(--border);
    }
    .action-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 16px; border-radius: 100px;
        background: transparent; border: 1px solid transparent;
        color: var(--text-muted); font-family: var(--font-body);
        font-size: .84rem; font-weight: 500;
        cursor: pointer; transition: all .2s;
    }
    .action-btn:hover { background: var(--bg-card2); border-color: var(--border); color: var(--text); }
    .action-btn.liked { color: #E05555; }
    .action-btn.liked:hover { background: rgba(224,85,85,.08); border-color: rgba(224,85,85,.3); }
    .action-btn svg { width: 17px; height: 17px; }
    .action-sep { flex: 1; }
    .action-share {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 16px; border-radius: 100px;
        background: transparent; border: 1px solid var(--border);
        color: var(--text-muted); font-family: var(--font-body);
        font-size: .84rem; cursor: pointer; transition: all .2s; text-decoration: none;
    }
    .action-share:hover { border-color: var(--gold); color: var(--gold); }

    /* ── AUDIO PLAYER ── */
    .post-audio {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        background: linear-gradient(135deg, rgba(200,82,42,.06), rgba(212,168,67,.06));
    }
    .audio-play-btn {
        width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
        background: var(--terra); border: none; color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; cursor: pointer; transition: transform .15s, opacity .2s;
    }
    .audio-play-btn:hover { transform: scale(1.08); }
    .audio-play-btn:active { transform: scale(.95); }
    .audio-info { flex: 1; min-width: 0; }
    .audio-track-name {
        font-size: .82rem; font-weight: 700; color: var(--text);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .audio-progress-wrap {
        height: 3px; background: var(--border); border-radius: 3px;
        margin-top: 5px; cursor: pointer; position: relative; overflow: hidden;
    }
    .audio-progress-bar {
        height: 100%; width: 0%; background: var(--terra);
        border-radius: 3px; transition: width .25s linear;
    }
    .audio-time {
        font-size: .68rem; color: var(--text-muted); flex-shrink: 0;
        font-variant-numeric: tabular-nums;
    }
    .audio-vol-btn {
        width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
        background: transparent; border: 1px solid var(--border);
        color: var(--text-muted); font-size: .8rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .2s;
    }
    .audio-vol-btn:hover { border-color: var(--terra); color: var(--terra); }
    .audio-loop-indicator {
        font-size: .65rem; color: var(--terra); font-weight: 700;
        background: var(--terra-soft); border-radius: 100px;
        padding: 2px 7px; flex-shrink: 0;
    }

    /* ── DRAFT BANNER ── */
    .draft-banner {
        display: flex; align-items: center; gap: 10px;
        background: rgba(212,168,67,.08);
        border-bottom: 1px solid rgba(212,168,67,.2);
        padding: 12px 20px;
        font-size: .82rem; color: var(--gold);
    }

    /* ── OWNER ACTIONS ── */
    .owner-actions {
        display: flex; gap: 8px;
    }
    .btn-delete-post {
        background: transparent; border: 1px solid rgba(224,85,85,.3);
        color: #E05555; padding: 7px 14px; border-radius: 100px;
        font-family: var(--font-body); font-size: .78rem; font-weight: 500;
        cursor: pointer; transition: all .2s;
    }
    .btn-delete-post:hover { background: rgba(224,85,85,.08); border-color: #E05555; }

    /* ── SUCCESS FLASH ── */
    .post-success {
        display: flex; align-items: center; gap: 10px;
        background: rgba(45,90,61,.12);
        border: 1px solid rgba(45,90,61,.25);
        color: #3D8A58;
        padding: 14px 18px; border-radius: 14px;
        font-size: .88rem; font-weight: 500;
        margin-bottom: 20px;
    }
    [data-theme="dark"] .post-success { color: #6DC48A; }

    /* ── COMMENTS ── */
    .post-comments {
        border-top: 1px solid var(--border);
        padding: 20px 24px 24px;
    }
    .comments-title {
        font-family: var(--font-head); font-size: .88rem; font-weight: 700;
        color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em;
        margin-bottom: 16px;
    }

    /* Form */
    .comment-form-wrap {
        display: flex; gap: 12px; align-items: flex-start;
        margin-bottom: 20px;
    }
    .comment-form-avi {
        width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px; overflow: hidden;
    }
    .comment-form-avi-inner {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: .78rem; font-weight: 700; overflow: hidden;
    }
    .comment-form-avi-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .comment-form { flex: 1; display: flex; flex-direction: column; gap: 8px; }
    .comment-input {
        width: 100%; background: var(--bg-card2);
        border: 1px solid var(--border); border-radius: 12px;
        color: var(--text); font-family: var(--font-body); font-size: .88rem;
        padding: 10px 14px; resize: none; line-height: 1.5;
        transition: border-color .2s; min-height: 42px; max-height: 160px;
        overflow-y: auto;
    }
    .comment-input::placeholder { color: var(--text-muted); }
    .comment-input:focus { outline: none; border-color: var(--terra); }
    .comment-form-actions { display: flex; justify-content: flex-end; }
    .btn-comment-submit {
        background: var(--terra); border: none; color: white;
        padding: 7px 18px; border-radius: 100px;
        font-family: var(--font-body); font-size: .82rem; font-weight: 600;
        cursor: pointer; transition: opacity .2s;
    }
    .btn-comment-submit:disabled { opacity: .4; cursor: not-allowed; }
    .btn-comment-submit:not(:disabled):hover { opacity: .85; }

    /* Guest prompt */
    .comment-guest-prompt {
        text-align: center; padding: 14px;
        background: var(--bg-card2); border-radius: 12px;
        font-size: .85rem; color: var(--text-muted);
        margin-bottom: 20px;
    }
    .comment-guest-prompt a { color: var(--terra); text-decoration: none; font-weight: 600; }

    /* Comment items */
    .comment-item {
        display: flex; gap: 10px; padding: 12px 0;
        border-bottom: 1px solid var(--border);
        animation: fadeInUp .25s ease;
    }
    .comment-item:last-child { border-bottom: none; }
    .comment-avi {
        width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px; overflow: hidden; text-decoration: none;
    }
    .comment-avi-inner {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--bg-card);
        display: flex; align-items: center; justify-content: center;
        font-size: .72rem; font-weight: 700; overflow: hidden;
    }
    .comment-avi-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .comment-content { flex: 1; min-width: 0; }
    .comment-header { display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap; margin-bottom: 4px; }
    .comment-author {
        font-size: .84rem; font-weight: 700; color: var(--text);
        text-decoration: none;
    }
    .comment-author:hover { color: var(--terra); }
    .comment-verified { font-size: .7rem; color: var(--gold); }
    .comment-ago { font-size: .72rem; color: var(--text-muted); }
    .comment-body {
        font-size: .875rem; color: var(--text-muted); line-height: 1.55;
        word-break: break-word; white-space: pre-wrap;
    }
    .btn-comment-del {
        background: none; border: none; color: var(--text-muted);
        font-size: .7rem; cursor: pointer; padding: 2px 6px; border-radius: 6px;
        margin-left: auto; flex-shrink: 0;
        transition: color .2s, background .2s;
    }
    .btn-comment-del:hover { color: #E05555; background: rgba(224,85,85,.08); }

    /* States */
    .comments-loading, .comments-empty {
        text-align: center; padding: 28px 0;
        color: var(--text-muted); font-size: .85rem;
    }
    .comments-empty-icon { font-size: 1.6rem; margin-bottom: 6px; }
    .btn-load-more {
        width: 100%; margin-top: 8px; padding: 10px;
        background: var(--bg-card2); border: 1px solid var(--border);
        color: var(--text-muted); border-radius: 12px;
        font-family: var(--font-body); font-size: .83rem; cursor: pointer;
        transition: border-color .2s, color .2s;
    }
    .btn-load-more:hover { border-color: var(--terra); color: var(--terra); }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 520px) {
        .post-wrap { padding: 16px 12px 40px; }
        .post-author-row { padding: 16px 14px 12px; }
        .post-title { padding: 0 14px 10px; font-size: 1.15rem; }
        .post-body  { padding: 0 14px 16px; font-size: .92rem; }

        .post-actions { padding: 8px 10px; gap: 4px; }
        .action-btn   { padding: 7px 10px; font-size: .8rem; gap: 5px; }
        .action-share { padding: 7px 10px; font-size: .8rem; }

        .post-comments { padding: 16px 14px 20px; }
        .comment-input { font-size: .85rem; }
        .comment-body  { font-size: .84rem; }
    }
</style>
@endpush

@section('content')
<div class="post-page">
<div class="post-wrap">

    <a href="{{ url()->previous() }}" class="post-back">← Retour</a>

    @if(session('status') === 'post-created')
        <div class="post-success">✓ Publication créée avec succès !</div>
    @endif

    <div class="post-card-full">

        {{-- Brouillon --}}
        @if(! $post->is_published)
            <div class="draft-banner">
                💾 Brouillon — cette publication n'est pas encore visible publiquement.
                @if(auth()->id() === $post->user_id)
                    <form method="POST" action="{{ route('posts.publish', $post->id) }}" style="margin-left:auto;">
                        @csrf @method('PATCH')
                        <button type="submit" style="background:var(--gold);border:none;color:#000;padding:5px 14px;border-radius:100px;font-size:.78rem;font-weight:700;cursor:pointer;">
                            Publier maintenant
                        </button>
                    </form>
                @endif
            </div>
        @endif

        {{-- Auteur --}}
        <div class="post-author-row">
            <div class="post-author-left">
                <a href="{{ route('profile.show', $post->user->username) }}" class="post-avatar">
                    <div class="post-avatar-inner">
                        @if($post->user->avatar)
                            <img src="{{ Storage::url($post->user->avatar) }}" alt="">
                        @else
                            {{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}
                        @endif
                    </div>
                </a>
                <div>
                    <a href="{{ route('profile.show', $post->user->username) }}" class="post-author-name">
                        {{ $post->user->name }}
                    </a>
                    <div class="post-author-meta">
                        &#64;{{ $post->user->username }}
                        @if($post->user->niche)
                            · <span class="post-author-niche">{{ $post->user->niche }}</span>
                        @endif
                        · {{ $post->created_at?->diffForHumans() ?? "-" }}
                    </div>
                </div>
            </div>

            {{-- Owner actions --}}
            @auth
                @if(auth()->id() === $post->user_id)
                    <div class="owner-actions">
                        <form method="POST" action="{{ route('posts.destroy', $post->id) }}"
                              onsubmit="return confirm('Supprimer cette publication ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete-post">🗑 Supprimer</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        @php
            $canSeePost = !$post->is_exclusive
                || (auth()->check() && (
                    auth()->id() === $post->user_id
                    || auth()->user()->hasActiveSubscription()
                ));
        @endphp

        @if(!$canSeePost)
        {{-- ══ LOCK : contenu exclusif ══ --}}
        <div style="text-align:center;padding:48px 24px;">
            <div style="font-size:3rem;line-height:1;margin-bottom:16px;">🔒</div>
            <div style="font-family:var(--font-head);font-size:1.2rem;font-weight:800;color:var(--text);margin-bottom:8px;">Contenu exclusif</div>
            <div style="font-size:.9rem;color:var(--text-muted);margin-bottom:24px;line-height:1.5;">
                Cette publication est réservée aux abonnés MelanoGeek.<br>
                Abonne-toi pour accéder à tout le contenu exclusif des créateurs.
            </div>
            <a href="{{ route('subscription.pricing') }}"
               style="display:inline-flex;align-items:center;gap:8px;background:var(--gold);color:#1C1208;font-family:var(--font-head);font-size:.92rem;font-weight:700;padding:12px 28px;border-radius:100px;text-decoration:none;transition:opacity .2s;">
                ✦ Voir les abonnements
            </a>
        </div>
        @else

        {{-- Titre --}}
        @if($post->title)
            <div class="post-title">{{ $post->title }}</div>
        @endif

        {{-- Corps --}}
        @if($post->body)
            <div class="post-body">{{ $post->body }}</div>
        @endif

        {{-- Média --}}
        @if($post->mediaFiles->isNotEmpty())
            @php $imgs = $post->mediaFiles; $total = $imgs->count(); @endphp
            @if($total === 1)
                <div class="post-media">
                    <img src="{{ Storage::url($imgs->first()->media_url) }}" alt="{{ $post->title }}">
                </div>
            @else
                <div class="post-carousel" id="postCarousel">
                    <div class="carousel-track-wrap">
                        <div class="carousel-track" id="carouselTrack">
                            @foreach($imgs as $img)
                                <div class="carousel-slide">
                                    <img src="{{ Storage::url($img->media_url) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="carousel-btn prev" onclick="carouselMove(-1)">‹</button>
                    <button class="carousel-btn next" onclick="carouselMove(1)">›</button>
                    <div class="carousel-counter"><span id="carouselCur">1</span> / {{ $total }}</div>
                    <div class="carousel-dots" id="carouselDots">
                        @for($i = 0; $i < $total; $i++)
                            <div class="carousel-dot {{ $i === 0 ? 'active' : '' }}" onclick="carouselGo({{ $i }})"></div>
                        @endfor
                    </div>
                </div>
            @endif
        @elseif($post->media_url)
            <div class="post-media">
                @if($post->media_type === 'video')
                    <video src="{{ Storage::url($post->media_url) }}" controls controlsList="nodownload" oncontextmenu="return false"></video>
                @else
                    <img src="{{ Storage::url($post->media_url) }}" alt="{{ $post->title }}">
                @endif
            </div>
        @endif

        {{-- Audio de fond --}}
        @if($post->audio_url)
        <div class="post-audio" id="postAudioBar">
            <audio id="postAudio" src="{{ Storage::url($post->audio_url) }}" loop preload="metadata" controlsList="nodownload" oncontextmenu="return false"></audio>
            <button class="audio-play-btn" id="audioPlayBtn" onclick="audioToggle()">▶</button>
            <div class="audio-info">
                <div class="audio-track-name">🎵 {{ $post->audio_name ?? 'Musique' }}</div>
                <div class="audio-progress-wrap" id="audioProgressWrap">
                    <div class="audio-progress-bar" id="audioProgressBar"></div>
                </div>
            </div>
            <span class="audio-time" id="audioTime">0:00</span>
            <button class="audio-vol-btn" id="audioVolBtn" onclick="audioMuteToggle()" title="Couper le son">🔊</button>
        </div>
        @endif

        @endif {{-- fin @else canSeePost --}}

        {{-- Actions --}}
        <div class="post-actions">
            <button class="action-btn {{ $liked ? 'liked' : '' }}" id="likeBtn" onclick="toggleLike({{ $post->id }}, this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <span id="likesCount">{{ number_format($post->likes_count) }}</span>
            </button>
            <button class="action-btn" onclick="document.getElementById('comments').scrollIntoView({behavior:'smooth'})">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span id="commentsCount">{{ number_format($post->comments_count) }}</span>
            </button>
            <div class="action-sep"></div>
            <a href="#" class="action-share" onclick="sharePost(); return false;">
                ↗ Partager
            </a>
        </div>

        {{-- ── COMMENTAIRES ── --}}
        <div id="comments" class="post-comments">
            <div class="comments-title">Commentaires</div>

            {{-- Formulaire --}}
            @auth
            <div class="comment-form-wrap">
                <div class="comment-form-avi">
                    <div class="comment-form-avi-inner">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="">
                        @else
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div class="comment-form">
                    <textarea id="commentBody" class="comment-input"
                              placeholder="Ajouter un commentaire…" maxlength="1000" rows="1"></textarea>
                    <div class="comment-form-actions">
                        <button id="commentSubmit" class="btn-comment-submit" disabled>Envoyer</button>
                    </div>
                </div>
            </div>
            @else
            <div class="comment-guest-prompt">
                <a href="{{ route('login') }}">Connecte-toi</a> pour laisser un commentaire
            </div>
            @endauth

            {{-- Liste --}}
            <div id="commentsList">
                <div class="comments-loading">Chargement…</div>
            </div>

            <button id="commentsLoadMore" class="btn-load-more" style="display:none;">
                Voir plus de commentaires
            </button>
        </div>

    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    /* ── Like (toggle) ── */
    let _liking = false;
    async function toggleLike(postId, btn) {
        @guest
        window.location.href = '{{ route('login') }}';
        return;
        @endguest

        if (_liking) return;
        _liking = true;

        // Optimistic UI
        const wasLiked = btn.classList.contains('liked');
        const countEl  = document.getElementById('likesCount');
        const cur      = parseInt(countEl.textContent.replace(/\D/g, '')) || 0;
        btn.classList.toggle('liked', !wasLiked);
        countEl.textContent = !wasLiked ? cur + 1 : Math.max(0, cur - 1);

        try {
            const res = await fetch(`/posts/${postId}/like`, {
                method:  'POST',
                headers: {
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
            });

            if (res.status === 401 || res.status === 403) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            if (res.ok) {
                const data = await res.json();
                btn.classList.toggle('liked', data.liked);
                countEl.textContent = data.count;
            } else {
                // Rollback
                btn.classList.toggle('liked', wasLiked);
                countEl.textContent = cur;
            }
        } catch (e) {
            // Rollback on network error
            btn.classList.toggle('liked', wasLiked);
            countEl.textContent = cur;
        } finally {
            _liking = false;
        }
    }

    function sharePost() {
        if (navigator.share) {
            navigator.share({ title: document.title, url: window.location.href });
        } else {
            navigator.clipboard.writeText(window.location.href)
                .then(() => alert('Lien copié !'));
        }
    }

    /* ── Audio Player ── */
    (function () {
        const audio    = document.getElementById('postAudio');
        if (!audio) return;
        const playBtn  = document.getElementById('audioPlayBtn');
        const progBar  = document.getElementById('audioProgressBar');
        const timeEl   = document.getElementById('audioTime');
        const volBtn   = document.getElementById('audioVolBtn');
        const progWrap = document.getElementById('audioProgressWrap');

        function fmt(s) {
            const m = Math.floor(s / 60);
            return m + ':' + String(Math.floor(s % 60)).padStart(2, '0');
        }

        window.audioToggle = function() {
            if (audio.paused) { audio.play(); playBtn.textContent = '⏸'; }
            else              { audio.pause(); playBtn.textContent = '▶'; }
        };

        window.audioMuteToggle = function() {
            audio.muted = !audio.muted;
            volBtn.textContent = audio.muted ? '🔇' : '🔊';
        };

        audio.addEventListener('timeupdate', () => {
            if (!audio.duration) return;
            const pct = (audio.currentTime / audio.duration) * 100;
            progBar.style.width = pct + '%';
            timeEl.textContent  = fmt(audio.currentTime);
        });

        // Click on progress bar to seek
        progWrap.addEventListener('click', e => {
            if (!audio.duration) return;
            const rect = progWrap.getBoundingClientRect();
            audio.currentTime = ((e.clientX - rect.left) / rect.width) * audio.duration;
        });

        // Pause when leaving page
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) audio.pause();
        });
    })();

    /* ── Carousel ── */
    (function () {
        const track  = document.getElementById('carouselTrack');
        if (!track) return;
        const dots   = document.querySelectorAll('.carousel-dot');
        const curEl  = document.getElementById('carouselCur');
        const total  = dots.length;
        let idx = 0;

        window.carouselGo = function(i) {
            idx = Math.max(0, Math.min(total - 1, i));
            track.style.transform = `translateX(-${idx * 100}%)`;
            dots.forEach((d, j) => d.classList.toggle('active', j === idx));
            if (curEl) curEl.textContent = idx + 1;
        };

        window.carouselMove = function(dir) { carouselGo(idx + dir); };

        // Swipe touch support
        let startX = 0;
        const wrap = document.getElementById('postCarousel');
        wrap.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
        wrap.addEventListener('touchend',   e => {
            const dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 40) carouselMove(dx < 0 ? 1 : -1);
        });

        // Keyboard arrows when carousel is visible
        document.addEventListener('keydown', e => {
            if (!document.getElementById('postCarousel')) return;
            if (e.key === 'ArrowRight') carouselMove(1);
            if (e.key === 'ArrowLeft')  carouselMove(-1);
        });
    })();

    /* ══════════════════════════════════════
       COMMENTAIRES
    ══════════════════════════════════════ */
    (function () {
        const POST_ID     = {{ $post->id }};
        const AUTH_ID     = {{ auth()->id() ?? 'null' }};
        const IS_ADMIN    = {{ auth()->user()?->isAdmin() ? 'true' : 'false' }};
        const CSRF        = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        const list        = document.getElementById('commentsList');
        const loadMoreBtn = document.getElementById('commentsLoadMore');
        const countEl     = document.getElementById('commentsCount');
        const bodyEl      = document.getElementById('commentBody');
        const submitBtn   = document.getElementById('commentSubmit');

        let currentPage = 1;
        let lastPage    = 1;
        let isPosting   = false;

        /* ── Helpers ── */
        function esc(s) {
            return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function timeAgo(iso) {
            const diff = Math.floor((Date.now() - new Date(iso)) / 1000);
            if (diff < 60)   return 'à l\'instant';
            if (diff < 3600) return Math.floor(diff / 60) + ' min';
            if (diff < 86400)return Math.floor(diff / 3600) + ' h';
            return Math.floor(diff / 86400) + ' j';
        }

        function buildItem(c) {
            const canDel = AUTH_ID && (AUTH_ID === c.user.id || IS_ADMIN);
            const aviInner = c.user.avatar
                ? `<img src="${esc(c.user.avatar)}" alt="">`
                : `<span>${esc((c.user.name || '?')[0].toUpperCase())}</span>`;
            const verified = c.user.is_verified
                ? `<span class="comment-verified">✓</span>` : '';
            const delBtn = canDel
                ? `<button class="btn-comment-del" data-id="${c.id}" title="Supprimer">✕</button>` : '';

            return `<div class="comment-item" id="comment-${c.id}">
                <a href="/@${esc(c.user.username)}" class="comment-avi">
                    <div class="comment-avi-inner">${aviInner}</div>
                </a>
                <div class="comment-content">
                    <div class="comment-header">
                        <a href="/@${esc(c.user.username)}" class="comment-author">${esc(c.user.name)}</a>
                        ${verified}
                        <span class="comment-ago">${timeAgo(c.created_at)}</span>
                        ${delBtn}
                    </div>
                    <div class="comment-body">${esc(c.body)}</div>
                </div>
            </div>`;
        }

        /* ── Chargement ── */
        async function loadComments(page) {
            try {
                const res  = await fetch(`/api/posts/${POST_ID}/comments?page=${page}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();

                lastPage = json.meta.last_page;

                if (page === 1) {
                    if (!json.data.length) {
                        list.innerHTML = `<div class="comments-empty">
                            <div class="comments-empty-icon">💬</div>
                            Sois le premier à commenter !
                        </div>`;
                    } else {
                        list.innerHTML = json.data.map(buildItem).join('');
                    }
                } else {
                    list.insertAdjacentHTML('beforeend', json.data.map(buildItem).join(''));
                }

                loadMoreBtn.style.display = currentPage < lastPage ? 'block' : 'none';
                attachDeleteListeners();
            } catch (e) {
                list.innerHTML = '<div class="comments-empty">Erreur de chargement.</div>';
            }
        }

        /* ── Load More ── */
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                currentPage++;
                loadComments(currentPage);
            });
        }

        /* ── Textarea auto-resize + enable submit ── */
        if (bodyEl) {
            bodyEl.addEventListener('input', () => {
                bodyEl.style.height = 'auto';
                bodyEl.style.height = Math.min(bodyEl.scrollHeight, 160) + 'px';
                if (submitBtn) submitBtn.disabled = !bodyEl.value.trim();
            });
        }

        /* ── Soumettre un commentaire ── */
        if (submitBtn) {
            submitBtn.addEventListener('click', async () => {
                const body = bodyEl.value.trim();
                if (!body || isPosting) return;
                isPosting = true;
                submitBtn.disabled = true;

                try {
                    const res = await fetch(`/api/posts/${POST_ID}/comments`, {
                        method:  'POST',
                        headers: {
                            'Content-Type':  'application/json',
                            'Accept':        'application/json',
                            'X-CSRF-TOKEN':  CSRF,
                        },
                        body: JSON.stringify({ body }),
                    });

                    if (res.status === 401) {
                        window.location.href = '{{ route('login') }}';
                        return;
                    }

                    if (!res.ok) {
                        const err = await res.json().catch(() => ({}));
                        alert(err.message || 'Erreur lors de l\'envoi.');
                        return;
                    }

                    const comment = await res.json();

                    // Retirer l'état vide si présent
                    const empty = list.querySelector('.comments-empty, .comments-loading');
                    if (empty) empty.remove();

                    // Insérer en haut de la liste
                    list.insertAdjacentHTML('afterbegin', buildItem(comment));
                    attachDeleteListeners();

                    // Reset textarea
                    bodyEl.value = '';
                    bodyEl.style.height = 'auto';
                    submitBtn.disabled = true;

                    // Mettre à jour le compteur
                    if (countEl) {
                        const cur = parseInt(countEl.textContent.replace(/\D/g, '')) || 0;
                        countEl.textContent = cur + 1;
                    }

                } catch (e) {
                    alert('Erreur réseau.');
                } finally {
                    isPosting = false;
                }
            });
        }

        /* ── Supprimer un commentaire ── */
        function attachDeleteListeners() {
            list.querySelectorAll('.btn-comment-del').forEach(btn => {
                if (btn.dataset.bound) return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', async () => {
                    if (!confirm('Supprimer ce commentaire ?')) return;
                    const id = btn.dataset.id;
                    try {
                        const res = await fetch(`/api/comments/${id}`, {
                            method:  'DELETE',
                            headers: {
                                'Accept':       'application/json',
                                'X-CSRF-TOKEN': CSRF,
                            },
                        });
                        if (res.ok) {
                            document.getElementById(`comment-${id}`)?.remove();
                            if (countEl) {
                                const cur = parseInt(countEl.textContent.replace(/\D/g, '')) || 1;
                                countEl.textContent = Math.max(0, cur - 1);
                            }
                            if (!list.querySelector('.comment-item')) {
                                list.innerHTML = `<div class="comments-empty">
                                    <div class="comments-empty-icon">💬</div>
                                    Sois le premier à commenter !
                                </div>`;
                            }
                        }
                    } catch (e) {
                        alert('Erreur lors de la suppression.');
                    }
                });
            });
        }

        /* ── Init ── */
        loadComments(1);

        // Si l'URL contient #comments, scroll automatique après chargement
        if (window.location.hash === '#comments') {
            setTimeout(() => document.getElementById('comments')?.scrollIntoView({ behavior: 'smooth' }), 400);
        }
    })();
</script>
@endpush
