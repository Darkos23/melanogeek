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
    const tabContents = ['posts', 'about', 'creator', 'portfolio'].map(id => document.getElementById('tab-' + id)).filter(Boolean);
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
                document.querySelectorAll('.btn-follow').forEach(b => {
                    b.textContent = 'Abonné ✓';
                    b.classList.add('following');
                });
                const counter = document.getElementById('followersCount');
                if (counter) counter.textContent = data.count.toLocaleString('fr-FR');
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

        if (p.locked) {
            const left = document.getElementById('pmLeft');
            left.innerHTML = `<div style="width:100%;height:100%;background:linear-gradient(135deg,rgba(212,168,67,.08),rgba(0,0,0,.5));display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;padding:24px;text-align:center;">
                <div style="font-size:3rem;line-height:1;">🔒</div>
                <div style="font-family:var(--font-head);font-size:1.05rem;font-weight:800;color:#fff;">Contenu exclusif</div>
                <div style="font-size:.82rem;color:rgba(255,255,255,.7);">Réservé aux abonnés MelanoGeek</div>
                <a href="${p.post_url}" style="display:inline-block;background:var(--gold);color:#1C1208;font-family:var(--font-head);font-size:.85rem;font-weight:700;padding:9px 22px;border-radius:100px;text-decoration:none;margin-top:4px;">S'abonner pour voir ✦</a>
            </div>`;
            document.getElementById('pmContent').innerHTML = '';
            document.getElementById('pmActions').style.display = 'none';
            return;
        }

        const left = document.getElementById('pmLeft');
        if (p.media_files && p.media_files.length > 0) {
            pmCarTotal = p.media_files.length;
            let slides = p.media_files.map(url => `<div class="pm-car-slide"><img src="${url}" alt=""></div>`).join('');
            let navBtns = pmCarTotal > 1
                ? `<button class="pm-car-btn prev" onclick="pmCarMove(-1)">‹</button><button class="pm-car-btn next" onclick="pmCarMove(1)">›</button><div class="pm-car-counter" id="pmCarCounter">1 / ${pmCarTotal}</div>`
                : '';
            left.innerHTML = `<div class="pm-carousel"><div class="pm-car-track" id="pmCarTrack">${slides}</div>${navBtns}</div>`;
        } else if (p.media_url && p.media_type === 'video') {
            left.innerHTML = `<video src="${p.media_url}" controls style="width:100%;height:100%;object-fit:contain;"></video>`;
        } else if (p.media_url && p.media_type === 'image') {
            left.innerHTML = `<img src="${p.media_url}" alt="">`;
        } else {
            const gradients = ['#2C1810,#8B3A20','#1A2A1E,#2D5A3D','#1A1530,#3B2D6B','#2A1A08,#6B4010','#1E1010,#5A1E1E'];
            const g = gradients[p.id % 5];
            left.innerHTML = `<div class="pm-text-cover" style="background:linear-gradient(135deg,${g});">${(p.title || p.body || '').substring(0, 200)}</div>`;
        }

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
        if (e.key === 'Escape') {
            closePostModal();
        }
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
