<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Owner') — MelanoGeek Owner</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <script>(function(){ document.documentElement.setAttribute('data-theme', localStorage.getItem('mg-theme') || 'dark'); })();</script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [data-theme="dark"]  { --bg:#06040A;--bg-card:#0E0B16;--bg-card2:#15112000;--bg-hover:#1C1730;--text:#EFE8FF;--text-muted:rgba(239,232,255,.52);--text-faint:rgba(239,232,255,.22);--border:rgba(180,148,255,.09);--border-hover:rgba(180,148,255,.18);--nav-bg:rgba(6,4,10,.92);--shadow-md:0 8px 32px rgba(0,0,0,.6); }
        [data-theme="light"] { --bg:#F4F0FF;--bg-card:#FFF;--bg-card2:#EBE6F8;--bg-hover:#E4DDF5;--text:#1A0F2E;--text-muted:rgba(26,15,46,.52);--text-faint:rgba(26,15,46,.22);--border:rgba(26,15,46,.09);--border-hover:rgba(26,15,46,.18);--nav-bg:rgba(244,240,255,.95);--shadow-md:0 8px 32px rgba(0,0,0,.08); }
        :root { --owner:#7B3FD4;--owner-soft:rgba(123,63,212,.13);--owner-glow:rgba(123,63,212,.35);--gold:#D4A843;--gold-soft:rgba(212,168,67,.12);--terra:#C8522A;--font-head:'Plus Jakarta Sans',sans-serif;--font-body:'Outfit',sans-serif; }
        *,*::before,*::after { margin:0;padding:0;box-sizing:border-box; }
        body { background:var(--bg);color:var(--text);font-family:var(--font-body);-webkit-font-smoothing:antialiased;display:flex;min-height:100vh; }

        /* ── SIDEBAR ── */
        .owner-sidebar {
            width:248px;flex-shrink:0;
            background:var(--bg-card);
            border-right:1px solid var(--border);
            display:flex;flex-direction:column;
            position:fixed;top:0;left:0;bottom:0;
            z-index:50;
        }
        .sidebar-brand {
            padding:20px 20px 16px;
            border-bottom:1px solid var(--border);
            display:flex;align-items:center;gap:10px;
        }
        .sidebar-brand-name { font-family:var(--font-head);font-size:1rem;font-weight:800; }
        .sidebar-brand-name span { color:var(--gold); }
        .owner-badge {
            font-size:.6rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;
            background:linear-gradient(135deg,#7B3FD4,#A855F7);
            color:white;padding:2px 8px;border-radius:100px;
            box-shadow:0 2px 8px var(--owner-glow);
        }
        .sidebar-nav { flex:1;padding:12px 10px;overflow-y:auto; }
        .sidebar-section { font-size:.63rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-faint);padding:14px 10px 6px; }
        .sidebar-link {
            display:flex;align-items:center;gap:10px;
            padding:9px 10px;border-radius:10px;
            color:var(--text-muted);text-decoration:none;
            font-size:.84rem;font-weight:500;
            transition:all .2s;margin-bottom:2px;
        }
        .sidebar-link:hover { background:var(--bg-hover);color:var(--text); }
        .sidebar-link.active { background:var(--owner-soft);color:var(--owner);font-weight:600; }
        .sidebar-link.active .icon { filter:none; }
        .sidebar-link .icon { width:20px;text-align:center;font-size:.9rem;flex-shrink:0; }
        .sidebar-divider { height:1px;background:var(--border);margin:8px 10px; }
        .sidebar-footer {
            padding:14px 16px;
            border-top:1px solid var(--border);
            display:flex;align-items:center;gap:10px;
        }
        .sidebar-user-name { font-size:.82rem;font-weight:600; }
        .sidebar-user-role { font-size:.7rem;color:#A855F7;font-weight:700; }

        /* ── MAIN ── */
        .owner-main { flex:1;margin-left:248px;min-height:100vh;display:flex;flex-direction:column; }
        .owner-topbar {
            background:var(--bg-card);border-bottom:1px solid var(--border);
            padding:14px 28px;
            display:flex;align-items:center;justify-content:space-between;
            position:sticky;top:0;z-index:40;backdrop-filter:blur(12px);
        }
        .owner-page-title { font-family:var(--font-head);font-size:1rem;font-weight:700; }
        .owner-topbar-right { display:flex;gap:10px;align-items:center; }
        .topbar-btn { background:transparent;border:1px solid var(--border);color:var(--text-muted);padding:6px 14px;border-radius:100px;font-family:var(--font-body);font-size:.78rem;font-weight:500;text-decoration:none;transition:all .2s; }
        .topbar-btn:hover { border-color:var(--border-hover);color:var(--text); }
        .topbar-btn.danger { border-color:rgba(200,82,42,.3);color:var(--terra); }
        .owner-content { padding:28px;flex:1; }

        /* ── FLASH ── */
        .owner-flash { padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:.84rem;font-weight:500;display:flex;align-items:center;gap:8px; }
        .owner-flash.success { background:rgba(45,90,61,.12);border:1px solid rgba(45,90,61,.25);color:#3D8A58; }
        .owner-flash.error   { background:rgba(200,82,42,.1);border:1px solid rgba(200,82,42,.25);color:var(--terra); }
        [data-theme="dark"] .owner-flash.success { color:#6DC48A; }

        /* ── STAT CARDS ── */
        .stat-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-bottom:28px; }
        .stat-card { background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:20px; }
        .stat-card-label { font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px; }
        .stat-card-value { font-family:var(--font-head);font-size:2rem;font-weight:800;letter-spacing:-.03em; }
        .stat-card-sub { font-size:.74rem;color:var(--text-faint);margin-top:4px; }
        .stat-card.owner-accent { border-color:rgba(123,63,212,.3);background:var(--owner-soft); }
        .stat-card.owner-accent .stat-card-value { color:var(--owner); }
        .stat-card.gold { border-color:rgba(212,168,67,.25);background:var(--gold-soft); }
        .stat-card.gold .stat-card-value { color:var(--gold); }

        /* ── TABLE ── */
        .owner-table-wrap { background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:24px; }
        .owner-table-header { padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap; }
        .owner-table-title { font-family:var(--font-head);font-size:.92rem;font-weight:700; }
        table { width:100%;border-collapse:collapse; }
        th { padding:11px 16px;text-align:left;font-size:.7rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border); }
        td { padding:12px 16px;font-size:.84rem;border-bottom:1px solid var(--border);vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:var(--bg-hover); }

        /* ── BADGES ── */
        .badge { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:100px;font-size:.7rem;font-weight:700; }
        .badge-owner  { background:var(--owner-soft);color:var(--owner);border:1px solid rgba(123,63,212,.25); }
        .badge-admin  { background:var(--gold-soft);color:var(--gold);border:1px solid rgba(212,168,67,.25); }
        .badge-green  { background:rgba(45,90,61,.12);color:#3D8A58;border:1px solid rgba(45,90,61,.2); }
        .badge-red    { background:rgba(224,85,85,.1);color:#E05555;border:1px solid rgba(224,85,85,.2); }
        .badge-gray   { background:var(--bg-card2);color:var(--text-muted);border:1px solid var(--border); }
        [data-theme="dark"] .badge-green { color:#6DC48A; }

        /* ── ACTIONS ── */
        .action-row { display:flex;gap:6px;align-items:center; }
        .btn-action { padding:5px 12px;border-radius:8px;font-size:.75rem;font-weight:600;border:1px solid var(--border);background:transparent;color:var(--text-muted);transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;gap:4px; }
        .btn-action:hover { border-color:var(--border-hover);color:var(--text); }
        .btn-action.danger { border-color:rgba(224,85,85,.3);color:#E05555; }
        .btn-action.danger:hover { background:rgba(224,85,85,.08); }
        .btn-action.owner { border-color:rgba(123,63,212,.3);color:var(--owner); }
        .btn-action.owner:hover { background:var(--owner-soft); }

        /* ── FORM ── */
        .field { margin-bottom:16px; }
        .field label { display:block;font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px; }
        .field input,.field select,.field textarea { width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.88rem;outline:none;transition:border-color .2s; }
        .field input:focus,.field select:focus,.field textarea:focus { border-color:var(--owner); }
        .field textarea { resize:vertical;min-height:80px; }
        .btn-owner { background:linear-gradient(135deg,#7B3FD4,#A855F7);border:none;color:white;padding:11px 24px;border-radius:100px;font-family:var(--font-head);font-size:.9rem;font-weight:700;cursor:pointer;transition:all .2s; }
        .btn-owner:hover { box-shadow:0 8px 24px var(--owner-glow);transform:translateY(-1px); }

        /* Toggle switch */
        .toggle-row { display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border); }
        .toggle-row:last-child { border-bottom:none; }
        .toggle-label { font-size:.84rem; }
        .toggle-label small { display:block;font-size:.72rem;color:var(--text-muted); }
        .toggle-switch { position:relative;display:inline-block;width:42px;height:24px; }
        .toggle-switch input { opacity:0;width:0;height:0; }
        .toggle-slider { position:absolute;cursor:pointer;inset:0;background:var(--bg-hover);border:1px solid var(--border);border-radius:100px;transition:background .2s; }
        .toggle-slider::before { content:'';position:absolute;width:16px;height:16px;border-radius:50%;background:var(--text-faint);left:3px;top:50%;transform:translateY(-50%);transition:transform .2s,background .2s; }
        .toggle-switch input:checked + .toggle-slider { background:var(--owner-soft);border-color:rgba(123,63,212,.4); }
        .toggle-switch input:checked + .toggle-slider::before { transform:translate(18px,-50%);background:var(--owner); }

        /* Pagination */
        .pagination-wrap { padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:center; }
        .pagination-wrap .pagination { display:flex;gap:6px;list-style:none; }
        .pagination-wrap .pagination li a,
        .pagination-wrap .pagination li span { display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 8px;border-radius:8px;font-size:.8rem;background:var(--bg-card2);border:1px solid var(--border);color:var(--text-muted);text-decoration:none;transition:all .2s; }
        .pagination-wrap .pagination li.active span { background:var(--owner);border-color:var(--owner);color:white; }
        .pagination-wrap .pagination li a:hover { border-color:var(--owner);color:var(--owner); }

        /* Avatar mini */
        .user-avatar-mini { width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--owner),var(--gold));padding:1.5px;flex-shrink:0;overflow:hidden; }
        .user-avatar-mini-inner { width:100%;height:100%;border-radius:50%;background:var(--bg-card2);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;overflow:hidden; }
        .user-avatar-mini-inner img { width:100%;height:100%;object-fit:cover;border-radius:50%; }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ── SIDEBAR ── -->
    <aside class="owner-sidebar">
        <div class="sidebar-brand">
            <svg width="28" height="28" viewBox="0 0 42 42" fill="none">
                <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="var(--bg-card2)" stroke="#A855F7" stroke-width="0.8"/>
                <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#7B3FD4" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <circle cx="21" cy="9" r="2.5" fill="#D4A843"/>
            </svg>
            <div>
                <div class="sidebar-brand-name">Melano<span>Geek</span></div>
                <span class="owner-badge">✦ Owner</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            {{-- Zone Owner --}}
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

            <div class="sidebar-divider"></div>

            {{-- Zone Gestion (partagée avec admins) --}}
            <div class="sidebar-section">Modération</div>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span class="icon">👥</span> Utilisateurs
            </a>
            <a href="{{ route('admin.posts') }}" class="sidebar-link {{ request()->routeIs('admin.posts*') ? 'active' : '' }}">
                <span class="icon">📝</span> Publications
            </a>
            <a href="{{ route('admin.subscriptions') }}" class="sidebar-link {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}">
                <span class="icon">💳</span> Abonnements
            </a>
            <a href="{{ route('admin.applications') }}" class="sidebar-link {{ request()->routeIs('admin.applications*') ? 'active' : '' }}" style="position:relative;">
                <span class="icon">⭐</span> Candidatures
                @php $pendingCount = \App\Models\User::where('status','pending')->where(fn($q) => $q->whereNull('role')->orWhere('role','creator'))->count(); @endphp
                @if($pendingCount > 0)
                    <span style="margin-left:auto;background:var(--owner);color:white;font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:100px;">{{ $pendingCount }}</span>
                @endif
            </a>

            <div class="sidebar-section">Site</div>
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
                <div class="sidebar-user-role">✦ Propriétaire</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--text-faint);font-size:.85rem;cursor:pointer;" title="Déconnexion">⏻</button>
            </form>
        </div>
    </aside>

    <!-- ── MAIN ── -->
    <div class="owner-main">
        <div class="owner-topbar">
            <div class="owner-page-title">@yield('page-title', 'Dashboard Owner')</div>
            <div class="owner-topbar-right">
                <a href="{{ route('admin.users') }}" class="topbar-btn">🛡 Admin panel</a>
                <a href="{{ route('home') }}" class="topbar-btn">← Site public</a>
            </div>
        </div>

        <div class="owner-content">
            @if(session('success'))
                <div class="owner-flash success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="owner-flash error">✗ {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        const saved = localStorage.getItem('mg-theme') || 'dark';
        document.documentElement.setAttribute('data-theme', saved);
    </script>
    @stack('scripts')
</body>
</html>
