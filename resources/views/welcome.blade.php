@extends('layouts.app')

@section('title', 'MelanoGeek — La Culture Geek, Vue d\'Afrique')

@push('styles')
<style>
/* ══ LANDING PAGE ══ */
.lp { --accent: var(--terra); --accent2: var(--gold); }

/* ── HERO ── */
.lp-hero {
    min-height: 100vh;
    padding-top: 72px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}

/* Grille de fond */
.lp-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(var(--border) 1px, transparent 1px),
        linear-gradient(90deg, var(--border) 1px, transparent 1px);
    background-size: 52px 52px;
    mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 30%, transparent 100%);
    pointer-events: none;
    z-index: 0;
}

/* Lueur centrale */
.lp-hero::after {
    content: '';
    position: absolute;
    top: 30%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 700px;
    height: 500px;
    background: radial-gradient(ellipse, rgba(200,72,24,.13) 0%, transparent 65%);
    pointer-events: none;
    z-index: 0;
}

.lp-hero-inner {
    position: relative;
    z-index: 1;
    max-width: 860px;
    padding: 0 24px;
}

.lp-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--terra-soft);
    border: 1px solid rgba(200,72,24,.22);
    color: var(--terra);
    font-family: var(--font-head);
    font-size: .58rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 100px;
    margin-bottom: 28px;
}
.lp-badge-dot {
    width: 5px; height: 5px;
    background: var(--terra);
    border-radius: 50%;
    animation: pulse 2s infinite;
}
@keyframes pulse {0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.7)}}

.lp-h1 {
    font-family: var(--font-head);
    font-size: clamp(2.8rem, 6vw, 5.8rem);
    font-weight: 900;
    line-height: .95;
    letter-spacing: -.04em;
    color: var(--text);
    margin-bottom: 24px;
}
.lp-h1 .grad {
    background: linear-gradient(135deg, var(--terra) 0%, var(--gold) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.lp-h1 .outline {
    -webkit-text-stroke: 2px var(--terra);
    color: transparent;
}

.lp-sub {
    font-size: 1rem;
    line-height: 1.75;
    color: var(--text-muted);
    max-width: 520px;
    margin: 0 auto 36px;
}

.lp-ctas {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 56px;
}
.lp-btn-main {
    background: var(--terra);
    color: white !important;
    border: none;
    padding: 13px 28px;
    border-radius: 8px;
    font-family: var(--font-head);
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all .22s;
}
.lp-btn-main:hover { background: var(--accent); transform: translateY(-2px); box-shadow: 0 12px 32px rgba(200,72,24,.30); }
.lp-btn-ghost {
    background: none;
    color: var(--text-muted) !important;
    border: 1px solid var(--border);
    padding: 13px 28px;
    border-radius: 8px;
    font-size: .75rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all .2s;
}
.lp-btn-ghost:hover { border-color: var(--terra); color: var(--terra) !important; }

/* Stats inline hero */
.lp-hero-stats {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg-card);
    overflow: hidden;
    width: fit-content;
    margin: 0 auto;
}
.lp-hs {
    padding: 16px 32px;
    border-right: 1px solid var(--border);
    text-align: center;
}
.lp-hs:last-child { border-right: none; }
.lp-hs-n {
    font-family: var(--font-head);
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--terra);
    letter-spacing: -.02em;
}
.lp-hs-l {
    font-size: .6rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-top: 3px;
}

/* ── TICKER ── */
.lp-ticker {
    background: var(--terra);
    padding: 9px 0;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    z-index: 1;
}
.lp-ticker::before, .lp-ticker::after {
    content: '';
    position: absolute;
    top: 0; bottom: 0;
    width: 60px;
    z-index: 2;
}
.lp-ticker::before { left: 0; background: linear-gradient(90deg, var(--terra), transparent); }
.lp-ticker::after  { right: 0; background: linear-gradient(-90deg, var(--terra), transparent); }
.lp-ticker-t { display: inline-flex; animation: tickr 28s linear infinite; }
@keyframes tickr { from { transform: translateX(0) } to { transform: translateX(-50%) } }
.lp-tt {
    font-family: var(--font-head);
    font-size: .58rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(255,255,255,.85);
    padding: 0 28px;
    display: inline-flex;
    align-items: center;
    gap: 14px;
}
.lp-tt-dot { width: 4px; height: 4px; background: rgba(255,255,255,.35); border-radius: 50%; }

