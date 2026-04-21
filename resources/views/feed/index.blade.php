@extends('layouts.app')

@section('title', 'Fil d\'actualité')

@push('styles')
<style>
    .feed-page {
        padding-top: calc(80px + env(safe-area-inset-top));
        min-height: 100vh;
    }

    /* ══════════════════════════════════════
       LAYOUT : feed + sidebar
    ══════════════════════════════════════ */
    .feed-layout {
        max-width: 1060px;
        margin: 0 auto;
        padding: 36px 24px 80px;
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 28px;
        align-items: start;
    }

    /* ══════════════════════════════════════
       DISCOVERY BANNER
    ══════════════════════════════════════ */
    .discovery-banner {
        background: linear-gradient(135deg, var(--terra-soft), var(--gold-soft));
        border: 1px solid rgba(200,82,42,.2);
        border-radius: 18px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }
    .discovery-icon {
        font-size: 2rem;
        flex-shrink: 0;
        line-height: 1;
    }
    .discovery-text-title {
        font-family: var(--font-head);
        font-size: .96rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 3px;
    }
    .discovery-text-sub {
        font-size: .82rem;
        color: var(--text-muted);
        line-height: 1.45;
    }

    /* ══════════════════════════════════════
       POST CARD
    ══════════════════════════════════════ */
    .feed-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 16px;
        transition: border-color .2s, box-shadow .2s;
    }
    .feed-card:hover {
        border-color: var(--border-hover);
        box-shadow: var(--shadow-sm);
    }

    /* Auteur */
    .feed-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px 14px;
        gap: 10px;
    }
    .feed-card-author {
        display: flex;
        align-items: center;
        gap: 11px;
        text-decoration: none;
        flex: 1;
        min-width: 0;
    }
    .feed-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px;
        flex-shrink: 0;
        overflow: hidden;
        display: block;
    }
    .feed-avatar-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; font-weight: 700; color: var(--text);
        overflow: hidden;
    }
    .feed-avatar-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }

    .feed-author-info { min-width: 0; }
    .feed-author-name {
        font-family: var(--font-head);
        font-size: .9rem; font-weight: 700;
        color: var(--text);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        display: flex; align-items: center; gap: 5px;
    }
    .feed-author-name .verified-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 16px; height: 16px;
        background: var(--terra);
        border-radius: 50%;
        font-size: .55rem;
        color: white;
        font-weight: 700;
        flex-shrink: 0;
    }
    .feed-author-meta {
        font-size: .74rem;
        color: var(--text-muted);
        margin-top: 1px;
    }
    

    /* Bouton "Suivre" dans le card */
    .feed-follow-btn {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-muted);
        padding: 6px 14px;
        border-radius: 100px;
        font-family: var(--font-body);
        font-size: .78rem; font-weight: 500;
        cursor: pointer;
        transition: all .2s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .feed-follow-btn:hover {
        border-color: var(--terra);
        color: var(--terra);
        background: var(--terra-soft);
    }
    .feed-follow-btn.following {
        background: var(--terra-soft);
        border-color: rgba(200,82,42,.3);
        color: var(--terra);
    }
    .feed-follow-btn.following:hover {
        background: rgba(224,85,85,.08);
        border-color: rgba(224,85,85,.4);
        color: #E05555;
    }

    /* Contenu */
    .feed-card-title {
        font-family: var(--font-head);
        font-size: 1.05rem; font-weight: 800; letter-spacing: -.02em;
        color: var(--text); line-height: 1.3;
        padding: 0 20px 10px;
    }
    .feed-card-body {
        font-size: .91rem; line-height: 1.7;
        color: var(--text-muted);
        padding: 0 20px 14px;
        white-space: pre-wrap;
    }
    /* Tronquer à 4 lignes */
    .feed-card-body.truncated {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        white-space: normal;
    }

    /* Média */
    .feed-card-media {
        width: 100%;
        background: var(--bg-card2);
        overflow: hidden;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }
    .feed-card-media img,
    .feed-card-media video {
        width: 100%;
        max-height: 480px;
        object-fit: cover;
        display: block;
        cursor: pointer;
    }

    /* Lien "Voir plus" */
    .feed-read-more {
        display: block;
        color: var(--terra);
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        padding: 0 20px 12px;
        transition: color .2s;
    }
    .feed-read-more:hover { color: var(--accent); }

    /* ══ CONTENU EXCLUSIF ══ */
    .exclusive-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: .64rem;
        font-weight: 700;
        color: var(--gold);
        background: rgba(212,168,67,.12);
        border: 1px solid rgba(212,168,67,.3);
        border-radius: 100px;
        padding: 2px 7px;
        letter-spacing: .03em;
        vertical-align: middle;
        flex-shrink: 0;
    }
    .exclusive-gate {
        position: relative;
        overflow: hidden;
        border-top: 1px solid rgba(212,168,67,.15);
        border-bottom: 1px solid rgba(212,168,67,.15);
        margin: 6px 0;
    }
    .exclusive-blur-zone {
        filter: blur(10px);
        pointer-events: none;
        user-select: none;
        opacity: .55;
        max-height: 220px;
        overflow: hidden;
    }
    .exclusive-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,.55) 50%, rgba(0,0,0,.72) 100%);
        text-align: center;
        padding: 20px;
        min-height: 140px;
    }
    .exclusive-lock-icon { font-size: 1.7rem; line-height: 1; }
    .exclusive-lock-label {
        font-family: var(--font-head);
        font-size: .95rem;
        font-weight: 800;
        color: #fff;
        text-shadow: 0 1px 6px rgba(0,0,0,.4);
    }
    .exclusive-lock-sub { font-size: .76rem; color: rgba(255,255,255,.72); margin-top: -2px; }
    .exclusive-lock-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--gold);
        color: #1C1208;
        font-family: var(--font-head);
        font-size: .82rem;
        font-weight: 700;
        padding: 8px 22px;
        border-radius: 100px;
        text-decoration: none;
        margin-top: 6px;
        transition: opacity .2s, transform .15s;
        white-space: nowrap;
    }
    .exclusive-lock-btn:hover { opacity: .88; transform: translateY(-1px); }

    /* Actions */
    .feed-card-actions {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 10px 16px;
        border-top: 1px solid var(--border);
    }
    .feed-action-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 7px 14px; border-radius: 100px;
        background: transparent; border: 1px solid transparent;
        color: var(--text-muted); font-family: var(--font-body);
        font-size: .82rem; font-weight: 500;
        cursor: pointer; transition: all .2s;
    }
    .feed-action-btn:hover { background: var(--bg-card2); border-color: var(--border); color: var(--text); }
    .feed-action-btn.liked { color: #E05555; }
    .feed-action-btn.liked:hover { background: rgba(224,85,85,.08); border-color: rgba(224,85,85,.3); }
    .feed-action-btn svg { width: 15px; height: 15px; flex-shrink: 0; }

    .feed-action-sep { flex: 1; }
    .feed-action-share {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 100px;
        background: transparent; border: 1px solid var(--border);
        color: var(--text-muted); font-family: var(--font-body);
        font-size: .82rem; cursor: pointer; transition: all .2s;
        text-decoration: none;
    }
    .feed-action-share:hover { border-color: var(--gold); color: var(--gold); }

    /* ══════════════════════════════════════
       PAGINATION
    ══════════════════════════════════════ */
    .feed-pagination { margin-top: 8px; }

    /* ══════════════════════════════════════
       EMPTY STATE
    ══════════════════════════════════════ */
    .feed-empty {
        text-align: center;
        padding: 70px 20px;
        color: var(--text-muted);
    }
    .feed-empty-icon { font-size: 2.8rem; margin-bottom: 14px; }
    .feed-empty-title {
        font-family: var(--font-head);
        font-size: 1.2rem; font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }
    .feed-empty-sub { font-size: .88rem; line-height: 1.55; margin-bottom: 20px; }
    .feed-empty-cta { display: inline-flex; }

    /* ══════════════════════════════════════
       SIDEBAR
    ══════════════════════════════════════ */
    .feed-sidebar {
        position: sticky;
        top: 96px;
    }

    /* Créer un post */
    .sidebar-create {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 18px;
        margin-bottom: 16px;
    }
    .sidebar-create-title {
        font-family: var(--font-head);
        font-size: .88rem; font-weight: 700;
        color: var(--text);
        margin-bottom: 12px;
    }
    .sidebar-create-input {
        display: flex; align-items: center; gap: 10px;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 100px;
        padding: 10px 16px;
        text-decoration: none;
        color: var(--text-faint);
        font-size: .85rem;
        transition: border-color .2s, background .2s;
        width: 100%;
        cursor: pointer;
    }
    .sidebar-create-input:hover {
        border-color: var(--terra);
        background: var(--terra-soft);
        color: var(--terra);
    }

    /* Suggestions */
    .sidebar-suggestions {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
    }
    .sidebar-suggestions-title {
        font-family: var(--font-head);
        font-size: .88rem; font-weight: 700;
        color: var(--text);
        padding: 16px 18px 12px;
        border-bottom: 1px solid var(--border);
    }
    .suggestion-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .suggestion-item:last-child { border-bottom: none; }
    .suggestion-item:hover { background: var(--bg-hover); }

    .suggestion-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px;
        flex-shrink: 0;
        overflow: hidden;
        text-decoration: none;
        display: block;
    }
    .suggestion-avatar-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; font-weight: 700; color: var(--text);
        overflow: hidden;
    }
    .suggestion-avatar-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }

    .suggestion-info { flex: 1; min-width: 0; }
    .suggestion-name {
        font-size: .84rem; font-weight: 600;
        color: var(--text);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        text-decoration: none;
        display: block;
        transition: color .2s;
    }
    .suggestion-name:hover { color: var(--terra); }
    .suggestion-meta { font-size: .72rem; color: var(--text-muted); margin-top: 1px; }

    .suggestion-follow-btn {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-muted);
        padding: 5px 12px;
        border-radius: 100px;
        font-family: var(--font-body);
        font-size: .74rem; font-weight: 500;
        cursor: pointer;
        transition: all .2s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .suggestion-follow-btn:hover {
        border-color: var(--terra);
        color: var(--terra);
        background: var(--terra-soft);
    }
    .suggestion-follow-btn.following {
        background: var(--terra-soft);
        border-color: rgba(200,82,42,.3);
        color: var(--terra);
    }

    .sidebar-see-all {
        display: block;
        text-align: center;
        padding: 12px;
        font-size: .78rem;
        color: var(--terra);
        text-decoration: none;
        font-weight: 600;
        transition: color .2s;
        border-top: 1px solid var(--border);
    }
    .sidebar-see-all:hover { color: var(--accent); }

    /* ══════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════ */
    @@media (max-width: 860px) {
        .feed-layout {
            grid-template-columns: 1fr;
            padding: 24px 16px 60px;
        }
        .feed-sidebar { position: static; order: -1; }
        .feed-sidebar .sidebar-suggestions { display: none; }
    }
    @@media (max-width: 520px) {
        .feed-card-head { padding: 14px 14px 10px; }
        .feed-card-title { padding: 0 14px 8px; font-size: .96rem; }
        .feed-card-body { padding: 0 14px 12px; }
        .feed-card-actions { padding: 8px 10px; }
        .feed-action-btn { padding: 6px 10px; font-size: .8rem; gap: 5px; }
    }
    @@media (max-width: 400px) {
        .feed-layout { padding: 16px 10px 60px; }
        .feed-card-head { padding: 12px 12px 8px; }
        .feed-card-title { font-size: .9rem; }
    }
