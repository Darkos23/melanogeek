@extends('layouts.app')

@section('title', 'Explorer')

@push('styles')
<style>
    .explore-page {
        padding-top: calc(80px + env(safe-area-inset-top));
        min-height: 100vh;
    }

    /* ══════════════════════════════════════
       HERO / SEARCH BAR
    ══════════════════════════════════════ */
    .explore-hero {
        max-width: 1100px;
        margin: 0 auto;
        padding: 44px 32px 28px;
    }
    .explore-hero-title {
        font-family: var(--font-head);
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text);
        margin-bottom: 6px;
    }
    .explore-hero-title span { color: var(--gold); }
    .explore-hero-sub {
        font-size: .92rem;
        color: var(--text-muted);
        margin-bottom: 28px;
    }

    /* Barre de recherche */
    .explore-search-wrap {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 18px;
    }
    .explore-search-field {
        position: relative;
        flex: 1;
        min-width: 240px;
        max-width: 480px;
    }
    .explore-search-icon {
        position: absolute; left: 16px; top: 50%;
        transform: translateY(-50%);
        font-size: .9rem; color: var(--text-faint);
        pointer-events: none;
    }
    .explore-search-input {
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
    .explore-search-input::placeholder { color: var(--text-faint); }
    .explore-search-input:focus { border-color: var(--terra); box-shadow: 0 0 0 3px rgba(200,82,42,.1); }

    .explore-search-btn {
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
    .explore-search-btn:hover { background: var(--accent); transform: translateY(-1px); }

    .explore-reset {
        color: var(--text-muted);
        text-decoration: none;
        font-size: .82rem;
        padding: 11px 16px;
        border-radius: 100px;
        border: 1px solid var(--border);
        transition: all .2s;
    }
    .explore-reset:hover { border-color: var(--border-hover); color: var(--text); }

    /* ── Filtres ── */
    .explore-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    /* Pills type média */
    .type-pills { display: flex; gap: 6px; }
    .type-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 14px;
        border-radius: 100px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-muted);
        font-size: .78rem; font-weight: 500;
        text-decoration: none;
        transition: all .18s;
        cursor: pointer;
    }
    .type-pill:hover { border-color: var(--terra); color: var(--terra); background: var(--terra-soft); }
    .type-pill.active { border-color: var(--terra); background: var(--terra-soft); color: var(--terra); font-weight: 600; }

    /* Séparateur */
    .filter-divider {
        width: 1px; height: 22px;
        background: var(--border);
        flex-shrink: 0;
    }

    /* Tri */
    .sort-wrap { display: flex; gap: 6px; }
    .sort-pill {
        padding: 6px 14px;
        border-radius: 100px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-muted);
        font-size: .78rem; font-weight: 500;
        text-decoration: none;
        transition: all .18s;
    }
    .sort-pill:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-soft); }
    .sort-pill.active { border-color: var(--gold); background: var(--gold-soft); color: var(--gold); font-weight: 600; }

    /* Niches */
    .niche-pills-row {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 14px;
    }
    .niche-chip {
        padding: 5px 13px;
        border-radius: 100px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-muted);
        font-size: .74rem; font-weight: 500;
        text-decoration: none;
        transition: all .18s;
    }
    .niche-chip:hover { border-color: var(--terra); color: var(--terra); background: var(--terra-soft); }
    .niche-chip.active { border-color: var(--terra); background: var(--terra-soft); color: var(--terra); font-weight: 600; }

    /* ══════════════════════════════════════
       STORIES BAR
    ══════════════════════════════════════ */
    .explore-stories {
        max-width: 1100px;
        margin: 0 auto 4px;
        padding: 0 32px 20px;
    }
    .explore-stories-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 12px;
    }
    .explore-stories-scroll {
        display: flex;
        gap: 14px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }
    .explore-stories-scroll::-webkit-scrollbar { display: none; }
    .explore-story-item {
        display: flex; flex-direction: column; align-items: center; gap: 5px;
        flex-shrink: 0;
        background: none; border: none; padding: 0; cursor: pointer;
        width: 60px;
    }
    .explore-story-ring {
        width: 60px; height: 60px;
        border-radius: 50%;
        padding: 2px;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        overflow: hidden;
        transition: transform .2s;
    }
    .explore-story-item:hover .explore-story-ring { transform: scale(1.06); }
    .explore-story-ring-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
        border: 2px solid var(--bg);
        overflow: hidden;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: var(--text); font-size: .9rem;
    }
    .explore-story-ring-inner img {
        width: 100%; height: 100%;
        object-fit: cover; border-radius: 50%;
    }
    .explore-story-name {
        font-size: .62rem;
        font-weight: 600;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 60px;
        text-align: center;
    }

    /* ══════════════════════════════════════
       BODY
    ══════════════════════════════════════ */
    .explore-body {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 32px 80px;
    }

    .explore-meta {
        font-size: .8rem;
        color: var(--text-muted);
        margin-bottom: 20px;
    }
    .explore-meta strong { color: var(--text); }

    /* ══════════════════════════════════════
       GRILLE — masonry 3 colonnes
    ══════════════════════════════════════ */
    .explore-grid {
        columns: 3;
        column-gap: 12px;
    }

    .explore-item {
        break-inside: avoid;
        margin-bottom: 12px;
        border-radius: 16px;
        overflow: hidden;
        background: var(--bg-card);
        border: 1px solid var(--border);
        display: block;
        text-decoration: none;
        position: relative;
        cursor: pointer;
        transition: border-color .2s, box-shadow .2s, transform .2s;
    }
    .explore-item:hover {
        border-color: var(--border-hover);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    /* Post avec media : affichage image/vidéo dominant */
    .explore-item-media {
        position: relative;
        width: 100%;
        overflow: hidden;
        background: var(--bg-card2);
    }
    .explore-item-media img,
    .explore-item-media video {
        width: 100%;
        display: block;
        object-fit: cover;
        transition: transform .45s ease;
    }
    .explore-item:hover .explore-item-media img,
    .explore-item:hover .explore-item-media video { transform: scale(1.04); }

    /* Badge type en haut à gauche */
    .explore-type-badge {
        position: absolute; top: 10px; left: 10px;
        background: rgba(0,0,0,.55);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 100px;
        color: rgba(255,255,255,.9);
        font-size: .62rem; font-weight: 700;
        padding: 3px 9px;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    /* Overlay hover sur les médias */
    .explore-item-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.75) 0%, rgba(0,0,0,0) 45%);
        opacity: 0;
        transition: opacity .3s;
        display: flex; flex-direction: column;
        justify-content: flex-end;
        padding: 14px;
    }
    .explore-item:hover .explore-item-overlay { opacity: 1; }

    .overlay-stats {
        display: flex; gap: 12px;
        color: white;
        font-size: .8rem; font-weight: 600;
    }
    .overlay-stat { display: flex; align-items: center; gap: 5px; }

    /* Post texte-only */
    .explore-item-text {
        padding: 18px 20px;
    }
    .explore-item-text-title {
        font-family: var(--font-head);
        font-size: .96rem; font-weight: 800;
        color: var(--text);
        margin-bottom: 8px;
        line-height: 1.3;
    }
    .explore-item-text-body {
        font-size: .84rem; line-height: 1.65;
        color: var(--text-muted);
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Footer auteur en bas de chaque card */
    .explore-item-author {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 14px;
        border-top: 1px solid var(--border);
    }
    .explore-author-avatar {
        width: 26px; height: 26px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 1.5px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .explore-author-avatar-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: .62rem; font-weight: 700; color: var(--text);
        overflow: hidden;
    }
    .explore-author-avatar-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .explore-author-name {
        font-size: .76rem; font-weight: 600;
        color: var(--text-muted);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        flex: 1;
    }
    .explore-author-likes {
        font-size: .72rem;
        color: var(--text-faint);
        flex-shrink: 0;
    }

    /* ── Empty ── */
    .explore-empty {
        text-align: center;
        padding: 80px 20px;
        color: var(--text-muted);
        grid-column: 1 / -1;
    }
    .explore-empty-icon { font-size: 2.8rem; margin-bottom: 14px; }
    .explore-empty-title { font-family: var(--font-head); font-size: 1.2rem; font-weight: 700; color: var(--text); margin-bottom: 8px; }

    /* ── Pagination ── */
    .explore-pagination { margin-top: 32px; }

    /* ══════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════ */
    @@media (max-width: 900px) {
        .explore-grid { columns: 2; }
        .explore-hero { padding: 32px 16px 20px; }
        .explore-body { padding: 0 16px 60px; }
    }
    @@media (max-width: 560px) {
        .explore-grid { columns: 1; }
        .explore-hero-title { font-size: 1.6rem; }
        .filter-divider { display: none; }
    }
</style>
@endpush

@section('content')
<div class="explore-page">

    {{-- ── HERO + FILTRES ── --}}
    <div class="explore-hero">
        <div class="explore-hero-title">Explorer <span>MelanoGeek</span></div>
        <div class="explore-hero-sub">Découvre les publications de toute la communauté</div>

        {{-- Recherche --}}
        <form method="GET" action="{{ route('explore') }}">
            <div class="explore-search-wrap">
                <div class="explore-search-field">
                    <span class="explore-search-icon">🔍</span>
                    <input type="text" name="q" class="explore-search-input"
                        placeholder="Rechercher une publication..."
                        value="{{ $query }}">
                </div>
                {{-- Champs cachés pour garder les autres filtres --}}
                @if($type)  <input type="hidden" name="type"  value="{{ $type }}"> @endif
                @if($niche) <input type="hidden" name="niche" value="{{ $niche }}"> @endif
                @if($sort !== 'trending') <input type="hidden" name="sort" value="{{ $sort }}"> @endif

                <button type="submit" class="explore-search-btn">Rechercher</button>

                @if($query || $type || $niche || $sort !== 'trending')
                    <a href="{{ route('explore') }}" class="explore-reset">✕ Réinitialiser</a>
                @endif
            </div>

            {{-- Filtres type + tri --}}
            <div class="explore-filters">
                <div class="type-pills">
                    <a href="{{ route('explore', array_filter(['q'=>$query,'niche'=>$niche,'sort'=>$sort !== 'trending' ? $sort : null])) }}"
                       class="type-pill {{ !$type ? 'active' : '' }}">Tout</a>
                    <a href="{{ route('explore', array_filter(['q'=>$query,'type'=>'image','niche'=>$niche,'sort'=>$sort !== 'trending' ? $sort : null])) }}"
                       class="type-pill {{ $type === 'image' ? 'active' : '' }}">🖼 Images</a>
                    <a href="{{ route('explore', array_filter(['q'=>$query,'type'=>'video','niche'=>$niche,'sort'=>$sort !== 'trending' ? $sort : null])) }}"
                       class="type-pill {{ $type === 'video' ? 'active' : '' }}">▶ Vidéos</a>
                    <a href="{{ route('explore', array_filter(['q'=>$query,'type'=>'text','niche'=>$niche,'sort'=>$sort !== 'trending' ? $sort : null])) }}"
                       class="type-pill {{ $type === 'text' ? 'active' : '' }}">✍ Textes</a>
                </div>

                <div class="filter-divider"></div>

                <div class="sort-wrap">
                    <a href="{{ route('explore', array_filter(['q'=>$query,'type'=>$type,'niche'=>$niche])) }}"
                       class="sort-pill {{ $sort === 'trending' ? 'active' : '' }}">🔥 Tendances</a>
                    <a href="{{ route('explore', array_filter(['q'=>$query,'type'=>$type,'niche'=>$niche,'sort'=>'recent'])) }}"
                       class="sort-pill {{ $sort === 'recent' ? 'active' : '' }}">⏱ Récents</a>
                </div>
            </div>

            {{-- Niches --}}
            @if($niches->isNotEmpty())
            <div class="niche-pills-row">
                <a href="{{ route('explore', array_filter(['q'=>$query,'type'=>$type,'sort'=>$sort !== 'trending' ? $sort : null])) }}"
                   class="niche-chip {{ !$niche ? 'active' : '' }}">Toutes niches</a>
                @foreach($niches as $n)
                    <a href="{{ route('explore', array_filter(['q'=>$query,'type'=>$type,'niche'=>$n,'sort'=>$sort !== 'trending' ? $sort : null])) }}"
                       class="niche-chip {{ $niche === $n ? 'active' : '' }}">{{ $n }}</a>
                @endforeach
            </div>
            @endif
        </form>
    </div>

    {{-- ── STORIES BAR ── --}}
    @if($storyUsers->isNotEmpty())
    <div class="explore-stories">
        <div class="explore-stories-label">Stories actives</div>
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
            @if($query) pour &laquo; {{ $query }} &raquo; @endif
        </div>

        @if($posts->isEmpty())
            <div class="explore-empty fade-in">
                <div class="explore-empty-icon">🔍</div>
                <div class="explore-empty-title">Aucune publication trouvée</div>
                <div>Essaie d'autres mots-clés ou retire les filtres.</div>
            </div>
        @else
            <div class="explore-grid">
                @foreach($posts as $post)
                <a class="explore-item fade-in" href="{{ route('posts.show', $post->id) }}">

                    @if($post->media_url)
                        {{-- Card avec média --}}
                        <div class="explore-item-media">
                            @if($post->media_type === 'video')
                                <video src="{{ Storage::url($post->media_url) }}" muted preload="none"
                                    style="max-height:340px;"></video>
                                <div class="explore-type-badge">▶ Vidéo</div>
                            @else
                                <img src="{{ Storage::url($post->media_url) }}"
                                     alt="{{ $post->title }}"
                                     loading="lazy">
                            @endif

                            {{-- Overlay stats --}}
                            <div class="explore-item-overlay">
                                @if($post->title)
                                    <div style="font-size:.82rem;font-weight:700;color:white;margin-bottom:8px;line-height:1.3;">
                                        {{ Str::limit($post->title, 60) }}
                                    </div>
                                @endif
                                <div class="overlay-stats">
                                    <span class="overlay-stat">❤ {{ number_format($post->likes_count) }}</span>
                                    <span class="overlay-stat">💬 {{ number_format($post->comments_count) }}</span>
                                </div>
                            </div>
                        </div>

                    @else
                        {{-- Card texte-only --}}
                        <div class="explore-item-text">
                            @if($post->title)
                                <div class="explore-item-text-title">{{ $post->title }}</div>
                            @endif
                            @if($post->body)
                                <div class="explore-item-text-body">{{ $post->body }}</div>
                            @endif
                            <div style="display:flex;gap:12px;margin-top:12px;font-size:.76rem;color:var(--text-faint);">
                                <span>❤ {{ number_format($post->likes_count) }}</span>
                                <span>💬 {{ number_format($post->comments_count) }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Footer auteur --}}
                    <div class="explore-item-author">
                        <div class="explore-author-avatar">
                            <div class="explore-author-avatar-inner">
                                @if($post->user->avatar)
                                    <img src="{{ Storage::url($post->user->avatar) }}" alt="">
                                @else
                                    {{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}
                                @endif
                            </div>
                        </div>
                        <span class="explore-author-name">{{ $post->user->name }}</span>
                        <span class="explore-author-likes">{{ $post->created_at?->diffForHumans(null, true) ?? "-" }}</span>
                    </div>

                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
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
        const res  = await fetch(`/stories/${username}/list`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (!data.stories || data.stories.length === 0) return;
        StoryViewer.open(data.stories, data.user, 0);
    } catch (e) {}
}
</script>
@endpush
