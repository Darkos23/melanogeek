@extends('layouts.app')

@section('title', 'Analytics — ' . config('app.name'))

@push('styles')
<style>
.analytics-wrap {
    max-width: 900px;
    margin: 0 auto;
    padding: 100px 24px 60px;
}

.analytics-title {
    font-family: var(--font-head);
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 28px;
}

/* ── Grille de stats ── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 14px;
    margin-bottom: 32px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px 18px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: border-color .2s, transform .2s;
}
.stat-card:hover { border-color: var(--border-hover); transform: translateY(-2px); }

.stat-label {
    font-size: .72rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .06em;
}
.stat-value {
    font-family: var(--font-head);
    font-size: 1.9rem;
    font-weight: 800;
    color: var(--text);
    line-height: 1;
}
.stat-value.terra  { color: var(--terra); }
.stat-value.gold   { color: var(--gold); }

/* ── Graphique barres ── */
.chart-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 22px 22px 18px;
    margin-bottom: 28px;
}
.chart-header {
    font-family: var(--font-head);
    font-size: .9rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 20px;
}

.bar-chart {
    display: flex;
    align-items: flex-end;
    gap: 10px;
    height: 120px;
}
.bar-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    height: 100%;
    justify-content: flex-end;
}
.bar {
    width: 100%;
    background: var(--terra);
    border-radius: 5px 5px 0 0;
    min-height: 4px;
    transition: opacity .2s;
    position: relative;
}
.bar:hover { opacity: .8; }
.bar-count {
    font-size: .68rem;
    font-weight: 700;
    color: var(--text-muted);
    position: absolute;
    top: -18px;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
}
.bar-label {
    font-size: .65rem;
    color: var(--text-muted);
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
}
.bar-zero { background: var(--border); }

/* ── Top posts ── */
.top-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 28px;
}
.top-header {
    font-family: var(--font-head);
    font-size: .9rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 16px;
}

.top-post {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    color: var(--text);
    transition: background .15s;
}
.top-post:last-child { border-bottom: none; }
.top-post:hover { opacity: .8; }

.top-rank {
    font-family: var(--font-head);
    font-size: .8rem;
    font-weight: 800;
    color: var(--text-faint);
    width: 20px;
    flex-shrink: 0;
    text-align: center;
}
.top-rank.gold-rank { color: var(--gold); }

.top-content {
    flex: 1;
    font-size: .83rem;
    color: var(--text-muted);
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.top-content em {
    font-style: normal;
    font-size: .7rem;
    display: block;
    color: var(--text-faint);
    margin-top: 2px;
}

.top-stats {
    display: flex;
    gap: 14px;
    flex-shrink: 0;
}
.top-stat {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .78rem;
    color: var(--text-muted);
    font-weight: 600;
}
.top-stat svg { opacity: .6; }

/* ── Vide ── */
.empty-box {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
    font-size: .85rem;
}
.empty-box .icon { font-size: 2rem; margin-bottom: 8px; }
</style>
@endpush

@section('content')
<div class="analytics-wrap">

    <div class="analytics-title">📊 Analytics</div>

    {{-- ── Stats globales ── --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-label">Publications</div>
            <div class="stat-value terra">{{ $totalPosts }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Brouillons</div>
            <div class="stat-value">{{ $totalDrafts }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">J'aime reçus</div>
            <div class="stat-value gold">{{ number_format($totalLikes) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Commentaires</div>
            <div class="stat-value">{{ number_format($totalComments) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Abonnés</div>
            <div class="stat-value terra">{{ number_format($totalFollowers) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Abonnements</div>
            <div class="stat-value">{{ $totalFollowing }}</div>
        </div>
        @if($servicesCount !== null)
        <div class="stat-card">
            <div class="stat-label">Services</div>
            <div class="stat-value">{{ $servicesCount }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Commandes reçues</div>
            <div class="stat-value gold">{{ $ordersReceived }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Terminées</div>
            <div class="stat-value terra">{{ $ordersCompleted }}</div>
        </div>
        @endif
    </div>

    {{-- ── Graphique publications par mois ── --}}
    <div class="chart-section">
        <div class="chart-header">Publications par mois (6 derniers mois)</div>

        @php
            $maxCount = max(collect($months)->pluck('count')->max(), 1);
        @endphp

        @if($maxCount === 1 && collect($months)->sum('count') === 0)
            <div class="empty-box" style="padding:20px;">
                <div class="icon">📭</div>
                Aucune publication sur les 6 derniers mois.
            </div>
        @else
        <div class="bar-chart">
            @foreach($months as $m)
            @php $height = round(($m['count'] / $maxCount) * 100); @endphp
            <div class="bar-col">
                <div class="bar {{ $m['count'] === 0 ? 'bar-zero' : '' }}"
                     style="height:{{ max($height, 3) }}%;">
                    @if($m['count'] > 0)
                    <span class="bar-count">{{ $m['count'] }}</span>
                    @endif
                </div>
                <div class="bar-label">{{ $m['label'] }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── Top 5 posts ── --}}
    <div class="top-section">
        <div class="top-header">Top 5 publications</div>

        @forelse($topPosts as $i => $post)
        <a href="{{ route('posts.show', $post->id) }}" class="top-post">
            <div class="top-rank {{ $i === 0 ? 'gold-rank' : '' }}">{{ $i + 1 }}</div>
            <div class="top-content">
                {{ Str::limit(strip_tags($post->content), 60) ?: '(média sans texte)' }}
                <em>{{ $post->created_at->diffForHumans() }}</em>
            </div>
            <div class="top-stats">
                <div class="top-stat">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="var(--terra)" stroke="none">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    {{ number_format($post->likes_count) }}
                </div>
                <div class="top-stat">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    {{ number_format($post->comments_count) }}
                </div>
            </div>
        </a>
        @empty
        <div class="empty-box">
            <div class="icon">✍️</div>
            Publie du contenu pour voir tes statistiques.
        </div>
        @endforelse
    </div>

</div>
@endsection
