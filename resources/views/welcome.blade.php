<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>MelanoGeek — La culture geek, vue d'Afrique</title>
<meta name="description" content="Articles, débats, reviews et actualités. De la scène esport africaine aux mythologies africaines dans le Web3.">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<meta name="theme-color" content="#ff5a1f">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;700;800&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web@2.1.1/src/index.js" defer></script>

<style>
/* ══════════════════════════════════════════
   MIX — V1 Soul · V2 Structure
══════════════════════════════════════════ */

*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; overflow-x: hidden; }

:root {
    --obsidian:    #0d0d14;
    --carbon:      #13131c;
    --obsidian-2:  #18181f;
    --sunrise:     #ff5a1f;
    --sunrise-2:   #e04010;
    --cyan:        #22d3ee;
    --ochre:       #d97706;
    --glass-bg:    rgba(255,255,255,.07);
    --glass-bg2:   rgba(255,255,255,.04);
    --glass-b:     rgba(255,255,255,.10);
    --glass-b2:    rgba(255,255,255,.06);
    --text:        rgba(255,255,255,.85);
    --text-muted:  rgba(255,255,255,.45);
    --text-dim:    rgba(255,255,255,.28);
    --font-disp:   'Bricolage Grotesque', sans-serif;
    --font-body:   'Inter', sans-serif;
    --font-mono:   'JetBrains Mono', monospace;
}

body {
    background: var(--obsidian);
    color: var(--text);
    font-family: var(--font-body);
    overflow-x: hidden;
    min-height: 100dvh;
    padding-top: 88px;
    padding-bottom: 16px;
    position: relative;
}

::selection { background: var(--sunrise); color: white; }

