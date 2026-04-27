@extends('layouts.app')

@push('styles')
<style>

/* â”€â”€ LAYOUT PRINCIPAL â”€â”€ */
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

/* â”€â”€ SIDEBAR â”€â”€ */
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

/* Forum sidebar */
.sb-thread {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px 14px;
    text-decoration: none;
    border-bottom: 1px solid var(--border);
    transition: background .15s;
}
.sb-thread:last-child { border-bottom: none; }
.sb-thread:hover { background: rgba(255,255,255,.03); }
.sb-thread-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: rgba(255,255,255,.05);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0; margin-top: 1px;
    border: 1px solid var(--border);
}
.sb-thread-content { flex: 1; min-width: 0; }
.sb-thread-title {
    font-size: .68rem; font-weight: 600; line-height: 1.35;
    color: rgba(255,255,255,.75);
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.sb-thread:hover .sb-thread-title { color: rgba(255,255,255,.95); }
.sb-thread-meta {
    font-family: 'JetBrains Mono', monospace;
    font-size: .55rem; color: var(--text-faint);
    margin-top: 4px; display: flex; align-items: center; gap: 5px;
}
.sb-thread-replies {
    background: rgba(255,255,255,.06); border-radius: 4px;
    padding: 1px 5px; font-size: .55rem; color: var(--text-muted);
    font-family: 'JetBrains Mono', monospace; font-weight: 600;
}
.sb-online-dot {
    display: inline-block; width: 6px; height: 6px;
    background: #22c55e; border-radius: 50%;
    box-shadow: 0 0 6px rgba(34,197,94,.6);
}

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

/* â”€â”€ BREADCRUMB â”€â”€ */
.blog-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .63rem; color: var(--text-faint);
    margin-bottom: 28px;
    flex-wrap: wrap;
}
.blog-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color .16s; }
.blog-breadcrumb a:hover { color: var(--text); }
.blog-breadcrumb-sep { color: var(--text-faint); }

/* â”€â”€ FOOTER â”€â”€ */
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

/* â”€â”€ RESPONSIVE â”€â”€ */
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

{{-- â•â• TWO-COLUMN LAYOUT â•â• --}}
<div class="blog-wrap">
    <main class="blog-main">
        @if(isset($breadcrumbs))
        <div class="blog-breadcrumb">
            <a href="{{ route('home') }}">Accueil</a>
            @foreach($breadcrumbs as $label => $url)
                <span class="blog-breadcrumb-sep">â€º</span>
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
            {{-- Sidebar par dÃ©faut --}}
            <div class="sidebar-block">
                <div class="sidebar-block-head">CatÃ©gories</div>
                <div class="sidebar-block-body">
    @php $activeCategory = request('category'); @endphp
    <a href="{{ route('blog.index') }}" class="sb-item {{ !$activeCategory ? 'active' : '' }}">
        <span class="sb-item-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></span>
        <span class="sb-item-name">Tout</span>
    </a>
    <a href="{{ route('blog.index') }}?category=manga-anime" class="sb-item {{ $activeCategory === 'manga-anime' ? 'active' : '' }}">
        <span class="sb-item-icon">🎌</span>
        <span class="sb-item-name">Animés &amp; mangas</span>
    </a>
    <a href="{{ route('blog.index') }}?category=gaming" class="sb-item {{ $activeCategory === 'gaming' ? 'active' : '' }}">
        <span class="sb-item-icon">🎮</span>
        <span class="sb-item-name">Gaming &amp; E-sport</span>
    </a>
    <a href="{{ route('blog.index') }}?category=cinema-series" class="sb-item {{ $activeCategory === 'cinema-series' ? 'active' : '' }}">
        <span class="sb-item-icon">🎬</span>
        <span class="sb-item-name">Cinéma &amp; séries</span>
    </a>
    <a href="{{ route('blog.index') }}?category=tech" class="sb-item {{ $activeCategory === 'tech' ? 'active' : '' }}">
        <span class="sb-item-icon">🤖</span>
        <span class="sb-item-name">Tech &amp; IA</span>
    </a>
    <a href="{{ route('blog.index') }}?category=carriere" class="sb-item {{ $activeCategory === 'carriere' ? 'active' : '' }}">
        <span class="sb-item-icon">💼</span>
        <span class="sb-item-name">Éducation &amp; carrière</span>
    </a>
    <a href="{{ route('blog.index') }}?category=culture" class="sb-item {{ $activeCategory === 'culture' ? 'active' : '' }}">
        <span class="sb-item-icon">🚀</span>
        <span class="sb-item-name">Afrofuturisme</span>
    </a>
    <a href="{{ route('blog.index') }}?category=hardware" class="sb-item {{ $activeCategory === 'hardware' ? 'active' : '' }}">
        <span class="sb-item-icon">🖥️</span>
        <span class="sb-item-name">Hardware &amp; setup</span>
    </a>
    <a href="{{ route('blog.index') }}?category=web3-economie" class="sb-item {{ $activeCategory === 'web3-economie' ? 'active' : '' }}">
        <span class="sb-item-icon">₿</span>
        <span class="sb-item-name">Économie numérique</span>
    </a>
</div>
            </div>

            {{-- Forum actif --}}
            @php
                $sbThreads = \App\Models\ForumThread::with('user')
                    ->orderByDesc('last_reply_at')
                    ->limit(4)
                    ->get();
            @endphp
            @if($sbThreads->isNotEmpty())
            <div class="sidebar-block">
                <div class="sidebar-block-head" style="display:flex;align-items:center;justify-content:space-between;">
                    <span>Forum actif</span>
                    <span style="display:flex;align-items:center;gap:5px;font-size:.52rem;color:rgba(255,255,255,.35);">
                        <span class="sb-online-dot"></span> Live
                    </span>
                </div>
                <div class="sidebar-block-body">
                    @foreach($sbThreads as $t)
                    <a href="{{ route('forum.show', $t->id) }}" class="sb-thread">
                        <div class="sb-thread-icon">{{ $t->category_icon }}</div>
                        <div class="sb-thread-content">
                            <div class="sb-thread-title">{{ $t->title }}</div>
                            <div class="sb-thread-meta">
                                <span class="sb-thread-replies">{{ $t->replies_count }} rép.</span>
                                <span>{{ ($t->last_reply_at ?? $t->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                <div style="padding:10px 14px;border-top:1px solid var(--border)">
                    <a href="{{ route('forum.index') }}" style="font-family:'JetBrains Mono',monospace;font-size:.58rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--terra);text-decoration:none;display:flex;align-items:center;gap:6px;">
                        Voir tout le forum
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
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

{{-- â•â• FOOTER â•â• --}}
<footer class="blog-footer">
    <span>Â© {{ date('Y') }} MelanoGeek â€“ La culture geek, vue d'Afrique.</span>
    <ul class="blog-footer-links">
        <li><a href="{{ route('home') }}">Accueil</a></li>
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
        <li><a href="{{ route('forum.index') }}">Forum</a></li>
        <li><a href="{{ route('community') }}">CommunautÃ©</a></li>
        <li><a href="{{ route('about') }}">Ã€ propos</a></li>
    </ul>
    {{-- DÃ©veloppÃ© par Korilab â€” masquÃ© temporairement --}}
</footer>

@endsection

