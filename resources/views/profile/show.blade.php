@extends('layouts.app')

@section('title', $user->name)

@push('styles')
<style>
    .profile-page { padding-top: 64px; }

    /* ══ COVER ══ */
    .profile-cover {
        width: 100%;
        height: 300px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #2D1B0E 0%, #6B3520 50%, #C8522A 80%, #D4A843 100%);
    }
    .profile-cover img {
        width: 100%; height: 100%;
        object-fit: cover;
    }
    .profile-cover-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.5) 100%);
    }
    @auth
    .cover-edit-btn {
        position: absolute; top: 16px; right: 16px;
        background: rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        padding: 8px 16px;
        border-radius: 100px;
        font-size: .78rem;
        font-family: var(--font-body);
        font-weight: 500;
        cursor: none;
        backdrop-filter: blur(8px);
        transition: background .2s;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .cover-edit-btn:hover { background: rgba(0,0,0,0.6); color: white; }
    @endauth

    /* ══ PROFILE HEADER ══ */
    .profile-header {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 32px;
        position: relative;
    }

    .profile-avatar-wrap {
        position: relative;
        display: inline-block;
        margin-top: -56px;
        margin-bottom: 16px;
    }
    .profile-avatar {
        width: 112px; height: 112px;
        border-radius: 50%;
        border: 4px solid var(--bg);
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 3px;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.8rem;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: border-color .4s;
    }
    .profile-avatar img {
        width: 100%; height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .profile-avatar-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.4rem;
    }
    .profile-verified {
        position: absolute; bottom: 4px; right: 4px;
        width: 28px; height: 28px;
        border: 3px solid var(--bg);
        border-radius: 50%;
        transition: border-color .4s;
        display: flex; align-items: center; justify-content: center;
    }
    .profile-verified svg {
        width: 100%; height: 100%;
        filter: drop-shadow(0 1px 4px rgba(56,151,240,.4));
    }

    .profile-top-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
    }
    .profile-identity { flex: 1; min-width: 200px; }

    .profile-name {
        font-family: var(--font-head);
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--text);
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 2px;
    }
    .profile-name .badge-verified {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        flex-shrink: 0;
    }
    .profile-name .badge-verified svg {
        width: 100%;
        height: 100%;
        filter: drop-shadow(0 1px 3px rgba(56,151,240,.35));
    }
    .profile-username {
        font-size: .88rem;
        color: var(--text-muted);
        margin-bottom: 10px;
    }
    .profile-niche {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--terra-soft);
        border: 1px solid rgba(200,82,42,.2);
        color: var(--terra);
        font-size: .75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 100px;
        margin-bottom: 14px;
    }
    .profile-bio {
        font-size: .92rem;
        line-height: 1.65;
        color: var(--text-muted);
        max-width: 520px;
        margin-bottom: 14px;
        white-space: pre-line;
    }

    /* Infos (location, website) */
    .profile-meta {
        display: flex; flex-wrap: wrap; gap: 16px;
        margin-bottom: 16px;
    }
    .profile-meta-item {
        display: flex; align-items: center; gap: 6px;
        font-size: .82rem;
        color: var(--text-muted);
    }
    .profile-meta-item a {
        color: var(--gold);
        text-decoration: none;
        font-weight: 500;
    }
    .profile-meta-item a:hover { text-decoration: underline; }

    /* Réseaux sociaux */
    .profile-socials {
        display: flex; gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .social-link {
        display: flex; align-items: center; gap: 6px;
        padding: 6px 14px;
        border-radius: 100px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-muted);
        font-size: .78rem;
        font-weight: 500;
        text-decoration: none;
        transition: all .2s;
        cursor: none;
    }
    .social-link:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: var(--gold-soft);
    }

    /* Actions */
    .profile-actions {
        display: flex; gap: 10px; flex-wrap: wrap;
        padding-top: 4px;
    }
    .btn-follow {
        background: var(--terra);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 100px;
        font-family: var(--font-head);
        font-size: .88rem;
        font-weight: 700;
        cursor: none;
        transition: background .2s, transform .15s, box-shadow .2s;
    }
    .btn-follow:hover {
        background: var(--accent);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(200,82,42,.3);
    }
    .btn-follow.following {
        background: var(--bg-card);
        color: var(--text-muted);
        border: 1px solid var(--border);
    }
    .btn-follow.following:hover {
        border-color: #E05555;
        color: #E05555;
        background: rgba(224,85,85,.08);
        box-shadow: none;
    }
    .btn-message {
        background: var(--bg-card);
        color: var(--text);
        border: 1px solid var(--border);
        padding: 10px 24px;
        border-radius: 100px;
        font-family: var(--font-body);
        font-size: .88rem;
        font-weight: 500;
        cursor: none;
        transition: all .2s;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-message:hover {
        border-color: var(--border-hover);
        background: var(--bg-hover);
    }
    .btn-block {
        background: transparent;
        color: var(--text-muted);
        border: 1px solid var(--border);
        padding: 10px 16px;
        border-radius: 100px;
        font-family: var(--font-body);
        font-size: .82rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .2s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-block:hover, .btn-block.blocking {
        border-color: #E05555;
        color: #E05555;
        background: rgba(224,85,85,.08);
    }
    .btn-edit {
        background: var(--bg-card);
        color: var(--text-muted);
        border: 1px solid var(--border);
        padding: 10px 24px;
        border-radius: 100px;
        font-family: var(--font-body);
        font-size: .88rem;
        font-weight: 500;
        cursor: none;
        transition: all .2s;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-edit:hover { border-color: var(--border-hover); color: var(--text); background: var(--bg-hover); }

    /* ══ STATS BAR ══ */
    .profile-stats {
        display: flex;
        gap: 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        margin: 28px 0 0;
        transition: border-color .4s;
    }
    .stat-item {
        flex: 1;
        text-align: center;
        padding: 18px 12px;
        position: relative;
        cursor: none;
        transition: background .2s;
        border-radius: 4px;
    }
    .stat-item:hover { background: var(--bg-hover); }
    .stat-item + .stat-item::before {
        content: '';
        position: absolute; left: 0; top: 20%; bottom: 20%;
        width: 1px;
        background: var(--border);
    }
    .stat-value {
        font-family: var(--font-head);
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.02em;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: .72rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    /* ══ STORIES ROW ══ */
    .profile-stories {
        max-width: 900px;
        margin: 0 auto;
        padding: 28px 32px 20px;
    }
    .profile-stories-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 12px;
    }
    .profile-stories-list {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .profile-story-thumb {
        width: 64px;
        background: none;
        border: none;
        padding: 0;
        cursor: none;
        display: flex; flex-direction: column; align-items: center; gap: 5px;
    }
    .profile-story-ring {
        width: 64px; height: 64px;
        border-radius: 50%;
        padding: 2px;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        flex-shrink: 0;
        overflow: hidden;
    }
    .profile-story-ring-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
        border: 2px solid var(--bg);
        overflow: hidden;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .profile-story-ring-inner img {
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    .profile-story-video-badge {
        font-size: .55rem; font-weight: 700;
        color: var(--text-muted);
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    /* ══ CONTENT AREA ══ */
    .profile-body {
        max-width: 900px;
        margin: 0 auto;
        padding: 28px 32px 60px;
    }

    /* Tabs */
    .profile-tabs {
        display: flex;
        border-bottom: 1px solid var(--border);
        margin-bottom: 28px;
        gap: 0;
        transition: border-color .4s;
    }
    .profile-tab {
        padding: 12px 24px;
        font-size: .84rem;
        font-weight: 600;
        color: var(--text-muted);
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        cursor: none;
        transition: color .2s, border-color .2s;
        font-family: var(--font-body);
        letter-spacing: .02em;
        display: flex; align-items: center; gap: 7px;
    }
    .profile-tab:hover { color: var(--text); }
    .profile-tab.active {
        color: var(--terra);
        border-bottom-color: var(--terra);
    }
    .profile-tab .tab-count {
        background: var(--bg-card2);
        color: var(--text-muted);
        font-size: .65rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 100px;
        transition: background .4s;
    }
    .profile-tab.active .tab-count {
        background: var(--terra-soft);
        color: var(--terra);
    }

    /* ══ GRID POSTS (Instagram style) ══ */
    .posts-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3px;
    }
    .grid-post {
        aspect-ratio: 1;
        position: relative;
        overflow: hidden;
        cursor: none;
        background: var(--bg-card2);
    }
    .grid-post-media {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem;
        transition: transform .4s ease;
    }
    .grid-post-media img, .grid-post-media video {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }
    .grid-post:hover .grid-post-media,
    .grid-post:hover .grid-post-media img { transform: scale(1.06); }

    .grid-post-overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,0);
        display: flex; align-items: center; justify-content: center;
        gap: 24px;
        opacity: 0;
        transition: background .3s, opacity .3s;
    }
    .grid-post:hover .grid-post-overlay {
        background: rgba(0,0,0,0.55);
        opacity: 1;
    }
    .grid-post-stat {
        display: flex; align-items: center; gap: 7px;
        color: white;
        font-family: var(--font-head);
        font-size: .9rem;
        font-weight: 700;
    }
    .grid-post-stat svg { width: 18px; height: 18px; }

    /* Badge vidéo / multi */
    .grid-post-badge {
        position: absolute; top: 8px; right: 8px;
        background: rgba(0,0,0,0.5);
        color: white;
        font-size: .65rem;
        padding: 3px 7px;
        border-radius: 6px;
        backdrop-filter: blur(4px);
    }

    /* Badge cadenas exclusif */
    .grid-post-lock {
        position: absolute; top: 8px; left: 8px;
        background: rgba(212,168,67,.85);
        color: #1C1208;
        font-size: .65rem;
        font-weight: 700;
        padding: 3px 7px;
        border-radius: 6px;
        backdrop-filter: blur(4px);
        letter-spacing: .02em;
        z-index: 3;
    }
    /* Overlay lock sur la grille (posts verrouillés) */
    .grid-post-locked-overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,.52);
        display: flex; align-items: center; justify-content: center;
        transition: background .3s;
    }
    .grid-post-locked-overlay:hover { background: rgba(0,0,0,.68); }
    .grid-lock-icon {
        font-size: 1.6rem;
        filter: drop-shadow(0 2px 6px rgba(0,0,0,.5));
        transition: transform .2s;
    }
    .grid-post-locked-overlay:hover .grid-lock-icon { transform: scale(1.15); }

    /* Grille uniforme — pas de featured post */

    /* Placeholder vide */
    .empty-posts {
        grid-column: span 3;
        padding: 60px 20px;
        text-align: center;
        color: var(--text-muted);
    }
    .empty-posts-icon { font-size: 3rem; margin-bottom: 14px; }
    .empty-posts-title { font-family: var(--font-head); font-size: 1.1rem; font-weight: 700; margin-bottom: 8px; color: var(--text); }
    .empty-posts-desc { font-size: .86rem; line-height: 1.6; }

    /* ══ POST MODAL ══ */
    .pm-overlay {
        position: fixed; inset: 0; z-index: 600;
        background: rgba(0,0,0,.75);
        backdrop-filter: blur(6px);
        display: flex; align-items: center; justify-content: center;
        padding: 16px;
        opacity: 0; visibility: hidden;
        transition: opacity .2s, visibility .2s;
    }
    .pm-overlay.open { opacity: 1; visibility: visible; }
    .pm-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 22px;
        width: 100%; max-width: 940px;
        max-height: 92vh;
        display: flex;
        overflow: hidden;
        box-shadow: 0 24px 64px rgba(0,0,0,.5);
        transform: scale(.95);
        transition: transform .2s;
    }
    .pm-overlay.open .pm-box { transform: scale(1); }

    /* Côté média */
    .pm-left {
        flex: 0 0 55%;
        background: #000;
        display: flex; align-items: center; justify-content: center;
        position: relative;
        min-height: 400px;
        overflow: hidden;
    }
    .pm-left img, .pm-left video {
        width: 100%; height: 100%;
        object-fit: contain;
        display: block;
    }
    .pm-text-cover {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        padding: 32px;
        font-family: var(--font-head);
        font-size: 1rem; font-weight: 700;
        color: rgba(255,255,255,.9);
        text-align: center;
        line-height: 1.6;
    }

    /* Carousel dans modal */
    .pm-carousel { width: 100%; height: 100%; position: relative; overflow: hidden; }
    .pm-car-track { display: flex; height: 100%; transition: transform .3s ease; }
    .pm-car-slide { flex: 0 0 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #000; }
    .pm-car-slide img { width: 100%; height: 100%; object-fit: contain; }
    .pm-car-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 34px; height: 34px; border-radius: 50%;
        background: rgba(0,0,0,.5); border: none; color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; z-index: 5;
    }
    .pm-car-btn.prev { left: 10px; }
    .pm-car-btn.next { right: 10px; }
    .pm-car-counter {
        position: absolute; bottom: 10px; right: 12px;
        background: rgba(0,0,0,.5); color: white;
        font-size: .7rem; font-weight: 700;
        padding: 3px 8px; border-radius: 20px;
    }

    /* Côté contenu */
    .pm-right {
        flex: 1;
        display: flex; flex-direction: column;
        min-width: 0;
    }
    .pm-head {
        display: flex; align-items: center; gap: 12px;
        padding: 18px 20px 14px;
        border-bottom: 1px solid var(--border);
        flex-shrink: 0;
    }
    .pm-avi {
        width: 40px; height: 40px; border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px; flex-shrink: 0; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
    }
    .pm-avi-inner {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; font-weight: 700; overflow: hidden;
    }
    .pm-avi-inner img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .pm-author-info { flex: 1; min-width: 0; }
    .pm-author-name {
        font-family: var(--font-head); font-size: .88rem; font-weight: 700;
        color: var(--text); text-decoration: none; display: block;
    }
    .pm-author-name:hover { color: var(--terra); }
    .pm-author-meta { font-size: .72rem; color: var(--text-muted); margin-top: 1px; }
    .pm-close {
        background: var(--bg-card2); border: 1px solid var(--border);
        color: var(--text-muted); width: 32px; height: 32px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0;
    }
    .pm-close:hover { border-color: var(--border-hover); color: var(--text); }

    .pm-content {
        flex: 1; overflow-y: auto; padding: 18px 20px;
    }
    .pm-title {
        font-family: var(--font-head); font-size: 1rem; font-weight: 800;
        color: var(--text); margin-bottom: 10px; line-height: 1.3;
    }
    .pm-body {
        font-size: .9rem; line-height: 1.7;
        color: var(--text-muted); white-space: pre-wrap;
    }

    .pm-audio {
        display: flex; align-items: center; gap: 10px;
        margin-top: 14px; padding: 10px 12px;
        background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 10px;
    }
    .pm-audio-name { font-size: .8rem; font-weight: 600; flex: 1; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pm-audio-btn {
        width: 30px; height: 30px; border-radius: 50%;
        background: var(--terra); border: none; color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem; flex-shrink: 0;
    }

    .pm-actions {
        display: flex; align-items: center; gap: 4px;
        padding: 12px 20px;
        border-top: 1px solid var(--border);
        flex-shrink: 0;
    }
    .pm-action-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 7px 14px; border-radius: 100px;
        background: transparent; border: 1px solid transparent;
        color: var(--text-muted); font-family: var(--font-body);
        font-size: .82rem; font-weight: 500;
        transition: all .2s;
    }
    .pm-action-btn:hover { background: var(--bg-card2); border-color: var(--border); color: var(--text); }
    .pm-action-btn.liked { color: #E05555; }
    .pm-action-btn.liked:hover { background: rgba(224,85,85,.08); border-color: rgba(224,85,85,.3); }
    .pm-action-btn svg { width: 15px; height: 15px; flex-shrink: 0; }
    .pm-action-sep { flex: 1; }
    .pm-full-link {
        font-size: .78rem; color: var(--terra); text-decoration: none; font-weight: 600;
    }
    .pm-full-link:hover { color: var(--accent); }

    /* Skeleton loader */
    .pm-skeleton {
        flex: 1; display: flex; flex-direction: column; gap: 12px; padding: 20px;
    }
    .pm-skel-line {
        height: 14px; border-radius: 6px;
        background: linear-gradient(90deg, var(--bg-card2) 25%, var(--bg-hover) 50%, var(--bg-card2) 75%);
        background-size: 200% 100%;
        animation: skel-shimmer 1.2s infinite;
    }
    @keyframes skel-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    /* Responsive */
    @media (max-width: 680px) {
        .pm-box { flex-direction: column; max-height: 96vh; }
        .pm-left { flex: 0 0 280px; min-height: 280px; }
    }

    /* ══ PROFIL PRIVÉ ══ */
    .private-lock {
        padding: 60px 20px;
        text-align: center;
        border-top: 1px solid var(--border);
        margin-top: 24px;
    }
    .private-lock-icon {
        font-size: 2.8rem;
        margin-bottom: 16px;
        display: flex; align-items: center; justify-content: center;
        width: 72px; height: 72px;
        background: var(--bg-card2);
        border: 1.5px solid var(--border);
        border-radius: 50%;
        margin: 0 auto 20px;
    }
    .private-lock-title {
        font-family: var(--font-head);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 10px;
    }
    .private-lock-desc {
        font-size: .88rem;
        color: var(--text-muted);
        margin-bottom: 24px;
        line-height: 1.6;
    }
    .private-lock-divider {
        display: flex; align-items: center; gap: 16px;
        max-width: 280px; margin: 0 auto 24px;
        color: var(--text-faint); font-size: .75rem;
    }
    .private-lock-divider::before,
    .private-lock-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    /* ══ ABOUT TAB ══ */
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .about-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        transition: background .4s, border-color .4s;
    }
    .about-card-title {
        font-family: var(--font-head);
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 16px;
    }
    .about-item {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        font-size: .88rem;
        color: var(--text);
        transition: border-color .4s;
    }
    .about-item:last-child { border-bottom: none; }
    .about-item-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
    .about-item-value { color: var(--text-muted); }
    .about-item-value a { color: var(--gold); text-decoration: none; }
    .about-item-value a:hover { text-decoration: underline; }

    /* ══ CREATOR BADGE ══ */
    .badge-creator {
        background: linear-gradient(135deg, var(--gold), #E8A020);
        color: #1A0A00;
        font-size: .6rem;
        padding: 3px 10px;
        border-radius: 100px;
        font-weight: 800;
        letter-spacing: .05em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    /* ══ CREATOR PANEL (own profile) ══ */
    .creator-panel {
        background: linear-gradient(135deg, rgba(212,168,67,.07), rgba(200,82,42,.04));
        border: 1px solid rgba(212,168,67,.22);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }
    .creator-panel-title {
        font-family: var(--font-head);
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 14px;
        display: flex; align-items: center; gap: 7px;
    }
    .creator-quick-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 16px;
    }
    .creator-quick-stat {
        text-align: center;
        background: var(--bg-card2);
        border-radius: 10px;
        padding: 12px 8px;
        transition: background .4s;
    }
    .creator-quick-stat-val {
        font-family: var(--font-head);
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--gold);
        letter-spacing: -.01em;
        line-height: 1;
        margin-bottom: 4px;
    }
    .creator-quick-stat-lbl {
        font-size: .66rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .creator-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-creator-action {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px;
        border-radius: 100px;
        font-size: .82rem;
        font-weight: 600;
        font-family: var(--font-body);
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .2s;
    }
    .btn-creator-action.primary { background: var(--terra); color: white; }
    .btn-creator-action.primary:hover { background: var(--accent); }
    .btn-creator-action.secondary {
        background: var(--bg-card2);
        color: var(--text-muted);
        border: 1px solid var(--border);
    }
    .btn-creator-action.secondary:hover { color: var(--text); border-color: var(--border-hover); }

    /* ══ BECOME CREATOR CARD ══ */
    .become-creator-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex; align-items: center; gap: 20px;
        transition: background .4s, border-color .4s;
    }
    .become-creator-icon { font-size: 2.2rem; flex-shrink: 0; }
    .become-creator-text { flex: 1; }
    .become-creator-title {
        font-family: var(--font-head);
        font-size: .95rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--text);
    }
    .become-creator-desc {
        font-size: .82rem;
        color: var(--text-muted);
        line-height: 1.55;
    }

    /* ══ CREATOR INFO TAB ══ */
    .creator-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .creator-info-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 22px;
        transition: background .4s, border-color .4s;
    }
    .creator-info-card-title {
        font-family: var(--font-head);
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 14px;
    }
    .creator-stat-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 9px 0;
        border-bottom: 1px solid var(--border);
        font-size: .86rem;
        transition: border-color .4s;
    }
    .creator-stat-row:last-child { border-bottom: none; }
    .creator-stat-row-label { color: var(--text-muted); display: flex; align-items: center; gap: 8px; }
    .creator-stat-row-value { font-weight: 700; color: var(--text); }

    @@media (max-width: 768px) {
        .profile-cover { height: 200px; }
        .profile-header { padding: 0 16px; }
        .profile-body { padding: 20px 16px 40px; }
        .posts-grid { grid-template-columns: repeat(3,1fr); gap: 2px; }
        .posts-grid .grid-post:first-child { grid-column: span 1; grid-row: span 1; }
        .profile-top-row { flex-direction: column; }
        .about-grid { grid-template-columns: 1fr; }
        .profile-stats { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')
<div class="profile-page">

    <!-- ══ COVER ══ -->
    <div class="profile-cover">
        @if($user->cover_photo)
            <img src="{{ Storage::url($user->cover_photo) }}" alt="Cover">
        @endif
        <div class="profile-cover-overlay"></div>
        @auth
            @if(auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}" class="cover-edit-btn">
                    ✎ Modifier la couverture
                </a>
            @endif
        @endauth
    </div>

    <!-- ══ HEADER ══ -->
    <div class="profile-header">

        <!-- Avatar -->
        <div class="profile-avatar-wrap">
            <div class="profile-avatar">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                @else
                    <div class="profile-avatar-inner">{{ mb_strtoupper(mb_substr($user->name,0,1)) }}</div>
                @endif
            </div>
            @if($user->is_verified)
                <div class="profile-verified" title="Compte vérifié">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="12" fill="#3897F0"/>
                        <path d="M7 12.3L10.4 15.8L17 8.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
            @endif
        </div>

        <div class="profile-top-row">
            <!-- Identité -->
            <div class="profile-identity">
                <div class="profile-name">
                    {{ $user->name }}
                    @if($user->is_verified)
                        <span class="badge-verified" title="Compte vérifié">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="12" fill="#3897F0"/>
                                <path d="M7 12.3L10.4 15.8L17 8.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            </svg>
                        </span>
                    @endif
                    @if($user->isCreator())
                        <span class="badge-creator">✦ Créateur</span>
                    @endif
                </div>
                <div class="profile-username">&#64;{{ $user->username }}</div>

                @if($user->niche)
                    <div class="profile-niche">🎨 {{ $user->niche }}</div>
                @endif

                @if($user->isCreator())
                    <div id="availabilityBadge" style="display:inline-flex;align-items:center;gap:5px;font-size:.75rem;font-weight:600;padding:3px 10px;border-radius:100px;margin-bottom:12px;
                        {{ $user->is_available ? 'background:rgba(34,197,94,.1);color:#16a34a;border:1px solid rgba(34,197,94,.25);' : 'background:rgba(224,85,85,.08);color:#E05555;border:1px solid rgba(224,85,85,.2);' }}">
                        {{ $user->is_available ? '🟢 Disponible aux commandes' : '🔴 Non disponible' }}
                    </div>
                @endif

                @if($user->bio || $user->bio_wolof)
                    <div class="profile-bio-block">
                        @if($user->bio)
                            <p class="profile-bio">{{ $user->bio }}</p>
                        @endif
                        @if($user->bio_wolof)
                            <p class="profile-bio profile-bio-wolof" style="margin-top:4px;font-style:italic;color:var(--text-muted);">{{ $user->bio_wolof }}</p>
                        @endif
                    </div>
                @endif

                <!-- Meta infos -->
                <div class="profile-meta">
                    @if($user->location)
                        <div class="profile-meta-item">
                            📍 <span>{{ $user->location }}</span>
                        </div>
                    @endif
                    @if($user->website)
                        <div class="profile-meta-item">
                            🔗 <a href="{{ $user->website }}" target="_blank" rel="noopener">
                                {{ parse_url($user->website, PHP_URL_HOST) ?? $user->website }}
                            </a>
                        </div>
                    @endif
                    <div class="profile-meta-item">
                        📅 <span>Membre depuis {{ $user->created_at->translatedFormat('F Y') }}</span>
                    </div>
                </div>

                <!-- Réseaux sociaux -->
                @if($user->instagram || $user->tiktok || $user->youtube || $user->twitter)
                <div class="profile-socials">
                    @if($user->instagram)
                        <a href="https://instagram.com/{{ $user->instagram }}" target="_blank" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            Instagram
                        </a>
                    @endif
                    @if($user->tiktok)
                        <a href="https://tiktok.com/@{{ $user->tiktok }}" target="_blank" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z"/></svg>
                            TikTok
                        </a>
                    @endif
                    @if($user->youtube)
                        <a href="https://youtube.com/@{{ $user->youtube }}" target="_blank" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
                            YouTube
                        </a>
                    @endif
                    @if($user->twitter)
                        <a href="https://twitter.com/{{ $user->twitter }}" target="_blank" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            X / Twitter
                        </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Boutons action -->
            <div class="profile-actions">
                @auth
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}" class="btn-edit">✎ Modifier le profil</a>
                        @if($user->isCreator())
                        <button
                            id="availabilityBtn"
                            onclick="toggleAvailability(this)"
                            class="btn-message"
                            style="{{ $user->is_available ? 'color:var(--text);' : 'color:#E05555;border-color:#E05555;' }}">
                            {{ $user->is_available ? '🟢 Disponible' : '🔴 Indisponible' }}
                        </button>
                        @endif
                    @else
                        <button
                            class="btn-follow {{ auth()->user()->isFollowing($user) ? 'following' : '' }}"
                            id="followBtn"
                            onclick="toggleFollow('{{ $user->username }}', this)">
                            {{ auth()->user()->isFollowing($user) ? 'Abonné ✓' : '+ Suivre' }}
                        </button>
                        <a href="{{ route('messages.show', $user->username) }}" class="btn-message">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            Message
                        </a>
                        <button
                            class="btn-block {{ auth()->user()->isBlocking($user) ? 'blocking' : '' }}"
                            id="blockBtn"
                            onclick="toggleBlock('{{ $user->username }}', this)">
                            {{ auth()->user()->isBlocking($user) ? '🚫 Bloqué' : '⊘ Bloquer' }}
                        </button>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-follow">+ Suivre</a>
                @endauth
            </div>
        </div>

        <!-- Stats -->
        <div class="profile-stats">
            <div class="stat-item">
                <div class="stat-value">{{ number_format($user->posts()->count()) }}</div>
                <div class="stat-label">Publications</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="followersCount">{{ number_format($user->followers()->count()) }}</div>
                <div class="stat-label">Abonnés</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($user->following()->count()) }}</div>
                <div class="stat-label">Abonnements</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($totalLikes) }}</div>
                <div class="stat-label">Likes reçus</div>
            </div>
        </div>
    </div>

    {{-- ══ STORIES ══ --}}
    @if($stories->isNotEmpty())
    <div class="profile-stories">
        <div class="profile-stories-label">Stories actives</div>
        <div class="profile-stories-list">
            @foreach($stories as $i => $story)
            <button class="profile-story-thumb" type="button"
                onclick="openProfileStory({{ $i }})" title="Story {{ $i + 1 }}">
                <div class="profile-story-ring">
                    <div class="profile-story-ring-inner">
                        @if($story->media_type === 'image')
                            <img src="{{ Storage::url($story->media_url) }}" alt="Story">
                        @else
                            ▶
                        @endif
                    </div>
                </div>
                <div class="profile-story-video-badge">
                    {{ $story->media_type === 'video' ? 'vidéo' : $story->created_at->diffForHumans(null, true) }}
                </div>
            </button>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ══ BODY ══ -->
    <div class="profile-body">

        {{-- ══ CREATOR PANEL (visible uniquement sur son propre profil créateur) ══ --}}
        @if($user->isCreator() && auth()->id() === $user->id)
        <div class="creator-panel">
            <div class="creator-panel-title">✦ Espace Créateur</div>
            <div class="creator-quick-stats">
                <div class="creator-quick-stat">
                    <div class="creator-quick-stat-val">{{ number_format($user->posts()->count()) }}</div>
                    <div class="creator-quick-stat-lbl">Publications</div>
                </div>
                <div class="creator-quick-stat">
                    <div class="creator-quick-stat-val">{{ number_format($user->followers()->count()) }}</div>
                    <div class="creator-quick-stat-lbl">Abonnés</div>
                </div>
                <div class="creator-quick-stat">
                    <div class="creator-quick-stat-val">{{ number_format($totalLikes) }}</div>
                    <div class="creator-quick-stat-lbl">Likes reçus</div>
                </div>
                <div class="creator-quick-stat">
                    <div class="creator-quick-stat-val">{{ number_format($user->comments()->count()) }}</div>
                    <div class="creator-quick-stat-lbl">Commentaires</div>
                </div>
            </div>
            <div class="creator-actions">
                <a href="{{ route('posts.create') }}" class="btn-creator-action primary">
                    ✎ Nouvelle publication
                </a>
                <a href="{{ route('profile.edit') }}" class="btn-creator-action secondary">
                    ⚙ Modifier le profil
                </a>
            </div>
        </div>
        @endif

        {{-- ══ DEVENIR CRÉATEUR (user simple sur son propre profil) ══ --}}
        @if(!$user->isCreator() && !$user->isStaff() && auth()->id() === $user->id)
        <div class="become-creator-card">
            <div class="become-creator-icon">🎨</div>
            <div class="become-creator-text">
                @if($myCreatorRequest && $myCreatorRequest->isPending())
                    <div class="become-creator-title">Demande en attente</div>
                    <div class="become-creator-desc">
                        Ta demande pour devenir créateur est en cours d'examen. Tu seras notifié dès qu'elle sera traitée.
                    </div>
                @elseif($myCreatorRequest && $myCreatorRequest->status === 'rejected')
                    <div class="become-creator-title">Demande refusée</div>
                    <div class="become-creator-desc">
                        Ta demande a été refusée. Tu peux soumettre une nouvelle demande avec plus de détails sur ta démarche créative.
                    </div>
                @else
                    <div class="become-creator-title">Deviens Créateur</div>
                    <div class="become-creator-desc">
                        Partage ton contenu exclusif et rejoins la communauté des créateurs MelanoGeek.
                    </div>
                @endif
            </div>
            <div>
                @if($myCreatorRequest && $myCreatorRequest->isPending())
                    <span style="font-size:.78rem;color:var(--gold);font-weight:600;padding:9px 18px;background:rgba(212,168,67,.1);border:1px solid rgba(212,168,67,.25);border-radius:100px;white-space:nowrap;">
                        ⏳ En attente
                    </span>
                @else
                    <button onclick="document.getElementById('creator-request-modal').style.display='flex'" class="btn-creator-action primary" style="white-space:nowrap;">
                        {{ $myCreatorRequest ? '↻ Soumettre à nouveau' : '+ Postuler' }}
                    </button>
                @endif
            </div>
        </div>
        @endif

        <!-- Tabs -->
        <div class="profile-tabs">
            <button class="profile-tab active" data-tab="posts">
                @if($isLocked)
                    🔒 Publications
                @else
                    ⊞ Publications
                @endif
                <span class="tab-count">{{ $isLocked ? '—' : $user->posts()->where('is_published', true)->count() }}</span>
            </button>
            <button class="profile-tab" data-tab="about">
                👤 À propos
            </button>
            @if($user->isCreator())
            <button class="profile-tab" data-tab="creator">
                ✦ Créateur
            </button>
            @endif
        </div>

        <!-- Tab: Posts grid -->
        <div id="tab-posts">
            @if($isLocked)
            {{-- ── Profil privé : verrou Instagram ── --}}
            <div class="private-lock">
                <div class="private-lock-icon">🔒</div>
                <div class="private-lock-title">Ce compte est privé</div>
                <div class="private-lock-desc">Suivez ce compte pour voir ses photos et publications.</div>
                <div class="private-lock-divider">ou</div>
                @auth
                    @if(auth()->id() !== $user->id)
                        <button class="btn-follow {{ auth()->user()->isFollowing($user) ? 'following' : '' }}"
                            id="followBtnLock"
                            onclick="toggleFollow('{{ $user->username }}', this)">
                            {{ auth()->user()->isFollowing($user) ? 'Abonné ✓' : '+ Suivre' }}
                        </button>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-follow">+ Suivre</a>
                @endauth
            </div>
            @else
            @php
                $viewerCanSeeExclusive = auth()->check() && (
                    auth()->id() === $user->id
                    || auth()->user()->hasActiveSubscription()
                );
            @endphp
            <div class="posts-grid">
                @forelse($posts as $post)
                    @php $postLocked = $post->is_exclusive && !$viewerCanSeeExclusive; @endphp
                    <div class="grid-post"
                         onclick="{{ $postLocked ? 'window.location=\''.route('subscription.pricing').'\'' : 'openPostModal('.$post->id.')' }}"
                         style="cursor:pointer;">
                        <div class="grid-post-media">
                            @if($post->mediaFiles->isNotEmpty())
                                <img src="{{ Storage::url($post->mediaFiles->first()->media_url) }}"
                                     alt="" style="{{ $postLocked ? 'filter:blur(6px);transform:scale(1.08);' : '' }}">
                                @if(!$postLocked && $post->mediaFiles->count() > 1)
                                    <div class="grid-post-badge">⧉</div>
                                @endif
                            @elseif($post->media_url && $post->media_type === 'image')
                                <img src="{{ Storage::url($post->media_url) }}" alt="{{ $post->title }}"
                                     style="{{ $postLocked ? 'filter:blur(6px);transform:scale(1.08);' : '' }}">
                            @elseif($post->media_url && $post->media_type === 'video')
                                <video src="{{ $post->media_url }}" style="width:100%;height:100%;object-fit:cover;{{ $postLocked ? 'filter:blur(6px);transform:scale(1.08);' : '' }}"></video>
                                @if(!$postLocked)<div class="grid-post-badge">▶</div>@endif
                            @else
                                {{-- Post texte --}}
                                <div style="width:100%;height:100%;background:var(--bg-card2);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:10px;gap:6px;{{ $postLocked ? 'filter:blur(4px);' : '' }}">
                                    <span style="font-size:1.2rem;opacity:.4;">📝</span>
                                    @if(!$postLocked)
                                    <div style="font-size:.68rem;color:var(--text-muted);text-align:center;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;">
                                        {{ Str::limit($post->title ?: $post->body, 60) }}
                                    </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if($postLocked)
                            {{-- Overlay cadenas --}}
                            <div class="grid-post-lock">🔒 Exclusif</div>
                            <div class="grid-post-locked-overlay">
                                <div class="grid-lock-icon">🔒</div>
                            </div>
                        @else
                            {{-- Overlay stats normal --}}
                            <div class="grid-post-overlay">
                                <div class="grid-post-stat">
                                    <svg viewBox="0 0 24 24" fill="white" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    {{ number_format($post->likes_count) }}
                                </div>
                                <div class="grid-post-stat">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    {{ number_format($post->comments_count) }}
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="empty-posts">
                        <div class="empty-posts-icon">📭</div>
                        <div class="empty-posts-title">Aucune publication</div>
                        <div class="empty-posts-desc">
                            @if(auth()->id() === $user->id)
                                Tu n'as pas encore publié de contenu. <a href="{{ route('posts.create') }}" style="color:var(--terra);text-decoration:none;font-weight:600;">Créer ta première publication →</a>
                            @else
                                {{ $user->name }} n'a pas encore publié de contenu.
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
            @endif
        </div>

        <!-- Tab: About -->
        <div id="tab-about" style="display:none;">
            <div class="about-grid">
                <div class="about-card">
                    <div class="about-card-title">Informations</div>
                    @if($user->niche)
                    <div class="about-item">
                        <span class="about-item-icon">🎨</span>
                        <span>Niche</span>
                        <span class="about-item-value" style="margin-left:auto;">{{ $user->niche }}</span>
                    </div>
                    @endif
                    @if($user->location)
                    <div class="about-item">
                        <span class="about-item-icon">📍</span>
                        <span>Localisation</span>
                        <span class="about-item-value" style="margin-left:auto;">{{ $user->location }}</span>
                    </div>
                    @endif
                    <div class="about-item">
                        <span class="about-item-icon">📅</span>
                        <span>Membre depuis</span>
                        <span class="about-item-value" style="margin-left:auto;">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="about-item">
                        <span class="about-item-icon">🌍</span>
                        <span>Origine</span>
                        <span class="about-item-value" style="margin-left:auto;">
                            @if($user->country_type === 'senegal')
                                <span style="display:inline-flex;align-items:center;gap:5px;">
                                    <svg width="16" height="11" viewBox="0 0 22 15" xmlns="http://www.w3.org/2000/svg" style="border-radius:2px;flex-shrink:0;vertical-align:middle;">
                                        <rect width="8" height="15" fill="#00A859"/>
                                        <rect x="7" width="8" height="15" fill="#FDEF42"/>
                                        <rect x="14" width="8" height="15" fill="#E31B23"/>
                                        <polygon points="11,3 11.7,5.2 14,5.2 12.2,6.5 12.9,8.7 11,7.4 9.1,8.7 9.8,6.5 8,5.2 10.3,5.2" fill="#00A859"/>
                                    </svg> Sénégal
                                </span>
                            @elseif($user->country_type === 'africa')
                                🌍 Afrique
                            @else
                                ✈️ Diaspora
                            @endif
                        </span>
                    </div>
                    @if($user->website)
                    <div class="about-item">
                        <span class="about-item-icon">🔗</span>
                        <span>Site web</span>
                        <span class="about-item-value" style="margin-left:auto;">
                            <a href="{{ $user->website }}" target="_blank">{{ parse_url($user->website, PHP_URL_HOST) }}</a>
                        </span>
                    </div>
                    @endif
                </div>

                <div class="about-card">
                    <div class="about-card-title">Réseaux sociaux</div>
                    @forelse([
                        ['icon'=>'📸','label'=>'Instagram','val'=>$user->instagram,'url'=>'https://instagram.com/'],
                        ['icon'=>'🎵','label'=>'TikTok','val'=>$user->tiktok,'url'=>'https://tiktok.com/@'],
                        ['icon'=>'▶️','label'=>'YouTube','val'=>$user->youtube,'url'=>'https://youtube.com/@'],
                        ['icon'=>'✖','label'=>'X / Twitter','val'=>$user->twitter,'url'=>'https://twitter.com/'],
                    ] as $social)
                        @if($social['val'])
                        <div class="about-item">
                            <span class="about-item-icon">{{ $social['icon'] }}</span>
                            <span>{{ $social['label'] }}</span>
                            <span class="about-item-value" style="margin-left:auto;">
                                <a href="{{ $social['url'].$social['val'] }}" target="_blank">{{ '@'.$social['val'] }}</a>
                            </span>
                        </div>
                        @endif
                    @empty
                        <div style="padding:20px 0;text-align:center;color:var(--text-muted);font-size:.86rem;">Aucun réseau social renseigné.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Tab: Créateur (uniquement sur les profils créateurs) --}}
        @if($user->isCreator())
        <div id="tab-creator" style="display:none;">
            <div class="creator-info-grid">

                {{-- Stats détaillées --}}
                <div class="creator-info-card">
                    <div class="creator-info-card-title">📊 Statistiques</div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">📝 Publications</span>
                        <span class="creator-stat-row-value">{{ number_format($user->posts()->count()) }}</span>
                    </div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">👥 Abonnés</span>
                        <span class="creator-stat-row-value">{{ number_format($user->followers()->count()) }}</span>
                    </div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">❤️ Likes reçus</span>
                        <span class="creator-stat-row-value">{{ number_format($totalLikes) }}</span>
                    </div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">💬 Commentaires</span>
                        <span class="creator-stat-row-value">{{ number_format($user->comments()->count()) }}</span>
                    </div>
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">📅 Créateur depuis</span>
                        <span class="creator-stat-row-value">{{ $user->created_at->translatedFormat('F Y') }}</span>
                    </div>
                </div>

                {{-- Infos créateur --}}
                <div class="creator-info-card">
                    <div class="creator-info-card-title">🎨 Profil créateur</div>

                    @if($user->niche)
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">🎯 Niche</span>
                        <span class="creator-stat-row-value">{{ $user->niche }}</span>
                    </div>
                    @endif

                    @if($user->location)
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">📍 Localisation</span>
                        <span class="creator-stat-row-value">{{ $user->location }}</span>
                    </div>
                    @endif

                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">🌍 Origine</span>
                        <span class="creator-stat-row-value">
                            @if($user->country_type === 'senegal')
                                <span style="display:inline-flex;align-items:center;gap:5px;">
                                    <svg width="16" height="11" viewBox="0 0 22 15" xmlns="http://www.w3.org/2000/svg" style="border-radius:2px;flex-shrink:0;vertical-align:middle;">
                                        <rect width="8" height="15" fill="#00A859"/>
                                        <rect x="7" width="8" height="15" fill="#FDEF42"/>
                                        <rect x="14" width="8" height="15" fill="#E31B23"/>
                                        <polygon points="11,3 11.7,5.2 14,5.2 12.2,6.5 12.9,8.7 11,7.4 9.1,8.7 9.8,6.5 8,5.2 10.3,5.2" fill="#00A859"/>
                                    </svg> Sénégal
                                </span>
                            @elseif($user->country_type === 'africa')
                                🌍 Afrique
                            @else
                                ✈️ Diaspora
                            @endif
                        </span>
                    </div>

                    @if($user->website)
                    <div class="creator-stat-row">
                        <span class="creator-stat-row-label">🔗 Site web</span>
                        <span class="creator-stat-row-value">
                            <a href="{{ $user->website }}" target="_blank" rel="noopener" style="color:var(--gold);text-decoration:none;">
                                {{ parse_url($user->website, PHP_URL_HOST) ?? $user->website }}
                            </a>
                        </span>
                    </div>
                    @endif

                    @if($user->instagram || $user->tiktok || $user->youtube || $user->twitter)
                    <div style="margin-top:12px;">
                        <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">Réseaux sociaux</div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            @if($user->instagram)
                                <a href="https://instagram.com/{{ $user->instagram }}" target="_blank" class="social-link" style="font-size:.75rem;">📸 Instagram</a>
                            @endif
                            @if($user->tiktok)
                                <a href="https://tiktok.com/@{{ $user->tiktok }}" target="_blank" class="social-link" style="font-size:.75rem;">🎵 TikTok</a>
                            @endif
                            @if($user->youtube)
                                <a href="https://youtube.com/@{{ $user->youtube }}" target="_blank" class="social-link" style="font-size:.75rem;">▶️ YouTube</a>
                            @endif
                            @if($user->twitter)
                                <a href="https://twitter.com/{{ $user->twitter }}" target="_blank" class="social-link" style="font-size:.75rem;">✖ Twitter</a>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(!$user->niche && !$user->website && !$user->instagram && !$user->tiktok && !$user->youtube && !$user->twitter)
                    <div style="padding:20px 0;text-align:center;color:var(--text-muted);font-size:.84rem;">
                        Aucune info créateur renseignée.
                        @if(auth()->id() === $user->id)
                            <br><a href="{{ route('profile.edit') }}" style="color:var(--terra);text-decoration:none;font-weight:600;">Compléter mon profil →</a>
                        @endif
                    </div>
                    @endif
                </div>

            </div>

            {{-- Si l'utilisateur n'est pas abonné, CTA pour rejoindre la plateforme --}}
            @auth
                @if(!auth()->user()->hasPremiumAccess() && auth()->id() !== $user->id)
                <div style="background:linear-gradient(135deg,rgba(200,82,42,.08),rgba(212,168,67,.06));border:1px solid rgba(200,82,42,.2);border-radius:16px;padding:24px;margin-top:20px;text-align:center;">
                    <div style="font-size:2rem;margin-bottom:10px;">🔒</div>
                    <div style="font-family:var(--font-head);font-size:1rem;font-weight:700;margin-bottom:8px;">Contenu exclusif</div>
                    <div style="font-size:.86rem;color:var(--text-muted);margin-bottom:16px;line-height:1.6;">
                        Abonne-toi à MelanoGeek pour accéder au contenu premium de {{ $user->name }} et de tous les créateurs.
                    </div>
                    <a href="{{ route('feed') }}" style="display:inline-flex;align-items:center;gap:8px;background:var(--terra);color:white;padding:11px 24px;border-radius:100px;font-family:var(--font-head);font-size:.88rem;font-weight:700;text-decoration:none;transition:background .2s;">
                        ⭐ Voir les offres
                    </a>
                </div>
                @endif
            @else
                <div style="background:linear-gradient(135deg,rgba(200,82,42,.08),rgba(212,168,67,.06));border:1px solid rgba(200,82,42,.2);border-radius:16px;padding:24px;margin-top:20px;text-align:center;">
                    <div style="font-size:2rem;margin-bottom:10px;">🌟</div>
                    <div style="font-family:var(--font-head);font-size:1rem;font-weight:700;margin-bottom:8px;">Rejoins MelanoGeek</div>
                    <div style="font-size:.86rem;color:var(--text-muted);margin-bottom:16px;line-height:1.6;">
                        Crée un compte pour suivre {{ $user->name }} et accéder à tout le contenu de la plateforme.
                    </div>
                    <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:8px;background:var(--terra);color:white;padding:11px 24px;border-radius:100px;font-family:var(--font-head);font-size:.88rem;font-weight:700;text-decoration:none;">
                        S'inscrire gratuitement →
                    </a>
                </div>
            @endauth
        </div>
        @endif

    </div>