/* ── Ambient glows ── */
.amb { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
.amb-1 { top: -20%; left: -10%; width: 55%; height: 55%; background: radial-gradient(ellipse, rgba(255,90,31,.16) 0%, transparent 70%); filter: blur(100px); }
.amb-2 { bottom: -20%; right: -10%; width: 45%; height: 45%; background: radial-gradient(ellipse, rgba(34,211,238,.09) 0%, transparent 70%); filter: blur(100px); }

/* ── Glass ── */
.glass {
    background: var(--glass-bg);
    border: 1px solid var(--glass-b);
    backdrop-filter: blur(16px) saturate(1.4);
    -webkit-backdrop-filter: blur(16px) saturate(1.4);
}

/* ══════════════════════════════════════════
   NAV
══════════════════════════════════════════ */
.v1-nav {
    position: fixed; top: 0; left: 0; width: 100%;
    z-index: 50; padding: 16px 32px;
}
.v1-nav-inner {
    max-width: 1280px; margin: 0 auto;
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 24px;
    border-radius: 9999px;
}

.v1-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
.v1-logo-diamond {
    width: 32px; height: 32px; background: var(--sunrise); border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    transform: rotate(45deg); transition: transform .5s ease; flex-shrink: 0;
}
.v1-logo:hover .v1-logo-diamond { transform: rotate(90deg); }
.v1-logo-diamond-inner {
    width: 12px; height: 12px; background: var(--obsidian); border-radius: 2px;
    transform: rotate(-45deg);
}
.v1-logo-text { font-family: var(--font-disp); font-weight: 800; font-size: 1.25rem; letter-spacing: -.03em; color: white; }
.v1-logo-text span { color: var(--sunrise); }

.v1-nav-links { display: flex; align-items: center; gap: 32px; list-style: none; }
.v1-nav-links a {
    font-size: .875rem; font-weight: 500; color: var(--text-muted);
    text-decoration: none; transition: color .25s; position: relative;
}
.v1-nav-links a:hover { color: white; }
.v1-nav-links .forum { color: var(--ochre); }
.v1-nav-links .forum:hover { color: var(--sunrise); }
.mythos-dot {
    position: absolute; top: -8px; right: -10px;
    width: 6px; height: 6px; border-radius: 50%; background: var(--cyan);
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }
@media (max-width: 1024px) { .v1-nav-links { display: none; } }

.v1-nav-cta {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 20px; border-radius: 9999px;
    background: white; color: var(--obsidian);
    font-size: .875rem; font-weight: 700;
    text-decoration: none; transition: background .2s; white-space: nowrap;
}
.v1-nav-cta:hover { background: #e0e0e0; }
@media (max-width: 640px) { .v1-nav-cta { display: none; } }

.v1-nav-mobile { display: none; }
@media (max-width: 1024px) {
    .v1-nav-mobile { display: flex; align-items: center; color: white; font-size: 1.5rem; background: none; border: none; cursor: pointer; }
}

/* ══════════════════════════════════════════
   MAIN WRAPPER
══════════════════════════════════════════ */
.v1-main {
    position: relative; z-index: 10;
    max-width: 1280px; margin: 0 auto; padding: 0 32px;
}
@media (max-width: 768px) { .v1-main { padding: 0 16px; } }

/* ══════════════════════════════════════════
   HERO — compact text block
══════════════════════════════════════════ */
.mix-hero {
    padding: 52px 0 44px;
}
.v1-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 5px 14px; border-radius: 9999px;
    background: rgba(255,90,31,.1); border: 1px solid rgba(255,90,31,.2);
    color: var(--sunrise); font-size: .75rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    margin-bottom: 22px;
}
.v1-h1 {
    font-family: var(--font-disp);
    font-size: clamp(2.6rem, 5.5vw, 4.8rem);
    font-weight: 800; line-height: 1.06; letter-spacing: -.05em;
    color: white; margin-bottom: 20px;
}
.v1-h1-outline {
    display: block; margin-top: 6px;
    -webkit-text-stroke: 2px rgba(255,255,255,.65);
    color: transparent;
    transition: -webkit-text-stroke-color .3s;
}
.mix-hero:hover .v1-h1-outline { -webkit-text-stroke-color: white; }
.v1-hero-sub {
    font-size: 1rem; line-height: 1.75; color: var(--text-muted);
    max-width: 560px; margin-bottom: 28px; font-weight: 300;
}
.v1-hero-ctas { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

.v1-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px; border-radius: 9999px;
    background: var(--sunrise); color: white;
    font-size: .9rem; font-weight: 700; text-decoration: none;
    transition: background .2s, box-shadow .2s, transform .15s;
}
.v1-btn-primary:hover { background: var(--sunrise-2); box-shadow: 0 0 22px rgba(255,90,31,.4); transform: translateY(-1px); }
.v1-btn-primary:active { transform: scale(.97); }

.v1-btn-ghost {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px; border-radius: 9999px;
    color: white; font-size: .9rem; font-weight: 700; text-decoration: none;
    transition: background .2s, transform .15s;
}
.v1-btn-ghost:hover { background: rgba(255,255,255,.1); }
.v1-btn-ghost:active { transform: scale(.97); }

/* ══════════════════════════════════════════
   MARQUEE
══════════════════════════════════════════ */
.v1-marquee-wrap {
    transform: rotate(-1deg) scaleX(1.05);
    margin: 0 -32px 48px;
    border-top: 1px solid rgba(255,255,255,.05);
    border-bottom: 1px solid rgba(255,255,255,.05);
    overflow: hidden;
}
.v1-marquee-track {
    display: flex; align-items: center;
    padding: 11px 0; white-space: nowrap;
    animation: v1marquee 24s linear infinite;
}
@keyframes v1marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }
.v1-marquee-track:hover { animation-play-state: paused; }
.v1-marquee-item {
    font-family: var(--font-mono); font-size: .72rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.22); padding: 0 24px;
    display: inline-flex; align-items: center; gap: 24px;
}
.v1-marquee-item.bright { color: white; }
.v1-marquee-star { color: var(--sunrise); font-size: .6rem; }

/* ══════════════════════════════════════════
   8 + 4 LAYOUT
══════════════════════════════════════════ */
.mix-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 36px;
    align-items: start;
    margin-bottom: 80px;
}
@media (max-width: 1024px) {
    .mix-layout { grid-template-columns: 1fr; }
    .mix-sidebar { display: none; }
}

/* ══════════════════════════════════════════
   FEATURED HERO CARD
══════════════════════════════════════════ */
.mix-feat-card {
    position: relative; height: 380px;
    border-radius: 28px; overflow: hidden;
    text-decoration: none; display: block;
    margin-bottom: 20px;
    transition: transform .35s, box-shadow .3s;
    border: 1px solid rgba(255,255,255,.08);
}
.mix-feat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 60px rgba(0,0,0,.5); }

.mix-feat-bg {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #ff5a1f 0%, #c84818 35%, #3d150a 70%, var(--obsidian) 100%);
}
.mix-feat-bg img {
    width: 100%; height: 100%; object-fit: cover;
    mix-blend-mode: luminosity; opacity: .55;
    filter: contrast(1.2) saturate(1.4);
}
.mix-feat-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, var(--obsidian) 0%, rgba(13,13,20,.55) 45%, transparent 100%);
}

