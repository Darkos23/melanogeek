<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — MelanoGeek {{ auth()->user()?->isOwner() ? 'Owner' : 'Admin' }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <script>(function(){ document.documentElement.setAttribute('data-theme', localStorage.getItem('mg-theme') || 'dark'); })();</script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Variables */
        [data-theme="dark"]  { --bg:#0A0705;--bg-card:#111009;--bg-card2:#181210;--bg-hover:#1E1810;--text:#F0E8DE;--text-muted:rgba(240,232,222,.55);--text-faint:rgba(240,232,222,.25);--border:rgba(255,255,255,.07);--border-hover:rgba(255,255,255,.14);--nav-bg:rgba(10,7,5,.9);--shadow-md:0 8px 32px rgba(0,0,0,.5); }
        [data-theme="light"] { --bg:#F7F3EE;--bg-card:#FFF;--bg-card2:#EDE8E0;--bg-hover:#F0EAE2;--text:#1C1208;--text-muted:rgba(28,18,8,.52);--text-faint:rgba(28,18,8,.22);--border:rgba(28,18,8,.09);--border-hover:rgba(28,18,8,.18);--nav-bg:rgba(247,243,238,.95);--shadow-md:0 8px 32px rgba(0,0,0,.08); }
        :root { --terra:#C8522A;--terra-soft:rgba(200,82,42,.12);--gold:#D4A843;--gold-soft:rgba(212,168,67,.12);--accent:#E8732A;--font-head:'Plus Jakarta Sans',sans-serif;--font-body:'Outfit',sans-serif; }
        *,*::before,*::after { margin:0;padding:0;box-sizing:border-box; }
        body { background:var(--bg);color:var(--text);font-family:var(--font-body);-webkit-font-smoothing:antialiased;display:flex;min-height:100vh; }

        /* ── SIDEBAR ── */
        .admin-sidebar {
            width: 240px; flex-shrink: 0;
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top:0; left:0; bottom:0;
            z-index: 50;
            transition: background .4s, border-color .4s;
        }
        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand-name {
            font-family: var(--font-head); font-size: 1rem; font-weight: 800;
        }
        .sidebar-brand-name span { color: var(--gold); }
        .sidebar-badge {
            font-size: .6rem; font-weight: 700; letter-spacing: .06em;
            text-transform: uppercase;
            background: var(--terra); color: white;
            padding: 2px 7px; border-radius: 100px;
        }
        .sidebar-nav { flex: 1; padding: 12px 10px; overflow-y: auto; }
        .sidebar-section { font-size: .65rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--text-faint); padding: 12px 10px 6px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 10px;
            color: var(--text-muted); text-decoration: none;
            font-size: .84rem; font-weight: 500;
            transition: all .2s; margin-bottom: 2px;
        }
        .sidebar-link:hover { background: var(--bg-hover); color: var(--text); }
        .sidebar-link.active { background: var(--terra-soft); color: var(--terra); font-weight: 600; }
        .sidebar-link .icon { width: 20px; text-align: center; font-size: .9rem; flex-shrink: 0; }
        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-user-name { font-size: .82rem; font-weight: 600; }
        .sidebar-user-role { font-size: .7rem; color: var(--gold); font-weight: 700; }

        /* ── MAIN ── */
        .admin-main {
            flex: 1;
            margin-left: 240px;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .admin-topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
            backdrop-filter: blur(12px);
        }
        .admin-page-title {
            font-family: var(--font-head);
            font-size: 1rem; font-weight: 700;
        }
        .admin-topbar-right { display: flex; gap: 10px; align-items: center; }
        .topbar-btn {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-muted); padding: 6px 14px;
            border-radius: 100px; font-family: var(--font-body);
            font-size: .78rem; font-weight: 500;
            text-decoration: none; transition: all .2s;
        }
        .topbar-btn:hover { border-color: var(--border-hover); color: var(--text); }
        .admin-content { padding: 28px; flex: 1; }

        /* ── FLASH ── */
        .admin-flash {
            padding: 12px 16px; border-radius: 12px; margin-bottom: 20px;
            font-size: .84rem; font-weight: 500;
            display: flex; align-items: center; gap: 8px;
        }
        .admin-flash.success { background:rgba(45,90,61,.12);border:1px solid rgba(45,90,61,.25);color:#3D8A58; }
        .admin-flash.error   { background:rgba(200,82,42,.1);border:1px solid rgba(200,82,42,.25);color:var(--terra); }
        [data-theme="dark"] .admin-flash.success { color:#6DC48A; }

        /* ── CARDS STAT ── */
        .stat-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-bottom:28px; }
        .stat-card { background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:20px;transition:background .4s,border-color .4s; }
        .stat-card-label { font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px; }
        .stat-card-value { font-family:var(--font-head);font-size:2rem;font-weight:800;letter-spacing:-.03em;color:var(--text); }
        .stat-card-sub { font-size:.74rem;color:var(--text-faint);margin-top:4px; }
        .stat-card.accent { border-color:rgba(200,82,42,.3);background:var(--terra-soft); }
        .stat-card.accent .stat-card-value { color:var(--terra); }

        /* ── TABLE ── */
        .admin-table-wrap { background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden; }
        .admin-table-header { padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap; }
        .admin-table-title { font-family:var(--font-head);font-size:.92rem;font-weight:700; }
        table { width:100%;border-collapse:collapse; }
        th { padding:11px 16px;text-align:left;font-size:.7rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border); }
        td { padding:12px 16px;font-size:.84rem;border-bottom:1px solid var(--border);vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:var(--bg-hover); }

        /* ── BADGES ── */
        .badge { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:100px;font-size:.7rem;font-weight:700;letter-spacing:.03em; }
        .badge-green  { background:rgba(45,90,61,.12);color:#3D8A58;border:1px solid rgba(45,90,61,.2); }
        .badge-red    { background:rgba(224,85,85,.1);color:#E05555;border:1px solid rgba(224,85,85,.2); }
        .badge-gold   { background:var(--gold-soft);color:var(--gold);border:1px solid rgba(212,168,67,.25); }
        .badge-terra  { background:var(--terra-soft);color:var(--terra);border:1px solid rgba(200,82,42,.2); }
        .badge-gray   { background:var(--bg-card2);color:var(--text-muted);border:1px solid var(--border); }
        [data-theme="dark"] .badge-green { color:#6DC48A; }

        /* ── ACTIONS ── */
        .action-row { display:flex;gap:6px;align-items:center; }
        .btn-action {
            padding:5px 12px;border-radius:8px;font-size:.75rem;font-weight:600;
            border:1px solid var(--border);background:transparent;color:var(--text-muted);
            transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;gap:4px;
        }
        .btn-action:hover { border-color:var(--border-hover);color:var(--text); }
        .btn-action.danger { border-color:rgba(224,85,85,.3);color:#E05555; }
        .btn-action.danger:hover { background:rgba(224,85,85,.08);border-color:#E05555; }
        .btn-action.success { border-color:rgba(45,90,61,.3);color:#3D8A58; }
        .btn-action.success:hover { background:rgba(45,90,61,.08); }
        [data-theme="dark"] .btn-action.success { color:#6DC48A; }

        /* ── SEARCH ── */
        .admin-search { background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:8px 14px;color:var(--text);font-family:var(--font-body);font-size:.84rem;outline:none;transition:border-color .2s; }
        .admin-search:focus { border-color:var(--terra); }
        .admin-search::placeholder { color:var(--text-faint); }

        /* ── AVATAR MINI ── */
        .user-avatar-mini { width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--terra),var(--gold));padding:1.5px;flex-shrink:0;overflow:hidden; }
        .user-avatar-mini-inner { width:100%;height:100%;border-radius:50%;background:var(--bg-card2);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;overflow:hidden; }
        .user-avatar-mini-inner img { width:100%;height:100%;object-fit:cover;border-radius:50%; }

        /* Pagination */
        .pagination-wrap { padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:center; }
        .pagination-wrap .pagination { display:flex;gap:6px;list-style:none; }
        .pagination-wrap .pagination li a,
        .pagination-wrap .pagination li span { display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 8px;border-radius:8px;font-size:.8rem;background:var(--bg-card2);border:1px solid var(--border);color:var(--text-muted);text-decoration:none;transition:all .2s; }
        .pagination-wrap .pagination li.active span { background:var(--terra);border-color:var(--terra);color:white; }
        .pagination-wrap .pagination li a:hover { border-color:var(--terra);color:var(--terra); }

        /* ── MOBILE HAMBURGER ── */
        .admin-mob-toggle {
            display:none;
            align-items:center;justify-content:center;
            width:36px;height:36px;
            background:var(--bg-card2);
            border:1px solid var(--border);
            border-radius:8px;
            cursor:pointer;
            color:var(--text-muted);
            font-size:1.1rem;
            flex-shrink:0;
        }

        /* ── OVERLAY SIDEBAR MOBILE ── */
        .admin-sidebar-overlay {
            display:none;
            position:fixed;inset:0;
            background:rgba(0,0,0,.55);
            z-index:49;
            backdrop-filter:blur(2px);
        }
        .admin-sidebar-overlay.open { display:block; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            body { display:block; }

            /* Sidebar : hors écran par défaut */
            .admin-sidebar {
                transform:translateX(-100%);
                transition:transform .25s ease;
                z-index:50;
                width:260px;
            }
            .admin-sidebar.open { transform:translateX(0); }

            /* Main : pleine largeur */
            .admin-main { margin-left:0; }

            /* Topbar */
            .admin-topbar { padding:12px 16px; gap:8px; }
            .admin-page-title { font-size:.88rem; }
            .admin-topbar-right { gap:6px; }
            .topbar-btn { padding:5px 10px; font-size:.72rem; }

            /* Contenu */
            .admin-content { padding:16px; }

            /* Stat grid : 2 colonnes */
            .stat-grid { grid-template-columns:repeat(2,1fr);gap:10px; }

            /* Tables : scroll horizontal */
            .admin-table-wrap { overflow-x:auto; }

            /* Afficher le bouton hamburger */
            .admin-mob-toggle { display:flex; }
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

    <!-- ── OVERLAY SIDEBAR MOBILE ── -->
    <div class="admin-sidebar-overlay" id="adminOverlay"></div>

    <!-- ── SIDEBAR ── -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <svg width="28" height="28" viewBox="0 0 42 42" fill="none">
                <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="var(--bg-card2)" stroke="#D4A843" stroke-width="0.8"/>
                <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#C8522A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
            <div>
                <div class="sidebar-brand-name">Melano<span>Geek</span></div>
                <span class="sidebar-badge">{{ auth()->user()?->isOwner() ? 'Owner' : 'Admin' }}</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            @if(auth()->user()?->isOwner())
                {{-- Navigation owner complète --}}
                <div class="sidebar-section">Espace Owner</div>
                <a href="{{ route('owner.dashboard') }}" class="sidebar-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <span class="icon">👑</span> Vue d'ensemble
                </a>
                <a href="{{ route('owner.finances') }}" class="sidebar-link {{ request()->routeIs('owner.finances') ? 'active' : '' }}">
                    <span class="icon">💰</span> Finances
                </a>
                <a href="{{ route('owner.staff') }}" class="sidebar-link {{ request()->routeIs('owner.staff') ? 'active' : '' }}">
                    <span class="icon">🛡️</span> Gestion du staff
                </a>
                <a href="{{ route('owner.settings') }}" class="sidebar-link {{ request()->routeIs('owner.settings') ? 'active' : '' }}">
                    <span class="icon">⚙️</span> Paramètres
                </a>
                <a href="{{ route('owner.logs') }}" class="sidebar-link {{ request()->routeIs('owner.logs') ? 'active' : '' }}">
                    <span class="icon">📋</span> Logs d'activité
                </a>
                <div style="height:1px;background:var(--border);margin:8px 10px;"></div>
            @else
                <div class="sidebar-section">Vue d'ensemble</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="icon">📊</span> Dashboard
                </a>
            @endif

            <div class="sidebar-section">Modération</div>
            <a href="{{ route('admin.applications') }}" class="sidebar-link {{ request()->routeIs('admin.applications*') ? 'active' : '' }}">
                <span class="icon">📋</span> Candidatures
                @php $pendingApps = \App\Models\User::where('status','pending')->where(fn($q) => $q->whereNull('role')->orWhere('role','creator'))->count(); @endphp
                @if($pendingApps > 0)
                    <span style="margin-left:auto;background:#E8B84B;color:#1A0E06;font-size:.6rem;font-weight:800;padding:2px 7px;border-radius:100px;">{{ $pendingApps }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span class="icon">👥</span> Utilisateurs
            </a>
            <a href="{{ route('admin.posts') }}" class="sidebar-link {{ request()->routeIs('admin.posts*') ? 'active' : '' }}">
                <span class="icon">📝</span> Publications
            </a>
            <a href="{{ route('admin.subscriptions') }}" class="sidebar-link {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}">
                <span class="icon">💳</span> Abonnements
                @php $pendingSubscriptions = \App\Models\Subscription::where('status','pending')->count(); @endphp
                @if($pendingSubscriptions > 0)
                    <span style="margin-left:auto;background:var(--gold);color:#1C1208;font-size:.6rem;font-weight:700;padding:2px 6px;border-radius:100px;">{{ $pendingSubscriptions }}</span>
                @endif
            </a>
            <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" style="position:relative;">
                <span class="icon">🚩</span> Signalements
                @php $pendingReports = \App\Models\Report::where('status','pending')->count(); @endphp
                @if($pendingReports > 0)
                    <span style="margin-left:auto;background:#E05555;color:white;font-size:.6rem;font-weight:700;padding:2px 6px;border-radius:100px;">{{ $pendingReports }}</span>
                @endif
            </a>
            <div class="sidebar-section">Marketplace</div>
            <a href="{{ route('marketplace.index') }}" class="sidebar-link" target="_blank">
                <span class="icon">🛍️</span> Voir le marketplace
                @php $pendingOrders = \App\Models\Order::where('status','pending')->count(); @endphp
                @if($pendingOrders > 0)
                    <span style="margin-left:auto;background:var(--gold);color:#1C1208;font-size:.6rem;font-weight:700;padding:2px 6px;border-radius:100px;">{{ $pendingOrders }}</span>
                @endif
            </a>

            <div class="sidebar-section">Site</div>
            <a href="{{ route('admin.about') }}" class="sidebar-link {{ request()->routeIs('admin.about*') ? 'active' : '' }}">
                <span class="icon">📄</span> Page À propos
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
                <div class="sidebar-user-role">✦ {{ auth()->user()?->isOwner() ? 'Owner' : 'Admin' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--text-faint);font-size:.85rem;cursor:pointer;" title="Déconnexion">⏻</button>
            </form>
        </div>
    </aside>

    <!-- ── MAIN ── -->
    <div class="admin-main">
        <div class="admin-topbar">
            <button class="admin-mob-toggle" id="adminMobToggle" aria-label="Ouvrir le menu">☰</button>
            <div class="admin-page-title">@yield('page-title', 'Dashboard')</div>
            <div class="admin-topbar-right">
                <button id="adminThemeBtn" style="background:transparent;border:1px solid var(--border);color:var(--text-muted);width:34px;height:34px;border-radius:8px;cursor:pointer;font-size:.9rem;display:flex;align-items:center;justify-content:center;transition:all .2s;flex-shrink:0;" title="Changer le thème">🌙</button>
                <a href="{{ route('home') }}" class="topbar-btn">← Site</a>
            </div>
        </div>

        <div class="admin-content">
            @if(session('success'))
                <div class="admin-flash success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="admin-flash error">✗ {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        // Theme (sync avec le site principal)
        const html = document.documentElement;
        const saved = localStorage.getItem('mg-theme') || 'dark';
        html.setAttribute('data-theme', saved);

        /* ── Theme toggle ── */
        (function () {
            const btn = document.getElementById('adminThemeBtn');
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

        /* ── Sidebar mobile ── */
        (function () {
            const toggle  = document.getElementById('adminMobToggle');
            const sidebar = document.querySelector('.admin-sidebar');
            const overlay = document.getElementById('adminOverlay');
            if (!toggle || !sidebar || !overlay) return;

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            }

            toggle.addEventListener('click', openSidebar);
            overlay.addEventListener('click', closeSidebar);

            sidebar.querySelectorAll('.sidebar-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) closeSidebar();
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) closeSidebar();
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
