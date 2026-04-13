<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'owner') — melanogeek</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg:          #1a1a1a;
            --sidebar-bg:  #141414;
            --border:      rgba(255,255,255,.08);
            --text:        rgba(255,255,255,.85);
            --text-muted:  rgba(255,255,255,.38);
            --text-faint:  rgba(255,255,255,.18);
            --accent:      #a78bfa;
            --green:       #6ee7b7;
            --red:         #f87171;
            --yellow:      #fcd34d;
            --mono:        'JetBrains Mono', monospace;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--mono);
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
            font-size: 13px;
        }

        /* ════════════════════════════
           SIDEBAR
        ════════════════════════════ */
        .ow-sidebar {
            width: 200px;
            flex-shrink: 0;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
        }

        .ow-sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
        }
        .ow-sidebar-logo {
            font-size: .82rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -.01em;
            text-decoration: none;
            display: block;
            margin-bottom: 4px;
        }
        .ow-sidebar-role {
            font-size: .62rem;
            color: var(--accent);
            letter-spacing: .04em;
        }

        .ow-sidebar-nav {
            flex: 1;
            padding: 20px 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .ow-nav-group { display: flex; flex-direction: column; gap: 2px; }
        .ow-nav-label {
            font-size: .62rem;
            color: var(--text-faint);
            letter-spacing: .08em;
            margin-bottom: 6px;
        }
        .ow-nav-link {
            font-size: .78rem;
            color: var(--text-muted);
            text-decoration: none;
            padding: 4px 0;
            transition: color .12s;
            display: block;
        }
        .ow-nav-link:hover { color: var(--text); }
        .ow-nav-link.active { color: var(--accent); }

        .ow-sidebar-sep {
            height: 1px;
            background: var(--border);
            margin: 0 -20px;
        }

        .ow-sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .ow-footer-user { flex: 1; min-width: 0; }
        .ow-footer-name {
            font-size: .75rem;
            font-weight: 500;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ow-footer-role {
            font-size: .6rem;
            color: var(--accent);
            margin-top: 1px;
        }
        .ow-logout-btn {
            background: none;
            border: none;
            color: var(--text-faint);
            font-size: .8rem;
            cursor: pointer;
            font-family: var(--mono);
            padding: 2px 4px;
            transition: color .12s;
        }
        .ow-logout-btn:hover { color: var(--red); }

        /* ════════════════════════════
           MAIN
        ════════════════════════════ */
        .ow-main {
            flex: 1;
            margin-left: 200px;
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
            background: var(--sidebar-bg);
        }
        .ow-breadcrumb {
            font-size: .72rem;
            color: var(--text-faint);
        }
        .ow-breadcrumb span { color: var(--text-muted); }
        .ow-topbar-actions { display: flex; gap: 16px; align-items: center; }
        .ow-topbar-link {
            font-size: .7rem;
            color: var(--text-faint);
            text-decoration: none;
            transition: color .12s;
        }
        .ow-topbar-link:hover { color: var(--text-muted); }

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
            font-family: var(--mono);
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
            font-family: var(--mono); font-size: .75rem;
            outline: none; transition: border-color .15s;
        }
        .field input:focus, .field select:focus, .field textarea:focus {
            border-color: rgba(167,139,250,.40);
        }
        .field textarea { resize: vertical; min-height: 80px; }
        .btn-save {
            background: rgba(167,139,250,.12);
            border: 1px solid rgba(167,139,250,.25);
            color: var(--accent);
            padding: 8px 20px; border-radius: 6px;
            font-family: var(--mono); font-size: .72rem; font-weight: 500;
            cursor: pointer; transition: background .15s;
        }
        .btn-save:hover { background: rgba(167,139,250,.20); }

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
        .toggle-switch input:checked + .toggle-slider { background: rgba(167,139,250,.20); border-color: rgba(167,139,250,.35); }
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
        .pagination-wrap .pagination li.active span { border-color: rgba(167,139,250,.30); color: var(--accent); }
        .pagination-wrap .pagination li a:hover { border-color: rgba(255,255,255,.15); color: var(--text); }

        /* AVATAR MINI */
        .user-avi {
            width: 26px; height: 26px; border-radius: 5px;
            background: rgba(167,139,250,.12); border: 1px solid rgba(167,139,250,.20);
            display: flex; align-items: center; justify-content: center;
            font-size: .65rem; font-weight: 600; color: var(--accent);
            flex-shrink: 0; overflow: hidden;
        }
        .user-avi img { width: 100%; height: 100%; object-fit: cover; }

        /* SEARCH */
        .admin-search {
            background: var(--sidebar-bg); border: 1px solid var(--border);
            border-radius: 6px; padding: 7px 11px;
            color: var(--text); font-family: var(--mono); font-size: .72rem;
            outline: none; transition: border-color .15s;
        }
        .admin-search:focus { border-color: rgba(167,139,250,.35); }

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
        <span class="ow-sidebar-role">// owner</span>
    </div>

    <nav class="ow-sidebar-nav">
        <div class="ow-nav-group">
            <div class="ow-nav-label">// owner</div>
            <a href="{{ route('owner.dashboard') }}" class="ow-nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">overview</a>
            <a href="{{ route('owner.staff') }}"     class="ow-nav-link {{ request()->routeIs('owner.staff') ? 'active' : '' }}">staff</a>
            <a href="{{ route('owner.settings') }}"  class="ow-nav-link {{ request()->routeIs('owner.settings') ? 'active' : '' }}">settings</a>
            <a href="{{ route('owner.logs') }}"      class="ow-nav-link {{ request()->routeIs('owner.logs') ? 'active' : '' }}">logs</a>
        </div>

        <div class="ow-sidebar-sep"></div>

        <div class="ow-nav-group">
            <div class="ow-nav-label">// moderation</div>
            <a href="{{ route('admin.users') }}" class="ow-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">users</a>
            <a href="{{ route('admin.posts') }}" class="ow-nav-link {{ request()->routeIs('admin.posts*') ? 'active' : '' }}">posts</a>
            <a href="{{ route('admin.dashboard') }}" class="ow-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">admin panel</a>
        </div>

        <div class="ow-sidebar-sep"></div>

        <div class="ow-nav-group">
            <a href="{{ route('home') }}" class="ow-nav-link" target="_blank">↗ view site</a>
        </div>
    </nav>

    <div class="ow-sidebar-footer">
        <div class="ow-footer-user">
            <div class="ow-footer-name">{{ auth()->user()->username ?? auth()->user()->name }}</div>
            <div class="ow-footer-role">owner</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="ow-logout-btn" title="Logout">⏻</button>
        </form>
    </div>
</aside>

<!-- ══ MAIN ══ -->
<div class="ow-main">
    <div class="ow-topbar">
        <button class="ow-mob-toggle" id="owMobToggle">☰</button>
        <div class="ow-breadcrumb">melanogeek / owner / <span>@yield('page-title', 'overview')</span></div>
        <div class="ow-topbar-actions">
            <a href="{{ route('home') }}" class="ow-topbar-link" target="_blank">↗ site</a>
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
