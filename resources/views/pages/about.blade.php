@extends('layouts.app')

@section('title', $settings['about_title'] ?: 'À propos de MelanoGeek')

@push('styles')
<style>
    .about-page {
        padding-top: calc(80px + env(safe-area-inset-top));
        padding-bottom: 80px;
    }
    .about-hero {
        max-width: 760px;
        margin: 0 auto;
        padding: 0 32px;
        text-align: center;
        margin-bottom: 64px;
    }
    .about-hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--terra-soft);
        border: 1px solid rgba(200,82,42,.2);
        color: var(--terra);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 100px;
        margin-bottom: 20px;
    }
    .about-hero-title {
        font-family: var(--font-head);
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.1;
        color: var(--text);
        margin-bottom: 20px;
    }
    .about-hero-title span { color: var(--terra); }
    .about-hero-tagline {
        font-size: 1.05rem;
        color: var(--text-muted);
        line-height: 1.7;
        max-width: 540px;
        margin: 0 auto;
    }

    /* ── DIVIDER ── */
    .about-divider {
        max-width: 760px;
        margin: 48px auto;
        padding: 0 32px;
        display: flex; align-items: center; gap: 16px;
    }
    .about-divider::before, .about-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }
    .about-divider-icon { color: var(--gold); font-size: .9rem; flex-shrink: 0; }

    /* ── SECTIONS ── */
    .about-section {
        max-width: 760px;
        margin: 0 auto 48px;
        padding: 0 32px;
    }
    .about-section-label {
        font-family: var(--font-head);
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .09em;
        text-transform: uppercase;
        color: var(--terra);
        margin-bottom: 14px;
        display: flex; align-items: center; gap: 8px;
    }
    .about-section-label::after {
        content: '';
        display: inline-block;
        width: 32px;
        height: 2px;
        background: var(--terra);
        border-radius: 2px;
        opacity: .4;
    }
    .about-section-title {
        font-family: var(--font-head);
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--text);
        margin-bottom: 14px;
    }
    .about-section-body {
        font-size: .96rem;
        color: var(--text-muted);
        line-height: 1.8;
        white-space: pre-wrap;
    }

    /* ── STATS STRIP ── */
    .about-stats {
        max-width: 760px;
        margin: 0 auto 56px;
        padding: 0 32px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    .about-stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px 20px;
        text-align: center;
        transition: background .4s, border-color .4s;
    }
    .about-stat-val {
        font-family: var(--font-head);
        font-size: 2.4rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--terra);
        line-height: 1;
        margin-bottom: 8px;
    }
    .about-stat-lbl {
        font-size: .78rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* ── VALUES GRID ── */
    .about-values-body {
        font-size: .96rem;
        color: var(--text-muted);
        line-height: 1.8;
        white-space: pre-wrap;
    }

    /* ── CTA ── */
    .about-cta {
        max-width: 760px;
        margin: 0 auto;
        padding: 0 32px 40px;
        text-align: center;
    }
    .about-cta-box {
        background: linear-gradient(135deg, var(--terra-soft), var(--gold-soft));
        border: 1px solid rgba(200,82,42,.2);
        border-radius: 20px;
        padding: 44px 32px;
    }
    .about-cta-title {
        font-family: var(--font-head);
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 12px;
        color: var(--text);
    }
    .about-cta-desc {
        font-size: .92rem;
        color: var(--text-muted);
        margin-bottom: 24px;
        line-height: 1.65;
    }
    .about-cta-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    .btn-cta-primary {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--terra); color: white;
        padding: 12px 28px;
        border-radius: 100px;
        font-family: var(--font-head);
        font-size: .9rem; font-weight: 700;
        text-decoration: none;
        transition: background .2s, transform .15s;
    }
    .btn-cta-primary:hover { background: var(--accent); transform: translateY(-1px); }
    .btn-cta-secondary {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--bg-card); color: var(--text);
        padding: 12px 28px;
        border-radius: 100px;
        border: 1px solid var(--border);
        font-size: .9rem; font-weight: 500;
        text-decoration: none;
        transition: all .2s;
    }
    .btn-cta-secondary:hover { border-color: var(--border-hover); }

    @@media (max-width: 640px) {
        .about-hero, .about-section, .about-stats, .about-cta { padding: 0 20px; }
        .about-stats { grid-template-columns: 1fr; }
        .about-hero-title { font-size: 2rem; }
    }
