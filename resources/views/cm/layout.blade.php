<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CM') — MelanoGeek Community</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <script>(function(){ document.documentElement.setAttribute('data-theme', localStorage.getItem('mg-theme') || 'dark'); })();</script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Variables globales */
        [data-theme="dark"]  { --bg:#080C0B;--bg-card:#0D1411;--bg-card2:#131C1A;--bg-hover:#182320;--text:#E2F0ED;--text-muted:rgba(226,240,237,.52);--text-faint:rgba(226,240,237,.22);--border:rgba(255,255,255,.07);--border-hover:rgba(255,255,255,.14);--shadow-md:0 8px 32px rgba(0,0,0,.5); }
        [data-theme="light"] { --bg:#F2F7F6;--bg-card:#FFF;--bg-card2:#E8F0EE;--bg-hover:#E0EEEB;--text:#0C1E1A;--text-muted:rgba(12,30,26,.52);--text-faint:rgba(12,30,26,.22);--border:rgba(12,30,26,.09);--border-hover:rgba(12,30,26,.18);--shadow-md:0 8px 32px rgba(0,0,0,.08); }
        :root {
            --cm:#2DB8A0;
            --cm-soft:rgba(45,184,160,.12);
            --cm-border:rgba(45,184,160,.25);
            --cm-glow:rgba(45,184,160,.15);
            --terra:#C8522A;--terra-soft:rgba(200,82,42,.12);
            --gold:#D4A843;--gold-soft:rgba(212,168,67,.12);
            --font-head:'Plus Jakarta Sans',sans-serif;
            --font-body:'Outfit',sans-serif;
        }
        *,*::before,*::after { margin:0;padding:0;box-sizing:border-box; }
        body { background:var(--bg);color:var(--text);font-family:var(--font-body);-webkit-font-smoothing:antialiased;display:flex;min-height:100vh; }

        /* ── SIDEBAR ── */
        .cm-sidebar {
            width: 240px; flex-shrink: 0;
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top:0; left:0; bottom:0;
            z-index: 50;
            transition: background .4s, border-color .4s, transform .25s ease;
        }

        /* Accent line at top */
        .cm-sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--cm), transparent);
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand-name {
            font-family: var(--font-head); font-size: 1rem; font-weight: 800;
        }
        .sidebar-brand-name span { color: var(--cm); }
        .sidebar-badge {
            font-size: .6rem; font-weight: 700; letter-spacing: .06em;
            text-transform: uppercase;
            background: var(--cm); color: #040E0C;
            padding: 2px 7px; border-radius: 100px;
        }

        .sidebar-nav { flex: 1; padding: 12px 10px; overflow-y: auto; }
        .sidebar-section {
            font-size: .63rem; font-weight: 700; letter-spacing: .09em;
            text-transform: uppercase; color: var(--text-faint);
            padding: 12px 10px 6px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 10px;
            color: var(--text-muted); text-decoration: none;
            font-size: .84rem; font-weight: 500;
            transition: all .2s; margin-bottom: 2px;
        }
        .sidebar-link:hover { background: var(--bg-hover); color: var(--text); }
        .sidebar-link.active { background: var(--cm-soft); color: var(--cm); font-weight: 600; }
        .sidebar-link .icon { width: 20px; text-align: center; font-size: .9rem; flex-shrink: 0; }
        .sidebar-link .badge-count {
            margin-left: auto;
            background: #E05555; color: white;
            font-size: .6rem; font-weight: 700;
            padding: 2px 6px; border-radius: 100px;
        }

        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-user-name { font-size: .82rem; font-weight: 600; }
        .sidebar-user-role { font-size: .7rem; color: var(--cm); font-weight: 700; }

        /* ── MAIN ── */
        .cm-main {
            flex: 1;
            margin-left: 240px;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .cm-topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
            backdrop-filter: blur(12px);
        }
        .cm-page-title {
            font-family: var(--font-head);
            font-size: 1rem; font-weight: 700;
        }
        .cm-topbar-right { display: flex; gap: 10px; align-items: center; }
        .topbar-btn {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-muted); padding: 6px 14px;
            border-radius: 100px; font-family: var(--font-body);
            font-size: .78rem; font-weight: 500;
            text-decoration: none; transition: all .2s;
        }
        .topbar-btn:hover { border-color: var(--border-hover); color: var(--text); }

        .cm-content { padding: 28px; flex: 1; }

        /* ── FLASH ── */
        .cm-flash {
            padding: 12px 16px; border-radius: 12px; margin-bottom: 20px;
            font-size: .84rem; font-weight: 500;
            display: flex; align-items: center; gap: 8px;
        }
        .cm-flash.success { background:rgba(45,184,160,.1);border:1px solid rgba(45,184,160,.25);color:var(--cm); }
        .cm-flash.error   { background:rgba(200,82,42,.1);border:1px solid rgba(200,82,42,.25);color:var(--terra); }

        /* ── STAT CARDS ── */
        .stat-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-bottom:28px; }
        .stat-card {
            background:var(--bg-card);border:1px solid var(--border);
            border-radius:16px;padding:20px;
            transition:background .4s,border-color .4s,transform .2s,box-shadow .2s;
            position:relative;overflow:hidden;
        }
        .stat-card::after {
            content:'';position:absolute;top:0;left:0;right:0;height:2px;
            background:linear-gradient(90deg,transparent,var(--cm),transparent);
            opacity:0;transition:opacity .3s;
        }
        .stat-card:hover { transform:translateY(-2px);box-shadow:0 8px 28px var(--cm-glow); }
        .stat-card:hover::after { opacity:1; }
        .stat-card-label { font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px; }
        .stat-card-value { font-family:var(--font-head);font-size:2rem;font-weight:800;letter-spacing:-.03em;color:var(--text); }
        .stat-card-sub { font-size:.74rem;color:var(--text-faint);margin-top:4px; }
        .stat-card.accent { border-color:var(--cm-border);background:var(--cm-soft); }
        .stat-card.accent .stat-card-value { color:var(--cm); }

        /* ── TABLE ── */
        .cm-table-wrap { background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden; }
        .cm-table-header { padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap; }
        .cm-table-title { font-family:var(--font-head);font-size:.92rem;font-weight:700; }
        table { width:100%;border-collapse:collapse; }
        th { padding:11px 16px;text-align:left;font-size:.7rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border); }
        td { padding:12px 16px;font-size:.84rem;border-bottom:1px solid var(--border);vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:var(--bg-hover); }

        /* ── BADGES ── */
        .badge { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:100px;font-size:.7rem;font-weight:700;letter-spacing:.03em; }
        .badge-cm     { background:var(--cm-soft);color:var(--cm);border:1px solid var(--cm-border); }
        .badge-green  { background:rgba(45,184,160,.1);color:#2DB8A0;border:1px solid rgba(45,184,160,.2); }
        .badge-red    { background:rgba(224,85,85,.1);color:#E05555;border:1px solid rgba(224,85,85,.2); }
        .badge-gold   { background:var(--gold-soft);color:var(--gold);border:1px solid rgba(212,168,67,.25); }
        .badge-terra  { background:var(--terra-soft);color:var(--terra);border:1px solid rgba(200,82,42,.2); }
        .badge-gray   { background:var(--bg-card2);color:var(--text-muted);border:1px solid var(--border); }

        /* ── ACTIONS ── */
        .action-row { display:flex;gap:6px;align-items:center;flex-wrap:wrap; }
        .btn-action {
            padding:5px 12px;border-radius:8px;font-size:.75rem;font-weight:600;
            border:1px solid var(--border);background:transparent;color:var(--text-muted);
            transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;gap:4px;
            cursor:pointer;
        }
        .btn-action:hover { border-color:var(--border-hover);color:var(--text); }
        .btn-action.danger { border-color:rgba(224,85,85,.3);color:#E05555; }
        .btn-action.danger:hover { background:rgba(224,85,85,.08);border-color:#E05555; }
        .btn-action.success { border-color:var(--cm-border);color:var(--cm); }
        .btn-action.success:hover { background:var(--cm-soft); }
        .btn-action.warn { border-color:rgba(212,168,67,.3);color:var(--gold); }
        .btn-action.warn:hover { background:var(--gold-soft); }

        /* ── SEARCH / FILTER ── */
        .cm-search { background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:8px 14px;color:var(--text);font-family:var(--font-body);font-size:.84rem;outline:none;transition:border-color .2s; }
        .cm-search:focus { border-color:var(--cm); }
        .cm-search::placeholder { color:var(--text-faint); }
        .cm-select { background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:8px 14px;color:var(--text);font-family:var(--font-body);font-size:.84rem;outline:none;transition:border-color .2s;cursor:pointer; }
        .cm-select:focus { border-color:var(--cm); }

        /* ── AVATAR MINI ── */
        .user-avatar-mini { width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--cm),var(--gold));padding:1.5px;flex-shrink:0;overflow:hidden; }
        .user-avatar-mini-inner { width:100%;height:100%;border-radius:50%;background:var(--bg-card2);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;overflow:hidden; }
        .user-avatar-mini-inner img { width:100%;height:100%;object-fit:cover;border-radius:50%; }

        /* ── PAGINATION ── */
        .pagination-wrap { padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:center; }
        .pagination-wrap .pagination { display:flex;gap:6px;list-style:none; }
        .pagination-wrap .pagination li a,
        .pagination-wrap .pagination li span { display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 8px;border-radius:8px;font-size:.8rem;background:var(--bg-card2);border:1px solid var(--border);color:var(--text-muted);text-decoration:none;transition:all .2s; }
        .pagination-wrap .pagination li.active span { background:var(--cm);border-color:var(--cm);color:#040E0C;font-weight:700; }
        .pagination-wrap .pagination li a:hover { border-color:var(--cm);color:var(--cm); }

        /* ── MOBILE HAMBURGER ── */
        .cm-mob-toggle {
            display:none;align-items:center;justify-content:center;
            width:36px;height:36px;background:var(--bg-card2);
            border:1px solid var(--border);border-radius:8px;
            cursor:pointer;color:var(--text-muted);font-size:1.1rem;flex-shrink:0;
        }

        /* ── OVERLAY ── */
        .cm-sidebar-overlay {
            display:none;position:fixed;inset:0;
            background:rgba(0,0,0,.55);z-index:49;backdrop-filter:blur(2px);
        }
        .cm-sidebar-overlay.open { display:block; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            body { display:block; }
            .cm-sidebar { transform:translateX(-100%);z-index:50;width:260px; }
            .cm-sidebar.open { transform:translateX(0); }
            .cm-main { margin-left:0; }
            .cm-topbar { padding:12px 16px;gap:8px; }
            .cm-page-title { font-size:.88rem; }
            .cm-topbar-right { gap:6px; }
            .topbar-btn { padding:5px 10px;font-size:.72rem; }
            .cm-content { padding:16px; }
            .stat-grid { grid-template-columns:repeat(2,1fr);gap:10px; }
            .cm-table-wrap { overflow-x:auto; }
            .cm-mob-toggle { display:flex; }
        }
        @media (max-width: 480px) {
            .stat-grid { grid-template-columns:1fr 1fr; }
            .stat-card { padding:14px; }
            .stat-card-value { font-size:1.5rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ── OVERLAY MOBILE ── -->
    <div class="cm-sidebar-overlay" id="cmOverlay"></div>

    <!-- ── SIDEBAR ── -->
    <aside class="cm-sidebar">
        <div class="sidebar-brand">
            <svg width="28" height="28" viewBox="0 0 42 42" fill="none">
                <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="var(--bg-card2)" stroke="#2DB8A0" stroke-width="0.8"/>
                <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#2DB8A0" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
            <div>
                <div class="sidebar-brand-name">Melano<span>Geek</span></div>
                <span class="sidebar-badge">CM Panel</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section">Espace CM</div>
            <a href="{{ route('cm.dashboard') }}" class="sidebar-link {{ request()->routeIs('cm.dashboard') ? 'active' : '' }}">
                <span class="icon">🛡️</span> Dashboard
            </a>

            <div class="sidebar-section">Modération</div>
            <a href="{{ route('cm.reports') }}" class="sidebar-link {{ request()->routeIs('cm.reports') ? 'active' : '' }}">
                <span class="icon">🚩</span> Signalements
                @php $pendingReports = \App\Models\Report::where('status','pending')->count(); @endphp
                @if($pendingReports > 0)
                    <span class="badge-count">{{ $pendingReports }}</span>
                @endif
            </a>
            <a href="{{ route('cm.posts') }}" class="sidebar-link {{ request()->routeIs('cm.posts') ? 'active' : '' }}">
                <span class="icon">📝</span> Publications
            </a>
            <a href="{{ route('cm.comments') }}" class="sidebar-link {{ request()->routeIs('cm.comments') ? 'active' : '' }}">
                <span class="icon">💬</span> Commentaires
            </a>

            <div class="sidebar-section">Site</div>
            <a href="{{ route('cm.homepage') }}" class="sidebar-link {{ request()->routeIs('cm.homepage') ? 'active' : '' }}">
                <span class="icon">🏠</span> Page d'accueil
            </a>
            <a href="{{ route('cm.about') }}" class="sidebar-link {{ request()->routeIs('cm.about') ? 'active' : '' }}">
                <span class="icon">📖</span> À propos
            </a>
            <a href="{{ route('cm.niches') }}" class="sidebar-link {{ request()->routeIs('cm.niches') ? 'active' : '' }}">
                <span class="icon">🏷️</span> Niches
            </a>
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                <span class="icon">🌐</span> Voir le site
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-avatar-mini">
                <div class="user-avatar-mini-inner">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="">
                    @else
                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
            </div>
            <div style="flex:1;min-width:0;">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">✦ Community Manager</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--text-faint);font-size:.85rem;cursor:pointer;" title="Déconnexion">⏻</button>
            </form>
        </div>
    </aside>

    <!-- ── MAIN ── -->
    <div class="cm-main">
        <div class="cm-topbar">
            <button class="cm-mob-toggle" id="cmMobToggle" aria-label="Menu">☰</button>
            <div class="cm-page-title">@yield('page-title', 'Dashboard')</div>
            <div class="cm-topbar-right">
                <button id="cmThemeBtn" style="background:transparent;border:1px solid var(--border);color:var(--text-muted);width:34px;height:34px;border-radius:8px;cursor:pointer;font-size:.9rem;display:flex;align-items:center;justify-content:center;transition:all .2s;flex-shrink:0;" title="Thème">🌙</button>
                <a href="{{ route('home') }}" class="topbar-btn">← Site</a>
            </div>
        </div>

        <div class="cm-content">
            @if(session('success'))
                <div class="cm-flash success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="cm-flash error">✗ {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        // Sync thème
        const html = document.documentElement;
        const saved = localStorage.getItem('mg-theme') || 'dark';
        html.setAttribute('data-theme', saved);

        (function () {
            const btn = document.getElementById('cmThemeBtn');
            if (!btn) return;
            btn.textContent = saved === 'dark' ? '☀️' : '🌙';
            btn.addEventListener('click', function () {
                const current = document.documentElement.getAttribute('data-theme');
                const next = current === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', next);
                localStorage.setItem('mg-theme', next);
                btn.textContent = next === 'dark' ? '☀️' : '🌙';
            });
        })();

        (function () {
            const toggle  = document.getElementById('cmMobToggle');
            const sidebar = document.querySelector('.cm-sidebar');
            const overlay = document.getElementById('cmOverlay');
            if (!toggle || !sidebar || !overlay) return;

            function open()  { sidebar.classList.add('open'); overlay.classList.add('open'); document.body.style.overflow='hidden'; }
            function close() { sidebar.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow=''; }

            toggle.addEventListener('click', open);
            overlay.addEventListener('click', close);
            sidebar.querySelectorAll('.sidebar-link').forEach(l => l.addEventListener('click', () => { if (window.innerWidth <= 768) close(); }));
            window.addEventListener('resize', () => { if (window.innerWidth > 768) close(); });
        })();
    </script>
    @stack('scripts')
</body>
</html>