/* ── SECTIONS COMMUNES ── */
.lp-section { padding: 80px 52px; position: relative; }
.lp-section-inner { max-width: 1200px; margin: 0 auto; }

.lp-sec-top {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 44px;
    gap: 20px;
    flex-wrap: wrap;
}
.lp-sec-label {
    font-size: .58rem;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--terra);
    margin-bottom: 8px;
}
.lp-sec-title {
    font-family: var(--font-head);
    font-size: clamp(1.4rem, 2.5vw, 2.2rem);
    font-weight: 800;
    letter-spacing: -.03em;
    line-height: 1.1;
    color: var(--text);
}
.lp-sec-title span { color: var(--terra); }
.lp-sec-link {
    font-size: .68rem;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--text-muted) !important;
    text-decoration: none;
    border-bottom: 1px solid var(--border);
    padding-bottom: 2px;
    white-space: nowrap;
    transition: all .2s;
}
.lp-sec-link:hover { color: var(--terra) !important; border-color: var(--terra); }

/* ── ARTICLES GRID ── */
.art-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    background: var(--border);
}

.art-card {
    background: var(--bg-card);
    padding: 28px;
    text-decoration: none;
    display: block;
    transition: background .2s;
    position: relative;
}
.art-card:hover { background: var(--bg-hover); }
.art-card:hover .art-title { color: var(--terra); }

.art-cat {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .58rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--terra);
    background: var(--terra-soft);
    border: 1px solid rgba(200,72,24,.18);
    padding: 3px 10px;
    border-radius: 4px;
    margin-bottom: 16px;
}

.art-thumb {
    width: 100%;
    aspect-ratio: 16/9;
    border-radius: 8px;
    background: var(--bg-card2);
    margin-bottom: 16px;
    overflow: hidden;
    position: relative;
}
.art-thumb img { width: 100%; height: 100%; object-fit: cover; }
.art-thumb-placeholder {
    width: 100%; height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.4rem;
    background: linear-gradient(135deg, var(--bg-card2), var(--bg-hover));
}

.art-title {
    font-family: var(--font-head);
    font-size: .92rem;
    font-weight: 700;
    line-height: 1.35;
    color: var(--text);
    margin-bottom: 10px;
    transition: color .2s;
}
.art-excerpt {
    font-size: .75rem;
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
    gap: 12px;
    font-size: .62rem;
    color: var(--text-muted);
}
.art-avi {
    width: 22px; height: 22px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .58rem;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}
.art-dot { width: 3px; height: 3px; background: var(--text-faint); border-radius: 50%; }

/* Article featured (grande carte gauche) */
.art-grid-featured {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 1px;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    background: var(--border);
}
.art-featured {
    background: var(--bg-card);
    padding: 36px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    transition: background .2s;
}
.art-featured:hover { background: var(--bg-hover); }
.art-featured:hover .art-title { color: var(--terra); }
.art-featured .art-thumb { aspect-ratio: 16/8; margin-bottom: 24px; }
.art-featured .art-title { font-size: 1.2rem; }

.art-side {
    display: flex;
    flex-direction: column;
    gap: 1px;
    background: var(--border);
}
.art-side-card {
    background: var(--bg-card);
    padding: 24px 28px;
    text-decoration: none;
    display: block;
    flex: 1;
    transition: background .2s;
}
.art-side-card:hover { background: var(--bg-hover); }
.art-side-card:hover .art-title { color: var(--terra); }
.art-side-card .art-title { font-size: .82rem; }

