@extends('layouts.app')

@section('title', $settings['about_title'] ?: 'À propos — MelanoGeek')

@push('styles')
<style>
/* ══ ABOUT PAGE ══ */
.about {
    padding-top: calc(72px + env(safe-area-inset-top));
    padding-bottom: 100px;
}

/* ── HERO ── */
.about-hero {
    position: relative;
    text-align: center;
    padding: 80px 24px 72px;
    overflow: hidden;
}
.about-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(var(--border) 1px, transparent 1px),
        linear-gradient(90deg, var(--border) 1px, transparent 1px);
    background-size: 52px 52px;
    mask-image: radial-gradient(ellipse 70% 80% at 50% 50%, black 30%, transparent 100%);
    pointer-events: none;
    z-index: 0;
}
.about-hero-inner {
    position: relative;
    z-index: 1;
    max-width: 680px;
    margin: 0 auto;
}
.about-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.12);
    color: rgba(255,255,255,.70);
    font-family: var(--font-head);
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .10em;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 100px;
    margin-bottom: 28px;
}
.about-badge-dot {
    width: 5px; height: 5px;
    background: var(--gold);
    border-radius: 50%;
}
.about-hero-title {
    font-family: var(--font-head);
    font-size: clamp(2.2rem, 5vw, 3.6rem);
    font-weight: 800;
    letter-spacing: -0.03em;
    line-height: 1.1;
    color: var(--text);
    margin-bottom: 20px;
}
.about-hero-title .grad {
    background: linear-gradient(90deg, var(--gold), var(--terra));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.about-hero-lead {
    font-size: 1.05rem;
    color: var(--text-muted);
    line-height: 1.75;
    max-width: 520px;
    margin: 0 auto;
}

/* ── STATS ── */
.about-stats-wrap {
    max-width: 760px;
    margin: 0 auto 80px;
    padding: 0 24px;
}
.about-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    background: var(--bg-card);
}
.about-stat {
    padding: 32px 20px;
    text-align: center;
    border-right: 1px solid var(--border);
}
.about-stat:last-child { border-right: none; }
.about-stat-n {
    font-family: var(--font-head);
    font-size: 2.6rem;
    font-weight: 800;
    letter-spacing: -0.04em;
    color: var(--text);
    line-height: 1;
    margin-bottom: 6px;
}
.about-stat-n span { color: var(--gold); }
.about-stat-l {
    font-size: .78rem;
    color: var(--text-faint);
    font-weight: 500;
    letter-spacing: .02em;
}

/* ── CONTENT SECTIONS ── */
.about-body {
    max-width: 680px;
    margin: 0 auto;
    padding: 0 24px;
}
.about-block {
    margin-bottom: 64px;
}
.about-block-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-head);
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .10em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 16px;
}
.about-block-label::after {
    content: '';
    width: 28px;
    height: 1px;
    background: var(--gold);
    opacity: .4;
}
.about-block-title {
    font-family: var(--font-head);
    font-size: 1.5rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: var(--text);
    margin-bottom: 16px;
    line-height: 1.25;
}
.about-block-text {
    font-size: .97rem;
    color: var(--text-muted);
    line-height: 1.85;
    white-space: pre-wrap;
}

/* Separator */
.about-sep {
    max-width: 680px;
    margin: 0 auto 64px;
    padding: 0 24px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.about-sep::before, .about-sep::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}
.about-sep-icon {
    color: var(--border-hover);
    font-size: .8rem;
    flex-shrink: 0;
}

/* ── EMPTY STATE ── */
.about-empty {
    text-align: center;
    padding: 40px 24px;
    color: var(--text-faint);
    font-size: .9rem;
}

