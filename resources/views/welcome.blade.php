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
   V1 — MelanoGeek Dark Afrofuturist
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

/* Sélection */
::selection { background: var(--sunrise); color: white; }

/* ── Ambient background ── */
.amb { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
.amb-1 { top: -20%; left: -10%; width: 60%; height: 60%; background: radial-gradient(ellipse, rgba(255,90,31,.18) 0%, transparent 70%); filter: blur(100px); }
.amb-2 { bottom: -20%; right: -10%; width: 50%; height: 50%; background: radial-gradient(ellipse, rgba(34,211,238,.10) 0%, transparent 70%); filter: blur(100px); }

/* ── Glass panel ── */
.glass {
    background: var(--glass-bg);
    border: 1px solid var(--glass-b);
    backdrop-filter: blur(16px) saturate(1.4);
    -webkit-backdrop-filter: blur(16px) saturate(1.4);
}

/* ═══════════════════
   NAV
═══════════════════ */
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

/* Logo */
.v1-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
.v1-logo-diamond {
    width: 32px; height: 32px;
    background: var(--sunrise); border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    transform: rotate(45deg);
    transition: transform .5s ease;
    flex-shrink: 0;
}
.v1-logo:hover .v1-logo-diamond { transform: rotate(90deg); }
.v1-logo-diamond-inner {
    width: 12px; height: 12px;
    background: var(--obsidian); border-radius: 2px;
    transform: rotate(-45deg);
}
.v1-logo-text {
    font-family: var(--font-disp); font-weight: 800; font-size: 1.25rem;
    letter-spacing: -.03em; color: white;
}
.v1-logo-text span { color: var(--sunrise); }

/* Nav links */
.v1-nav-links {
    display: flex; align-items: center; gap: 32px;
    list-style: none;
}
.v1-nav-links a {
    font-family: var(--font-body); font-size: .875rem; font-weight: 500;
    color: var(--text-muted); text-decoration: none;
    transition: color .25s; position: relative;
}
.v1-nav-links a:hover { color: white; }
.v1-nav-links .forum { color: var(--ochre); }
.v1-nav-links .forum:hover { color: var(--sunrise); }
.v1-nav-links .mythos-dot {
    position: absolute; top: -8px; right: -10px;
    width: 6px; height: 6px; border-radius: 50%; background: var(--cyan);
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }
@media (max-width: 1024px) { .v1-nav-links { display: none; } }

/* Nav CTA */
.v1-nav-cta {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 20px; border-radius: 9999px;
    background: white; color: var(--obsidian);
    font-family: var(--font-body); font-size: .875rem; font-weight: 700;
    text-decoration: none; transition: background .2s;
    white-space: nowrap;
}
.v1-nav-cta:hover { background: #e0e0e0; }
@media (max-width: 640px) { .v1-nav-cta { display: none; } }

/* Nav mobile menu btn */
.v1-nav-mobile { display: none; }
@media (max-width: 1024px) { .v1-nav-mobile { display: flex; align-items: center; color: white; font-size: 1.5rem; background: none; border: none; cursor: pointer; } }

/* ═══════════════════
   MAIN WRAPPER
═══════════════════ */
.v1-main {
    position: relative; z-index: 10;
    max-width: 1280px; margin: 0 auto; padding: 0 32px;
}
@media (max-width: 768px) { .v1-main { padding: 0 16px; } }

/* ═══════════════════
   HERO
═══════════════════ */
.v1-hero {
    min-height: 85dvh;
    display: flex; flex-direction: column; justify-content: center;
    margin-bottom: 96px; position: relative;
}
.v1-hero-grid {
    display: grid;
    grid-template-columns: 7fr 5fr;
    gap: 32px;
    align-items: center;
}
@media (max-width: 1024px) {
    .v1-hero-grid { grid-template-columns: 1fr; }
    .v1-hero-right { display: none; }
    .v1-hero { min-height: auto; padding: 40px 0 20px; margin-bottom: 60px; }
}

/* Left */
.v1-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 5px 14px; border-radius: 9999px;
    background: rgba(255,90,31,.1); border: 1px solid rgba(255,90,31,.2);
    color: var(--sunrise); font-size: .75rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    margin-bottom: 24px;
}
.v1-h1 {
    font-family: var(--font-disp);
    font-size: clamp(3rem, 6vw, 5.5rem);
    font-weight: 800; line-height: 1.05; letter-spacing: -.05em;
    color: white; margin-bottom: 24px;
}
.v1-h1-outline {
    display: block; margin-top: 8px;
    -webkit-text-stroke: 2px rgba(255,255,255,.7);
    color: transparent;
    transition: -webkit-text-stroke-color .3s;
}
.v1-hero:hover .v1-h1-outline { -webkit-text-stroke-color: white; }
.v1-hero-sub {
    font-size: 1.1rem; line-height: 1.7;
    color: var(--text-muted); max-width: 520px; margin-bottom: 32px;
    font-weight: 300;
}
.v1-hero-ctas { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }

.v1-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 14px 28px; border-radius: 9999px;
    background: var(--sunrise); color: white;
    font-family: var(--font-body); font-size: .925rem; font-weight: 700;
    text-decoration: none;
    transition: background .2s, box-shadow .2s, transform .15s;
}
.v1-btn-primary:hover {
    background: var(--sunrise-2);
    box-shadow: 0 0 24px rgba(255,90,31,.45);
    transform: translateY(-1px);
}
.v1-btn-primary:active { transform: scale(.97); }

