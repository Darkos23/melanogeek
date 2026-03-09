@extends('layouts.app')

@section('title', 'Marketplace — MelanoGeek')

@section('content')
<style>
.mkp-wrap { max-width: 1100px; margin: 0 auto; padding: 88px 20px 80px; }

/* Hero */
.mkp-hero { margin-bottom: 40px; }
.mkp-hero-eyebrow {
    display: inline-block; font-size: .7rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: var(--terra); background: var(--terra-soft);
    padding: 3px 12px; border-radius: 100px; margin-bottom: 12px;
}
.mkp-hero h1 {
    font-family: var(--font-head); font-size: clamp(1.6rem, 3.5vw, 2.2rem);
    font-weight: 800; color: var(--text); margin-bottom: 6px;
}
.mkp-hero p { font-size: .9rem; color: var(--text-muted); }

/* Filters */
.mkp-filters {
    display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 32px;
    align-items: center;
}
.mkp-search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 340px; }
.mkp-search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
.mkp-search {
    width: 100%; background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 10px; padding: 9px 12px 9px 36px;
    font-size: .84rem; font-family: var(--font-body); color: var(--text); outline: none;
    transition: border-color .15s;
}
.mkp-search:focus { border-color: var(--terra); }
.mkp-search::placeholder { color: var(--text-faint); }
.mkp-select {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 10px; padding: 9px 14px; font-size: .84rem;
    font-family: var(--font-body); color: var(--text); outline: none;
    transition: border-color .15s;
}
.mkp-select:focus { border-color: var(--terra); }

/* Category pills */
.cat-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 28px; }
.cat-pill {
    display: flex; align-items: center; gap: 5px;
    padding: 5px 14px; border-radius: 100px; font-size: .78rem; font-weight: 600;
    border: 1px solid var(--border); background: var(--bg-card);
    color: var(--text-muted); text-decoration: none;
    transition: all .15s;
}
.cat-pill:hover, .cat-pill.active {
    border-color: var(--terra); background: var(--terra-soft); color: var(--terra);
}