</div>

{{-- ══ MODAL : Demande créateur ══ --}}
@if(auth()->id() === $user->id && !$user->isCreator() && !$user->isStaff())
<div id="creator-request-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.75);align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(4px);">
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:20px;padding:32px;max-width:480px;width:100%;position:relative;">
        <button onclick="document.getElementById('creator-request-modal').style.display='none'" style="position:absolute;top:16px;right:16px;background:none;border:none;color:var(--text-muted);font-size:1.2rem;cursor:pointer;line-height:1;">✕</button>
        <div style="font-size:2rem;margin-bottom:12px;">🎨</div>
        <div style="font-family:var(--font-head);font-size:1.1rem;font-weight:800;margin-bottom:6px;">Devenir Créateur</div>
        <div style="font-size:.84rem;color:var(--text-muted);margin-bottom:20px;line-height:1.6;">
            Explique-nous ta démarche créative, ton univers et ce que tu souhaites partager sur MelanoGeek.
        </div>
        <form method="POST" action="{{ route('creator-request.store') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);margin-bottom:8px;">Ta motivation</label>
                <textarea name="motivation" rows="5" required minlength="50" maxlength="1000"
                    placeholder="Décris ton univers créatif, tes projets, ce que tu vas partager…"
                    style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:12px;padding:12px 14px;color:var(--text);font-family:var(--font-body);font-size:.88rem;resize:vertical;outline:none;box-sizing:border-box;">{{ $myCreatorRequest?->motivation }}</textarea>
                <div style="font-size:.72rem;color:var(--text-faint);margin-top:4px;">Minimum 50 caractères.</div>
            </div>
            <button type="submit" class="btn-creator-action primary" style="width:100%;justify-content:center;padding:12px;">
                🎨 Soumettre ma demande
            </button>
        </form>
    </div>
