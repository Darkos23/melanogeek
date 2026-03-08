@extends('layouts.app')

@section('title', 'Créateurs')

@push('styles')
<style>
    .creators-page {
        padding-top: 80px;
        min-height: 100vh;
    }

    /* ── HERO ── */
    .creators-hero {
        max-width: 1100px;
        margin: 0 auto;
        padding: 48px 32px 36px;
    }
    .creators-hero-title {
        font-family: var(--font-head);
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text);
        margin-bottom: 8px;
    }
    .creators-hero-title span { color: var(--gold); }
    .creators-hero-sub {
        font-size: .96rem;
        color: var(--text-muted);
        margin-bottom: 32px;
    }

    /* ── FILTERS ── */
    .creators-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }
    .filter-search-wrap { position: relative; flex: 1; min-width: 220px; max-width: 380px; }
    .filter-search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: .9rem; pointer-events: none; color: var(--text-faint); }
    .filter-search {
        width: 100%;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 100px;
        padding: 11px 20px 11px 44px;
        color: var(--text);
        font-family: var(--font-body);
        font-size: .88rem;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .filter-search::placeholder { color: var(--text-faint); }
    .filter-search:focus { border-color: var(--terra); box-shadow: 0 0 0 3px rgba(200,82,42,.1); }

    .filter-btn {
        background: var(--terra);
        border: none;
        color: white;
        padding: 11px 22px;
        border-radius: 100px;
        font-family: var(--font-body);
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, transform .15s;
        white-space: nowrap;
    }
    .filter-btn:hover { background: var(--accent); transform: translateY(-1px); }

    .filter-reset {
        color: var(--text-muted);
        text-decoration: none;
        font-size: .82rem;
        padding: 11px 16px;
        border-radius: 100px;
        border: 1px solid var(--border);
        transition: all .2s;
    }
    .filter-reset:hover { border-color: var(--border-hover); color: var(--text); }

    /* Niche pills */
    .niche-pills { display:flex; flex-wrap:wrap; gap:8px; margin-top:16px; }
    .niche-pill {
        padding: 6px 14px;
        border-radius: 100px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-muted);
        font-size: .78rem;
        font-weight: 500;
        text-decoration: none;
        transition: all .18s;
    }
    .niche-pill:hover { border-color: var(--terra); color: var(--terra); background: var(--terra-soft); }
    .niche-pill.active { border-color: var(--terra); background: var(--terra-soft); color: var(--terra); font-weight: 600; }

    /* ── GRID ── */
    .creators-body {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 32px 80px;
    }
    .creators-count {
        font-size: .8rem;
        color: var(--text-muted);
        margin-bottom: 20px;
    }
    .creators-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px;
    }

    /* ── CREATOR CARD — style Instagram explore ── */
    .creator-card {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        aspect-ratio: 3/4;
        cursor: pointer;
        text-decoration: none;
        display: block;
        background: var(--bg-card2);
    }

    /* Fond / cover plein */
    .creator-card-bg {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .45s ease;
    }
    .creator-card:hover .creator-card-bg { transform: scale(1.06); }

    /* Dégradé sombre en bas */
    .creator-card-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(
            to bottom,
            rgba(0,0,0,0) 30%,
            rgba(0,0,0,.55) 65%,
            rgba(0,0,0,.88) 100%
        );
        transition: opacity .3s;
    }

    /* Badge niche en haut à gauche */
    .creator-card-niche {
        position: absolute; top: 14px; left: 14px;
        background: rgba(0,0,0,.55);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.12);
        color: rgba(255,255,255,.9);
        font-size: .66rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 100px;
        letter-spacing: .03em;
    }

    /* Badge vérifié en haut à droite */
    .creator-verified {
        position: absolute; top: 14px; right: 14px;
        width: 26px; height: 26px;
        background: var(--terra);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .65rem;
        color: white;
        font-weight: 700;
    }

    /* Infos en bas */
    .creator-card-info {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 16px;
        display: flex; align-items: flex-end; gap: 12px;
    }
    .creator-card-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,.3);
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .creator-card-avatar-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
        background: #1A1208;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        font-weight: 700;
        color: white;
        overflow: hidden;
    }
    .creator-card-avatar-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }

    .creator-card-text { flex: 1; min-width: 0; }
    .creator-card-name {
        font-family: var(--font-head);
        font-size: .9rem;
        font-weight: 700;
        color: white;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        margin-bottom: 2px;
    }
    .creator-card-meta {
        font-size: .7rem;
        color: rgba(255,255,255,.65);
        display: flex; gap: 8px; flex-wrap: wrap;
    }

    /* Bouton follow flottant — visible au hover */
    .creator-card-follow-wrap {
        position: absolute; top: 14px; left: 50%; transform: translateX(-50%);
        opacity: 0; pointer-events: none;
        transition: opacity .25s, transform .25s;
        transform: translateX(-50%) translateY(-4px);
    }
    .creator-card:hover .creator-card-follow-wrap {
        opacity: 1; pointer-events: all;
        transform: translateX(-50%) translateY(0);
    }
    .creator-card-follow {
        background: white;
        color: #111;
        border: none;
        padding: 7px 18px;
        border-radius: 100px;
        font-family: var(--font-head);
        font-size: .78rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s, transform .15s;
        white-space: nowrap;
        text-decoration: none;
        display: inline-block;
    }
    .creator-card-follow:hover { background: var(--gold); transform: scale(1.04); }
    .creator-card-follow.following {
        background: rgba(255,255,255,.18);
        color: white;
        border: 1px solid rgba(255,255,255,.3);
        backdrop-filter: blur(6px);
    }
    .creator-card-follow.following:hover { background: rgba(224,85,85,.6); color: white; }

    /* Empty */
    .creators-empty {
        text-align: center;
        padding: 80px 20px;
        color: var(--text-muted);
    }
    .creators-empty-icon { font-size: 3rem; margin-bottom: 16px; }
    .creators-empty-title { font-family: var(--font-head); font-size: 1.2rem; font-weight: 700; color: var(--text); margin-bottom: 8px; }

    /* Pagination */
    .creators-pagination { margin-top: 40px; }

    @@media (max-width: 768px) {
        .creators-hero { padding: 32px 16px 24px; }
        .creators-body { padding: 0 16px 60px; }
        .creators-hero-title { font-size: 1.6rem; }
        .creators-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
    }