.v1-btn-ghost {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 14px 28px; border-radius: 9999px;
    color: white;
    font-family: var(--font-body); font-size: .925rem; font-weight: 700;
    text-decoration: none;
    transition: background .2s, transform .15s;
}
.v1-btn-ghost:hover { background: rgba(255,255,255,.1); }
.v1-btn-ghost:active { transform: scale(.97); }

/* Right — image card */
.v1-hero-right {
    position: relative;
    height: 600px;
}
.v1-hero-card {
    position: absolute; inset: 0;
    border-radius: 40px; overflow: hidden;
    display: flex; align-items: center; justify-content: center; padding: 16px;
}
.v1-hero-card-inner {
    position: relative; width: 100%; height: 100%;
    border-radius: 28px; overflow: hidden;
}
.v1-hero-card-img {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #0f0a0d 0%, #2a0d1a 30%, #8b1a3c 60%, #c84818 100%);
}
.v1-hero-card-img img {
    width: 100%; height: 100%; object-fit: cover;
    filter: contrast(1.25) saturate(1.5);
    mix-blend-mode: luminosity; opacity: .8;
}
.v1-hero-card-grad {
    position: absolute; inset: 0;
    background: linear-gradient(to top, var(--obsidian) 0%, transparent 60%);
    opacity: .85;
}
.v1-hero-card-overlay {
    position: absolute; inset: 0;
    background: var(--sunrise); mix-blend-mode: overlay; opacity: .3;
}
/* Bottom podcast card */
.v1-hero-podcast {
    position: absolute; bottom: 24px; left: 24px; right: 24px; z-index: 3;
    padding: 14px 16px; border-radius: 18px;
    display: flex; align-items: center; gap: 14px;
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
}
.v1-podcast-play {
    width: 44px; height: 44px; flex-shrink: 0;
    background: white; color: var(--obsidian);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
}
.v1-podcast-label {
    font-family: var(--font-mono); font-size: .6rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase; color: var(--cyan); margin-bottom: 4px;
}
.v1-podcast-title {
    font-size: .85rem; font-weight: 500; color: white; line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
}
/* Corner decorations */
.v1-hero-corner-tr {
    position: absolute; top: -16px; right: -16px; width: 96px; height: 96px;
    border-top: 2px solid rgba(255,90,31,.5); border-right: 2px solid rgba(255,90,31,.5);
    border-radius: 0 48px 0 0; pointer-events: none;
}
.v1-hero-corner-bl {
    position: absolute; bottom: -16px; left: -16px; width: 96px; height: 96px;
    border-bottom: 2px solid rgba(34,211,238,.5); border-left: 2px solid rgba(34,211,238,.5);
    border-radius: 0 0 0 48px; pointer-events: none;
}

/* ═══════════════════
   MARQUEE
═══════════════════ */
.v1-marquee-wrap {
    transform: rotate(-1deg) scaleX(1.05);
    margin: 0 -32px 96px;
    border-top: 1px solid rgba(255,255,255,.05);
    border-bottom: 1px solid rgba(255,255,255,.05);
    overflow: hidden;
}
.v1-marquee-track {
    display: flex; align-items: center;
    padding: 12px 0; white-space: nowrap;
    animation: v1marquee 24s linear infinite;
}
@keyframes v1marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }
.v1-marquee-track:hover { animation-play-state: paused; }
.v1-marquee-item {
    font-family: var(--font-mono); font-size: .75rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.22); padding: 0 28px;
    display: inline-flex; align-items: center; gap: 28px;
}
.v1-marquee-item.bright { color: white; }
.v1-marquee-star { color: var(--sunrise); font-size: .65rem; }

