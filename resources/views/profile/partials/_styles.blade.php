<style>
    .profile-page { padding-top: calc(64px + env(safe-area-inset-top)); }

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
        cursor: pointer;
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
        margin-bottom: 20px;
    }
    .profile-identity { flex: 1; min-width: 0; }

    .profile-name {
        font-family: var(--font-head);
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        transition: color .4s;
    }
    .badge-verified {
        display: inline-flex; align-items: center;
        width: 20px; height: 20px;
        flex-shrink: 0;
    }
    .badge-verified svg { width: 100%; height: 100%; filter: drop-shadow(0 1px 4px rgba(56,151,240,.4)); }

    .profile-username {
        font-size: .88rem;
        color: var(--text-muted);
        margin-bottom: 8px;
        transition: color .4s;
    }
    

    .profile-bio {
        font-size: .9rem;
        color: var(--text-muted);
        line-height: 1.65;
        max-width: 520px;
        margin-bottom: 12px;
        transition: color .4s;
    }

    .profile-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 12px;
    }
    .profile-meta-item {
        display: flex; align-items: center; gap: 5px;
        font-size: .8rem; color: var(--text-muted);
    }
    .profile-meta-item a { color: var(--gold); text-decoration: none; }
    .profile-meta-item a:hover { text-decoration: underline; }

    .profile-socials { display: flex; flex-wrap: wrap; gap: 8px; }
    .social-link {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 100px;
        font-size: .78rem; font-weight: 600;
        color: var(--text-muted);
        text-decoration: none;
        transition: all .2s;
    }
    .social-link:hover {
        color: var(--text);
        border-color: var(--border-hover);
        background: var(--bg-hover);
    }

    /* Boutons actions */
    .profile-actions { display: flex; flex-wrap: wrap; gap: 10px; flex-shrink: 0; }
    .btn-edit {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px; border-radius: 100px;
        background: var(--bg-card2); border: 1px solid var(--border);
        color: var(--text-muted); font-family: var(--font-body);
        font-size: .84rem; font-weight: 600;
        text-decoration: none; transition: all .2s;
    }
    .btn-edit:hover { color: var(--text); border-color: var(--border-hover); background: var(--bg-hover); }

    .btn-follow {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 22px; border-radius: 100px;
        background: var(--terra); color: white; border: none;
        font-family: var(--font-body); font-size: .84rem; font-weight: 700;
        cursor: pointer; transition: background .2s;
    }
    .btn-follow.following {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-muted);
    }
    .btn-follow.following:hover { background: var(--bg-hover); color: var(--text); border-color: var(--border-hover); }
    .btn-follow:not(.following):hover { background: var(--accent); }

    .btn-message {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px; border-radius: 100px;
        background: var(--bg-card2); border: 1px solid var(--border);
        color: var(--text-muted); font-family: var(--font-body);
        font-size: .84rem; font-weight: 600;
        text-decoration: none; cursor: pointer;
        transition: all .2s;
    }
    .btn-message:hover { color: var(--text); border-color: var(--border-hover); background: var(--bg-hover); }

    .btn-block { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 100px; background: var(--bg-card2); border: 1px solid var(--border); color: var(--text-muted); font-family: var(--font-body); font-size: .82rem; font-weight: 600; cursor: pointer; transition: all .2s; }
    .btn-block:hover { border-color: #E05555; color: #E05555; }
    .btn-block.blocking { border-color: rgba(224,85,85,.4); color: #E05555; background: rgba(224,85,85,.06); }

    /* Stats */
    .profile-stats {
        display: flex;
        gap: 32px;
        padding: 16px 0;
        border-top: 1px solid var(--border);
        margin-top: 16px;
        transition: border-color .4s;
    }
    .stat-item { text-align: center; }
    .stat-value { font-family: var(--font-head); font-size: 1.2rem; font-weight: 800; color: var(--text); transition: color .4s; }
    .stat-label { font-size: .72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: .04em; margin-top: 2px; transition: color .4s; }

    /* Stories */
    .profile-stories {
        max-width: 900px; margin: 0 auto;
        padding: 14px 32px 0;
    }
    .profile-stories-label {
        font-size: .68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        color: var(--text-muted); margin-bottom: 10px;
    }
    .profile-stories-list { display: flex; gap: 12px; overflow-x: auto; padding-bottom: 6px; }
    .profile-story-thumb { background: none; border: none; padding: 0; cursor: pointer; flex-shrink: 0; }
    .profile-story-ring {
        width: 64px; height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2.5px;
        display: flex; align-items: center; justify-content: center;
    }
    .profile-story-ring-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
        background: var(--bg-card);
        border: 2px solid var(--bg);
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem;
        overflow: hidden;
    }
    .profile-story-ring-inner img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .profile-story-video-badge { font-size: .58rem; color: var(--text-muted); text-align: center; margin-top: 4px; }

    /* Body */
    .profile-body {
        max-width: 900px;
        margin: 0 auto;
        padding: 24px 32px 60px;
    }

    /* Tabs */
    .profile-tabs {
        display: flex;
        border-bottom: 1px solid var(--border);
        margin-bottom: 24px;
        gap: 0;
        overflow-x: auto;
        transition: border-color .4s;
    }
    .profile-tab {
        background: none; border: none; border-bottom: 2px solid transparent;
        padding: 12px 20px; font-size: .84rem; font-weight: 600;
        color: var(--text-muted); margin-bottom: -1px;
        cursor: pointer;
        transition: color .2s, border-color .2s;
        font-family: var(--font-body);
        letter-spacing: .02em;
        display: flex; align-items: center; gap: 7px;
        white-space: nowrap;
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
        cursor: pointer;
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

    .grid-post-badge {
        position: absolute; top: 8px; right: 8px;
        background: rgba(0,0,0,0.5);
        color: white;
        font-size: .65rem;
        padding: 3px 7px;
        border-radius: 6px;
        backdrop-filter: blur(4px);
    }

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

    /* Owner delete button on posts */
    .grid-post-owner-actions {
        position: absolute; top: 7px; right: 7px;
        display: flex; gap: 5px;
        opacity: 0; transition: opacity .2s;
        z-index: 10;
    }
    .grid-post:hover .grid-post-owner-actions { opacity: 1; }
    .grid-post-owner-actions .gp-del-btn {
        width: 28px; height: 28px; border-radius: 50%;
        background: rgba(0,0,0,.65); border: none; color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem; cursor: pointer; backdrop-filter: blur(4px);
        transition: background .2s; line-height: 1;
        text-decoration: none;
    }
    .grid-post-owner-actions .gp-del-btn:hover { background: rgba(200,50,50,.8); }
    /* Always visible on touch devices */
    @media (hover: none) {
        .grid-post-owner-actions { opacity: 1; }
        .pf-card-actions { opacity: 1 !important; }
    }

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

    .pm-content { flex: 1; overflow-y: auto; padding: 18px 20px; }
    .pm-title { font-family: var(--font-head); font-size: 1rem; font-weight: 800; color: var(--text); margin-bottom: 10px; line-height: 1.3; }
    .pm-body { font-size: .9rem; line-height: 1.7; color: var(--text-muted); white-space: pre-wrap; }

    .pm-audio { display: flex; align-items: center; gap: 10px; margin-top: 14px; padding: 10px 12px; background: var(--bg-card2); border: 1px solid var(--border); border-radius: 10px; }
    .pm-audio-name { font-size: .8rem; font-weight: 600; flex: 1; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pm-audio-btn { width: 30px; height: 30px; border-radius: 50%; background: var(--terra); border: none; color: white; display: flex; align-items: center; justify-content: center; font-size: .7rem; flex-shrink: 0; }

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
    .pm-full-link { font-size: .78rem; color: var(--terra); text-decoration: none; font-weight: 600; }
    .pm-full-link:hover { color: var(--accent); }

    .pm-skeleton { flex: 1; display: flex; flex-direction: column; gap: 12px; padding: 20px; }
    .pm-skel-line {
        height: 14px; border-radius: 6px;
        background: linear-gradient(90deg, var(--bg-card2) 25%, var(--bg-hover) 50%, var(--bg-card2) 75%);
        background-size: 200% 100%;
        animation: skel-shimmer 1.2s infinite;
    }
    @keyframes skel-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    @media (max-width: 680px) {
        .pm-box { flex-direction: column; max-height: 96vh; }
        .pm-left { flex: 0 0 280px; min-height: 280px; }
    }

    /* ══ PROFIL PRIVÉ ══ */
    .private-lock { padding: 60px 20px; text-align: center; border-top: 1px solid var(--border); margin-top: 24px; }
    .private-lock-icon { font-size: 2.8rem; margin-bottom: 16px; display: flex; align-items: center; justify-content: center; width: 72px; height: 72px; background: var(--bg-card2); border: 1.5px solid var(--border); border-radius: 50%; margin: 0 auto 20px; }
    .private-lock-title { font-family: var(--font-head); font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 10px; }
    .private-lock-desc { font-size: .88rem; color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }
    .private-lock-divider { display: flex; align-items: center; gap: 16px; max-width: 280px; margin: 0 auto 24px; color: var(--text-faint); font-size: .75rem; }
    .private-lock-divider::before, .private-lock-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    /* ══ ABOUT TAB ══ */
    .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .about-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; transition: background .4s, border-color .4s; }
    .about-card-title { font-family: var(--font-head); font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; }
    .about-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: .88rem; color: var(--text); transition: border-color .4s; }
    .about-item:last-child { border-bottom: none; }
    .about-item-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
    .about-item-value { color: var(--text-muted); }
    .about-item-value a { color: var(--gold); text-decoration: none; }
    .about-item-value a:hover { text-decoration: underline; }

    /* ══ CREATOR BADGE ══ */
    .badge-creator { background: linear-gradient(135deg, var(--gold), #E8A020); color: #1A0A00; font-size: .6rem; padding: 3px 10px; border-radius: 100px; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; white-space: nowrap; }
    .badge-cm { background: linear-gradient(135deg, #2DB8A0, #1E9A87); color: #040E0C; font-size: .6rem; padding: 3px 10px; border-radius: 100px; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; white-space: nowrap; }

    /* ══ CREATOR PANEL ══ */
    .creator-panel { background: linear-gradient(135deg, rgba(212,168,67,.07), rgba(200,82,42,.04)); border: 1px solid rgba(212,168,67,.22); border-radius: 16px; padding: 20px 24px; margin-bottom: 24px; }
    .creator-panel-title { font-family: var(--font-head); font-size: .75rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--gold); margin-bottom: 14px; display: flex; align-items: center; gap: 7px; }
    .creator-quick-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 16px; }
    .creator-quick-stat { text-align: center; background: var(--bg-card2); border-radius: 10px; padding: 12px 8px; transition: background .4s; }
    .creator-quick-stat-val { font-family: var(--font-head); font-size: 1.25rem; font-weight: 800; color: var(--gold); letter-spacing: -.01em; line-height: 1; margin-bottom: 4px; }
    .creator-quick-stat-lbl { font-size: .66rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
    .creator-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-creator-action { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 100px; font-size: .82rem; font-weight: 600; font-family: var(--font-body); text-decoration: none; border: none; cursor: pointer; transition: all .2s; }
    .btn-creator-action.primary { background: var(--terra); color: white; }
    .btn-creator-action.primary:hover { background: var(--accent); }
    .btn-creator-action.secondary { background: var(--bg-card2); color: var(--text-muted); border: 1px solid var(--border); }
    .btn-creator-action.secondary:hover { color: var(--text); border-color: var(--border-hover); }

    /* ══ BECOME CREATOR ══ */
    .become-creator-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 20px 24px; margin-bottom: 24px; display: flex; align-items: center; gap: 20px; transition: background .4s, border-color .4s; }
    .become-creator-icon { font-size: 2.2rem; flex-shrink: 0; }
    .become-creator-text { flex: 1; }
    .become-creator-title { font-family: var(--font-head); font-size: .95rem; font-weight: 700; margin-bottom: 5px; color: var(--text); }
    .become-creator-desc { font-size: .82rem; color: var(--text-muted); line-height: 1.55; }

    /* ══ CREATOR INFO TAB ══ */
    .creator-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .creator-info-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 22px; transition: background .4s, border-color .4s; }
    .creator-info-card-title { font-family: var(--font-head); font-size: .75rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 14px; }
    .creator-stat-row { display: flex; align-items: center; justify-content: space-between; padding: 9px 0; border-bottom: 1px solid var(--border); font-size: .86rem; transition: border-color .4s; }
    .creator-stat-row:last-child { border-bottom: none; }
    .creator-stat-row-label { color: var(--text-muted); display: flex; align-items: center; gap: 8px; }
    .creator-stat-row-value { font-weight: 700; color: var(--text); }

    /* ══ PORTFOLIO IMMERSIF ══ */
    .pf-tab-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 14px;
    }
    .pf-filter-chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .pf-chip {
        padding: 6px 16px; border-radius: 100px;
        background: var(--bg-card2); border: 1px solid var(--border);
        font-size: .78rem; font-weight: 600; color: var(--text-muted);
        cursor: pointer; transition: all .2s; white-space: nowrap;
    }
    .pf-chip:hover { color: var(--text); border-color: var(--border-hover); }
    .pf-chip.active { background: var(--terra-soft); border-color: var(--terra); color: var(--terra); }

    .pf-add-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 20px; border-radius: 100px;
        background: var(--terra); color: white; border: none;
        font-family: var(--font-body); font-size: .82rem; font-weight: 700;
        text-decoration: none; transition: background .2s; white-space: nowrap;
    }
    .pf-add-btn:hover { background: var(--accent); color: white; }

    /* Masonry grid via CSS columns */
    .pf-masonry {
        columns: 3;
        column-gap: 14px;
    }
    .pf-masonry-item {
        break-inside: avoid;
        margin-bottom: 14px;
        cursor: pointer;
    }

    .pf-card-inner {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        transition: transform .3s ease, box-shadow .3s ease;
        min-height: 160px;
    }
    .pf-masonry-item:hover .pf-card-inner {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0,0,0,.4);
    }

    .pf-card-img {
        width: 100%;
        display: block;
        object-fit: cover;
    }
    .pf-card-no-img {
        width: 100%;
        aspect-ratio: 4/3;
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem;
        background: linear-gradient(135deg, var(--bg-card2), var(--bg-hover));
    }

    /* Overlay hover */
    .pf-card-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.88) 0%, rgba(0,0,0,.3) 50%, transparent 100%);
        opacity: 0;
        transition: opacity .3s ease;
        display: flex; flex-direction: column; justify-content: flex-end;
        padding: 20px;
    }
    .pf-masonry-item:hover .pf-card-overlay { opacity: 1; }

    .pf-card-title {
        font-family: var(--font-head); font-size: .92rem; font-weight: 800;
        color: white; line-height: 1.25; margin-bottom: 6px;
        transform: translateY(8px);
        transition: transform .3s ease;
    }
    .pf-masonry-item:hover .pf-card-title { transform: translateY(0); }

    .pf-card-cat {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: .7rem; font-weight: 700;
        color: rgba(255,255,255,.75);
        background: rgba(255,255,255,.12);
        padding: 3px 10px; border-radius: 100px;
        backdrop-filter: blur(4px);
        transform: translateY(8px);
        transition: transform .3s ease .04s;
        width: fit-content;
    }
    .pf-masonry-item:hover .pf-card-cat { transform: translateY(0); }

    /* Owner controls — barre visible sous chaque carte */
    .pf-owner-bar {
        display: flex; gap: 6px; padding: 6px 4px 2px;
    }
    .pf-owner-btn {
        flex: 1; padding: 6px 0; border-radius: 8px; border: none;
        font-size: .72rem; font-weight: 600; cursor: pointer;
        text-align: center; text-decoration: none;
        display: block; transition: background .15s, color .15s;
        font-family: var(--font-body);
    }
    .pf-owner-btn.edit {
        background: var(--bg-card2); color: var(--text-muted);
    }
    .pf-owner-btn.edit:hover { background: var(--border); color: var(--text); }
    .pf-owner-btn.delete {
        background: rgba(200,82,42,.1); color: var(--terra);
    }
    .pf-owner-btn.delete:hover { background: var(--terra); color: white; }

    /* External link badge */
    .pf-card-link-badge {
        position: absolute; bottom: 10px; right: 10px;
        background: rgba(212,168,67,.85); color: #1C1208;
        font-size: .6rem; font-weight: 700;
        padding: 3px 8px; border-radius: 6px;
        backdrop-filter: blur(4px);
        letter-spacing: .02em;
    }

    /* Entrance animation */
    .pf-masonry-item {
        opacity: 0;
        animation: pfFadeUp .5s ease forwards;
    }
    @keyframes pfFadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Lightbox */
    .pf-lb-overlay {
        position: fixed; inset: 0; z-index: 700;
        background: rgba(0,0,0,.92);
        backdrop-filter: blur(12px);
        display: flex; align-items: center; justify-content: center;
        padding: 20px;
        opacity: 0; visibility: hidden;
        transition: opacity .25s, visibility .25s;
    }
    .pf-lb-overlay.open { opacity: 1; visibility: visible; }
    .pf-lb-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 24px;
        width: 100%; max-width: 1000px;
        max-height: 90vh;
        display: flex;
        overflow: hidden;
        box-shadow: 0 32px 80px rgba(0,0,0,.7);
        transform: scale(.94);
        transition: transform .25s;
    }
    .pf-lb-overlay.open .pf-lb-box { transform: scale(1); }

    .pf-lb-left {
        flex: 0 0 58%;
        background: #0A0805;
        display: flex; align-items: center; justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .pf-lb-img { width: 100%; height: 100%; object-fit: contain; display: block; }
    .pf-lb-no-img {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; gap: 12px; color: rgba(255,255,255,.3);
        font-size: 4rem;
    }

    /* Gallery navigation in lightbox */
    .pf-lb-car-track { display: flex; height: 100%; transition: transform .3s; }
    .pf-lb-car-slide { flex: 0 0 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
    .pf-lb-car-slide img { width: 100%; height: 100%; object-fit: contain; }
    .pf-lb-nav {
        position: absolute; top: 50%; transform: translateY(-50%);
        background: rgba(0,0,0,.5); border: none; color: white;
        width: 38px; height: 38px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; cursor: pointer; z-index: 5;
        backdrop-filter: blur(4px); transition: background .2s;
    }
    .pf-lb-nav:hover { background: rgba(0,0,0,.8); }
    .pf-lb-nav.prev { left: 12px; }
    .pf-lb-nav.next { right: 12px; }
    .pf-lb-counter {
        position: absolute; bottom: 12px; right: 14px;
        background: rgba(0,0,0,.5); color: white;
        font-size: .7rem; font-weight: 700;
        padding: 3px 9px; border-radius: 20px;
        backdrop-filter: blur(4px);
    }

    .pf-lb-right {
        flex: 1;
        display: flex; flex-direction: column;
        overflow-y: auto;
        padding: 32px 28px;
        min-width: 0;
        position: relative;
    }
    .pf-lb-close {
        position: absolute; top: 16px; right: 16px;
        background: var(--bg-card2); border: 1px solid var(--border);
        color: var(--text-muted); width: 32px; height: 32px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: .9rem; cursor: pointer; z-index: 5; transition: all .2s;
    }
    .pf-lb-close:hover { color: var(--text); border-color: var(--border-hover); }

    .pf-lb-cat-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 100px;
        background: var(--terra-soft); border: 1px solid rgba(200,82,42,.3);
        font-size: .72rem; font-weight: 700; color: var(--terra);
        margin-bottom: 14px;
    }
    .pf-lb-title {
        font-family: var(--font-head); font-size: 1.4rem; font-weight: 800;
        color: var(--text); line-height: 1.2; margin-bottom: 16px;
    }
    .pf-lb-desc {
        font-size: .88rem; color: var(--text-muted); line-height: 1.7;
        margin-bottom: 20px; white-space: pre-wrap;
    }
    .pf-lb-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 20px; }
    .pf-lb-tag {
        padding: 3px 10px; border-radius: 100px;
        background: var(--bg-card2); border: 1px solid var(--border);
        font-size: .72rem; color: var(--text-muted);
    }
    .pf-lb-link {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 22px; border-radius: 100px;
        background: var(--terra); color: white; text-decoration: none;
        font-family: var(--font-head); font-size: .85rem; font-weight: 700;
        transition: background .2s; width: fit-content; margin-top: auto;
    }
    .pf-lb-link:hover { background: var(--accent); color: white; }

    /* Responsive */
    @@media (max-width: 900px) {
        .pf-masonry { columns: 2; }
        .pf-lb-box { flex-direction: column; max-height: 95vh; }
        .pf-lb-left { flex: 0 0 260px; }
    }
    @@media (max-width: 600px) {
        .pf-masonry { columns: 2; column-gap: 8px; }
        .pf-masonry-item { margin-bottom: 8px; }
    }
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