</style>
@endpush

@section('content')
<div class="creators-page">

    <!-- Hero + Filters -->
    <div class="creators-hero">
        <div class="creators-hero-title">Les <span>Créateurs</span></div>
        <div class="creators-hero-sub">Découvre les talents de la communauté MelanoGeek</div>

        <form method="GET" class="creators-filters">
            <div class="filter-search-wrap">
                <span class="filter-search-icon">🔍</span>
                <input type="text" name="search" class="filter-search"
                    placeholder="Rechercher un créateur..."
                    value="{{ request('search') }}">
            </div>
            <button type="submit" class="filter-btn">Rechercher</button>
            @if(request('search') || request('niche'))
                <a href="{{ route('creators') }}" class="filter-reset">✕</a>
            @endif
        </form>

        {{-- Pills niches --}}
        @if($niches->isNotEmpty())
        <div class="niche-pills">
            <a href="{{ route('creators', array_filter(['search' => request('search')])) }}"
               class="niche-pill {{ !request('niche') ? 'active' : '' }}">Tous</a>
            @foreach($niches as $n)
                <a href="{{ route('creators', array_filter(['search' => request('search'), 'niche' => $n])) }}"
                   class="niche-pill {{ request('niche') === $n ? 'active' : '' }}">{{ $n }}</a>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Grid -->
    <div class="creators-body">

        <div class="creators-count">{{ $creators->total() }} créateur{{ $creators->total() > 1 ? 's' : '' }}</div>

        @if($creators->isEmpty())
            <div class="creators-empty">
                <div class="creators-empty-icon">🔍</div>
                <div class="creators-empty-title">Aucun créateur trouvé</div>
                <div>Essaie d'autres mots-clés ou une autre niche.</div>
            </div>
        @else
            <div class="creators-grid">
                @php
                    $gradients = [
                        ['#2D1B0E','#8B3A20'],['#1A2A1E','#2D5A3D'],
                        ['#1A1530','#3B2D6B'],['#2A1A08','#6B4010'],
                        ['#1E1010','#5A1E1E'],['#0D1F15','#1D5A3A'],
                    ];
                @endphp
                @foreach($creators as $creator)
                @php $g = $gradients[$loop->index % count($gradients)]; @endphp
                <a class="creator-card" href="{{ route('profile.show', $creator->username) }}">

                    {{-- Fond : cover photo ou dégradé --}}
                    @if($creator->cover_photo)
                        <img class="creator-card-bg" src="{{ Storage::url($creator->cover_photo) }}" alt="">
                    @elseif($creator->avatar)
                        <img class="creator-card-bg" src="{{ Storage::url($creator->avatar) }}" alt="" style="filter:blur(4px) brightness(.7);transform:scale(1.1);">
                    @else
                        <div class="creator-card-bg" style="background:linear-gradient(160deg,{{ $g[0] }},{{ $g[1] }});"></div>
                    @endif

                    {{-- Overlay gradient --}}
                    <div class="creator-card-overlay"></div>

                    {{-- Badge niche --}}
                    @if($creator->niche)
                        <div class="creator-card-niche">{{ $creator->niche }}</div>
                    @endif

                    {{-- Badge vérifié --}}
                    @if($creator->is_verified)
                        <div class="creator-verified">✓</div>
                    @endif

                    {{-- Bouton follow au hover --}}
                    <div class="creator-card-follow-wrap">
                        @auth
                            @if(auth()->id() !== $creator->id)
                                <button class="creator-card-follow {{ auth()->user()->isFollowing($creator) ? 'following' : '' }}"
                                    onclick="event.preventDefault();toggleFollow({{ $creator->id }}, this)">
                                    {{ auth()->user()->isFollowing($creator) ? 'Abonné ✓' : '+ Suivre' }}
                                </button>
                            @endif
                        @else
                            <span class="creator-card-follow">+ Suivre</span>
                        @endauth
                    </div>

                    {{-- Infos en bas --}}
                    <div class="creator-card-info">
                        <div class="creator-card-avatar">
                            <div class="creator-card-avatar-inner">
                                @if($creator->avatar)
                                    <img src="{{ Storage::url($creator->avatar) }}" alt="">
                                @else
                                    {{ mb_strtoupper(mb_substr($creator->name, 0, 1)) }}
                                @endif
                            </div>
                        </div>
                        <div class="creator-card-text">
                            <div class="creator-card-name">{{ $creator->name }}</div>
                            <div class="creator-card-meta">
                                <span>{{ number_format($creator->followers_count) }} abonnés</span>
                                <span>·</span>
                                <span>{{ number_format($creator->posts_count) }} posts</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="creators-pagination">
                {{ $creators->links() }}
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleFollow(userId, btn) {
        fetch(`/users/${userId}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.following) {
                btn.textContent = 'Abonné ✓';
                btn.classList.add('following');
            } else {
                btn.textContent = '+ Suivre';
                btn.classList.remove('following');
            }
        });
    }
</script>
@endpush
