<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>MelanoGeek — Le Réseau des Créateurs Africains</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<meta name="theme-color" content="#7C3AED">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600&family=Unbounded:wght@700;900&family=DM+Mono&display=swap">
<style>
/* ══ RESET & BASE ══ */
.mg-landing { --ink: #f1f5f9; --ink2: rgba(241,245,249,.5); --ink3: rgba(241,245,249,.07); --bg: #070a14; --card: rgba(255,255,255,.03); --violet: #7C3AED; --indigo: #4F46E5; --gold: #D4A843; --glow: rgba(124,58,237,.18); font-family:'Sora',sans-serif; background: var(--bg); color: var(--ink); overflow-x: hidden; }
.mg-landing *, .mg-landing *::before, .mg-landing *::after { box-sizing: border-box; }

/* ══ NAV ══ */
.mg-nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; padding: 0 2rem; height: 64px; display: flex; align-items: center; justify-content: space-between; background: rgba(7,10,20,.7); backdrop-filter: blur(20px); border-bottom: 1px solid var(--ink3); }
.mg-nav-logo { font-family: 'Unbounded', sans-serif; font-size: .95rem; font-weight: 900; letter-spacing: -.02em; color: var(--ink); text-decoration: none; }
.mg-nav-logo span { background: linear-gradient(135deg, var(--violet), var(--indigo)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.mg-nav-links { display: flex; align-items: center; gap: 2rem; }
.mg-nav-links a { font-size: .75rem; color: var(--ink2); text-decoration: none; transition: color .2s; }
.mg-nav-links a:hover { color: var(--ink); }
.mg-nav-ctas { display: flex; align-items: center; gap: .75rem; }
.mg-btn-ghost { font-family: 'Sora', sans-serif; font-size: .75rem; color: var(--ink2) !important; text-decoration: none; transition: color .2s; }
.mg-btn-ghost:hover { color: var(--ink) !important; }
.mg-btn-primary { font-family: 'Unbounded', sans-serif; font-size: .62rem; font-weight: 700; letter-spacing: .04em; color: #fff !important; background: linear-gradient(135deg, var(--violet), var(--indigo)); padding: 9px 20px; border-radius: 8px; text-decoration: none; transition: opacity .2s, transform .2s; }
.mg-btn-primary:hover { opacity: .88; transform: translateY(-1px); }

/* ══ HERO ══ */
.mg-hero { min-height: 100vh; padding-top: 64px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; position: relative; overflow: hidden; padding-left: 2rem; padding-right: 2rem; }
.mg-hero-glow { position: absolute; top: 20%; left: 50%; transform: translateX(-50%); width: 600px; height: 400px; background: radial-gradient(ellipse at center, rgba(124,58,237,.2) 0%, transparent 70%); pointer-events: none; }
.mg-hero-glow2 { position: absolute; bottom: 10%; left: 20%; width: 300px; height: 300px; background: radial-gradient(ellipse at center, rgba(79,70,229,.12) 0%, transparent 70%); pointer-events: none; }
.mg-hero-grid { position: absolute; inset: 0; background-image: linear-gradient(rgba(241,245,249,.025) 1px, transparent 1px), linear-gradient(90deg, rgba(241,245,249,.025) 1px, transparent 1px); background-size: 64px 64px; pointer-events: none; mask-image: radial-gradient(ellipse at center, black 30%, transparent 80%); }

.mg-hero-pill { display: inline-flex; align-items: center; gap: 8px; background: rgba(124,58,237,.1); border: 1px solid rgba(124,58,237,.25); color: #a78bfa; font-family: 'Unbounded', sans-serif; font-size: .52rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; padding: 5px 14px; border-radius: 100px; margin-bottom: 32px; }
.mg-pill-dot { width: 5px; height: 5px; background: #a78bfa; border-radius: 50%; animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }

.mg-hero-h1 { font-family: 'Unbounded', sans-serif; font-size: clamp(2.5rem, 6vw, 6rem); font-weight: 900; line-height: .95; letter-spacing: -.04em; color: var(--ink); margin-bottom: 28px; position: relative; z-index: 1; }
.mg-hero-h1 .grad { background: linear-gradient(135deg, #a78bfa 0%, #818cf8 50%, #60a5fa 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.mg-hero-h1 .outline { -webkit-text-stroke: 2px rgba(167,139,250,.4); color: transparent; }

.mg-hero-desc { font-size: .95rem; line-height: 1.8; color: var(--ink2); max-width: 540px; margin: 0 auto 44px; font-weight: 300; position: relative; z-index: 1; }

.mg-hero-btns { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; position: relative; z-index: 1; margin-bottom: 64px; }
.mg-btn-xl { font-family: 'Unbounded', sans-serif; font-size: .7rem; font-weight: 700; letter-spacing: .04em; color: #fff !important; background: linear-gradient(135deg, var(--violet), var(--indigo)); padding: 15px 32px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: all .25s; box-shadow: 0 0 40px rgba(124,58,237,.3); }
.mg-btn-xl:hover { transform: translateY(-2px); box-shadow: 0 0 60px rgba(124,58,237,.45); }
.mg-btn-xl-ghost { font-family: 'Sora', sans-serif; font-size: .82rem; color: var(--ink2) !important; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: color .2s; padding: 15px 24px; border: 1px solid var(--ink3); border-radius: 10px; }
.mg-btn-xl-ghost:hover { color: var(--ink) !important; border-color: rgba(241,245,249,.15); }

/* Hero UI preview */
.mg-hero-ui { position: relative; z-index: 1; max-width: 780px; margin: 0 auto; }
.mg-ui-frame { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); border-radius: 16px; padding: 20px; box-shadow: 0 32px 80px rgba(0,0,0,.5), 0 0 0 1px rgba(124,58,237,.1); overflow: hidden; }
.mg-ui-bar { display: flex; align-items: center; gap: 6px; margin-bottom: 16px; }
.mg-ui-dot { width: 10px; height: 10px; border-radius: 50%; }
.mg-ui-dot:nth-child(1) { background: #ff5f57; }
.mg-ui-dot:nth-child(2) { background: #febc2e; }
.mg-ui-dot:nth-child(3) { background: #28c840; }
.mg-ui-posts { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.mg-ui-post { border-radius: 10px; overflow: hidden; aspect-ratio: 4/3; position: relative; }
.mg-ui-post:first-child { grid-column: 1 / 3; aspect-ratio: 2/1; }
.mg-ui-post-bg { width: 100%; height: 100%; }
.mg-ui-post-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 10px; background: linear-gradient(to top, rgba(0,0,0,.8), transparent); }
.mg-ui-post-name { font-family: 'Unbounded', sans-serif; font-size: .45rem; font-weight: 700; color: rgba(255,255,255,.9); }
.mg-ui-post-stat { font-family: 'Sora', sans-serif; font-size: .4rem; color: rgba(255,255,255,.55); }

/* ══ STATS ══ */
.mg-stats { display: flex; border-top: 1px solid var(--ink3); border-bottom: 1px solid var(--ink3); }
.mg-stat { flex: 1; text-align: center; padding: 28px 20px; border-right: 1px solid var(--ink3); position: relative; }
.mg-stat:last-child { border-right: none; }
.mg-stat::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 100%, rgba(124,58,237,.07) 0%, transparent 70%); pointer-events: none; }
.mg-stat-n { font-family: 'Unbounded', sans-serif; font-size: 1.8rem; font-weight: 900; letter-spacing: -.04em; background: linear-gradient(135deg, var(--ink), rgba(241,245,249,.7)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.mg-stat-l { font-family: 'Sora', sans-serif; font-size: .62rem; color: var(--ink2); text-transform: uppercase; letter-spacing: .08em; margin-top: 4px; }

/* ══ SECTIONS ══ */
.mg-section { padding: 96px 2rem; position: relative; }
.mg-section-inner { max-width: 1100px; margin: 0 auto; }
.mg-section-eye { font-family: 'Unbounded', sans-serif; font-size: .5rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #a78bfa; margin-bottom: 14px; display: flex; align-items: center; gap: 10px; }
.mg-section-eye::before { content: ''; width: 24px; height: 1px; background: #a78bfa; }
.mg-section-h2 { font-family: 'Unbounded', sans-serif; font-size: clamp(1.6rem, 3.5vw, 3rem); font-weight: 900; letter-spacing: -.03em; line-height: 1.1; color: var(--ink); margin-bottom: 16px; }
.mg-section-h2 .grad { background: linear-gradient(135deg, #a78bfa, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.mg-section-sub { font-size: .88rem; line-height: 1.8; color: var(--ink2); font-weight: 300; max-width: 520px; }

/* ══ FEATURES GRID ══ */
.mg-features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: var(--ink3); border: 1px solid var(--ink3); border-radius: 16px; overflow: hidden; margin-top: 64px; }
.mg-feat { background: var(--bg); padding: 36px 32px; transition: background .3s; position: relative; }
.mg-feat:hover { background: rgba(124,58,237,.06); }
.mg-feat-ico { width: 44px; height: 44px; border-radius: 12px; background: rgba(124,58,237,.1); border: 1px solid rgba(124,58,237,.2); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 20px; }
.mg-feat-title { font-family: 'Unbounded', sans-serif; font-size: .78rem; font-weight: 700; color: var(--ink); margin-bottom: 8px; letter-spacing: -.01em; }
.mg-feat-desc { font-size: .78rem; line-height: 1.7; color: var(--ink2); font-weight: 300; }
.mg-feat-tag { display: inline-block; margin-top: 14px; font-family: 'Unbounded', sans-serif; font-size: .42rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #a78bfa; padding: 3px 8px; background: rgba(124,58,237,.1); border: 1px solid rgba(124,58,237,.2); border-radius: 4px; }

/* ══ HOW IT WORKS ══ */
.mg-how { background: rgba(255,255,255,.015); border-top: 1px solid var(--ink3); border-bottom: 1px solid var(--ink3); }
.mg-how-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.mg-steps { display: flex; flex-direction: column; gap: 0; }
.mg-step { display: flex; gap: 20px; padding: 28px 0; border-bottom: 1px solid var(--ink3); position: relative; cursor: pointer; transition: all .2s; }
.mg-step:last-child { border-bottom: none; }
.mg-step-num { width: 36px; height: 36px; border-radius: 10px; background: rgba(124,58,237,.1); border: 1px solid rgba(124,58,237,.2); display: flex; align-items: center; justify-content: center; font-family: 'Unbounded', sans-serif; font-size: .6rem; font-weight: 700; color: #a78bfa; flex-shrink: 0; }
.mg-step-title { font-family: 'Unbounded', sans-serif; font-size: .78rem; font-weight: 700; color: var(--ink); margin-bottom: 6px; }
.mg-step-desc { font-size: .78rem; line-height: 1.7; color: var(--ink2); font-weight: 300; }
.mg-how-visual { background: rgba(255,255,255,.03); border: 1px solid var(--ink3); border-radius: 20px; padding: 32px; min-height: 360px; display: flex; flex-direction: column; gap: 12px; position: relative; overflow: hidden; }
.mg-how-visual::before { content: ''; position: absolute; top: -80px; right: -80px; width: 240px; height: 240px; background: radial-gradient(ellipse at center, rgba(124,58,237,.15) 0%, transparent 70%); }
.mg-hv-row { display: flex; align-items: center; gap: 12px; padding: 14px 16px; background: rgba(255,255,255,.03); border: 1px solid var(--ink3); border-radius: 10px; }
.mg-hv-ico { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0; }
.mg-hv-txt { flex: 1; }
.mg-hv-name { font-family: 'Unbounded', sans-serif; font-size: .62rem; font-weight: 700; color: var(--ink); }
.mg-hv-sub { font-size: .6rem; color: var(--ink2); margin-top: 2px; }
.mg-hv-badge { font-family: 'Unbounded', sans-serif; font-size: .42rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: 3px 8px; border-radius: 4px; }
.mg-hv-badge.v { background: rgba(124,58,237,.12); border: 1px solid rgba(124,58,237,.25); color: #a78bfa; }
.mg-hv-badge.g { background: rgba(34,197,94,.08); border: 1px solid rgba(34,197,94,.2); color: #86efac; }

/* ══ CREATORS ══ */
.mg-creators-row { display: flex; gap: 14px; overflow-x: auto; padding-bottom: 14px; scroll-snap-type: x mandatory; -ms-overflow-style: none; scrollbar-width: none; margin-top: 48px; }
.mg-creators-row::-webkit-scrollbar { display: none; }
.mg-cr-card { flex: 0 0 200px; scroll-snap-align: start; background: var(--card); border: 1px solid var(--ink3); border-radius: 16px; padding: 20px 16px; transition: all .3s; text-decoration: none; color: inherit; display: block; position: relative; overflow: hidden; }
.mg-cr-card::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 0%, rgba(124,58,237,.08) 0%, transparent 70%); opacity: 0; transition: opacity .3s; }
.mg-cr-card:hover { border-color: rgba(124,58,237,.3); transform: translateY(-4px); }
.mg-cr-card:hover::before { opacity: 1; }
.mg-cr-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 14px; }
.mg-cr-av { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; overflow: hidden; }
.mg-cr-av img { width: 100%; height: 100%; object-fit: cover; }
.mg-cr-bdg { font-family: 'Unbounded', sans-serif; font-size: .44rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: 3px 7px; border-radius: 4px; }
.mg-cr-bdg.v { background: rgba(124,58,237,.12); border: 1px solid rgba(124,58,237,.25); color: #a78bfa; }
.mg-cr-bdg.n { background: rgba(34,197,94,.08); border: 1px solid rgba(34,197,94,.2); color: #86efac; }
.mg-cr-name { font-family: 'Unbounded', sans-serif; font-size: .74rem; font-weight: 700; color: var(--ink); margin-bottom: 3px; }
.mg-cr-niche { font-size: .65rem; color: var(--ink2); margin-bottom: 14px; }
.mg-cr-stats { display: flex; gap: 14px; }
.mg-cr-sn { font-family: 'Unbounded', sans-serif; font-size: .78rem; font-weight: 700; color: var(--ink); }
.mg-cr-sl { font-size: .54rem; color: var(--ink2); text-transform: uppercase; letter-spacing: .05em; margin-top: 1px; }

/* ══ VALEURS ══ */
.mg-vals { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-top: 48px; }
.mg-val { background: var(--card); border: 1px solid var(--ink3); border-radius: 16px; padding: 28px 24px; transition: all .3s; position: relative; overflow: hidden; }
.mg-val::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, var(--violet), var(--indigo)); transform: scaleX(0); transition: transform .3s; transform-origin: left; }
.mg-val:hover { border-color: rgba(124,58,237,.2); }
.mg-val:hover::after { transform: scaleX(1); }
.mg-val-ico { font-size: 1.5rem; margin-bottom: 14px; }
.mg-val-title { font-family: 'Unbounded', sans-serif; font-size: .72rem; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
.mg-val-desc { font-size: .76rem; line-height: 1.7; color: var(--ink2); font-weight: 300; }

/* ══ PRICING ══ */
.mg-pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 64px; max-width: 920px; }
.mg-plan { background: var(--card); border: 1px solid var(--ink3); border-radius: 20px; padding: 32px 28px; position: relative; transition: all .3s; }
.mg-plan:hover { border-color: rgba(124,58,237,.25); transform: translateY(-4px); }
.mg-plan.hot { background: linear-gradient(135deg, rgba(124,58,237,.12), rgba(79,70,229,.08)); border-color: rgba(124,58,237,.35); }
.mg-plan.hot:hover { border-color: rgba(124,58,237,.5); }
.mg-plan-badge { position: absolute; top: -10px; left: 24px; background: linear-gradient(135deg, var(--violet), var(--indigo)); color: white !important; font-family: 'Unbounded', sans-serif; font-size: .48rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: 3px 10px; border-radius: 100px; }
.mg-plan-flag { font-size: 1.6rem; margin-bottom: 14px; }
.mg-plan-name { font-family: 'Unbounded', sans-serif; font-size: .58rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--ink2); margin-bottom: 20px; }
.mg-plan-price { font-family: 'Unbounded', sans-serif; font-size: 2.2rem; font-weight: 900; letter-spacing: -.04em; color: var(--ink); line-height: 1; }
.mg-plan-price.free { background: linear-gradient(135deg, #86efac, #4ade80); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 1.5rem; }
.mg-plan-price sub { font-family: 'Sora', sans-serif; font-size: .58rem; font-weight: 400; color: var(--ink2); vertical-align: bottom; margin-left: 2px; }
.mg-plan-period { font-size: .65rem; color: var(--ink2); margin-top: 4px; margin-bottom: 24px; }
.mg-plan-ul { list-style: none; display: flex; flex-direction: column; gap: 9px; margin-bottom: 28px; padding: 0; }
.mg-plan-ul li { font-size: .76rem; color: var(--ink2); display: flex; align-items: center; gap: 9px; }
.mg-plan-ul li::before { content: ''; width: 16px; height: 16px; border-radius: 50%; background: rgba(124,58,237,.15); border: 1px solid rgba(124,58,237,.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; background-image: url("data:image/svg+xml,%3Csvg width='10' height='8' viewBox='0 0 10 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 4L3.5 6.5L9 1' stroke='%23a78bfa' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: center; }
.mg-plan-btn { display: block; width: 100%; text-align: center; padding: 12px; border-radius: 10px; font-family: 'Unbounded', sans-serif; font-size: .62rem; font-weight: 700; letter-spacing: .03em; transition: all .2s; text-decoration: none; cursor: pointer; }
.mg-plan-btn.outline { background: none; border: 1px solid var(--ink3); color: var(--ink2) !important; }
.mg-plan-btn.outline:hover { border-color: rgba(241,245,249,.2); color: var(--ink) !important; }
.mg-plan-btn.fill { background: linear-gradient(135deg, var(--violet), var(--indigo)); color: #fff !important; border: none; box-shadow: 0 8px 24px rgba(124,58,237,.3); }
.mg-plan-btn.fill:hover { box-shadow: 0 12px 32px rgba(124,58,237,.45); transform: translateY(-1px); }

/* ══ CTA FINALE ══ */
.mg-cta { padding: 120px 2rem; text-align: center; position: relative; overflow: hidden; }
.mg-cta::before { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 800px; height: 400px; background: radial-gradient(ellipse at center, rgba(124,58,237,.15) 0%, transparent 65%); pointer-events: none; }
.mg-cta-inner { position: relative; z-index: 1; max-width: 640px; margin: 0 auto; }
.mg-cta h2 { font-family: 'Unbounded', sans-serif; font-size: clamp(1.8rem, 4vw, 3.5rem); font-weight: 900; letter-spacing: -.04em; line-height: 1.0; color: var(--ink); margin-bottom: 20px; }
.mg-cta h2 .grad { background: linear-gradient(135deg, #a78bfa, #818cf8, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.mg-cta-sub { font-size: .88rem; line-height: 1.8; color: var(--ink2); font-weight: 300; margin-bottom: 40px; }
.mg-cta-btns { display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; }
.mg-cta-note { font-size: .65rem; color: var(--ink2); margin-top: 20px; }

/* ══ FOOTER ══ */
.mg-footer { padding: 48px 2rem; border-top: 1px solid var(--ink3); display: flex; flex-direction: column; align-items: center; gap: 20px; }
.mg-footer-logo { font-family: 'Unbounded', sans-serif; font-size: .9rem; font-weight: 900; letter-spacing: -.02em; color: var(--ink); }
.mg-footer-logo span { background: linear-gradient(135deg, var(--violet), var(--indigo)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.mg-footer-links { display: flex; gap: 2rem; }
.mg-footer-links a { font-size: .72rem; color: var(--ink2); text-decoration: none; transition: color .2s; }
.mg-footer-links a:hover { color: var(--ink); }
.mg-footer-copy { font-size: .65rem; color: rgba(241,245,249,.25); }

/* ══ ANIMATIONS ══ */
.reveal { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
.reveal.visible { opacity: 1; transform: translateY(0); }
</style>
</style>
</head>
<body>
<div class="mg-landing">

{{-- NAV --}}
<nav class="mg-nav">
    <a href="{{ url('/') }}" class="mg-nav-logo">Melano<span>Geek</span></a>
    <div class="mg-nav-links">
        <a href="#features">Fonctionnalités</a>
        <a href="#creators">Créateurs</a>
        <a href="#tarifs">Tarifs</a>
    </div>
    <div class="mg-nav-ctas">
        @auth
            <a href="{{ route('dashboard') }}" class="mg-btn-ghost">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="mg-btn-ghost">Connexion</a>
            <a href="{{ route('register') }}" class="mg-btn-primary">Commencer →</a>
        @endauth
    </div>
</nav>

{{-- HERO --}}
<section class="mg-hero">
    <div class="mg-hero-grid"></div>
    <div class="mg-hero-glow"></div>
    <div class="mg-hero-glow2"></div>

    <div class="mg-hero-pill">
        <span class="mg-pill-dot"></span>
        🇸🇳 Made in Dakar
    </div>

    <h1 class="mg-hero-h1">
        Le réseau des<br>
        <span class="grad">créateurs africains.</span>
    </h1>

    <p class="mg-hero-desc">
        Publie, monétise et connecte-toi avec une communauté qui te comprend vraiment.
        Pas d'algorithme punitif — juste de la création pure.
    </p>

    <div class="mg-hero-btns">
        <a href="{{ route('register') }}" class="mg-btn-xl">
            Créer mon profil gratuitement
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </a>
        <a href="{{ route('register') }}" class="mg-btn-xl-ghost">
            Explorer les créateurs →
        </a>
    </div>

    <div class="mg-hero-ui">
        <div class="mg-ui-frame">
            <div class="mg-ui-bar">
                <div class="mg-ui-dot"></div>
                <div class="mg-ui-dot"></div>
                <div class="mg-ui-dot"></div>
            </div>
            <div class="mg-ui-posts">
                <div class="mg-ui-post" style="background: linear-gradient(135deg, #1a0533, #4c1d95, #7c3aed);">
                    <div class="mg-ui-post-overlay">
                        <div class="mg-ui-post-name">🎨 Kofi_Design</div>
                        <div class="mg-ui-post-stat">2.4k abonnés · 47 posts</div>
                    </div>
                </div>
                <div class="mg-ui-post" style="background: linear-gradient(135deg, #0f172a, #1e3a5f, #2563eb);">
                    <div class="mg-ui-post-overlay">
                        <div class="mg-ui-post-name">🎵 AmaraSound</div>
                        <div class="mg-ui-post-stat">5.1k abonnés</div>
                    </div>
                </div>
                <div class="mg-ui-post" style="background: linear-gradient(135deg, #0c1a0c, #14532d, #16a34a);">
                    <div class="mg-ui-post-overlay">
                        <div class="mg-ui-post-name">📸 DakarLens</div>
                        <div class="mg-ui-post-stat">3.8k abonnés</div>
                    </div>
                </div>
                <div class="mg-ui-post" style="background: linear-gradient(135deg, #1c0a00, #7c2d12, #c2410c);">
                    <div class="mg-ui-post-overlay">
                        <div class="mg-ui-post-name">✍️ DiawStories</div>
                        <div class="mg-ui-post-stat">1.9k abonnés</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<div class="mg-stats">
    @php
        $totalUsers = \App\Models\User::count();
        $totalPosts = \App\Models\Post::count();
        $totalCreators = \App\Models\User::where('role','creator')->count();
        $fmt = fn($n) => $n >= 1000 ? round($n/1000,1).'k' : $n;
    @endphp
    <div class="mg-stat">
        <div class="mg-stat-n">{{ $fmt($totalUsers ?: 500) }}+</div>
        <div class="mg-stat-l">Membres</div>
    </div>
    <div class="mg-stat">
        <div class="mg-stat-n">{{ $fmt($totalCreators ?: 120) }}+</div>
        <div class="mg-stat-l">Créateurs</div>
    </div>
    <div class="mg-stat">
        <div class="mg-stat-n">{{ $fmt($totalPosts ?: 2400) }}+</div>
        <div class="mg-stat-l">Publications</div>
    </div>
    <div class="mg-stat">
        <div class="mg-stat-n">100%</div>
        <div class="mg-stat-l">Africain</div>
    </div>
</div>

{{-- FEATURES --}}
<section class="mg-section reveal" id="features">
    <div class="mg-section-inner">
        <div class="mg-section-eye">Fonctionnalités</div>
        <h2 class="mg-section-h2">Tout ce dont un créateur<br>a besoin pour <span class="grad">rayonner.</span></h2>
        <p class="mg-section-sub">Un seul endroit pour créer, partager, monétiser et connecter avec ta communauté.</p>

        <div class="mg-features-grid">
            <div class="mg-feat">
                <div class="mg-feat-ico">📰</div>
                <div class="mg-feat-title">Feed Personnalisé</div>
                <div class="mg-feat-desc">Un algorithme qui favorise les créateurs que tu suis. Pas de publicité, pas de manipulation.</div>
                <span class="mg-feat-tag">Chronologique</span>
            </div>
            <div class="mg-feat">
                <div class="mg-feat-ico">🏪</div>
                <div class="mg-feat-title">Marketplace</div>
                <div class="mg-feat-desc">Propose tes services directement à ta communauté. Paiement via Wave et Orange Money.</div>
                <span class="mg-feat-tag">Wave · OM</span>
            </div>
            <div class="mg-feat">
                <div class="mg-feat-ico">📖</div>
                <div class="mg-feat-title">Stories</div>
                <div class="mg-feat-desc">Partage des moments éphémères en 24h. Photo, vidéo, texte — le format que tu veux.</div>
                <span class="mg-feat-tag">24h</span>
            </div>
            <div class="mg-feat">
                <div class="mg-feat-ico">💬</div>
                <div class="mg-feat-title">Messagerie Directe</div>
                <div class="mg-feat-desc">Discute directement avec tes abonnés et tes clients. Simple, rapide, privé.</div>
                <span class="mg-feat-tag">Temps réel</span>
            </div>
            <div class="mg-feat">
                <div class="mg-feat-ico">🎓</div>
                <div class="mg-feat-title">Cours & Formations</div>
                <div class="mg-feat-desc">Crée et vends tes formations en ligne. Partage ton expertise et génère des revenus passifs.</div>
                <span class="mg-feat-tag">Nouveau</span>
            </div>
            <div class="mg-feat">
                <div class="mg-feat-ico">📊</div>
                <div class="mg-feat-title">Analytics Créateur</div>
                <div class="mg-feat-desc">Suis la croissance de ta communauté. Vues, abonnés, revenus — tout en un coup d'œil.</div>
                <span class="mg-feat-tag">Dashboard</span>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="mg-section mg-how reveal">
    <div class="mg-section-inner">
        <div class="mg-how-grid">
            <div>
                <div class="mg-section-eye">Comment ça marche</div>
                <h2 class="mg-section-h2">En ligne en<br><span class="grad">3 minutes.</span></h2>
                <p class="mg-section-sub" style="margin-bottom: 40px;">Pas de formulaire compliqué. Pas de carte bancaire requise pour les Sénégalais.</p>

                <div class="mg-steps">
                    <div class="mg-step">
                        <div class="mg-step-num">01</div>
                        <div>
                            <div class="mg-step-title">Crée ton profil</div>
                            <div class="mg-step-desc">Choisis ton nom, ta niche créative et personnalise ton profil. Gratuit, en 2 minutes.</div>
                        </div>
                    </div>
                    <div class="mg-step">
                        <div class="mg-step-num">02</div>
                        <div>
                            <div class="mg-step-title">Publie ton contenu</div>
                            <div class="mg-step-desc">Posts, stories, vidéos, articles. Ton contenu est vu par ceux qui t'ont choisi.</div>
                        </div>
                    </div>
                    <div class="mg-step">
                        <div class="mg-step-num">03</div>
                        <div>
                            <div class="mg-step-title">Monétise ta créativité</div>
                            <div class="mg-step-desc">Active le marketplace, propose des services, vends des formations. Wave et Orange Money acceptés.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mg-how-visual">
                <div class="mg-hv-row">
                    <div class="mg-hv-ico" style="background: rgba(124,58,237,.12);">🎨</div>
                    <div class="mg-hv-txt">
                        <div class="mg-hv-name">Kofi_Design</div>
                        <div class="mg-hv-sub">Illustrateur · Dakar</div>
                    </div>
                    <span class="mg-hv-badge v">✓ Vérifié</span>
                </div>
                <div class="mg-hv-row">
                    <div class="mg-hv-ico" style="background: rgba(34,197,94,.08);">🎵</div>
                    <div class="mg-hv-txt">
                        <div class="mg-hv-name">AmaraSound</div>
                        <div class="mg-hv-sub">Musicien · Abidjan</div>
                    </div>
                    <span class="mg-hv-badge g">🆕 Nouveau</span>
                </div>
                <div class="mg-hv-row">
                    <div class="mg-hv-ico" style="background: rgba(251,191,36,.08);">📸</div>
                    <div class="mg-hv-txt">
                        <div class="mg-hv-name">DakarLens</div>
                        <div class="mg-hv-sub">Photographe · Dakar</div>
                    </div>
                    <span class="mg-hv-badge v">✓ Vérifié</span>
                </div>
                <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--ink3); display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: .65rem; color: var(--ink2);">Nouveaux créateurs cette semaine</span>
                    <span style="font-family: 'Unbounded', sans-serif; font-size: .75rem; font-weight: 700; color: #86efac;">+24</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CREATORS --}}
@php
    $featuredCreators = \App\Models\User::where('role','creator')->withCount(['followers','posts as published_posts'])->orderByDesc('followers_count')->limit(8)->get();
    $crBgs = ['linear-gradient(135deg,#4c1d95,#7c3aed)','linear-gradient(135deg,#1e3a5f,#2563eb)','linear-gradient(135deg,#14532d,#16a34a)','linear-gradient(135deg,#7c2d12,#c2410c)'];
    $nicheEmojis = ['Artiste'=>'🎨','Musicien'=>'🎵','Photographe'=>'📸','Vidéaste'=>'🎬','Écrivain'=>'✍️','Styliste'=>'👗','Danseur'=>'💃','Cuisinier'=>'🍽️','Podcasteur'=>'🎙️','Illustrateur'=>'✏️','Comédien'=>'🎭','Influenceur'=>'⭐'];
    $fmt = fn($n) => $n >= 1000 ? round($n/1000,1).'k' : $n;
    $demoCreators = [
        ['name'=>'Kofi_Design','niche'=>'Illustrateur','emoji'=>'🎨','ab'=>'2.4k','po'=>47,'b'=>'v','bt'=>'✓ Vérifié','bg'=>$crBgs[0]],
        ['name'=>'AmaraSound','niche'=>'Musicien','emoji'=>'🎵','ab'=>'5.1k','po'=>89,'b'=>'v','bt'=>'✓ Vérifié','bg'=>$crBgs[1]],
        ['name'=>'DakarLens','niche'=>'Photographe','emoji'=>'📸','ab'=>'3.8k','po'=>134,'b'=>'v','bt'=>'✓ Vérifié','bg'=>$crBgs[2]],
        ['name'=>'DiawStories','niche'=>'Écrivain','emoji'=>'✍️','ab'=>'1.9k','po'=>28,'b'=>'n','bt'=>'🆕 Nouveau','bg'=>$crBgs[3]],
        ['name'=>'FatoStyle','niche'=>'Styliste','emoji'=>'👗','ab'=>'4.2k','po'=>67,'b'=>'v','bt'=>'✓ Vérifié','bg'=>$crBgs[0]],
        ['name'=>'AbdouVlog','niche'=>'Vidéaste','emoji'=>'🎬','ab'=>'8.7k','po'=>201,'b'=>'v','bt'=>'✓ Vérifié','bg'=>$crBgs[1]],
    ];
@endphp

<section class="mg-section reveal" id="creators">
    <div class="mg-section-inner">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 48px;">
            <div>
                <div class="mg-section-eye">Nos créateurs</div>
                <h2 class="mg-section-h2" style="margin-bottom: 0;">Ils créent.<br><span class="grad">Tu découvres.</span></h2>
            </div>
            <a href="{{ route('register') }}" style="font-size: .72rem; color: var(--ink2); text-decoration: none; border-bottom: 1px solid var(--ink3); padding-bottom: 2px; transition: all .2s;" onmouseover="this.style.color='#a78bfa';this.style.borderColor='#a78bfa'" onmouseout="this.style.color='';this.style.borderColor=''">Voir tous →</a>
        </div>

        <div class="mg-creators-row">
            @if($featuredCreators->isNotEmpty())
                @foreach($featuredCreators as $i => $cr)
                @php $ne = $nicheEmojis[$cr->niche ?? ''] ?? '🌟'; @endphp
                <a href="{{ route('profile.show', $cr->username) }}" class="mg-cr-card">
                    <div class="mg-cr-top">
                        <div class="mg-cr-av" style="background: {{ $crBgs[$i % 4] }};">
                            @if($cr->avatar)
                                <img src="{{ asset('storage/'.$cr->avatar) }}" alt="">
                            @else
                                {{ mb_strtoupper(mb_substr($cr->username, 0, 1)) }}
                            @endif
                        </div>
                        <span class="mg-cr-bdg {{ $cr->is_verified ? 'v' : 'n' }}">{{ $cr->is_verified ? '✓ Vérifié' : '🆕 Nouveau' }}</span>
                    </div>
                    <div class="mg-cr-name">{{ $cr->username }}</div>
                    <div class="mg-cr-niche">{{ $ne }} {{ $cr->niche ?: 'Créateur' }}</div>
                    <div class="mg-cr-stats">
                        <div><div class="mg-cr-sn">{{ $fmt($cr->followers_count) }}</div><div class="mg-cr-sl">Abonnés</div></div>
                        <div><div class="mg-cr-sn">{{ $cr->published_posts }}</div><div class="mg-cr-sl">Posts</div></div>
                    </div>
                </a>
                @endforeach
            @else
                @foreach($demoCreators as $c)
                <div class="mg-cr-card">
                    <div class="mg-cr-top">
                        <div class="mg-cr-av" style="background: {{ $c['bg'] }};">{{ $c['emoji'] }}</div>
                        <span class="mg-cr-bdg {{ $c['b'] }}">{{ $c['bt'] }}</span>
                    </div>
                    <div class="mg-cr-name">{{ $c['name'] }}</div>
                    <div class="mg-cr-niche">{{ $c['emoji'] }} {{ $c['niche'] }}</div>
                    <div class="mg-cr-stats">
                        <div><div class="mg-cr-sn">{{ $c['ab'] }}</div><div class="mg-cr-sl">Abonnés</div></div>
                        <div><div class="mg-cr-sn">{{ $c['po'] }}</div><div class="mg-cr-sl">Posts</div></div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- VALEURS --}}
<section class="mg-section mg-how reveal">
    <div class="mg-section-inner">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center;">
            <div>
                <div class="mg-section-eye">Notre mission</div>
                <h2 class="mg-section-h2">La créativité africaine mérite une plateforme à sa <span class="grad">hauteur.</span></h2>
                <p class="mg-section-sub">Pas d'algorithme qui punit. Pas de barrières géographiques. Juste de la création pure et une vraie communauté.</p>
            </div>
            <div class="mg-vals">
                @php
                    $vals = [
                        ['🇸🇳','100% Local','Conçu à Dakar, pour les créateurs africains en premier.'],
                        ['🎁','1 mois gratuit',"Essai gratuit d'un mois pour tous les Sénégalais. Sans carte."],
                        ['⚡','Sans censure','Ton contenu est vu par ceux qui t\'ont choisi.'],
                        ['💳','Paiement local','Wave et Orange Money. Pas besoin de carte internationale.'],
                    ];
                @endphp
                @foreach($vals as $v)
                <div class="mg-val">
                    <div class="mg-val-ico">{{ $v[0] }}</div>
                    <div class="mg-val-title">{{ $v[1] }}</div>
                    <div class="mg-val-desc">{{ $v[2] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- PRICING --}}
<section class="mg-section reveal" id="tarifs">
    <div class="mg-section-inner">
        <div style="text-align: center; margin-bottom: 0;">
            <div class="mg-section-eye" style="justify-content: center;">Tarifs</div>
            <h2 class="mg-section-h2" style="text-align: center;">Simple.<br><span class="grad">Transparent.</span></h2>
        </div>
        <div class="mg-pricing-grid">
            <div class="mg-plan">
                <div class="mg-plan-flag">
                    <svg width="32" height="21" viewBox="0 0 32 21" xmlns="http://www.w3.org/2000/svg" style="border-radius:3px;display:block;"><rect width="11" height="21" fill="#00A859"/><rect x="10" width="12" height="21" fill="#FDEF42"/><rect x="21" width="11" height="21" fill="#E31B23"/><polygon points="16,4.5 17,7.5 20.2,7.5 17.6,9.3 18.6,12.3 16,10.5 13.4,12.3 14.4,9.3 11.8,7.5 15,7.5" fill="#00A859"/></svg>
                </div>
                <div class="mg-plan-name">Sénégal</div>
                <div class="mg-plan-price free">GRATUIT</div>
                <div class="mg-plan-period">1 mois offert · Aucune carte</div>
                <ul class="mg-plan-ul">
                    <li>Profil créateur complet</li>
                    <li>Publications illimitées</li>
                    <li>Messagerie directe</li>
                    <li>Accès au marketplace</li>
                </ul>
                <a href="{{ route('register') }}" class="mg-plan-btn outline">Créer mon compte</a>
            </div>

            <div class="mg-plan hot">
                <div class="mg-plan-badge">🔥 Populaire</div>
                <div class="mg-plan-flag">🌍</div>
                <div class="mg-plan-name">Afrique</div>
                <div class="mg-plan-price">2 500<sub>FCFA/mois</sub></div>
                <div class="mg-plan-period">~4€ · Wave & Orange Money</div>
                <ul class="mg-plan-ul">
                    <li>Tout du plan Sénégal</li>
                    <li>Badge 🌍 Afrique sur ton profil</li>
                    <li>Paiement Wave & Orange Money</li>
                    <li>Support prioritaire 48h</li>
                </ul>
                @auth
                    <a href="{{ route('subscription.checkout', 'african') }}" class="mg-plan-btn fill">Commencer →</a>
                @else
                    <a href="{{ route('register') }}" class="mg-plan-btn fill">Commencer →</a>
                @endauth
            </div>

            <div class="mg-plan">
                <div class="mg-plan-flag">✈️</div>
                <div class="mg-plan-name">Diaspora</div>
                <div class="mg-plan-price">9,99<sub>€/mois</sub></div>
                <div class="mg-plan-period">Stripe · Carte bancaire</div>
                <ul class="mg-plan-ul">
                    <li>Tout du plan Afrique</li>
                    <li>Badge ✈️ Diaspora sur ton profil</li>
                    <li>Paiement par carte bancaire</li>
                    <li>Support prioritaire 24h</li>
                </ul>
                @auth
                    <a href="{{ route('subscription.checkout', 'global') }}" class="mg-plan-btn outline">S'abonner</a>
                @else
                    <a href="{{ route('register') }}" class="mg-plan-btn outline">S'abonner</a>
                @endauth
            </div>
        </div>
    </div>
</section>

{{-- CTA FINALE --}}
@guest
<section class="mg-cta reveal">
    <div class="mg-cta-inner">
        <h2>Prêt à faire rayonner<br><span class="grad">ton art ?</span></h2>
        <p class="mg-cta-sub">Inscription gratuite en 2 minutes. Aucune carte requise pour les Sénégalais.</p>
        <div class="mg-cta-btns">
            <a href="{{ route('register') }}" class="mg-btn-xl">Créer mon profil gratuitement</a>
            <a href="{{ route('register') }}" class="mg-btn-xl-ghost">Explorer les créateurs →</a>
        </div>
        <div class="mg-cta-note">🇸🇳 Fait à Dakar avec ❤️</div>
    </div>
</section>
@endguest

{{-- FOOTER --}}
<footer class="mg-footer">
    <div class="mg-footer-logo">Melano<span>Geek</span></div>
    <div class="mg-footer-links">
        <a href="{{ route('about') }}">À propos</a>
        <a href="#">Confidentialité</a>
        <a href="#">CGU</a>
        <a href="#">Contact</a>
    </div>
    <div class="mg-footer-copy">© {{ date('Y') }} MelanoGeek · Dakar 🇸🇳</div>
</footer>

</div>
<script>
    const obs = new IntersectionObserver(e => e.forEach(el => {
        if (el.isIntersecting) el.target.classList.add('visible');
    }), { threshold: .1 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
</script>
</body>
</html>