/* ── CTA ── */
.about-cta-wrap {
    max-width: 760px;
    margin: 0 auto;
    padding: 0 24px;
}
.about-cta {
    position: relative;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 56px 40px;
    text-align: center;
    overflow: hidden;
}
.about-cta::before {
    content: '';
    position: absolute;
    top: -60px; left: 50%;
    transform: translateX(-50%);
    width: 400px; height: 200px;
    background: radial-gradient(ellipse, rgba(212,168,67,.10) 0%, transparent 70%);
    pointer-events: none;
}
.about-cta-title {
    font-family: var(--font-head);
    font-size: 1.7rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: var(--text);
    margin-bottom: 12px;
    position: relative;
}
.about-cta-desc {
    font-size: .95rem;
    color: var(--text-muted);
    line-height: 1.65;
    max-width: 420px;
    margin: 0 auto 28px;
    position: relative;
}
.about-cta-btns {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
    position: relative;
}
.about-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.90);
    color: rgba(0,0,0,.90) !important;
    padding: 0 22px;
    height: 46px;
    border-radius: 9999px;
    font-family: var(--font-head);
    font-size: .8125rem;
    font-weight: 500;
    letter-spacing: .05em;
    text-transform: uppercase;
    text-decoration: none;
    transition: background .15s;
}
.about-btn-primary:hover { background: #fff; }
.about-btn-secondary {
    display: inline-flex; align-items: center; gap: 8px;
    background: transparent;
    color: rgba(255,255,255,.75) !important;
    border: 1px solid rgba(255,255,255,.18);
    padding: 0 22px;
    height: 46px;
    border-radius: 9999px;
    font-family: var(--font-head);
    font-size: .8125rem;
    font-weight: 500;
    letter-spacing: .05em;
    text-transform: uppercase;
    text-decoration: none;
    transition: border-color .15s, color .15s, background .15s;
}
.about-btn-secondary:hover {
    border-color: rgba(255,255,255,.40);
    color: #fff !important;
    background: rgba(255,255,255,.05);
}

@@media (max-width: 640px) {
    .about-hero { padding: 60px 20px 56px; }
    .about-stats { grid-template-columns: 1fr; }
    .about-stat { border-right: none; border-bottom: 1px solid var(--border); }
    .about-stat:last-child { border-bottom: none; }
    .about-stats-wrap, .about-body, .about-cta-wrap { padding: 0 16px; }
    .about-cta { padding: 40px 24px; }
}
</style>
@endpush

@section('content')
<div class="about">

    {{-- ── HERO ── --}}
    <div class="about-hero">
        <div class="about-hero-inner">
            <div class="about-badge">
                <span class="about-badge-dot"></span>
                À propos de MelanoGeek
            </div>
            <h1 class="about-hero-title">
                @if($settings['about_title'])
                    {!! nl2br(e($settings['about_title'])) !!}
                @else
                    La culture <span class="grad">geek</span>,<br>vue d'Afrique
                @endif
            </h1>
            @if($settings['about_tagline'])
                <p class="about-hero-lead">{{ $settings['about_tagline'] }}</p>
            @else
                <p class="about-hero-lead">
                    Une communauté africaine autour du manga, du gaming, de la tech et de la culture nerd. Blog, forum, débats — par et pour les geeks du continent.
                </p>
            @endif
        </div>
    </div>

    {{-- ── STATS ── --}}
    @php
        $statMembers  = \App\Models\User::where('is_active', true)->where('role', '!=', 'owner')->count();
        $statPosts    = \App\Models\Post::where('is_published', true)->count();
        $statComments = \App\Models\Comment::count();
    @endphp
    <div class="about-stats-wrap">
        <div class="about-stats">
            <div class="about-stat">
                <div class="about-stat-n">{{ number_format($statMembers) }}<span>+</span></div>
                <div class="about-stat-l">Membres</div>
            </div>
            <div class="about-stat">
                <div class="about-stat-n">{{ number_format($statPosts) }}<span>+</span></div>
                <div class="about-stat-l">Publications</div>
            </div>
            <div class="about-stat">
                <div class="about-stat-n">{{ number_format($statComments) }}<span>+</span></div>
                <div class="about-stat-l">Commentaires</div>
            </div>
        </div>
    </div>

    {{-- ── CONTENT ── --}}
    <div class="about-body">

        @if($settings['about_mission'])
        <div class="about-block">
            <div class="about-block-label">Notre mission</div>
            <p class="about-block-text">{{ $settings['about_mission'] }}</p>
        </div>
        @if($settings['about_story'] || $settings['about_values'])
            <div class="about-sep"><span class="about-sep-icon">✦</span></div>
        @endif
        @endif

        @if($settings['about_story'])
        <div class="about-block">
            <div class="about-block-label">Notre histoire</div>
            <p class="about-block-text">{{ $settings['about_story'] }}</p>
        </div>
        @if($settings['about_values'])
            <div class="about-sep"><span class="about-sep-icon">✦</span></div>
        @endif
        @endif

        @if($settings['about_values'])
        <div class="about-block">
            <div class="about-block-label">Nos valeurs</div>
            <p class="about-block-text">{{ $settings['about_values'] }}</p>
        </div>
        @endif

        @if(!$settings['about_mission'] && !$settings['about_story'] && !$settings['about_values'])
        <div class="about-empty">La page est en cours de rédaction. Revenez bientôt.</div>
        @endif

    </div>

    {{-- ── CTA ── --}}
    <div class="about-cta-wrap">
        <div class="about-cta">
            <div class="about-cta-title">Rejoins la communauté</div>
            <p class="about-cta-desc">
                Que tu sois passionné de manga, de gaming ou de tech africaine — MelanoGeek est fait pour toi.
            </p>
            <div class="about-cta-btns">
                @guest
                    <a href="{{ route('register') }}" class="about-btn-primary">Créer un compte →</a>
                    <a href="{{ route('blog.index') }}" class="about-btn-secondary">Lire le blog</a>
                @else
                    <a href="{{ route('home') }}" class="about-btn-primary">Accueil →</a>
                    <a href="{{ route('blog.index') }}" class="about-btn-secondary">Lire le blog</a>
                @endguest
            </div>
        </div>
    </div>

</div>
@endsection
