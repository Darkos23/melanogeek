@extends('layouts.app')

@section('title', ($post->title ?? 'Publication') . ' — MelanoGeek')

@php
    $metaDesc    = Str::limit(strip_tags($post->body ?? ''), 155);
    $metaImage   = $post->thumbnail
        ? asset('storage/' . $post->thumbnail)
        : ($post->media_url && $post->media_type === 'image' ? asset('storage/' . $post->media_url) : asset('images/og-default.jpg'));
@endphp
@section('meta_description', $metaDesc)
@section('og_type', 'article')
@section('og_title', ($post->title ?? 'Publication') . ' — MelanoGeek')
@section('og_description', $metaDesc)
@section('og_image', $metaImage)
@section('canonical', route('posts.show', $post->id))

@push('styles')
<style>
    /* ── BARRE DE LECTURE ── */
    #mg-read-bar {
        position: fixed;
        top: 0; left: 0;
        height: 3px;
        width: 0%;
        background: linear-gradient(90deg, var(--terra), var(--gold));
        z-index: 10000;
        pointer-events: none;
        transition: width .08s linear;
        border-radius: 0 2px 2px 0;
        box-shadow: 0 0 8px rgba(200,82,42,.55);
    }

    .post-page { padding-top: calc(80px + env(safe-area-inset-top)); min-height: 100vh; }

    .post-wrap {
        max-width: 680px;
        margin: 0 auto;
        padding: 32px 24px 60px;
    }

    /* ── BACK ── */
    .post-back {
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; color: var(--text-muted);
        font-size: .84rem; margin-bottom: 24px;
        transition: color .2s; cursor: pointer;
    }
    .post-back:hover { color: var(--text); }

    /* ── CARD ── */
    .post-card-full {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
    }

    /* ── AUTHOR ── */
    .post-author-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 24px 16px; gap: 12px;
    }
    .post-author-left { display: flex; align-items: center; gap: 12px; }
    .post-avatar {
        width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px; overflow: hidden; text-decoration: none;
    }
    .post-avatar-inner {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; font-weight: 700; overflow: hidden;
    }
    .post-avatar-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .post-author-name {
        font-family: var(--font-head); font-size: .92rem; font-weight: 700;
        color: var(--text); text-decoration: none;
    }
    .post-author-name:hover { color: var(--terra); }
    .post-author-meta { font-size: .76rem; color: var(--text-muted); margin-top: 2px; }
    

    /* ── CONTENU ── */
    .post-title {
        font-family: var(--font-head);
        font-size: 1.35rem; font-weight: 800; letter-spacing: -.02em;
        color: var(--text); line-height: 1.3;
        padding: 0 24px 12px;
    }
    /* ══════════════════════════════════════════
       CORPS ARTICLE — TYPOGRAPHIE ÉDITORIALE
    ══════════════════════════════════════════ */
    .post-body {
        font-size: 1rem;
        line-height: 1.82;
        color: rgba(240,232,216,.72);
        padding: 4px 28px 28px;
        font-family: var(--font-body);
        counter-reset: section-counter;
    }

    /* ── Paragraphes ── */
    .post-body p {
        margin-bottom: 1.35em;
    }

    /* ── Premier paragraphe — légèrement plus grand ── */
    .post-body > p:first-of-type {
        font-size: 1.06rem;
        color: rgba(240,232,216,.82);
        line-height: 1.78;
    }

    /* ── DROP CAP ── */
    .post-body.has-drop-cap > p:first-of-type::first-letter {
        font-family: var(--font-head);
        font-size: 4.4em;
        font-weight: 800;
        line-height: 0.78;
        float: left;
        margin: 6px 12px -4px 0;
        color: var(--terra);
        text-shadow: 0 0 40px rgba(200,82,42,.30);
    }

    /* ── H2 — chapitres éditoriaux ── */
    .post-body h2 {
        font-family: var(--font-head);
        font-size: 1.35rem;
        font-weight: 800;
        line-height: 1.25;
        color: var(--cream);
        margin: 2.6em 0 .75em;
        padding-left: 16px;
        border-left: 3px solid var(--terra);
        letter-spacing: -.02em;
        position: relative;
    }
    .post-body h2::before {
        content: counter(section-counter, decimal-leading-zero);
        counter-increment: section-counter;
        display: block;
        font-family: 'JetBrains Mono', monospace;
        font-size: .55rem;
        font-weight: 600;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--terra);
        margin-bottom: 6px;
        opacity: .85;
    }

    /* ── H3 — sous-sections ── */
    .post-body h3 {
        font-family: var(--font-head);
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--gold);
        margin: 2em 0 .6em;
        letter-spacing: -.01em;
    }

    /* ── H4 ── */
    .post-body h4 {
        font-size: .9rem;
        font-weight: 700;
        color: var(--cream);
        text-transform: uppercase;
        letter-spacing: .08em;
        margin: 1.6em 0 .5em;
    }

    /* ── Gras & italique ── */
    .post-body strong, .post-body b {
        color: var(--cream);
        font-weight: 700;
    }
    .post-body em, .post-body i {
        color: rgba(240,232,216,.88);
        font-style: italic;
    }

    /* ── Liens ── */
    .post-body a {
        color: var(--gold);
        text-decoration: underline;
        text-decoration-color: rgba(212,168,67,.35);
        text-underline-offset: 3px;
        transition: color .18s, text-decoration-color .18s;
    }
    .post-body a:hover {
        color: #e8c060;
        text-decoration-color: var(--gold);
    }

    /* ── Blockquote — citation éditoriale ── */
    .post-body blockquote {
        margin: 2.2em 0;
        padding: 20px 24px 20px 28px;
        border-left: 3px solid var(--gold);
        background: rgba(212,168,67,.05);
        border-radius: 0 10px 10px 0;
        font-size: 1.08rem;
        line-height: 1.72;
        color: rgba(240,232,216,.88);
        font-style: italic;
        position: relative;
    }
    .post-body blockquote::before {
        content: '\201C';
        position: absolute;
        top: -8px; left: 18px;
        font-size: 4rem;
        line-height: 1;
        color: var(--gold);
        opacity: .25;
        font-family: Georgia, serif;
    }
    .post-body blockquote p { margin-bottom: 0; }

    /* ── Code inline ── */
    .post-body code {
        font-family: 'JetBrains Mono', monospace;
        font-size: .82em;
        background: rgba(212,168,67,.10);
        color: var(--gold);
        padding: 2px 7px;
        border-radius: 5px;
        border: 1px solid rgba(212,168,67,.18);
    }

    /* ── Bloc de code ── */
    .post-body pre {
        background: #141414;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 20px 22px;
        overflow-x: auto;
        margin: 1.8em 0;
        font-family: 'JetBrains Mono', monospace;
        font-size: .82rem;
        line-height: 1.65;
        color: rgba(240,232,216,.80);
    }
    .post-body pre code {
        background: none;
        border: none;
        padding: 0;
        color: inherit;
        font-size: inherit;
    }

    /* ── Listes ── */
    .post-body ul, .post-body ol {
        padding-left: 0;
        margin: 1.2em 0 1.4em;
        list-style: none;
    }
    .post-body ul li, .post-body ol li {
        position: relative;
        padding-left: 22px;
        margin-bottom: .6em;
        color: rgba(240,232,216,.72);
        line-height: 1.72;
        counter-increment: list-counter;
    }
    .post-body ul li::before {
        content: '';
        position: absolute;
        left: 0; top: .62em;
        width: 6px; height: 6px;
        background: var(--terra);
        border-radius: 50%;
        opacity: .85;
    }
    .post-body ol {
        counter-reset: list-counter;
    }
    .post-body ol li::before {
        content: counter(list-counter) '.';
        position: absolute;
        left: 0;
        font-family: 'JetBrains Mono', monospace;
        font-size: .75em;
        font-weight: 700;
        color: var(--terra);
        top: .18em;
    }

    /* ── Séparateur horizontal ── */
    .post-body hr {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--gold), transparent);
        opacity: .3;
        margin: 2.5em 0;
    }

    /* ── Images dans l'article ── */
    .post-body img {
        max-width: 100%;
        border-radius: 10px;
        margin: 1.4em 0;
        display: block;
    }

    /* ── MÉDIA ── */
    .post-media {
        width: 100%;
        background: var(--bg-card2);
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        overflow: hidden;
    }
    .post-media img, .post-media video {
        width: 100%; max-height: 540px;
        object-fit: cover; object-position: center top; display: block;
    }

    /* ── CAROUSEL ── */
    .post-carousel {
        width: 100%;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        background: var(--bg-card2);
        position: relative;
        overflow: hidden;
    }
    .carousel-track-wrap {
        overflow: hidden;
    }
    .carousel-track {
        display: flex;
        transition: transform .3s ease;
    }
    .carousel-slide {
        min-width: 100%;
        display: flex; align-items: center; justify-content: center;
        background: var(--bg-card2);
        max-height: 540px;
        overflow: hidden;
    }
    .carousel-slide img {
        width: 100%; max-height: 540px;
        object-fit: cover; display: block;
    }
    /* Prev / Next arrows */
    .carousel-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 34px; height: 34px; border-radius: 50%;
        background: rgba(0,0,0,.55); border: 1px solid rgba(255,255,255,.15);
        color: white; font-size: .9rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 2; transition: background .2s;
    }
    .carousel-btn:hover { background: rgba(0,0,0,.8); }
    .carousel-btn.prev { left: 12px; }
    .carousel-btn.next { right: 12px; }
    /* Dots */
    .carousel-dots {
        position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);
        display: flex; gap: 5px; z-index: 2;
    }
    .carousel-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: rgba(255,255,255,.45); transition: background .2s, width .2s;
        cursor: pointer;
    }
    .carousel-dot.active {
        background: white; width: 18px; border-radius: 100px;
    }
    /* Counter badge */
    .carousel-counter {
        position: absolute; top: 10px; right: 12px;
        background: rgba(0,0,0,.55); color: white;
        font-size: .72rem; font-weight: 600; letter-spacing: .03em;
        padding: 3px 9px; border-radius: 100px; z-index: 2;
    }

    /* ── ACTIONS ── */
    .post-actions {
        display: flex; align-items: center; gap: 6px;
        padding: 14px 20px;
        border-top: 1px solid var(--border);
    }
    .action-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 16px; border-radius: 100px;
        background: transparent; border: 1px solid transparent;
        color: var(--text-muted); font-family: var(--font-body);
        font-size: .84rem; font-weight: 500;
        cursor: pointer; transition: all .2s;
    }
    .action-btn:hover { background: var(--bg-card2); border-color: var(--border); color: var(--text); }
    .action-btn.liked { color: #E05555; }
    .action-btn.liked:hover { background: rgba(224,85,85,.08); border-color: rgba(224,85,85,.3); }
    .action-btn svg { width: 17px; height: 17px; }
    .action-sep { flex: 1; }
    /* ── CARD BIO AUTEUR ── */
    .post-author-card {
        display: flex; gap: 18px; align-items: flex-start;
        padding: 24px;
        margin: 20px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(200,82,42,.05), rgba(212,168,67,.04));
        border: 1px solid rgba(212,168,67,.12);
    }
    .pac-avatar {
        width: 68px; height: 68px; border-radius: 50%;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
    }
    .pac-avatar img, .pac-avatar span {
        width: 100%; height: 100%; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: var(--bg-card2);
        color: var(--text); font-weight: 700; font-size: 1.5rem;
        object-fit: cover;
    }
    .pac-body { flex: 1; min-width: 0; }
    .pac-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: .64rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .12em;
        color: var(--terra); opacity: .8;
        margin-bottom: 4px;
    }
    .pac-name {
        display: inline-flex; align-items: center; gap: 6px;
        font-family: var(--font-head);
        font-size: 1.15rem; font-weight: 700;
        color: var(--text); text-decoration: none;
        letter-spacing: -.01em;
        transition: color .2s;
    }
    .pac-name:hover { color: var(--gold); }
    .pac-verified { color: var(--gold); font-size: .85rem; }
    .pac-bio {
        font-size: .86rem; line-height: 1.55;
        color: var(--text-muted);
        margin: 8px 0 10px;
    }
    .pac-link {
        display: inline-block;
        font-family: 'JetBrains Mono', monospace;
        font-size: .72rem; font-weight: 600;
        color: var(--gold);
        text-decoration: none;
        letter-spacing: .04em;
        transition: opacity .2s;
    }
    .pac-link:hover { opacity: .75; }
    @media (max-width: 520px) {
        .post-author-card { margin: 16px 12px; padding: 18px; gap: 14px; }
        .pac-avatar { width: 54px; height: 54px; }
        .pac-name { font-size: 1rem; }
        .pac-bio { font-size: .82rem; }
    }

    .post-views-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 13px; border-radius: 100px;
        background: rgba(212,168,67,.07); border: 1px solid rgba(212,168,67,.18);
        color: rgba(212,168,67,.75); font-size: .8rem; font-weight: 600;
        letter-spacing: .01em;
    }
    .action-share {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 16px; border-radius: 100px;
        background: transparent; border: 1px solid var(--border);
        color: var(--text-muted); font-family: var(--font-body);
        font-size: .84rem; cursor: pointer; transition: all .2s; text-decoration: none;
    }
    .action-share:hover { border-color: var(--gold); color: var(--gold); }

    /* ── AUDIO PLAYER ── */
    .post-audio {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        background: linear-gradient(135deg, rgba(200,82,42,.06), rgba(212,168,67,.06));
    }
    .audio-play-btn {
        width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
        background: var(--terra); border: none; color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; cursor: pointer; transition: transform .15s, opacity .2s;
    }
    .audio-play-btn:hover { transform: scale(1.08); }
    .audio-play-btn:active { transform: scale(.95); }
    .audio-info { flex: 1; min-width: 0; }
    .audio-track-name {
        font-size: .82rem; font-weight: 700; color: var(--text);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .audio-progress-wrap {
        height: 3px; background: var(--border); border-radius: 3px;
        margin-top: 5px; cursor: pointer; position: relative; overflow: hidden;
    }
    .audio-progress-bar {
        height: 100%; width: 0%; background: var(--terra);
        border-radius: 3px; transition: width .25s linear;
    }
    .audio-time {
        font-size: .68rem; color: var(--text-muted); flex-shrink: 0;
        font-variant-numeric: tabular-nums;
    }
    .audio-vol-btn {
        width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
        background: transparent; border: 1px solid var(--border);
        color: var(--text-muted); font-size: .8rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .2s;
    }
    .audio-vol-btn:hover { border-color: var(--terra); color: var(--terra); }
    .audio-loop-indicator {
        font-size: .65rem; color: var(--terra); font-weight: 700;
        background: var(--terra-soft); border-radius: 100px;
        padding: 2px 7px; flex-shrink: 0;
    }

    /* ── DRAFT BANNER ── */
    .draft-banner {
        display: flex; align-items: center; gap: 10px;
        background: rgba(212,168,67,.08);
        border-bottom: 1px solid rgba(212,168,67,.2);
        padding: 12px 20px;
        font-size: .82rem; color: var(--gold);
    }

    /* ── OWNER ACTIONS ── */
    .owner-actions {
        display: flex; gap: 8px;
    }
    .btn-edit-post {
        background: transparent; border: 1px solid var(--border-hover);
        color: var(--text-muted); padding: 7px 14px; border-radius: 100px;
        font-family: var(--font-body); font-size: .78rem; font-weight: 500;
        text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
        transition: all .2s;
    }
    .btn-edit-post:hover { background: var(--bg-hover); color: var(--text); border-color: var(--text-muted); }

    .btn-delete-post {
        background: transparent; border: 1px solid rgba(224,85,85,.3);
        color: #E05555; padding: 7px 14px; border-radius: 100px;
        font-family: var(--font-body); font-size: .78rem; font-weight: 500;
        cursor: pointer; transition: all .2s;
    }
    .btn-delete-post:hover { background: rgba(224,85,85,.08); border-color: #E05555; }

    /* ── SUCCESS FLASH ── */
    .post-success {
        display: flex; align-items: center; gap: 10px;
        background: rgba(45,90,61,.12);
        border: 1px solid rgba(45,90,61,.25);
        color: #6DC48A;
        padding: 14px 18px; border-radius: 14px;
        font-size: .88rem; font-weight: 500;
        margin-bottom: 20px;
    }

    /* ── COMMENTS ── */
    .post-comments {
        border-top: 1px solid var(--border);
        padding: 20px 24px 24px;
    }
    .comments-title {
        font-family: var(--font-head); font-size: .88rem; font-weight: 700;
        color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em;
        margin-bottom: 16px;
    }

    /* Form */
    .comment-form-wrap {
        display: flex; gap: 12px; align-items: flex-start;
        margin-bottom: 20px;
    }
    .comment-form-avi {
        width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px; overflow: hidden;
    }
    .comment-form-avi-inner {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: .78rem; font-weight: 700; overflow: hidden;
    }
    .comment-form-avi-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .comment-form { flex: 1; display: flex; flex-direction: column; gap: 8px; }
    .comment-input {
        width: 100%; background: var(--bg-card2);
        border: 1px solid var(--border); border-radius: 12px;
        color: var(--text); font-family: var(--font-body); font-size: .88rem;
        padding: 10px 14px; resize: none; line-height: 1.5;
        transition: border-color .2s; min-height: 42px; max-height: 160px;
        overflow-y: auto;
    }
    .comment-input::placeholder { color: var(--text-muted); }
    .comment-input:focus { outline: none; border-color: var(--terra); }
    /* Comment toolbar */
    .comment-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 4px 0;
    }
    .comment-toolbar-left { display: flex; gap: 4px; align-items: center; position: relative; }
    .cmt-tool-btn {
        background: none; border: none; cursor: pointer;
        font-size: 1rem; padding: 5px 7px; border-radius: 8px;
        color: var(--text-muted); line-height: 1;
        transition: background .15s;
    }
    .cmt-tool-btn:hover { background: var(--bg-hover); }
    /* Emoji panel */
    .cmt-emoji-panel {
        display: none; position: absolute; bottom: calc(100% + 6px); left: 0;
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 12px; padding: 10px; width: 260px;
        box-shadow: 0 8px 24px rgba(0,0,0,.3); z-index: 50;
    }
    .cmt-emoji-panel.open { display: block; }
    .cmt-emoji-search {
        width: 100%; background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 8px; padding: 6px 10px; color: var(--text);
        font-size: .8rem; outline: none; margin-bottom: 8px;
    }
    .cmt-emoji-grid {
        display: grid; grid-template-columns: repeat(8,1fr); gap: 2px;
        max-height: 160px; overflow-y: auto;
    }
    .cmt-emoji-grid button {
        background: none; border: none; cursor: pointer;
        font-size: 1.1rem; padding: 4px; border-radius: 6px;
        line-height: 1; min-height: unset;
        transition: background .12s;
    }
    .cmt-emoji-grid button:hover { background: var(--bg-hover); }
    /* GIF panel */
    .cmt-gif-panel {
        display: none; position: absolute; bottom: calc(100% + 6px); left: 0;
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 12px; padding: 10px; width: 280px;
        box-shadow: 0 8px 24px rgba(0,0,0,.3); z-index: 50;
    }
    .cmt-gif-panel.open { display: block; }
    .cmt-gif-search {
        width: 100%; background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 8px; padding: 6px 10px; color: var(--text);
        font-size: .8rem; outline: none; margin-bottom: 8px;
    }
    .cmt-gif-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 4px;
        max-height: 180px; overflow-y: auto;
    }
    .cmt-gif-grid img {
        width: 100%; height: 80px; object-fit: cover; border-radius: 6px; cursor: pointer;
        transition: opacity .15s;
    }
    .cmt-gif-grid img:hover { opacity: .8; }
    .cmt-gif-preview {
        display: flex; align-items: center; gap: 8px; padding: 6px 10px;
        background: var(--bg-card2); border-radius: 8px; margin-top: 6px;
    }
    .cmt-gif-preview img { height: 40px; border-radius: 4px; }
    .cmt-gif-remove { background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:.8rem; }
    /* Edit comment */
    .cmt-edit-wrap {
        display: none; flex-direction: column; gap: 6px; margin-top: 6px;
    }
    .cmt-edit-wrap.open { display: flex; }
    .cmt-edit-input {
        width: 100%; background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 10px; padding: 8px 12px; color: var(--text);
        font-family: var(--font-body); font-size: .875rem; line-height: 1.5;
        resize: none; outline: none;
    }
    .cmt-edit-input:focus { border-color: var(--terra); }
    .cmt-edit-actions { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-cmt-save { background: var(--terra); border: none; color: white; padding: 5px 14px; border-radius: 100px; font-size: .78rem; font-weight: 600; cursor: pointer; }
    .btn-cmt-cancel { background: none; border: 1px solid var(--border); color: var(--text-muted); padding: 5px 12px; border-radius: 100px; font-size: .78rem; cursor: pointer; }
    .btn-comment-edit { background: none; border: none; color: var(--text-muted); font-size: .7rem; cursor: pointer; padding: 2px 6px; border-radius: 6px; transition: color .2s, background .2s; }
    .btn-comment-edit:hover { color: var(--gold); background: rgba(212,168,67,.08); }

    .comment-form-actions { display: flex; justify-content: flex-end; }
    .btn-comment-submit {
        background: var(--terra); border: none; color: white;
        padding: 7px 18px; border-radius: 100px;
        font-family: var(--font-body); font-size: .82rem; font-weight: 600;
        cursor: pointer; transition: opacity .2s;
    }
    .btn-comment-submit:disabled { opacity: .4; cursor: not-allowed; }
    .btn-comment-submit:not(:disabled):hover { opacity: .85; }

    /* Guest prompt */
    .comment-guest-prompt {
        text-align: center; padding: 14px;
        background: var(--bg-card2); border-radius: 12px;
        font-size: .85rem; color: var(--text-muted);
        margin-bottom: 20px;
    }
    .comment-guest-prompt a { color: var(--terra); text-decoration: none; font-weight: 600; }

    /* Comment items */
    .comment-item {
        display: flex; gap: 10px; padding: 12px 0;
        border-bottom: 1px solid var(--border);
        animation: fadeInUp .25s ease;
    }
    .comment-item:last-child { border-bottom: none; }
    .comment-avi {
        width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px; overflow: hidden; text-decoration: none;
    }
    .comment-avi-inner {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--bg-card);
        display: flex; align-items: center; justify-content: center;
        font-size: .72rem; font-weight: 700; overflow: hidden;
    }
    .comment-avi-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .comment-content { flex: 1; min-width: 0; }
    .comment-header { display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap; margin-bottom: 4px; }
    .comment-author {
        font-size: .84rem; font-weight: 700; color: var(--text);
        text-decoration: none;
    }
    .comment-author:hover { color: var(--terra); }
    .comment-verified { font-size: .7rem; color: var(--gold); }
    .comment-ago { font-size: .72rem; color: var(--text-muted); }
    .comment-body {
        font-size: .875rem; color: var(--text-muted); line-height: 1.55;
        word-break: break-word; white-space: pre-wrap;
    }
    .btn-comment-del {
        background: none; border: none; color: var(--text-muted);
        font-size: .7rem; cursor: pointer; padding: 2px 6px; border-radius: 6px;
        margin-left: auto; flex-shrink: 0;
        transition: color .2s, background .2s;
    }
    .btn-comment-del:hover { color: #E05555; background: rgba(224,85,85,.08); }

    /* States */
    .comments-loading, .comments-empty {
        text-align: center; padding: 28px 0;
        color: var(--text-muted); font-size: .85rem;
    }
    .comments-empty-icon { font-size: 1.6rem; margin-bottom: 6px; }
    .btn-load-more {
        width: 100%; margin-top: 8px; padding: 10px;
        background: var(--bg-card2); border: 1px solid var(--border);
        color: var(--text-muted); border-radius: 12px;
        font-family: var(--font-body); font-size: .83rem; cursor: pointer;
        transition: border-color .2s, color .2s;
    }
    .btn-load-more:hover { border-color: var(--terra); color: var(--terra); }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 520px) {
        .post-wrap { padding: 16px 12px 40px; }
        .post-author-row { padding: 16px 14px 12px; }
        .post-title { padding: 0 14px 10px; font-size: 1.15rem; }
        .post-body  { padding: 0 14px 16px; font-size: .92rem; }

        .post-actions { padding: 8px 10px; gap: 4px; }
        .action-btn   { padding: 7px 10px; font-size: .8rem; gap: 5px; }
        .action-share { padding: 7px 10px; font-size: .8rem; }

        .post-comments { padding: 16px 14px 20px; }
        .comment-input { font-size: .85rem; }
        .comment-body  { font-size: .84rem; }
    }
