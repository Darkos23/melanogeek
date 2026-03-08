{{-- ══ STORY VIEWER MODAL (partagé) ══ --}}
<div id="storyViewerOverlay" style="display:none;"
     onclick="if(event.target===this)StoryViewer.close()">
    <div id="storyViewerBox">

        {{-- Barre de progression --}}
        <div id="storyProgTrack"></div>

        {{-- Header utilisateur --}}
        <div id="storyViewerHead">
            <div id="storyViewerAvi"></div>
            <div id="storyViewerMeta">
                <div id="storyViewerName"></div>
                <div id="storyViewerTime"></div>
            </div>
            <button id="storyViewerClose" onclick="StoryViewer.close()" type="button">✕</button>
        </div>

        {{-- Zone média --}}
        <div id="storyMediaWrap"></div>

        {{-- Zones tactiles prev / next --}}
        <div id="storyTapPrev" onclick="StoryViewer.prev()"></div>
        <div id="storyTapNext" onclick="StoryViewer.next()"></div>

    </div>
</div>

<style>
#storyViewerOverlay {
    position: fixed; inset: 0; z-index: 1000;
    background: rgba(0,0,0,.92);
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(6px);
}
#storyViewerBox {
    position: relative;
    width: 100%; max-width: 420px;
    height: 100dvh; max-height: 800px;
    background: #0a0a0a;
    border-radius: 16px;
    overflow: hidden;
    display: flex; flex-direction: column;
    box-shadow: 0 24px 80px rgba(0,0,0,.9);
}
/* Barre de progression */
#storyProgTrack {
    display: flex; gap: 4px;
    padding: 12px 12px 0;
    flex-shrink: 0;
    position: relative; z-index: 2;
}
.story-prog-wrap {
    flex: 1; height: 3px;
    background: rgba(255,255,255,.3);
    border-radius: 3px;
    overflow: hidden;
}
.story-prog-bar {
    height: 100%; width: 0%;
    background: white;
    border-radius: 3px;
    transition: none;
}
.story-prog-bar.done  { width: 100%; }
.story-prog-bar.active { animation: storyProg linear forwards; }

/* Header */
#storyViewerHead {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px;
    flex-shrink: 0;
    position: relative; z-index: 2;
}
#storyViewerAvi {
    width: 36px; height: 36px; border-radius: 50%;
    border: 2px solid var(--terra, #C8522A);
    overflow: hidden; flex-shrink: 0;
    background: linear-gradient(135deg, #C8522A, #D4A843);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; color: white; font-size: .8rem;
}
#storyViewerAvi img { width: 100%; height: 100%; object-fit: cover; }
#storyViewerMeta { flex: 1; min-width: 0; }
#storyViewerName {
    font-size: .86rem; font-weight: 700; color: white;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
#storyViewerTime { font-size: .7rem; color: rgba(255,255,255,.6); }
#storyViewerClose {
    background: rgba(255,255,255,.15); border: none;
    width: 28px; height: 28px; border-radius: 50%;
    color: white; font-size: .8rem; cursor: none;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: background .2s;
}
#storyViewerClose:hover { background: rgba(255,255,255,.3); }

/* Zone média */
#storyMediaWrap {
    flex: 1;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    position: relative; z-index: 1;
}
#storyMediaWrap img,
#storyMediaWrap video {
    max-width: 100%; max-height: 100%;
    object-fit: contain;
    display: block;
}

/* Zones tactiles */
#storyTapPrev, #storyTapNext {
    position: absolute;
    top: 0; bottom: 0;
    width: 40%;
    z-index: 3; cursor: none;
}
#storyTapPrev { left: 0; }
#storyTapNext { right: 0; }

@keyframes storyProg {
    from { width: 0%; }
    to   { width: 100%; }
}
</style>

<script>
const StoryViewer = (function () {
    let stories    = [];
    let userInfo   = {};
    let currentIdx = 0;
    let timer      = null;

    function open(storiesArr, user, startIdx = 0) {
        stories    = storiesArr;
        userInfo   = user;
        currentIdx = startIdx;
        buildProgress(stories.length);
        renderUser();
        document.getElementById('storyViewerOverlay').style.display = 'flex';
        show(startIdx);
    }

    function close() {
        clearTimer();
        const video = document.querySelector('#storyMediaWrap video');
        if (video) video.pause();
        document.getElementById('storyViewerOverlay').style.display = 'none';
    }

    function show(idx) {
        clearTimer();
        currentIdx = idx;
        const story = stories[idx];
        if (!story) { close(); return; }

        // Barres de progression
        document.querySelectorAll('.story-prog-bar').forEach((bar, i) => {
            bar.style.animation = 'none';
            bar.style.width = i < idx ? '100%' : '0%';
        });
        const activeBar = document.querySelectorAll('.story-prog-bar')[idx];

        // Média
        const wrap = document.getElementById('storyMediaWrap');
        if (story.media_type === 'video') {
            wrap.innerHTML = `<video src="${esc(story.media_url)}" autoplay playsinline muted></video>`;
            const vid = wrap.querySelector('video');
            vid.onloadedmetadata = () => {
                activeBar.style.animation = `storyProg ${vid.duration}s linear forwards`;
            };
            vid.onended = () => next();
        } else {
            wrap.innerHTML = `<img src="${esc(story.media_url)}" alt="">`;
            activeBar.style.animation = 'storyProg 5s linear forwards';
            timer = setTimeout(() => next(), 5000);
        }

        // Temps
        document.getElementById('storyViewerTime').textContent = story.created_at || '';
    }

    function next() {
        if (currentIdx + 1 < stories.length) show(currentIdx + 1);
        else close();
    }

    function prev() {
        if (currentIdx > 0) show(currentIdx - 1);
        else show(0); // recommence depuis le début
    }

    function clearTimer() { if (timer) { clearTimeout(timer); timer = null; } }

    function buildProgress(n) {
        document.getElementById('storyProgTrack').innerHTML =
            Array.from({length: n}, () =>
                '<div class="story-prog-wrap"><div class="story-prog-bar"></div></div>'
            ).join('');
    }

    function renderUser() {
        const aviEl  = document.getElementById('storyViewerAvi');
        const nameEl = document.getElementById('storyViewerName');
        aviEl.innerHTML = userInfo.avatar
            ? `<img src="${esc(userInfo.avatar)}" alt="">`
            : (userInfo.name || '?')[0].toUpperCase();
        nameEl.textContent = (userInfo.name || '') + (userInfo.is_verified ? ' ✓' : '');
    }

    function esc(s) {
        return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Fermer avec Échap
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowRight') next();
        if (e.key === 'ArrowLeft')  prev();
    });

    return { open, close, next, prev };
})();
</script>
