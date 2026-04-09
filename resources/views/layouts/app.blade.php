<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'MelanoGeek'))</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#C8522A">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="MelanoGeek">
    <link rel="apple-touch-icon" href="/images/icons/apple-touch-icon.png">

    <!-- Fonts : chargement dynamique selon le thème actif -->
    <script>
    (function(){
        var t = localStorage.getItem('mg-theme') || 'light';
        if (t === 'dark') t = 'hogwarts';
        var f = 'family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Outfit:wght@300;400;500;600';
        if (t === 'light')    f += '&family=Unbounded:wght@300;400;600;700;900&family=Sora:wght@300;400;500;600';
        if (t === 'hogwarts') f += '&family=Cinzel:wght@400;600;700;800';
        var pc1 = document.createElement('link'); pc1.rel = 'preconnect'; pc1.href = 'https://fonts.googleapis.com';
        var pc2 = document.createElement('link'); pc2.rel = 'preconnect'; pc2.href = 'https://fonts.gstatic.com'; pc2.crossOrigin = 'anonymous';
        var lk  = document.createElement('link'); lk.rel = 'stylesheet';
        lk.href = 'https://fonts.googleapis.com/css2?' + f + '&display=swap';
        document.head.append(pc1, pc2, lk);
        window._mgFontLoaded = t;
    })();
    </script>

    <style>
    /* ═══════════════════════════════════════════════
       VARIABLES GLOBALES — 3 THÈMES
    ═══════════════════════════════════════════════ */

    /* ── Sombre (défaut) ── */
    :root, [data-theme="dark"] {
        --bg:           #0D0905;
        --bg-card:      #141009;
        --bg-card2:     #1C1810;
        --bg-hover:     #221C13;
        --text:         #F0E8D8;
        --cream:        #F0E8D8;
        --text-muted:   rgba(240,232,216,0.55);
        --cream-muted:  rgba(240,232,216,0.55);
        --text-faint:   rgba(240,232,216,0.18);
        --muted:        rgba(240,232,216,0.35);
        --border:       rgba(240,232,216,0.10);
        --border-hover: rgba(240,232,216,0.22);
        --nav-bg:       rgba(13,9,5,0.90);
        --toggle-bg:    rgba(240,232,216,0.08);
        --terra:        #C8522A;
        --terracotta:   #C8522A;
        --terra-soft:   rgba(200,82,42,0.12);
        --gold:         #D4A843;
        --gold-soft:    rgba(212,168,67,0.10);
        --accent:       #E06030;
        --green:        #2A7A48;
        --font-head:    'Plus Jakarta Sans', sans-serif;
        --font-body:    'Outfit', sans-serif;
        --shadow-sm:    0 4px 12px rgba(0,0,0,0.40);
        --shadow-md:    0 8px 24px rgba(0,0,0,0.50);
        --shadow-lg:    0 16px 48px rgba(0,0,0,0.65);
        --grain:        1;
    }

    /* ── Clair / v4 Terracotta Africain ── */
    [data-theme="light"] {
        --bg:           #F5EDD6;
        --bg-card:      #FBF5E6;
        --bg-card2:     #EDE0C0;
        --bg-hover:     #E8D9B0;
        --text:         #1E0E04;
        --cream:        #1E0E04;
        --text-muted:   rgba(30,14,4,0.55);
        --cream-muted:  rgba(30,14,4,0.55);
        --text-faint:   rgba(30,14,4,0.18);
        --muted:        rgba(30,14,4,0.35);
        --border:       rgba(30,14,4,0.12);
        --border-hover: rgba(30,14,4,0.25);
        --nav-bg:       rgba(245,237,214,0.90);
        --toggle-bg:    #EDE0C0;
        --terra:        #C84818;
        --terracotta:   #C84818;
        --terra-soft:   rgba(200,72,24,0.10);
        --gold:         #B87820;
        --gold-soft:    rgba(184,120,32,0.10);
        --accent:       #E85A1A;
        --green:        #1A5A30;
        --font-head:    'Unbounded', sans-serif;
        --font-body:    'Sora', sans-serif;
        --shadow-sm:    0 4px 12px rgba(30,14,4,0.08);
        --shadow-md:    0 8px 24px rgba(30,14,4,0.12);
        --shadow-lg:    0 16px 48px rgba(30,14,4,0.20);
        --grain:        0;
    }

    /* ── Hogwarts ── */
    [data-theme="hogwarts"] {
        --bg:           #05040F;
        --bg-card:      #0D0B1E;
        --bg-card2:     #130F2A;
        --bg-hover:     #1C1640;
        --text:         #EFE5C8;
        --cream:        #EFE5C8;
        --text-muted:   rgba(239,229,200,0.55);
        --cream-muted:  rgba(239,229,200,0.55);
        --text-faint:   rgba(239,229,200,0.18);
        --muted:        rgba(239,229,200,0.35);
        --border:       rgba(180,148,60,0.14);
        --border-hover: rgba(180,148,60,0.30);
        --nav-bg:       rgba(5,4,15,0.92);
        --toggle-bg:    rgba(180,148,60,0.10);
        --terra:        #9B5FD1;
        --terracotta:   #9B5FD1;
        --terra-soft:   rgba(123,63,190,0.15);
        --gold:         #D4AF37;
        --gold-soft:    rgba(212,175,55,0.12);
        --accent:       #B87AE8;
        --green:        #2A7A48;
        --font-head:    'Cinzel', serif;
        --font-body:    'Outfit', sans-serif;
        --shadow-sm:    0 4px 12px rgba(0,0,0,0.55);
        --shadow-md:    0 8px 24px rgba(0,0,0,0.70);
        --shadow-lg:    0 16px 48px rgba(0,0,0,0.85);
        --grain:        1;
    }

    /* ═══════════════════════════════════════════════
       BASE
    ═══════════════════════════════════════════════ */
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; overflow-x: hidden; }
    body {
        background: var(--bg);
        color: var(--text);
        font-family: var(--font-body);
        transition: background .35s, color .35s;
        /* overflow-x sur html pour éviter le bug iOS PWA où
           body overflow:hidden bloque le focus des inputs */
    }

    /* Restauration des curseurs natifs */
    *, *::before, *::after { cursor: auto; }
    a, button, label, select, [onclick],
    input[type=submit], input[type=button],
    input[type=checkbox], input[type=radio],
    input[type=range], input[type=file] { cursor: pointer; }
    textarea, input[type=text], input[type=email],
    input[type=password], input[type=number],
    input[type=search] { cursor: text; }

    /* ═══════════════════════════════════════════════
       MOBILE / PWA — fixes globaux
    ═══════════════════════════════════════════════ */
    @media (max-width: 768px) {
        /* Taille de texte minimale lisible sur mobile */
        body { font-size: 15px; }

        /* Touch targets : min 44px (recommandation Apple) */
        button, a, [role="button"],
        input[type=submit], input[type=button] {
            min-height: 44px;
            touch-action: manipulation;
        }

        /* Inputs plus grands sur mobile */
        input, textarea, select {
            font-size: 16px !important; /* évite le zoom auto sur iOS */
            touch-action: manipulation;
        }

        /* Éviter les overflow horizontaux */
        img, video, iframe { max-width: 100%; }

        /* Padding safe area pour iPhone (encoches) */
        .mg-nav {
            padding-left: max(16px, env(safe-area-inset-left));
            padding-right: max(16px, env(safe-area-inset-right));
        }

        /* Contenu principal : safe area bottom (barre iPhone) */
        main, .feed-page, .profile-page, .page-content {
            padding-bottom: max(60px, calc(60px + env(safe-area-inset-bottom)));
        }
    }

    /* iOS : font-size 16px empêche le zoom automatique sur focus input */
    @media (max-width: 480px) {
        input, textarea, select { font-size: 16px !important; }
    }

    /* ═══════════════════════════════════════════════
       NAVIGATION
    ═══════════════════════════════════════════════ */
    .mg-nav {
        position: fixed; top:0; left:0; right:0; z-index:200;
        height: calc(72px + env(safe-area-inset-top));
        padding: env(safe-area-inset-top) 52px 0;
        display: flex; align-items: center; justify-content: space-between;
        background: var(--nav-bg);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        transition: background .35s, border-color .35s;
    }

    /* Logo */
    .mg-logo { display:flex;align-items:center;gap:12px;text-decoration:none; }
    .mg-logo-svg { width:36px;height:36px;flex-shrink:0; }
    .mg-logo-name { font-family:var(--font-head);font-weight:700;font-size:.9rem;letter-spacing:-.01em;color:var(--text);transition:color .35s; }
    .mg-logo-name span { color:var(--terra); }

    /* Liens */
    .mg-links { display:flex;gap:32px;list-style:none; }
    .mg-links a { font-size:.72rem;color:var(--text-muted);text-decoration:none;letter-spacing:.06em;text-transform:uppercase;transition:color .2s;cursor:pointer; }
    .mg-links a:hover, .mg-links a.mg-link-active { color:var(--terra); }

    /* Droite */
    .mg-right { display:flex;gap:8px;align-items:center; }
    .mg-btn-ghost { background:none;border:1px solid var(--border);color:var(--text-muted);padding:8px 16px;border-radius:6px;font-family:var(--font-body);font-size:.73rem;cursor:pointer;transition:all .2s;text-decoration:none; }
    .mg-btn-ghost:hover { border-color:var(--terra);color:var(--terra); }
    .mg-btn-solid { background:var(--terra);color:white;border:none;padding:8px 16px;border-radius:6px;font-family:var(--font-body);font-size:.73rem;font-weight:600;cursor:pointer;transition:all .2s;text-decoration:none; }
    .mg-btn-solid:hover { background:var(--accent);transform:translateY(-1px); }

    /* Bouton thème */
    .theme-toggle {
        width:36px;height:36px;border-radius:50%;
        background: var(--toggle-bg);
        border: 1px solid var(--border);
        color: var(--text-muted);
        font-size:.95rem;
        display:flex;align-items:center;justify-content:center;
        cursor:pointer;transition:all .2s;flex-shrink:0;
    }
    .theme-toggle:hover { border-color:var(--terra);color:var(--terra);background:var(--terra-soft); }

    /* Hogwarts — logo reste terracotta, nav gold hover */
    [data-theme="hogwarts"] .mg-logo-hex        { background: #C8522A !important; }
    [data-theme="hogwarts"] .mg-logo-name span  { color: #C8522A !important; }
    [data-theme="hogwarts"] .mg-links a:hover   { color: var(--gold); }
    [data-theme="hogwarts"] .mg-btn-ghost:hover { border-color:var(--gold);color:var(--gold); }

    /* Hogwarts — fond magique : étoiles ✦ éclairs ⚡ lunes 🌙 hiboux 🦉 baguettes */
    [data-theme="hogwarts"] body::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background-image: url("data:image/svg+xml,%3Csvg width='400' height='400' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='18' cy='22' r='1.2' fill='%23D4AF37' opacity='0.55'/%3E%3Ccircle cx='73' cy='58' r='0.8' fill='%23EFE5C8' opacity='0.4'/%3E%3Ccircle cx='142' cy='18' r='1' fill='%23EFE5C8' opacity='0.5'/%3E%3Ccircle cx='195' cy='77' r='1.3' fill='%23D4AF37' opacity='0.4'/%3E%3Ccircle cx='230' cy='30' r='0.9' fill='%23EFE5C8' opacity='0.35'/%3E%3Ccircle cx='310' cy='52' r='1.1' fill='%23D4AF37' opacity='0.45'/%3E%3Ccircle cx='360' cy='20' r='0.8' fill='%23EFE5C8' opacity='0.4'/%3E%3Ccircle cx='45' cy='160' r='0.9' fill='%23D4AF37' opacity='0.3'/%3E%3Ccircle cx='115' cy='195' r='1.2' fill='%23EFE5C8' opacity='0.4'/%3E%3Ccircle cx='270' cy='140' r='1' fill='%23D4AF37' opacity='0.35'/%3E%3Ccircle cx='380' cy='175' r='0.8' fill='%23EFE5C8' opacity='0.4'/%3E%3Ccircle cx='60' cy='290' r='1.1' fill='%23D4AF37' opacity='0.45'/%3E%3Ccircle cx='175' cy='310' r='0.9' fill='%23EFE5C8' opacity='0.35'/%3E%3Ccircle cx='320' cy='260' r='1.2' fill='%23D4AF37' opacity='0.4'/%3E%3Ccircle cx='390' cy='320' r='0.8' fill='%23EFE5C8' opacity='0.3'/%3E%3Ccircle cx='130' cy='370' r='1' fill='%23D4AF37' opacity='0.4'/%3E%3Ccircle cx='250' cy='385' r='0.9' fill='%23EFE5C8' opacity='0.35'/%3E%3Cpath d='M55,87 L56.8,92.2 L62,94 L56.8,95.8 L55,101 L53.2,95.8 L48,94 L53.2,92.2Z' fill='%23D4AF37' opacity='0.25'/%3E%3Cpath d='M180,37 L181.8,42.2 L187,44 L181.8,45.8 L180,51 L178.2,45.8 L173,44 L178.2,42.2Z' fill='%23EFE5C8' opacity='0.2'/%3E%3Cpath d='M295,107 L297.2,113.2 L303,115 L297.2,116.8 L295,123 L292.8,116.8 L287,115 L292.8,113.2Z' fill='%23D4AF37' opacity='0.22'/%3E%3Cpath d='M88,242 L89.8,247.2 L95,249 L89.8,250.8 L88,256 L86.2,250.8 L81,249 L86.2,247.2Z' fill='%23EFE5C8' opacity='0.2'/%3E%3Cpath d='M350,192 L352,198 L358,200 L352,202 L350,208 L348,202 L342,200 L348,198Z' fill='%23D4AF37' opacity='0.22'/%3E%3Cpath d='M220,322 L222,328 L228,330 L222,332 L220,338 L218,332 L212,330 L218,328Z' fill='%23EFE5C8' opacity='0.18'/%3E%3Cpath d='M345,58 L339,72 L343,72 L337,86 L353,68 L348,68Z' fill='%239B5FD1' opacity='0.18'/%3E%3Cpath d='M123,130 L118,141 L121.5,141 L116,152 L129,137 L125,137Z' fill='%23B87AE8' opacity='0.15'/%3E%3Cpath d='M273,310 L268,321 L271.5,321 L266,332 L279,317 L275,317Z' fill='%239B5FD1' opacity='0.15'/%3E%3Cpath d='M168,157 Q180,167 168,177 Q188,175 188,167 Q188,159 168,157Z' fill='%23D4AF37' opacity='0.17'/%3E%3Cpath d='M368,298 Q378,307 368,316 Q386,314 386,307 Q386,300 368,298Z' fill='%23EFE5C8' opacity='0.14'/%3E%3Cellipse cx='30' cy='342' rx='9' ry='12' fill='%23EFE5C8' opacity='0.1'/%3E%3Cpath d='M24,330 L21,324 M36,330 L39,324' stroke='%23EFE5C8' stroke-width='1.8' stroke-linecap='round' opacity='0.12'/%3E%3Ccircle cx='27' cy='338' r='2.5' fill='none' stroke='%23D4AF37' stroke-width='1' opacity='0.18'/%3E%3Ccircle cx='33' cy='338' r='2.5' fill='none' stroke='%23D4AF37' stroke-width='1' opacity='0.18'/%3E%3Cellipse cx='335' cy='167' rx='9' ry='12' fill='%23EFE5C8' opacity='0.08'/%3E%3Cpath d='M329,155 L326,149 M341,155 L344,149' stroke='%23EFE5C8' stroke-width='1.8' stroke-linecap='round' opacity='0.1'/%3E%3Ccircle cx='332' cy='163' r='2.5' fill='none' stroke='%23D4AF37' stroke-width='1' opacity='0.15'/%3E%3Ccircle cx='338' cy='163' r='2.5' fill='none' stroke='%23D4AF37' stroke-width='1' opacity='0.15'/%3E%3Cline x1='192' y1='228' x2='208' y2='212' stroke='%23EFE5C8' stroke-width='1.5' stroke-linecap='round' opacity='0.15'/%3E%3Ccircle cx='208' cy='212' r='2' fill='%23D4AF37' opacity='0.25'/%3E%3Ccircle cx='208' cy='212' r='4' fill='none' stroke='%23D4AF37' stroke-width='0.5' opacity='0.18'/%3E%3Cline x1='62' y1='73' x2='78' y2='57' stroke='%23EFE5C8' stroke-width='1.5' stroke-linecap='round' opacity='0.12'/%3E%3Ccircle cx='78' cy='57' r='2' fill='%23D4AF37' opacity='0.2'/%3E%3Cline x1='373' y1='387' x2='387' y2='373' stroke='%23EFE5C8' stroke-width='1.5' stroke-linecap='round' opacity='0.13'/%3E%3Ccircle cx='387' cy='373' r='2' fill='%23D4AF37' opacity='0.22'/%3E%3Cline x1='18' y1='22' x2='55' y2='95' stroke='%23D4AF37' stroke-width='0.4' opacity='0.08'/%3E%3Cline x1='55' y1='95' x2='73' y2='58' stroke='%23D4AF37' stroke-width='0.4' opacity='0.08'/%3E%3Cline x1='295' y1='115' x2='310' y2='52' stroke='%23EFE5C8' stroke-width='0.4' opacity='0.07'/%3E%3Cline x1='310' y1='52' x2='360' y2='20' stroke='%23EFE5C8' stroke-width='0.4' opacity='0.07'/%3E%3Cline x1='88' y1='250' x2='60' y2='290' stroke='%23D4AF37' stroke-width='0.4' opacity='0.07'/%3E%3C/svg%3E");
        background-size: 400px 400px;
        animation: hog-twinkle 10s ease-in-out infinite alternate;
    }

    /* Hogwarts — lueurs nébuleuses violettes et dorées */
    [data-theme="hogwarts"] body::after {
        content: '';
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background:
            radial-gradient(ellipse at 15% 25%, rgba(123,63,190,0.08) 0%, transparent 50%),
            radial-gradient(ellipse at 85% 75%, rgba(212,175,55,0.06) 0%, transparent 50%),
            radial-gradient(ellipse at 62% 10%, rgba(184,122,232,0.05) 0%, transparent 38%),
            radial-gradient(ellipse at 30% 80%, rgba(155,95,209,0.05) 0%, transparent 40%);
        animation: hog-nebula 18s ease-in-out infinite alternate;
    }

    @keyframes hog-twinkle {
        0%   { opacity: 0.55; }
        55%  { opacity: 1; }
        100% { opacity: 0.65; }
    }

    @keyframes hog-nebula {
        0%   { opacity: 0.45; }
        100% { opacity: 1; }
    }

    /* Fond kente — mode light uniquement, sur tout le site */
    [data-theme="light"] body::before {
        content: ''; position: fixed; inset: 0; z-index: 0; pointer-events: none;
        background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C84818' stroke-width='0.6' opacity='0.08'%3E%3Crect x='10' y='10' width='20' height='20'/%3E%3Crect x='50' y='10' width='20' height='20'/%3E%3Crect x='10' y='50' width='20' height='20'/%3E%3Crect x='50' y='50' width='20' height='20'/%3E%3Cline x1='0' y1='40' x2='80' y2='40'/%3E%3Cline x1='40' y1='0' x2='40' y2='80'/%3E%3Cpath d='M10 10 L30 30 M50 10 L70 30 M10 50 L30 70 M50 50 L70 70'/%3E%3C/g%3E%3C/svg%3E");
        background-size: 80px 80px;
    }

    /* Supprimer le kente terracotta sur la page d'accueil en mode Hogwarts */
    [data-theme="hogwarts"] .home-page::before { display: none; }

    @media (prefers-reduced-motion: reduce) {
        [data-theme="hogwarts"] body::before,
        [data-theme="hogwarts"] body::after { animation: none !important; }
    }

    /* ═══════════════════════════════════════════════
       HAMBURGER
    ═══════════════════════════════════════════════ */
    .mg-hamburger {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        width: 36px; height: 36px;
        background: var(--toggle-bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        flex-shrink: 0;
        transition: all .2s;
    }
    .mg-hamburger:hover { border-color: var(--terra); background: var(--terra-soft); }
    .mg-hamburger span {
        display: block;
        width: 16px; height: 1.5px;
        background: var(--text);
        border-radius: 2px;
        transition: transform .25s, opacity .2s;
    }
    .mg-hamburger.open span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); }
    .mg-hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
    .mg-hamburger.open span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); }

    /* ═══════════════════════════════════════════════
       MENU MOBILE
    ═══════════════════════════════════════════════ */
    .mg-mobile-menu {
        position: fixed;
        top: 64px; left: 0; right: 0;
        background: var(--nav-bg);
        backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
        border-bottom: 1px solid var(--border);
        z-index: 198;
        transform: translateY(-12px);
        opacity: 0;
        visibility: hidden;
        transition: transform .25s, opacity .2s, visibility .2s;
        max-height: calc(100vh - 64px);
        overflow-y: auto;
    }
    .mg-mobile-menu.open {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }
    .mg-mob-links { list-style: none; padding: 6px 0; }
    .mg-mob-links a {
        display: flex; align-items: center; gap: 10px;
        padding: 15px 24px;
        font-family: var(--font-body);
        font-size: .95rem; font-weight: 600;
        color: var(--text-muted);
        text-decoration: none;
        border-bottom: 1px solid var(--border);
        transition: color .15s, background .15s;
    }
    .mg-mob-links li:last-child a { border-bottom: none; }
    .mg-mob-links a:active, .mg-mob-links a:hover { color: var(--terra); background: var(--terra-soft); }
    .mg-mob-auth {
        display: flex; gap: 10px;
        padding: 16px 20px 20px;
        border-top: 1px solid var(--border);
    }
    .mg-mob-ghost, .mg-mob-solid {
        flex: 1; text-align: center;
        padding: 13px 16px; border-radius: 10px;
        font-family: var(--font-body);
        font-size: .88rem; font-weight: 700;
        text-decoration: none; transition: all .2s;
    }
    .mg-mob-ghost { border: 1px solid var(--border); color: var(--text); }
    .mg-mob-ghost:hover { border-color: var(--terra); color: var(--terra); }
    .mg-mob-solid { background: var(--terra); color: white; }
    .mg-mob-solid:hover { opacity: .88; }

    /* ═══════════════════════════════════════════════
       RESPONSIVE
    ═══════════════════════════════════════════════ */
    @media (max-width: 768px) {
        .mg-nav { padding: env(safe-area-inset-top) 16px 0; height: calc(64px + env(safe-area-inset-top)); }
        .mg-links { display: none; }
        .mg-hamburger { display: flex; }
        /* Boutons Connexion / Rejoindre / Mon fil → dans le burger */
        .mg-btn-ghost, .mg-btn-solid { display: none; }
        /* Bouton user : avatar seul, sans nom ni chevron */
        .mg-user-name, #mgUserChevron { display: none; }
        .mg-user-btn { padding: 4px; border-radius: 50%; }
        /* Logo : réduire légèrement le nom */
        .mg-logo-name { font-size: .8rem; }
        /* Theme toggle : visible sur mobile (la place libérée par le bouton message) */
        .theme-toggle { display: flex !important; min-height: unset; width: 36px; height: 36px; }
        /* Réduire le gap entre icônes */
        .mg-right { gap: 4px; }
        /* Corriger les boutons ronds : annuler le min-height: 44px global */
        .mg-notif-btn,
        .mg-hamburger,
        .mg-user-btn {
            min-height: unset;
            width: 36px;
            height: 36px;
        }
        /* Dropdown notifications : fixé sur mobile pour ne pas déborder */
        .mg-notif-dropdown {
            position: fixed;
            top: calc(64px + env(safe-area-inset-top) + 6px);
            left: 10px;
            right: 10px;
            width: auto;
            max-height: 65vh;
            overflow-y: auto;
        }
        .mg-notif-dropdown::before { display: none; }
    }
    </style>

    @stack('styles')

    <style>
    /* ── PROTECTION CONTENU CRÉATEURS ── */
    img, video {
        -webkit-user-drag: none;
        user-drag: none;
        -webkit-user-select: none;
        user-select: none;
    }
    /* Overlay transparent sur les médias pour bloquer le clic droit natif */
    .media-protected { position: relative; display: block; }
    .media-protected::after {
        content: '';
        position: absolute; inset: 0;
        z-index: 1;
    }
    </style>