</style>
@endpush

@section('content')
<div id="mg-read-bar" aria-hidden="true"></div>
<div class="post-page">
<div class="post-wrap">

    <a href="{{ url()->previous() }}" class="post-back">← Retour</a>

    @if(session('status') === 'post-created')
        <div class="post-success">✓ Publication créée avec succès !</div>
    @elseif(session('status') === 'post-updated')
        <div class="post-success">✓ Publication mise à jour !</div>
    @endif

    <div class="post-card-full" data-reveal>

        {{-- Brouillon --}}
        @if(! $post->is_published)
            <div class="draft-banner">
                💾 Brouillon — cette publication n'est pas encore visible publiquement.
                @if(auth()->id() === $post->user_id)
                    <form method="POST" action="{{ route('posts.publish', $post->id) }}" style="margin-left:auto;">
                        @csrf @method('PATCH')
                        <button type="submit" style="background:var(--gold);border:none;color:#000;padding:5px 14px;border-radius:100px;font-size:.78rem;font-weight:700;cursor:pointer;">
                            Publier maintenant
                        </button>
                    </form>
                @endif
            </div>
        @endif

        {{-- Auteur --}}
        <div class="post-author-row">
            <div class="post-author-left">
                <a href="{{ route('profile.show', $post->user->username) }}" class="post-avatar">
                    <div class="post-avatar-inner">
                        @if($post->user->avatar)
                            <img src="{{ Storage::url($post->user->avatar) }}" alt="">
                        @else
                            {{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}
                        @endif
                    </div>
                </a>
                <div>
                    <a href="{{ route('profile.show', $post->user->username) }}" class="post-author-name">
                        {{ $post->user->username }}
                    </a>
                    <div class="post-author-meta">
                        {{ $post->created_at?->diffForHumans() ?? "-" }}
                    </div>
                </div>
            </div>

            {{-- Owner actions --}}
            @auth
                @if(auth()->id() === $post->user_id)
                    <div class="owner-actions">
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn-edit-post">✏️ Modifier</a>
                        <form method="POST" action="{{ route('posts.destroy', $post->id) }}"
                              onsubmit="return confirm('Supprimer cette publication ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete-post">🗑 Supprimer</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        {{-- Image de couverture --}}
        @if($post->thumbnail)
            <div style="margin:0 0 20px;border-radius:10px;overflow:hidden;">
                <img src="{{ asset('storage/'.$post->thumbnail) }}" alt="{{ $post->title }}" style="width:100%;max-height:500px;object-fit:cover;object-position:center;display:block;">
            </div>
        @endif

        {{-- Titre --}}
        @if($post->title)
            <div class="post-title">{{ $post->title }}</div>
        @endif

        {{-- Corps --}}
        @if($post->body)
            @php $hasDropCap = $post->title && str_word_count(strip_tags($post->body)) >= 80; @endphp
            <div class="post-body{{ $hasDropCap ? ' has-drop-cap' : '' }}">{!! $post->body !!}</div>
        @endif

        {{-- Média --}}
        @if($post->mediaFiles->isNotEmpty())
            @php $imgs = $post->mediaFiles; $total = $imgs->count(); @endphp
            @if($total === 1)
                <div class="post-media">
                    <img src="{{ Storage::url($imgs->first()->media_url) }}" alt="{{ $post->title }}">
                </div>
            @else
                <div class="post-carousel" id="postCarousel">
                    <div class="carousel-track-wrap">
                        <div class="carousel-track" id="carouselTrack">
                            @foreach($imgs as $img)
                                <div class="carousel-slide">
                                    <img src="{{ Storage::url($img->media_url) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="carousel-btn prev" onclick="carouselMove(-1)">‹</button>
                    <button class="carousel-btn next" onclick="carouselMove(1)">›</button>
                    <div class="carousel-counter"><span id="carouselCur">1</span> / {{ $total }}</div>
                    <div class="carousel-dots" id="carouselDots">
                        @for($i = 0; $i < $total; $i++)
                            <div class="carousel-dot {{ $i === 0 ? 'active' : '' }}" onclick="carouselGo({{ $i }})"></div>
                        @endfor
                    </div>
                </div>
            @endif
        @elseif($post->media_url)
            <div class="post-media">
                @if($post->media_type === 'video')
                    <video src="{{ Storage::url($post->media_url) }}" controls controlsList="nodownload" oncontextmenu="return false"></video>
                @else
                    <img src="{{ Storage::url($post->media_url) }}" alt="{{ $post->title }}">
                @endif
            </div>
        @endif

        {{-- Audio de fond --}}
        @if($post->audio_url)
        <div class="post-audio" id="postAudioBar">
            <audio id="postAudio" src="{{ Storage::url($post->audio_url) }}" loop preload="metadata" controlsList="nodownload" oncontextmenu="return false"></audio>
            <button class="audio-play-btn" id="audioPlayBtn" onclick="audioToggle()">▶</button>
            <div class="audio-info">
                <div class="audio-track-name">🎵 {{ $post->audio_name ?? 'Musique' }}</div>
                <div class="audio-progress-wrap" id="audioProgressWrap">
                    <div class="audio-progress-bar" id="audioProgressBar"></div>
                </div>
            </div>
            <span class="audio-time" id="audioTime">0:00</span>
            <button class="audio-vol-btn" id="audioVolBtn" onclick="audioMuteToggle()" title="Couper le son">🔊</button>
        </div>
        @endif

        {{-- Actions --}}
        <div class="post-actions">
            <button class="action-btn {{ $liked ? 'liked' : '' }}" id="likeBtn" onclick="toggleLike({{ $post->id }}, this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <span id="likesCount">{{ number_format($post->likes_count) }}</span>
            </button>
            <button class="action-btn" onclick="document.getElementById('comments').scrollIntoView({behavior:'smooth'})">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span id="commentsCount">{{ number_format($post->comments_count) }}</span>
            </button>
            @auth
            @if(auth()->id() === $post->user_id || auth()->user()->isAdmin())
            <span class="post-views-pill">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                {{ number_format($post->views_count) }} vue{{ $post->views_count != 1 ? 's' : '' }}
            </span>
            @endif
            @endauth
            <div class="action-sep"></div>
            <a href="#" class="action-share" onclick="sharePost(); return false;">
                ↗ Partager
            </a>
        </div>

        {{-- ── BIO AUTEUR ── --}}
        <div class="post-author-card">
            <a href="{{ route('profile.show', $post->user->username) }}" class="pac-avatar">
                @if($post->user->avatar)
                    <img src="{{ Storage::url($post->user->avatar) }}" alt="">
                @else
                    <span>{{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}</span>
                @endif
            </a>
            <div class="pac-body">
                <div class="pac-label">Écrit par</div>
                <a href="{{ route('profile.show', $post->user->username) }}" class="pac-name">
                    {{ $post->user->name }}
                    @if($post->user->is_verified ?? false)
                        <span class="pac-verified" title="Vérifié">✓</span>
                    @endif
                </a>
                @if($post->user->bio)
                <p class="pac-bio">{{ Str::limit($post->user->bio, 180) }}</p>
                @endif
            </div>
        </div>

        {{-- ── COMMENTAIRES ── --}}
        <div id="comments" class="post-comments">
            <div class="comments-title">Commentaires</div>

            {{-- Formulaire --}}
            @auth
            <div class="comment-form-wrap">
                <div class="comment-form-avi">
                    <div class="comment-form-avi-inner">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="">
                        @else
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div class="comment-form">
                    <textarea id="commentBody" class="comment-input"
                              placeholder="Ajouter un commentaire…" maxlength="1000" rows="1"></textarea>
                    <div id="cmtGifPreview" class="cmt-gif-preview" style="display:none;">
                        <img id="cmtGifImg" src="" alt="GIF">
                        <span id="cmtGifLabel" style="font-size:.72rem;color:var(--text-muted);flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
                        <button type="button" class="cmt-gif-remove" onclick="removeCmtGif()">✕</button>
                    </div>
                    <div class="comment-toolbar">
                        <div class="comment-toolbar-left">
                            <!-- Emoji -->
                            <div style="position:relative;">
                                <button type="button" class="cmt-tool-btn" id="cmtEmojiBtn" title="Emoji">😊</button>
                                <div class="cmt-emoji-panel" id="cmtEmojiPanel">
                                    <input type="text" class="cmt-emoji-search" placeholder="Rechercher…" id="cmtEmojiSearch">
                                    <div class="cmt-emoji-grid" id="cmtEmojiGrid"></div>
                                </div>
                            </div>
                            <!-- GIF -->
                            <div style="position:relative;">
                                <button type="button" class="cmt-tool-btn" id="cmtGifBtn" title="GIF">GIF</button>
                                <div class="cmt-gif-panel" id="cmtGifPanel">
                                    <input type="text" class="cmt-gif-search" placeholder="Rechercher un GIF…" id="cmtGifSearch">
                                    <div class="cmt-gif-grid" id="cmtGifGrid"></div>
                                </div>
                            </div>
                        </div>
                        <button id="commentSubmit" class="btn-comment-submit" disabled>Envoyer</button>
                    </div>
                    <input type="hidden" id="cmtGifUrl" value="">
                </div>
            </div>
            @else
            <div class="comment-guest-prompt">
                <a href="{{ route('login') }}">Connecte-toi</a> pour laisser un commentaire
            </div>
            @endauth

            {{-- Liste --}}
            <div id="commentsList">
                <div class="comments-loading">Chargement…</div>
            </div>

            <button id="commentsLoadMore" class="btn-load-more" style="display:none;">
                Voir plus de commentaires
            </button>
        </div>

    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    /* ── Like (toggle) ── */
    let _liking = false;
    async function toggleLike(postId, btn) {
        @guest
        window.location.href = '{{ route('login') }}';
        return;
        @endguest

        if (_liking) return;
        _liking = true;

        // Optimistic UI
        const wasLiked = btn.classList.contains('liked');
        const countEl  = document.getElementById('likesCount');
        const cur      = parseInt(countEl.textContent.replace(/\D/g, '')) || 0;
        btn.classList.toggle('liked', !wasLiked);
        countEl.textContent = !wasLiked ? cur + 1 : Math.max(0, cur - 1);

        try {
            const res = await fetch(`/posts/${postId}/like`, {
                method:  'POST',
                headers: {
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
            });

            if (res.status === 401 || res.status === 403) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            if (res.ok) {
                const data = await res.json();
                btn.classList.toggle('liked', data.liked);
                countEl.textContent = data.count;
            } else {
                // Rollback
                btn.classList.toggle('liked', wasLiked);
                countEl.textContent = cur;
            }
        } catch (e) {
            // Rollback on network error
            btn.classList.toggle('liked', wasLiked);
            countEl.textContent = cur;
        } finally {
            _liking = false;
        }
    }

    function sharePost() {
        if (navigator.share) {
            navigator.share({ title: document.title, url: window.location.href });
        } else {
            navigator.clipboard.writeText(window.location.href)
                .then(() => alert('Lien copié !'));
        }
    }

    /* ── Audio Player ── */
    (function () {
        const audio    = document.getElementById('postAudio');
        if (!audio) return;
        const playBtn  = document.getElementById('audioPlayBtn');
        const progBar  = document.getElementById('audioProgressBar');
        const timeEl   = document.getElementById('audioTime');
        const volBtn   = document.getElementById('audioVolBtn');
        const progWrap = document.getElementById('audioProgressWrap');

        function fmt(s) {
            const m = Math.floor(s / 60);
            return m + ':' + String(Math.floor(s % 60)).padStart(2, '0');
        }

        window.audioToggle = function() {
            if (audio.paused) { audio.play(); playBtn.textContent = '⏸'; }
            else              { audio.pause(); playBtn.textContent = '▶'; }
        };

        window.audioMuteToggle = function() {
            audio.muted = !audio.muted;
            volBtn.textContent = audio.muted ? '🔇' : '🔊';
        };

        audio.addEventListener('timeupdate', () => {
            if (!audio.duration) return;
            const pct = (audio.currentTime / audio.duration) * 100;
            progBar.style.width = pct + '%';
            timeEl.textContent  = fmt(audio.currentTime);
        });

        // Click on progress bar to seek
        progWrap.addEventListener('click', e => {
            if (!audio.duration) return;
            const rect = progWrap.getBoundingClientRect();
            audio.currentTime = ((e.clientX - rect.left) / rect.width) * audio.duration;
        });

        // Pause when leaving page
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) audio.pause();
        });
    })();

    /* ── Carousel ── */
    (function () {
        const track  = document.getElementById('carouselTrack');
        if (!track) return;
        const dots   = document.querySelectorAll('.carousel-dot');
        const curEl  = document.getElementById('carouselCur');
        const total  = dots.length;
        let idx = 0;

        window.carouselGo = function(i) {
            idx = Math.max(0, Math.min(total - 1, i));
            track.style.transform = `translateX(-${idx * 100}%)`;
            dots.forEach((d, j) => d.classList.toggle('active', j === idx));
            if (curEl) curEl.textContent = idx + 1;
        };

        window.carouselMove = function(dir) { carouselGo(idx + dir); };

        // Swipe touch support
        let startX = 0;
        const wrap = document.getElementById('postCarousel');
        wrap.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
        wrap.addEventListener('touchend',   e => {
            const dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 40) carouselMove(dx < 0 ? 1 : -1);
        });

        // Keyboard arrows when carousel is visible
        document.addEventListener('keydown', e => {
            if (!document.getElementById('postCarousel')) return;
            if (e.key === 'ArrowRight') carouselMove(1);
            if (e.key === 'ArrowLeft')  carouselMove(-1);
        });
    })();

    /* ══════════════════════════════════════
       COMMENTAIRES
    ══════════════════════════════════════ */
    (function () {
        const POST_ID     = {{ $post->id }};
        const AUTH_ID     = {{ auth()->id() ?? 'null' }};
        const IS_ADMIN    = {{ auth()->user()?->isAdmin() ? 'true' : 'false' }};
        const CSRF        = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        const list        = document.getElementById('commentsList');
        const loadMoreBtn = document.getElementById('commentsLoadMore');
        const countEl     = document.getElementById('commentsCount');
        const bodyEl      = document.getElementById('commentBody');
        const submitBtn   = document.getElementById('commentSubmit');

        let currentPage = 1;
        let lastPage    = 1;
        let isPosting   = false;

        /* ── Helpers ── */
        function esc(s) {
            return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function timeAgo(iso) {
            const diff = Math.floor((Date.now() - new Date(iso)) / 1000);
            if (diff < 60)   return 'à l\'instant';
            if (diff < 3600) return Math.floor(diff / 60) + ' min';
            if (diff < 86400)return Math.floor(diff / 3600) + ' h';
            return Math.floor(diff / 86400) + ' j';
        }

        function buildItem(c) {
            const isOwn  = AUTH_ID && AUTH_ID === c.user.id;
            const canDel = AUTH_ID && (isOwn || IS_ADMIN);
            const aviInner = c.user.avatar
                ? `<img src="${esc(c.user.avatar)}" alt="">`
                : `<span>${esc((c.user.name || '?')[0].toUpperCase())}</span>`;
            const verified = c.user.is_verified ? `<span class="comment-verified">✓</span>` : '';
            const body = c.body || '';
            // body may contain a GIF tag like [gif:url]
            const gifMatch = body.match(/\[gif:(https?[^\]]+)\]/);
            const textPart = body.replace(/\[gif:https?[^\]]+\]/, '').trim();
            const bodyHtml = (textPart ? `<div class="comment-body">${esc(textPart)}</div>` : '')
                + (gifMatch ? `<img src="${gifMatch[1]}" alt="GIF" style="max-width:200px;border-radius:8px;margin-top:6px;display:block;">` : '');
            const editBtn = isOwn ? `<button class="btn-comment-edit" data-id="${c.id}" title="Modifier">✏️</button>` : '';
            const delBtn  = canDel ? `<button class="btn-comment-del" data-id="${c.id}" title="Supprimer">✕</button>` : '';

            return `<div class="comment-item" id="comment-${c.id}">
                <a href="/@${esc(c.user.username)}" class="comment-avi">
                    <div class="comment-avi-inner">${aviInner}</div>
                </a>
                <div class="comment-content">
                    <div class="comment-header">
                        <a href="/@${esc(c.user.username)}" class="comment-author">${esc(c.user.name)}</a>
                        ${verified}
                        <span class="comment-ago">${timeAgo(c.created_at)}</span>
                        ${editBtn}${delBtn}
                    </div>
                    <div class="comment-body-wrap" id="cbw-${c.id}">${bodyHtml}</div>
                    <div class="cmt-edit-wrap" id="cedit-${c.id}">
                        <textarea class="cmt-edit-input" rows="2">${esc(body)}</textarea>
                        <div class="cmt-edit-actions">
                            <button type="button" class="btn-cmt-cancel" onclick="cancelEdit(${c.id})">Annuler</button>
                            <button type="button" class="btn-cmt-save"   onclick="saveEdit(${c.id})">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        /* ── Chargement ── */
        async function loadComments(page) {
            try {
                const res  = await fetch(`/api/posts/${POST_ID}/comments?page=${page}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();

                lastPage = json.meta.last_page;

                if (page === 1) {
                    if (!json.data.length) {
                        list.innerHTML = `<div class="comments-empty">
                            <div class="comments-empty-icon">💬</div>
                            Sois le premier à commenter !
                        </div>`;
                    } else {
                        list.innerHTML = json.data.map(buildItem).join('');
                    }
                } else {
                    list.insertAdjacentHTML('beforeend', json.data.map(buildItem).join(''));
                }

                loadMoreBtn.style.display = currentPage < lastPage ? 'block' : 'none';
                attachListeners();
            } catch (e) {
                list.innerHTML = '<div class="comments-empty">Erreur de chargement.</div>';
            }
        }

        /* ── Load More ── */
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                currentPage++;
                loadComments(currentPage);
            });
        }

        /* ── Textarea auto-resize + enable submit ── */
        const gifUrlEl = document.getElementById('cmtGifUrl');
        function updateSubmitState() {
            if (submitBtn) submitBtn.disabled = !(bodyEl?.value.trim() || gifUrlEl?.value);
        }
        if (bodyEl) {
            bodyEl.addEventListener('input', () => {
                bodyEl.style.height = 'auto';
                bodyEl.style.height = Math.min(bodyEl.scrollHeight, 160) + 'px';
                updateSubmitState();
            });
        }

        /* ── Soumettre un commentaire ── */
        if (submitBtn) {
            submitBtn.addEventListener('click', async () => {
                let body = bodyEl?.value.trim() || '';
                const gifUrl = gifUrlEl?.value;
                if (gifUrl) body = body + (body ? ' ' : '') + `[gif:${gifUrl}]`;
                if (!body || isPosting) return;
                isPosting = true;
                submitBtn.disabled = true;

                try {
                    const res = await fetch(`/api/posts/${POST_ID}/comments`, {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                        body: JSON.stringify({ body }),
                    });
                    if (res.status === 401) { window.location.href = '{{ route('login') }}'; return; }
                    if (!res.ok) { const err = await res.json().catch(() => ({})); alert(err.message || 'Erreur.'); return; }
                    const comment = await res.json();
                    const empty = list.querySelector('.comments-empty, .comments-loading');
                    if (empty) empty.remove();
                    list.insertAdjacentHTML('afterbegin', buildItem(comment));
                    attachListeners();
                    if (bodyEl) { bodyEl.value = ''; bodyEl.style.height = 'auto'; }
                    removeCmtGif();
                    if (countEl) { const cur = parseInt(countEl.textContent.replace(/\D/g,''))||0; countEl.textContent = cur+1; }
                } catch (e) { alert('Erreur réseau.'); }
                finally { isPosting = false; updateSubmitState(); }
            });
        }

        /* ── Supprimer / Edit ── */
        function attachListeners() {
            list.querySelectorAll('.btn-comment-del').forEach(btn => {
                if (btn.dataset.bound) return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', async () => {
                    if (!confirm('Supprimer ce commentaire ?')) return;
                    const id = btn.dataset.id;
                    try {
                        const res = await fetch(`/api/comments/${id}`, {
                            method: 'DELETE',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                        });
                        if (res.ok) {
                            document.getElementById(`comment-${id}`)?.remove();
                            if (countEl) { const cur = parseInt(countEl.textContent.replace(/\D/g,''))||1; countEl.textContent = Math.max(0,cur-1); }
                            if (!list.querySelector('.comment-item')) list.innerHTML = `<div class="comments-empty"><div class="comments-empty-icon">💬</div>Sois le premier à commenter !</div>`;
                        }
                    } catch(e) { alert('Erreur.'); }
                });
            });
            list.querySelectorAll('.btn-comment-edit').forEach(btn => {
                if (btn.dataset.bound) return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    document.getElementById(`cbw-${id}`)?.style.setProperty('display','none');
                    document.getElementById(`cedit-${id}`)?.classList.add('open');
                });
            });
        }
        window.cancelEdit = function(id) {
            document.getElementById(`cbw-${id}`)?.style.removeProperty('display');
            document.getElementById(`cedit-${id}`)?.classList.remove('open');
        };
        window.saveEdit = async function(id) {
            const wrap = document.getElementById(`cedit-${id}`);
            const inp  = wrap?.querySelector('.cmt-edit-input');
            const body = inp?.value.trim();
            if (!body) return;
            try {
                const res = await fetch(`/api/comments/${id}`, {
                    method: 'PATCH',
                    headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF },
                    body: JSON.stringify({ body }),
                });
                if (res.ok) {
                    const updated = await res.json();
                    const bw = document.getElementById(`cbw-${id}`);
                    if (bw) {
                        const gifMatch = body.match(/\[gif:(https?[^\]]+)\]/);
                        const textPart = body.replace(/\[gif:https?[^\]]+\]/, '').trim();
                        bw.innerHTML = (textPart ? `<div class="comment-body">${esc(textPart)}</div>` : '')
                            + (gifMatch ? `<img src="${gifMatch[1]}" alt="GIF" style="max-width:200px;border-radius:8px;margin-top:6px;display:block;">` : '');
                        bw.style.removeProperty('display');
                    }
                    cancelEdit(id);
                }
            } catch(e) { alert('Erreur.'); }
        };

        /* ── Emoji picker ── */
        const EMOJIS = ['😀','😂','🥲','😍','🥰','😎','🤔','🤯','😤','🥳','🎉','❤️','🔥','💯','👍','👏','🙌','💪','🤝','✨','🌟','💎','🎮','📱','💻','🎵','🎬','📚','🌍','🦁','🐉','⚔️','🏆','🎯','💡','🚀','🌈','😭','😅','🤣','😊','😇','🥺','😢','😡','🤩','🫡','🫠','🫶','💀','👀','🙏','🤦','🤷','💬','🗣️','👑','🎭'];
        const emojiGrid = document.getElementById('cmtEmojiGrid');
        const emojiSearch = document.getElementById('cmtEmojiSearch');
        const emojiPanel = document.getElementById('cmtEmojiPanel');
        const emojiBtn = document.getElementById('cmtEmojiBtn');

        function renderEmojis(list) {
            emojiGrid.innerHTML = list.map(e => `<button type="button" onclick="insertEmoji('${e}')">${e}</button>`).join('');
        }
        renderEmojis(EMOJIS);
        emojiSearch?.addEventListener('input', () => {
            renderEmojis(EMOJIS.filter(e => e.includes(emojiSearch.value)));
        });
        window.insertEmoji = function(e) {
            if (!bodyEl) return;
            const s = bodyEl.selectionStart, end = bodyEl.selectionEnd;
            bodyEl.value = bodyEl.value.slice(0,s) + e + bodyEl.value.slice(end);
            bodyEl.selectionStart = bodyEl.selectionEnd = s + e.length;
            bodyEl.focus();
            updateSubmitState();
        };
        emojiBtn?.addEventListener('click', e => {
            e.stopPropagation();
            emojiPanel?.classList.toggle('open');
            gifPanel?.classList.remove('open');
        });

        /* ── GIF picker (Tenor) ── */
        const gifPanel = document.getElementById('cmtGifPanel');
        const gifGrid  = document.getElementById('cmtGifGrid');
        const gifSearch = document.getElementById('cmtGifSearch');
        const gifBtn   = document.getElementById('cmtGifBtn');
        let gifTimer;

        async function fetchGifs(q) {
            const url = q
                ? `https://tenor.googleapis.com/v2/search?q=${encodeURIComponent(q)}&key=AIzaSyAyimkuYQYF_FXVALexPko2arMuR3S1gc4&limit=12&media_filter=gif`
                : `https://tenor.googleapis.com/v2/featured?key=AIzaSyAyimkuYQYF_FXVALexPko2arMuR3S1gc4&limit=12&media_filter=gif`;
            try {
                const res = await fetch(url);
                const data = await res.json();
                gifGrid.innerHTML = (data.results||[]).map(r => {
                    const gif = r.media_formats?.gif?.url || r.media_formats?.tinygif?.url || '';
                    return `<img src="${gif}" alt="${r.content_description||'GIF'}" onclick="selectGif('${gif}','${esc(r.content_description||'GIF')}')">`;
                }).join('');
            } catch(e) { gifGrid.innerHTML = '<span style="font-size:.75rem;color:var(--text-muted);padding:8px;">Erreur chargement GIFs</span>'; }
        }

        gifBtn?.addEventListener('click', e => {
            e.stopPropagation();
            gifPanel?.classList.toggle('open');
            emojiPanel?.classList.remove('open');
            if (gifPanel?.classList.contains('open') && !gifGrid.innerHTML) fetchGifs('');
        });
        gifSearch?.addEventListener('input', () => {
            clearTimeout(gifTimer);
            gifTimer = setTimeout(() => fetchGifs(gifSearch.value), 400);
        });
        window.selectGif = function(url, label) {
            if (!gifUrlEl) return;
            gifUrlEl.value = url;
            document.getElementById('cmtGifImg').src = url;
            document.getElementById('cmtGifLabel').textContent = label;
            document.getElementById('cmtGifPreview').style.display = 'flex';
            gifPanel?.classList.remove('open');
            updateSubmitState();
        };
        window.removeCmtGif = function() {
            if (gifUrlEl) gifUrlEl.value = '';
            document.getElementById('cmtGifPreview').style.display = 'none';
            document.getElementById('cmtGifImg').src = '';
            updateSubmitState();
        };

        // Fermer panels au clic extérieur
        document.addEventListener('click', () => {
            emojiPanel?.classList.remove('open');
            gifPanel?.classList.remove('open');
        });
        emojiPanel?.addEventListener('click', e => e.stopPropagation());
        gifPanel?.addEventListener('click', e => e.stopPropagation());

        /* ── Init ── */
        loadComments(1);

        // Si l'URL contient #comments, scroll automatique après chargement
        if (window.location.hash === '#comments') {
            setTimeout(() => document.getElementById('comments')?.scrollIntoView({ behavior: 'smooth' }), 400);
        }
    })();

    /* ── Barre de progression lecture ── */
    (function () {
        const bar = document.getElementById('mg-read-bar');
        if (!bar) return;
        function updateBar() {
            const scrollTop  = window.scrollY;
            const docHeight  = document.documentElement.scrollHeight - window.innerHeight;
            const pct        = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            bar.style.width  = Math.min(pct, 100) + '%';
        }
        window.addEventListener('scroll', updateBar, { passive: true });
        updateBar();
    })();
</script>
@endpush
