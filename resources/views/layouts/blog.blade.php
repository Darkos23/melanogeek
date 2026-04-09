<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
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
        var t = localStorage.getItem('mg-theme') || 'light';
        var f = 'family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Outfit:wght@300;400;500;600';
        if (t === 'light') f += '&family=Unbounded:wght@300;400;600;700;900&family=Sora:wght@300;400;500;600';
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
        --bg: #0D0905; --bg-card: #141009; --bg-card2: #1C1810; --bg-hover: #221C13;
        --text: #F0E8D8; --text-muted: rgba(240,232,216,0.55); --text-faint: rgba(240,232,216,0.18);
        --border: rgba(240,232,216,0.10); --border-hover: rgba(240,232,216,0.22);
        --nav-bg: rgba(13,9,5,0.90); --toggle-bg: rgba(240,232,216,0.08);
        --terra: #C8522A; --terra-soft: rgba(200,82,42,0.12);
        --gold: #D4A843; --gold-soft: rgba(212,168,67,0.10);
        --accent: #E06030; --green: #2A7A48;
        --font-head: 'Plus Jakarta Sans', sans-serif; --font-body: 'Outfit', sans-serif;
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
        --font-head: 'Unbounded', sans-serif; --font-body: 'Sora', sans-serif;
        --shadow-sm: 0 4px 12px rgba(30,14,4,0.08); --shadow-md: 0 8px 24px rgba(30,14,4,0.12);
    }

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; overflow-x: hidden; }
    body { background: var(--bg); color: var(--text); font-family: var(--font-body); }
    a { cursor: pointer; } button { cursor: pointer; }

    /* ─── NAVIGATION ─── */
    .blog-nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 200;
        height: 64px;
        padding: 0 52px;
        display: flex; align-items: center; justify-content: space-between; gap: 24px;
        background: var(--nav-bg);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
    }

    .blog-nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
    .blog-nav-logo svg { width: 30px; height: 30px; flex-shrink: 0; }
    .blog-nav-logo-name {
        font-family: var(--font-head);
        font-weight: 700; font-size: .82rem;
        color: var(--text);
    }
    .blog-nav-logo-name span { color: var(--terra); }

    .blog-nav-links { display: flex; gap: 4px; list-style: none; }
    .blog-nav-links a {
        font-size: .72rem; font-weight: 600;
        color: var(--text-muted);
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all .18s;
        letter-spacing: .03em;
    }
    .blog-nav-links a:hover,
    .blog-nav-links a.active { color: var(--terra); background: var(--terra-soft); }

    .blog-nav-right { display: flex; align-items: center; gap: 8px; }
    .blog-nav-search {
        display: flex; align-items: center; gap: 8px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 7px 12px;
        transition: border-color .2s;
    }
    .blog-nav-search:focus-within { border-color: var(--terra); }
    .blog-nav-search input {
        background: none; border: none; outline: none;
        color: var(--text); font-family: var(--font-body);
        font-size: .72rem; width: 160px;
    }
    .blog-nav-search input::placeholder { color: var(--text-faint); }
    .blog-nav-search svg { color: var(--text-faint); flex-shrink: 0; }
    .blog-nav-btn {
        background: var(--terra); color: white; border: none;
        padding: 7px 16px; border-radius: 7px;
        font-family: var(--font-body); font-size: .72rem; font-weight: 600;
        text-decoration: none; transition: all .18s;
    }
    .blog-nav-btn:hover { background: var(--accent); }

    /* Theme toggle */
    .blog-theme-toggle {
        width: 32px; height: 32px; border-radius: 50%;
        background: var(--toggle-bg); border: 1px solid var(--border);
        color: var(--text-muted); font-size: .88rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .2s;
    }
    .blog-theme-toggle:hover { border-color: var(--terra); color: var(--terra); background: var(--terra-soft); }

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
        padding: 88px 52px 64px;
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 40px;
        align-items: start;
    }
    .blog-main { min-width: 0; }

    /* ─── SIDEBAR ─── */
    .blog-sidebar { position: sticky; top: 84px; display: flex; flex-direction: column; gap: 20px; }

    .sidebar-block {
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        background: var(--bg-card);
    }
    .sidebar-block-head {
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        font-family: var(--font-head);
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--text-muted);
    }
    .sidebar-block-body { padding: 4px 0; }

    .sb-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 18px;
        text-decoration: none;
        font-size: .75rem;
        color: var(--text-muted);
        transition: all .16s;
        border-bottom: 1px solid var(--border);
    }
    .sb-item:last-child { border-bottom: none; }
    .sb-item:hover { background: var(--bg-hover); color: var(--terra); }
    .sb-item-icon { font-size: 1rem; flex-shrink: 0; }
    .sb-item-name { flex: 1; font-weight: 500; }
    .sb-item-count { font-family: var(--font-head); font-size: .65rem; font-weight: 700; color: var(--text-faint); }

    /* Tags */
    .sb-tags { padding: 14px 18px; display: flex; flex-wrap: wrap; gap: 6px; }
    .sb-tag {
        font-size: .62rem; font-weight: 600;
        color: var(--text-muted);
        background: var(--bg-card2);
        border: 1px solid var(--border);
        padding: 3px 10px; border-radius: 100px;
        text-decoration: none;
        transition: all .16s;
        letter-spacing: .03em;
    }
    .sb-tag:hover { border-color: var(--terra); color: var(--terra); background: var(--terra-soft); }

    /* Newsletter */
    .sb-newsletter { padding: 18px; }
    .sb-newsletter p { font-size: .72rem; color: var(--text-muted); line-height: 1.55; margin-bottom: 12px; }
    .sb-newsletter input {
        width: 100%; background: var(--bg-card2); border: 1px solid var(--border);
        color: var(--text); font-family: var(--font-body); font-size: .72rem;
        padding: 9px 12px; border-radius: 7px; outline: none; margin-bottom: 8px;
        transition: border-color .2s;
    }
    .sb-newsletter input:focus { border-color: var(--terra); }
    .sb-newsletter button {
        width: 100%; background: var(--terra); color: white; border: none;
        padding: 9px 16px; border-radius: 7px; font-family: var(--font-body);
        font-size: .72rem; font-weight: 600; cursor: pointer; transition: background .18s;
    }
    .sb-newsletter button:hover { background: var(--accent); }

    /* ─── BREADCRUMB ─── */
    .blog-breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: .63rem; color: var(--text-faint);
        margin-bottom: 28px;
        flex-wrap: wrap;
    }
    .blog-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color .16s; }
    .blog-breadcrumb a:hover { color: var(--terra); }
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
    .blog-footer-links a:hover { color: var(--terra); }

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
        <li><a href="{{ route('about') }}">À propos</a></li>
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
            <a href="{{ route('login') }}" style="font-size:.72rem;color:var(--text-muted);text-decoration:none;padding:7px 12px;border:1px solid var(--border);border-radius:7px;transition:all .18s;" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">Connexion</a>
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
                    <a href="{{ route('blog.index') }}?cat=manga" class="sb-item">
                        <span class="sb-item-icon">🎌</span>
                        <span class="sb-item-name">Manga & Animé</span>
                        <span class="sb-item-count">142</span>
                    </a>
                    <a href="{{ route('blog.index') }}?cat=gaming" class="sb-item">
                        <span class="sb-item-icon">🎮</span>
                        <span class="sb-item-name">Gaming</span>
                        <span class="sb-item-count">98</span>
                    </a>
                    <a href="{{ route('blog.index') }}?cat=tech" class="sb-item">
                        <span class="sb-item-icon">💻</span>
                        <span class="sb-item-name">Tech & IA</span>
                        <span class="sb-item-count">76</span>
                    </a>
                    <a href="{{ route('blog.index') }}?cat=cinema" class="sb-item">
                        <span class="sb-item-icon">🎬</span>
                        <span class="sb-item-name">Cinéma & Séries</span>
                        <span class="sb-item-count">64</span>
                    </a>
                    <a href="{{ route('blog.index') }}?cat=bd" class="sb-item">
                        <span class="sb-item-icon">📚</span>
                        <span class="sb-item-name">BD & Comics</span>
                        <span class="sb-item-count">47</span>
                    </a>
                    <a href="{{ route('blog.index') }}?cat=culture" class="sb-item">
                        <span class="sb-item-icon">🌍</span>
                        <span class="sb-item-name">Culture africaine</span>
                        <span class="sb-item-count">89</span>
                    </a>
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
    apply(localStorage.getItem('mg-theme') || 'light');
    btn && btn.addEventListener('click', function(){
        apply(html.getAttribute('data-theme') === 'light' ? 'dark' : 'light');
    });
})();
</script>

@stack('scripts')
</body>
</html>
