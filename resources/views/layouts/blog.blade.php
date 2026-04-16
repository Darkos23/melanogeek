<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
        var f = 'family=DM+Serif+Display&family=Bricolage+Grotesque:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@300;400;500;600';
        var pc1 = document.createElement('link'); pc1.rel = 'preconnect'; pc1.href = 'https://fonts.googleapis.com';
        var pc2 = document.createElement('link'); pc2.rel = 'preconnect'; pc2.href = 'https://fonts.gstatic.com'; pc2.crossOrigin = 'anonymous';
        var lk  = document.createElement('link'); lk.rel = 'stylesheet';
        lk.href = 'https://fonts.googleapis.com/css2?' + f + '&display=swap';
        document.head.append(pc1, pc2, lk);
    })();
    </script>

    <style>
    /* â•â•â• VARIABLES (reprise de app.blade.php) â•â•â• */
    :root {
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

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; overflow-x: hidden; }
    body { background: var(--bg); color: var(--text); font-family: var(--font-body); }
    a { cursor: pointer; } button { cursor: pointer; }

    /* â”€â”€â”€ NAVIGATION â”€â”€â”€ */
    .blog-nav-wrapper {
        position: sticky;
        top: 0;
        z-index: 100;
        background: var(--nav-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--border);
    }
    .blog-nav {
        position: relative;
        padding: 0 48px;
        height: 64px;
        display: flex; align-items: center; justify-content: space-between; gap: 24px;
        background: transparent;
        border: none;
        max-width: 1280px;
        margin: 0 auto;
    }

    .blog-nav-logo { display: flex; align-items: center; text-decoration: none; }
    .blog-nav-logo-name {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-weight: 800; font-size: 1.4rem; letter-spacing: -.04em;
        color: rgba(255,255,255,.92);
    }
    .blog-nav-logo-name:hover { color: #fff; }

    .blog-nav-links { display: flex; gap: 28px; list-style: none; }
    .blog-nav-links a {
        font-family: 'JetBrains Mono', monospace;
        font-size: .75rem; font-weight: 500;
        color: rgba(255,255,255,.50);
        text-decoration: none;
        letter-spacing: .05em; text-transform: uppercase;
        transition: color .2s;
    }
    .blog-nav-links a:hover { color: rgba(255,255,255,.90); }

    .blog-nav-right { display: flex; align-items: center; gap: 8px; }
    .blog-nav-search {
        display: flex; align-items: center; gap: 8px;
        height: 36px;
        padding: 0 14px;
        background: rgba(0,0,0,.15);
        border: 1px solid transparent;
        border-radius: 999px;
        transition: background .2s, border-color .2s;
    }
    .blog-nav-search:hover,
    .blog-nav-search:focus-within {
        background: rgba(0,0,0,.25);
        border-color: rgba(255,255,255,.14);
    }
    .blog-nav-search input {
        background: none; border: none; outline: none;
        color: var(--text); font-family: 'JetBrains Mono', monospace;
        font-size: .75rem; width: 140px; letter-spacing: .04em;
    }
    .blog-nav-search input::placeholder { color: var(--text-faint); }
    .blog-nav-search svg { color: var(--text-faint); flex-shrink: 0; }
    .blog-nav-ghost {
        background: rgba(0,0,0,.15);
        border: 1px solid transparent;
        color: rgba(255,255,255,.92);
        padding: 0 14px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        font-family: 'JetBrains Mono', monospace;
        font-size: .8125rem;
        font-weight: 500;
        letter-spacing: .05em;
        text-transform: uppercase;
        text-decoration: none;
        transition: background .2s;
    }
    .blog-nav-ghost:hover { background: rgba(0,0,0,.25); }
    .blog-nav-btn {
        background: rgba(255,255,255,.90); color: rgba(0,0,0,.90); border: none;
        padding: 0 14px; height: 36px; border-radius: 999px;
        font-family: 'JetBrains Mono', monospace; font-size: .8125rem; font-weight: 500;
        letter-spacing: .05em; text-transform: uppercase;
        text-decoration: none; display: inline-flex; align-items: center;
        transition: background .2s;
    }
    .blog-nav-btn:hover { background: #fff; }

    .blog-editorial-bar {
        border-top: 1px solid var(--border);
        background: transparent;
        max-width: 1280px;
        margin-left: auto;
        margin-right: auto;
    }
    .blog-editorial-inner {
        padding: 9px 48px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
    }
    .blog-editorial-id {
        font-family: 'JetBrains Mono', monospace;
        font-size: .6rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--text-faint);
        white-space: nowrap;
    }
    .blog-editorial-id strong {
        color: var(--gold);
        font-weight: 600;
    }
    .blog-editorial-links {
        display: flex;
        align-items: center;
        gap: 20px;
        list-style: none;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .blog-editorial-links a {
        font-family: 'JetBrains Mono', monospace;
        font-size: .58rem;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--text-faint);
        text-decoration: none;
        transition: color .18s;
    }
    .blog-editorial-links a:hover { color: var(--gold); }

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

    /* â”€â”€â”€ LAYOUT PRINCIPAL â”€â”€â”€ */
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

    /* â”€â”€â”€ SIDEBAR â”€â”€â”€ */
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

    /* â”€â”€â”€ BREADCRUMB â”€â”€â”€ */
    .blog-breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: .63rem; color: var(--text-faint);
        margin-bottom: 28px;
        flex-wrap: wrap;
    }
    .blog-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color .16s; }
    .blog-breadcrumb a:hover { color: var(--text); }
    .blog-breadcrumb-sep { color: var(--text-faint); }

    /* â”€â”€â”€ FOOTER â”€â”€â”€ */
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

    /* â”€â”€â”€ RESPONSIVE â”€â”€â”€ */
    @media (max-width: 1024px) {
        .blog-wrap { grid-template-columns: 1fr; }
        .blog-sidebar { position: static; }
    }
    @media (max-width: 768px) {
        .blog-nav { padding: 0 16px; height: 64px; }
        .blog-editorial-bar { display: none; }
        .blog-nav-links, .blog-nav-search { display: none; }
        .blog-hamburger { display: flex; }
        .blog-nav-btn { display: none; }
        .blog-wrap { padding: 80px 16px 48px; }
        .blog-footer { padding: 24px 16px; flex-direction: column; text-align: center; }
        .blog-footer-links { flex-wrap: wrap; justify-content: center; }
        /* User menu : pill → icon only */
        .mg-user-name, #mgUserChevron { display: none !important; }
        .mg-user-btn { padding: 4px; border-radius: 50%; }
    }

    /* ── MOBILE NAV DRAWER ── */
    .blog-mobile-nav {
        position: fixed;
        inset: 0;
        z-index: 200;
        display: none;
    }
    .blog-mobile-nav.open { display: block; }
    .blog-mobile-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,.55);
        backdrop-filter: blur(4px);
    }
    .blog-mobile-drawer {
        position: absolute;
        top: 0; right: 0;
        width: min(300px, 85vw);
        height: 100%;
        background: var(--bg-card);
        border-left: 1px solid var(--border);
        padding: 24px 20px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        overflow-y: auto;
        transform: translateX(100%);
        transition: transform .25s cubic-bezier(.4,0,.2,1);
    }
    .blog-mobile-nav.open .blog-mobile-drawer { transform: translateX(0); }
    .blog-mobile-close {
        align-self: flex-end;
        background: var(--toggle-bg);
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: 8px;
        padding: 6px 10px;
        font-size: .78rem;
        cursor: pointer;
        margin-bottom: 12px;
    }
    .blog-mobile-link {
        display: block;
        padding: 11px 14px;
        border-radius: 10px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: .88rem;
        font-weight: 500;
        transition: background .15s, color .15s;
    }
    .blog-mobile-link:hover { background: var(--bg-hover); color: var(--text); }
    .blog-mobile-divider { height: 1px; background: var(--border); margin: 8px 0; }
    </style>

    @stack('styles')