/* ═══════════════════
   À LA UNE
═══════════════════ */
.v1-aune { margin-bottom: 96px; }
.v1-aune-header {
    display: flex; align-items: flex-end; justify-content: space-between;
    margin-bottom: 40px;
}
.v1-aune-title {
    font-family: var(--font-disp);
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800; letter-spacing: -.04em; color: white; margin-bottom: 8px;
}
.v1-aune-sub { color: var(--text-muted); font-size: .9rem; }
.v1-aune-link {
    display: flex; align-items: center; gap: 8px;
    font-size: .875rem; font-weight: 700; color: var(--sunrise);
    text-decoration: none; transition: color .2s; white-space: nowrap;
    flex-shrink: 0; margin-left: 24px; margin-bottom: 4px;
}
.v1-aune-link:hover { color: white; }

/* Grid 4 cols × 2 rows */
.v1-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(2, minmax(200px, auto));
    gap: 20px;
}
@media (max-width: 1024px) { .v1-grid { grid-template-columns: repeat(2,1fr); grid-template-rows: auto; } }
@media (max-width: 600px)  { .v1-grid { grid-template-columns: 1fr; } }

/* Card base */
.v1-card {
    border-radius: 24px; overflow: hidden; text-decoration: none;
    position: relative; transition: transform .3s, box-shadow .3s;
    display: block;
}
.v1-card:hover { transform: translateY(-3px); }

/* Featured 2×2 */
.v1-card-featured {
    grid-column: span 2; grid-row: span 2;
    min-height: 400px;
}
@media (max-width: 1024px) { .v1-card-featured { grid-column: span 2; grid-row: span 1; min-height: 320px; } }