.mix-feat-badge {
    position: absolute; top: 20px; left: 20px; z-index: 5;
    padding: 4px 12px; border-radius: 9999px;
    background: var(--cyan); color: var(--obsidian);
    font-family: var(--font-mono); font-size: .58rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
}
.mix-feat-arrow {
    position: absolute; top: 20px; right: 20px; z-index: 5;
    width: 38px; height: 38px; border-radius: 50%;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1rem;
    transition: background .2s, color .2s, transform .3s;
}
.mix-feat-card:hover .mix-feat-arrow {
    background: white; color: var(--obsidian); transform: rotate(45deg);
}

.mix-feat-body {
    position: absolute; bottom: 0; left: 0; right: 0; z-index: 5;
    padding: 24px 28px;
}
.mix-feat-title {
    font-family: var(--font-disp);
    font-size: clamp(1.25rem, 2.2vw, 1.7rem);
    font-weight: 700; color: white; line-height: 1.2; margin-bottom: 14px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .2s;
}
.mix-feat-card:hover .mix-feat-title { color: var(--cyan); }
.mix-feat-meta {
    display: flex; align-items: center; justify-content: space-between;
}
.mix-feat-author { display: flex; align-items: center; gap: 10px; }
.mix-feat-avi {
    width: 30px; height: 30px; border-radius: 50%; overflow: hidden; flex-shrink: 0;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: .62rem; font-weight: 700; color: white;
}
.mix-feat-avi img { width: 100%; height: 100%; object-fit: cover; }
.mix-feat-author-name { font-size: .8rem; color: rgba(255,255,255,.65); }
.mix-feat-stats {
    display: flex; align-items: center; gap: 12px;
    font-family: var(--font-mono); font-size: .65rem; color: rgba(255,255,255,.38);
}

/* ══════════════════════════════════════════
   CATEGORY PILLS
══════════════════════════════════════════ */
.mix-cat-pills {
    display: flex; gap: 8px; overflow-x: auto; padding: 2px 0 16px;
    scrollbar-width: none; -webkit-overflow-scrolling: touch;
    margin-bottom: 28px;
}
.mix-cat-pills::-webkit-scrollbar { display: none; }

.mix-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 9999px; white-space: nowrap;
    font-size: .8rem; font-weight: 600; text-decoration: none;
    transition: all .18s; flex-shrink: 0;
}
.mix-pill-on {
    background: white; color: var(--obsidian);
    box-shadow: 0 2px 12px rgba(255,255,255,.12);
}
.mix-pill-off {
    background: var(--glass-bg); border: 1px solid var(--glass-b);
    color: var(--text-muted);
    backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
}
.mix-pill-off:hover { color: white; border-color: rgba(255,255,255,.22); background: rgba(255,255,255,.1); }

/* ══════════════════════════════════════════
   ARTICLES SECTION
══════════════════════════════════════════ */
.mix-section-hd {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 18px;
}
.mix-section-title {
    font-family: var(--font-disp); font-size: 1.35rem; font-weight: 700;
    letter-spacing: -.03em; color: white;
}
.mix-see-all {
    display: flex; align-items: center; gap: 5px;
    font-size: .78rem; font-weight: 600; color: var(--sunrise);
    text-decoration: none; transition: color .2s; white-space: nowrap;
}
.mix-see-all:hover { color: white; }

.mix-articles {
    display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
}
@media (max-width: 600px) { .mix-articles { grid-template-columns: 1fr; } }

.mix-art-card {
    display: flex; flex-direction: column;
    border-radius: 20px; overflow: hidden; text-decoration: none;
    transition: transform .3s, box-shadow .2s;
}
.mix-art-card:hover { transform: translateY(-3px); box-shadow: 0 14px 40px rgba(0,0,0,.4); }

.mix-art-img {
    position: relative; height: 175px; overflow: hidden;
    background: var(--obsidian-2);
    flex-shrink: 0;
}
.mix-art-img img {
    width: 100%; height: 100%; object-fit: cover;
    filter: saturate(.65); transition: filter .45s, transform .5s;
}
.mix-art-card:hover .mix-art-img img { filter: saturate(1); transform: scale(1.04); }

.mix-art-badge {
    position: absolute; top: 11px; left: 11px; z-index: 2;
    padding: 3px 10px; border-radius: 9999px;
    font-family: var(--font-mono); font-size: .56rem; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    background: var(--sunrise); color: white;
}