</style>
@endpush

@section('content')
<div class="about-page">

    {{-- Hero --}}
    <div class="about-hero">
        <div class="about-hero-eyebrow">✦ À propos</div>
        <h1 class="about-hero-title">
            @if($settings['about_title'])
                {!! nl2br(e($settings['about_title'])) !!}
            @else
                La plateforme des <span>créateurs africains</span>
            @endif
        </h1>
        @if($settings['about_tagline'])
            <p class="about-hero-tagline">{{ $settings['about_tagline'] }}</p>
        @endif
    </div>

    {{-- Stats --}}
    @php
        $statUsers    = \App\Models\User::where('is_active', true)->where('role', '!=', 'owner')->count();
        $statCreators = \App\Models\User::where('role', 'creator')->count();
        $statPosts    = \App\Models\Post::where('is_published', true)->count();
    @endphp
    <div class="about-stats">
        <div class="about-stat-card">
            <div class="about-stat-val">{{ number_format($statUsers) }}</div>
            <div class="about-stat-lbl">Membres</div>
        </div>
        <div class="about-stat-card">
            <div class="about-stat-val">{{ number_format($statCreators) }}</div>
            <div class="about-stat-lbl">Créateurs actifs</div>
        </div>
        <div class="about-stat-card">
            <div class="about-stat-val">{{ number_format($statPosts) }}</div>
            <div class="about-stat-lbl">Publications</div>
        </div>
    </div>

    {{-- Mission --}}
    @if($settings['about_mission'])
    <div class="about-section">
        <div class="about-section-label">🎯 Notre mission</div>
        <p class="about-section-body">{{ $settings['about_mission'] }}</p>
    </div>
    <div class="about-divider"><span class="about-divider-icon">✦</span></div>
    @endif

    {{-- Histoire --}}
    @if($settings['about_story'])
    <div class="about-section">
        <div class="about-section-label">📖 Notre histoire</div>
        <p class="about-section-body">{{ $settings['about_story'] }}</p>
    </div>
    @if($settings['about_values'])
    <div class="about-divider"><span class="about-divider-icon">✦</span></div>
    @endif
    @endif

    {{-- Valeurs --}}
    @if($settings['about_values'])
    <div class="about-section">
        <div class="about-section-label">✨ Nos valeurs</div>
        <p class="about-section-body">{{ $settings['about_values'] }}</p>
    </div>
    @endif

    {{-- Si aucun contenu n'est encore rempli --}}
    @if(!$settings['about_mission'] && !$settings['about_story'] && !$settings['about_values'])
    <div class="about-section" style="text-align:center;padding-top:20px;padding-bottom:20px;">
        <div style="color:var(--text-faint);font-size:.9rem;">
            La page est en cours de rédaction. Revenez bientôt.
        </div>
    </div>
    @endif

    {{-- CTA --}}
    <div class="about-cta">
        <div class="about-cta-box">
            <div class="about-cta-title">Rejoins la communauté</div>
            <div class="about-cta-desc">
                Que tu sois créateur ou fan de contenu africain, MelanoGeek est fait pour toi.
            </div>
            <div class="about-cta-btns">
                @guest
                    <a href="{{ route('register') }}" class="btn-cta-primary">Créer un compte →</a>
                    <a href="{{ route('home') }}" class="btn-cta-secondary">En savoir plus</a>
                @else
                    <a href="{{ route('feed') }}" class="btn-cta-primary">Voir le feed →</a>
                    <a href="{{ route('creators') }}" class="btn-cta-secondary">Découvrir les créateurs</a>
                @endguest
            </div>
        </div>
    </div>

</div>
@endsection