</head>
<body>

{{-- â•â• NAVIGATION â•â• --}}
<div class="blog-nav-wrapper">
<nav class="blog-nav">
    <a href="{{ route('home') }}" class="blog-nav-logo">
        <div class="blog-nav-logo-name">melanogeek</div>
    </a>

    <ul class="blog-nav-links">
        @include('partials.site-nav-links')
    </ul>

    <div class="blog-nav-right">
        <div class="blog-nav-search">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" placeholder="Rechercherâ€¦" id="blogSearchInput">
        </div>

        @guest
            <a href="{{ route('login') }}" class="blog-nav-ghost">Connexion</a>
            <a href="{{ route('register') }}" class="blog-nav-btn">Rejoindre</a>
        @else
            <div class="mg-user-menu" id="mgUserMenu">
                <button class="mg-user-btn" id="mgUserBtn" type="button">
                    <div class="mg-user-avi">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="">
                        @else
                            {{ mb_strtoupper(mb_substr(auth()->user()->username, 0, 1)) }}
                        @endif
                    </div>
                    <span class="mg-user-name">{{ auth()->user()->username }}</span>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" style="opacity:.5;transition:transform .2s;" id="mgUserChevron">
                        <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
                <div class="mg-user-dropdown" id="mgUserDropdown">
                    <a href="{{ route('profile.show', auth()->user()->username) }}" class="mg-drop-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        Mon profil
                    </a>
                    <a href="{{ route('profile.edit') }}" class="mg-drop-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Paramètres
                    </a>
                    @if(auth()->user()->isAdminOrOwner())
                        <a href="{{ route('admin.dashboard') }}" class="mg-drop-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                            Dashboard admin
                        </a>
                    @endif
                    <div class="mg-drop-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mg-drop-item mg-drop-logout">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        @endguest

        <button class="blog-hamburger" id="blogHamburger">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<div class="blog-editorial-bar">
    <div class="blog-editorial-inner">
        <div class="blog-editorial-id">
            <strong>MelanoGeek</strong> · Vol. I · Éd. Printemps {{ date('Y') }} · La culture geek, vue d'Afrique
        </div>
        <ul class="blog-editorial-links">
            <li><a href="{{ route('blog.index') }}?category=manga-anime" class="{{ request('category') === 'manga-anime' ? 'active' : '' }}">Manga</a></li>
            <li><a href="{{ route('blog.index') }}?category=gaming" class="{{ request('category') === 'gaming' ? 'active' : '' }}">Gaming</a></li>
            <li><a href="{{ route('blog.index') }}?category=dev" class="{{ request('category') === 'dev' ? 'active' : '' }}">Développement</a></li>
            <li><a href="{{ route('blog.index') }}?category=tech" class="{{ request('category') === 'tech' ? 'active' : '' }}">Tech &amp; IA</a></li>
            <li><a href="{{ route('blog.index') }}?category=cinema-series" class="{{ request('category') === 'cinema-series' ? 'active' : '' }}">Cinéma</a></li>
            <li><a href="{{ route('forum.index') }}" >Forum</a></li>
        </ul>
    </div>
