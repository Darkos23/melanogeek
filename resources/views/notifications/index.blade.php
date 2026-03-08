@extends('layouts.app')

@section('title', 'Notifications — MelanoGeek')

@push('styles')
<style>
    .notif-page {
        padding-top: 72px;
        min-height: 100vh;
    }

    /* ══ LAYOUT ══ */
    .notif-layout {
        max-width: 680px;
        margin: 0 auto;
        padding: 0;
        border-left: 1px solid var(--border);
        border-right: 1px solid var(--border);
        min-height: calc(100vh - 72px);
        background: var(--bg-card);
    }

    /* ══ HEADER ══ */
    .notif-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 72px;
        background: var(--bg-card);
        z-index: 10;
        backdrop-filter: blur(10px);
    }

    .notif-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .notif-title {
        font-family: var(--font-head);
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--text);
    }

    .notif-badge {
        background: var(--terra);
        color: white;
        font-size: .68rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 100px;
        line-height: 1.4;
    }

    .notif-read-all {
        background: none;
        border: 1px solid var(--border);
        color: var(--text-muted);
        font-family: var(--font-body);
        font-size: .75rem;
        padding: 6px 14px;
        border-radius: 100px;
        cursor: pointer;
        transition: all .2s;
    }

    .notif-read-all:hover {
        border-color: var(--terra);
        color: var(--terra);
    }

    /* ══ LISTE ══ */
    .notif-list {
        padding: 0;
    }

    /* ══ ITEM ══ */
    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 24px;
        border-bottom: 1px solid var(--border);
        transition: background .15s;
        cursor: pointer;
        text-decoration: none;
        color: var(--text);
        position: relative;
    }

    .notif-item:hover {
        background: var(--bg-hover);
    }

    .notif-item.unread {
        background: var(--terra-soft);
    }

    .notif-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--terra);
        border-radius: 0 2px 2px 0;
    }

    /* Avatar */
    .notif-avatar {
        position: relative;
        flex-shrink: 0;
    }

    .notif-avi {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        font-weight: 700;
        color: white;
        overflow: hidden;
        flex-shrink: 0;
    }

    .notif-avi img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .notif-type-icon {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .65rem;
        border: 2px solid var(--bg-card);
    }

    .notif-type-icon.follow  { background: var(--terra); }
    .notif-type-icon.like    { background: #e85a8c; }
    .notif-type-icon.comment { background: var(--gold); }

    /* Contenu */
    .notif-content {
        flex: 1;
        min-width: 0;
    }

    .notif-text {
        font-size: .875rem;
        color: var(--text);
        line-height: 1.45;
        margin-bottom: 4px;
    }

    .notif-text strong {
        font-weight: 700;
        color: var(--text);
    }

    .notif-excerpt {
        font-size: .8rem;
        color: var(--text-muted);
        background: var(--bg-card2);
        border-left: 2px solid var(--border);
        padding: 4px 10px;
        border-radius: 0 6px 6px 0;
        margin-top: 6px;
        font-style: italic;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .notif-time {
        font-size: .72rem;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Miniature post */
    .notif-post-thumb {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        border: 1px solid var(--border);
    }

    /* ══ VIDE ══ */
    .notif-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 80px 24px;
        text-align: center;
        gap: 16px;
    }

    .notif-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--bg-card2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .notif-empty-title {
        font-family: var(--font-head);
        font-size: 1rem;
        font-weight: 700;
        color: var(--text);
    }

    .notif-empty-sub {
        font-size: .84rem;
        color: var(--text-muted);
        max-width: 300px;
    }

    /* ══ PAGINATION ══ */
    .notif-pagination {
        padding: 20px 24px;
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .notif-pagination a,
    .notif-pagination span {
        font-size: .78rem;
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
        color: var(--text-muted);
        text-decoration: none;
        transition: all .2s;
    }

    .notif-pagination a:hover {
        border-color: var(--terra);
        color: var(--terra);
    }

    .notif-pagination span.active {
        background: var(--terra);
        border-color: var(--terra);
        color: white;
        font-weight: 700;
    }

    /* ══ TABS (filtre) ══ */
    .notif-tabs {
        display: flex;
        border-bottom: 1px solid var(--border);
        padding: 0 24px;
    }

    .notif-tab {
        padding: 12px 16px;
        font-size: .78rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all .2s;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
        font-family: var(--font-body);
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .notif-tab.active {
        color: var(--terra);
        border-bottom-color: var(--terra);
    }

    .notif-tab:hover:not(.active) {
        color: var(--text);
    }

    @media (max-width: 700px) {
        .notif-item { padding: 14px 16px; }
        .notif-header { padding: 14px 16px 12px; }
        .notif-tabs { padding: 0 16px; }
    }
</style>
@endpush

@section('content')
<div class="notif-page">
<div class="notif-layout">

    {{-- ── HEADER ── --}}
    <div class="notif-header">
        <div class="notif-header-left">
            <h1 class="notif-title">Notifications</h1>
            @php
                $unreadCount = auth()->user()->unreadNotifications()->count();
            @endphp
            @if($unreadCount > 0)
                <span class="notif-badge" id="unreadBadge">{{ $unreadCount }}</span>
            @endif
        </div>
        @if($notifications->isNotEmpty())
            <button class="notif-read-all" id="btnReadAll">
                Tout marquer lu
            </button>
        @endif
    </div>

    {{-- ── TABS ── --}}
    <div class="notif-tabs">
        <button class="notif-tab active" data-filter="all">Tout</button>
        <button class="notif-tab" data-filter="follow">Abonnements</button>
        <button class="notif-tab" data-filter="like">J'aimes</button>
        <button class="notif-tab" data-filter="comment">Commentaires</button>
    </div>

    {{-- ── LISTE ── --}}
    <div class="notif-list" id="notifList">

        @forelse($notifications as $notif)
            @php
                $data     = $notif->data;
                $type     = $data['type'] ?? 'unknown';
                $isUnread = is_null($notif->read_at);

                // Avatar
                $avatarPath = $data['avatar'] ?? null;
                $avatarUrl  = $avatarPath ? \Storage::url($avatarPath) : null;
                $initials   = mb_strtoupper(mb_substr($data['name'] ?? '?', 0, 1));

                // Liens
                $profileUrl  = route('profile.show', $data['username'] ?? '#');
                $postUrl     = isset($data['post_id']) ? route('posts.show', $data['post_id']) : null;
                $targetUrl   = $postUrl ?? $profileUrl;
            @endphp

            <a href="{{ $targetUrl }}"
               class="notif-item {{ $isUnread ? 'unread' : '' }}"
               data-type="{{ $type }}"
               data-id="{{ $notif->id }}">

                {{-- Avatar + icône type --}}
                <div class="notif-avatar">
                    <div class="notif-avi">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $data['name'] ?? '' }}">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div class="notif-type-icon {{ $type }}">
                        @if($type === 'follow') 👤
                        @elseif($type === 'like') ❤️
                        @elseif($type === 'comment') 💬
                        @else 🔔
                        @endif
                    </div>
                </div>

                {{-- Texte --}}
                <div class="notif-content">
                    <div class="notif-text">
                        @if($type === 'follow')
                            <strong>{{ $data['name'] ?? $data['username'] ?? 'Quelqu\'un' }}</strong>
                            a commencé à te suivre.
                        @elseif($type === 'like')
                            <strong>{{ $data['name'] ?? $data['username'] ?? 'Quelqu\'un' }}</strong>
                            a aimé ta publication
                            @if(!empty($data['post_title']))
                                <em>« {{ \Str::limit($data['post_title'], 40) }} »</em>
                            @endif.
                        @elseif($type === 'comment')
                            <strong>{{ $data['name'] ?? $data['username'] ?? 'Quelqu\'un' }}</strong>
                            a commenté ta publication
                            @if(!empty($data['post_title']))
                                <em>« {{ \Str::limit($data['post_title'], 40) }} »</em>
                            @endif.
                        @else
                            Nouvelle notification.
                        @endif
                    </div>

                    {{-- Extrait commentaire --}}
                    @if($type === 'comment' && !empty($data['comment_body']))
                        <div class="notif-excerpt">{{ $data['comment_body'] }}</div>
                    @endif

                    <div class="notif-time">
                        {{ $notif->created_at->diffForHumans() }}
                    </div>
                </div>

                {{-- Miniature post si image disponible --}}
                {{-- (optionnel – on n'a pas encore chargé les posts ici) --}}

            </a>
        @empty
            {{-- Vide --}}
            <div class="notif-empty">
                <div class="notif-empty-icon">🔔</div>
                <div class="notif-empty-title">Aucune notification</div>
                <div class="notif-empty-sub">
                    Quand quelqu'un te suit, aime ou commente tes publications, tu le verras ici.
                </div>
            </div>
        @endforelse

    </div>

    {{-- ── PAGINATION ── --}}
    @if($notifications->hasPages())
        <div class="notif-pagination">
            {{-- Précédent --}}
            @if($notifications->onFirstPage())
                <span>←</span>
            @else
                <a href="{{ $notifications->previousPageUrl() }}">←</a>
            @endif

            {{-- Pages --}}
            @foreach(range(1, $notifications->lastPage()) as $page)
                @if($page == $notifications->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $notifications->url($page) }}">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Suivant --}}
            @if($notifications->hasMorePages())
                <a href="{{ $notifications->nextPageUrl() }}">→</a>
            @else
                <span>→</span>
            @endif
        </div>
    @endif

</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // ── Tabs (filtre côté client) ──
    const tabs     = document.querySelectorAll('.notif-tab');
    const items    = document.querySelectorAll('.notif-item');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.dataset.filter;
            items.forEach(item => {
                if (filter === 'all' || item.dataset.type === filter) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // ── Tout marquer lu ──
    const btnReadAll = document.getElementById('btnReadAll');
    if (btnReadAll) {
        btnReadAll.addEventListener('click', async () => {
            try {
                await fetch('{{ route('notifications.read-all') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                // Supprimer la classe "unread" de tous les items
                document.querySelectorAll('.notif-item.unread').forEach(el => {
                    el.classList.remove('unread');
                });

                // Masquer le badge
                const badge = document.getElementById('unreadBadge');
                if (badge) badge.remove();

                // Masquer le bouton
                btnReadAll.style.display = 'none';

            } catch (e) {
                console.error(e);
            }
        });
    }

    // ── Marquer comme lu au clic sur une notif ──
    items.forEach(item => {
        item.addEventListener('click', async (e) => {
            if (!item.classList.contains('unread')) return;

            const id = item.dataset.id;
            try {
                await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                item.classList.remove('unread');
            } catch (e) {
                // silencieux
            }
        });
    });

})();
</script>
@endpush
