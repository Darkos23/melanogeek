@extends('layouts.app')

@section('title', 'MelanoGeek — La Culture Geek, Vue d\'Afrique')
@section('meta_description', 'MelanoGeek — La culture geek vue d\'Afrique. Articles, débats et reviews autour du manga, gaming, tech, cinéma et de la culture nerd africaine.')
@section('og_title', 'MelanoGeek — La Culture Geek, Vue d\'Afrique')
@section('og_description', 'Articles, débats et reviews autour du manga, gaming, tech, cinéma et de la culture nerd africaine.')
@section('canonical', route('home'))

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   HOMEPAGE REDESIGN — V1 Dark × V2 Structure
═══════════════════════════════════════════════ */

.hp { overflow-x: hidden; }

/* ══ HERO ══ */
.hp-hero {
    max-width: 1280px; margin: 0 auto;
    padding: 52px 52px 64px;
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 40px;
    align-items: center;
    position: relative;
}
.hp-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 70% at 10% 70%, rgba(200,82,42,.12) 0%, transparent 60%),
        radial-gradient(ellipse 40% 50% at 90% 20%, rgba(212,168,67,.07) 0%, transparent 55%);
    pointer-events: none; z-index: 0;
}
.hp-hero > * { position: relative; z-index: 1; }
@media (max-width: 1024px) {
    .hp-hero { grid-template-columns: 1fr; padding: 40px 28px 52px; }
    .hp-hero-right { display: none; }
}
@media (max-width: 600px) { .hp-hero { padding: 32px 16px 44px; } }

.hp-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase;
    color: var(--terra);
    margin-bottom: 20px;
}
.hp-hero-eyebrow::before {
    content: ''; display: block;
    width: 22px; height: 1px; background: var(--terra); opacity: .8;
}
.hp-h1 {
    font-family: var(--font-head);
    font-size: clamp(2.6rem, 5vw, 4.4rem);
    font-weight: 800; line-height: 1.04; letter-spacing: -.04em;
    color: rgba(255,255,255,.93); margin: 0 0 20px;
}
.hp-h1-accent {
    background: linear-gradient(135deg, #D4A843 0%, #f0c060 40%, #C8522A 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.hp-sub {
    font-size: 1rem; line-height: 1.7; color: rgba(255,255,255,.45);
    max-width: 480px; margin: 0 0 32px;
}
.hp-ctas { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.hp-btn-main {
    background: var(--terra); color: white !important;
    border: none; padding: 0 24px; height: 46px; border-radius: 9999px;
    font-family: var(--font-head); font-size: .85rem; font-weight: 700;
    text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    transition: background .15s, transform .15s;
}
.hp-btn-main:hover { background: var(--accent); transform: translateY(-1px); }
.hp-btn-ghost {
    background: transparent; color: rgba(255,255,255,.5) !important;
    border: 1px solid rgba(255,255,255,.14); padding: 0 22px; height: 46px; border-radius: 9999px;
    font-family: var(--font-head); font-size: .85rem; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    transition: color .15s, border-color .15s;
}
.hp-btn-ghost:hover { color: rgba(255,255,255,.85) !important; border-color: rgba(255,255,255,.28); }

/* Hero right — featured image card */
.hp-hero-card {
    display: block; text-decoration: none;
    border-radius: 22px; overflow: hidden;
    background: var(--bg-card2);
    border: 1px solid var(--border);
    aspect-ratio: 3/4; position: relative;
    transition: border-color .25s, box-shadow .25s;
}
.hp-hero-card:hover { border-color: var(--border-hover); box-shadow: 0 24px 64px rgba(0,0,0,.6); }
.hp-hero-card-img { position: absolute; inset: 0; }
.hp-hero-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .7s cubic-bezier(.2,.8,.2,1); }
.hp-hero-card:hover .hp-hero-card-img img { transform: scale(1.05); }
.hp-hero-card-gradient {
    position: absolute; inset: 0;
    background: linear-gradient(135deg,#1a0a04,#5a2010,#c84818);
}
.hp-hero-card-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(8,4,2,.97) 0%, rgba(8,4,2,.5) 50%, rgba(8,4,2,.05) 100%);
}
.hp-hero-card-badge {
    position: absolute; top: 16px; left: 16px; z-index: 3;
    font-family: 'JetBrains Mono', monospace; font-size: .5rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    padding: 4px 10px; border-radius: 100px;
    background: var(--terra); color: white;
}
.hp-hero-card-new {
    position: absolute; top: 16px; right: 16px; z-index: 3;
    font-family: 'JetBrains Mono', monospace; font-size: .5rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    padding: 4px 10px; border-radius: 100px;
    background: rgba(0,0,0,.55); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.12); color: rgba(255,255,255,.6);
    display: flex; align-items: center; gap: 5px;
}
.hp-hero-card-new-dot {
    width: 5px; height: 5px; border-radius: 50%; background: #22c55e;
    box-shadow: 0 0 6px rgba(34,197,94,.7);
}
.hp-hero-card-info {
    position: absolute; bottom: 0; left: 0; right: 0;
    padding: 22px 20px; z-index: 2;
}
.hp-hero-card-cat {
    font-family: 'JetBrains Mono', monospace; font-size: .52rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase; color: var(--terra);
    display: block; margin-bottom: 8px;
}
.hp-hero-card-title {
    font-family: var(--font-head); font-size: 1.05rem; font-weight: 700;
    line-height: 1.3; color: rgba(255,255,255,.9);
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.hp-hero-card-meta {
    margin-top: 10px; padding-top: 10px;
    border-top: 1px solid rgba(255,255,255,.1);
    font-family: 'JetBrains Mono', monospace; font-size: .55rem; color: rgba(255,255,255,.3);
    display: flex; align-items: center; gap: 7px;
}

/* ══ TICKER ══ */
.hp-ticker {
    background: var(--bg-card); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
    overflow: hidden; white-space: nowrap; position: relative;
    display: flex; align-items: stretch; width: 100%;
}
.hp-ticker-label {
    flex-shrink: 0; display: flex; align-items: center;
    padding: 0 18px; background: var(--terra);
    font-family: 'JetBrains Mono', monospace; font-size: .58rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase; color: white; gap: 7px; z-index: 3;
}
.hp-ticker-dot { width: 6px; height: 6px; background: white; border-radius: 50%; animation: tickerPulse 1.4s ease-in-out infinite; }
@keyframes tickerPulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }
.hp-ticker-track { flex: 1; overflow: hidden; position: relative; }
.hp-ticker-track::after { content:'';position:absolute;right:0;top:0;bottom:0;width:60px;z-index:2;background:linear-gradient(-90deg,var(--bg-card),transparent); }
.hp-ticker-t { display:inline-flex;animation:tickr 40s linear infinite;padding:11px 0; }
.hp-ticker-t:hover { animation-play-state:paused; }
@keyframes tickr { from{transform:translateX(0)} to{transform:translateX(-50%)} }
.hp-tt {
    font-family:'JetBrains Mono',monospace; font-size:.6rem; font-weight:500;
    letter-spacing:.06em; color:var(--text-muted);
    padding:0 32px; display:inline-flex; align-items:center; gap:12px;
    text-decoration:none; transition:color .18s;
}
.hp-tt:hover { color:var(--cream); }
.hp-tt-type { font-weight:700;letter-spacing:.1em;text-transform:uppercase;font-size:.55rem;flex-shrink:0; }
.hp-tt-type.blog  { color:var(--terra); }
.hp-tt-type.forum { color:var(--gold); }
.hp-tt-title { max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }

