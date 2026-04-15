<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'owner') — melanogeek</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg:          #1a1a1a;
            --sidebar-bg:  #1f1f1f;
            --panel-bg:    rgba(255,255,255,.025);
            --border:      rgba(255,255,255,.08);
            --text:        rgba(245,239,227,.9);
            --text-muted:  rgba(245,239,227,.56);
            --text-faint:  rgba(245,239,227,.26);
            --accent:      #D4A843;
            --terra:       #C8522A;
            --green:       #6ee7b7;
            --red:         #f87171;
            --yellow:      #fcd34d;
            --font-head:   'Bricolage Grotesque', sans-serif;
            --font-body:   'Outfit', sans-serif;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(212,168,67,.06), transparent 26%),
                radial-gradient(circle at 82% 14%, rgba(255,255,255,.025), transparent 22%),
                var(--bg);
            color: var(--text);
            font-family: var(--font-body);
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
            font-size: 14px;
        }

        /* ════════════════════════════
           SIDEBAR
        ════════════════════════════ */
        .ow-sidebar {
            width: 240px;
            flex-shrink: 0;
            background:
                linear-gradient(180deg, rgba(255,255,255,.04), transparent 20%),
                linear-gradient(180deg, #1f1f1f, #1a1a1a);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            overflow: hidden;
            z-index: 50;
        }

        .ow-sidebar-brand {
            padding: 24px 22px 18px;
            border-bottom: 1px solid var(--border);
        }
        .ow-sidebar-logo {
            font-family: var(--font-head);
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -.04em;
            text-decoration: none;
            display: block;
            margin-bottom: 6px;
        }
        .ow-sidebar-role {
            font-size: .7rem;
            color: var(--accent);
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .ow-sidebar-nav {
            flex: 1;
            padding: 22px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .ow-nav-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .ow-nav-label {
            font-size: .68rem;
            color: var(--text-faint);
            letter-spacing: .12em;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .ow-nav-link {
            font-size: .9rem;
            color: var(--text-muted);
            text-decoration: none;
            padding: 12px 14px;
            transition: color .12s, background .12s, border-color .12s, transform .12s;
            display: block;
            border: 1px solid transparent;
            border-radius: 14px;
            background: transparent;
        }
        .ow-nav-link:hover {
            color: var(--text);
            background: rgba(255,255,255,.035);
            border-color: rgba(255,255,255,.06);
            transform: translateX(2px);
        }
        .ow-nav-link.active {
            color: #f7efdf;
            background: linear-gradient(135deg, rgba(212,168,67,.14), rgba(200,82,42,.08));
            border-color: rgba(212,168,67,.22);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.05);
        }

        .ow-sidebar-sep {
            height: 1px;
            background: var(--border);
            margin: 2px 0;
        }

        .ow-sidebar-footer {
            padding: 18px 22px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .ow-footer-user { flex: 1; min-width: 0; }
        .ow-footer-name {
            font-size: .88rem;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ow-footer-role {
            font-size: .68rem;
            color: var(--text-faint);
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: .1em;
        }
        .ow-logout-btn {
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.06);
            color: var(--text-muted);
            font-size: .9rem;
            cursor: pointer;
            font-family: var(--font-body);
            width: 38px;
            height: 38px;
            border-radius: 12px;
            transition: color .12s, border-color .12s, background .12s;
        }
        .ow-logout-btn:hover {
            color: var(--red);
            border-color: rgba(248,113,113,.16);
            background: rgba(248,113,113,.06);
        }

        /* ════════════════════════════
           MAIN
        ════════════════════════════ */
        .ow-main {
            flex: 1;
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* TOPBAR */
        .ow-topbar {
            height: 48px;
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(26,26,26,.92);
            backdrop-filter: blur(14px);
        }
        .ow-breadcrumb {
            font-size: .8rem;
            color: var(--text-faint);
        }
        .ow-breadcrumb span {
            color: var(--text);
            font-weight: 600;
        }
        .ow-topbar-actions { display: flex; gap: 16px; align-items: center; }
        .ow-topbar-link {
            font-size: .76rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color .12s;
        }
        .ow-topbar-link:hover { color: var(--text); }

        /* CONTENT */
        .ow-content {
            padding: 32px 28px;
            flex: 1;
        }

        /* FLASH */
        .ow-flash {
            font-size: .72rem;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .ow-flash.success { background: rgba(110,231,183,.06); border: 1px solid rgba(110,231,183,.15); color: var(--green); }
        .ow-flash.error   { background: rgba(248,113,113,.06); border: 1px solid rgba(248,113,113,.15); color: var(--red); }

        /* ════════════════════════════
           SHARED COMPONENTS
           (tables, badges, etc.)
        ════════════════════════════ */
        .ow-table-wrap {
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .ow-table-head {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .ow-table-title { font-size: .72rem; font-weight: 600; color: var(--text); }
        .ow-table-action { font-size: .65rem; color: var(--accent); text-decoration: none; }
        .ow-table-action:hover { text-decoration: underline; }

        table { width: 100%; border-collapse: collapse; }
        th {
            padding: 10px 18px;
            text-align: left;
            font-size: .6rem;
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-faint);
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 11px 18px;
            font-size: .72rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-muted);
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { color: var(--text); }

        .badge {
            display: inline-flex; align-items: center;
            padding: 2px 8px; border-radius: 4px;
            font-size: .6rem; font-weight: 500; letter-spacing: .04em;
        }
        .badge-owner  { background: rgba(167,139,250,.10); color: var(--accent); border: 1px solid rgba(167,139,250,.20); }
        .badge-admin  { background: rgba(212,168,67,.08);  color: #D4A843;       border: 1px solid rgba(212,168,67,.18); }
        .badge-green  { background: rgba(110,231,183,.08); color: var(--green);  border: 1px solid rgba(110,231,183,.18); }
        .badge-red    { background: rgba(248,113,113,.08); color: var(--red);    border: 1px solid rgba(248,113,113,.18); }
        .badge-gray   { background: rgba(255,255,255,.04); color: var(--text-faint); border: 1px solid var(--border); }

        .action-row { display: flex; gap: 8px; align-items: center; }
        .btn-action {
            padding: 4px 10px; border-radius: 5px;
            font-size: .65rem; font-weight: 500;
            border: 1px solid var(--border);
            background: transparent; color: var(--text-faint);
            font-family: var(--font-body);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
            transition: border-color .12s, color .12s;
        }
        .btn-action:hover { border-color: rgba(255,255,255,.18); color: var(--text); }
        .btn-action.danger { border-color: rgba(248,113,113,.20); color: var(--red); }
        .btn-action.danger:hover { background: rgba(248,113,113,.06); }
        .btn-action.accent { border-color: rgba(167,139,250,.25); color: var(--accent); }
        .btn-action.accent:hover { background: rgba(167,139,250,.06); }

        /* FORM */
        .field { margin-bottom: 16px; }
        .field label {
            display: block; font-size: .62rem; font-weight: 500;
            letter-spacing: .07em; text-transform: uppercase;
            color: var(--text-faint); margin-bottom: 7px;
        }
        .field input, .field select, .field textarea {
            width: 100%; background: var(--sidebar-bg);
            border: 1px solid var(--border); border-radius: 6px;
            padding: 9px 12px; color: var(--text);
            font-family: var(--font-body); font-size: .85rem;
            outline: none; transition: border-color .15s;
        }
        .field input:focus, .field select:focus, .field textarea:focus {
            border-color: rgba(167,139,250,.40);
        }
        .field textarea { resize: vertical; min-height: 80px; }
        .btn-save {
            background: rgba(212,168,67,.12);
            border: 1px solid rgba(212,168,67,.25);
            color: var(--accent);
            padding: 8px 20px; border-radius: 6px;
            font-family: var(--font-body); font-size: .78rem; font-weight: 600;
            cursor: pointer; transition: background .15s;
        }
        .btn-save:hover { background: rgba(212,168,67,.20); }

        /* TOGGLE */
        .toggle-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 0; border-bottom: 1px solid var(--border);
        }
        .toggle-row:last-child { border-bottom: none; }
        .toggle-label { font-size: .75rem; color: var(--text-muted); }
        .toggle-label small { display: block; font-size: .65rem; color: var(--text-faint); margin-top: 2px; }
        .toggle-switch { position: relative; display: inline-block; width: 36px; height: 20px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer; inset: 0;
            background: rgba(255,255,255,.08); border: 1px solid var(--border);
            border-radius: 100px; transition: background .2s;
        }
        .toggle-slider::before {
            content: ''; position: absolute;
            width: 12px; height: 12px; border-radius: 50%;
            background: var(--text-faint); left: 3px; top: 50%;
            transform: translateY(-50%); transition: transform .2s, background .2s;
        }
        .toggle-switch input:checked + .toggle-slider { background: rgba(212,168,67,.20); border-color: rgba(212,168,67,.35); }
        .toggle-switch input:checked + .toggle-slider::before { transform: translate(16px, -50%); background: var(--accent); }

        /* PAGINATION */
        .pagination-wrap { padding: 14px 18px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; }
        .pagination-wrap .pagination { display: flex; gap: 4px; list-style: none; }
        .pagination-wrap .pagination li a,
        .pagination-wrap .pagination li span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 28px; height: 28px; padding: 0 6px;
            border-radius: 5px; font-size: .65rem;
            border: 1px solid var(--border);
            color: var(--text-faint); text-decoration: none;
            transition: border-color .12s, color .12s;
        }
        .pagination-wrap .pagination li.active span { border-color: rgba(212,168,67,.30); color: var(--accent); }
        .pagination-wrap .pagination li a:hover { border-color: rgba(255,255,255,.15); color: var(--text); }

        /* AVATAR MINI */
        .user-avi {
            width: 26px; height: 26px; border-radius: 5px;
            background: rgba(212,168,67,.12); border: 1px solid rgba(212,168,67,.20);
            display: flex; align-items: center; justify-content: center;
            font-size: .65rem; font-weight: 600; color: var(--accent);
            flex-shrink: 0; overflow: hidden;
        }
        .user-avi img { width: 100%; height: 100%; object-fit: cover; }

        /* SEARCH */
        .admin-search {
            background: var(--sidebar-bg); border: 1px solid var(--border);
            border-radius: 6px; padding: 7px 11px;
            color: var(--text); font-family: var(--font-body); font-size: .8rem;
            outline: none; transition: border-color .15s;
        }
        .admin-search:focus { border-color: rgba(212,168,67,.35); }

        /* MOBILE */
        .ow-mob-toggle {
            display: none; align-items: center; justify-content: center;
            width: 32px; height: 32px; background: transparent;
            border: 1px solid var(--border); border-radius: 5px;
            cursor: pointer; color: var(--text-muted); font-size: 1rem;
            flex-shrink: 0;
        }
        .ow-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.6); z-index: 49;
        }
        .ow-overlay.open { display: block; }

        @media (max-width: 768px) {
            .ow-sidebar { transform: translateX(-100%); transition: transform .22s ease; }
            .ow-sidebar.open { transform: translateX(0); }
            .ow-main { margin-left: 0; }
            .ow-mob-toggle { display: flex; }
            .ow-content { padding: 20px 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="ow-overlay" id="owOverlay"></div>

<!-- ══ SIDEBAR ══ -->
<aside class="ow-sidebar">
    <div class="ow-sidebar-brand">
        <a href="{{ route('owner.dashboard') }}" class="ow-sidebar-logo">melanogeek</a>
        <span class="ow-sidebar-role">Direction</span>
    </div>

    <nav class="ow-sidebar-nav">
        <div class="ow-nav-group">
            <div class="ow-nav-label">Pilotage</div>
            <a href="{{ route('owner.dashboard') }}" class="ow-nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">Vue d’ensemble</a>
            <a href="{{ route('owner.staff') }}"     class="ow-nav-link {{ request()->routeIs('owner.staff') ? 'active' : '' }}">Équipe</a>
            <a href="{{ route('owner.settings') }}"  class="ow-nav-link {{ request()->routeIs('owner.settings') ? 'active' : '' }}">Réglages</a>
            <a href="{{ route('owner.logs') }}"      class="ow-nav-link {{ request()->routeIs('owner.logs') ? 'active' : '' }}">Journal</a>
        </div>

        <div class="ow-sidebar-sep"></div>

        <div class="ow-nav-group">
            <div class="ow-nav-label">Administration</div>
            <a href="{{ route('admin.users') }}" class="ow-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">Utilisateurs</a>
            <a href="{{ route('admin.posts') }}" class="ow-nav-link {{ request()->routeIs('admin.posts*') ? 'active' : '' }}">Publications</a>
            <a href="{{ route('admin.dashboard') }}" class="ow-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard admin</a>
        </div>

        <div class="ow-sidebar-sep"></div>

        <div class="ow-nav-group">
            <div class="ow-nav-label">Accès rapide</div>
            <a href="{{ route('home') }}" class="ow-nav-link" target="_blank">↗ Voir le site</a>
        </div>
    </nav>

    <div class="ow-sidebar-footer">
        <div class="ow-footer-user">
            <div class="ow-footer-name">{{ auth()->user()->username ?? auth()->user()->name }}</div>
            <div class="ow-footer-role">Propriétaire</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="ow-logout-btn" title="Déconnexion">⏻</button>
        </form>
    </div>
</aside>

<!-- ══ MAIN ══ -->
<div class="ow-main">
    <div class="ow-topbar">
        <button class="ow-mob-toggle" id="owMobToggle">☰</button>
        <div class="ow-breadcrumb">MelanoGeek / Direction / <span>@yield('page-title', 'Vue d’ensemble')</span></div>
        <div class="ow-topbar-actions">
            <a href="{{ route('home') }}" class="ow-topbar-link" target="_blank">↗ Ouvrir le site</a>
        </div>
    </div>

    <div class="ow-content">
        @if(session('success'))
            <div class="ow-flash success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="ow-flash error">✗ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
(function () {
    const toggle  = document.getElementById('owMobToggle');
    const sidebar = document.querySelector('.ow-sidebar');
    const overlay = document.getElementById('owOverlay');
    if (!toggle) return;
    function open()  { sidebar.classList.add('open');  overlay.classList.add('open');  document.body.style.overflow = 'hidden'; }
    function close() { sidebar.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow = ''; }
    toggle.addEventListener('click', open);
    overlay.addEventListener('click', close);
    sidebar.querySelectorAll('.ow-nav-link').forEach(l => l.addEventListener('click', () => { if (window.innerWidth <= 768) close(); }));
    window.addEventListener('resize', () => { if (window.innerWidth > 768) close(); });
})();
</script>
@stack('scripts')
</body>
</html>