/* ── CATÉGORIES ── */
.cat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1px;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    background: var(--border);
}
.cat-card {
    background: var(--bg-card);
    padding: 28px 24px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: background .2s;
    position: relative;
    overflow: hidden;
}
.cat-card::before {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: var(--terra);
    transform: scaleX(0);
    transition: transform .25s;
    transform-origin: left;
}
.cat-card:hover { background: var(--bg-hover); }
.cat-card:hover::before { transform: scaleX(1); }
.cat-icon {
    width: 42px; height: 42px;
    border-radius: 10px;
    background: var(--terra-soft);
    border: 1px solid rgba(200,72,24,.18);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}
.cat-name {
    font-family: var(--font-head);
    font-size: .8rem;
    font-weight: 700;
    color: var(--text);
}
.cat-count {
    font-size: .62rem;
    color: var(--text-muted);
    letter-spacing: .04em;
}

/* ── FORUM PREVIEW ── */
.forum-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 1px;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    background: var(--border);
}
.forum-threads {
    background: var(--bg-card);
}
.forum-thread {
    padding: 20px 28px;
    border-bottom: 1px solid var(--border);
    display: flex;
    gap: 14px;
    text-decoration: none;
    transition: background .18s;
}
.forum-thread:last-child { border-bottom: none; }
.forum-thread:hover { background: var(--bg-hover); }