</div>
</div>

{{-- â•â• CONTENU â•â• --}}

{{-- ── MOBILE NAV DRAWER ── --}}
<div class="blog-mobile-nav" id="blogMobileNav">
    <div class="blog-mobile-overlay" id="blogMobileOverlay"></div>
    <div class="blog-mobile-drawer">
        <button class="blog-mobile-close" id="blogMobileClose">✕ Fermer</button>
        <a href="{{ route('blog.index') }}" class="blog-mobile-link">Blog</a>
        <a href="{{ route('forum.index') }}" class="blog-mobile-link">Forum</a>
        <div class="blog-mobile-divider"></div>
        @guest
            <a href="{{ route('login') }}" class="blog-mobile-link">Connexion</a>
            <a href="{{ route('register') }}" class="blog-mobile-link" style="color:var(--terra);font-weight:700;">Rejoindre</a>
        @else
            <a href="{{ route('profile.show', auth()->user()->username) }}" class="blog-mobile-link">Mon profil</a>
            <a href="{{ route('profile.edit') }}" class="blog-mobile-link">Paramètres</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="blog-mobile-link" style="width:100%;background:none;border:none;text-align:left;cursor:pointer;color:#f87171;">Déconnexion</button>
            </form>
        @endguest
    </div>
</div>

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
                <div class="sidebar-block-head">Catégories</div>
                <div class="sidebar-block-body">
                    @php $activeCategory = request('category'); @endphp
                    <a href="{{ route('blog.index') }}" class="sb-item {{ !$activeCategory ? 'active' : '' }}">
                        <span class="sb-item-icon">✦</span>
                        <span class="sb-item-name">Tout</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=manga-anime" class="sb-item {{ $activeCategory === 'manga-anime' ? 'active' : '' }}">
                        <span class="sb-item-icon">🌍</span>
                        <span class="sb-item-name">Manga &amp; Animé</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=gaming" class="sb-item {{ $activeCategory === 'gaming' ? 'active' : '' }}">
                        <span class="sb-item-icon">🎮</span>
                        <span class="sb-item-name">Gaming</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=tech" class="sb-item {{ $activeCategory === 'tech' ? 'active' : '' }}">
                        <span class="sb-item-icon">💻</span>
                        <span class="sb-item-name">Tech &amp; IA</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=dev" class="sb-item {{ $activeCategory === 'dev' ? 'active' : '' }}">
                        <span class="sb-item-icon">🔧</span>
                        <span class="sb-item-name">Développement</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=cinema-series" class="sb-item {{ $activeCategory === 'cinema-series' ? 'active' : '' }}">
                        <span class="sb-item-icon">🎬</span>
                        <span class="sb-item-name">Cinéma &amp; Séries</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=culture" class="sb-item {{ $activeCategory === 'culture' ? 'active' : '' }}">
                        <span class="sb-item-icon">🌍</span>
                        <span class="sb-item-name">Culture &amp; Société</span>
                    </a>
                    <a href="{{ route('blog.index') }}?category=debat" class="sb-item {{ $activeCategory === 'debat' ? 'active' : '' }}">
                        <span class="sb-item-icon">💬</span>
                        <span class="sb-item-name">Débat</span>
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