/* ══ STATS BAR ══ */
.hp-stats {
    display: flex; align-items: stretch; border-bottom: 1px solid var(--border);
    overflow-x: auto; scrollbar-width: none;
}
.hp-stats::-webkit-scrollbar { display: none; }
.hp-stats-item {
    flex: 1; min-width: 90px;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 14px 12px; border-right: 1px solid var(--border); text-align: center;
}
.hp-stats-item:last-child { border-right: none; }
.hp-stats-n {
    font-family: 'DM Serif Display', serif; font-size: 1.3rem;
    color: rgba(255,255,255,.8); line-height: 1;
}
.hp-stats-l {
    font-family: 'JetBrains Mono', monospace; font-size: .5rem;
    letter-spacing: .09em; text-transform: uppercase; color: var(--text-faint); margin-top: 4px;
}

/* ══ LAYOUT PRINCIPAL : 8 + 4 ══ */
.hp-main {
    max-width: 1280px; margin: 0 auto;
    padding: 56px 52px 72px;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 52px;
    align-items: start;
}
@media (max-width: 1024px) { .hp-main { grid-template-columns: 1fr; padding: 40px 28px 56px; gap: 40px; } }
@media (max-width: 600px)  { .hp-main { padding: 32px 16px 48px; } }

/* ── Section header ── */
.hp-sec-header {
    display: flex; align-items: center; gap: 14px; margin-bottom: 28px;
}
.hp-sec-num {
    font-family: 'JetBrains Mono', monospace; font-size: .58rem;
    letter-spacing: .14em; text-transform: uppercase; color: var(--terra); flex-shrink: 0;
}
.hp-sec-title {
    font-family: 'JetBrains Mono', monospace; font-size: .65rem;
    letter-spacing: .1em; text-transform: uppercase; color: var(--text-muted); flex-shrink: 0;
}
.hp-sec-line { flex: 1; height: 1px; background: var(--border); }
.hp-sec-link {
    font-family: 'JetBrains Mono', monospace; font-size: .58rem;
    letter-spacing: .08em; text-transform: uppercase;
    color: var(--text-faint) !important; text-decoration: none; white-space: nowrap; flex-shrink: 0;
    transition: color .18s;
}
.hp-sec-link:hover { color: var(--terra) !important; }

/* ── Category pills ── */
.hp-pills {
    display: flex; gap: 6px; flex-wrap: nowrap;
    overflow-x: auto; scrollbar-width: none; padding-bottom: 2px; margin-bottom: 28px;
}
.hp-pills::-webkit-scrollbar { display: none; }
.hp-pill {
    flex-shrink: 0; white-space: nowrap;
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 100px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase;
    text-decoration: none; border: 1px solid var(--border);
    background: transparent; color: rgba(255,255,255,.38);
    transition: all .18s;
}
.hp-pill:hover { border-color: rgba(255,255,255,.2); color: rgba(255,255,255,.75); background: rgba(255,255,255,.04); }
.hp-pill.active { background: var(--terra); border-color: var(--terra); color: white; }