.ft-avi {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}
.ft-body { flex: 1; min-width: 0; }
.ft-cat {
    font-size: .56rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--terra);
    margin-bottom: 4px;
}
.ft-title {
    font-size: .82rem;
    font-weight: 600;
    color: var(--text);
    line-height: 1.35;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ft-meta {
    font-size: .6rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 8px;
}
.ft-replies {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 0 16px;
    min-width: 52px;
    flex-shrink: 0;
}
.ft-r-n {
    font-family: var(--font-head);
    font-size: .85rem;
    font-weight: 700;
    color: var(--text-muted);
}
.ft-r-l { font-size: .52rem; color: var(--text-faint); text-transform: uppercase; letter-spacing: .07em; }

/* Forum cats (colonne droite) */
.forum-cats {
    background: var(--bg-card);
    display: flex;
    flex-direction: column;
}
.forum-cat-item {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: background .18s;
}
.forum-cat-item:last-child { border-bottom: none; }
.forum-cat-item:hover { background: var(--bg-hover); }
.fci-icon {
    width: 38px; height: 38px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    background: var(--bg-card2);
    flex-shrink: 0;
}
.fci-name {
    font-size: .8rem;
    font-weight: 600;
    color: var(--text);
}
.fci-desc {
    font-size: .62rem;
    color: var(--text-muted);
    margin-top: 2px;
}
.fci-count {
    margin-left: auto;
    font-family: var(--font-head);
    font-size: .75rem;
    font-weight: 700;
    color: var(--text-faint);
}

/* ── CTA SECTION ── */
.lp-cta-section {
    padding: 80px 52px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.lp-cta-section::before {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 600px; height: 400px;
    background: radial-gradient(ellipse, rgba(200,72,24,.09) 0%, transparent 65%);
    pointer-events: none;
}
.lp-cta-box {
    position: relative;
    z-index: 1;
    max-width: 620px;
    margin: 0 auto;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 52px 44px;
    background: var(--bg-card);
}
.lp-cta-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    background: var(--terra-soft);
    border: 1px solid rgba(200,72,24,.22);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin: 0 auto 20px;
}
.lp-cta-title {
    font-family: var(--font-head);
    font-size: clamp(1.3rem, 2.5vw, 1.9rem);
    font-weight: 800;
    letter-spacing: -.03em;
    color: var(--text);
    margin-bottom: 14px;
}
.lp-cta-desc {
    font-size: .82rem;
    color: var(--text-muted);
    line-height: 1.7;
    margin-bottom: 28px;
}

/* ── FOOTER ── */
.lp-footer {
    border-top: 1px solid var(--border);
    padding: 36px 52px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}
.lp-footer-copy {
    font-size: .65rem;
    color: var(--text-faint);
    letter-spacing: .04em;
}
.lp-footer-links {
    display: flex;
    gap: 24px;
    list-style: none;
}
.lp-footer-links a {
    font-size: .65rem;
    color: var(--text-muted);
    text-decoration: none;
    letter-spacing: .06em;
    text-transform: uppercase;
    transition: color .18s;
}
.lp-footer-links a:hover { color: var(--terra); }

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
    .art-grid { grid-template-columns: repeat(2, 1fr); }
    .art-grid-featured { grid-template-columns: 1fr; }
    .art-side { flex-direction: row; }
    .forum-grid { grid-template-columns: 1fr; }
    .cat-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .lp-section { padding: 52px 20px; }
    .lp-cta-section { padding: 52px 20px; }
    .lp-cta-box { padding: 36px 24px; }
    .lp-h1 { font-size: clamp(2.2rem, 10vw, 3.2rem); }
    .lp-hero-stats { flex-direction: column; width: 100%; border-radius: 10px; }
    .lp-hs { border-right: none; border-bottom: 1px solid var(--border); }
    .lp-hs:last-child { border-bottom: none; }
    .art-grid { grid-template-columns: 1fr; }
    .art-side { flex-direction: column; }
    .cat-grid { grid-template-columns: repeat(2, 1fr); }
    .lp-footer { flex-direction: column; text-align: center; padding: 28px 20px; }
    .lp-footer-links { flex-wrap: wrap; justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="lp">

{{-- ══ HERO ══ --}}
<section class="lp-hero">
    <div class="lp-hero-inner">
        <div class="lp-badge">
            <span class="lp-badge-dot"></span>
            Blog · Forum · Communauté
        </div>

        <h1 class="lp-h1">
            La culture <span class="grad">geek</span>,<br>
            vue <span class="outline">d'Afrique</span>
        </h1>

        <p class="lp-sub">
            Articles, débats, reviews et discussions autour du manga, du gaming,
            de la tech et de la culture nerd — par et pour la communauté africaine.
        </p>

        <div class="lp-ctas">
            <a href="{{ route('register') }}" class="lp-btn-main">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Rejoindre la communauté
            </a>
            <a href="{{ route('blog.index') }}" class="lp-btn-ghost">
                Lire le blog
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="lp-hero-stats">
            <div class="lp-hs">
                <div class="lp-hs-n">2.4k+</div>
                <div class="lp-hs-l">Membres</div>
            </div>
            <div class="lp-hs">
                <div class="lp-hs-n">380+</div>
                <div class="lp-hs-l">Articles</div>
            </div>
            <div class="lp-hs">
                <div class="lp-hs-n">1.1k+</div>
                <div class="lp-hs-l">Sujets forum</div>
            </div>
            <div class="lp-hs">
                <div class="lp-hs-n">12+</div>
                <div class="lp-hs-l">Pays</div>
            </div>
        </div>
    </div>
</section>

{{-- ══ TICKER ══ --}}
<div class="lp-ticker">
    <div class="lp-ticker-t">
        @foreach(array_fill(0, 2, null) as $_)
        <span class="lp-tt"><span class="lp-tt-dot"></span> Manga africain</span>
        <span class="lp-tt"><span class="lp-tt-dot"></span> Gaming</span>
        <span class="lp-tt"><span class="lp-tt-dot"></span> Animé</span>
        <span class="lp-tt"><span class="lp-tt-dot"></span> Science-fiction</span>
        <span class="lp-tt"><span class="lp-tt-dot"></span> Tech & IA</span>
        <span class="lp-tt"><span class="lp-tt-dot"></span> BD & Comics</span>
        <span class="lp-tt"><span class="lp-tt-dot"></span> Cinéma de genre</span>
        <span class="lp-tt"><span class="lp-tt-dot"></span> Cosplay</span>
        <span class="lp-tt"><span class="lp-tt-dot"></span> Geek culture</span>
        @endforeach
    </div>
</div>

{{-- ══ ARTICLES À LA UNE ══ --}}
<section class="lp-section">
    <div class="lp-section-inner">
        <div class="lp-sec-top">
            <div>
                <div class="lp-sec-label">À la une</div>
                <div class="lp-sec-title">Les derniers <span>articles</span></div>
            </div>
            <a href="{{ route('blog.index') }}" class="lp-sec-link">Tous les articles →</a>
        </div>

        <div class="art-grid-featured">
            {{-- Article featured --}}
            <a href="#" class="art-featured">
                <div class="art-thumb">
                    <div class="art-thumb-placeholder">🎌</div>
                </div>
                <div class="art-cat">Manga africain</div>
                <div class="art-title">Anansi : le retour du dieu-araignée dans le manga afrofuturiste</div>
                <div class="art-excerpt">Comment les auteurs africains réinventent la mythologie continentale à travers les codes du manga japonais pour créer un genre hybride unique.</div>
                <div class="art-meta" style="margin-top:auto">
                    <div class="art-avi">K</div>
                    <span>Kemi Adeyemi</span>
                    <span class="art-dot"></span>
                    <span>12 jan. 2026</span>
                    <span class="art-dot"></span>
                    <span>8 min</span>
                </div>
            </a>

            {{-- Articles côté --}}
            <div class="art-side">
                <a href="#" class="art-side-card">
                    <div class="art-cat">Gaming</div>
                    <div class="art-title">African Game Week 2026 : les studios locaux à surveiller</div>
                    <div class="art-excerpt">Tour d'horizon des studios indépendants africains qui font parler d'eux.</div>
                    <div class="art-meta" style="margin-top:12px">
                        <div class="art-avi">D</div>
                        <span>Djiby Fall</span>
                        <span class="art-dot"></span>
                        <span>5 min</span>
                    </div>
                </a>
                <a href="#" class="art-side-card">
                    <div class="art-cat">Tech & IA</div>
                    <div class="art-title">L'IA générative au service des langues africaines</div>
                    <div class="art-excerpt">Des chercheurs forment des LLMs sur le wolof, le yoruba et le swahili.</div>
                    <div class="art-meta" style="margin-top:12px">
                        <div class="art-avi">A</div>
                        <span>Amara Koné</span>
                        <span class="art-dot"></span>
                        <span>6 min</span>
                    </div>
                </a>
                <a href="#" class="art-side-card">
                    <div class="art-cat">Cinéma</div>
                    <div class="art-title">Nollywood SF : quand Lagos se réinvente en dystopie</div>
                    <div class="art-excerpt">Top 5 des films de science-fiction nigérians à ne pas manquer en 2026.</div>
                    <div class="art-meta" style="margin-top:12px">
                        <div class="art-avi">F</div>
                        <span>Fatou Diallo</span>
                        <span class="art-dot"></span>
                        <span>4 min</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══ CATÉGORIES ══ --}}