{{-- â•â• FOOTER â•â• --}}
<footer class="blog-footer">
    <span>Â© {{ date('Y') }} MelanoGeek â€” La culture geek, vue d'Afrique.</span>
    <ul class="blog-footer-links">
        <li><a href="{{ route('home') }}">Accueil</a></li>
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
        <li><a href="{{ route('forum.index') }}">Forum</a></li>
        <li><a href="{{ route('community') }}">Communauté</a></li>
        <li><a href="{{ route('about') }}">À propos</a></li>
    </ul>
</footer>


@stack('scripts')
<style>
.mg-user-menu{position:relative;}
.mg-user-btn{display:flex;align-items:center;gap:8px;background:var(--bg-card);border:1px solid var(--border);border-radius:100px;padding:5px 12px 5px 5px;color:var(--text);cursor:pointer;transition:border-color .2s;}
.mg-user-btn:hover{border-color:var(--border-hover);}
.mg-user-avi{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--terra),var(--gold));display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:white;flex-shrink:0;overflow:hidden;}
.mg-user-avi img{width:100%;height:100%;object-fit:cover;}
.mg-user-name{font-size:.78rem;font-weight:600;max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.mg-user-dropdown{position:absolute;top:calc(100% + 8px);right:0;background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:6px;min-width:180px;box-shadow:0 8px 32px rgba(0,0,0,.25);opacity:0;visibility:hidden;transform:translateY(-6px);transition:opacity .18s,transform .18s,visibility .18s;z-index:300;}
.mg-user-dropdown.open{opacity:1;visibility:visible;transform:translateY(0);}
.mg-drop-item{display:flex;align-items:center;gap:9px;padding:8px 12px;border-radius:9px;font-size:.82rem;font-weight:500;color:var(--text-muted);text-decoration:none;transition:background .15s,color .15s;width:100%;background:none;border:none;text-align:left;cursor:pointer;font-family:var(--font-body);}
.mg-drop-item:hover{background:var(--bg-hover);color:var(--text);}
.mg-drop-divider{height:1px;background:var(--border);margin:4px 0;}
.mg-drop-logout:hover{color:#f87171 !important;background:rgba(248,113,113,.08) !important;}
</style>
<script>
(function(){
    // User dropdown
    const btn  = document.getElementById('mgUserBtn');
    const drop = document.getElementById('mgUserDropdown');
    const chev = document.getElementById('mgUserChevron');
    if(btn && drop){
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const open = drop.classList.toggle('open');
            if(chev) chev.style.transform = open ? 'rotate(180deg)' : '';
        });
        document.addEventListener('click', () => {
            drop.classList.remove('open');
            if(chev) chev.style.transform = '';
        });
    }

    // Hamburger / mobile drawer
    const ham     = document.getElementById('blogHamburger');
    const mNav    = document.getElementById('blogMobileNav');
    const mClose  = document.getElementById('blogMobileClose');
    const mOver   = document.getElementById('blogMobileOverlay');
    function openMobile(){ mNav.classList.add('open'); ham && ham.classList.add('open'); document.body.style.overflow='hidden'; }
    function closeMobile(){ mNav.classList.remove('open'); ham && ham.classList.remove('open'); document.body.style.overflow=''; }
    if(ham) ham.addEventListener('click', openMobile);
    if(mClose) mClose.addEventListener('click', closeMobile);
    if(mOver)  mOver.addEventListener('click', closeMobile);
})();
</script>
</body>
</html>