</style>
@endpush

@section('content')
<div class="feed-page">
<div class="feed-layout">

    {{-- ════════════════════════════════════
         COLONNE PRINCIPALE
    ════════════════════════════════════ --}}
    <main>

        {{-- Bannière découverte --}}
        @if($isDiscovery)
        <div class="discovery-banner fade-in">
            <div class="discovery-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div>
            <div>
                <div class="discovery-text-title">Mode Découverte</div>
                <div class="discovery-text-sub">Tu ne suis personne encore — on te montre les dernières publications de la communauté. Suis des créateurs pour personnaliser ton fil !</div>
            </div>
        </div>
        @endif

        {{-- Posts --}}
        <div id="feedList">
        @forelse ($posts as $post)
            @include('feed._post', ['post' => $post, 'likedPostIds' => $likedPostIds, 'followingIdsArr' => $followingIdsArr])
        @empty
        <div class="feed-empty fade-in">
            <div class="feed-empty-icon"><svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg></div>
            <div class="feed-empty-title">Aucune publication pour l'instant</div>
            <div class="feed-empty-sub">Suis des créateurs pour voir leurs publications ici,<br>ou explore la communauté !</div>
            <a href="{{ route('creators') }}" class="btn-primary feed-empty-cta">Découvrir des créateurs →</a>
        </div>
        @endforelse
        </div>

        {{-- Infinite scroll sentinel --}}
        @if($posts->hasMorePages())
        <div id="feedSentinel" style="height:60px;display:flex;align-items:center;justify-content:center;">
            <span style="font-size:.78rem;color:var(--text-faint);">Chargement…</span>
        </div>
        @endif
        <div id="feedEndMsg" style="display:none;text-align:center;padding:24px 0;font-size:.78rem;color:var(--text-faint);">
            Tu as tout vu ! 🎉
        </div>

    </main>

    {{-- ════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════ --}}
    <aside class="feed-sidebar">

        {{-- Créer un post (admin uniquement) --}}
        @auth
        @if(auth()->user()->isAdmin())
        <div class="sidebar-create">
            <div class="sidebar-create-title">Publier quelque chose</div>
            <a href="{{ route('posts.create') }}" class="sidebar-create-input" style="display:inline-flex;align-items:center;gap:8px;">
                <x-icon name="pen" :size="14"/> Qu'as-tu à partager ?
            </a>
        </div>
        @endif
        @endauth

        {{-- Suggestions --}}
        @if($suggestions->isNotEmpty())
        <div class="sidebar-suggestions">
            <div class="sidebar-suggestions-title">Créateurs à suivre</div>

            @foreach($suggestions as $user)
            <div class="suggestion-item">
                <a href="{{ route('profile.show', $user->username) }}" class="suggestion-avatar">
                    <div class="suggestion-avatar-inner">
                        @if($user->avatar)
                            <img src="{{ Storage::disk('public')->url($user->avatar) }}" alt="{{ $user->name }}">
                        @else
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                </a>
                <div class="suggestion-info">
                    <a href="{{ route('profile.show', $user->username) }}" class="suggestion-name">{{ $user->username }}</a>
                    <div class="suggestion-meta">
                        {{ number_format($user->followers_count) }} abonné{{ $user->followers_count > 1 ? 's' : '' }}
                    </div>
                </div>
                <button class="suggestion-follow-btn"
                    onclick="toggleFollow('{{ $user->username }}', this)"
                    + Suivre
                </button>
            </div>
            @endforeach

            <a href="{{ route('creators') }}" class="sidebar-see-all">Voir tous les créateurs →</a>
        </div>
        @endif

    </aside>