.mix-art-body {
    flex: 1; padding: 14px 16px 10px;
}
.mix-art-title {
    font-family: var(--font-disp); font-size: .92rem; font-weight: 700;
    color: white; line-height: 1.3; margin-bottom: 6px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .2s;
}
.mix-art-card:hover .mix-art-title { color: var(--sunrise); }
.mix-art-excerpt {
    font-size: .75rem; color: var(--text-muted); line-height: 1.55;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.mix-art-footer {
    padding: 8px 16px 13px;
    display: flex; align-items: center; justify-content: space-between;
    font-family: var(--font-mono); font-size: .58rem; color: var(--text-dim);
    border-top: 1px solid rgba(255,255,255,.06); margin-top: 8px;
}

/* ══════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════ */
.mix-sidebar { position: sticky; top: 104px; }

.mix-forum-widget {
    border-radius: 20px; overflow: hidden; padding: 20px;
}
.mix-fw-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 18px;
}
.mix-fw-title {
    font-family: var(--font-disp); font-size: 1.05rem; font-weight: 700;
    color: white; display: flex; align-items: center; gap: 8px;
}
.mix-fw-dot {
    width: 8px; height: 8px; border-radius: 50%; background: var(--cyan);
    animation: pulse-dot 2s ease-in-out infinite;
}
.mix-fw-btn {
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(255,255,255,.06); border: 1px solid var(--glass-b);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); text-decoration: none; transition: all .15s;
}
.mix-fw-btn:hover { background: rgba(255,255,255,.12); color: white; }

.mix-fw-threads { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }

.mix-fw-thread {
    display: flex; align-items: center; gap: 11px;
    padding: 11px 12px; border-radius: 14px;
    background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06);
    text-decoration: none; transition: background .15s, border-color .15s;
}
.mix-fw-thread:hover { background: rgba(255,255,255,.065); border-color: rgba(255,255,255,.13); }

.mix-fw-thread-ico {
    width: 38px; height: 38px; flex-shrink: 0; border-radius: 11px;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08);
    display: flex; align-items: center; justify-content: center; font-size: 1rem;
    transition: background .15s;
}
.mix-fw-thread:hover .mix-fw-thread-ico { background: rgba(255,90,31,.14); }

.mix-fw-thread-body { flex: 1; min-width: 0; }
.mix-fw-thread-title {
    font-size: .78rem; font-weight: 600; color: rgba(255,255,255,.78); line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .15s;
}
.mix-fw-thread:hover .mix-fw-thread-title { color: white; }
.mix-fw-thread-meta { display: flex; align-items: center; gap: 5px; margin-top: 3px; }
.mix-fw-count {
    font-family: var(--font-mono); font-size: .56rem; font-weight: 600;
    background: rgba(255,255,255,.08); border-radius: 4px;
    padding: 1px 5px; color: var(--text-muted);
}
.mix-fw-time { font-family: var(--font-mono); font-size: .54rem; color: var(--text-dim); }

/* Divider online */
.mix-fw-online {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 0; margin-bottom: 14px;
    border-top: 1px solid rgba(255,255,255,.07);
    border-bottom: 1px solid rgba(255,255,255,.07);
}
.mix-fw-avis { display: flex; }
.mix-fw-avi {
    width: 24px; height: 24px; border-radius: 50%;
    border: 2px solid var(--obsidian); background: rgba(255,255,255,.1);
    margin-left: -7px; display: flex; align-items: center; justify-content: center;
    font-size: .52rem; font-weight: 700; color: white; overflow: hidden;
    flex-shrink: 0;
}
.mix-fw-avi:first-child { margin-left: 0; }
.mix-fw-avi img { width: 100%; height: 100%; object-fit: cover; }
.mix-fw-live {
    display: flex; align-items: center; gap: 5px;
    font-family: var(--font-mono); font-size: .62rem; color: var(--text-muted);
}
.mix-fw-greendot {
    width: 6px; height: 6px; border-radius: 50%; background: #22c55e;
    box-shadow: 0 0 6px rgba(34,197,94,.6); flex-shrink: 0;
}

/* Input */
.mix-fw-input-row {
    display: flex; gap: 0;
    background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
    border-radius: 11px; overflow: hidden; padding: 4px;
    margin-bottom: 12px;
}
.mix-fw-input {
    flex: 1; background: transparent; border: none; outline: none;
    padding: 8px 10px; color: white;
    font-family: var(--font-body); font-size: .78rem;
    min-width: 0;
}
.mix-fw-input::placeholder { color: var(--text-dim); }
.mix-fw-post {
    flex-shrink: 0; padding: 7px 13px; border-radius: 7px;
    background: var(--sunrise); color: white; border: none;
    font-family: var(--font-mono); font-size: .6rem; font-weight: 700;
    letter-spacing: .05em; text-transform: uppercase; cursor: pointer;
    transition: background .15s;
}
.mix-fw-post:hover { background: var(--sunrise-2); }

