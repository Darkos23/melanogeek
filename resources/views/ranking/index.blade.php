@extends('layouts.app')

@section('title', 'Classement des créateurs')

@push('styles')
<style>
/* ══ WRAP ══ */
.rk-wrap {
    padding: 88px 20px 80px;
    max-width: 900px;
    margin: 0 auto;
    min-height: 100vh;
}

/* ══ HERO ══ */
.rk-hero { margin-bottom: 36px; }
.rk-eyebrow {
    font-family: var(--font-body);
    font-size: .62rem;
    font-weight: 600;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--terra);
    margin-bottom: 6px;
}
.rk-title {
    font-family: var(--font-head);
    font-size: clamp(1.6rem, 4vw, 2.4rem);
    font-weight: 900;
    letter-spacing: -.035em;
    color: var(--text);
    line-height: 1.1;
    margin-bottom: 8px;
}
.rk-title span { color: var(--terra); }
.rk-sub {
    font-size: .86rem;
    color: var(--text-muted);
    font-family: var(--font-body);
}

/* ══ FILTERS ══ */
.rk-filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 40px;
    align-items: center;
}
.rk-period-group { display: flex; gap: 4px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 100px; padding: 3px; }
.rk-period-btn {
    font-family: var(--font-body);
    font-size: .76rem;
    font-weight: 600;
    padding: 7px 18px;
    border-radius: 100px;
    border: none;
    cursor: pointer;
    color: var(--text-muted);
    background: transparent;
    transition: all .2s;
    text-decoration: none;
    display: inline-block;
}
.rk-period-btn.active { background: var(--terra); color: white; }
.rk-period-btn:hover:not(.active) { color: var(--text); }

.rk-niche-select {
    font-family: var(--font-body);
    font-size: .8rem;
    padding: 9px 16px;
    border-radius: 100px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text);
    outline: none;
    cursor: pointer;
    transition: border-color .2s;
}
.rk-niche-select:focus { border-color: var(--terra); }

/* ══ PODIUM ══ */
.rk-podium {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
    margin-bottom: 36px;
    align-items: end;
}
.pod-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 24px 16px 20px;
    text-align: center;
    text-decoration: none;
    color: inherit;
    display: block;
    position: relative;
    transition: transform .25s, box-shadow .25s, border-color .25s;
}
.pod-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(30,14,4,.1); border-color: rgba(200,72,24,.25); }

/* 1er = plus grand */
.pod-card.p1 { padding-top: 32px; border-color: rgba(212,168,67,.4); background: linear-gradient(160deg, var(--bg-card) 0%, rgba(212,168,67,.05) 100%); }
.pod-card.p1:hover { border-color: var(--gold); box-shadow: 0 20px 50px rgba(212,168,67,.15); }
.pod-card.p2 { order: -1; }

.pod-medal {
    position: absolute;
    top: -14px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 1.6rem;
    line-height: 1;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,.15));
}
.pod-medal.p1 { font-size: 2rem; top: -18px; }

.pod-av {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    font-weight: 800;
    color: white;
    overflow: hidden;
    position: relative;
}
.pod-card.p1 .pod-av {
    width: 76px;
    height: 76px;
    font-size: 2rem;
    box-shadow: 0 0 0 3px var(--gold), 0 0 0 6px rgba(212,168,67,.15);
}
.pod-av img { width: 100%; height: 100%; object-fit: cover; }