<section class="lp-section" style="padding-top:0">
    <div class="lp-section-inner">
        <div class="lp-sec-top">
            <div>
                <div class="lp-sec-label">Explorer par thème</div>
                <div class="lp-sec-title">Toutes les <span>catégories</span></div>
            </div>
        </div>
        <div class="cat-grid">
            <a href="{{ route('blog.index') }}?cat=manga" class="cat-card">
                <div class="cat-icon">🎌</div>
                <div class="cat-name">Manga & Animé</div>
                <div class="cat-count">142 articles</div>
            </a>
            <a href="{{ route('blog.index') }}?cat=gaming" class="cat-card">
                <div class="cat-icon">🎮</div>
                <div class="cat-name">Gaming</div>
                <div class="cat-count">98 articles</div>
            </a>
            <a href="{{ route('blog.index') }}?cat=tech" class="cat-card">
                <div class="cat-icon">💻</div>
                <div class="cat-name">Tech & IA</div>
                <div class="cat-count">76 articles</div>
            </a>
            <a href="{{ route('blog.index') }}?cat=cinema" class="cat-card">
                <div class="cat-icon">🎬</div>
                <div class="cat-name">Cinéma & Séries</div>
                <div class="cat-count">64 articles</div>
            </a>
            <a href="{{ route('blog.index') }}?cat=bd" class="cat-card">
                <div class="cat-icon">📚</div>
                <div class="cat-name">BD & Comics</div>
                <div class="cat-count">47 articles</div>
            </a>
            <a href="{{ route('blog.index') }}?cat=cosplay" class="cat-card">
                <div class="cat-icon">🎭</div>
                <div class="cat-name">Cosplay</div>
                <div class="cat-count">31 articles</div>
            </a>
            <a href="{{ route('blog.index') }}?cat=scifi" class="cat-card">
                <div class="cat-icon">🚀</div>
                <div class="cat-name">Science-Fiction</div>
                <div class="cat-count">58 articles</div>
            </a>
            <a href="{{ route('blog.index') }}?cat=culture" class="cat-card">
                <div class="cat-icon">🌍</div>
                <div class="cat-name">Culture africaine</div>
                <div class="cat-count">89 articles</div>
            </a>
        </div>
    </div>
