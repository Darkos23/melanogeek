@extends('layouts.app')

@push('styles')
<style>

/* ── LAYOUT PRINCIPAL ── */
.blog-wrap {
    max-width: 1280px;
    margin: 0 auto;
    padding: 48px 52px 64px;
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 48px;
    align-items: start;
}
.blog-main { min-width: 0; }

/* ── SIDEBAR ── */
.blog-sidebar { position: sticky; top: 24px; display: flex; flex-direction: column; gap: 28px; }

.sidebar-block {
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    background: transparent;
}
.sidebar-block-head {
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--gold);
}
.sidebar-block-body { padding: 4px 0; }

.sb-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 16px;
    text-decoration: none;
    font-family: 'JetBrains Mono', monospace;
    font-size: .68rem; letter-spacing: .03em;
    color: rgba(255,255,255,.45);
    transition: all .16s;
    border-bottom: 1px solid var(--border);
}
.sb-item:last-child { border-bottom: none; }
.sb-item:hover { color: rgba(255,255,255,.85); background: rgba(255,255,255,.03); }
.sb-item.active { color: var(--gold); }
.sb-item-icon { font-size: .9rem; flex-shrink: 0; }
.sb-item-name { flex: 1; }

/* Tags */
.sb-tags { padding: 12px 16px; display: flex; flex-wrap: wrap; gap: 6px; }
.sb-tag {
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem; font-weight: 500;
    color: rgba(255,255,255,.35);
    background: transparent;
    border: 1px solid var(--border);
    padding: 3px 9px; border-radius: 100px;
    text-decoration: none;
    transition: all .16s;
    letter-spacing: .04em;
}
.sb-tag:hover { border-color: rgba(255,255,255,.20); color: rgba(255,255,255,.70); }

/* Newsletter */
.sb-newsletter { padding: 16px; }
.sb-newsletter p { font-size: .7rem; color: var(--text-faint); line-height: 1.55; margin-bottom: 12px; font-family: 'JetBrains Mono', monospace; letter-spacing: .02em; }
.sb-newsletter input {
    width: 100%; background: rgba(255,255,255,.04); border: 1px solid var(--border);
    color: var(--text); font-family: 'JetBrains Mono', monospace; font-size: .68rem;
    padding: 8px 12px; border-radius: 6px; outline: none; margin-bottom: 8px;
    transition: border-color .2s;
}
.sb-newsletter input:focus { border-color: rgba(255,255,255,.20); }
.sb-newsletter button {
    width: 100%; background: rgba(255,255,255,.90); color: rgba(0,0,0,.90); border: none;
    padding: 9px 16px; border-radius: 6px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .68rem; font-weight: 500; letter-spacing: .05em; text-transform: uppercase;
    cursor: pointer; transition: background .15s;
}
.sb-newsletter button:hover { background: #fff; }

/* ── BREADCRUMB ── */
.blog-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .63rem; color: var(--text-faint);
    margin-bottom: 28px;
    flex-wrap: wrap;
}
.blog-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color .16s; }
.blog-breadcrumb a:hover { color: var(--text); }
.blog-breadcrumb-sep { color: var(--text-faint); }

/* ── FOOTER ── */
.blog-footer {
    border-top: 1px solid var(--border);
    padding: 28px 52px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; flex-wrap: wrap;
    font-size: .62rem; color: var(--text-faint);
}
.blog-footer-links { display: flex; gap: 20px; list-style: none; }
.blog-footer-links a { color: var(--text-muted); text-decoration: none; transition: color .16s; }
.blog-footer-links a:hover { color: var(--text); }

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
    .blog-wrap { grid-template-columns: 1fr; padding: 32px 28px 56px; }
    .blog-sidebar { display: none; }
}
@media (max-width: 768px) {
    .blog-wrap { padding: 20px 16px 48px; }
    .blog-footer { padding: 20px 16px; flex-direction: column; text-align: center; }
    .blog-footer-links { flex-wrap: wrap; justify-content: center; }
}
</style>
@endpush

@section('content')

