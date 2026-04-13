<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MelanoGeek') — Blog Geek Africain</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <meta name="theme-color" content="#C8522A">
    @stack('meta')

    {{-- Fonts --}}
    <script>
    (function(){
        var t = localStorage.getItem('mg-theme') || 'dark';
        var f = 'family=DM+Serif+Display&family=Bricolage+Grotesque:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@300;400;500;600';
        if (t === 'light') f += '&family=Sora:wght@300;400;500;600';
        var pc1 = document.createElement('link'); pc1.rel = 'preconnect'; pc1.href = 'https://fonts.googleapis.com';
        var pc2 = document.createElement('link'); pc2.rel = 'preconnect'; pc2.href = 'https://fonts.gstatic.com'; pc2.crossOrigin = 'anonymous';
        var lk  = document.createElement('link'); lk.rel = 'stylesheet';
        lk.href = 'https://fonts.googleapis.com/css2?' + f + '&display=swap';
        document.head.append(pc1, pc2, lk);
        document.documentElement.setAttribute('data-theme', t);
    })();
    </script>

    <style>
    /* ═══ VARIABLES (reprise de app.blade.php) ═══ */
    :root, [data-theme="dark"] {
        --bg: #1a1a1a; --bg-card: #1f1f1f; --bg-card2: #242424; --bg-hover: #2a2a2a;
        --text: #F0E8D8; --text-muted: rgba(240,232,216,0.55); --text-faint: rgba(240,232,216,0.18);
        --border: rgba(240,232,216,0.10); --border-hover: rgba(240,232,216,0.22);
        --nav-bg: rgba(26,26,26,0.90); --toggle-bg: rgba(240,232,216,0.08);
        --terra: #C8522A; --terra-soft: rgba(200,82,42,0.12);
        --gold: #D4A843; --gold-soft: rgba(212,168,67,0.10);
        --accent: #E06030; --green: #2A7A48;
        --font-head: 'Bricolage Grotesque', sans-serif; --font-body: 'Outfit', sans-serif;
        --shadow-sm: 0 4px 12px rgba(0,0,0,0.40); --shadow-md: 0 8px 24px rgba(0,0,0,0.50);
    }
    [data-theme="light"] {
        --bg: #F5EDD6; --bg-card: #FBF5E6; --bg-card2: #EDE0C0; --bg-hover: #E8D9B0;
        --text: #1E0E04; --text-muted: rgba(30,14,4,0.55); --text-faint: rgba(30,14,4,0.18);
        --border: rgba(30,14,4,0.12); --border-hover: rgba(30,14,4,0.25);
        --nav-bg: rgba(245,237,214,0.90); --toggle-bg: #EDE0C0;
        --terra: #C84818; --terra-soft: rgba(200,72,24,0.10);
        --gold: #B87820; --gold-soft: rgba(184,120,32,0.10);
        --accent: #E85A1A; --green: #1A5A30;
        --font-head: 'Bricolage Grotesque', sans-serif; --font-body: 'Sora', sans-serif;
        --shadow-sm: 0 4px 12px rgba(30,14,4,0.08); --shadow-md: 0 8px 24px rgba(30,14,4,0.12);
    }

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; overflow-x: hidden; }
    body { background: var(--bg); color: var(--text); font-family: var(--font-body); }
    a { cursor: pointer; } button { cursor: pointer; }

    /* ─── NAVIGATION ─── */
    .blog-nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 200;
        height: 56px;
        padding: 0 52px;
        display: flex; align-items: center; justify-content: space-between; gap: 24px;
        background: rgba(26,26,26,.92);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
    }

    .blog-nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
    .blog-nav-logo svg { width: 26px; height: 26px; flex-shrink: 0; }
    .blog-nav-logo-name {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-weight: 800; font-size: 1rem; letter-spacing: -.04em;
        color: rgba(255,255,255,.92);
    }
    .blog-nav-logo-name span { color: var(--gold); }

    .blog-nav-links { display: flex; gap: 24px; list-style: none; }
    .blog-nav-links a {
        font-family: 'JetBrains Mono', monospace;
        font-size: .7rem; font-weight: 500;
        color: rgba(255,255,255,.45);
        text-decoration: none;
        letter-spacing: .05em; text-transform: uppercase;
        transition: color .18s;
    }
    .blog-nav-links a:hover,
    .blog-nav-links a.active { color: rgba(255,255,255,.90); }

    .blog-nav-right { display: flex; align-items: center; gap: 8px; }
    .blog-nav-search {
        display: flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.04);
        border: 1px solid var(--border);
        border-radius: 7px;
        padding: 6px 12px;
        transition: border-color .2s;
    }
    .blog-nav-search:focus-within { border-color: rgba(255,255,255,.20); }
    .blog-nav-search input {
        background: none; border: none; outline: none;
        color: var(--text); font-family: 'JetBrains Mono', monospace;
        font-size: .68rem; width: 140px; letter-spacing: .02em;
    }
    .blog-nav-search input::placeholder { color: var(--text-faint); }
    .blog-nav-search svg { color: var(--text-faint); flex-shrink: 0; }
    .blog-nav-btn {
        background: rgba(255,255,255,.90); color: rgba(0,0,0,.90); border: none;
        padding: 0 16px; height: 32px; border-radius: 999px;
        font-family: 'JetBrains Mono', monospace; font-size: .68rem; font-weight: 500;
        letter-spacing: .05em; text-transform: uppercase;
        text-decoration: none; display: inline-flex; align-items: center;
        transition: background .15s;
    }
    .blog-nav-btn:hover { background: #fff; }

    /* Theme toggle */
    .blog-theme-toggle {
        width: 32px; height: 32px; border-radius: 50%;
        background: var(--toggle-bg); border: 1px solid var(--border);
        color: var(--text-muted); font-size: .88rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .2s;
    }
    .blog-theme-toggle:hover { border-color: rgba(255,255,255,.3); color: rgba(255,255,255,.9); background: rgba(255,255,255,.06); }

    /* Hamburger */
    .blog-hamburger {
        display: none; flex-direction: column; align-items: center; justify-content: center;
        gap: 4px; width: 32px; height: 32px;
        background: var(--toggle-bg); border: 1px solid var(--border);
        border-radius: 7px; cursor: pointer;
    }
    .blog-hamburger span { display: block; width: 15px; height: 1.5px; background: var(--text); border-radius: 2px; transition: all .2s; }
    .blog-hamburger.open span:nth-child(1) { transform: translateY(5.5px) rotate(45deg); }
    .blog-hamburger.open span:nth-child(2) { opacity: 0; }
    .blog-hamburger.open span:nth-child(3) { transform: translateY(-5.5px) rotate(-45deg); }

    /* ─── LAYOUT PRINCIPAL ─── */
    .blog-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 76px 52px 64px;
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 48px;
        align-items: start;
    }
    .blog-main { min-width: 0; }

    /* ─── SIDEBAR ─── */
    .blog-sidebar { position: sticky; top: 72px; display: flex; flex-direction: column; gap: 28px; }

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

    /* ─── BREADCRUMB ─── */
    .blog-breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: .63rem; color: var(--text-faint);
        margin-bottom: 28px;
        flex-wrap: wrap;
    }
    .blog-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color .16s; }
    .blog-breadcrumb a:hover { color: var(--text); }
    .blog-breadcrumb-sep { color: var(--text-faint); }

    /* ─── FOOTER ─── */
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

    /* ─── RESPONSIVE ─── */
    @media (max-width: 1024px) {
        .blog-wrap { grid-template-columns: 1fr; }
        .blog-sidebar { position: static; }
    }
    @media (max-width: 768px) {
        .blog-nav { padding: 0 16px; }
        .blog-nav-links, .blog-nav-search { display: none; }
        .blog-hamburger { display: flex; }
        .blog-nav-btn { display: none; }
        .blog-wrap { padding: 80px 16px 48px; }
        .blog-footer { padding: 24px 16px; flex-direction: column; text-align: center; }
        .blog-footer-links { flex-wrap: wrap; justify-content: center; }
    }
    </style>

    @stack('styles')