</section>

{{-- ══ FORUM PREVIEW ══ --}}
<section class="lp-section" style="background:var(--bg-card2)">
    <div class="lp-section-inner">
        <div class="lp-sec-top">
            <div>
                <div class="lp-sec-label">Communauté</div>
                <div class="lp-sec-title">Le <span>forum</span> des geeks</div>
            </div>
            <a href="{{ route('forum.index') }}" class="lp-sec-link">Accéder au forum →</a>
        </div>

        <div class="forum-grid">
            {{-- Threads récents --}}
            <div class="forum-threads">
                <a href="#" class="forum-thread">
                    <div class="ft-avi">N</div>
                    <div class="ft-body">
                        <div class="ft-cat">Gaming</div>
                        <div class="ft-title">Quelqu'un a testé Anansi Chronicles sur PS5 ? Mon avis après 20h de jeu</div>
                        <div class="ft-meta">
                            <span>Nana Osei</span>
                            <span>·</span>
                            <span>il y a 2h</span>
                        </div>
                    </div>
                    <div class="ft-replies">
                        <div class="ft-r-n">24</div>
                        <div class="ft-r-l">rép.</div>
                    </div>
                </a>
                <a href="#" class="forum-thread">
                    <div class="ft-avi" style="background:linear-gradient(135deg,#1A5A30,#B87820)">Z</div>
                    <div class="ft-body">
                        <div class="ft-cat">Manga africain</div>
                        <div class="ft-title">Top 10 des mangas afrofuturistes — ma liste après 3 ans de lecture intensive</div>
                        <div class="ft-meta">
                            <span>Zeynab Ibrahim</span>
                            <span>·</span>
                            <span>il y a 5h</span>
                        </div>
                    </div>
                    <div class="ft-replies">
                        <div class="ft-r-n">61</div>
                        <div class="ft-r-l">rép.</div>
                    </div>
                </a>
                <a href="#" class="forum-thread">
                    <div class="ft-avi" style="background:linear-gradient(135deg,#0A3A7A,#C84818)">M</div>
                    <div class="ft-body">
                        <div class="ft-cat">Tech & IA</div>
                        <div class="ft-title">Comment j'ai créé une IA qui parle lingala — retour d'expérience</div>
                        <div class="ft-meta">
                            <span>Mwana Kitoko</span>
                            <span>·</span>
                            <span>il y a 8h</span>
                        </div>
                    </div>
                    <div class="ft-replies">
                        <div class="ft-r-n">38</div>
                        <div class="ft-r-l">rép.</div>
                    </div>
                </a>
                <a href="#" class="forum-thread">
                    <div class="ft-avi" style="background:linear-gradient(135deg,#7A2080,#D4A843)">A</div>
                    <div class="ft-body">
                        <div class="ft-cat">Cosplay</div>
                        <div class="ft-title">Partage de costume — Shuri (Black Panther) fait maison, budget 25€</div>
                        <div class="ft-meta">
                            <span>Aïssata Barry</span>
                            <span>·</span>
                            <span>il y a 12h</span>
                        </div>
                    </div>
                    <div class="ft-replies">
                        <div class="ft-r-n">17</div>
                        <div class="ft-r-l">rép.</div>
                    </div>
                </a>
                <a href="#" class="forum-thread">
                    <div class="ft-avi" style="background:linear-gradient(135deg,#5A3010,#E85A1A)">O</div>
                    <div class="ft-body">
                        <div class="ft-cat">Cinéma</div>
                        <div class="ft-title">Débat : Wakanda Forever était-il à la hauteur des attentes ?</div>
                        <div class="ft-meta">
                            <span>Olu Adebayo</span>
                            <span>·</span>
                            <span>il y a 1j</span>
                        </div>
                    </div>
                    <div class="ft-replies">
                        <div class="ft-r-n">112</div>
                        <div class="ft-r-l">rép.</div>
                    </div>
                </a>
            </div>

            {{-- Catégories forum --}}
            <div class="forum-cats">
                <div style="padding:20px 24px;border-bottom:1px solid var(--border)">
                    <div class="lp-sec-label" style="margin-bottom:0">Catégories</div>
                </div>
                <a href="{{ route('forum.index') }}?cat=manga" class="forum-cat-item">
                    <div class="fci-icon">🎌</div>
                    <div>
                        <div class="fci-name">Manga & Animé</div>
                        <div class="fci-desc">Débats, recommandations</div>
                    </div>
                    <div class="fci-count">312</div>
                </a>
                <a href="{{ route('forum.index') }}?cat=gaming" class="forum-cat-item">
                    <div class="fci-icon">🎮</div>
                    <div>
                        <div class="fci-name">Gaming</div>
                        <div class="fci-desc">Reviews, tournois, news</div>
                    </div>
                    <div class="fci-count">248</div>
                </a>
                <a href="{{ route('forum.index') }}?cat=tech" class="forum-cat-item">
                    <div class="fci-icon">💻</div>
                    <div>
                        <div class="fci-name">Tech & IA</div>
                        <div class="fci-desc">Projets, outils, tutoriels</div>
                    </div>
                    <div class="fci-count">187</div>
                </a>
                <a href="{{ route('forum.index') }}?cat=culture" class="forum-cat-item">
                    <div class="fci-icon">🌍</div>
                    <div>
                        <div class="fci-name">Culture africaine</div>
                        <div class="fci-desc">Mythes, arts, traditions</div>
                    </div>
                    <div class="fci-count">143</div>
                </a>
                <a href="{{ route('forum.index') }}?cat=cosplay" class="forum-cat-item">
                    <div class="fci-icon">🎭</div>
                    <div>
                        <div class="fci-name">Cosplay</div>
                        <div class="fci-desc">Créations, conseils</div>
                    </div>
                    <div class="fci-count">95</div>
                </a>
                <a href="{{ route('forum.index') }}?cat=offtopic" class="forum-cat-item">
                    <div class="fci-icon">☕</div>
                    <div>
                        <div class="fci-name">Off-topic</div>
                        <div class="fci-desc">Détente & bavardages</div>
                    </div>
                    <div class="fci-count">124</div>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══ CTA REJOINDRE ══ --}}