/* ── Cover story (featured) ── */
.hp-cover {
    display: block; text-decoration: none;
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 18px;
    overflow: hidden; margin-bottom: 28px;
    transition: border-color .25s, box-shadow .25s, transform .25s;
}
.hp-cover:hover { border-color: var(--border-hover); box-shadow: 0 20px 56px rgba(0,0,0,.5); transform: translateY(-2px); }
.hp-cover-img {
    width: 100%; aspect-ratio: 21/9;
    background: linear-gradient(135deg,#1a0a04,#5a2010,#c84818);
    overflow: hidden; position: relative;
}
.hp-cover-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .7s cubic-bezier(.2,.8,.2,1); }
.hp-cover:hover .hp-cover-img img { transform: scale(1.03); }
.hp-cover-img-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(10,6,3,.7) 0%, transparent 50%);
}
.hp-cover-body { padding: 28px 32px 32px; }
.hp-cover-tags {
    display: flex; align-items: center; gap: 10px;
    font-family: 'JetBrains Mono', monospace; font-size: .58rem; font-weight: 700;
    letter-spacing: .14em; text-transform: uppercase; margin-bottom: 14px;
}
.hp-cover-label { color: var(--terra); padding: 3px 9px; border: 1px solid var(--terra); border-radius: 3px; }
.hp-cover-cat { color: var(--text-faint); }
.hp-cover-title {
    font-family: var(--font-head);
    font-size: clamp(1.5rem, 2.8vw, 2.1rem);
    font-weight: 800; line-height: 1.1; letter-spacing: -.03em;
    color: var(--text); margin: 0 0 12px;
    transition: color .2s;
}
.hp-cover:hover .hp-cover-title { color: var(--terra); }
.hp-cover-excerpt {
    font-size: .88rem; line-height: 1.65; color: var(--text-muted);
    margin: 0 0 18px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.hp-cover-byline {
    display: flex; align-items: center; gap: 9px;
    font-family: 'JetBrains Mono', monospace; font-size: .6rem; color: var(--text-faint);
    padding-top: 14px; border-top: 1px solid var(--border);
}
.hp-cover-avi {
    width: 22px; height: 22px; border-radius: 50%;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    display: flex; align-items: center; justify-content: center;
    font-size: .55rem; font-weight: 700; color: white; flex-shrink: 0; overflow: hidden;
}
.hp-cover-avi img { width: 100%; height: 100%; object-fit: cover; }

/* ── Article grid (3 cols) ── */
.hp-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 1px; background: var(--border);
    border: 1px solid var(--border); border-radius: 14px; overflow: hidden;
}
@media (max-width: 900px) { .hp-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 500px) { .hp-grid { grid-template-columns: 1fr; } }

.hp-card {
    background: var(--bg-card); padding: 0;
    text-decoration: none; display: flex; flex-direction: column;
    transition: background .2s;
}
.hp-card:hover { background: var(--bg-hover); }
.hp-card-thumb {
    width: 100%; aspect-ratio: 16/9; overflow: hidden; position: relative; flex-shrink: 0;
}
.hp-card-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
.hp-card:hover .hp-card-thumb img { transform: scale(1.04); }
.hp-card-thumb-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
}
.hp-card-badge {
    position: absolute; top: 8px; left: 8px; z-index: 2;
    font-family: 'JetBrains Mono', monospace; font-size: .5rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    padding: 3px 8px; border-radius: 100px;
    background: rgba(0,0,0,.55); backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.1); color: rgba(255,255,255,.8);
}
.hp-card-body { padding: 16px 18px 18px; flex: 1; display: flex; flex-direction: column; gap: 8px; }
.hp-card-cat {
    font-family: 'JetBrains Mono', monospace; font-size: .54rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase; color: var(--terra);
}
.hp-card-title {
    font-family: var(--font-head); font-size: .95rem; font-weight: 700;
    line-height: 1.3; color: rgba(255,255,255,.88);
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .2s; flex: 1;
}
.hp-card:hover .hp-card-title { color: var(--gold); }
.hp-card-meta {
    display: flex; align-items: center; gap: 6px;
    font-family: 'JetBrains Mono', monospace; font-size: .56rem; color: var(--text-faint);
    padding-top: 10px; border-top: 1px solid var(--border); margin-top: auto;
}
.hp-card-dot { width: 2px; height: 2px; background: var(--text-faint); border-radius: 50%; }

/* ══ SIDEBAR ══ */
.hp-sidebar { position: sticky; top: 80px; display: flex; flex-direction: column; gap: 24px; }

.hp-widget {
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden;
}
.hp-widget-head {
    padding: 11px 16px; border-bottom: 1px solid var(--border);
    font-family: 'JetBrains Mono', monospace; font-size: .58rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase; color: var(--gold);
    display: flex; align-items: center; justify-content: space-between;
}
.hp-widget-live {
    display: flex; align-items: center; gap: 5px;
    font-size: .52rem; color: rgba(255,255,255,.3);
}
.hp-widget-live-dot {
    width: 5px; height: 5px; border-radius: 50%; background: #22c55e;
    box-shadow: 0 0 5px rgba(34,197,94,.6);
    animation: tickerPulse 2s ease-in-out infinite;
}

