@extends('layouts.app')

@section('title', 'MelanoGeek — La Culture Geek, Vue d\'Afrique')
@section('meta_description', 'MelanoGeek — La culture geek vue d\'Afrique. Articles, débats et reviews autour du manga, gaming, tech, cinéma et de la culture nerd africaine.')
@section('og_title', 'MelanoGeek — La Culture Geek, Vue d\'Afrique')
@section('og_description', 'Articles, débats et reviews autour du manga, gaming, tech, cinéma et de la culture nerd africaine.')
@section('canonical', route('home'))

@push('styles')
<style>
/* ═══════════════════════════════════════════════════
   LANDING PAGE — DARK EDITORIAL
   Inspiré Pitchfork dark · Vercel · The Ringer
   Police titre : var(--font-head)
   Accent primaire : or (#D4A843)
═══════════════════════════════════════════════════ */

/* ── Fond neutre, pas de motifs hérités ── */

/* ── Variable serif ── */
.lp { --serif: 'DM Serif Display', 'Georgia', serif; overflow-x: hidden; width: 100%; }

/* ══ HERO — EDITORIAL ASYMÉTRIQUE ══ */
.lp-hero {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 0;
    max-width: 1280px;
    margin: 0 auto;
    padding: 56px 52px 80px;
    position: relative;
    overflow: hidden;
    align-items: center;
}
.lp-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 70% 80% at 15% 60%, rgba(200,82,42,0.09) 0%, transparent 65%),
        radial-gradient(ellipse 50% 70% at 85% 30%, rgba(212,168,67,0.06) 0%, transparent 60%);
    pointer-events: none; z-index: 0;
}
.lp-hero > * { position: relative; z-index: 1; }
@media (max-width: 1100px) {
    .lp-hero { grid-template-columns: 1fr; padding: 44px 28px 60px; }
    .lp-hero-right { display: none; }
}
@media (max-width: 600px) { .lp-hero { padding: 34px 20px 52px; } }

.lp-hero-left {
    display: flex;
    flex-direction: column;
    gap: 28px;
    padding-right: 64px;
}
@media (max-width: 1100px) { .lp-hero-left { padding-right: 0; } }

/* Vol. label */
.lp-vol {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .62rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--gold);
}
.lp-vol::before {
    content: '';
    display: block;
    width: 28px;
    height: 1px;
    background: var(--gold);
    opacity: .7;
}

