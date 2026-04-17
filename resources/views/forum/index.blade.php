@extends('layouts.blog')

@section('title', 'Forum — MelanoGeek')

@push('styles')
<style>
/* ══ FORUM ══ */

/* ── EN-TÊTE ── */
.forum-header { margin-bottom: 32px; }
.forum-eyebrow {
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 8px;
}
.forum-title {
    font-family: var(--font-head);
    font-size: clamp(1.6rem, 3vw, 2.4rem);
    font-weight: 800;
    letter-spacing: -.03em;
    line-height: 1.1;
    color: var(--text);
    margin-bottom: 10px;
}
.forum-lead {
    font-size: .82rem;
    color: var(--text-muted);
    line-height: 1.65;
    max-width: 520px;
}

/* ── BARRE D'ACTION ── */
.forum-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 28px;
    flex-wrap: wrap;
}
.forum-filters { display: flex; gap: 4px; }
.forum-filter {
    padding: 7px 14px;
    border-radius: 7px;
    font-size: .7rem;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-muted);
    transition: border-color .18s, color .18s, background .18s;
}
.forum-filter:hover,
.forum-filter.active {
    border-color: rgba(255,255,255,.25);
    color: var(--text);
    background: rgba(255,255,255,.05);
}
.forum-filter.active {
    border-color: rgba(255,255,255,.30);
    color: var(--text);
}
.forum-new-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255,255,255,.90);
    color: rgba(0,0,0,.90) !important;
    border: none;
    padding: 0 16px;
    height: 36px;
    border-radius: 9999px;
    font-family: var(--font-head);
    font-size: .72rem;
    font-weight: 500;
    letter-spacing: .05em;
    text-transform: uppercase;
    text-decoration: none;
    transition: background .15s;
}
.forum-new-btn:hover { background: #fff; }

/* ── CATÉGORIES ── */
.forum-cats-label {
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-faint);
    margin-bottom: 12px;
}
.forum-cats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    background: var(--border);
    margin-bottom: 28px;
}
.forum-cat {
    background: var(--bg-card);
    padding: 18px 20px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: background .16s;
}
.forum-cat:hover { background: var(--bg-hover); }
.forum-cat-icon {
    width: 38px; height: 38px;
    border-radius: 9px;
    background: var(--bg-card2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.forum-cat-name {
    font-size: .76rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 2px;
}
.forum-cat-desc {
    font-size: .6rem;
    color: var(--text-muted);
}
.forum-cat-count {
    font-family: var(--font-head);
    font-size: .75rem;
    font-weight: 700;
    color: var(--text-faint);
    margin-left: auto;
    flex-shrink: 0;
}

/* ── THREADS ── */
.forum-threads {
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 28px;
}
.forum-threads-head {
    display: grid;
    grid-template-columns: 1fr 72px 72px 110px;
    padding: 10px 22px;
    border-bottom: 1px solid var(--border);
    background: var(--bg-card2);
}
.forum-col-label {
    font-size: .56rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-faint);
}
.forum-col-label.center { text-align: center; }
.forum-col-label.right  { text-align: right; }

.forum-thread {
    display: grid;
    grid-template-columns: 1fr 72px 72px 110px;
    align-items: center;
    padding: 15px 22px;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    background: var(--bg-card);
    transition: background .15s;
}
.forum-thread:last-child { border-bottom: none; }
.forum-thread:hover { background: var(--bg-hover); }

.forum-thread-main { display: flex; align-items: flex-start; gap: 12px; min-width: 0; }
.forum-thread-avi {
    width: 34px; height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .82rem;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}
.forum-thread-meta { display: flex; align-items: center; gap: 5px; margin-bottom: 4px; flex-wrap: wrap; }
.forum-tag {
    font-size: .54rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 2px 6px;
    border-radius: 4px;
}
.forum-tag-cat {
    color: rgba(255,255,255,.55);
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.10);
}
.forum-tag-pin {
    color: var(--gold);
    background: var(--gold-soft);
    border: 1px solid rgba(184,120,32,.2);
}
.forum-thread-title {
    font-size: .78rem;
    font-weight: 600;
    color: var(--text);
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.forum-thread-author { font-size: .6rem; color: var(--text-muted); margin-top: 3px; }

.forum-thread-num {
    text-align: center;
}
.forum-thread-num-val {
    font-family: var(--font-head);
    font-size: .8rem;
    font-weight: 700;
    color: var(--text);
}
.forum-thread-num-lbl {
    font-size: .52rem;
    color: var(--text-faint);
    text-transform: uppercase;
    letter-spacing: .05em;
}
.forum-thread-time {
    text-align: right;
    font-size: .63rem;
    color: var(--text-muted);
}

/* ── PAGINATION ── */
.forum-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.forum-page-info { font-size: .68rem; color: var(--text-muted); }
.forum-page-info strong { color: var(--text); }
.forum-pages { display: flex; gap: 4px; }
.forum-page {
    width: 32px; height: 32px;
    border-radius: 7px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .73rem;
    color: var(--text-muted);
    text-decoration: none;
    transition: border-color .15s, color .15s;
}
.forum-page:hover { border-color: rgba(255,255,255,.25); color: var(--text); }
.forum-page.active {
    border-color: rgba(255,255,255,.30);
    background: rgba(255,255,255,.07);
    color: var(--text);
    font-weight: 600;
}
.forum-page.disabled { opacity: .3; pointer-events: none; }
.forum-page-dots { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: .73rem; color: var(--text-faint); }

/* ── SIDEBAR WIDGETS ── */
.forum-widget {
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bg-card);
    margin-bottom: 16px;
}
.forum-widget-head {
    padding: 13px 16px;
    border-bottom: 1px solid var(--border);
    font-family: var(--font-head);
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-muted);
}
.forum-widget-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1px;
    background: var(--border);
}
.forum-widget-stat {
    background: var(--bg-card);
    padding: 16px 14px;
    text-align: center;
}
.forum-widget-stat-val {
    font-family: var(--font-head);
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -.02em;
}
.forum-widget-stat-lbl {
    font-size: .56rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .07em;
    margin-top: 3px;
}
.forum-widget-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    color: var(--text-muted);
    font-size: .74rem;
    transition: background .15s, color .15s;
}
.forum-widget-row:last-child { border-bottom: none; }
.forum-widget-row:hover { background: var(--bg-hover); color: var(--text); }
.forum-widget-row-count {
    font-family: var(--font-head);
    font-size: .65rem;
    font-weight: 700;
    color: var(--text-faint);
    margin-left: auto;
}
.forum-contrib-avi {
    width: 28px; height: 28px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .72rem;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}