/* Grid */
.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}
.service-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden; text-decoration: none;
    transition: border-color .2s, transform .2s;
    display: flex; flex-direction: column;
}
.service-card:hover { border-color: var(--border-hover); transform: translateY(-3px); }
.service-cover {
    width: 100%; aspect-ratio: 16/9; object-fit: cover;
    background: var(--bg-card2);
    display: flex; align-items: center; justify-content: center;
}
.service-cover img { width: 100%; height: 100%; object-fit: cover; }
.service-cover-placeholder {
    width: 100%; aspect-ratio: 16/9;
    background: linear-gradient(135deg, var(--bg-card2), var(--bg-hover));
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem;
}
.service-body { padding: 16px; flex: 1; display: flex; flex-direction: column; gap: 10px; }
.service-creator {
    display: flex; align-items: center; gap: 8px;
}
.service-avi {
    width: 26px; height: 26px; border-radius: 50%;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    padding: 1.5px; flex-shrink: 0;
}
.service-avi-inner {
    width: 100%; height: 100%; border-radius: 50%;
    background: var(--bg-card2); display: flex; align-items: center;
    justify-content: center; font-size: .65rem; font-weight: 700; overflow: hidden;
}
.service-avi-inner img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.service-creator-name { font-size: .78rem; font-weight: 600; color: var(--text-muted); }
.service-title {
    font-family: var(--font-head); font-size: .95rem; font-weight: 700;
    color: var(--text); line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.service-meta { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
.service-price {
    font-family: var(--font-head); font-size: 1.05rem; font-weight: 800;
    color: var(--terra);
}
.service-delivery { font-size: .72rem; color: var(--text-muted); }
.service-cat-badge {
    font-size: .68rem; font-weight: 700; padding: 2px 8px;
    border-radius: 100px; background: var(--terra-soft);
    color: var(--terra); border: 1px solid rgba(200,82,42,.2);
}
.empty-state {
    text-align: center; padding: 80px 20px; color: var(--text-muted);
}
.empty-state-icon { font-size: 3rem; margin-bottom: 12px; }
.empty-state-title { font-family: var(--font-head); font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 6px; }

.mkp-cta-creator {
    background: linear-gradient(135deg, var(--terra-soft), var(--gold-soft));
    border: 1px solid rgba(200,82,42,.2); border-radius: 16px;
    padding: 24px 28px; display: flex; align-items: center;
    justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 32px;
}
.mkp-cta-creator h3 {
    font-family: var(--font-head); font-size: 1rem; font-weight: 800; color: var(--text); margin-bottom: 4px;
}
.mkp-cta-creator p { font-size: .82rem; color: var(--text-muted); }
.btn-terra {
    background: var(--terra); color: white; border: none;
    padding: 10px 20px; border-radius: 10px; font-family: var(--font-head);
    font-size: .85rem; font-weight: 700; text-decoration: none;
    white-space: nowrap; transition: opacity .2s;
}
.btn-terra:hover { opacity: .85; }
</style>

<div class="mkp-wrap">

    <div class="mkp-hero">
        <div class="mkp-hero-eyebrow">Marketplace</div>
        <h1>Services des créateurs africains</h1>
        <p>Photographie, musique, design, mode… commande directement aux talents MelanoGeek.</p>
    </div>

    @auth
        @if(auth()->user()->isCreator())
        <div class="mkp-cta-creator">
            <div>
                <h3>✨ Tu es créateur — propose tes services</h3>
                <p>Gère tes offres et reçois des commandes directement.</p>
            </div>
            <a href="{{ route('services.create') }}" class="btn-terra">+ Nouveau service</a>
        </div>
        @endif
    @endauth

    {{-- Filtres --}}
    <form method="GET" action="{{ route('marketplace.index') }}">
        <div class="mkp-filters">
            <div class="mkp-search-wrap">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="search" class="mkp-search" placeholder="Rechercher un service…" value="{{ request('search') }}">
            </div>
            <select name="sort" class="mkp-select" onchange="this.form.submit()">
                <option value="">Récents</option>
                <option value="popular"    {{ request('sort') === 'popular'    ? 'selected' : '' }}>Populaires</option>
                <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Prix croissant</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
            </select>
            @if(request('search') || request('sort'))
            <a href="{{ route('marketplace.index', request()->only('category')) }}" style="font-size:.8rem;color:var(--text-muted);text-decoration:none;align-self:center;">✕ Réinitialiser</a>
            @endif
        </div>

        {{-- Catégories --}}
        <div class="cat-pills">
            <a href="{{ route('marketplace.index', array_merge(request()->except('category'), ['search' => request('search'), 'sort' => request('sort')])) }}"
               class="cat-pill {{ ! request('category') ? 'active' : '' }}">Tout</a>
            @foreach($categories as $key => $cat)
            <a href="{{ route('marketplace.index', array_merge(request()->except('category'), ['category' => $key, 'search' => request('search'), 'sort' => request('sort')])) }}"
               class="cat-pill {{ request('category') === $key ? 'active' : '' }}">
                {{ $cat['icon'] }} {{ $cat['label'] }}
            </a>
            @endforeach
        </div>
    </form>

    {{-- Grille --}}
    @if($services->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">🛍️</div>
        <div class="empty-state-title">Aucun service trouvé</div>
        <p style="font-size:.84rem;">Essaie une autre catégorie ou reviens bientôt.</p>
    </div>
    @else
    <div class="service-grid">
        @foreach($services as $service)
        <a href="{{ route('marketplace.show', $service) }}" class="service-card">
            @if($service->cover_image)
            <div class="service-cover"><img src="{{ Storage::url($service->cover_image) }}" alt="{{ $service->title }}" loading="lazy"></div>
            @else
            <div class="service-cover-placeholder">{{ $service->category_icon }}</div>
            @endif
            <div class="service-body">
                <div class="service-creator">
                    <div class="service-avi">
                        <div class="service-avi-inner">
                            @if($service->user->avatar)<img src="{{ Storage::url($service->user->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($service->user->name,0,1)) }}@endif
                        </div>
                    </div>
                    <span class="service-creator-name">{{ $service->user->name }}</span>
                    <span class="service-cat-badge" style="margin-left:auto;">{{ $service->category_icon }}</span>
                </div>
                <div class="service-title">{{ $service->title }}</div>
                <div class="service-meta">
                    <div class="service-price">{{ $service->price_formatted }}</div>
                    <div class="service-delivery">⏱ {{ $service->delivery_days }}j</div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{ $services->links() }}
    @endif

</div>
@endsection