</head>
<body ontouchstart="">

<!-- ══ NAVIGATION ══ -->
<nav class="mg-nav">
    <a href="{{ route('home') }}" class="mg-logo">
        <svg class="mg-logo-svg" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="var(--bg-card2)" stroke="#D4A843" stroke-width="0.8"/>
            <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#C8522A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
        <div class="mg-logo-name">Melano<span>Geek</span></div>
    </a>

    <ul class="mg-links">
        <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'mg-link-active' : '' }}">Blog</a></li>
        <li><a href="{{ route('forum.index') }}" class="{{ request()->routeIs('forum.*') ? 'mg-link-active' : '' }}">Forum</a></li>
        <li><a href="{{ route('about') }}">À propos</a></li>
    </ul>

    <div class="mg-right">
        @guest
            <a href="{{ route('login') }}"    class="mg-btn-ghost">Connexion</a>
            <a href="{{ route('register') }}" class="mg-btn-solid">Rejoindre</a>
        @else
            {{-- Cloche notifications --}}
            <div class="mg-notif-menu" id="mgNotifMenu">
                <button class="mg-notif-btn" id="mgNotifToggle" title="Notifications" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span class="mg-notif-dot" id="mgNotifDot" style="display:none;"></span>
                </button>
                <div class="mg-notif-dropdown" id="mgNotifDropdown">
                    <div class="mg-nd-header">
                        <span class="mg-nd-title">Notifications</span>
                        <button class="mg-nd-read-all" id="mgNdReadAll" type="button">Tout lire</button>
                    </div>
                    <div class="mg-nd-list" id="mgNdList">
                        <div class="mg-nd-loading" id="mgNdLoading">
                            <div class="mg-nd-spinner"></div>
                        </div>
                    </div>
                    <a href="{{ route('notifications') }}" class="mg-nd-footer">
                        Voir toutes les notifications →
                    </a>
                </div>
            </div>

            {{-- Menu utilisateur --}}
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
        <button class="mg-hamburger" id="mgHamburger" type="button" aria-label="Menu" aria-expanded="false" aria-controls="mgMobileMenu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <button class="theme-toggle" id="themeToggle" title="Changer le thème">☀️</button>
    </div>