</div>
@endif
@include('stories._viewer')

{{-- ══ POST MODAL ══ --}}
<div class="pm-overlay" id="pmOverlay" onclick="pmClickOutside(event)">
    <div class="pm-box" id="pmBox">

        {{-- Côté gauche : média --}}
        <div class="pm-left" id="pmLeft">
            <div style="color:rgba(255,255,255,.4); font-size:1.5rem;">⋯</div>
        </div>

        {{-- Côté droit : contenu --}}
        <div class="pm-right">
            <div class="pm-head" id="pmHead">
                <div class="pm-avi"><div class="pm-avi-inner" id="pmAviInner">?</div></div>
                <div class="pm-author-info">
                    <a class="pm-author-name" id="pmAuthorName" href="#">—</a>
                    <div class="pm-author-meta" id="pmAuthorMeta"></div>
                </div>
                <button class="pm-close" onclick="closePostModal()">✕</button>
            </div>

            <div class="pm-content" id="pmContent">
                <div class="pm-skeleton">
                    <div class="pm-skel-line" style="width:60%;"></div>
                    <div class="pm-skel-line" style="width:90%;"></div>
                    <div class="pm-skel-line" style="width:75%;"></div>
                    <div class="pm-skel-line" style="width:85%;"></div>
                    <div class="pm-skel-line" style="width:50%;"></div>
                </div>
            </div>

            <div class="pm-actions" id="pmActions" style="display:none;">
                <button class="pm-action-btn" id="pmLikeBtn" onclick="pmToggleLike()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <span id="pmLikeCount">0</span>
                </button>
                <span class="pm-action-btn" style="pointer-events:none;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span id="pmCommentCount">0</span>
                </span>
                <div class="pm-action-sep"></div>
                <a class="pm-full-link" id="pmFullLink" href="#">Voir le post →</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