/* Forum threads in sidebar */
.hp-thread {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 11px 16px; border-bottom: 1px solid var(--border);
    text-decoration: none; transition: background .15s;
}
.hp-thread:last-child { border-bottom: none; }
.hp-thread:hover { background: rgba(255,255,255,.03); }
.hp-thread-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: rgba(255,255,255,.05); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0; margin-top: 1px;
}
.hp-thread-body { flex: 1; min-width: 0; }
.hp-thread-title {
    font-size: .7rem; font-weight: 600; line-height: 1.35; color: rgba(255,255,255,.72);
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .15s;
}
.hp-thread:hover .hp-thread-title { color: rgba(255,255,255,.95); }
.hp-thread-meta {
    font-family: 'JetBrains Mono', monospace; font-size: .54rem; color: var(--text-faint);
    margin-top: 4px; display: flex; align-items: center; gap: 6px;
}
.hp-thread-replies {
    background: rgba(255,255,255,.06); border-radius: 4px;
    padding: 1px 6px; font-size: .52rem; color: var(--text-muted); font-weight: 600;
    font-family: 'JetBrains Mono', monospace;
}
.hp-widget-footer {
    padding: 10px 16px; border-top: 1px solid var(--border);
}
.hp-widget-footer a {
    font-family: 'JetBrains Mono', monospace; font-size: .56rem; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase; color: var(--terra) !important;
    text-decoration: none; display: flex; align-items: center; gap: 6px;
    transition: opacity .15s;
}
.hp-widget-footer a:hover { opacity: .75; }

/* Popular posts in sidebar */
.hp-popular-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 12px 16px; border-bottom: 1px solid var(--border);
    text-decoration: none; transition: background .15s;
}
.hp-popular-item:last-child { border-bottom: none; }
.hp-popular-item:hover { background: rgba(255,255,255,.03); }
.hp-popular-rank {
    font-family: 'DM Serif Display', serif; font-size: 1.3rem; font-weight: 400;
    color: var(--terra); opacity: .55; line-height: 1; min-width: 28px; flex-shrink: 0;
}
.hp-popular-title {
    font-size: .7rem; font-weight: 600; line-height: 1.35; color: rgba(255,255,255,.72);
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .15s;
}
.hp-popular-item:hover .hp-popular-title { color: var(--gold); }
.hp-popular-meta {
    font-family: 'JetBrains Mono', monospace; font-size: .53rem; color: var(--text-faint); margin-top: 4px;
}

/* ══ CATÉGORIES (full width) ══ */
.hp-cats-section { border-top: 1px solid var(--border); padding: 56px 52px; }
.hp-cats-inner { max-width: 1280px; margin: 0 auto; }
@media (max-width: 1024px) { .hp-cats-section { padding: 44px 28px; } }
@media (max-width: 600px)  { .hp-cats-section { padding: 36px 16px; } }

.hp-cat-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;
}
@media (max-width: 1024px) { .hp-cat-grid { grid-template-columns: repeat(4,1fr); } }
@media (max-width: 640px)  { .hp-cat-grid { grid-template-columns: repeat(2,1fr); } }

.hp-cat-card {
    padding: 20px 18px 18px; border-radius: 12px;
    border: 1px solid var(--border); background: var(--bg-card);
    text-decoration: none; display: flex; flex-direction: column; gap: 0;
    position: relative; overflow: hidden; min-height: 110px; justify-content: flex-end;
    transition: transform .22s, box-shadow .22s, border-color .22s;
}
.hp-cat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.3); border-color: var(--border-hover); }
.hp-cat-icon { font-size: 1.8rem; line-height: 1; margin-bottom: 10px; }
.hp-cat-name { font-family: var(--font-head); font-size: .9rem; font-weight: 700; color: rgba(255,255,255,.85); letter-spacing: -.01em; }
.hp-cat-count { font-family: 'JetBrains Mono', monospace; font-size: .54rem; letter-spacing: .08em; color: rgba(255,255,255,.3); text-transform: uppercase; margin-top: 3px; }

/* ══ CTA ══ */
.hp-cta-section {
    padding: 80px 52px; text-align: center; position: relative; overflow: hidden;
    border-top: 1px solid var(--border);
}
.hp-cta-section::before {
    content: ''; position: absolute; top: 50%; left: 50%;
    transform: translate(-50%,-50%); width: 700px; height: 500px;
    background: radial-gradient(ellipse, rgba(212,168,67,.09) 0%, transparent 65%);
    pointer-events: none;
}
.hp-cta-inner { position: relative; z-index: 1; max-width: 520px; margin: 0 auto; }
.hp-cta-eyebrow {
    font-family: 'JetBrains Mono', monospace; font-size: .58rem; letter-spacing: .16em;
    text-transform: uppercase; color: var(--gold); margin-bottom: 18px;
}
.hp-cta-title {
    font-family: var(--font-head); font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 800; line-height: 1.1; letter-spacing: -.04em;
    color: rgba(255,255,255,.92); margin-bottom: 14px;
}
.hp-cta-desc { font-size: .9rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 28px; }