<section class="lp-cta-section">
    <div class="lp-cta-box">
        <div class="lp-cta-icon">🌍</div>
        <div class="lp-cta-title">Tu es geek et africain·e ?<br>Tu es chez toi ici.</div>
        <p class="lp-cta-desc">
            Rejoins une communauté qui célèbre la culture geek africaine —
            sans complexe, sans filtre. Crée ton compte gratuitement et
            commence à lire, écrire et débattre dès aujourd'hui.
        </p>
        <div class="lp-ctas" style="margin-bottom:0">
            <a href="{{ route('register') }}" class="lp-btn-main">
                Créer mon compte gratuit
            </a>
            <a href="{{ route('login') }}" class="lp-btn-ghost">
                Se connecter
            </a>
        </div>
    </div>
</section>

{{-- ══ FOOTER ══ --}}
<footer class="lp-footer">
    <div class="lp-footer-copy">© {{ date('Y') }} MelanoGeek — La culture geek, vue d'Afrique.</div>
    <ul class="lp-footer-links">
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
        <li><a href="{{ route('forum.index') }}">Forum</a></li>
        <li><a href="{{ route('about') }}">À propos</a></li>
        <li><a href="#">Mentions légales</a></li>
        <li><a href="#">Contact</a></li>
    </ul>
</footer>

</div>
@endsection