.v1-card-featured .v1-card-img {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #0a1a0a, #1a5a25);
}
.v1-card-featured .v1-card-img img {
    width: 100%; height: 100%; object-fit: cover;
    filter: saturate(.5); transition: filter .5s;
}
.v1-card-featured:hover .v1-card-img img { filter: saturate(1); }
.v1-card-featured .v1-card-carbon {
    position: absolute; inset: 0;
    background: rgba(20,20,28,.4); transition: opacity .4s;
}
.v1-card-featured:hover .v1-card-carbon { opacity: 0; }
.v1-card-featured .v1-card-grad {
    position: absolute; inset: 0;
    background: linear-gradient(to top, var(--obsidian) 0%, rgba(13,13,20,.8) 45%, transparent 100%);
}
.v1-card-featured .v1-card-body {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; justify-content: flex-end;
    padding: 32px; z-index: 10;
}
.v1-badge-cyan {
    display: inline-flex; padding: 4px 12px; border-radius: 9999px;
    background: var(--cyan); color: var(--obsidian);
    font-family: var(--font-mono); font-size: .6rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
}
.v1-badge-sunrise { background: var(--sunrise); color: white; }
.v1-badge-ochre { background: var(--ochre); color: white; }
.v1-card-badges { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
.v1-card-mins { font-size: .75rem; color: rgba(255,255,255,.45); display: flex; align-items: center; gap: 4px; }
.v1-card-featured .v1-card-title {
    font-family: var(--font-disp);
    font-size: clamp(1.4rem, 2.5vw, 1.9rem);
    font-weight: 700; line-height: 1.2; color: white;
    margin-bottom: 10px; transition: color .25s;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.v1-card-featured:hover .v1-card-title { color: var(--cyan); }
.v1-card-excerpt {
    color: var(--text-muted); font-size: .875rem; line-height: 1.6;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    margin-bottom: 20px;
}
.v1-card-author { display: flex; align-items: center; gap: 10px; }
.v1-card-avi {
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 700; color: white; overflow: hidden; flex-shrink: 0;
}
.v1-card-avi img { width: 100%; height: 100%; object-fit: cover; }

/* Horizontal card (2col × 1row) */
.v1-card-horiz {
    grid-column: span 2;
    display: flex; align-items: center; gap: 0;
    padding: 0; overflow: hidden;
}
.v1-card-horiz-img {
    width: 40%; flex-shrink: 0; align-self: stretch; position: relative;
    overflow: hidden; background: linear-gradient(135deg, #1a0a2e, #4a1a6e);
}
.v1-card-horiz-img img {
    position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
    filter: saturate(.5); transition: filter .4s;
}
.v1-card-horiz:hover .v1-card-horiz-img img { filter: saturate(1); }
.v1-card-horiz-img-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to right, transparent, rgba(13,13,20,.4));
    mix-blend-mode: multiply;
}
.v1-card-horiz-body {
    flex: 1; padding: 24px 22px; display: flex; flex-direction: column; justify-content: center;
}
.v1-card-horiz-cat {
    font-family: var(--font-mono); font-size: .62rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase; color: var(--sunrise); margin-bottom: 10px;
}
.v1-card-horiz-title {
    font-family: var(--font-disp); font-size: 1.05rem; font-weight: 700;
    color: white; line-height: 1.3; margin-bottom: 8px;
    transition: color .2s;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.v1-card-horiz:hover .v1-card-horiz-title { color: var(--sunrise); }
.v1-card-horiz-excerpt {
    font-size: .8rem; color: var(--text-muted); line-height: 1.55;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}

/* Small card — icon */
.v1-card-small {
    padding: 22px; display: flex; flex-direction: column; justify-content: space-between;
    position: relative; overflow: hidden;
}
.v1-card-small::before {
    content: ''; position: absolute; top: 0; right: 0; width: 96px; height: 96px;
    background: rgba(255,255,255,.04); border-radius: 0 0 0 100%;
}
.v1-card-small-icon {
    width: 40px; height: 40px; border-radius: 50%;
    background: rgba(255,255,255,.08); display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; margin-bottom: 10px; transition: background .2s;
}
.v1-card-small:hover .v1-card-small-icon { background: var(--ochre); }
.v1-card-small-cat {
    font-family: var(--font-mono); font-size: .6rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase; color: var(--ochre);
}
.v1-card-small-title {
    font-family: var(--font-disp); font-size: 1rem; font-weight: 700;
    color: white; line-height: 1.3; margin-top: 4px;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.v1-card-small-footer {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 14px; border-top: 1px solid rgba(255,255,255,.1);
    margin-top: 14px;
    font-size: .72rem; color: var(--text-dim);
}
.v1-card-small-arrow {
    font-size: 1rem; color: white; transition: transform .2s;
}
.v1-card-small:hover .v1-card-small-arrow { transform: translateX(4px); }

/* Forum live card */
.v1-card-forum {
    padding: 22px; display: flex; flex-direction: column; justify-content: space-between;
    background: linear-gradient(135deg, var(--carbon), var(--obsidian));
    border: 1px dashed rgba(255,255,255,.2) !important;
    position: relative;
    transition: border-color .25s !important;
}
.v1-card-forum:hover { border-color: rgba(255,90,31,.5) !important; }
.v1-forum-ping {
    position: absolute; top: 20px; right: 20px;
    width: 12px; height: 12px;
}
.v1-forum-ping-outer {
    position: absolute; inset: 0; border-radius: 50%; background: var(--sunrise); opacity: .75;
    animation: ping 1.5s cubic-bezier(0,0,.2,1) infinite;
}
@keyframes ping { 0%{transform:scale(1);opacity:.75} 100%{transform:scale(2);opacity:0} }
.v1-forum-ping-inner {
    position: relative; width: 12px; height: 12px;
    border-radius: 50%; background: var(--sunrise);
}
.v1-forum-head {
    font-family: var(--font-mono); font-size: .62rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase; color: white;
    margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
}
.v1-forum-thread {
    background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.06);
    border-radius: 10px; padding: 10px 12px; margin-bottom: 10px;
}
.v1-forum-thread:last-of-type { margin-bottom: 0; }
.v1-forum-thread-title {
    font-size: .8rem; color: rgba(255,255,255,.8); font-weight: 500; line-height: 1.35;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.v1-forum-thread-meta { font-size: .68rem; color: var(--cyan); margin-top: 6px; }
.v1-forum-cta {
    margin-top: 16px; text-align: center;
    font-family: var(--font-mono); font-size: .65rem; font-weight: 700;
    color: var(--sunrise); text-decoration: none;
}
.v1-card-forum:hover .v1-forum-cta { text-decoration: underline; }

/* ═══════════════════
   NEWSLETTER
═══════════════════ */
.v1-newsletter {
    max-width: 900px; margin: 0 auto 96px;
    padding: 44px 52px; border-radius: 32px;
    border-color: rgba(34,211,238,.2) !important;
    display: flex; align-items: center; justify-content: space-between; gap: 40px;
    position: relative; overflow: hidden;
}
.v1-newsletter-glow {
    position: absolute; left: -32px; bottom: -32px;
    width: 256px; height: 256px; border-radius: 50%;
    background: rgba(34,211,238,.08); filter: blur(50px); pointer-events: none;
}
.v1-newsletter-left { position: relative; z-index: 1; flex: 1; min-width: 0; }
.v1-nl-icon { font-size: 2.5rem; color: var(--cyan); margin-bottom: 12px; display: block; }
.v1-nl-title {
    font-family: var(--font-disp); font-size: clamp(1.5rem, 2.5vw, 2.1rem);
    font-weight: 800; letter-spacing: -.04em; color: white; margin-bottom: 8px;
}
.v1-nl-sub { font-size: .9rem; color: var(--text-muted); line-height: 1.6; }
.v1-newsletter-right { position: relative; z-index: 1; flex: 1; min-width: 240px; }
.v1-nl-form { display: flex; flex-direction: column; gap: 10px; }
.v1-nl-row { display: flex; gap: 10px; }
.v1-nl-input {
    flex: 1; background: rgba(13,13,20,.6); border: 1px solid rgba(255,255,255,.1);
    border-radius: 9999px; padding: 14px 22px; color: white;
    font-family: var(--font-body); font-size: .9rem; outline: none;
    transition: border-color .2s;
}
.v1-nl-input:focus { border-color: var(--cyan); }
.v1-nl-input::placeholder { color: var(--text-dim); }
.v1-nl-btn {
    flex-shrink: 0; padding: 14px 24px; border-radius: 9999px;
    background: white; color: var(--obsidian); border: none;
    font-family: var(--font-body); font-size: .9rem; font-weight: 700;
    cursor: pointer; transition: background .2s, color .2s;
    white-space: nowrap;
}
.v1-nl-btn:hover { background: var(--cyan); }
.v1-nl-note { font-size: .72rem; color: rgba(255,255,255,.25); text-align: center; }
@media (max-width: 768px) {
    .v1-newsletter { flex-direction: column; padding: 28px 24px; }
    .v1-nl-row { flex-direction: column; }
    .v1-nl-btn { width: 100%; }
}

/* ═══════════════════
   FOOTER
═══════════════════ */
.v1-footer {
    border-top: 1px solid rgba(255,255,255,.05);
    padding: 40px 32px 28px;
    position: relative; z-index: 10;
}
.v1-footer-inner {
    max-width: 1280px; margin: 0 auto;
    display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;
}
.v1-footer-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
.v1-footer-diamond {
    width: 22px; height: 22px; background: white; border-radius: 3px;
    transform: rotate(45deg); display: flex; align-items: center; justify-content: center;
}
.v1-footer-name {
    font-family: var(--font-disp); font-weight: 800; letter-spacing: -.03em; color: white;
}
.v1-footer-name span { color: rgba(255,255,255,.3); }
.v1-footer-year { font-size: .8rem; color: rgba(255,255,255,.25); margin-left: 8px; font-weight: 400; font-family: var(--font-body); }
.v1-footer-socials { display: flex; align-items: center; gap: 18px; }
.v1-footer-socials a {
    font-size: 1.3rem; color: rgba(255,255,255,.3);
    text-decoration: none; transition: color .25s;
}
.v1-footer-socials a:hover { color: white; }
@media (max-width: 640px) { .v1-footer-inner { justify-content: center; text-align: center; } }

/* ═══════════════════
   MOBILE BOTTOM NAV
═══════════════════ */
.v1-mbnav {
    display: none;
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 200;
    padding: 8px 0 max(8px, env(safe-area-inset-bottom));
    border-top: 1px solid rgba(255,255,255,.08);
}
@media (max-width: 768px) { .v1-mbnav { display: flex; } body { padding-bottom: 68px; } }
.v1-mbn-item {
    flex: 1; display: flex; flex-direction: column; align-items: center; gap: 3px;
    padding: 6px 4px; text-decoration: none; color: rgba(255,255,255,.28);
    font-family: var(--font-mono); font-size: .48rem; letter-spacing: .06em; text-transform: uppercase;
    transition: color .15s;
}
.v1-mbn-item.active, .v1-mbn-item:hover { color: var(--sunrise); }
.v1-mbn-item svg { width: 20px; height: 20px; }

/* ═══════════════════
   ANIMATIONS REVEAL
═══════════════════ */
[data-reveal] { opacity: 0; transform: translateY(20px); transition: opacity .6s, transform .6s; }
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
            <div class="v1-logo-diamond">
                <div class="v1-logo-diamond-inner"></div>
            </div>
            <span class="v1-logo-text">Melano<span>Geek</span></span>
        </a>

        <ul class="v1-nav-links">
            <li><a href="{{ route('blog.index') }}?category=manga-anime">Animés</a></li>
            <li><a href="{{ route('blog.index') }}?category=gaming">Gaming</a></li>
            <li><a href="{{ route('blog.index') }}?category=tech">Tech &amp; Web3</a></li>
            <li><a href="{{ route('blog.index') }}?category=culture" style="position:relative">
                Mythos <span class="v1-nav-links mythos-dot"></span>
            </a></li>
            <li><a href="{{ route('forum.index') }}" class="forum">Forum</a></li>
        </ul>

        <div style="display:flex;align-items:center;gap:12px">
            @guest
            <a href="{{ route('register') }}" class="v1-nav-cta">
                Rejoindre le Hub
                <i class="ph-bold ph-arrow-right"></i>
            </a>
            @else
            <a href="{{ route('blog.index') }}" class="v1-nav-cta">
                {{ auth()->user()->name }}
                <i class="ph-bold ph-arrow-right"></i>
            </a>
            @endguest
            <button class="v1-nav-mobile">
                <i class="ph-bold ph-list"></i>
            </button>
        </div>
    </div>
</nav>

{{-- ══ MAIN ══ --}}
<main class="v1-main">

    {{-- ══ HERO ══ --}}
    <section class="v1-hero">
        <div class="v1-hero-grid">

            {{-- Left --}}
            <div data-reveal>
                <div class="v1-hero-badge">
                    <i class="ph-fill ph-planet"></i>
                    Le réseau des créateurs
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
                        Explorer les articles
                        <i class="ph-bold ph-arrow-down-right"></i>
                    </a>
                    <a href="{{ route('forum.index') }}" class="v1-btn-ghost glass">
                        <i class="ph-fill ph-chats-circle" style="font-size:1.1rem"></i>
                        Forum Communautaire
                    </a>
                </div>
            </div>

            {{-- Right — image card --}}
            <div class="v1-hero-right" data-reveal style="transition-delay:.2s">
                @if($featured)
                <a href="{{ route('posts.show', $featured->id) }}" class="v1-hero-card glass">
                    <div class="v1-hero-card-inner">
                        <div class="v1-hero-card-img">
                            @if($featured->primary_image_url)
                                <img src="{{ $featured->primary_image_url }}" alt="{{ $featured->title }}">
                            @endif
                        </div>
                        <div class="v1-hero-card-grad"></div>
                        <div class="v1-hero-card-overlay"></div>
                        <div class="v1-hero-podcast glass">
                            <div class="v1-podcast-play">
                                <i class="ph-fill ph-play" style="margin-left:3px"></i>
                            </div>
                            <div>
                                <div class="v1-podcast-label">Dernier article</div>
                                <div class="v1-podcast-title">{{ $featured->title }}</div>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="v1-hero-corner-tr"></div>
                <div class="v1-hero-corner-bl"></div>
                @endif
            </div>

        </div>
    </section>

    {{-- ══ MARQUEE ══ --}}
    <div class="v1-marquee-wrap glass" data-reveal style="transition-delay:.35s">
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

    {{-- ══ À LA UNE ══ --}}
    <section class="v1-aune" data-reveal style="transition-delay:.1s">
        <div class="v1-aune-header">
            <div>
                <h2 class="v1-aune-title">À la Une</h2>
                <p class="v1-aune-sub">Dernières analyses et actualités de l'écosystème.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="v1-aune-link">
                Voir tout <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>

        <div class="v1-grid">

            {{-- FEATURED 2×2 --}}
            @if($featured)
            @php $featMins = max(1,(int)ceil(str_word_count(strip_tags($featured->body??''))/200)); @endphp
            <a href="{{ route('posts.show', $featured->id) }}" class="v1-card v1-card-featured glass">
                <div class="v1-card-img">
                    @if($featured->primary_image_url)
                        <img src="{{ $featured->primary_image_url }}" alt="{{ $featured->title }}">
                    @endif
                </div>
                <div class="v1-card-carbon"></div>
                <div class="v1-card-grad"></div>
                <div class="v1-card-body">
                    <div class="v1-card-badges">
                        <span class="v1-badge-cyan">{{ $featured->category_label ?? 'À la Une' }}</span>
                        <span class="v1-card-mins">
                            <i class="ph-bold ph-clock"></i> {{ $featMins }} min
                        </span>
                    </div>
                    <h3 class="v1-card-title">{{ $featured->title }}</h3>
                    <p class="v1-card-excerpt">{{ Str::limit(strip_tags($featured->body??''), 120) }}</p>
                    <div class="v1-card-author">
                        <div class="v1-card-avi">
                            @if($featured->user->avatar_url)
                                <img src="{{ $featured->user->avatar_url }}" alt="">
                            @else
                                {{ strtoupper(substr($featured->user->name??'?',0,2)) }}
                            @endif
                        </div>
                        <span style="font-size:.85rem;color:rgba(255,255,255,.7)">Par {{ $featured->user->name }}</span>
                    </div>
                </div>
            </a>
            @endif

            {{-- HORIZONTAL CARD (2col × 1row) --}}
            @if($side_posts->isNotEmpty())
            @php $p1 = $side_posts->first(); @endphp
            <a href="{{ route('posts.show', $p1->id) }}" class="v1-card v1-card-horiz glass" style="grid-column:span 2">
                <div class="v1-card-horiz-img">
                    @if($p1->primary_image_url)
                        <img src="{{ $p1->primary_image_url }}" alt="{{ $p1->title }}">
                    @endif
                    <div class="v1-card-horiz-img-overlay"></div>
                </div>
                <div class="v1-card-horiz-body">
                    <div class="v1-card-horiz-cat">{{ $p1->category_label ?? 'Article' }}</div>
                    <h3 class="v1-card-horiz-title">{{ $p1->title }}</h3>
                    <p class="v1-card-horiz-excerpt">{{ Str::limit(strip_tags($p1->body??''), 110) }}</p>
                </div>
            </a>
            @endif

            {{-- SMALL CARD --}}
            @if($side_posts->count() > 1)
            @php $p2 = $side_posts->get(1); @endphp
            <a href="{{ route('posts.show', $p2->id) }}" class="v1-card v1-card-small glass">
                <div>
                    <div class="v1-card-small-icon">
                        <i class="ph-fill ph-article" style="font-size:1.1rem;color:var(--ochre)"></i>
                    </div>
                    <div class="v1-card-small-cat">{{ $p2->category_label ?? 'Article' }}</div>
                    <div class="v1-card-small-title">{{ $p2->title }}</div>
                </div>
                <div class="v1-card-small-footer">
                    <span>{{ $p2->user->name }}</span>
                    <i class="ph-bold ph-arrow-right v1-card-small-arrow"></i>
                </div>
            </a>
            @endif

            {{-- FORUM LIVE CARD --}}
            <a href="{{ route('forum.index') }}" class="v1-card v1-card-forum">
                <div class="v1-forum-ping">
                    <span class="v1-forum-ping-outer"></span>
                    <span class="v1-forum-ping-inner"></span>
                </div>
                <div>
                    <div class="v1-forum-head">
                        <i class="ph-fill ph-users-three"></i> En direct du Forum
                    </div>
                    @foreach($recentThreads->take(2) as $t)
                    <div class="v1-forum-thread">
                        <div class="v1-forum-thread-title">"{{ $t->title }}"</div>
                        <div class="v1-forum-thread-meta">{{ $t->replies_count }} réponses • {{ $t->created_at->diffForHumans() }}</div>
                    </div>
                    @endforeach
                </div>
                <div class="v1-forum-cta">Rejoindre la conversation</div>
            </a>

        </div>
    </section>

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

{{-- ══ MOBILE NAV ══ --}}
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
    }, { threshold: .12 });
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