</nav>

<style>
/* ── Cloche notifications ── */
.mg-notif-menu { position: relative; }
.mg-notif-btn {
    position: relative;
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--toggle-bg);
    border: 1px solid var(--border);
    color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all .2s;
    flex-shrink: 0;
}
.mg-notif-btn:hover,
.mg-notif-btn.active { border-color: var(--terra); color: var(--terra); background: var(--terra-soft); }
.mg-notif-dot {
    position: absolute;
    top: 5px; right: 5px;
    width: 8px; height: 8px;
    background: var(--terra);
    border-radius: 50%;
    border: 2px solid var(--nav-bg);
    animation: notif-pulse 2s ease-in-out infinite;
}
@keyframes notif-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%       { transform: scale(1.25); opacity: .7; }
}

/* ── Dropdown ── */
.mg-notif-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: -8px;
    width: 340px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    opacity: 0; visibility: hidden; transform: translateY(-8px) scale(.97);
    transition: opacity .18s, transform .18s, visibility .18s;
    z-index: 400;
}
.mg-notif-dropdown.open {
    opacity: 1; visibility: visible; transform: translateY(0) scale(1);
}

/* Petite flèche */
.mg-notif-dropdown::before {
    content: '';
    position: absolute;
    top: -6px; right: 18px;
    width: 12px; height: 12px;
    background: var(--bg-card);
    border-top: 1px solid var(--border);
    border-left: 1px solid var(--border);
    transform: rotate(45deg);
}

