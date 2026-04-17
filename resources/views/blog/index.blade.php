@extends('layouts.blog')

@section('title', 'Blog')

@push('styles')
<style>
/* Featured card */
.featured-card {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    text-decoration: none;
    background: var(--bg-card);
    min-height: 360px;
    transition: border-color .2s, box-shadow .2s;
}
.featured-card:hover {
    border-color: var(--border-hover);
    box-shadow: 0 8px 32px rgba(0,0,0,.35);
}
.featured-img-wrap {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg,#2A1206,#7A3010,#C84818);
}
.featured-img {
    width: 100%; height: 100%; object-fit: cover; display: block;
    transition: transform .5s ease;
}
.featured-card:hover .featured-img { transform: scale(1.04); }
.featured-img-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,.15) 60%, rgba(13,9,5,.55) 100%);
}
.featured-badge {
    position: absolute; top: 18px; left: 18px;
    background: var(--terra); color: white;
    font-family: 'JetBrains Mono', monospace;
    font-size: .55rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    padding: 4px 10px; border-radius: 100px;
}
.featured-content {
    padding: 32px 28px;
    display: flex; flex-direction: column; justify-content: space-between;
    gap: 20px;
}
.featured-content-top { display: flex; flex-direction: column; gap: 14px; }
.featured-eyebrow {
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem; font-weight: 600; letter-spacing: .1em;
    text-transform: uppercase; color: var(--terra);
    display: flex; align-items: center;
}
.featured-title {
    font-family: var(--font-head);
    font-size: clamp(1.1rem, 2vw, 1.65rem);
    font-weight: 800; letter-spacing: -.03em;
    line-height: 1.2; color: var(--text);
}
.featured-excerpt {
    font-size: .8rem; color: var(--text-muted);
    line-height: 1.65;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.featured-content-bottom { display: flex; flex-direction: column; gap: 16px; }
.featured-meta {
    display: flex; align-items: center; gap: 7px;
    font-size: .62rem; color: var(--text-muted);
}
.featured-avi {
    width: 22px; height: 22px; border-radius: 50%;
    object-fit: cover; flex-shrink: 0;
}
.featured-avi-initial {
    background: linear-gradient(135deg, var(--terra), var(--gold));
    display: flex; align-items: center; justify-content: center;
    font-size: .58rem; font-weight: 700; color: white;
}
.featured-dot {
    width: 3px; height: 3px;
    background: var(--text-faint); border-radius: 50%; flex-shrink: 0;
}
.featured-cta {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--terra); color: white;
    font-size: .78rem; font-weight: 700;
    padding: 10px 18px; border-radius: 100px;
    align-self: flex-start;
    transition: opacity .2s;
}
.featured-card:hover .featured-cta { opacity: .88; }

/* Posts grid */
.posts-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 32px;
}
@media (max-width: 700px) {
    .featured-card { grid-template-columns: 1fr; min-height: unset; }
    .featured-img-wrap { aspect-ratio: 16/7; }
    .featured-content { padding: 20px 18px; }
    .posts-grid { grid-template-columns: 1fr; gap: 12px; }
}

/* Card individuelle */
.post-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    transition: border-color .2s, transform .2s, box-shadow .2s;
}
.post-card:hover {
    border-color: var(--border-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(0,0,0,.35);
}

/* Banner : image OU gradient placeholder */
.post-card-banner {
    width: 100%;
    aspect-ratio: 16/8;
    overflow: hidden;
    position: relative;
    flex-shrink: 0;
}
.post-card-banner img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: transform .4s ease;
}
.post-card:hover .post-card-banner img { transform: scale(1.04); }