.forum-contrib-name { font-size: .74rem; font-weight: 600; color: var(--text); }
.forum-contrib-sub { font-size: .59rem; color: var(--text-muted); }

.forum-widget-cta {
    padding: 20px 18px;
    text-align: center;
}
.forum-widget-cta-icon { font-size: 1.5rem; margin-bottom: 10px; }
.forum-widget-cta-title {
    font-family: var(--font-head);
    font-size: .8rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 8px;
}
.forum-widget-cta-desc {
    font-size: .68rem;
    color: var(--text-muted);
    line-height: 1.55;
    margin-bottom: 14px;
}
.forum-widget-cta-btn {
    display: block;
    background: rgba(255,255,255,.90);
    color: rgba(0,0,0,.90) !important;
    text-align: center;
    padding: 0 16px;
    height: 36px;
    line-height: 36px;
    border-radius: 9999px;
    font-family: var(--font-head);
    font-size: .72rem;
    font-weight: 500;
    letter-spacing: .05em;
    text-transform: uppercase;
    text-decoration: none;
    transition: background .15s;
}
.forum-widget-cta-btn:hover { background: #fff; }

@media (max-width: 640px) {
    .forum-cats { grid-template-columns: 1fr 1fr; }
    .forum-threads-head { display: none; }
    .forum-thread { grid-template-columns: 1fr 60px 80px; }
    .forum-thread-num:nth-child(3) { display: none; }
}
@media (max-width: 480px) {
    .forum-cats { grid-template-columns: 1fr; }
    .forum-thread { grid-template-columns: 1fr; gap: 4px; }
    .forum-thread-num { display: none; }
    .forum-thread-last { display: none; }
}
</style>
@endpush

@section('main')

@php $currentSort = $sort ?? ''; $currentCat = $cat ?? ''; @endphp

{{-- ── EN-TÊTE ── --}}
<div class="forum-header">
    <div class="forum-eyebrow">Communauté</div>
    <h1 class="forum-title">Le forum des geeks africains</h1>
    <p class="forum-lead">Débats, recommandations, créations et discussions dans une ambiance bienveillante.</p>
</div>

{{-- ── BARRE D'ACTION ── --}}
<div class="forum-bar">
    <div class="forum-filters">
        @foreach(['Tous' => '', 'Récents' => 'recent', 'Populaires' => 'popular', 'Sans réponse' => 'unanswered'] as $label => $val)
        <a href="{{ route('forum.index') }}{{ $val ? '?sort='.$val : '' }}"
           class="forum-filter {{ $currentSort === $val ? 'active' : '' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    @auth
    <a href="{{ route('forum.create') }}" class="forum-new-btn">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Nouveau sujet
    </a>
    @else
    <a href="{{ route('login') }}" class="forum-new-btn">Connexion pour poster</a>
    @endauth
</div>

{{-- ── CATÉGORIES ── --}}
<div class="forum-cats-label">Catégories</div>
<div class="forum-cats">
    @foreach($categories as $slug => $info)
    <a href="{{ route('forum.index') }}?cat={{ $slug }}{{ $currentSort ? '&sort='.$currentSort : '' }}"
       class="forum-cat {{ $currentCat === $slug ? 'forum-cat-active' : '' }}">
        <div class="forum-cat-icon">{{ $info['icon'] }}</div>
        <div style="flex:1;min-width:0">
            <div class="forum-cat-name">{{ $info['label'] }}</div>
        </div>
    </a>
    @endforeach
</div>

{{-- ── THREADS ── --}}
<div class="forum-threads">
    <div class="forum-threads-head">
        <div class="forum-col-label">Sujet</div>
        <div class="forum-col-label center">Rép.</div>
        <div class="forum-col-label center">Vues</div>
        <div class="forum-col-label right">Activité</div>
    </div>

    @forelse($threads as $thread)
    <a href="{{ route('forum.show', $thread) }}" class="forum-thread" data-reveal data-delay="{{ ($loop->index % 5) + 1 }}">
        <div class="forum-thread-main">
            <div class="forum-thread-avi" style="background:linear-gradient(135deg,var(--terra),var(--gold))">
                @if($thread->user->avatar)
                    <img src="{{ Storage::url($thread->user->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                @else
                    {{ mb_strtoupper(mb_substr($thread->user->name, 0, 1)) }}
                @endif
            </div>
            <div style="min-width:0">
                <div class="forum-thread-meta">
                    <span class="forum-tag forum-tag-cat">{{ $thread->category_label }}</span>
                    @if($thread->is_pinned)<span class="forum-tag forum-tag-pin">📌 Épinglé</span>@endif
                    @if($thread->replies_count > 20)<span style="font-size:.7rem">🔥</span>@endif
                </div>
                <div class="forum-thread-title">{{ $thread->title }}</div>
                <div class="forum-thread-author">par {{ $thread->user->name }}</div>
            </div>
        </div>
        <div class="forum-thread-num">
            <div class="forum-thread-num-val">{{ number_format($thread->replies_count) }}</div>
            <div class="forum-thread-num-lbl">rép.</div>
        </div>
        <div class="forum-thread-num">
            <div class="forum-thread-num-val" style="font-family:inherit;font-size:.76rem;font-weight:500;color:var(--text-muted)">{{ number_format($thread->views_count) }}</div>
            <div class="forum-thread-num-lbl">vues</div>
        </div>
        <div class="forum-thread-time">{{ $thread->last_reply_at?->diffForHumans(null, true) ?? $thread->created_at->diffForHumans(null, true) }}</div>
    </a>
    @empty
    <div style="padding:40px;text-align:center;color:var(--text-muted);">
        <div style="font-size:2rem;margin-bottom:8px;">💬</div>
        <div style="font-size:.85rem;">Aucun sujet pour l'instant. Sois le premier !</div>
    </div>
    @endforelse
</div>

{{-- ── PAGINATION ── --}}
@if($threads->hasPages())
<div class="forum-pagination">
    <div class="forum-page-info">
        Page <strong>{{ $threads->currentPage() }}</strong> sur <strong>{{ $threads->lastPage() }}</strong>
    </div>
    <div class="forum-pages">
        @if($threads->onFirstPage())
            <span class="forum-page disabled">‹</span>
        @else
            <a href="{{ $threads->previousPageUrl() }}" class="forum-page">‹</a>
        @endif
        @foreach($threads->getUrlRange(1, $threads->lastPage()) as $page => $url)
            @if($page == $threads->currentPage())
                <span class="forum-page active">{{ $page }}</span>
            @elseif(abs($page - $threads->currentPage()) <= 2 || $page == 1 || $page == $threads->lastPage())
                <a href="{{ $url }}" class="forum-page">{{ $page }}</a>
            @elseif(abs($page - $threads->currentPage()) == 3)
                <span class="forum-page-dots">…</span>
            @endif
        @endforeach
        @if($threads->hasMorePages())
            <a href="{{ $threads->nextPageUrl() }}" class="forum-page">›</a>
        @else
            <span class="forum-page disabled">›</span>
        @endif
    </div>
</div>
@endif

@endsection

@section('sidebar')

{{-- Stats --}}
<div class="forum-widget">
    <div class="forum-widget-head">Statistiques</div>
    <div class="forum-widget-stats">
        @foreach(['Sujets' => number_format($stats['threads']), 'Réponses' => number_format($stats['replies']), 'Membres' => number_format($stats['members']), 'En ligne' => '—'] as $lbl => $val)
        <div class="forum-widget-stat">
            <div class="forum-widget-stat-val">{{ $val }}</div>
            <div class="forum-widget-stat-lbl">{{ $lbl }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Catégories sidebar --}}
<div class="forum-widget">
    <div class="forum-widget-head">Catégories</div>
    @foreach($categories as $slug => $info)
    <a href="{{ route('forum.index') }}?cat={{ $slug }}" class="forum-widget-row">
        <span style="font-size:.95rem">{{ $info['icon'] }}</span>
        <span style="flex:1;font-weight:500">{{ $info['label'] }}</span>
    </a>
    @endforeach
</div>

{{-- Top contributeurs --}}
<div class="forum-widget">
    <div class="forum-widget-head">Top contributeurs</div>
    @forelse($topContributors as $contributor)
    <div class="forum-widget-row" style="cursor:default">
        <div class="forum-contrib-avi" style="background:linear-gradient(135deg,var(--terra),var(--gold))">
            @if($contributor->avatar)
                <img src="{{ Storage::url($contributor->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:7px;">
            @else
                {{ mb_strtoupper(mb_substr($contributor->name, 0, 1)) }}
            @endif
        </div>
        <div>
            <div class="forum-contrib-name">{{ $contributor->name }}</div>
            <div class="forum-contrib-sub">{{ $contributor->forum_threads_count }} sujets</div>
        </div>
    </div>
    @empty
    <div style="padding:16px;text-align:center;font-size:.78rem;color:var(--text-muted);">Aucun contributeur encore.</div>
    @endforelse
</div>

{{-- CTA --}}
@guest
<div class="forum-widget">
    <div class="forum-widget-cta">
        <div class="forum-widget-cta-icon">🌍</div>
        <div class="forum-widget-cta-title">Rejoins la conversation</div>
        <p class="forum-widget-cta-desc">Crée ton compte pour poster, voter et participer aux débats.</p>
        <a href="{{ route('register') }}" class="forum-widget-cta-btn">Créer un compte</a>
    </div>
</div>
@endguest

@endsection