.mg-nd-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 16px 10px;
    border-bottom: 1px solid var(--border);
}
.mg-nd-title {
    font-family: var(--font-head);
    font-size: .88rem;
    font-weight: 800;
    color: var(--text);
}
.mg-nd-read-all {
    font-size: .72rem;
    color: var(--terra);
    background: none; border: none;
    cursor: pointer;
    font-family: var(--font-body);
    font-weight: 600;
    transition: opacity .2s;
    padding: 0;
}
.mg-nd-read-all:hover { opacity: .7; }

/* Liste d'items */
.mg-nd-list {
    max-height: 340px;
    overflow-y: auto;
}

/* Spinner */
.mg-nd-loading {
    display: flex; align-items: center; justify-content: center;
    padding: 28px;
}
.mg-nd-spinner {
    width: 22px; height: 22px;
    border: 2px solid var(--border);
    border-top-color: var(--terra);
    border-radius: 50%;
    animation: nd-spin .7s linear infinite;
}
@keyframes nd-spin { to { transform: rotate(360deg); } }

/* Item */
.mg-nd-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 11px 16px;
    border-bottom: 1px solid var(--border);
    text-decoration: none; color: var(--text);
    transition: background .15s;
    cursor: none;
    position: relative;
}
.mg-nd-item:last-child { border-bottom: none; }
.mg-nd-item:hover { background: var(--bg-hover); }
.mg-nd-item.unread { background: var(--terra-soft); }
.mg-nd-item.unread::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: var(--terra);
}