/* ══ NEWSLETTER ══ */
.hp-newsletter {
    border-top: 1px solid var(--border);
    padding: 64px 52px; max-width: 1280px; margin: 0 auto;
    display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center;
}
@media (max-width: 768px) { .hp-newsletter { grid-template-columns:1fr; padding:44px 20px; gap:24px; } }
@media (max-width: 1024px) { .hp-newsletter { padding:52px 28px; } }
.hp-nl-eyebrow {
    font-family: 'JetBrains Mono', monospace; font-size: .56rem; font-weight: 700;
    letter-spacing: .14em; text-transform: uppercase; color: var(--gold);
    display: flex; align-items: center; gap: 8px; margin-bottom: 12px;
}
.hp-nl-eyebrow::before { content:'';display:block;width:18px;height:1px;background:var(--gold);opacity:.7; }
.hp-nl-title {
    font-family: var(--font-head); font-size: clamp(1.4rem,2.5vw,1.9rem);
    font-weight: 800; letter-spacing: -.04em; line-height: 1.1; color: var(--cream);
}
.hp-nl-title span { color: var(--terra); }
.hp-nl-sub { font-size: .85rem; color: var(--text-muted); line-height: 1.65; margin-top: 8px; }
.hp-nl-form { display:flex; gap:8px; flex-wrap:wrap; margin-top:4px; }
.hp-nl-input {
    flex:1; background:var(--bg-card); border:1px solid var(--border);
    border-radius:100px; padding:12px 22px; color:var(--text);
    font-family:var(--font-body); font-size:.88rem; outline:none;
    transition:border-color .2s, box-shadow .2s; min-width: 180px;
}
.hp-nl-input:focus { border-color:var(--terra); box-shadow:0 0 0 3px rgba(200,82,42,.1); }
.hp-nl-btn {
    background:var(--terra); color:white; border:none; border-radius:100px;
    padding:12px 24px; font-family:var(--font-body); font-size:.88rem; font-weight:700;
    cursor:pointer; transition:background .2s, transform .15s; white-space:nowrap;
}
.hp-nl-btn:hover { background:var(--accent); transform:translateY(-1px); }
.hp-nl-note { font-size:.7rem; color:var(--text-faint); margin-top:8px; }
.hp-nl-note strong { color:var(--gold); }

/* ══ FOOTER ══ */
.hp-footer {
    border-top: 1px solid var(--border);
    padding: 28px 52px; max-width: 1280px; margin: 0 auto;
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; flex-wrap: wrap;
}
.hp-footer-copy { font-family:'JetBrains Mono',monospace; font-size:.56rem; letter-spacing:.08em; color:var(--text-faint); }
.hp-footer-links { display:flex; gap:20px; list-style:none; }
.hp-footer-links a { font-family:'JetBrains Mono',monospace; font-size:.56rem; letter-spacing:.08em; text-transform:uppercase; color:var(--text-faint); text-decoration:none; transition:color .15s; }
.hp-footer-links a:hover { color:var(--gold); }
@media (max-width: 640px) { .hp-footer { flex-direction:column; text-align:center; padding:24px 16px; } .hp-footer-links { flex-wrap:wrap; justify-content:center; } }
@media (max-width: 1024px) { .hp-cta-section { padding:60px 28px; } }

/* ══ MOBILE BOTTOM NAV ══ */
.hp-mbnav {
    display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 200;
    background: rgba(18,12,8,.94); backdrop-filter: blur(16px) saturate(1.5);
    border-top: 1px solid var(--border);
    padding: 6px 0 max(6px, env(safe-area-inset-bottom));
}
@media (max-width: 768px) { .hp-mbnav { display: flex; } body { padding-bottom: 62px; } }
.mbn-item {
    flex: 1; display: flex; flex-direction: column; align-items: center; gap: 3px;
    padding: 6px 4px; text-decoration: none;
    color: rgba(255,255,255,.3); font-family:'JetBrains Mono',monospace;
    font-size: .48rem; letter-spacing: .06em; text-transform: uppercase; transition: color .15s;
}
.mbn-item.active, .mbn-item:hover { color: var(--terra); }
.mbn-item svg { width:20px; height:20px; }
</style>
@endpush

@section('content')
<div class="hp">

{{-- ══ HERO ══ --}}
<section class="hp-hero">
    {{-- Gauche --}}
    <div>
        <div class="hp-hero-eyebrow">La culture geek africaine</div>
        <h1 class="hp-h1">
            Articles, débats<br>
            <span class="hp-h1-accent">vue d'Afrique.</span>
        </h1>
        <p class="hp-sub">
            Animés, gaming, cinéma, tech, Web3 et mythologies africaines —
            par et pour la communauté geek du continent.
        </p>
        <div class="hp-ctas">
            <a href="{{ route('blog.index') }}" class="hp-btn-main">
                Explorer les articles
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('forum.index') }}" class="hp-btn-ghost">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Forum communautaire
            </a>
        </div>
    </div>

    {{-- Droite — featured article card --}}
    <div class="hp-hero-right">
        @if($featured)
        @php $featMins = max(1,(int)ceil(str_word_count(strip_tags($featured->body??''))/200)); @endphp
        <a href="{{ route('posts.show', $featured->id) }}" class="hp-hero-card">
            <div class="hp-hero-card-img">
                @if($featured->primary_image_url)
                    <img src="{{ $featured->primary_image_url }}" alt="{{ $featured->title }}">
                @else
                    <div class="hp-hero-card-gradient"></div>
                @endif
            </div>
            <div class="hp-hero-card-overlay"></div>
            @if($featured->category)
            <div class="hp-hero-card-badge">{{ $featured->category_label }}</div>
            @endif
            <div class="hp-hero-card-new">
                <span class="hp-hero-card-new-dot"></span> Nouveau
            </div>
            <div class="hp-hero-card-info">
                <div class="hp-hero-card-title">{{ $featured->title }}</div>
                <div class="hp-hero-card-meta">
                    <span>{{ $featured->user->name }}</span>
                    <span>·</span>
                    <span>{{ $featMins }} min</span>
                    <span>·</span>
                    <span>{{ $featured->created_at->diffForHumans(null, true) }}</span>
                </div>
            </div>
        </a>
        @endif
    </div>