</head>
<body>

{{-- ══ NAVIGATION ══ --}}
<nav class="blog-nav">
    <a href="{{ route('home') }}" class="blog-nav-logo">
        <svg viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="var(--bg-card2)" stroke="#D4A843" stroke-width="0.8"/>
            <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#C8522A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
        <div class="blog-nav-logo-name">Melano<span>Geek</span></div>
    </a>

    <ul class="blog-nav-links">
        <li><a href="{{ route('home') }}">Accueil</a></li>
        <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a></li>
        <li><a href="{{ route('forum.index') }}" class="{{ request()->routeIs('forum.*') ? 'active' : '' }}">Forum</a></li>
        <li><a href="{{ route('home') }}">Communauté</a></li>
        <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">À propos</a></li>
    </ul>

    <div class="blog-nav-right">
        <div class="blog-nav-search">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" placeholder="Rechercher…" id="blogSearchInput">
        </div>

        <button class="blog-theme-toggle" id="blogThemeToggle" title="Changer le thème">
            <span id="blogThemeIcon">☀️</span>
        </button>

        @guest
            <a href="{{ route('login') }}" style="font-size:.72rem;color:var(--text-muted);text-decoration:none;padding:7px 12px;border:1px solid var(--border);border-radius:7px;transition:all .18s;" onmouseover="this.style.borderColor='rgba(255,255,255,.3)';this.style.color='rgba(255,255,255,.9)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">Connexion</a>
            <a href="{{ route('register') }}" class="blog-nav-btn">Rejoindre</a>
        @else
            <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,var(--terra),var(--gold));display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:white;text-decoration:none;cursor:pointer;">
                {{ mb_strtoupper(mb_substr(auth()->user()->username, 0, 1)) }}
            </div>
        @endguest

        <button class="blog-hamburger" id="blogHamburger">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- ══ CONTENU ══ --}}
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
                        <span class="sb-item-icon">✦</span>
                        <span class="sb-item-name">Tout</span>
                    </a>
                    @foreach([
                        ['manga-anime',   '🎌', 'Manga & Animé'],
                        ['gaming',        '🎮', 'Gaming'],
                        ['tech',          '💻', 'Tech & IA'],
                        ['cinema-series', '🎬', 'Cinéma & Séries'],
                        ['culture',       '🌍', 'Culture & Société'],
                        ['debat',         '💬', 'Débat'],
                    ] as [$slug, $icon, $name])
                    <a href="{{ route('blog.index') }}?category={{ $slug }}"
                       class="sb-item {{ $activeCategory === $slug ? 'active' : '' }}">
                        <span class="sb-item-icon">{{ $icon }}</span>
                        <span class="sb-item-name">{{ $name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="sidebar-block">
                <div class="sidebar-block-head">Tags populaires</div>
                <div class="sb-tags">
                    <a href="#" class="sb-tag">afrofuturisme</a>
                    <a href="#" class="sb-tag">manga</a>
                    <a href="#" class="sb-tag">gaming</a>
                    <a href="#" class="sb-tag">IA</a>
                    <a href="#" class="sb-tag">cosplay</a>
                    <a href="#" class="sb-tag">Nollywood</a>
                    <a href="#" class="sb-tag">webtoon</a>
                    <a href="#" class="sb-tag">esports</a>
                    <a href="#" class="sb-tag">RPG</a>
                    <a href="#" class="sb-tag">SF africaine</a>
                    <a href="#" class="sb-tag">comics</a>
                    <a href="#" class="sb-tag">pixel art</a>
                </div>
            </div>

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
    <span>© {{ date('Y') }} MelanoGeek — La culture geek, vue d'Afrique.</span>
    <ul class="blog-footer-links">
        <li><a href="{{ route('home') }}">Accueil</a></li>
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
        <li><a href="{{ route('forum.index') }}">Forum</a></li>
        <li><a href="{{ route('home') }}">Communauté</a></li>
        <li><a href="{{ route('about') }}">À propos</a></li>
    </ul>
</footer>

<script>
// Theme toggle
(function(){
    var btn = document.getElementById('blogThemeToggle');
    var icon = document.getElementById('blogThemeIcon');
    var html = document.documentElement;
    function apply(t){
        html.setAttribute('data-theme', t);
        localStorage.setItem('mg-theme', t);
        icon.textContent = t === 'light' ? '🌙' : '☀️';
    }
    apply(localStorage.getItem('mg-theme') || 'dark');
    btn && btn.addEventListener('click', function(){
        apply(html.getAttribute('data-theme') === 'light' ? 'dark' : 'light');
    });
})();
</script>

@stack('scripts')
</body>
</html>