.mg-nd-avi {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 700; color: white;
    flex-shrink: 0; overflow: hidden; position: relative;
}
.mg-nd-avi img { width: 100%; height: 100%; object-fit: cover; }
.mg-nd-type-icon {
    position: absolute; bottom: -2px; right: -2px;
    width: 15px; height: 15px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .5rem;
    border: 1.5px solid var(--bg-card);
}
.mg-nd-type-icon.follow  { background: var(--terra); }
.mg-nd-type-icon.like    { background: #e85a8c; }
.mg-nd-type-icon.comment { background: var(--gold); }

.mg-nd-body { flex: 1; min-width: 0; }
.mg-nd-text {
    font-size: .8rem; line-height: 1.4;
    color: var(--text);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.mg-nd-text strong { font-weight: 700; }
.mg-nd-ago { font-size: .7rem; color: var(--text-muted); margin-top: 2px; }

/* Vide */
.mg-nd-empty {
    display: flex; flex-direction: column; align-items: center;
    padding: 32px 16px; gap: 8px; text-align: center;
}
.mg-nd-empty-icon { font-size: 1.8rem; }
.mg-nd-empty-text { font-size: .82rem; color: var(--text-muted); }

/* Footer lien */
.mg-nd-footer {
    display: block;
    text-align: center;
    padding: 11px 16px;
    font-size: .76rem;
    font-weight: 600;
    color: var(--terra);
    text-decoration: none;
    border-top: 1px solid var(--border);
    transition: background .15s;
    cursor: pointer;
}
.mg-nd-footer:hover { background: var(--terra-soft); }

/* ── Lien messages dans dropdown notifs ── */
.mg-nd-msg-link {
    position: relative;
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .74rem; font-weight: 600;
    color: var(--text-muted);
    text-decoration: none;
    transition: color .15s;
    font-family: var(--font-body);
}
.mg-nd-msg-link:hover { color: var(--terra); }

.mg-user-menu { position:relative; }
.mg-user-btn {
    display:flex;align-items:center;gap:8px;
    background:var(--bg-card);border:1px solid var(--border);
    border-radius:100px;padding:5px 12px 5px 5px;
    color:var(--text);cursor:pointer;transition:border-color .2s;
}
.mg-user-btn:hover { border-color:var(--border-hover); }
.mg-user-avi {
    width:28px;height:28px;border-radius:50%;
    background:linear-gradient(135deg,var(--terra),var(--gold));
    display:flex;align-items:center;justify-content:center;
    font-size:.72rem;font-weight:700;color:white;
    flex-shrink:0;overflow:hidden;
}
.mg-user-avi img { width:100%;height:100%;object-fit:cover; }
.mg-user-name { font-size:.78rem;font-weight:600;max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
.mg-user-dropdown {
    position:absolute;top:calc(100% + 8px);right:0;
    background:var(--bg-card);border:1px solid var(--border);
    border-radius:14px;padding:6px;min-width:180px;
    box-shadow:var(--shadow-md);
    opacity:0;visibility:hidden;transform:translateY(-6px);
    transition:opacity .18s,transform .18s,visibility .18s;
    z-index:300;
}
.mg-user-dropdown.open { opacity:1;visibility:visible;transform:translateY(0); }
.mg-drop-item {
    display:flex;align-items:center;gap:9px;
    padding:8px 12px;border-radius:9px;
    font-size:.82rem;font-weight:500;color:var(--text-muted);
    text-decoration:none;transition:background .15s,color .15s;
    width:100%;background:none;border:none;text-align:left;cursor:pointer;
    font-family:var(--font-body);
}
.mg-drop-item:hover { background:var(--bg-hover);color:var(--text); }
.mg-drop-divider { height:1px;background:var(--border);margin:4px 0; }
.mg-drop-logout:hover { color:var(--terra) !important;background:var(--terra-soft) !important; }
</style>

<!-- ══ MENU MOBILE ══ -->
<div class="mg-mobile-menu" id="mgMobileMenu" aria-hidden="true" role="dialog" aria-label="Menu de navigation">
    <ul class="mg-mob-links">
        <li><a href="{{ route('explore') }}">🔍 Explorer</a></li>
        <li><a href="{{ route('creators') }}">✨ Créateurs</a></li>
        <li><a href="{{ route('ranking') }}">🏆 Classement</a></li>
        <li><a href="{{ route('marketplace.index') }}">🛒 Marketplace</a></li>
        <li><a href="{{ route('subscription.pricing') }}">💎 Tarifs</a></li>
        <li><a href="{{ route('about') }}">📖 À propos</a></li>
        @auth
        <li><a href="{{ route('feed') }}">📰 Mon fil</a></li>
        @endauth
    </ul>
    @guest
    <div class="mg-mob-auth">
        <a href="{{ route('login') }}"    class="mg-mob-ghost">Connexion</a>
        <a href="{{ route('register') }}" class="mg-mob-solid">Rejoindre</a>
    </div>
    @endguest
</div>

<!-- ══ CONTENU ══ -->
@yield('content')

<!-- ══ SCRIPTS GLOBAUX ══ -->
<script>
(function () {
    /* ── Menu utilisateur ── */
    const userBtn  = document.getElementById('mgUserBtn');
    const userDrop = document.getElementById('mgUserDropdown');
    const chevron  = document.getElementById('mgUserChevron');
    if (userBtn && userDrop) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const open = userDrop.classList.toggle('open');
            if (chevron) chevron.style.transform = open ? 'rotate(180deg)' : '';
        });
        document.addEventListener('click', () => {
            userDrop.classList.remove('open');
            if (chevron) chevron.style.transform = '';
        });
    }

    /* ── Hamburger mobile ── */
    (function () {
        const burger  = document.getElementById('mgHamburger');
        const mobMenu = document.getElementById('mgMobileMenu');
        if (!burger || !mobMenu) return;

        function closeMenu() {
            burger.classList.remove('open');
            mobMenu.classList.remove('open');
            burger.setAttribute('aria-expanded', 'false');
            mobMenu.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        burger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = burger.classList.toggle('open');
            mobMenu.classList.toggle('open', isOpen);
            burger.setAttribute('aria-expanded', String(isOpen));
            mobMenu.setAttribute('aria-hidden', String(!isOpen));
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        document.addEventListener('click', (e) => {
            if (!burger.contains(e.target) && !mobMenu.contains(e.target)) {
                closeMenu();
            }
        });

        /* Fermer en cliquant un lien dans le menu */
        mobMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', closeMenu);
        });

        /* Fermer si on passe en desktop */
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) closeMenu();
        });
    })();

    /* ── Thème ── */
    const THEMES = ['light', 'hogwarts'];
    const ICONS  = { light: '☀️', hogwarts: '🌙' };

    function applyTheme(t) {
        document.documentElement.setAttribute('data-theme', t);
        localStorage.setItem('mg-theme', t);
        const btn = document.getElementById('themeToggle');
        if (btn) btn.textContent = ICONS[t] || '☀️';
        /* Charger les fonts si ce thème ne l'a pas encore fait */
        if (window._mgFontLoaded !== t) {
            window._mgFontLoaded = t;
            var f = 'family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Outfit:wght@300;400;500;600';
            if (t === 'light')    f += '&family=Unbounded:wght@300;400;600;700;900&family=Sora:wght@300;400;500;600';
            if (t === 'hogwarts') f += '&family=Cinzel:wght@400;600;700;800';
            var lk = document.createElement('link'); lk.rel = 'stylesheet';
            lk.href = 'https://fonts.googleapis.com/css2?' + f + '&display=swap';
            document.head.appendChild(lk);
        }
    }

    /* Initialisation — migration ancien 'dark' → 'hogwarts' */
    const raw   = localStorage.getItem('mg-theme') || 'light';
    const saved = raw === 'dark' ? 'hogwarts' : raw;
    applyTheme(saved);

    /* Cycle au clic : clair ↔ hogwarts */
    const toggleBtn = document.getElementById('themeToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const cur  = document.documentElement.getAttribute('data-theme') || 'light';
            const next = THEMES[(THEMES.indexOf(cur) + 1) % THEMES.length];
            applyTheme(next);
        });
    }

    /* ── Dropdown Notifications ── */
    @auth
    (function () {
        const toggle   = document.getElementById('mgNotifToggle');
        const dropdown = document.getElementById('mgNotifDropdown');
        const dot      = document.getElementById('mgNotifDot');
        const list     = document.getElementById('mgNdList');
        const readAll  = document.getElementById('mgNdReadAll');
        if (!toggle || !dropdown) return;

        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        let loaded = false;

        /* ── Positionnement mobile via JS (évite le bug position:fixed + overflow:hidden sur iOS) ── */
        function positionDropdownMobile() {
            if (window.innerWidth > 768) {
                dropdown.style.cssText = '';
                return;
            }
            const rect = toggle.getBoundingClientRect();
            dropdown.style.position = 'fixed';
            dropdown.style.top      = (rect.bottom + 6) + 'px';
            dropdown.style.left     = '10px';
            dropdown.style.right    = '10px';
            dropdown.style.width    = 'auto';
            dropdown.style.maxHeight = '65vh';
            dropdown.style.overflowY = 'auto';
        }

        /* ── Ouvre / ferme ── */
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = dropdown.classList.toggle('open');
            toggle.classList.toggle('active', isOpen);
            if (isOpen) { positionDropdownMobile(); }
            if (isOpen && !loaded) fetchNotifs();
        });
        document.addEventListener('click', (e) => {
            if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                toggle.classList.remove('active');
            }
        });

        /* ── Fetch & render ── */
        async function fetchNotifs() {
            try {
                const res  = await fetch('{{ route('notifications.dropdown') }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                loaded = true;
                dot.style.display = data.unread > 0 ? 'block' : 'none';
                renderList(data.items);
            } catch (e) {
                list.innerHTML = '<div class="mg-nd-empty"><div class="mg-nd-empty-icon">⚠️</div><div class="mg-nd-empty-text">Erreur de chargement</div></div>';
            }
        }

        function renderList(items) {
            if (!items.length) {
                list.innerHTML = '<div class="mg-nd-empty"><div class="mg-nd-empty-icon">🔔</div><div class="mg-nd-empty-text">Aucune notification pour l\'instant</div></div>';
                return;
            }
            list.innerHTML = items.map(n => {
                const isUnread = !n.read_at;
                const target   = n.post_id ? `/posts/${n.post_id}` : `/@${n.username}`;
                const avatarEl = n.avatar
                    ? `<img src="/storage/${n.avatar}" alt="">`
                    : `<span>${(n.name || '?')[0].toUpperCase()}</span>`;
                const icons    = { follow: '👤', like: '❤️', comment: '💬' };
                const typeIcon = icons[n.type] || '🔔';

                let text = '';
                if (n.type === 'follow')        text = `<strong>${esc(n.name)}</strong> a commencé à te suivre`;
                else if (n.type === 'like')     text = `<strong>${esc(n.name)}</strong> a aimé ta publication`;
                else if (n.type === 'comment')  text = `<strong>${esc(n.name)}</strong> a commenté ta publication`;
                else                            text = 'Nouvelle notification';

                return `<a href="${target}" class="mg-nd-item${isUnread ? ' unread' : ''}" data-id="${n.id}">
                    <div class="mg-nd-avi">
                        ${avatarEl}
                        <div class="mg-nd-type-icon ${n.type}">${typeIcon}</div>
                    </div>
                    <div class="mg-nd-body">
                        <div class="mg-nd-text">${text}</div>
                        <div class="mg-nd-ago">${esc(n.ago)}</div>
                    </div>
                </a>`;
            }).join('');

            /* marquer comme lu au clic */
            list.querySelectorAll('.mg-nd-item.unread').forEach(el => {
                el.addEventListener('click', () => {
                    fetch(`/notifications/${el.dataset.id}/read`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                    });
                    el.classList.remove('unread');
                });
            });
        }

        function esc(s) {
            if (!s) return '';
            return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        /* ── Marquer tout comme lu ── */
        if (readAll) {
            readAll.addEventListener('click', async () => {
                await fetch('{{ route('notifications.read-all') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                });
                list.querySelectorAll('.mg-nd-item.unread').forEach(el => el.classList.remove('unread'));
                dot.style.display = 'none';
            });
        }

        /* ── Badge messages non-lus ── */
        (function () {
            const msgDot = document.getElementById('mgMsgDot');
            if (!msgDot) return;
            async function pollMsgBadge() {
                try {
                    const res  = await fetch('{{ route('messages.unread-count') }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    msgDot.style.display = (data.count > 0) ? 'block' : 'none';
                } catch (e) {}
            }
            pollMsgBadge();
            setInterval(pollMsgBadge, 30000);
        })();

        /* ── Polling badge toutes les 30s ── */
        async function pollBadge() {
            try {
                const res  = await fetch('{{ route('notifications.unread-count') }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                dot.style.display = data.count > 0 ? 'block' : 'none';
                /* recharge si le dropdown est ouvert */
                if (dropdown.classList.contains('open')) fetchNotifs();
            } catch (e) {}
        }
        pollBadge();
        setInterval(pollBadge, 30000);
    })();
    @endauth

    /* ── Web Push : bannière cliquable (le navigateur exige un geste utilisateur) ── */
    @auth
    (function () {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;
        if (Notification.permission === 'denied') return; // l'user a déjà refusé

        const VAPID_PUBLIC_KEY = '{{ config('services.vapid.public_key') }}';
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        function urlBase64ToUint8Array(b64) {
            const pad = '='.repeat((4 - b64.length % 4) % 4);
            const raw = atob((b64 + pad).replace(/-/g, '+').replace(/_/g, '/'));
            return Uint8Array.from([...raw].map(c => c.charCodeAt(0)));
        }

        async function doSubscribe() {
            try {
                const reg = await navigator.serviceWorker.ready;
                const existing = await reg.pushManager.getSubscription();
                if (existing) { hideBanner(); return; }

                const perm = await Notification.requestPermission();
                if (perm !== 'granted') { hideBanner(); return; }

                const sub  = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
                });
                const key  = sub.getKey('p256dh');
                const auth = sub.getKey('auth');

                await fetch('{{ route('push.subscribe') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({
                        endpoint:  sub.endpoint,
                        publicKey: btoa(String.fromCharCode(...new Uint8Array(key))),
                        authToken: btoa(String.fromCharCode(...new Uint8Array(auth))),
                    }),
                });
                hideBanner();
            } catch (e) { console.warn('WebPush:', e); hideBanner(); }
        }

        function hideBanner() {
            const b = document.getElementById('mgPushBanner');
            if (b) b.remove();
            localStorage.setItem('mg-push-dismissed', '1');
        }

        async function showBannerIfNeeded() {
            if (localStorage.getItem('mg-push-dismissed')) return;
            const reg = await navigator.serviceWorker.ready;
            const existing = await reg.pushManager.getSubscription();
            if (existing) return; // déjà abonné

            const banner = document.createElement('div');
            banner.id = 'mgPushBanner';
            banner.innerHTML = `
                <span>🔔 Reçois les notifications même hors de l'app</span>
                <button id="mgPushAccept">Activer</button>
                <button id="mgPushDismiss">✕</button>
            `;
            banner.style.cssText = `
                position:fixed;bottom:calc(env(safe-area-inset-bottom, 0px) + 72px);
                left:50%;transform:translateX(-50%);
                display:flex;align-items:center;gap:10px;
                background:var(--bg-card,#1c1810);border:1px solid var(--terra,#C8522A);
                color:var(--text,#f0e8d8);border-radius:12px;padding:10px 16px;
                font-size:.82rem;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,.4);
                white-space:nowrap;max-width:calc(100vw - 32px);
            `;
            banner.querySelector('#mgPushAccept').style.cssText =
                'background:var(--terra,#C8522A);color:#fff;border:none;border-radius:8px;padding:5px 12px;cursor:pointer;font-size:.82rem;';
            banner.querySelector('#mgPushDismiss').style.cssText =
                'background:none;border:none;color:var(--text-muted,#999);cursor:pointer;font-size:1rem;padding:2px 4px;';

            banner.querySelector('#mgPushAccept').addEventListener('click', doSubscribe);
            banner.querySelector('#mgPushDismiss').addEventListener('click', hideBanner);
            document.body.appendChild(banner);
        }

        window.addEventListener('load', () => setTimeout(showBannerIfNeeded, 4000));
    })();
    @endauth

})();
</script>