</section>

{{-- ══ TICKER ══ --}}
<div class="hp-ticker">
    <div class="hp-ticker-label">
        <span class="hp-ticker-dot"></span> Live
    </div>
    <div class="hp-ticker-track">
        <div class="hp-ticker-t">
            @foreach(array_fill(0, 2, null) as $_)
            @foreach($recentPosts as $rp)
            <a href="{{ route('posts.show', $rp->id) }}" class="hp-tt">
                <span class="hp-tt-type blog">Blog</span>
                <span class="hp-tt-title">{{ $rp->title }}</span>
                <span style="color:var(--text-faint)">·</span>
                <span style="color:var(--text-faint);font-size:.55rem">{{ $rp->created_at->diffForHumans(null,true) }}</span>
            </a>
            @endforeach
            @foreach($recentThreads as $rt)
            <a href="{{ route('forum.show', $rt) }}" class="hp-tt">
                <span class="hp-tt-type forum">Forum</span>
                <span class="hp-tt-title">{{ $rt->title }}</span>
                <span style="color:var(--text-faint)">·</span>
                <span style="color:var(--text-faint);font-size:.55rem">{{ $rt->created_at->diffForHumans(null,true) }}</span>
            </a>
            @endforeach
            @endforeach
        </div>
    </div>
</div>

{{-- ══ STATS BAR ══ --}}
<div class="hp-stats">
    <div class="hp-stats-item">
        <div class="hp-stats-n" data-count="{{ $stats['users'] }}">{{ $stats['users'] }}</div>
        <div class="hp-stats-l">Membres</div>
    </div>
    <div class="hp-stats-item">
        <div class="hp-stats-n" data-count="{{ $stats['posts'] }}">{{ $stats['posts'] }}</div>
        <div class="hp-stats-l">Articles</div>
    </div>
    <div class="hp-stats-item">
        <div class="hp-stats-n" data-count="{{ $stats['comments'] }}">{{ $stats['comments'] }}</div>
        <div class="hp-stats-l">Contributions</div>
    </div>
    <div class="hp-stats-item">
        <div class="hp-stats-n" data-count="{{ $stats['visits'] }}">{{ $stats['visits'] }}</div>
        <div class="hp-stats-l">Visites</div>
    </div>
</div>