/* Titre principal */
.lp-h1 {
    font-family: var(--font-head);
    font-size: clamp(2.8rem, 5vw, 4.6rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -.04em;
    color: rgba(255,255,255,.94);
    margin: 0;
}
.lp-h1-gold {
    background: linear-gradient(135deg, #D4A843 0%, #f0c060 40%, #C8522A 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Filet or sous le titre */
.lp-hero-rule {
    width: 64px;
    height: 2px;
    background: var(--gold);
    opacity: .6;
    border: none;
    margin: 0;
}

/* Sous-titre */
.lp-sub {
    font-family: var(--font-body);
    font-size: 1.05rem;
    line-height: 1.65;
    color: rgba(255,255,255,.50);
    max-width: 440px;
    margin: 0;
}

/* CTAs */
.lp-ctas {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Bouton principal — blanc pill avec halo coloré (identique ngrok) */
.lp-btn-main {
    position: relative;
    background: rgba(255,255,255,.90);
    color: rgba(0,0,0,.90) !important;
    border: none;
    padding: 0 22px;
    height: 46px;
    border-radius: 9999px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .78rem;
    font-weight: 500;
    letter-spacing: .05em;
    text-transform: uppercase;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background .15s, transform .15s;
    overflow: visible;
    user-select: none;
}
.lp-btn-main::after {
    content: '';
    position: absolute;
    inset: 1px;
    border-radius: 9999px;
    background: conic-gradient(
        rgb(252,211,77),
        rgb(190,242,100),
        rgb(110,231,183),
        rgb(125,211,252),
        rgb(216,180,254),
        rgb(253,164,175),
        rgb(252,211,77)
    );
    filter: url(#btn-glow-blur);
    opacity: .85;
    z-index: -1;
    transition: transform .2s;
}
.lp-btn-main:hover { background: #fff; }
.lp-btn-main:hover::after { transform: scale(1.06); }
.lp-btn-main:active { transform: scale(.97); }

/* Bouton secondaire — ghost sobre */
.lp-btn-ghost {
    background: transparent;
    color: rgba(255,255,255,.55) !important;
    border: 1px solid rgba(255,255,255,.14);
    padding: 0 22px;
    height: 46px;
    border-radius: 9999px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .78rem;
    font-weight: 500;
    letter-spacing: .05em;
    text-transform: uppercase;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: color .15s, border-color .15s;
}
.lp-btn-ghost:hover {
    color: rgba(255,255,255,.85) !important;
    border-color: rgba(255,255,255,.28);
}

/* Scroll hint */
.lp-scroll-hint {
    width: 26px; height: 42px;
    border: 1.5px solid rgba(255,255,255,0.15);
    border-radius: 100px;
    display: flex; align-items: flex-start;
    justify-content: center; padding-top: 5px;
    margin-top: 8px;
}
.lp-scroll-dot {
    width: 4px; height: 8px;
    background: rgba(255,255,255,0.45);
    border-radius: 100px;
    animation: scrollBounce 1.8s ease-in-out infinite;
}
@keyframes scrollBounce {
    0%, 100% { transform: translateY(0); opacity: .45; }
    50%       { transform: translateY(12px); opacity: .9; }
}
@media (prefers-reduced-motion: reduce) { .lp-scroll-dot { animation: none; } }

/* ── Colonne droite — encart éditorial ── */
.lp-hero-right {
    border-left: 1px solid var(--border);
    padding-left: 52px;
    display: flex;
    flex-direction: column;
    gap: 32px;
    align-self: stretch;
    justify-content: center;
}
.lp-ed-pullquote {
    border-left: 2px solid var(--gold);
    padding-left: 18px;
}
.lp-ed-pullquote-text {
    font-family: var(--serif);
    font-size: 1.05rem;
    font-style: italic;
    line-height: 1.55;
    color: rgba(255,255,255,.65);
}
.lp-ed-pullquote-attr {
    margin-top: 10px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-faint);
}
.lp-ed-stats {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.lp-ed-stat {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
    border-bottom: 1px solid var(--border);
    padding-bottom: 12px;
}
.lp-ed-stat:last-child { border-bottom: none; padding-bottom: 0; }
.lp-ed-stat-n {
    font-family: var(--serif);
    font-size: 1.6rem;
    color: rgba(255,255,255,.80);
    line-height: 1;
}
.lp-ed-stat-l {
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-faint);
    text-align: right;
}

/* ══ TICKER ACTIVITÉ ══ */
.lp-ticker {
    background: var(--bg-card);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: 0;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    display: flex;
    align-items: stretch;
    max-width: 100vw;
    width: 100%;
}
.lp-ticker-label {
    flex-shrink: 0;
    display: flex; align-items: center;
    padding: 0 18px;
    background: var(--terra);
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: white;
    gap: 7px;
    z-index: 3;
}
.lp-ticker-label-dot {
    width: 6px; height: 6px;
    background: white; border-radius: 50%;
    animation: tickerPulse 1.4s ease-in-out infinite;
}
@keyframes tickerPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .4; transform: scale(.7); }
}
.lp-ticker-track {
    flex: 1;
    overflow: hidden;
    position: relative;
}
.lp-ticker-track::after {
    content: ''; position: absolute;
    right: 0; top: 0; bottom: 0; width: 60px; z-index: 2;
    background: linear-gradient(-90deg, var(--bg-card), transparent);
}
.lp-ticker-t { display: inline-flex; animation: tickr 40s linear infinite; padding: 11px 0; }
.lp-ticker-t:hover { animation-play-state: paused; }
@keyframes tickr { from { transform: translateX(0) } to { transform: translateX(-50%) } }
.lp-tt {
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem;
    font-weight: 500;
    letter-spacing: .06em;
    color: var(--text-muted);
    padding: 0 32px;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    transition: color .18s;
}
.lp-tt:hover { color: var(--cream); }
.lp-tt-type {
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    font-size: .55rem;
    flex-shrink: 0;
}
.lp-tt-type.blog   { color: var(--terra); }
.lp-tt-type.forum  { color: var(--gold); }
.lp-tt-title { max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.lp-tt-sep { color: var(--border); flex-shrink: 0; }

/* ══ STRUCTURE SECTIONS ══ */
.lp-section {
    padding: 72px 52px;
    max-width: 1280px;
    margin: 0 auto;
}
.lp-section-full {
    padding: 72px 52px;
}
.lp-section-full .lp-section-inner {
    max-width: 1280px;
    margin: 0 auto;
}
@media (max-width: 1024px) {
    .lp-section { padding: 52px 28px; }
    .lp-section-full { padding: 52px 28px; }
}
@media (max-width: 768px) {
    .lp-section { padding: 44px 16px; }
    .lp-section-full { padding: 44px 16px; }
    .lp-ed-header { margin-bottom: 28px; }
}

/* ── En-tête éditorial de section (trait + numéro + titre) ── */
.lp-ed-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 48px;
}
.lp-ed-header-num {
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--gold);
    white-space: nowrap;
    flex-shrink: 0;
}
.lp-ed-header-title {
    font-family: 'JetBrains Mono', monospace;
    font-size: .68rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-muted);
    white-space: nowrap;
    flex-shrink: 0;
}
.lp-ed-header-line {
    flex: 1;
    height: 1px;
    background: var(--border);
}
.lp-ed-header-link {
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-faint) !important;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
    transition: color .18s;
}
.lp-ed-header-link:hover { color: var(--gold) !important; }

/* ══ ARTICLES — GRILLE ÉDITORIALE ══ */

/* Featured : grand + stack */
.art-grid-featured {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 16px;
    background: transparent;
    border: none;
    border-radius: 0;
    overflow: visible;
}
@media (max-width: 1024px) { .art-grid-featured { grid-template-columns: 1fr; } }