@stack('scripts')

<script>
/* ══════════════════════════════════════════
   PROTECTION CONTENU CRÉATEURS
   Empêche le téléchargement des images/vidéos/audio
══════════════════════════════════════════ */
(function () {
    function protect(el) {
        if (el.dataset.prot) return;
        el.dataset.prot = '1';

        // Bloquer le menu contextuel (clic droit)
        el.addEventListener('contextmenu', e => e.preventDefault());

        if (el.tagName === 'IMG') {
            // Bloquer le drag-and-drop
            el.addEventListener('dragstart', e => e.preventDefault());
            // Attribut HTML natif
            el.setAttribute('draggable', 'false');
        }

        if (el.tagName === 'VIDEO' || el.tagName === 'AUDIO') {
            // Cacher le bouton télécharger natif des navigateurs
            el.setAttribute('controlsList', 'nodownload');
            el.setAttribute('oncontextmenu', 'return false');
        }
    }

    function protectAll() {
        document.querySelectorAll('img, video, audio').forEach(protect);
    }

    // Protéger les éléments existants
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', protectAll);
    } else {
        protectAll();
    }

    // Protéger les éléments ajoutés dynamiquement (stories, commentaires, etc.)
    const obs = new MutationObserver(mutations => {
        mutations.forEach(m => {
            m.addedNodes.forEach(node => {
                if (node.nodeType !== 1) return;
                if (['IMG','VIDEO','AUDIO'].includes(node.tagName)) protect(node);
                node.querySelectorAll?.('img,video,audio').forEach(protect);
            });
        });
    });
    obs.observe(document.documentElement, { childList: true, subtree: true });

    // Bloquer Ctrl+S (Enregistrer la page)
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
            e.preventDefault();
        }
    });
})();
</script>
<script>
/* ── PWA : Bannière d'installation (Android + iOS) ── */
(function () {
    var forceShow = location.search.includes('pwa-test');
    if (!forceShow) {
        // Déjà installé (mode standalone) → rien à faire
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) return;
        // Déjà refusé
        if (localStorage.getItem('mg-install-dismissed')) return;
    }
    // En mode test : reset le flag pour pouvoir re-tester
    if (forceShow) localStorage.removeItem('mg-install-dismissed');

    var isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent) && !window.MSStream;
    var deferredPrompt = null;

    // Capture beforeinstallprompt dès qu'il se déclenche (Chrome mobile/Android)
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
    });

    /* ── Styles communs de la bannière ── */
    var BANNER_CSS =
        'position:fixed;' +
        'top:calc(env(safe-area-inset-top,0px) + 64px);' +   /* juste sous la nav */
        'left:0;right:0;' +
        'display:flex;align-items:center;gap:10px;' +
        'background:var(--bg-card,#1c1810);' +
        'border-bottom:2px solid var(--terra,#C8522A);' +
        'color:var(--text,#f0e8d8);padding:10px 16px;' +
        'font-size:.82rem;z-index:9998;box-shadow:0 4px 20px rgba(0,0,0,.5);' +
        'box-sizing:border-box;';

    var BTN_CSS =
        'flex-shrink:0;background:var(--terra,#C8522A);color:#fff;border:none;' +
        'border-radius:8px;padding:6px 14px;cursor:pointer;font-size:.82rem;font-weight:700;white-space:nowrap;';
    var CLOSE_CSS =
        'flex-shrink:0;background:none;border:none;color:var(--text-muted,#aaa);' +
        'cursor:pointer;font-size:1.2rem;padding:2px 8px;line-height:1;margin-left:auto;';

    function dismiss() {
        var b = document.getElementById('mgInstallBanner');
        if (b) b.remove();
        localStorage.setItem('mg-install-dismissed', '1');
    }

    function showBanner() {
        if (document.getElementById('mgInstallBanner')) return;
        var banner = document.createElement('div');
        banner.id = 'mgInstallBanner';

        if (isIOS) {
            /* ══ CAS iOS : instructions manuelles ══ */
            banner.innerHTML =
                '<span style="flex:1">📲 Installe l\'app : <strong>⬆️ Partager</strong> → <strong>"Sur l\'écran d\'accueil"</strong></span>' +
                '<button id="mgInstallX" aria-label="Fermer">✕</button>';
        } else {
            /* ══ CAS Chrome / Android / Desktop ══ */
            banner.innerHTML =
                '<span style="flex:1">📲 Installe MelanoGeek sur ton appareil</span>' +
                '<button id="mgInstallBtn">Installer</button>' +
                '<button id="mgInstallX" aria-label="Fermer">✕</button>';
        }

        banner.style.cssText = BANNER_CSS;
        if (banner.querySelector('#mgInstallBtn')) banner.querySelector('#mgInstallBtn').style.cssText = BTN_CSS;
        banner.querySelector('#mgInstallX').style.cssText = CLOSE_CSS;

        if (banner.querySelector('#mgInstallBtn')) {
            banner.querySelector('#mgInstallBtn').addEventListener('click', async function () {
                if (deferredPrompt) {
                    // Chrome a fourni le prompt natif → on l'utilise
                    banner.remove();
                    deferredPrompt.prompt();
                    var result = await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    if (result.outcome === 'accepted') localStorage.setItem('mg-install-dismissed', '1');
                } else {
                    // Fallback : icône ⊕ dans la barre d'adresse (Chrome desktop)
                    banner.querySelector('span').textContent = '👆 Cherche l\'icône ⊕ dans la barre d\'adresse pour installer l\'app';
                    banner.querySelector('#mgInstallBtn').remove();
                }
            });
        }

        banner.querySelector('#mgInstallX').addEventListener('click', dismiss);
        document.body.appendChild(banner);
    }

    // Afficher la bannière après 1 seconde dans tous les cas
    setTimeout(showBanner, 1000);

    // Nettoyage si installation confirmée via une autre méthode
    window.addEventListener('appinstalled', dismiss);
})();
</script>
<script>
/* ── Service Worker : enregistrement + détection de mise à jour ── */
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js').then(function (reg) {

            /* Affiche un toast quand une nouvelle version est prête */
            function showUpdateToast(sw) {
                if (document.getElementById('mgUpdateToast')) return;
                var toast = document.createElement('div');
                toast.id = 'mgUpdateToast';
                toast.innerHTML =
                    '<span>✨ Mise à jour disponible</span>' +
                    '<button id="mgUpdateBtn">Actualiser</button>';
                toast.style.cssText =
                    'position:fixed;bottom:calc(env(safe-area-inset-bottom,0px) + 72px);' +
                    'left:50%;transform:translateX(-50%);' +
                    'display:flex;align-items:center;gap:12px;' +
                    'background:var(--bg-card,#1c1810);border:1px solid var(--gold,#D4A843);' +
                    'color:var(--text,#f0e8d8);border-radius:12px;padding:10px 18px;' +
                    'font-size:.82rem;z-index:9999;box-shadow:0 4px 24px rgba(0,0,0,.55);' +
                    'white-space:nowrap;max-width:calc(100vw - 32px);';
                toast.querySelector('#mgUpdateBtn').style.cssText =
                    'background:var(--gold,#D4A843);color:#1a1208;border:none;' +
                    'border-radius:8px;padding:6px 14px;cursor:pointer;font-weight:700;font-size:.82rem;';
                toast.querySelector('#mgUpdateBtn').addEventListener('click', function () {
                    sw.postMessage({ type: 'SKIP_WAITING' });
                });
                document.body.appendChild(toast);
            }

            /* SW déjà en attente au moment du chargement de la page */
            if (reg.waiting) showUpdateToast(reg.waiting);

            /* Nouveau SW qui s'installe en arrière-plan */
            reg.addEventListener('updatefound', function () {
                var newSW = reg.installing;
                newSW.addEventListener('statechange', function () {
                    if (newSW.state === 'installed' && navigator.serviceWorker.controller) {
                        showUpdateToast(newSW);
                    }
                });
            });

            /* Quand le nouveau SW prend le contrôle → rechargement automatique */
            var refreshing = false;
            navigator.serviceWorker.addEventListener('controllerchange', function () {
                if (!refreshing) { refreshing = true; location.reload(); }
            });

        }).catch(function () {});
    });
}
</script>
<script>
// Fix iOS PWA : les inputs ne reçoivent pas le focus au tap en mode standalone
(function () {
    if (!window.navigator.standalone) return;
    document.addEventListener('touchend', function (e) {
        var el = e.target;
        if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.tagName === 'SELECT') {
            e.preventDefault();
            setTimeout(function () { el.focus(); }, 10);
        }
    }, false);
})();
</script>
</body>
</html>