{{-- ══ LAYOUT PRINCIPAL 8+4 ══ --}}
<div class="hp-main">

    {{-- Colonne contenu (gauche) --}}
    <div>
        {{-- Section header --}}
        <div class="hp-sec-header">
            <span class="hp-sec-num">§ 01</span>
            <span class="hp-sec-title">À la une</span>
            <div class="hp-sec-line"></div>
            <a href="{{ route('blog.index') }}" class="hp-sec-link">Tous les articles →</a>
        </div>

        {{-- Pills catégories --}}
        <div class="hp-pills">
            <a href="{{ route('blog.index') }}" class="hp-pill active">✦ Tous</a>
            <a href="{{ route('blog.index') }}?category=manga-anime"   class="hp-pill">🎌 Animés</a>
            <a href="{{ route('blog.index') }}?category=gaming"        class="hp-pill">🎮 Gaming</a>
            <a href="{{ route('blog.index') }}?category=cinema-series" class="hp-pill">🎬 Cinéma</a>
            <a href="{{ route('blog.index') }}?category=tech"          class="hp-pill">🤖 Tech</a>
            <a href="{{ route('blog.index') }}?category=culture"       class="hp-pill">🚀 Afrofuturisme</a>
            <a href="{{ route('blog.index') }}?category=web3-economie" class="hp-pill">₿ Web3</a>
            <a href="{{ route('blog.index') }}?category=hardware"      class="hp-pill">🖥️ Hardware</a>
        </div>

        {{-- Cover story --}}
        @if($featured)
        @php
            $featExcerpt = Str::limit(strip_tags($featured->body ?? ''), 160);
            $featMins    = max(1,(int)ceil(str_word_count(strip_tags($featured->body??''))/200));
        @endphp
        <a href="{{ route('posts.show', $featured->id) }}" class="hp-cover" data-reveal>
            <div class="hp-cover-img">
                @if($featured->primary_image_url)
                    <img src="{{ $featured->primary_image_url }}" alt="{{ $featured->title }}">
                    <div class="hp-cover-img-overlay"></div>
                @endif
            </div>
            <div class="hp-cover-body">
                <div class="hp-cover-tags">
                    <span class="hp-cover-label">À la une</span>
                    @if($featured->category)
                    <span style="color:var(--text-faint)">·</span>
                    <span class="hp-cover-cat">{{ $featured->category_label }}</span>
                    @endif
                </div>
                <h2 class="hp-cover-title">{{ $featured->title }}</h2>
                @if($featExcerpt)
                <p class="hp-cover-excerpt">{{ $featExcerpt }}</p>
                @endif
                <div class="hp-cover-byline">
                    <div class="hp-cover-avi">
                        @if($featured->user->avatar_url)
                            <img src="{{ $featured->user->avatar_url }}" alt="">
                        @else
                            {{ strtoupper(substr($featured->user->name??'?',0,1)) }}
                        @endif
                    </div>
                    <span style="color:var(--text-muted)">{{ $featured->user->name }}</span>
                    <span>·</span>
                    <span>{{ $featured->created_at->format('d M Y') }}</span>
                    <span>·</span>
                    <span>{{ $featMins }} min</span>
                </div>
            </div>
        </a>
        @endif

        {{-- Grille articles --}}
        @if($side_posts->isNotEmpty())
        <div class="hp-grid">
            @foreach($side_posts->concat($popularPosts->take(3)) as $post)
            @php
                $mins    = max(1,(int)ceil(str_word_count(strip_tags($post->body??''))/200));
                $initial = strtoupper(substr($post->user->name??'?',0,1));
                $catGradients = [
                    'manga-anime'   => 'linear-gradient(135deg,#1a0a2e,#4a1a6e)',
                    'gaming'        => 'linear-gradient(135deg,#0a1a0a,#1a5a25)',
                    'tech'          => 'linear-gradient(135deg,#0a1628,#1a4cb8)',
                    'cinema-series' => 'linear-gradient(135deg,#1a0808,#7a1a1a)',
                    'web3-economie' => 'linear-gradient(135deg,#062a24,#0d6a5f)',
                    'culture'       => 'linear-gradient(135deg,#1a0a04,#7c3012)',
                    'hardware'      => 'linear-gradient(135deg,#111827,#374151)',
                    'carriere'      => 'linear-gradient(135deg,#2e1065,#7e22ce)',
                ];
                $cardBg = $catGradients[$post->category] ?? 'linear-gradient(135deg,#1a1a1a,#2a2a2a)';
            @endphp
            <a href="{{ route('posts.show', $post->id) }}" class="hp-card" data-reveal>
                <div class="hp-card-thumb" style="background:{{ $cardBg }}">
                    @if($post->primary_image_url)
                        <img src="{{ $post->primary_image_url }}" alt="{{ $post->title }}">
                    @endif
                    @if($post->category)
                    <div class="hp-card-badge">{{ $post->category_label }}</div>
                    @endif
                </div>
                <div class="hp-card-body">
                    <div class="hp-card-title">{{ $post->title }}</div>
                    <div class="hp-card-meta">
                        <span>{{ $post->user->name }}</span>
                        <span class="hp-card-dot"></span>
                        <span>{{ $mins }} min</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Sidebar (droite) --}}
    <aside class="hp-sidebar">

        {{-- Forum actif --}}
        @php
            $sideThreads = \App\Models\ForumThread::with('user')->orderByDesc('last_reply_at')->limit(5)->get();
        @endphp
        @if($sideThreads->isNotEmpty())
        <div class="hp-widget">
            <div class="hp-widget-head">
                Forum actif
                <span class="hp-widget-live"><span class="hp-widget-live-dot"></span> Live</span>
            </div>
            @foreach($sideThreads as $t)
            <a href="{{ route('forum.show', $t->id) }}" class="hp-thread">
                <div class="hp-thread-icon">{{ $t->category_icon }}</div>
                <div class="hp-thread-body">
                    <div class="hp-thread-title">{{ $t->title }}</div>
                    <div class="hp-thread-meta">
                        <span class="hp-thread-replies">{{ $t->replies_count }} rép.</span>
                        <span>{{ ($t->last_reply_at ?? $t->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
            @endforeach
            <div class="hp-widget-footer">
                <a href="{{ route('forum.index') }}">
                    Voir tout le forum
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
        @endif

        {{-- Plus lus --}}
        @if(isset($popularPosts) && $popularPosts->isNotEmpty())
        <div class="hp-widget">
            <div class="hp-widget-head">Plus lus</div>
            @foreach($popularPosts->take(5) as $i => $pp)
            <a href="{{ route('posts.show', $pp->id) }}" class="hp-popular-item">
                <div class="hp-popular-rank">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</div>
                <div>
                    <div class="hp-popular-title">{{ $pp->title }}</div>
                    <div class="hp-popular-meta">{{ $pp->user->name }} · {{ number_format($pp->views_count) }} vues</div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        {{-- Écrire --}}
        @auth
        <div class="hp-widget" style="padding:18px">
            <div style="font-family:'JetBrains Mono',monospace;font-size:.56rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:10px">Créer</div>
            <a href="{{ route('posts.create') }}" style="display:flex;align-items:center;gap:8px;padding:11px 16px;background:var(--terra);color:white;text-decoration:none;border-radius:100px;font-family:var(--font-head);font-size:.82rem;font-weight:700;transition:background .2s" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--terra)'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Écrire un article
            </a>
        </div>
        @endauth

    </aside>
</div>

{{-- ══ CATÉGORIES ══ --}}
<section class="hp-cats-section">
    <div class="hp-cats-inner">
        <div class="hp-sec-header" style="margin-bottom:24px">
            <span class="hp-sec-num">§ 02</span>
            <span class="hp-sec-title">Explorer par thème</span>
            <div class="hp-sec-line"></div>
        </div>
        @php
        $hpCats = [
            'manga-anime'   => ['🎌','Animés & mangas'],
            'gaming'        => ['🎮','Gaming & E-sport'],
            'cinema-series' => ['🎬','Cinéma & séries'],
            'tech'          => ['🤖','Tech & IA'],
            'web3-economie' => ['₿','Web3'],
            'culture'       => ['🚀','Afrofuturisme'],
            'hardware'      => ['🖥️','Hardware'],
            'carriere'      => ['💼','Carrière'],
        ];
        @endphp
        <div class="hp-cat-grid">
            @foreach($hpCats as $slug => [$icon, $label])
            @php $count = $category_counts[$slug] ?? 0; @endphp
            <a href="{{ route('blog.index') }}?category={{ $slug }}" class="hp-cat-card" data-reveal data-delay="{{ $loop->iteration }}">
                <div class="hp-cat-icon">{{ $icon }}</div>
                <div class="hp-cat-name">{{ $label }}</div>
                @if($count > 0)
                <div class="hp-cat-count">{{ $count }} article{{ $count > 1 ? 's' : '' }}</div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA ══ --}}