.art-featured {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 0;
    text-decoration: none;
    display: flex; flex-direction: column;
    transition: border-color .2s, box-shadow .2s, transform .2s;
    overflow: hidden;
}
.art-featured:hover {
    border-color: var(--border-hover);
    box-shadow: 0 12px 36px rgba(0,0,0,0.45);
    transform: translateY(-2px);
    background: var(--bg-card);
}
.art-featured-inner { padding: 24px 28px 28px; display: flex; flex-direction: column; flex: 1; }

.art-side {
    display: flex; flex-direction: column;
    gap: 12px; background: transparent;
}
.art-side-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px 22px;
    text-decoration: none;
    display: flex; flex-direction: column;
    flex: 1; transition: border-color .2s, box-shadow .2s, transform .2s;
    position: relative; overflow: hidden;
}
.art-side-card::before {
    content: ''; position: absolute;
    left: 0; top: 0; bottom: 0; width: 3px;
    background: var(--terra); opacity: 0; transition: opacity .2s;
    border-radius: 0;
}
.art-side-card:hover {
    border-color: var(--border-hover);
    box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    transform: translateY(-1px);
}
.art-side-card:hover::before { opacity: 1; }

/* Numéro éditorial sur les side cards */
.art-side-num {
    font-family: 'JetBrains Mono', monospace;
    font-size: .55rem;
    letter-spacing: .14em;
    color: var(--text-faint);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.art-side-num::after {
    content: '';
    display: block;
    flex: 1;
    height: 1px;
    background: var(--border);
}

/* Grille 3 colonnes pour les récents */
.art-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--border);
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
}
@media (max-width: 1024px) { .art-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px)  { .art-grid { grid-template-columns: 1fr; } }

.art-card {
    background: var(--bg-card);
    padding: 28px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    transition: background .2s;
}
.art-card:hover { background: var(--bg-hover); }

/* Catégorie tag */
.art-cat {
    display: inline-flex;
    align-items: center;
    font-family: 'JetBrains Mono', monospace;
    font-size: .56rem;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--terra);
    margin-bottom: 14px;
    width: fit-content;
}

/* Miniature */
.art-thumb {
    width: 100%;
    aspect-ratio: 16/9;
    border-radius: 6px;
    background: var(--bg-card2);
    margin-bottom: 20px;
    overflow: hidden;
}
.art-featured .art-thumb { aspect-ratio: 16/8; margin-bottom: 0; }
.art-thumb img { width: 100%; height: 100%; object-fit: cover; }
.art-thumb-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.4rem;
    background: linear-gradient(135deg, var(--bg-card2), var(--bg-hover));
}

/* Titre article — DM Serif */
.art-title {
    font-family: var(--serif);
    font-size: 1.05rem;
    font-weight: 400;
    line-height: 1.35;
    color: rgba(255,255,255,.90);
    margin-bottom: 10px;
    transition: color .2s;
    flex: 1;
}
.art-featured .art-title { font-size: 1.4rem; line-height: 1.25; }
.art-side-card .art-title { font-size: .92rem; }
.art-featured:hover .art-title,
.art-side-card:hover .art-title,
.art-card:hover .art-title { color: var(--gold); }

.art-excerpt {
    font-size: .78rem;
    line-height: 1.65;
    color: var(--text-muted);
    margin-bottom: 18px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.art-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem;
    letter-spacing: .06em;
    color: var(--text-faint);
    margin-top: auto;
}
.art-avi {
    width: 20px; height: 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    display: flex; align-items: center; justify-content: center;
    font-size: .52rem; font-weight: 700; color: white; flex-shrink: 0;
}
.art-dot { width: 2px; height: 2px; background: var(--text-faint); border-radius: 50%; }

/* ══ CATÉGORIES — CARTES GRADIENT ══ */
.cat-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 12px;
}
@media (max-width: 1024px) { .cat-grid { grid-template-columns: repeat(4, 1fr); justify-items: stretch; } }
@media (max-width: 600px)  { .cat-grid { grid-template-columns: repeat(2, 1fr); } }
/* Dernière carte seule → pleine largeur sur mobile */
@media (max-width: 600px)  { .cat-grid > a:last-child:nth-child(odd) { grid-column: span 2; } }

