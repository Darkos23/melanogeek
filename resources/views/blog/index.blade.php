@extends('layouts.blog')

@section('title', 'Blog')

@section('main')

{{-- ── EN-TÊTE PAGE ── --}}
<div style="margin-bottom:40px">
    <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);margin-bottom:14px;display:flex;align-items:center;gap:10px">
        <span style="display:inline-block;width:24px;height:1px;background:var(--gold);opacity:.7"></span>
        MelanoGeek · Blog
    </div>
    <h1 style="font-family:'DM Serif Display',serif;font-size:clamp(2rem,4vw,3.2rem);font-weight:400;line-height:1.1;letter-spacing:-.01em;color:rgba(255,255,255,.92);margin-bottom:16px">
        Culture geek,<br><span style="color:var(--gold)">vue d'Afrique.</span>
    </h1>
    <p style="font-size:.85rem;color:rgba(255,255,255,.45);line-height:1.65;max-width:460px;font-family:'Inter',sans-serif">
        Articles, reviews, analyses et coups de cœur autour du manga, du gaming, du développement, de la tech et de la culture nerd africaine.
    </p>
</div>

{{-- ── FILTRES CATÉGORIES ── --}}
<div style="display:flex;align-items:center;gap:0;margin-bottom:36px;border-bottom:1px solid var(--border)">
    @php
    $cats = [
        ''               => 'Tout',
        'manga-anime'    => 'Manga',
        'gaming'         => 'Gaming',
        'tech'           => 'Tech & IA',
        'dev'            => 'Développement',
        'cinema-series'  => 'Cinéma',
        'culture'        => 'Culture',
        'debat'          => 'Débat',
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
<a href="{{ route('blog.index') }}" style="display:block;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden;text-decoration:none;margin-bottom:20px;transition:background .2s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='var(--bg-card)'">
    <div style="aspect-ratio:16/6;background:linear-gradient(135deg,#2A1206,#7A3010,#C84818);position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;font-size:4rem">
        @if($featured->thumbnail)
            <img src="{{ asset('storage/'.$featured->thumbnail) }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
        @elseif($featured->media_url && $featured->media_type === 'image')
            <img src="{{ asset('storage/'.$featured->media_url) }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
        @else
            📰
        @endif
        <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(13,9,5,.75) 0%,rgba(13,9,5,.3) 60%)"></div>
        <div style="position:absolute;bottom:24px;left:28px;right:28px">
            @if($featured->category)
            <div style="font-size:.58rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.7);margin-bottom:8px">
                {{ $featured->category_label }}
            </div>
            @endif
            <div style="font-family:'DM Serif Display',serif;font-size:clamp(1.2rem,2.5vw,2rem);font-weight:400;letter-spacing:-.01em;color:white;line-height:1.2;text-shadow:0 2px 12px rgba(0,0,0,.4)">
                {{ $featured->title }}
            </div>
        </div>
    </div>
    <div style="padding:24px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
        <div style="flex:1;min-width:0">
            @php $excerpt = Str::limit(strip_tags($featured->body ?? ''), 160); @endphp
            @if($excerpt)
            <p style="font-size:.78rem;color:var(--text-muted);line-height:1.6;margin-bottom:14px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                {{ $excerpt }}
            </p>
            @endif
            <div style="display:flex;align-items:center;gap:10px;font-size:.62rem;color:var(--text-muted)">
                @if($featured->user->avatar)
                    <img src="{{ asset('storage/'.$featured->user->avatar) }}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;flex-shrink:0" alt="">
                @else
                    <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,var(--terra),var(--gold));display:flex;align-items:center;justify-content:center;font-size:.62rem;font-weight:700;color:white;flex-shrink:0">{{ strtoupper(substr($featured->user->name,0,1)) }}</div>
                @endif
                <span>{{ $featured->user->name }}</span>
                <span style="width:3px;height:3px;background:var(--text-faint);border-radius:50%"></span>
                <span>{{ $featured->created_at->format('d M Y') }}</span>
                <span style="width:3px;height:3px;background:var(--text-faint);border-radius:50%"></span>
                <span>{{ max(1,(int)ceil(str_word_count(strip_tags($featured->body??''))/200)) }} min de lecture</span>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:.68rem;font-weight:600;color:var(--terra);white-space:nowrap">
            Lire l'article
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </div>
    </div>
</a>

{{-- ── GRILLE ARTICLES ── --}}
@if($rest->isNotEmpty())
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1px;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--border);margin-bottom:28px">
    @foreach($rest as $post)
    @php
        $excerpt = Str::limit(strip_tags($post->body ?? ''), 120);
        $mins    = max(1,(int)ceil(str_word_count(strip_tags($post->body??''))/200));
        $initial = strtoupper(substr($post->user->name ?? '?',0,1));
    @endphp
    <a href="{{ route('blog.index') }}" style="background:var(--bg-card);padding:24px 26px;text-decoration:none;display:block;transition:background .18s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='var(--bg-card)'">
        @if($post->category)
        <div style="font-family:'JetBrains Mono',monospace;font-size:.54rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:10px">
            {{ $post->category_label }}
        </div>
        @endif
        <div style="font-family:'DM Serif Display',serif;font-size:.95rem;font-weight:400;line-height:1.35;color:rgba(255,255,255,.88);margin-bottom:8px">
            {{ $post->title }}
        </div>
        @if($excerpt)
        <div style="font-size:.72rem;color:var(--text-muted);line-height:1.6;margin-bottom:14px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
            {{ $excerpt }}
        </div>
        @endif
        <div style="display:flex;align-items:center;gap:8px;font-size:.6rem;color:var(--text-muted)">
            @if($post->user->avatar)
                <img src="{{ asset('storage/'.$post->user->avatar) }}" style="width:20px;height:20px;border-radius:50%;object-fit:cover;flex-shrink:0" alt="">
            @else
                <div style="width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,var(--terra),var(--gold));display:flex;align-items:center;justify-content:center;font-size:.58rem;font-weight:700;color:white;flex-shrink:0">{{ $initial }}</div>
            @endif
            <span>{{ $post->user->name }}</span>
            <span style="width:3px;height:3px;background:var(--text-faint);border-radius:50%"></span>
            <span>{{ $post->created_at->format('d M Y') }}</span>
            <span style="width:3px;height:3px;background:var(--text-faint);border-radius:50%"></span>
            <span>{{ $mins }} min</span>
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