/* Gradients par catégorie quand pas d'image */
.post-card-banner.cat-manga-anime  { background: linear-gradient(135deg,#1a0a2e,#4a1a6e,#8b2fc9); }
.post-card-banner.cat-gaming       { background: linear-gradient(135deg,#0a1a0a,#0d3b1a,#1a6b35); }
.post-card-banner.cat-tech         { background: linear-gradient(135deg,#0a1628,#0d3060,#1a5cb8); }
.post-card-banner.cat-dev          { background: linear-gradient(135deg,#1a1200,#4a3400,#8b6200); }
.post-card-banner.cat-cinema-series{ background: linear-gradient(135deg,#1a0808,#4a1010,#9b1a1a); }
.post-card-banner.cat-culture      { background: linear-gradient(135deg,#0a1a0a,#1a3a10,#2d6b1a); }
.post-card-banner.cat-debat        { background: linear-gradient(135deg,#1a1218,#3a1a38,#6b2060); }
.post-card-banner.cat-default      { background: linear-gradient(135deg,var(--bg-card2),var(--bg-hover)); }

/* Icône centrée dans le placeholder */
.post-card-banner-icon {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.4rem;
    opacity: .35;
}

/* Corps de la carte */
.post-card-body {
    padding: 20px 22px 22px;
    display: flex;
    flex-direction: column;
    flex: 1;
    gap: 10px;
}
.post-card-cat {
    font-family: 'JetBrains Mono', monospace;
    font-size: .57rem;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--terra);
}
.post-card-title {
    font-family: var(--font-head);
    font-size: 1.05rem;
    font-weight: 700;
    line-height: 1.3;
    color: rgba(255,255,255,.90);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.post-card-excerpt {
    font-size: .76rem;
    color: var(--text-muted);
    line-height: 1.65;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}
.post-card-meta {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: .62rem;
    color: var(--text-muted);
    margin-top: auto;
    padding-top: 4px;
    border-top: 1px solid var(--border);
}
.post-card-avi {
    width: 22px; height: 22px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    display: flex; align-items: center; justify-content: center;
    font-size: .58rem; font-weight: 700; color: white;
    flex-shrink: 0; overflow: hidden;
}
.post-card-avi img { width: 100%; height: 100%; object-fit: cover; }
.post-card-dot { width: 3px; height: 3px; background: var(--text-faint); border-radius: 50%; flex-shrink: 0; }
</style>
@endpush

@section('main')

{{-- ── EN-TÊTE PAGE ── --}}
<div style="margin-bottom:40px">
    <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);margin-bottom:14px;display:flex;align-items:center;gap:10px">
        <span style="display:inline-block;width:24px;height:1px;background:var(--gold);opacity:.7"></span>
        MelanoGeek · Blog
    </div>
    <h1 data-scramble style="font-family:var(--font-head);font-size:clamp(2rem,4vw,3.2rem);font-weight:800;line-height:1.05;letter-spacing:-.04em;color:rgba(255,255,255,.92);margin-bottom:16px">
        Culture geek,<br><span style="color:var(--gold)">vue d'Afrique.</span>
    </h1>
    <p style="font-size:.85rem;color:rgba(255,255,255,.45);line-height:1.65;max-width:460px;font-family:'Inter',sans-serif">
        Articles, reviews, analyses et coups de cœur autour du manga, du gaming, du développement, de la tech et de la culture nerd africaine.
    </p>

    @auth
    <div style="margin-top:18px">
        <a href="{{ route('posts.create') }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 18px;border:1px solid var(--gold);border-radius:999px;color:var(--gold);text-decoration:none;font-size:.85rem;font-weight:700;transition:background .2s;background:rgba(255,255,255,.04);">
            ✍️ Écrire un article
        </a>
    </div>
    @endauth
</div>

{{-- ── FILTRES CATÉGORIES ── --}}
<div style="display:flex;align-items:center;gap:0;margin-bottom:36px;border-bottom:1px solid var(--border)">
    @php
    $cats = [
        ''               => 'Tous les articles',
        'manga-anime'    => 'Manga',
        'gaming'         => 'Gaming',
        'tech'           => 'Tech & IA',
        'dev'            => 'Dev',
        'cinema-series'  => 'Cinema',
        'culture'        => 'Culture',
        'debat'          => 'Debat',
    ];
    @endphp

    @foreach($cats as $slug => $label)
    @php $active = $category === ($slug ?: null); @endphp
    <a href="{{ route('blog.index') }}{{ $slug ? '?category='.$slug : '' }}"
       style="display:inline-flex;align-items:center;padding:10px 16px;font-family:'JetBrains Mono',monospace;font-size:.62rem;letter-spacing:.06em;text-transform:uppercase;text-decoration:none;border-bottom:2px solid {{ $active ? 'var(--gold)' : 'transparent' }};color:{{ $active ? 'var(--gold)' : 'rgba(255,255,255,.35)' }};margin-bottom:-1px;transition:color .15s;">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- ── RECHERCHE ── --}}
<form method="GET" action="{{ route('blog.index') }}" style="margin-bottom:28px;display:flex;gap:8px;align-items:center;">
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <input
        type="text"
        name="q"
        value="{{ $query ?? '' }}"
        placeholder="Rechercher un article…"
        style="flex:1;background:var(--bg-card);border:1px solid var(--border);border-radius:10px;color:var(--text);font-family:var(--font-body);font-size:.84rem;padding:10px 16px;outline:none;transition:border-color .2s;"
        onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
    >
    <button type="submit" style="background:var(--gold);border:none;color:#000;font-family:var(--font-head);font-size:.78rem;font-weight:700;padding:10px 18px;border-radius:10px;cursor:pointer;white-space:nowrap;transition:opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
        Rechercher
    </button>
    @if(isset($query) && $query)
    <a href="{{ route('blog.index') }}{{ request('category') ? '?category='.request('category') : '' }}" style="font-size:.78rem;color:var(--text-muted);text-decoration:none;white-space:nowrap;">✕ Effacer</a>
    @endif
</form>

@if(isset($query) && $query)
<div style="font-size:.72rem;color:var(--text-muted);margin-bottom:16px;margin-top:-12px;">
    {{ $posts->total() }} résultat{{ $posts->total() > 1 ? 's' : '' }} pour "<strong style="color:var(--text)">{{ $query }}</strong>"
</div>
@endif

@if($posts->isEmpty())
{{-- ── AUCUN RÉSULTAT ── --}}
<div style="text-align:center;padding:80px 0;color:var(--text-faint)">
    <div style="font-size:2.5rem;margin-bottom:16px">📭</div>
    <div style="font-family:var(--font-head);font-size:1rem;font-weight:600;color:var(--text-muted);margin-bottom:8px">Aucun article dans cette catégorie</div>
    <a href="{{ route('blog.index') }}" style="font-size:.75rem;color:var(--terra);text-decoration:none">← Voir tous les articles</a>
</div>

@else

@php $featured = $posts->first(); $rest = $posts->slice(1); @endphp

{{-- ── ARTICLE FEATURED ── --}}
@php
    $featuredImg = $featured->thumbnail
        ? asset('storage/'.$featured->thumbnail)
        : ($featured->media_url && $featured->media_type === 'image' ? asset('storage/'.$featured->media_url) : null);
    $featuredExcerpt = Str::limit(strip_tags($featured->body ?? ''), 180);
    $featuredMins = max(1,(int)ceil(str_word_count(strip_tags($featured->body??''))/200));
@endphp
<a href="{{ route('posts.show', $featured->id) }}" class="featured-card" data-reveal style="margin-bottom:24px;">
    {{-- Image gauche --}}
    <div class="featured-img-wrap">
        @if($featuredImg)
            <img src="{{ $featuredImg }}" alt="{{ $featured->title }}" class="featured-img">
        @endif
        <div class="featured-img-overlay"></div>
        {{-- Catégorie badge --}}
        @if($featured->category)
        <div class="featured-badge">{{ $featured->category_label }}</div>
        @endif
    </div>

    {{-- Contenu droite --}}
    <div class="featured-content">
        <div class="featured-content-top">
            <div class="featured-eyebrow">
                <span style="display:inline-block;width:20px;height:1px;background:var(--terra);opacity:.8;vertical-align:middle;margin-right:8px;"></span>
                À la une
            </div>
            <h2 class="featured-title">{{ $featured->title }}</h2>
            @if($featuredExcerpt)
            <p class="featured-excerpt">{{ $featuredExcerpt }}</p>
            @endif
        </div>
        <div class="featured-content-bottom">
            <div class="featured-meta">
                @if($featured->user->avatar)
                    <img src="{{ asset('storage/'.$featured->user->avatar) }}" class="featured-avi" alt="">
                @else
                    <div class="featured-avi featured-avi-initial">{{ strtoupper(substr($featured->user->name,0,1)) }}</div>
                @endif
                <span>{{ $featured->user->name }}</span>
                <span class="featured-dot"></span>
                <span>{{ $featured->created_at->format('d M Y') }}</span>
                <span class="featured-dot"></span>
                <span>{{ $featuredMins }} min</span>
            </div>
            <div class="featured-cta">
                Lire l'article
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
        </div>
    </div>
</a>

{{-- ── GRILLE ARTICLES ── --}}
@if($rest->isNotEmpty())
<div class="posts-grid">
    @foreach($rest as $post)
    @php
        $excerpt  = Str::limit(strip_tags($post->body ?? ''), 130);
        $mins     = max(1,(int)ceil(str_word_count(strip_tags($post->body??''))/200));
        $initial  = strtoupper(substr($post->user->name ?? '?', 0, 1));
        $thumbUrl = $post->thumbnail
            ? asset('storage/'.$post->thumbnail)
            : ($post->media_url && $post->media_type === 'image' ? asset('storage/'.$post->media_url) : null);
        $catClass = $post->category ? 'cat-'.str_replace('_','-',$post->category) : 'cat-default';
        $catIcons = [
            'manga-anime'   => '🎌',
            'gaming'        => '🎮',
            'tech'          => '💻',
            'dev'           => '⌨️',
            'cinema-series' => '🎬',
            'culture'       => '🌍',
            'debat'         => '💬',
        ];
        $catIcon = $catIcons[$post->category] ?? '📰';
    @endphp
    <a href="{{ route('posts.show', $post->id) }}" class="post-card" data-reveal data-delay="{{ ($loop->index % 5) + 1 }}">

        {{-- Banner : image ou gradient coloré par catégorie --}}
        <div class="post-card-banner {{ $thumbUrl ? '' : $catClass }}">
            @if($thumbUrl)
                <img src="{{ $thumbUrl }}" alt="{{ $post->title }}">
            @else
                <div class="post-card-banner-icon">{{ $catIcon }}</div>
            @endif
        </div>

        {{-- Contenu --}}
        <div class="post-card-body">
            @if($post->category)
            <div class="post-card-cat">{{ $post->category_label }}</div>
            @endif
            <div class="post-card-title">{{ $post->title }}</div>
            @if($excerpt)
            <div class="post-card-excerpt">{{ $excerpt }}</div>
            @endif
            <div class="post-card-meta">
                <div class="post-card-avi">
                    @if($post->user->avatar)
                        <img src="{{ asset('storage/'.$post->user->avatar) }}" alt="">
                    @else
                        {{ $initial }}
                    @endif
                </div>
                <span>{{ $post->user->name }}</span>
                <span class="post-card-dot"></span>
                <span>{{ $post->created_at->format('d M Y') }}</span>
                <span class="post-card-dot"></span>
                <span>{{ $mins }} min</span>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endif

{{-- ── PAGINATION ── --}}
@if($posts->hasPages())
<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
    <div style="font-size:.68rem;color:var(--text-muted)">
        Affichage de <strong style="color:var(--text)">{{ $posts->firstItem() }}–{{ $posts->lastItem() }}</strong>
        sur <strong style="color:var(--text)">{{ $posts->total() }}</strong> articles
    </div>
    <div style="display:flex;gap:4px">
        @if($posts->onFirstPage())
        <span style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-faint)">‹</span>
        @else
        <a href="{{ $posts->previousPageUrl() }}" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none">‹</a>
        @endif

        @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
        @if($page == $posts->currentPage())
        <span style="width:34px;height:34px;border-radius:7px;border:1px solid var(--terra);background:var(--terra-soft);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:var(--terra)">{{ $page }}</span>
        @elseif(abs($page - $posts->currentPage()) <= 2 || $page == 1 || $page == $posts->lastPage())
        <a href="{{ $url }}" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none">{{ $page }}</a>
        @elseif(abs($page - $posts->currentPage()) == 3)
        <span style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-faint)">…</span>
        @endif
        @endforeach

        @if($posts->hasMorePages())
        <a href="{{ $posts->nextPageUrl() }}" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none">›</a>
        @else
        <span style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-faint)">›</span>
        @endif
    </div>
</div>
@endif

@endif {{-- end $posts->isEmpty() --}}

@endsection

@push('scripts')
<script>
/* ══ TEXT SCRAMBLE — titre hero ══ */
(function () {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&';

    function scramble(el) {
        // On ne travaille que sur le premier noeud texte du h1 (avant le <br>)
        const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT);
        const nodes  = [];
        while (walker.nextNode()) nodes.push(walker.currentNode);
        if (!nodes.length) return;

        // Prendre uniquement les deux premiers noeuds texte (avant/après <br>)
        nodes.slice(0, 2).forEach(function (node) {
            const original = node.nodeValue;
            if (!original.trim()) return;

            let frame = 0;
            const totalFrames = original.length * 3 + 12;

            const tick = setInterval(function () {
                let result = '';
                for (let i = 0; i < original.length; i++) {
                    if (original[i] === ' ' || original[i] === '\n') {
                        result += original[i];
                    } else if (i < frame / 3) {
                        result += original[i];
                    } else {
                        result += chars[Math.floor(Math.random() * chars.length)];
                    }
                }
                node.nodeValue = result;
                frame++;
                if (frame >= totalFrames) {
                    node.nodeValue = original;
                    clearInterval(tick);
                }
            }, 40);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const el = document.querySelector('[data-scramble]');
        if (el) setTimeout(function () { scramble(el); }, 200);
    });
})();
</script>
@endpush