.mix-fw-all-link {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 9px; border-radius: 9px;
    background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07);
    text-decoration: none; color: var(--sunrise);
    font-family: var(--font-mono); font-size: .6rem; font-weight: 600;
    letter-spacing: .07em; text-transform: uppercase;
    transition: all .15s;
}
.mix-fw-all-link:hover { background: rgba(255,255,255,.07); color: white; }

/* ══════════════════════════════════════════
   NEWSLETTER
══════════════════════════════════════════ */
.v1-newsletter {
    max-width: 960px; margin: 0 auto 80px;
    padding: 44px 52px; border-radius: 32px;
    border-color: rgba(34,211,238,.2) !important;
    display: flex; align-items: center; justify-content: space-between; gap: 40px;
    position: relative; overflow: hidden;
}
.v1-newsletter-glow {
    position: absolute; left: -32px; bottom: -32px;
    width: 240px; height: 240px; border-radius: 50%;
    background: rgba(34,211,238,.08); filter: blur(50px); pointer-events: none;
}
.v1-newsletter-left { position: relative; z-index: 1; flex: 1; min-width: 0; }
.v1-nl-icon { font-size: 2.4rem; color: var(--cyan); margin-bottom: 10px; display: block; }
.v1-nl-title {
    font-family: var(--font-disp); font-size: clamp(1.4rem, 2.4vw, 2rem);
    font-weight: 800; letter-spacing: -.04em; color: white; margin-bottom: 8px;
}
.v1-nl-sub { font-size: .88rem; color: var(--text-muted); line-height: 1.6; }
.v1-newsletter-right { position: relative; z-index: 1; flex: 1; min-width: 220px; }
.v1-nl-form { display: flex; flex-direction: column; gap: 10px; }
.v1-nl-row { display: flex; gap: 10px; }
.v1-nl-input {
    flex: 1; background: rgba(13,13,20,.6); border: 1px solid rgba(255,255,255,.1);
    border-radius: 9999px; padding: 13px 20px; color: white;
    font-size: .88rem; outline: none; transition: border-color .2s;
}
.v1-nl-input:focus { border-color: var(--cyan); }
.v1-nl-input::placeholder { color: var(--text-dim); }
.v1-nl-btn {
    flex-shrink: 0; padding: 13px 22px; border-radius: 9999px;
    background: white; color: var(--obsidian); border: none;
    font-size: .88rem; font-weight: 700; cursor: pointer;
    transition: background .2s; white-space: nowrap;
}
.v1-nl-btn:hover { background: var(--cyan); }
.v1-nl-note { font-size: .7rem; color: rgba(255,255,255,.22); text-align: center; }
@media (max-width: 768px) {
    .v1-newsletter { flex-direction: column; padding: 28px 22px; }
    .v1-nl-row { flex-direction: column; }
    .v1-nl-btn { width: 100%; }
}

/* ══════════════════════════════════════════
   FOOTER
══════════════════════════════════════════ */
.v1-footer {
    border-top: 1px solid rgba(255,255,255,.05);
    padding: 36px 32px 24px;
    position: relative; z-index: 10;
}
.v1-footer-inner {
    max-width: 1280px; margin: 0 auto;
    display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
}
.v1-footer-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
.v1-footer-diamond {
    width: 20px; height: 20px; background: white; border-radius: 3px;
    transform: rotate(45deg);
}
.v1-footer-name {
    font-family: var(--font-disp); font-weight: 800; letter-spacing: -.03em; color: white;
}
.v1-footer-name span { color: rgba(255,255,255,.28); }
.v1-footer-year { font-size: .78rem; color: rgba(255,255,255,.22); margin-left: 8px; font-family: var(--font-body); font-weight: 400; }
.v1-footer-socials { display: flex; align-items: center; gap: 18px; }
.v1-footer-socials a {
    font-size: 1.25rem; color: rgba(255,255,255,.28);
    text-decoration: none; transition: color .25s;
}
.v1-footer-socials a:hover { color: white; }
@media (max-width: 640px) { .v1-footer-inner { justify-content: center; text-align: center; } }

/* ══════════════════════════════════════════
   MOBILE BOTTOM NAV
══════════════════════════════════════════ */
.v1-mbnav {
    display: none;
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 200;
    padding: 8px 0 max(8px, env(safe-area-inset-bottom));
    border-top: 1px solid rgba(255,255,255,.08);
}
@media (max-width: 768px) {
    .v1-mbnav { display: flex; }
    body { padding-bottom: 68px; }
}
.v1-mbn-item {
    flex: 1; display: flex; flex-direction: column; align-items: center; gap: 3px;
    padding: 6px 4px; text-decoration: none; color: rgba(255,255,255,.28);
    font-family: var(--font-mono); font-size: .47rem; letter-spacing: .06em; text-transform: uppercase;
    transition: color .15s;
}
.v1-mbn-item.active, .v1-mbn-item:hover { color: var(--sunrise); }
.v1-mbn-item svg { width: 20px; height: 20px; }