{{-- ══ TWO-COLUMN LAYOUT ══ --}}
<div class="blog-wrap">
    <main class="blog-main">
        @if(isset($breadcrumbs))
        <div class="blog-breadcrumb">
            <a href="{{ route('home') }}">Accueil</a>
            @foreach($breadcrumbs as $label => $url)
                <span class="blog-breadcrumb-sep">›</span>
                @if($loop->last)
                    <span>{{ $label }}</span>
                @else
                    <a href="{{ $url }}">{{ $label }}</a>
                @endif
            @endforeach
        </div>
        @endif

        @yield('main')
    </main>

    <aside class="blog-sidebar">
        @hasSection('sidebar')
            @yield('sidebar')
        @else
            {{-- Sidebar par défaut --}}
            <div class="sidebar-block">
                <div class="sidebar-block-head">Catégories</div>
                <div class="sidebar-block-body">
                    @php $activeCategory = request('category'); @endphp
                    <a href="{{ route('blog.index') }}" class="sb-item {{ !$activeCategory ? 'active' : '' }}">
                        <span class="sb-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></span>
                        <span class="sb-item-name">Tout</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=manga-anime" class="sb-item {{ $activeCategory === 'manga-anime' ? 'active' : '' }}">
                        <span class="sb-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></span>
                        <span class="sb-item-name">Manga &amp; Animé</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=gaming" class="sb-item {{ $activeCategory === 'gaming' ? 'active' : '' }}">
                        <span class="sb-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 12h4m-2-2v4"/><circle cx="16" cy="10" r="1" fill="currentColor"/><circle cx="18" cy="12" r="1" fill="currentColor"/></svg></span>
                        <span class="sb-item-name">Gaming</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=tech" class="sb-item {{ $activeCategory === 'tech' ? 'active' : '' }}">
                        <span class="sb-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></span>
                        <span class="sb-item-name">Tech &amp; IA</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=dev" class="sb-item {{ $activeCategory === 'dev' ? 'active' : '' }}">
                        <span class="sb-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></span>
                        <span class="sb-item-name">Développement</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=cinema-series" class="sb-item {{ $activeCategory === 'cinema-series' ? 'active' : '' }}">
                        <span class="sb-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="2"/><path d="M7 2v20M17 2v20M2 12h20M2 7h5M17 7h5M2 17h5M17 17h5"/></svg></span>
                        <span class="sb-item-name">Cinéma &amp; Séries</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=culture" class="sb-item {{ $activeCategory === 'culture' ? 'active' : '' }}">
                        <span class="sb-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2l2.5 7.5H22l-6 4.5 2.5 7.5L12 17l-6.5 4.5L8 14 2 9.5h7.5z"/></svg></span>
                        <span class="sb-item-name">Afrofuturisme</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=debat" class="sb-item {{ $activeCategory === 'debat' ? 'active' : '' }}">
                        <span class="sb-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>
                        <span class="sb-item-name">Débat</span>
                    </a>
                </div>
            </div>

            @php
                $sbCatCounts = \App\Models\Post::published()
                    ->whereNotNull('category')
                    ->selectRaw('category, count(*) as total')
                    ->groupBy('category')
                    ->pluck('total', 'category');
            @endphp
            @if($sbCatCounts->isNotEmpty())
            <div class="sidebar-block">
                <div class="sidebar-block-head">Tags</div>
                <div class="sb-tags">
                    @foreach(\App\Models\Post::CATEGORIES as $slug => $label)
                        @if($sbCatCounts->has($slug))
                        <a href="{{ route('blog.index') }}?category={{ $slug }}" class="sb-tag">
                            {{ $label }} <span style="opacity:.5;font-size:.8em">({{ $sbCatCounts[$slug] }})</span>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <div class="sidebar-block">
                <div class="sidebar-block-head">Newsletter</div>
                <div class="sb-newsletter">
                    <p>Reçois les meilleurs articles de la semaine directement dans ta boîte mail.</p>
                    <input type="email" placeholder="ton@email.com">
                    <button type="button">S'abonner</button>
                </div>
            </div>
        @endif
    </aside>
</div>

{{-- ══ FOOTER ══ --}}
<footer class="blog-footer">
    <span>© {{ date('Y') }} MelanoGeek – La culture geek, vue d'Afrique.</span>
    <ul class="blog-footer-links">
        <li><a href="{{ route('home') }}">Accueil</a></li>
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
        <li><a href="{{ route('forum.index') }}">Forum</a></li>
        <li><a href="{{ route('community') }}">Communauté</a></li>
        <li><a href="{{ route('about') }}">À propos</a></li>
    </ul>
    {{-- Développé par Korilab — masqué temporairement --}}
</footer>

@endsection
