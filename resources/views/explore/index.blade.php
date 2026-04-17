@extends('layouts.app')

@section('title', 'Communauté · MelanoGeek')

@push('styles')
<style>
    .explore-page {
        padding-top: calc(72px + env(safe-area-inset-top));
        min-height: 100vh;
    }

    /* ══════════════════════════════════════
       HERO ÉDITORIAL
    ══════════════════════════════════════ */
    .explore-hero {
        max-width: 1200px;
        margin: 0 auto;
        padding: 48px 40px 32px;
    }
    .explore-eyebrow {
        display: inline-flex; align-items: center; gap: 10px;
        font-family: 'JetBrains Mono', monospace;
        font-size: .58rem; font-weight: 600;
        letter-spacing: .14em; text-transform: uppercase;
        color: var(--gold); margin-bottom: 14px;
    }
    .explore-eyebrow::before {
        content: ''; display: block;
        width: 24px; height: 1px;
        background: var(--gold); opacity: .7;
    }
    .explore-hero-title {
        font-family: var(--font-head);
        font-size: clamp(1.9rem, 3.5vw, 2.8rem);
        font-weight: 800;
        letter-spacing: -.04em;
        color: var(--cream);
        line-height: 1.05;
        margin-bottom: 12px;
    }
    .explore-hero-title span { color: var(--terra); }
    .explore-hero-sub {
        font-size: .88rem;
        color: var(--text-muted);
        max-width: 440px;
        line-height: 1.6;
        margin-bottom: 28px;
    }

    /* ── Barre de recherche ── */
    .explore-search-wrap {
        display: flex; gap: 8px; flex-wrap: wrap;
        align-items: center; margin-bottom: 18px;
    }
    .explore-search-field {
        position: relative; flex: 1;
        min-width: 240px; max-width: 460px;
    }
    .explore-search-icon {
        position: absolute; left: 16px; top: 50%;
        transform: translateY(-50%);
        color: var(--text-faint); pointer-events: none;
        font-size: .85rem;
    }
    .explore-search-input {
        width: 100%;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 100px;
        padding: 11px 20px 11px 44px;
        color: var(--text);
        font-family: var(--font-body); font-size: .88rem;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .explore-search-input::placeholder { color: var(--text-faint); }
    .explore-search-input:focus {
        border-color: var(--terra);
        box-shadow: 0 0 0 3px rgba(200,82,42,.1);
    }
    .explore-search-btn {
        background: var(--terra); border: none; color: white;
        padding: 11px 22px; border-radius: 100px;
        font-family: var(--font-body); font-size: .88rem; font-weight: 600;
        cursor: pointer; transition: background .2s, transform .15s;
        white-space: nowrap;
    }
    .explore-search-btn:hover { background: var(--accent); transform: translateY(-1px); }
    .explore-reset {
        color: var(--text-muted); text-decoration: none;
        font-size: .82rem; padding: 11px 16px;
        border-radius: 100px; border: 1px solid var(--border);
        transition: all .2s;
    }
    .explore-reset:hover { border-color: var(--border-hover); color: var(--text); }

    /* ── Filter pills ── */
    .explore-filters { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .type-pills, .sort-wrap { display: flex; gap: 6px; }
    .type-pill, .sort-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 14px; border-radius: 100px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-muted);
        font-size: .76rem; font-weight: 500;
        text-decoration: none;
        transition: all .18s;
    }
    .type-pill:hover { border-color: var(--terra); color: var(--terra); background: var(--terra-soft); }
    .type-pill.active { border-color: var(--terra); background: var(--terra-soft); color: var(--terra); font-weight: 600; }
    .sort-pill:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-soft); }
    .sort-pill.active { border-color: var(--gold); background: var(--gold-soft); color: var(--gold); font-weight: 600; }
    .filter-divider { width: 1px; height: 22px; background: var(--border); flex-shrink: 0; }

    /* ── Niche chips ── */
    .niche-pills-row { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 12px; }
    .niche-chip {
        padding: 5px 13px; border-radius: 100px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-muted);
        font-size: .73rem; font-weight: 500;
        text-decoration: none; transition: all .18s;
    }
    .niche-chip:hover { border-color: var(--terra); color: var(--terra); background: var(--terra-soft); }
    .niche-chip.active { border-color: var(--terra); background: var(--terra-soft); color: var(--terra); font-weight: 600; }

    /* ══════════════════════════════════════
       STORIES BAR
    ══════════════════════════════════════ */
    .explore-stories {
        max-width: 1200px;
        margin: 0 auto 8px;
        padding: 0 40px 24px;
        border-bottom: 1px solid var(--border);
    }
    .explore-stories-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: .58rem; font-weight: 600;
        letter-spacing: .12em; text-transform: uppercase;
        color: var(--text-faint); margin-bottom: 14px;
    }
    .explore-stories-scroll {
        display: flex; gap: 16px;
        overflow-x: auto; padding-bottom: 4px;
        scrollbar-width: none;
    }
    .explore-stories-scroll::-webkit-scrollbar { display: none; }
    .explore-story-item {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        flex-shrink: 0; background: none; border: none;
        padding: 0; cursor: pointer; width: 64px;
    }
    .explore-story-ring {
        width: 64px; height: 64px; border-radius: 50%;
        padding: 2.5px;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        transition: transform .2s, box-shadow .2s;
    }
    .explore-story-item:hover .explore-story-ring {
        transform: scale(1.07);
        box-shadow: 0 0 0 3px rgba(200,82,42,.2);
    }
    .explore-story-ring-inner {
        width: 100%; height: 100%; border-radius: 50%;
        border: 2.5px solid var(--bg);
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: var(--text); font-size: .95rem;
        overflow: hidden;
    }
    .explore-story-ring-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .explore-story-name {
        font-size: .62rem; font-weight: 600;
        color: var(--text-muted);
        white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis;
        max-width: 64px; text-align: center;
    }

    /* ══════════════════════════════════════
       BODY + GRILLE
    ══════════════════════════════════════ */
    .explore-body {
        max-width: 1200px;
        margin: 0 auto;
        padding: 28px 40px 80px;
    }
    .explore-meta {
        font-family: 'JetBrains Mono', monospace;
        font-size: .65rem; font-weight: 500;
        letter-spacing: .06em; text-transform: uppercase;
        color: var(--text-faint);
        margin-bottom: 24px;
    }
    .explore-meta strong { color: var(--text-muted); }

    /* Masonry 3 colonnes */
    .explore-grid { columns: 3; column-gap: 14px; }

    /* ── Card ── */
    .explore-item {
        break-inside: avoid;
        margin-bottom: 14px;
        border-radius: 16px;
        overflow: hidden;
        background: var(--bg-card);
        border: 1px solid var(--border);
        display: block;
        text-decoration: none;
        position: relative;
        transition: border-color .22s, box-shadow .22s, transform .22s;
    }
    .explore-item:hover {
        border-color: var(--border-hover);
        box-shadow: 0 12px 36px rgba(0,0,0,.45);
        transform: translateY(-3px);
    }

    /* ── Média ── */
    .explore-item-media {
        position: relative; width: 100%;
        overflow: hidden; background: var(--bg-card2);
    }
    .explore-item-media img,
    .explore-item-media video {
        width: 100%; display: block;
        object-fit: cover;
        transition: transform .5s ease;
    }
    .explore-item:hover .explore-item-media img,
    .explore-item:hover .explore-item-media video { transform: scale(1.05); }

    /* Badge type */
    .explore-type-badge {
        position: absolute; top: 10px; left: 10px;
        background: rgba(0,0,0,.6);
        backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 100px;
        color: rgba(255,255,255,.92);
        font-family: 'JetBrains Mono', monospace;
        font-size: .58rem; font-weight: 700;
        padding: 4px 10px; letter-spacing: .06em; text-transform: uppercase;
    }

    /* Overlay gradient + stats au hover */
    .explore-item-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.82) 0%, rgba(0,0,0,0) 50%);
        opacity: 0; transition: opacity .28s;
        display: flex; flex-direction: column;
        justify-content: flex-end; padding: 16px;
    }
    .explore-item:hover .explore-item-overlay { opacity: 1; }
    .overlay-title {
        font-family: var(--font-head);
        font-size: .88rem; font-weight: 800;
        color: white; line-height: 1.3;
        margin-bottom: 10px;
    }
    .overlay-stats {
        display: flex; gap: 14px;
        color: rgba(255,255,255,.85);
        font-size: .76rem; font-weight: 600;
    }

    /* ── Card bannière catégorie (texte sans image) ── */
    .explore-cat-banner {
        width: 100%; aspect-ratio: 16/7;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.2rem; position: relative; overflow: hidden;
    }
    .explore-cat-banner.cat-manga-anime   { background: linear-gradient(135deg,#1a0a2e,#4a1a6e,#8b2fc9); }
    .explore-cat-banner.cat-gaming        { background: linear-gradient(135deg,#0a2010,#1a4a20,#2a8a40); }
    .explore-cat-banner.cat-tech          { background: linear-gradient(135deg,#0a1020,#1a2a50,#2a4a9e); }
    .explore-cat-banner.cat-dev           { background: linear-gradient(135deg,#1a1000,#3a2800,#7a5000); }
    .explore-cat-banner.cat-cinema-series { background: linear-gradient(135deg,#1a0808,#4a1010,#9e2020); }
    .explore-cat-banner.cat-culture       { background: linear-gradient(135deg,#0a1a10,#1a3a20,#2a6a40); }
    .explore-cat-banner.cat-debat         { background: linear-gradient(135deg,#100a1a,#2a1a4a,#5a2a9e); }
    .explore-cat-banner.cat-default       { background: linear-gradient(135deg,#1a1a1a,#2a2a2a,#383838); }

    /* ── Contenu texte ── */
    .explore-item-body {
        padding: 16px 18px 14px;
    }
    .explore-item-cat {
        font-family: 'JetBrains Mono', monospace;
        font-size: .56rem; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase;
        color: var(--terra); margin-bottom: 7px;
    }
    .explore-item-title {
        font-family: var(--font-head);
        font-size: .95rem; font-weight: 800;
        color: var(--cream); line-height: 1.28;
        margin-bottom: 8px;
    }
    .explore-item-excerpt {
        font-size: .8rem; line-height: 1.6;
        color: var(--text-muted);
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 12px;
    }
    .explore-item-stats {
        display: flex; gap: 12px;
        font-size: .72rem; color: var(--text-faint);
    }

    /* ── Footer auteur ── */
    .explore-item-author {
        display: flex; align-items: center; gap: 9px;
        padding: 10px 16px;
        border-top: 1px solid var(--border);
    }
    .explore-author-avi {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 1.5px; flex-shrink: 0; overflow: hidden;
    }
    .explore-author-avi-inner {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: .65rem; font-weight: 700; color: var(--text);
        overflow: hidden;
    }
    .explore-author-avi-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .explore-author-name {
        font-size: .76rem; font-weight: 600;
        color: var(--text-muted);
        white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis; flex: 1;
    }
    .explore-author-verified {
        font-size: .7rem; color: var(--gold); flex-shrink: 0;
    }
    .explore-author-time {
        font-size: .68rem; color: var(--text-faint); flex-shrink: 0;
    }

    /* ── Empty ── */
    .explore-empty {
        text-align: center; padding: 80px 20px;
        color: var(--text-muted);
    }
    .explore-empty-icon { font-size: 3rem; margin-bottom: 16px; }
    .explore-empty-title {
        font-family: var(--font-head); font-size: 1.25rem;
        font-weight: 700; color: var(--text); margin-bottom: 8px;
    }

    /* ── Pagination ── */
    .explore-pagination { margin-top: 36px; }

    /* ══════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════ */
    @@media (max-width: 960px) {
        .explore-grid { columns: 2; }
        .explore-hero, .explore-body, .explore-stories { padding-left: 20px; padding-right: 20px; }
    }
    @@media (max-width: 560px) {
        .explore-grid { columns: 1; }
        .explore-hero-title { font-size: 1.7rem; }
        .filter-divider { display: none; }
    }
</style>
@endpush

@section('content')
<div class="explore-page">

    {{-- ── HERO ── --}}
    <div class="explore-hero">
        <div class="explore-eyebrow">MelanoGeek · Communauté</div>
        <h1 class="explore-hero-title">
            Toutes les <span>publications</span>
        </h1>
        <p class="explore-hero-sub">Explore ce que la communauté crée, partage et débat.</p>

        {{-- Recherche --}}
        <form method="GET" action="{{ route('community') }}">
            <div class="explore-search-wrap">
                <div class="explore-search-field">
                    <span class="explore-search-icon">🔍</span>
                    <input type="text" name="q" class="explore-search-input"
                        placeholder="Rechercher une publication…"
                        value="{{ $query }}">
                </div>
                @if($type) <input type="hidden" name="type" value="{{ $type }}"> @endif
                @if($sort !== 'trending') <input type="hidden" name="sort" value="{{ $sort }}"> @endif
                <button type="submit" class="explore-search-btn">Rechercher</button>
                @if($query || $type || $sort !== 'trending')
                    <a href="{{ route('community') }}" class="explore-reset">✕ Réinitialiser</a>
                @endif
            </div>

            <div class="explore-filters">
                <div class="type-pills">
                    <a href="{{ route('community', array_filter(['q'=>$query,'sort'=>$sort !== 'trending' ? $sort : null])) }}"
                       class="type-pill {{ !$type ? 'active' : '' }}">Tout</a>
                    <a href="{{ route('community', array_filter(['q'=>$query,'type'=>'image','sort'=>$sort !== 'trending' ? $sort : null])) }}"
                       class="type-pill {{ $type === 'image' ? 'active' : '' }}">🖼 Images</a>
                    <a href="{{ route('community', array_filter(['q'=>$query,'type'=>'video','sort'=>$sort !== 'trending' ? $sort : null])) }}"
                       class="type-pill {{ $type === 'video' ? 'active' : '' }}">▶ Vidéos</a>
                    <a href="{{ route('community', array_filter(['q'=>$query,'type'=>'text','sort'=>$sort !== 'trending' ? $sort : null])) }}"
                       class="type-pill {{ $type === 'text' ? 'active' : '' }}">✍ Textes</a>
                </div>
                <div class="filter-divider"></div>
                <div class="sort-wrap">
                    <a href="{{ route('community', array_filter(['q'=>$query,'type'=>$type])) }}"
                       class="sort-pill {{ $sort === 'trending' ? 'active' : '' }}">🔥 Tendances</a>
                    <a href="{{ route('community', array_filter(['q'=>$query,'type'=>$type,'sort'=>'recent'])) }}"
                       class="sort-pill {{ $sort === 'recent' ? 'active' : '' }}">⏱ Récents</a>
                </div>
            </div>
        </form>
    </div>

    {{-- ── STORIES ── --}}
    @if($storyUsers->isNotEmpty())
    <div class="explore-stories">
        <div class="explore-stories-label">Membres actifs</div>
        <div class="explore-stories-scroll">
            @foreach($storyUsers as $su)
            <button class="explore-story-item" type="button"
                onclick="openExploreStory('{{ $su->username }}')"
                title="{{ $su->name }}">
                <div class="explore-story-ring">
                    <div class="explore-story-ring-inner">
                        @if($su->avatar)
                            <img src="{{ Storage::url($su->avatar) }}" alt="{{ $su->name }}">
                        @else
                            {{ mb_strtoupper(mb_substr($su->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div class="explore-story-name">{{ Str::limit($su->name, 10) }}</div>
            </button>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── GRILLE ── --}}
    <div class="explore-body">

        <div class="explore-meta">
            <strong>{{ number_format($posts->total()) }}</strong> publication{{ $posts->total() > 1 ? 's' : '' }}
            @if($query) · &laquo;&nbsp;{{ $query }}&nbsp;&raquo; @endif
        </div>

        @if($posts->isEmpty())
            <div class="explore-empty" data-reveal>
                <div class="explore-empty-icon">🔍</div>
                <div class="explore-empty-title">Aucune publication trouvée</div>
                <div>Essaie d'autres mots-clés ou retire les filtres.</div>
            </div>
        @else
            <div class="explore-grid">
                @foreach($posts as $post)
                @php
                    $thumb    = $post->thumbnail ? Storage::url($post->thumbnail) : null;
                    $mediaImg = (!$thumb && $post->media_url && $post->media_type !== 'video') ? Storage::url($post->media_url) : null;
                    $img      = $thumb ?? $mediaImg;
                    $isVideo  = $post->media_url && $post->media_type === 'video';
                    $catClass = $post->category ? 'cat-'.str_replace('_','-',$post->category) : 'cat-default';
                    $catIcons = ['manga-anime'=>'🎌','gaming'=>'🎮','tech'=>'💻','dev'=>'⌨️','cinema-series'=>'🎬','culture'=>'🌍','debat'=>'💬'];
                    $catIcon  = $catIcons[$post->category] ?? '📰';
                    $excerpt  = Str::limit(strip_tags($post->body ?? ''), 120);
                @endphp
                <a class="explore-item" href="{{ route('posts.show', $post->id) }}" data-reveal data-delay="{{ ($loop->index % 5) + 1 }}">

                    @if($isVideo)
                        {{-- Vidéo --}}
                        <div class="explore-item-media">
                            <video src="{{ Storage::url($post->media_url) }}"
                                muted preload="none" style="max-height:320px;"></video>
                            <div class="explore-type-badge">▶ Vidéo</div>
                            <div class="explore-item-overlay">
                                @if($post->title)
                                    <div class="overlay-title">{{ Str::limit($post->title, 65) }}</div>
                                @endif
                                <div class="overlay-stats">
                                    <span>❤ {{ number_format($post->likes_count) }}</span>
                                    <span>💬 {{ number_format($post->comments_count) }}</span>
                                </div>
                            </div>
                        </div>

                    @elseif($img)
                        {{-- Image / thumbnail --}}
                        <div class="explore-item-media">
                            <img src="{{ $img }}" alt="{{ $post->title }}" loading="lazy">
                            <div class="explore-item-overlay">
                                @if($post->title)
                                    <div class="overlay-title">{{ Str::limit($post->title, 65) }}</div>
                                @endif
                                <div class="overlay-stats">
                                    <span>❤ {{ number_format($post->likes_count) }}</span>
                                    <span>💬 {{ number_format($post->comments_count) }}</span>
                                </div>
                            </div>
                        </div>

                    @else
                        {{-- Bannière catégorie --}}
                        <div class="explore-cat-banner {{ $catClass }}">
                            <span style="font-size:2.4rem;filter:drop-shadow(0 2px 8px rgba(0,0,0,.5))">{{ $catIcon }}</span>
                        </div>
                    @endif

                    {{-- Corps texte --}}
                    <div class="explore-item-body">
                        @if($post->category)
                        <div class="explore-item-cat">{{ $post->category_label }}</div>
                        @endif
                        @if($post->title)
                            <div class="explore-item-title">{{ $post->title }}</div>
                        @endif
                        @if($excerpt)
                            <div class="explore-item-excerpt">{{ $excerpt }}</div>
                        @endif
                        <div class="explore-item-stats">
                            <span>❤ {{ number_format($post->likes_count) }}</span>
                            <span>💬 {{ number_format($post->comments_count) }}</span>
                        </div>
                    </div>

                    {{-- Footer auteur --}}
                    <div class="explore-item-author">
                        <div class="explore-author-avi">
                            <div class="explore-author-avi-inner">
                                @if($post->user->avatar)
                                    <img src="{{ Storage::url($post->user->avatar) }}" alt="">
                                @else
                                    {{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}
                                @endif
                            </div>
                        </div>
                        <span class="explore-author-name">{{ $post->user->username }}</span>
                        @if($post->user->is_verified ?? false)
                            <span class="explore-author-verified" title="Vérifié">✦</span>
                        @endif
                        <span class="explore-author-time">{{ $post->created_at?->diffForHumans(null, true) ?? '—' }}</span>
                    </div>

                </a>
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="explore-pagination">
                    {{ $posts->links() }}
                </div>
            @endif
        @endif

    </div>
</div>

@include('stories._viewer')

@endsection

@push('scripts')
<script>
async function openExploreStory(username) {
    try {
        const res  = await fetch(`/stories/${username}/list`, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (!data.stories || data.stories.length === 0) return;
        StoryViewer.open(data.stories, data.user, 0);
    } catch (e) {}
}
</script>
@endpush