</div>
</div>
@endsection

@push('scripts')
<script>
    /* ══ INFINITE SCROLL ══ */
    (function () {
        let nextPage  = {{ $posts->currentPage() + 1 }};
        let hasMore   = {{ $posts->hasMorePages() ? 'true' : 'false' }};
        let loading   = false;

        const sentinel = document.getElementById('feedSentinel');
        const feedList = document.getElementById('feedList');
        const endMsg   = document.getElementById('feedEndMsg');

        if (!sentinel || !hasMore) {
            if (!hasMore && feedList && feedList.children.length > 0) {
                if (endMsg) endMsg.style.display = 'block';
            }
            return;
        }

        const observer = new IntersectionObserver(entries => {
            if (!entries[0].isIntersecting || loading || !hasMore) return;
            loading = true;

            fetch(`/feed/more?page=${nextPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.html) {
                    feedList.insertAdjacentHTML('beforeend', data.html);
                }
                hasMore  = data.hasMore;
                nextPage = data.nextPage;
                loading  = false;

                if (!hasMore) {
                    sentinel.remove();
                    if (endMsg) endMsg.style.display = 'block';
                }
            })
            .catch(() => { loading = false; });
        }, { rootMargin: '200px' });

        observer.observe(sentinel);
    })();

    /* ── Like ── */
    function toggleLike(postId, btn) {
        fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            const svg   = btn.querySelector('svg');
            const count = btn.querySelector('.like-count');
            if (data.liked) {
                btn.classList.add('liked');
                svg.setAttribute('fill', 'currentColor');
            } else {
                btn.classList.remove('liked');
                svg.setAttribute('fill', 'none');
            }
            if (data.count !== undefined) {
                count.textContent = Number(data.count).toLocaleString('fr-FR');
            }
        })
        .catch(() => {
            // Fallback optimiste
            const liked = btn.classList.toggle('liked');
            const svg   = btn.querySelector('svg');
            svg.setAttribute('fill', liked ? 'currentColor' : 'none');
            const count = btn.querySelector('.like-count');
            const n = parseInt(count.textContent.replace(/\s/g,''));
            count.textContent = liked ? n + 1 : Math.max(0, n - 1);
        });
    }

    /* ── Follow ── */
    function toggleFollow(username, btn) {
        fetch(`/users/${username}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.following) {
                btn.textContent = 'Abonné ✓';
                btn.classList.add('following');
            } else {
                btn.textContent = '+ Suivre';
                btn.classList.remove('following');
            }

            // Mettre à jour le compteur d'abonnés dans la sidebar
            if (data.count !== undefined) {
                const item = btn.closest('.suggestion-item');
                if (item) {
                    const meta = item.querySelector('.suggestion-meta');
                    if (meta) {
                        const count  = Number(data.count);
                        const label  = count > 1 ? 'abonnés' : 'abonné';
                    }
                }
            }
        });
    }

    /* ── Partage ── */
    function sharePost(url) {
        if (navigator.share) {
            navigator.share({ title: document.title, url });
        } else {
            navigator.clipboard.writeText(url)
                .then(() => {
                    // petit toast discret
                    const t = document.createElement('div');
                    t.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:5px;"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>Lien copié !';
                    Object.assign(t.style, {
                        position:'fixed', bottom:'32px', left:'50%',
                        transform:'translateX(-50%)',
                        background:'var(--bg-card)', border:'1px solid var(--border)',
                        color:'var(--text)', padding:'10px 20px',
                        borderRadius:'100px', fontSize:'.84rem',
                        boxShadow:'var(--shadow-md)', zIndex:'9999',
                        fontFamily:'var(--font-body)'
                    });
                    document.body.appendChild(t);
                    setTimeout(() => t.remove(), 2500);
                });
        }
    }

    /* ── Signalement ── */
    let _reportPostId = null;
    function openReportModal(postId) {
        _reportPostId = postId;
        document.getElementById('reportModal').classList.add('open');
    }
    function closeReportModal() {
        document.getElementById('reportModal').classList.remove('open');
        _reportPostId = null;
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('reportForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!_reportPostId) return;
            const reason = document.getElementById('reportReason').value;
            if (!reason) return;
            const CSRF = document.querySelector('meta[name=csrf-token]').content;
            fetch(`/posts/${_reportPostId}/report`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
                body: JSON.stringify({ reason })
            }).then(r => r.json()).then(data => {
                closeReportModal();
                const t = document.createElement('div');
                t.textContent = '✓ Signalement envoyé. Notre équipe va examiner ce contenu.';
                Object.assign(t.style, { position:'fixed', bottom:'24px', left:'50%', transform:'translateX(-50%)', background:'var(--bg-card)', border:'1px solid var(--border)', color:'var(--text)', padding:'12px 20px', borderRadius:'100px', fontSize:'.84rem', boxShadow:'var(--shadow-md)', zIndex:'9999', fontFamily:'var(--font-body)' });
                document.body.appendChild(t);
                setTimeout(() => t.remove(), 3000);
            }).catch(() => {});
        });
    });