/* ══════════════════════════════════════════
   REVEAL ANIMATIONS
══════════════════════════════════════════ */
[data-reveal] { opacity: 0; transform: translateY(18px); transition: opacity .6s, transform .6s; }
[data-reveal].visible { opacity: 1; transform: none; }
</style>
</head>
<body>

{{-- Ambient glows --}}
<div class="amb amb-1"></div>
<div class="amb amb-2"></div>

{{-- ══ NAV ══ --}}
<nav class="v1-nav">
    <div class="v1-nav-inner glass">
        <a href="{{ route('home') }}" class="v1-logo">
            <div class="v1-logo-diamond"><div class="v1-logo-diamond-inner"></div></div>
            <span class="v1-logo-text">Melano<span>Geek</span></span>
        </a>

        <ul class="v1-nav-links">
            <li><a href="{{ route('blog.index') }}?category=manga-anime">Animés</a></li>
            <li><a href="{{ route('blog.index') }}?category=gaming">Gaming</a></li>
            <li><a href="{{ route('blog.index') }}?category=tech">Tech &amp; Web3</a></li>
            <li><a href="{{ route('blog.index') }}?category=culture" style="position:relative">
                Mythos <span class="mythos-dot"></span>
            </a></li>
            <li><a href="{{ route('forum.index') }}" class="forum">Forum</a></li>
        </ul>

        <div style="display:flex;align-items:center;gap:12px">
            @guest
            <a href="{{ route('register') }}" class="v1-nav-cta">
                Rejoindre le Hub <i class="ph-bold ph-arrow-right"></i>
            </a>
            @else
            <a href="{{ route('blog.index') }}" class="v1-nav-cta">
                {{ auth()->user()->name }} <i class="ph-bold ph-arrow-right"></i>
            </a>
            @endguest
            <button class="v1-nav-mobile"><i class="ph-bold ph-list"></i></button>
        </div>
    </div>
</nav>