@if($stories->isNotEmpty())
@php
    $storiesData = $stories->map(fn($s) => [
        'id'         => $s->id,
        'media_url'  => \Storage::url($s->media_url),
        'media_type' => $s->media_type,
        'created_at' => $s->created_at->diffForHumans(),
    ]);
    $userData = [
        'name'        => $user->name,
        'username'    => $user->username,
        'avatar'      => $user->avatar ? \Storage::url($user->avatar) : null,
        'is_verified' => (bool) $user->is_verified,
    ];
@endphp
const _profileStories = @json($storiesData);
const _profileUser    = @json($userData);
function openProfileStory(idx) {
    StoryViewer.open(_profileStories, _profileUser, idx);
}
@endif

    // Tabs
    const tabContents = ['posts', 'about', 'creator'].map(id => document.getElementById('tab-' + id)).filter(Boolean);
    document.querySelectorAll('.profile-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            tabContents.forEach(el => el.style.display = 'none');
            const target = document.getElementById('tab-' + this.dataset.tab);
            if (target) target.style.display = 'block';
        });
    });

    // Follow toggle
    const profileIsLocked = {{ $isLocked ? 'true' : 'false' }};

    function toggleFollow(userSlug, btn) {
        // Évite les double-clics
        if (btn.dataset.loading) return;
        btn.dataset.loading = '1';
        const prevText = btn.textContent.trim();
        btn.textContent = '…';
        btn.style.opacity = '0.6';

        fetch(`/users/${userSlug}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(data => {
            if (data.following) {
                // Met à jour tous les boutons suivre sur la page
                document.querySelectorAll('.btn-follow').forEach(b => {
                    b.textContent = 'Abonné ✓';
                    b.classList.add('following');
                });
                // Met à jour le compteur d'abonnés
                const counter = document.getElementById('followersCount');
                if (counter) counter.textContent = data.count.toLocaleString('fr-FR');
                // Profil privé : recharger pour débloquer les posts
                if (profileIsLocked) {
                    setTimeout(() => window.location.reload(), 400);
                    return;
                }
            } else {
                document.querySelectorAll('.btn-follow').forEach(b => {
                    b.textContent = '+ Suivre';
                    b.classList.remove('following');
                });
                const counter = document.getElementById('followersCount');
                if (counter) counter.textContent = data.count.toLocaleString('fr-FR');
            }
            btn.style.opacity = '';
            delete btn.dataset.loading;
        })
        .catch(() => {
            // Restaure le bouton en cas d'erreur
            btn.textContent = prevText;
            btn.style.opacity = '';
            delete btn.dataset.loading;
        });
    }

    function toggleBlock(userSlug, btn) {
        if (btn.dataset.loading) return;
        const isBlocking = btn.classList.contains('blocking');
        if (!isBlocking && !confirm('Bloquer cet utilisateur ? Il ne pourra plus te suivre ni te contacter.')) return;
        btn.dataset.loading = '1';
        btn.style.opacity = '0.6';

        fetch(`/users/${userSlug}/block`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.blocked) {
                btn.textContent = '🚫 Bloqué';
                btn.classList.add('blocking');
                // Mettre à jour le bouton suivre aussi
                document.querySelectorAll('.btn-follow').forEach(b => {
                    b.textContent = '+ Suivre';
                    b.classList.remove('following');
                });
                const counter = document.getElementById('followersCount');
                if (counter && data.count !== undefined) counter.textContent = data.count.toLocaleString('fr-FR');
            } else {
                btn.textContent = '⊘ Bloquer';
                btn.classList.remove('blocking');
            }
            btn.style.opacity = '';
            delete btn.dataset.loading;
        })
        .catch(() => {
            btn.style.opacity = '';
            delete btn.dataset.loading;
        });
    }

    function toggleAvailability(btn) {
        if (btn.dataset.loading) return;
        btn.dataset.loading = '1';
        btn.style.opacity = '0.6';

        fetch('/availability/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('availabilityBadge');
            if (data.available) {
                btn.textContent = '🟢 Disponible';
                btn.style.color = '';
                btn.style.borderColor = '';
                if (badge) {
                    badge.textContent = '🟢 Disponible aux commandes';
                    badge.style.background = 'rgba(34,197,94,.1)';
                    badge.style.color = '#16a34a';
                    badge.style.border = '1px solid rgba(34,197,94,.25)';
                }
            } else {
                btn.textContent = '🔴 Indisponible';
                btn.style.color = '#E05555';
                btn.style.borderColor = '#E05555';
                if (badge) {
                    badge.textContent = '🔴 Non disponible';
                    badge.style.background = 'rgba(224,85,85,.08)';
                    badge.style.color = '#E05555';
                    badge.style.border = '1px solid rgba(224,85,85,.2)';
                }
            }
            btn.style.opacity = '';
            delete btn.dataset.loading;
        })
        .catch(() => {
            btn.style.opacity = '';
            delete btn.dataset.loading;
        });
    }

    /* ══ POST MODAL ══ */
    let pmCurrentId  = null;
    let pmLiked      = false;
    let pmCarIdx     = 0;
    let pmCarTotal   = 0;
    let pmAudio      = null;

    function openPostModal(postId) {
        pmCurrentId = postId;
        pmLiked = false;
        pmCarIdx = 0;
        if (pmAudio) { pmAudio.pause(); pmAudio = null; }

        // Reset UI
        document.getElementById('pmLeft').innerHTML    = '<div style="color:rgba(255,255,255,.4);font-size:1.5rem;">⋯</div>';
        document.getElementById('pmContent').innerHTML = '<div class="pm-skeleton"><div class="pm-skel-line" style="width:60%;"></div><div class="pm-skel-line" style="width:90%;"></div><div class="pm-skel-line" style="width:75%;"></div></div>';
        document.getElementById('pmActions').style.display = 'none';
        document.getElementById('pmAuthorName').textContent = '—';
        document.getElementById('pmAuthorMeta').textContent = '';
        document.getElementById('pmAviInner').innerHTML = '?';

        document.getElementById('pmOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';

        fetch(`/posts/${postId}/data`)
            .then(r => r.json())
            .then(pmRender)
            .catch(() => {
                document.getElementById('pmContent').innerHTML = '<p style="padding:20px;color:var(--text-muted);">Impossible de charger ce post.</p>';
            });
    }

    function pmRender(p) {
        // Author
        const avi = document.getElementById('pmAviInner');
        if (p.user.avatar) {
            avi.innerHTML = `<img src="${p.user.avatar}" alt="">`;
        } else {
            avi.textContent = (p.user.name || '?')[0].toUpperCase();
        }
        const authorLink = document.getElementById('pmAuthorName');
        authorLink.textContent = p.user.name;
        authorLink.href = p.user.profile_url;
        document.getElementById('pmAviInner').parentElement.href = p.user.profile_url;
        document.getElementById('pmAuthorMeta').textContent = `@${p.user.username} · ${p.created_at}`;

        // ── Contenu exclusif verrouillé ──────────────────────────────
        if (p.locked) {
            const left = document.getElementById('pmLeft');
            left.innerHTML = `<div style="width:100%;height:100%;background:linear-gradient(135deg,rgba(212,168,67,.08),rgba(0,0,0,.5));display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;padding:24px;text-align:center;">
                <div style="font-size:3rem;line-height:1;">🔒</div>
                <div style="font-family:var(--font-head);font-size:1.05rem;font-weight:800;color:#fff;">Contenu exclusif</div>
                <div style="font-size:.82rem;color:rgba(255,255,255,.7);">Réservé aux abonnés MelanoGeek</div>
                <a href="${p.post_url}" style="display:inline-block;background:var(--gold);color:#1C1208;font-family:var(--font-head);font-size:.85rem;font-weight:700;padding:9px 22px;border-radius:100px;text-decoration:none;margin-top:4px;">
                    S'abonner pour voir ✦
                </a>
            </div>`;
            document.getElementById('pmContent').innerHTML = '';
            document.getElementById('pmActions').style.display = 'none';
            return;
        }

        // Média (gauche)
        const left = document.getElementById('pmLeft');
        if (p.media_files && p.media_files.length > 0) {
            // Carousel multi-images
            pmCarTotal = p.media_files.length;
            let slides = p.media_files.map(url =>
                `<div class="pm-car-slide"><img src="${url}" alt=""></div>`
            ).join('');
            let navBtns = pmCarTotal > 1
                ? `<button class="pm-car-btn prev" onclick="pmCarMove(-1)">‹</button><button class="pm-car-btn next" onclick="pmCarMove(1)">›</button><div class="pm-car-counter" id="pmCarCounter">1 / ${pmCarTotal}</div>`
                : '';
            left.innerHTML = `<div class="pm-carousel"><div class="pm-car-track" id="pmCarTrack">${slides}</div>${navBtns}</div>`;
        } else if (p.media_url && p.media_type === 'video') {
            left.innerHTML = `<video src="${p.media_url}" controls style="width:100%;height:100%;object-fit:contain;"></video>`;
        } else if (p.media_url && p.media_type === 'image') {
            left.innerHTML = `<img src="${p.media_url}" alt="">`;
        } else {
            // Post texte : gradient
            const gradients = ['#2C1810,#8B3A20','#1A2A1E,#2D5A3D','#1A1530,#3B2D6B','#2A1A08,#6B4010','#1E1010,#5A1E1E'];
            const g = gradients[p.id % 5];
            left.innerHTML = `<div class="pm-text-cover" style="background:linear-gradient(135deg,${g});">${(p.title || p.body || '').substring(0, 200)}</div>`;
        }

        // Contenu (droite)
        let html = '';
        if (p.title) html += `<div class="pm-title">${esc(p.title)}</div>`;
        if (p.body)  html += `<div class="pm-body">${esc(p.body)}</div>`;
        if (p.audio_url) {
            html += `<div class="pm-audio">
                <button class="pm-audio-btn" id="pmAudioBtn" onclick="pmToggleAudio('${p.audio_url}')">▶</button>
                <span class="pm-audio-name">🎵 ${esc(p.audio_name || 'Musique de fond')}</span>
                <audio id="pmAudioEl" src="${p.audio_url}" loop></audio>
            </div>`;
        }
        document.getElementById('pmContent').innerHTML = html || '<p style="color:var(--text-faint);font-size:.85rem;">Aucun contenu texte.</p>';

        // Actions
        pmLiked = p.liked;
        pmRenderLikeBtn(p.likes_count);
        document.getElementById('pmCommentCount').textContent = p.comments_count;
        document.getElementById('pmFullLink').href = p.post_url;
        document.getElementById('pmActions').style.display = 'flex';
    }

    function pmRenderLikeBtn(count) {
        const btn = document.getElementById('pmLikeBtn');
        btn.className = 'pm-action-btn' + (pmLiked ? ' liked' : '');
        btn.querySelector('svg').setAttribute('fill', pmLiked ? 'currentColor' : 'none');
        document.getElementById('pmLikeCount').textContent = count;
    }

    function pmToggleLike() {
        if (!pmCurrentId) return;
        const CSRF = document.querySelector('meta[name=csrf-token]').content;
        fetch(`/posts/${pmCurrentId}/like`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        }).then(r => r.json()).then(data => {
            pmLiked = data.liked;
            pmRenderLikeBtn(data.count);
        });
    }

    function pmCarMove(dir) {
        pmCarIdx = (pmCarIdx + dir + pmCarTotal) % pmCarTotal;
        document.getElementById('pmCarTrack').style.transform = `translateX(-${pmCarIdx * 100}%)`;
        const counter = document.getElementById('pmCarCounter');
        if (counter) counter.textContent = `${pmCarIdx + 1} / ${pmCarTotal}`;
    }

    function pmToggleAudio(url) {
        const el  = document.getElementById('pmAudioEl');
        const btn = document.getElementById('pmAudioBtn');
        if (!el) return;
        if (el.paused) { el.play(); btn.textContent = '⏸'; }
        else           { el.pause(); btn.textContent = '▶'; }
    }

    function closePostModal() {
        document.getElementById('pmOverlay').classList.remove('open');
        document.body.style.overflow = '';
        if (pmAudio) { pmAudio.pause(); pmAudio = null; }
        const el = document.getElementById('pmAudioEl');
        if (el) el.pause();
        pmCurrentId = null;
    }

    function pmClickOutside(e) {
        if (e.target === document.getElementById('pmOverlay')) closePostModal();
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closePostModal();
        if (document.getElementById('pmOverlay').classList.contains('open')) {
            if (e.key === 'ArrowRight') pmCarMove(1);
            if (e.key === 'ArrowLeft')  pmCarMove(-1);
        }
    });

    function esc(s) {
        if (!s) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
</script>
@endpush