@guest
<section class="hp-cta-section">
    <div class="hp-cta-inner">
        <div class="hp-cta-eyebrow">Rejoindre</div>
        <div class="hp-cta-title">Tu es geek et africain·e ?<br>Tu es chez toi ici.</div>
        <p class="hp-cta-desc">Rejoins une communauté qui célèbre la culture geek africaine sans complexe. Crée ton compte gratuitement et commence à lire, écrire et débattre.</p>
        <div class="hp-ctas" style="justify-content:center">
            <a href="{{ route('register') }}" class="hp-btn-main">Créer mon compte gratuit</a>
            <a href="{{ route('login') }}" class="hp-btn-ghost">Se connecter</a>
        </div>
    </div>
</section>
@endguest

{{-- ══ NEWSLETTER ══ --}}
<div class="hp-newsletter" data-reveal>
    <div>
        <div class="hp-nl-eyebrow">Newsletter</div>
        <div class="hp-nl-title">Reste dans<br>la <span>boucle.</span></div>
        <p class="hp-nl-sub">Les meilleurs articles, débats et sorties geek — directement dans ta boîte mail. Pas de spam, jamais.</p>
    </div>
    <div>
        <form class="hp-nl-form" onsubmit="handleNl(event)">
            <input type="email" class="hp-nl-input" id="nlEmail" placeholder="ton@email.com" required autocomplete="email">
            <button type="submit" class="hp-nl-btn">S'inscrire</button>
        </form>
        <div class="hp-nl-note" id="nlNote">
            Rejoins <strong>{{ $stats['users'] }} membres</strong> — désinscription en 1 clic.
        </div>
    </div>
</div>

{{-- ══ FOOTER ══ --}}
<footer class="hp-footer">
    <div class="hp-footer-copy">© {{ date('Y') }} MelanoGeek — La culture geek, vue d'Afrique.</div>
    <ul class="hp-footer-links">
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
        <li><a href="{{ route('forum.index') }}">Forum</a></li>
        <li><a href="{{ route('about') }}">À propos</a></li>
    </ul>
</footer>

{{-- ══ MOBILE BOTTOM NAV ══ --}}
<nav class="hp-mbnav">
    <a href="{{ route('home') }}" class="mbn-item active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Accueil
    </a>
    <a href="{{ route('blog.index') }}" class="mbn-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        Articles
    </a>
    <a href="{{ route('forum.index') }}" class="mbn-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Forum
    </a>
    @auth
    <a href="{{ route('profile.show', auth()->user()->username ?? auth()->id()) }}" class="mbn-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Profil
    </a>
    @else
    <a href="{{ route('register') }}" class="mbn-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
        Rejoindre
    </a>
    @endauth
</nav>

</div>
@endsection

@push('scripts')
<script>
(function(){
    /* Compteurs animés */
    if (!window.matchMedia('(prefers-reduced-motion:reduce)').matches) {
        function animCount(el) {
            const t = parseInt(el.textContent.replace(/\D/g,''),10);
            if (!t) return;
            const dur = 1200, s = performance.now(), ease = t => 1-Math.pow(1-t,3);
            (function tick(now){
                const p = Math.min((now-s)/dur,1);
                el.textContent = Math.round(ease(p)*t);
                if (p<1) requestAnimationFrame(tick); else el.textContent = t;
            })(performance.now());
        }
        const obs = new IntersectionObserver(e=>e.forEach(x=>{if(x.isIntersecting){animCount(x.target);obs.unobserve(x.target);}}),{threshold:.5});
        document.querySelectorAll('.hp-stats-n').forEach(el=>obs.observe(el));
    }

    /* Newsletter */
    window.handleNl = function(e){
        e.preventDefault();
        const btn=e.target.querySelector('.hp-nl-btn'), note=document.getElementById('nlNote');
        btn.textContent='✓ Inscrit !'; btn.style.background='#2A7A48'; btn.disabled=true;
        if(note) note.innerHTML='Tu es sur la liste. À bientôt dans ta boîte mail.';
    };
})();
</script>
@endpush