</script>
@endpush

{{-- Modal signalement --}}
<div id="reportModal" style="display:none;position:fixed;inset:0;z-index:800;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:16px;"
     onclick="if(event.target===this)closeReportModal()">
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:20px;width:100%;max-width:400px;overflow:hidden;box-shadow:var(--shadow-lg);">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid var(--border);">
            <div style="font-family:var(--font-head);font-size:.95rem;font-weight:800;display:flex;align-items:center;gap:7px;"><x-icon name="flag" :size="15"/> Signaler ce contenu</div>
            <button onclick="closeReportModal()" style="background:none;border:none;color:var(--text-muted);display:flex;align-items:center;cursor:pointer;"><x-icon name="x" :size="16"/></button>
        </div>
        <form id="reportForm" style="padding:20px;">
            <p style="font-size:.84rem;color:var(--text-muted);margin-bottom:16px;line-height:1.5;">Pourquoi signales-tu cette publication ? Notre équipe de modération examinera le signalement.</p>
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:20px;">
                @foreach(['Contenu adulte / sexuel','Violence ou contenu choquant','Harcèlement ou intimidation','Fausse information / désinformation','Spam ou contenu commercial non souhaité','Autre'] as $r)
                <label style="display:flex;align-items:center;gap:10px;padding:10px 14px;border:1px solid var(--border);border-radius:10px;cursor:pointer;transition:background .15s;" onmouseover="this.style.background='var(--bg-card2)'" onmouseout="this.style.background=''">
                    <input type="radio" name="reportReasonRadio" value="{{ $r }}" onclick="document.getElementById('reportReason').value=this.value" style="accent-color:var(--terra);">
                    <span style="font-size:.85rem;">{{ $r }}</span>
                </label>
                @endforeach
                <input type="hidden" id="reportReason" name="reason" value="">
            </div>
            <button type="submit" style="width:100%;background:var(--terra);color:white;border:none;border-radius:12px;padding:12px;font-family:var(--font-head);font-size:.88rem;font-weight:700;">
                Envoyer le signalement
            </button>
        </form>
    </div>
</div>

<style>
#reportModal { display: none; }
#reportModal.open { display: flex !important; }
</style>