.cat-card {
    padding: 24px 20px;
    text-decoration: none;
    display: flex; flex-direction: column;
    gap: 0; border-radius: 14px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    position: relative; overflow: hidden;
    transition: transform .22s, box-shadow .22s, border-color .22s, background .22s;
    min-height: 130px; justify-content: flex-end;
}
.cat-card::after {
    content: '';
    position: absolute; inset: 0;
    opacity: 0.06;
    transition: opacity .22s;
    border-radius: 14px;
}
.cat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    border-color: var(--border-hover);
}
.cat-card:hover::after { opacity: 0.12; }
/* Teinte subtile par catégorie */
.cat-card[href*="manga-anime"]::after   { background: linear-gradient(145deg, #7c3aed, #a855f7); }
.cat-card[href*="gaming"]::after        { background: linear-gradient(145deg, #16a34a, #4ade80); }
.cat-card[href*="tech"]::after          { background: linear-gradient(145deg, #2563eb, #60a5fa); }
.cat-card[href*="dev"]::after           { background: linear-gradient(145deg, #d97706, #fbbf24); }
.cat-card[href*="cinema"]::after        { background: linear-gradient(145deg, #dc2626, #f87171); }
.cat-card[href*="culture"]::after       { background: linear-gradient(145deg, #059669, #34d399); }
.cat-card[href*="debat"]::after  { background: linear-gradient(145deg, #7c3aed, #c084fc); }
/* Overlay hover lumineux */
.cat-card::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, transparent 60%);
    opacity: 0; transition: opacity .22s;
}
.cat-card:hover::before { opacity: 1; }
/* Supprimer l'ancien ::after (la barre gold) */
.cat-card::after { display: none; }

.cat-icon {
    font-size: 2rem; line-height: 1;
    margin-bottom: 12px;
    filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
}
.cat-name {
    font-family: var(--font-head);
    font-size: .95rem; font-weight: 700;
    color: rgba(255,255,255,.88);
    transition: color .2s;
    letter-spacing: -.01em;
}
.cat-card:hover .cat-name { color: white; }
.cat-count {
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem; letter-spacing: .08em;
    color: rgba(255,255,255,0.35); text-transform: uppercase;
    margin-top: 4px;
}

/* ══ FORUM PREVIEW ══ */
.lp-section-forum { background: var(--bg-card); }

.forum-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 1px;
    background: var(--border);
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
}
@media (max-width: 900px) { .forum-grid { grid-template-columns: 1fr; } }

.forum-threads { background: var(--bg-card); }
.forum-thread {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    gap: 14px;
    text-decoration: none;
    transition: background .18s;
    align-items: flex-start;
}
.forum-thread:last-child { border-bottom: none; }
.forum-thread:hover { background: var(--bg-hover); }

.ft-avi {
    width: 32px; height: 32px;
    border-radius: 7px;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; font-weight: 700; color: white; flex-shrink: 0;
    margin-top: 2px;
}
.ft-body { flex: 1; min-width: 0; }
.ft-cat {
    font-family: 'JetBrains Mono', monospace;
    font-size: .54rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--terra);
    margin-bottom: 4px;
}
.ft-title {
    font-family: var(--serif);
    font-size: .9rem; font-weight: 400;
    color: rgba(255,255,255,.80);
    line-height: 1.35; margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color .18s;
}
.forum-thread:hover .ft-title { color: var(--gold); }
.ft-meta {
    font-family: 'JetBrains Mono', monospace;
    font-size: .56rem; color: var(--text-faint);
    display: flex; align-items: center; gap: 8px;
}
.ft-replies {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    text-align: center; padding: 0 12px; min-width: 44px; flex-shrink: 0;
}
.ft-r-n {
    font-family: var(--serif);
    font-size: 1rem; color: var(--text-muted); line-height: 1;
}
.ft-r-l { font-family: 'JetBrains Mono', monospace; font-size: .5rem; color: var(--text-faint); text-transform: uppercase; letter-spacing: .07em; }

.forum-cats { background: var(--bg-card); display: flex; flex-direction: column; }
.forum-cat-item {
    padding: 16px 22px;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    display: flex; align-items: center; gap: 12px;
    transition: background .18s;
}
.forum-cat-item:last-child { border-bottom: none; }
.forum-cat-item:hover { background: var(--bg-hover); }
.fci-icon { width: 32px; height: 32px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 1rem; background: var(--bg-card2); flex-shrink: 0; }
.fci-info { flex: 1; min-width: 0; }
.fci-name { font-family: var(--serif); font-size: .82rem; color: rgba(255,255,255,.75); transition: color .18s; }
.forum-cat-item:hover .fci-name { color: var(--gold); }
.fci-desc { font-family: 'JetBrains Mono', monospace; font-size: .54rem; letter-spacing: .06em; color: var(--text-faint); margin-top: 2px; }
.fci-count { font-family: var(--serif); font-size: .85rem; color: var(--text-faint); margin-left: auto; flex-shrink: 0; }

/* ══ CTA SECTION ══ */
.lp-cta-section {
    padding: 96px 52px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.lp-cta-section::before {
    content: '';
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 800px; height: 600px;
    background: radial-gradient(ellipse, rgba(212,168,67,.10) 0%, transparent 65%);
    pointer-events: none;
}
.lp-cta-inner {
    position: relative; z-index: 1;
    max-width: 560px; margin: 0 auto;
}
.lp-cta-label {
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem; letter-spacing: .14em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 20px;
}
.lp-cta-title {
    font-family: var(--serif);
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 400;
    line-height: 1.15;
    color: rgba(255,255,255,.92);
    margin-bottom: 16px;
    letter-spacing: -.01em;
}
.lp-cta-desc {
    font-size: .88rem;
    color: var(--text-muted);
    line-height: 1.7;
    margin-bottom: 32px;
    max-width: 440px;
    margin-left: auto; margin-right: auto;
}

/* ══ NEWSLETTER ══ */
.lp-newsletter {
    border-top: 1px solid var(--border);
    padding: 72px 52px;
    max-width: 1280px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: center;
}
@media (max-width: 768px) {
    .lp-newsletter { grid-template-columns: 1fr; padding: 48px 20px; gap: 28px; }
}
.lp-newsletter-eyebrow {
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem; font-weight: 600;
    letter-spacing: .14em; text-transform: uppercase;
    color: var(--gold);
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 14px;
}
.lp-newsletter-eyebrow::before {
    content: ''; display: block;
    width: 20px; height: 1px; background: var(--gold); opacity: .7;
}
.lp-newsletter-title {
    font-family: var(--font-head);
    font-size: clamp(1.5rem, 2.5vw, 2rem);
    font-weight: 800;
    letter-spacing: -.04em;
    line-height: 1.1;
    color: var(--cream);
    margin-bottom: 10px;
}
.lp-newsletter-title span { color: var(--terra); }
.lp-newsletter-sub {
    font-size: .85rem;
    color: var(--text-muted);
    line-height: 1.65;
}
.lp-newsletter-form { display: flex; flex-direction: column; gap: 10px; }
.lp-newsletter-row { display: flex; gap: 8px; flex-wrap: wrap; }
.lp-newsletter-input {
    flex: 1;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 100px;
    padding: 13px 22px;
    color: var(--text);
    font-family: var(--font-body); font-size: .88rem;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.lp-newsletter-input::placeholder { color: var(--text-faint); }
.lp-newsletter-input:focus {
    border-color: var(--terra);
    box-shadow: 0 0 0 3px rgba(200,82,42,.1);
}
.lp-newsletter-btn {
    background: var(--terra); color: white;
    border: none; border-radius: 100px;
    padding: 13px 24px;
    font-family: var(--font-body); font-size: .88rem; font-weight: 700;
    cursor: pointer; white-space: nowrap;
    transition: background .2s, transform .15s;
}
.lp-newsletter-btn:hover { background: var(--accent); transform: translateY(-1px); }
.lp-newsletter-note {
    font-size: .72rem;
    color: var(--text-faint);
    padding-left: 8px;
}
.lp-newsletter-note strong { color: var(--gold); }

/* ══ FOOTER ══ */
.lp-footer {
    border-top: 1px solid var(--border);
    padding: 32px 52px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; flex-wrap: wrap;
    max-width: 1280px; margin: 0 auto;
}
.lp-footer-copy {
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem; letter-spacing: .08em; color: var(--text-faint);
}
.lp-footer-links { display: flex; gap: 22px; list-style: none; }
.lp-footer-links a {
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem; letter-spacing: .08em; text-transform: uppercase;
    color: var(--text-faint); text-decoration: none; transition: color .18s;
}
.lp-footer-links a:hover { color: var(--gold); }
@media (max-width: 640px) {
    .lp-footer { flex-direction: column; text-align: center; padding: 28px 20px; }
    .lp-footer-links { flex-wrap: wrap; justify-content: center; }
}
@media (max-width: 1024px) {
    .lp-cta-section { padding: 72px 28px; }
    .lp-newsletter { padding: 52px 28px; }
}
@media (max-width: 768px) {
    .lp-cta-section { padding: 52px 16px; }
    .lp-newsletter { padding: 44px 16px; }
    .art-grid-featured { gap: 12px; }
    .art-side { gap: 12px; }
    .cat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .lp-newsletter-row { flex-direction: column; }
    .lp-newsletter-btn { width: 100%; }
    .lp-section, .lp-section-full, .lp-cta-section, .lp-newsletter { max-width: 100vw; box-sizing: border-box; }
}
</style>
@endpush

@section('content')
<div class="lp">

{{-- SVG filter pour le halo du bouton principal --}}
<svg width="0" height="0" style="position:absolute;pointer-events:none">
    <defs>
        <filter id="btn-glow-blur" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur in="SourceGraphic" stdDeviation="8"/>
        </filter>
    </defs>
</svg>

{{-- ══ HERO ══ --}}
<section class="lp-hero">
    <div class="lp-hero-left">
        <div class="lp-vol">Vol. I · {{ date('Y') }}</div>

        <h1 class="lp-h1">
            La culture geek,<br>
            <span class="lp-h1-gold">vue d'Afrique.</span>
        </h1>

        <hr class="lp-hero-rule">

        <p class="lp-sub">
            Articles, débats, reviews et conversations
            autour du manga, du gaming, du développement, de la tech et de
            la culture nerd — par et pour la communauté africaine.
        </p>

        <div class="lp-ctas">
            <a href="{{ route('register') }}" class="lp-btn-main">
                Rejoindre la communauté
            </a>
            <a href="{{ route('blog.index') }}" class="lp-btn-ghost">
                Lire le blog
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="lp-scroll-hint" aria-hidden="true">
            <div class="lp-scroll-dot"></div>
        </div>
    </div>

    {{-- Colonne droite — encart éditorial --}}
    <div class="lp-hero-right">
        <div class="lp-ed-pullquote">
            <div class="lp-ed-pullquote-text">
                "L'Afrique n'a pas besoin d'emprunter l'imaginaire des autres — elle en a un qui lui est propre, immense et inexploré."
            </div>
            <div class="lp-ed-pullquote-attr">— Éditorial · MelanoGeek</div>
        </div>
        <div class="lp-ed-stats">
            <div class="lp-ed-stat">
                <span class="lp-ed-stat-n" data-count="{{ $stats['users'] }}">{{ $stats['users'] }}</span>
                <span class="lp-ed-stat-l">Membres<br>inscrits</span>
            </div>
            <div class="lp-ed-stat">
                <span class="lp-ed-stat-n" data-count="{{ $stats['posts'] }}">{{ $stats['posts'] }}</span>
                <span class="lp-ed-stat-l">Articles<br>publiés</span>
            </div>
            <div class="lp-ed-stat">
                <span class="lp-ed-stat-n" data-count="{{ $stats['comments'] }}">{{ $stats['comments'] }}</span>
                <span class="lp-ed-stat-l">Contributions<br>communauté</span>
            </div>
        </div>
    </div>
</section>

{{-- ══ TICKER ══ --}}
<div class="lp-ticker">
    <div class="lp-ticker-label">
        <span class="lp-ticker-label-dot"></span>
        Live
    </div>
    <div class="lp-ticker-track">
        <div class="lp-ticker-t">
            @foreach(array_fill(0, 2, null) as $_)
            @foreach($recentPosts as $rp)
            <a href="{{ route('posts.show', $rp->id) }}" class="lp-tt">
                <span class="lp-tt-type blog">Blog</span>
                <span class="lp-tt-title">{{ $rp->title }}</span>
                <span class="lp-tt-sep">·</span>
                <span style="color:var(--text-faint);font-size:.56rem">{{ $rp->created_at->diffForHumans(null, true) }}</span>
            </a>
            @endforeach
            @foreach($recentThreads as $rt)
            <a href="{{ route('forum.show', $rt) }}" class="lp-tt">
                <span class="lp-tt-type forum">Forum</span>
                <span class="lp-tt-title">{{ $rt->title }}</span>
                <span class="lp-tt-sep">·</span>
                <span style="color:var(--text-faint);font-size:.56rem">{{ $rt->created_at->diffForHumans(null, true) }}</span>
            </a>
            @endforeach
            @endforeach
        </div>
    </div>
</div>

{{-- ══ ARTICLES À LA UNE ══ --}}
<section class="lp-section">
    <div class="lp-ed-header">
        <span class="lp-ed-header-num">§ 01</span>
        <span class="lp-ed-header-title">À la une</span>
        <div class="lp-ed-header-line"></div>
        <a href="{{ route('blog.index') }}" class="lp-ed-header-link">Tous les articles →</a>
    </div>

    @if($featured || $side_posts->isNotEmpty())
    <div class="art-grid-featured" @if($side_posts->isEmpty()) style="grid-template-columns:1fr" @endif>
        {{-- Article featured --}}
        @if($featured)
        @php
            $featExcerpt  = Str::limit(strip_tags($featured->body ?? ''), 140);
            $featMins     = max(1, (int) ceil(str_word_count(strip_tags($featured->body ?? '')) / 200));
            $featInitial  = strtoupper(substr($featured->user->name ?? '?', 0, 1));
        @endphp
        <a href="{{ route('posts.show', $featured->id) }}" class="art-featured" data-reveal>
            <div class="art-thumb">
                @if($featured->thumbnail)
                    <img src="{{ asset('storage/'.$featured->thumbnail) }}" alt="{{ $featured->title }}">
                @elseif($featured->media_url && $featured->media_type === 'image')
                    <img src="{{ asset('storage/'.$featured->media_url) }}" alt="{{ $featured->title }}">
                @else
                    <div class="art-thumb-placeholder">📰</div>
                @endif
            </div>
            <div class="art-featured-inner">
                @if($featured->category)
                <div class="art-cat">{{ $featured->category_label }}</div>
                @endif
                <div class="art-title">{{ $featured->title }}</div>
                @if($featExcerpt)
                <div class="art-excerpt">{{ $featExcerpt }}</div>
                @endif
                <div class="art-meta" style="margin-top:auto">
                    @if($featured->user->avatar)
                        <img src="{{ asset('storage/'.$featured->user->avatar) }}" class="art-avi" style="object-fit:cover" alt="">
                    @else
                        <div class="art-avi">{{ $featInitial }}</div>
                    @endif
                    <span>{{ $featured->user->name }}</span>
                    <span class="art-dot"></span>
                    <span>{{ $featured->created_at->format('d M Y') }}</span>
                    <span class="art-dot"></span>
                    <span>{{ $featMins }} min</span>
                </div>
            </div>
        </a>
        @endif

        {{-- Articles côté --}}
        @if($side_posts->isNotEmpty())
        <div class="art-side">
            @foreach($side_posts as $post)
            @php
                $excerpt = Str::limit(strip_tags($post->body ?? ''), 100);
                $mins    = max(1, (int) ceil(str_word_count(strip_tags($post->body ?? '')) / 200));
                $initial = strtoupper(substr($post->user->name ?? '?', 0, 1));
            @endphp
            <a href="{{ route('posts.show', $post->id) }}" class="art-side-card" data-reveal data-delay="{{ $loop->iteration }}">
                <div class="art-side-num">0{{ $loop->iteration }}</div>
                @if($post->category)
                <div class="art-cat">{{ $post->category_label }}</div>
                @endif
                <div class="art-title" style="font-size:1rem;line-height:1.3;margin-bottom:10px">{{ $post->title }}</div>
                @if($excerpt)
                <div class="art-excerpt" style="font-size:.78rem;-webkit-line-clamp:2">{{ $excerpt }}</div>
                @endif
                <div class="art-meta" style="margin-top:auto;padding-top:12px">
                    @if($post->user->avatar)
                        <img src="{{ asset('storage/'.$post->user->avatar) }}" class="art-avi" style="object-fit:cover" alt="">
                    @else
                        <div class="art-avi">{{ $initial }}</div>
                    @endif
                    <span>{{ $post->user->name }}</span>
                    <span class="art-dot"></span>
                    <span>{{ $mins }} min</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
    @else
    <div style="text-align:center;padding:64px 0;color:var(--text-faint);font-family:'JetBrains Mono',monospace;font-size:.75rem;letter-spacing:.08em">
        Aucun article publié pour le moment.
    </div>
    @endif
</section>

{{-- ══ CATÉGORIES ══ --}}
<section class="lp-section" style="padding-top:0">
    <div class="lp-ed-header">
        <span class="lp-ed-header-num">§ 02</span>
        <span class="lp-ed-header-title">Explorer par thème</span>
        <div class="lp-ed-header-line"></div>
    </div>
    @php
        $cats = [
            'manga-anime'   => ['🎌', 'Manga & Animé'],
            'gaming'        => ['🎮', 'Gaming'],
            'tech'          => ['💻', 'Tech & IA'],
            'dev'           => ['🛠️', 'Développement'],
            'cinema-series' => ['🎬', 'Cinéma & Séries'],
            'culture'       => ['🌍', 'Culture & Société'],
            'debat'         => ['💬', 'Débat'],
        ];
    @endphp
    <div class="cat-grid">
        @foreach($cats as $slug => [$icon, $label])
        @php $count = $category_counts[$slug] ?? 0; @endphp
        <a href="{{ route('blog.index') }}?category={{ $slug }}" class="cat-card" data-reveal data-delay="{{ $loop->iteration }}">
            <div class="cat-icon">{{ $icon }}</div>
            <div class="cat-name">{{ $label }}</div>
            @if($count > 0)
            <div class="cat-count">{{ $count }} article{{ $count > 1 ? 's' : '' }}</div>
            @endif
        </a>
        @endforeach
    </div>
</section>

{{-- ══ FORUM PREVIEW ══ --}}
<section class="lp-section-full lp-section-forum" style="border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
    <div class="lp-section-inner">
        <div style="padding-top:72px;padding-bottom:72px">
            <div class="lp-ed-header">
                <span class="lp-ed-header-num">§ 03</span>
                <span class="lp-ed-header-title">Forum</span>
                <div class="lp-ed-header-line"></div>
                <a href="{{ route('forum.index') }}" class="lp-ed-header-link">Accéder au forum →</a>
            </div>

            <div class="forum-grid">
                <div class="forum-threads">
                    @forelse($discussions as $disc)
                    @php
                        $discInitial = strtoupper(substr($disc->user->name ?? '?', 0, 1));
                        $discLabel   = $disc->title ? Str::limit($disc->title, 80) : Str::limit(strip_tags($disc->body ?? ''), 80);
                    @endphp
                    <a href="{{ route('forum.index') }}" class="forum-thread">
                        @if($disc->user->avatar)
                            <img src="{{ asset('storage/'.$disc->user->avatar) }}" class="ft-avi" style="object-fit:cover" alt="">
                        @else
                            <div class="ft-avi">{{ $discInitial }}</div>
                        @endif
                        <div class="ft-body">
                            <div class="ft-title">{{ $discLabel }}</div>
                            <div class="ft-meta">
                                <span>{{ $disc->user->name }}</span>
                                <span>·</span>
                                <span>{{ $disc->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="ft-replies">
                            <div class="ft-r-n">{{ $disc->comments_count }}</div>
                            <div class="ft-r-l">rép.</div>
                        </div>
                    </a>
                    @empty
                    <div style="padding:40px 24px;text-align:center;color:var(--text-faint);font-family:'JetBrains Mono',monospace;font-size:.7rem;letter-spacing:.08em">
                        Aucune discussion pour le moment.
                    </div>
                    @endforelse
                </div>

                <div class="forum-cats">
                    <div style="padding:16px 22px;border-bottom:1px solid var(--border)">
                        <span style="font-family:'JetBrains Mono',monospace;font-size:.56rem;letter-spacing:.1em;text-transform:uppercase;color:var(--text-faint)">Thèmes</span>
                    </div>
                    @foreach(\App\Models\ForumThread::CATEGORIES as $slug => $cat)
                    <a href="{{ route('forum.index') }}?cat={{ $slug }}" class="forum-cat-item">
                        <div class="fci-icon">{{ $cat['icon'] }}</div>
                        <div class="fci-info">
                            <div class="fci-name">{{ $cat['label'] }}</div>
                            <div class="fci-desc">{{ $forum_cat_counts[$slug] ?? 0 }} discussion{{ ($forum_cat_counts[$slug] ?? 0) != 1 ? 's' : '' }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ CTA REJOINDRE ══ --}}
<section class="lp-cta-section">
    <div class="lp-cta-inner">
        <div class="lp-cta-label">Rejoindre</div>
        <div class="lp-cta-title">Tu es geek et africain·e ?<br>Tu es chez toi ici.</div>
        <p class="lp-cta-desc">
            Rejoins une communauté qui célèbre la culture geek africaine —
            sans complexe, sans filtre. Crée ton compte gratuitement et
            commence à lire, écrire et débattre dès aujourd'hui.
        </p>
        <div class="lp-ctas" style="justify-content:center">
            <a href="{{ route('register') }}" class="lp-btn-main">
                Créer mon compte gratuit
            </a>
            <a href="{{ route('login') }}" class="lp-btn-ghost">
                Se connecter
            </a>
        </div>
    </div>
</section>

{{-- ══ NEWSLETTER ══ --}}
<div class="lp-newsletter" data-reveal>
    <div>
        <div class="lp-newsletter-eyebrow">Newsletter</div>
        <div class="lp-newsletter-title">Reste dans<br>la <span>boucle.</span></div>
        <p class="lp-newsletter-sub">
            Les meilleurs articles, débats chauds et sorties geek — directement dans ta boîte mail. Pas de spam, jamais.
        </p>
    </div>
    <div>
        <form class="lp-newsletter-form" onsubmit="handleNewsletterSubmit(event)">
            <div class="lp-newsletter-row">
                <input type="email" class="lp-newsletter-input" id="nlEmail"
                    placeholder="ton@email.com" required autocomplete="email">
                <button type="submit" class="lp-newsletter-btn">S'inscrire</button>
            </div>
            <div class="lp-newsletter-note" id="nlNote">
                Rejoins <strong>{{ $stats['users'] }} membres</strong> — désinscription en 1 clic.
            </div>
        </form>
    </div>
</div>

{{-- ══ FOOTER ══ --}}
<footer class="lp-footer">
    <div class="lp-footer-copy">© {{ date('Y') }} MelanoGeek — La culture geek, vue d'Afrique.</div>
    <ul class="lp-footer-links">
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
        <li><a href="{{ route('forum.index') }}">Forum</a></li>
        <li><a href="{{ route('about') }}">À propos</a></li>
        <li><a href="#">Mentions légales</a></li>
    </ul>
</footer>

</div>
@endsection

@push('scripts')
<script>
(function () {
    /* ══ COMPTEURS ANIMÉS — stats hero ══ */
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        function animateCount(el) {
            const target = parseInt(el.textContent.replace(/\D/g, ''), 10);
            if (isNaN(target) || target === 0) return;
            const duration = 1200;
            const start    = performance.now();
            const easeOut  = function (t) { return 1 - Math.pow(1 - t, 3); };
            function tick(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                el.textContent = Math.round(easeOut(progress) * target);
                if (progress < 1) requestAnimationFrame(tick);
                else el.textContent = target;
            }
            requestAnimationFrame(tick);
        }

        const statEls = document.querySelectorAll('.lp-ed-stat-n, .forum-widget-stat-val');
        const countObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCount(entry.target);
                    countObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        statEls.forEach(function (el) { countObserver.observe(el); });
    }

    /* ══ TILT 3D — cards ══ */
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches &&
        !window.matchMedia('(pointer: coarse)').matches) {
        const tiltSel = '.post-card, .art-featured, .art-side-card, .featured-card, .cat-card';
        document.querySelectorAll(tiltSel).forEach(function (card) {
            card.style.transition = 'transform .12s ease, box-shadow .12s ease';
            card.addEventListener('mousemove', function (e) {
                const rect = card.getBoundingClientRect();
                const cx   = rect.left + rect.width  / 2;
                const cy   = rect.top  + rect.height / 2;
                const dx   = (e.clientX - cx) / (rect.width  / 2);
                const dy   = (e.clientY - cy) / (rect.height / 2);
                const rotX = dy * -4;
                const rotY = dx *  5;
                card.style.transform = 'perspective(600px) rotateX(' + rotX + 'deg) rotateY(' + rotY + 'deg) scale(1.015)';
            });
            card.addEventListener('mouseleave', function () {
                card.style.transform = '';
            });
        });
    }

    /* ══ NEWSLETTER — feedback visuel ══ */
    window.handleNewsletterSubmit = function (e) {
        e.preventDefault();
        const note = document.getElementById('nlNote');
        const btn  = e.target.querySelector('.lp-newsletter-btn');
        btn.textContent  = '✓ Inscrit !';
        btn.style.background = '#2A7A48';
        btn.disabled = true;
        if (note) note.innerHTML = 'Tu es sur la liste. On se retrouve dans ta boîte mail bientôt.';
    };
})();
</script>
@endpush