{{-- ══ MAIN ══ --}}
<main class="v1-main">

    {{-- ══ HERO TEXT — compact ══ --}}
    <section class="mix-hero" data-reveal>
        <div class="v1-hero-badge">
            <i class="ph-fill ph-planet"></i> Le réseau des créateurs
        </div>
        <h1 class="v1-h1">
            La culture geek,<br>
            <span class="v1-h1-outline">vue d'Afrique.</span>
        </h1>
        <p class="v1-hero-sub">
            Articles, débats, reviews et actualités. De la scène esport à Dakar
            aux mythologies africaines dans le Web3 — par et pour la communauté nerd du continent.
        </p>
        <div class="v1-hero-ctas">
            <a href="{{ route('blog.index') }}" class="v1-btn-primary">
                Explorer les articles <i class="ph-bold ph-arrow-down-right"></i>
            </a>
            <a href="{{ route('forum.index') }}" class="v1-btn-ghost glass">
                <i class="ph-fill ph-chats-circle" style="font-size:1.05rem"></i>
                Forum Communautaire
            </a>
        </div>
    </section>

    {{-- ══ MARQUEE ══ --}}
    <div class="v1-marquee-wrap glass" data-reveal style="transition-delay:.2s">
        <div class="v1-marquee-track">
            @foreach(array_fill(0,2,null) as $_)
            <span class="v1-marquee-item">ANIMÉ &amp; MANGA</span><span class="v1-marquee-star">✦</span>
            <span class="v1-marquee-item">ESPORT AFRICAIN</span><span class="v1-marquee-star">✦</span>
            <span class="v1-marquee-item bright">TECH &amp; WEB3</span><span class="v1-marquee-star">✦</span>
            <span class="v1-marquee-item">HARDWARE REVIEWS</span><span class="v1-marquee-star">✦</span>
            <span class="v1-marquee-item">MYTHOLOGIE &amp; CINÉMA</span><span class="v1-marquee-star">✦</span>
            @endforeach
        </div>
    </div>

    {{-- ══ 8 + 4 LAYOUT ══ --}}
    <div class="mix-layout">

        {{-- ── MAIN COLUMN ── --}}
        <div>

            {{-- Featured hero card --}}
            @if($featured)
            @php $featMins = max(1,(int)ceil(str_word_count(strip_tags($featured->body??''))/200)); @endphp
            <a href="{{ route('posts.show', $featured->id) }}" class="mix-feat-card" data-reveal>
                <div class="mix-feat-bg">
                    @if($featured->primary_image_url)
                        <img src="{{ $featured->primary_image_url }}" alt="{{ $featured->title }}">
                    @endif
                </div>
                <div class="mix-feat-overlay"></div>
                <span class="mix-feat-badge">{{ $featured->category_label ?? 'À la Une' }}</span>
                <div class="mix-feat-arrow">
                    <i class="ph-bold ph-arrow-up-right"></i>
                </div>
                <div class="mix-feat-body">
                    <h2 class="mix-feat-title">{{ $featured->title }}</h2>
                    <div class="mix-feat-meta">
                        <div class="mix-feat-author">
                            <div class="mix-feat-avi">
                                @if($featured->user->avatar_url)
                                    <img src="{{ $featured->user->avatar_url }}" alt="">
                                @else
                                    {{ strtoupper(substr($featured->user->name??'?',0,2)) }}
                                @endif
                            </div>
                            <span class="mix-feat-author-name">Par {{ $featured->user->name }}</span>
                        </div>
                        <div class="mix-feat-stats">
                            <span><i class="ph-bold ph-clock"></i> {{ $featMins }} min</span>
                        </div>
                    </div>
                </div>
            </a>
            @endif

            {{-- Category pills --}}
            <div class="mix-cat-pills" data-reveal style="transition-delay:.1s">
                <a href="{{ route('blog.index') }}" class="mix-pill mix-pill-on">All Topics</a>
                <a href="{{ route('blog.index') }}?category=manga-anime" class="mix-pill mix-pill-off">🎌 Animés</a>
                <a href="{{ route('blog.index') }}?category=gaming" class="mix-pill mix-pill-off">🎮 Gaming</a>
                <a href="{{ route('blog.index') }}?category=tech" class="mix-pill mix-pill-off">🤖 Tech &amp; IA</a>
                <a href="{{ route('blog.index') }}?category=cinema-series" class="mix-pill mix-pill-off">🎬 Cinéma</a>
                <a href="{{ route('blog.index') }}?category=web3-economie" class="mix-pill mix-pill-off">₿ Web3</a>
                <a href="{{ route('blog.index') }}?category=culture" class="mix-pill mix-pill-off">🚀 Mythos</a>
                <a href="{{ route('blog.index') }}?category=hardware" class="mix-pill mix-pill-off">🖥️ Hardware</a>
            </div>

            {{-- Articles grid --}}
            @if($side_posts->isNotEmpty())
            <div class="mix-section-hd">
                <h2 class="mix-section-title">À la Une</h2>
                <a href="{{ route('blog.index') }}" class="mix-see-all">
                    Voir tout <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>

            <div class="mix-articles" data-reveal style="transition-delay:.15s">
                @foreach($side_posts->take(4) as $post)
                @php $mins = max(1,(int)ceil(str_word_count(strip_tags($post->body??''))/200)); @endphp
                <a href="{{ route('posts.show', $post->id) }}" class="mix-art-card glass">
                    <div class="mix-art-img">
                        @if($post->primary_image_url)
                            <img src="{{ $post->primary_image_url }}" alt="{{ $post->title }}">
                        @endif
                        <span class="mix-art-badge">{{ $post->category_label ?? 'Article' }}</span>
                    </div>
                    <div class="mix-art-body">
                        <h3 class="mix-art-title">{{ $post->title }}</h3>
                        <p class="mix-art-excerpt">{{ Str::limit(strip_tags($post->body??''), 90) }}</p>
                    </div>
                    <div class="mix-art-footer">
                        <span>{{ $post->user->name }}</span>
                        <span>{{ $mins }} min</span>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

        </div>

        {{-- ── SIDEBAR ── --}}
        <aside class="mix-sidebar" data-reveal style="transition-delay:.25s">
            <div class="mix-forum-widget glass">

                {{-- Header --}}
                <div class="mix-fw-head">
                    <div class="mix-fw-title">
                        <span class="mix-fw-dot"></span> Le Forum
                    </div>
                    <a href="{{ route('forum.index') }}" class="mix-fw-btn">
                        <i class="ph-bold ph-dots-three"></i>
                    </a>
                </div>

                {{-- Threads --}}
                @php
                    $sbThreads = $recentThreads->isNotEmpty()
                        ? $recentThreads
                        : \App\Models\ForumThread::with('user')->orderByDesc('last_reply_at')->limit(4)->get();
                @endphp
                <div class="mix-fw-threads">
                    @foreach($sbThreads->take(4) as $t)
                    <a href="{{ route('forum.show', $t->id) }}" class="mix-fw-thread">
                        <div class="mix-fw-thread-ico">{{ $t->category_icon ?? '💬' }}</div>
                        <div class="mix-fw-thread-body">
                            <div class="mix-fw-thread-title">{{ $t->title }}</div>
                            <div class="mix-fw-thread-meta">
                                <span class="mix-fw-count">{{ $t->replies_count }} rép.</span>
                                <span class="mix-fw-time">{{ ($t->last_reply_at ?? $t->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Online row --}}
                <div class="mix-fw-online">
                    <div class="mix-fw-avis">
                        @foreach($sbThreads->take(4) as $t)
                        <div class="mix-fw-avi">
                            @if($t->user->avatar_url ?? null)
                                <img src="{{ $t->user->avatar_url }}" alt="">
                            @else
                                {{ strtoupper(substr($t->user->name??'?',0,1)) }}
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <div class="mix-fw-live">
                        <span class="mix-fw-greendot"></span> En direct
                    </div>
                </div>

                {{-- Input --}}
                <form class="mix-fw-input-row" action="{{ route('forum.index') }}" method="GET">
                    <input type="text" name="q" class="mix-fw-input" placeholder="Lancer un sujet...">
                    <button type="submit" class="mix-fw-post">Poster</button>
                </form>

                <a href="{{ route('forum.index') }}" class="mix-fw-all-link">
                    Voir tout le forum <i class="ph-bold ph-arrow-right"></i>
                </a>

            </div>
        </aside>

    </div>{{-- end mix-layout --}}

    {{-- ══ NEWSLETTER ══ --}}
    <section class="v1-newsletter glass" data-reveal>
        <div class="v1-newsletter-glow"></div>
        <div class="v1-newsletter-left">
            <i class="ph-fill ph-envelope-simple-open v1-nl-icon"></i>
            <div class="v1-nl-title">Le condensé Geek.</div>
            <p class="v1-nl-sub">Recevez une fois par mois l'essentiel de l'actu nerd, tech et pop culture africaine, directement dans votre boîte mail.</p>
        </div>
        <div class="v1-newsletter-right">
            <form class="v1-nl-form" onsubmit="handleNl(event)">
                <div class="v1-nl-row">
                    <input type="email" class="v1-nl-input" placeholder="votre@email.com" required id="nlEmail">
                    <button type="submit" class="v1-nl-btn">S'abonner</button>
                </div>
                <div class="v1-nl-note" id="nlNote">Pas de spam. Promis jury craché.</div>
            </form>
        </div>
    </section>

</main>

{{-- ══ FOOTER ══ --}}
<footer class="v1-footer">
    <div class="v1-footer-inner">
        <a href="{{ route('home') }}" class="v1-footer-logo">
            <div class="v1-footer-diamond"></div>
            <span class="v1-footer-name">Melano<span>Geek</span><span class="v1-footer-year">© {{ date('Y') }}</span></span>
        </a>
        <div class="v1-footer-socials">
            <a href="#"><i class="ph-fill ph-twitter-logo"></i></a>
            <a href="#"><i class="ph-fill ph-discord-logo"></i></a>
            <a href="#"><i class="ph-fill ph-instagram-logo"></i></a>
            <a href="#"><i class="ph-fill ph-twitch-logo"></i></a>
        </div>
    </div>
</footer>

{{-- ══ MOBILE BOTTOM NAV ══ --}}
<nav class="v1-mbnav glass">
    <a href="{{ route('home') }}" class="v1-mbn-item active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Accueil
    </a>
    <a href="{{ route('blog.index') }}" class="v1-mbn-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Articles
    </a>
    <a href="{{ route('forum.index') }}" class="v1-mbn-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Forum
    </a>
    @auth
    <a href="{{ route('profile.show', auth()->user()->username ?? auth()->id()) }}" class="v1-mbn-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Profil
    </a>
    @else
    <a href="{{ route('register') }}" class="v1-mbn-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
        Rejoindre
    </a>
    @endauth
</nav>

<script>
(function(){
    /* Reveal on scroll */
    const obs = new IntersectionObserver(e => {
        e.forEach(x => { if (x.isIntersecting) { x.target.classList.add('visible'); obs.unobserve(x.target); } });
    }, { threshold: .1 });
    document.querySelectorAll('[data-reveal]').forEach(el => obs.observe(el));

    /* Newsletter */
    window.handleNl = function(e) {
        e.preventDefault();
        const btn = e.target.querySelector('.v1-nl-btn');
        const note = document.getElementById('nlNote');
        btn.textContent = '✓ Inscrit !'; btn.style.background = '#22d3ee'; btn.disabled = true;
        if (note) note.textContent = 'Tu es sur la liste. À bientôt dans ta boîte mail.';
    };
})();
</script>
</body>
</html>