.pod-rank {
    font-family: var(--font-head);
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-faint);
    margin-bottom: 4px;
}
.pod-name {
    font-family: var(--font-head);
    font-size: .82rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.pod-niche {
    font-size: .68rem;
    color: var(--text-muted);
    margin-bottom: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.pod-count {
    font-family: var(--font-head);
    font-size: 1.5rem;
    font-weight: 900;
    color: var(--terra);
    line-height: 1;
}
.pod-card.p1 .pod-count { font-size: 1.8rem; }
.pod-count-label {
    font-family: var(--font-body);
    font-size: .6rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-faint);
    margin-top: 2px;
}

/* ══ LISTE (rang 4+) ══ */
.rk-list { display: flex; flex-direction: column; gap: 8px; }
.rk-item {
    display: flex;
    align-items: center;
    gap: 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 14px 18px;
    text-decoration: none;
    color: inherit;
    transition: border-color .2s, transform .2s, box-shadow .2s;
}
.rk-item:hover { border-color: rgba(200,72,24,.22); transform: translateX(3px); box-shadow: 0 4px 16px rgba(30,14,4,.07); }

.rk-num {
    font-family: var(--font-head);
    font-size: .8rem;
    font-weight: 900;
    color: var(--text-faint);
    width: 28px;
    text-align: center;
    flex-shrink: 0;
}
.rk-av {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
    overflow: hidden;
    flex-shrink: 0;
}
.rk-av img { width: 100%; height: 100%; object-fit: cover; }
.rk-info { flex: 1; min-width: 0; }
.rk-uname {
    font-family: var(--font-head);
    font-size: .82rem;
    font-weight: 700;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.rk-uniche {
    font-size: .68rem;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.rk-badge {
    font-family: var(--font-head);
    font-size: .54rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    padding: 2px 8px;
    border-radius: 100px;
    flex-shrink: 0;
}
.rk-badge.v { background: rgba(200,72,24,.08); border: 1px solid rgba(200,72,24,.2); color: var(--terra); }
.rk-badge.n { background: var(--bg); border: 1px solid var(--border); color: var(--text-faint); }

.rk-stat { text-align: right; flex-shrink: 0; }
.rk-stat-n {
    font-family: var(--font-head);
    font-size: .9rem;
    font-weight: 800;
    color: var(--text);
    line-height: 1;
}
.rk-stat-l {
    font-size: .58rem;
    font-weight: 600;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--text-faint);
}

/* ══ VIDE ══ */
.rk-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
    font-size: .92rem;
}
.rk-empty-icon { font-size: 2.5rem; margin-bottom: 12px; }

/* ══ RESPONSIVE ══ */
@media (max-width: 620px) {
    .rk-wrap { padding: 76px 16px 60px; }
    .rk-podium { grid-template-columns: 1fr; gap: 20px; }
    .pod-card.p2 { order: 0; }
    .pod-card.p1 { order: -1; }
    /* Filtres : empilés verticalement */
    .rk-filters { flex-direction: column; align-items: stretch; gap: 8px; }
    .rk-niche-select { width: 100%; }
    .rk-period-group { justify-content: center; }
    .rk-period-btn { padding: 7px 12px; }
}
</style>
@endpush

@section('content')
@php
    $avatarColors = ['#8B5E3C','#2D5A3D','#C84818','#4A3728'];
    $fmt = fn($n) => $n >= 1000000 ? number_format($n/1000000,1).'M' : ($n >= 1000 ? number_format($n/1000,1).'k' : $n);

    $periodLabel = match($period) {
        'week'  => 'cette semaine',
        'month' => 'ce mois',
        default => 'all-time',
    };
    $countLabel = match($period) {
        'week', 'month' => 'Nouveaux abonnés',
        default         => 'Abonnés',
    };

    $buildUrl = fn($p, $n) => route('ranking', array_filter(['period' => $p !== 'all' ? $p : null, 'niche' => $n ?: null]));

    $nicheEmojis = [
        'Musique'=>'🎵','Photographie'=>'📸','Mode & Style'=>'👗','Beauté & Soins'=>'💄',
        'Cuisine'=>'🍽️','Vidéo & Vlog'=>'🎬','Art & Illustration'=>'🎨','Danse'=>'💃',
        'Comédie & Humour'=>'😂','Business'=>'💼','Voyage & Culture'=>'🌍','Sport & Fitness'=>'⚽',
        'Artisanat'=>'🪡','Éducation'=>'📚','Podcast'=>'🎙️','Lifestyle'=>'✨',
        'Photographe'=>'📸','Musicien'=>'🎵','Vidéaste'=>'🎬','Artiste digital'=>'🎨',
        'Styliste'=>'👗','Danseur'=>'💃','Cuisinier'=>'🍽️','Podcasteur'=>'🎙️',
    ];
@endphp

<div class="rk-wrap">

    {{-- HERO --}}
    <div class="rk-hero">
        <div class="rk-eyebrow">🏆 Classement</div>
        <div class="rk-title">Les créateurs qui <span>brillent.</span></div>
        <div class="rk-sub">Top créateurs {{ $periodLabel }}{{ $niche ? ' · ' . $niche : '' }}</div>
    </div>

    {{-- FILTERS --}}
    <div class="rk-filters">
        <div class="rk-period-group">
            <a href="{{ $buildUrl('all', $niche) }}" class="rk-period-btn {{ $period === 'all'   ? 'active' : '' }}">All-time</a>
            <a href="{{ $buildUrl('month', $niche) }}" class="rk-period-btn {{ $period === 'month' ? 'active' : '' }}">Ce mois</a>
            <a href="{{ $buildUrl('week', $niche) }}" class="rk-period-btn {{ $period === 'week'  ? 'active' : '' }}">Cette semaine</a>
        </div>

        @if($niches->isNotEmpty())
        <form method="GET" action="{{ route('ranking') }}" style="display:contents">
            @if($period !== 'all')<input type="hidden" name="period" value="{{ $period }}">@endif
            <select name="niche" class="rk-niche-select" onchange="this.form.submit()">
                <option value="">Toutes les niches</option>
                @foreach($niches as $n)
                <option value="{{ $n }}" {{ $niche === $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    @if($creators->isEmpty())
        <div class="rk-empty">
            <div class="rk-empty-icon">🔍</div>
            <div>Aucun créateur trouvé pour cette période.</div>
        </div>
    @else

    {{-- PODIUM TOP 3 --}}
    @if($podium->count() >= 2)
    <div class="rk-podium">
        @foreach($podium as $i => $cr)
        @php
            $rank     = $i + 1;
            $pClass   = 'p' . $rank;
            $medals   = ['🥇','🥈','🥉'];
            $medal    = $medals[$i] ?? '';
            $bg       = $avatarColors[$i % count($avatarColors)];
            $score    = $period !== 'all' ? ($cr->period_followers ?? 0) : $cr->followers_count;
        @endphp
        <a href="{{ route('profile.show', $cr->username) }}" class="pod-card {{ $pClass }}">
            <div class="pod-medal {{ $pClass }}">{{ $medal }}</div>
            <div class="pod-av" style="background:{{ $bg }}">
                @if($cr->avatar)
                    <img src="{{ asset('storage/'.$cr->avatar) }}" alt="">
                @else
                    {{ mb_strtoupper(mb_substr($cr->name ?? $cr->username, 0, 1)) }}
                @endif
            </div>
            <div class="pod-rank">#{{ $rank }}</div>
            <div class="pod-name">{{ $cr->name ?? $cr->username }}</div>
            <div class="pod-niche">{{ ($nicheEmojis[$cr->niche ?? ''] ?? '🌟') }} {{ $cr->niche ?: 'Créateur' }}</div>
            <div class="pod-count">{{ $fmt($score) }}</div>
            <div class="pod-count-label">{{ $countLabel }}</div>
        </a>
        @endforeach
    </div>
    @endif

    {{-- LISTE RANG 4+ --}}
    @if($rest->isNotEmpty())
    <div class="rk-list">
        @foreach($rest as $i => $cr)
        @php
            $rank  = $i + 4;
            $bg    = $avatarColors[$rank % count($avatarColors)];
            $score = $period !== 'all' ? ($cr->period_followers ?? 0) : $cr->followers_count;
        @endphp
        <a href="{{ route('profile.show', $cr->username) }}" class="rk-item">
            <div class="rk-num">#{{ $rank }}</div>
            <div class="rk-av" style="background:{{ $bg }}">
                @if($cr->avatar)
                    <img src="{{ asset('storage/'.$cr->avatar) }}" alt="">
                @else
                    {{ mb_strtoupper(mb_substr($cr->name ?? $cr->username, 0, 1)) }}
                @endif
            </div>
            <div class="rk-info">
                <div class="rk-uname">{{ $cr->name ?? $cr->username }}</div>
                <div class="rk-uniche">{{ ($nicheEmojis[$cr->niche ?? ''] ?? '🌟') }} {{ $cr->niche ?: 'Créateur' }}</div>
            </div>
            <span class="rk-badge {{ $cr->is_verified ? 'v' : 'n' }}">
                {{ $cr->is_verified ? '✓ Vérifié' : 'Nouveau' }}
            </span>
            <div class="rk-stat">
                <div class="rk-stat-n">{{ $fmt($score) }}</div>
                <div class="rk-stat-l">{{ $countLabel }}</div>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    @endif {{-- /isEmpty --}}
</div>
@endsection
