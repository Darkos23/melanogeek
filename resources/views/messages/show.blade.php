@extends('layouts.app')

@section('title', isset($user) ? 'Messages — ' . $user->name : 'Messages')

@push('styles')
<style>
    .messages-page {
        padding-top: calc(72px + env(safe-area-inset-top));
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ══ LAYOUT ══ */
    .messages-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        height: calc(100vh - 72px);
        max-width: 1100px;
        margin: 0 auto;
        width: 100%;
        border-left: 1px solid var(--border);
        border-right: 1px solid var(--border);
    }

    /* ══ SIDEBAR ══ */
    .messages-sidebar {
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: var(--bg-card);
    }

    .sidebar-header {
        padding: 20px 16px 14px;
        border-bottom: 1px solid var(--border);
        flex-shrink: 0;
    }

    .sidebar-title {
        font-family: var(--font-head);
        font-size: 1rem;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 12px;
    }

    .sidebar-search {
        width: 100%;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 100px;
        padding: 8px 14px;
        color: var(--text);
        font-family: var(--font-body);
        font-size: .82rem;
        outline: none;
        transition: border-color .2s;
    }

    .sidebar-search::placeholder { color: var(--text-muted); }
    .sidebar-search:focus { border-color: var(--terra); }

    .sidebar-conversations {
        flex: 1;
        overflow-y: auto;
        padding: 8px 0;
    }

    .conv-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        cursor: pointer;
        text-decoration: none;
        transition: background .15s;
        border-radius: 0;
        position: relative;
    }

    .conv-item:hover { background: var(--bg-hover); }
    .conv-item.active { background: var(--terra-soft); }
    .conv-item.active::before {
        content: '';
        position: absolute;
        left: 0; top: 10%; bottom: 10%;
        width: 3px;
        background: var(--terra);
        border-radius: 0 4px 4px 0;
    }

    .conv-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
        overflow: hidden;
        font-family: var(--font-head);
        position: relative;
    }

    .conv-avatar img {
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .conv-avatar-online {
        position: absolute;
        bottom: 2px; right: 2px;
        width: 10px; height: 10px;
        background: #22C55E;
        border: 2px solid var(--bg-card);
        border-radius: 50%;
    }

    .conv-info { flex: 1; min-width: 0; }

    .conv-name {
        font-size: .88rem;
        font-weight: 700;
        color: var(--text);
        font-family: var(--font-head);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-last {
        font-size: .76rem;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 2px;
    }

    .conv-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        flex-shrink: 0;
    }

    .conv-time {
        font-size: .68rem;
        color: var(--text-muted);
    }

    .conv-unread {
        background: var(--terra);
        color: white;
        font-size: .65rem;
        font-weight: 700;
        width: 18px; height: 18px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }

    .sidebar-empty {
        padding: 40px 20px;
        text-align: center;
        color: var(--text-muted);
        font-size: .84rem;
    }

    .sidebar-empty-icon { font-size: 2rem; margin-bottom: 10px; }

    /* ══ CHAT AREA ══ */
    .chat-area {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        background: var(--bg);
    }

    /* Chat header */
    .chat-header {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--bg-card);
        flex-shrink: 0;
    }

    .chat-header-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        font-weight: 700;
        color: white;
        overflow: hidden;
        font-family: var(--font-head);
        flex-shrink: 0;
    }

    .chat-header-avatar img {
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .chat-header-info { flex: 1; }

    .chat-header-name {
        font-family: var(--font-head);
        font-size: .95rem;
        font-weight: 700;
        color: var(--text);
    }

    .chat-header-sub {
        font-size: .72rem;
        color: var(--text-muted);
    }

    .chat-header-actions { display: flex; gap: 8px; }

    .chat-header-btn {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        color: var(--text-muted);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }

    .chat-header-btn:hover {
        border-color: var(--terra);
        color: var(--terra);
        background: var(--terra-soft);
    }

    /* Messages */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .chat-date-sep {
        text-align: center;
        font-size: .68rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin: 12px 0 4px;
    }

    .msg-row {
        display: flex;
        align-items: flex-end;
        gap: 8px;
    }

    .msg-row.mine { flex-direction: row-reverse; }

    .msg-bubble-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        display: flex; align-items: center; justify-content: center;
        font-size: .72rem;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
        overflow: hidden;
        font-family: var(--font-head);
        margin-bottom: 2px;
    }

    .msg-bubble-avatar img {
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .msg-bubble {
        max-width: 60%;
        padding: 10px 14px;
        border-radius: 18px;
        font-size: .88rem;
        line-height: 1.5;
        word-break: break-word;
        position: relative;
    }

    /* Leur message */
    .msg-row:not(.mine) .msg-bubble {
        background: var(--bg-card);
        color: var(--text);
        border: 1px solid var(--border);
        border-bottom-left-radius: 4px;
    }

    /* Mon message */
    .msg-row.mine .msg-bubble {
        background: var(--terra);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .msg-time {
        font-size: .64rem;
        color: var(--text-muted);
        margin-top: 3px;
        padding: 0 4px;
        white-space: nowrap;
        flex-shrink: 0;
        align-self: flex-end;
    }

    .msg-row.mine .msg-time { text-align: right; }

    /* ── Empty state ── */
    .chat-empty {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--text-muted);
        padding: 40px;
    }

    .chat-empty-icon { font-size: 3rem; margin-bottom: 16px; }

    .chat-empty-title {
        font-family: var(--font-head);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }

    .chat-empty-desc {
        font-size: .86rem;
        line-height: 1.6;
        max-width: 320px;
    }

    /* Chat input */
    .chat-input-area {
        padding: 14px 16px;
        border-top: 1px solid var(--border);
        background: var(--bg-card);
        flex-shrink: 0;
    }

    .chat-input-form {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 100px;
        padding: 8px 8px 8px 16px;
        transition: border-color .2s;
    }

    .chat-input-form:focus-within { border-color: var(--terra); }

    .chat-input {
        flex: 1;
        background: none;
        border: none;
        outline: none;
        color: var(--text);
        font-family: var(--font-body);
        font-size: .9rem;
        resize: none;
        max-height: 120px;
        line-height: 1.5;
    }

    .chat-input::placeholder { color: var(--text-muted); }

    .chat-send-btn {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: var(--terra);
        border: none;
        color: white;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .2s, transform .15s;
        flex-shrink: 0;
    }

    .chat-send-btn:hover { background: var(--accent); transform: scale(1.05); }
    .chat-send-btn:disabled { opacity: .4; cursor: default; transform: none; }

    /* ── No conversation selected ── */
    .chat-placeholder {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        padding: 40px;
        text-align: center;
    }

    .chat-placeholder-icon { font-size: 3.5rem; margin-bottom: 20px; opacity: .5; }
    .chat-placeholder-title {
        font-family: var(--font-head);
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }

    /* ══ BOUTON COMPOSER ══ */
    .sidebar-compose-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px; height: 32px;
        border-radius: 50%;
        background: var(--terra-soft);
        border: 1px solid rgba(200,82,42,.25);
        color: var(--terra);
        cursor: pointer;
        transition: all .2s;
        flex-shrink: 0;
    }
    .sidebar-compose-btn:hover { background: var(--terra); color: white; }

    .sidebar-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    /* ══ MODAL NOUVEAU MESSAGE ══ */
    .new-msg-overlay {
        position: fixed; inset: 0; z-index: 800;
        background: rgba(0,0,0,.6);
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(4px);
    }
    .new-msg-modal {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        width: 400px; max-width: 95vw;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }
    .new-msg-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 20px 14px;
        border-bottom: 1px solid var(--border);
    }
    .new-msg-title {
        font-family: var(--font-head);
        font-size: .92rem; font-weight: 800; color: var(--text);
    }
    .new-msg-close {
        background: none; border: none; color: var(--text-muted);
        font-size: 1rem; cursor: pointer; transition: color .2s; padding: 4px;
    }
    .new-msg-close:hover { color: var(--text); }
    .new-msg-search-wrap {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
    }
    .new-msg-search {
        width: 100%;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 100px;
        padding: 9px 16px;
        color: var(--text);
        font-family: var(--font-body);
        font-size: .84rem;
        outline: none;
        transition: border-color .2s;
    }
    .new-msg-search:focus { border-color: var(--terra); }
    .new-msg-search::placeholder { color: var(--text-muted); }
    .new-msg-results {
        max-height: 280px;
        overflow-y: auto;
    }
    .new-msg-result {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 20px;
        cursor: pointer;
        transition: background .15s;
        text-decoration: none;
        color: var(--text);
        border-bottom: 1px solid var(--border);
    }
    .new-msg-result:last-child { border-bottom: none; }
    .new-msg-result:hover { background: var(--bg-hover); }
    .new-msg-avi {
        width: 38px; height: 38px; border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        display: flex; align-items: center; justify-content: center;
        font-size: .82rem; font-weight: 700; color: white;
        flex-shrink: 0; overflow: hidden;
    }
    .new-msg-avi img { width: 100%; height: 100%; object-fit: cover; }
    .new-msg-name { font-size: .86rem; font-weight: 700; color: var(--text); }
    .new-msg-handle { font-size: .74rem; color: var(--text-muted); }
    .new-msg-hint {
        padding: 24px 20px;
        text-align: center;
        font-size: .82rem;
        color: var(--text-muted);
    }

    /* ══ MOBILE ══ */
    .mobile-back-btn { display: none; }

    @media (max-width: 768px) {
        .messages-layout {
            grid-template-columns: 1fr;
        }
        .messages-sidebar {
            display: {{ isset($user) ? 'none' : 'flex' }};
        }
        .mobile-back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--bg-card2);
            border: 1px solid var(--border);
            color: var(--text-muted);
            text-decoration: none;
            font-size: .9rem;
            flex-shrink: 0;
            margin-right: 4px;
        }
        .mobile-back-btn:hover { border-color: var(--border-hover); color: var(--text); }
    }
</style>
@endpush

@section('content')
<div class="messages-page">
<div class="messages-layout">

    {{-- ══ SIDEBAR ══ --}}
    <aside class="messages-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-header-row">
                <div class="sidebar-title">💬 Messages</div>
                <button class="sidebar-compose-btn" onclick="openNewMsg()" title="Nouvelle conversation" type="button">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                </button>
            </div>
            <input type="text" class="sidebar-search" placeholder="Rechercher une conversation…" id="searchConv">
        </div>

        <div class="sidebar-conversations" id="convList">
            @forelse($conversations as $conv)
                <a href="{{ route('messages.show', $conv->partner->username) }}"
                   class="conv-item {{ isset($user) && $user->id === $conv->partner->id ? 'active' : '' }}">

                    <div class="conv-avatar">
                        @if($conv->partner->avatar)
                            <img src="{{ Storage::url($conv->partner->avatar) }}" alt="{{ $conv->partner->name }}">
                        @else
                            {{ mb_strtoupper(mb_substr($conv->partner->name, 0, 1)) }}
                        @endif
                    </div>

                    <div class="conv-info">
                        <div class="conv-name">
                            {{ $conv->partner->name }}
                            @if($conv->partner->is_verified)
                                <svg width="12" height="12" viewBox="0 0 24 24" style="vertical-align:middle;"><circle cx="12" cy="12" r="12" fill="#3897F0"/><path d="M7 12.3L10.4 15.8L17 8.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
                            @endif
                        </div>
                        <div class="conv-last">
                            @if($conv->last_message->sender_id === auth()->id())
                                <span style="color:var(--text-faint);">Vous : </span>
                            @endif
                            {{ Str::limit($conv->last_message->body, 36) }}
                        </div>
                    </div>

                    <div class="conv-meta">
                        <div class="conv-time">
                            {{ $conv->last_message->created_at->diffForHumans(null, true) }}
                        </div>
                        @if($conv->unread > 0)
                            <div class="conv-unread">{{ $conv->unread }}</div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="sidebar-empty">
                    <div class="sidebar-empty-icon">💬</div>
                    Aucune conversation pour l'instant.
                </div>
            @endforelse
        </div>
    </aside>

    {{-- ══ ZONE CHAT ══ --}}
    <div class="chat-area">

        @if(isset($user))

            {{-- Header ──────────────────────────────── --}}
            <div class="chat-header">
                {{-- Retour liste sur mobile --}}
                <a href="{{ route('messages.index') }}" class="mobile-back-btn" title="Retour">←</a>
                <a href="{{ route('profile.show', $user->username) }}" class="chat-header-avatar" style="text-decoration:none;">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                    @else
                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                    @endif
                </a>
                <div class="chat-header-info">
                    <div class="chat-header-name">
                        {{ $user->name }}
                        @if($user->is_verified)
                            <svg width="14" height="14" viewBox="0 0 24 24" style="vertical-align:middle;"><circle cx="12" cy="12" r="12" fill="#3897F0"/><path d="M7 12.3L10.4 15.8L17 8.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
                        @endif
                    </div>
                    <div class="chat-header-sub">&#64;{{ $user->username }}</div>
                </div>
                <div class="chat-header-actions">
                    <a href="{{ route('profile.show', $user->username) }}" class="chat-header-btn" title="Voir le profil">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                </div>
            </div>

            {{-- Messages ──────────────────────────────── --}}
            <div class="chat-messages" id="chatMessages">

                @php $lastDate = null; @endphp

                @forelse($messages as $msg)
                    @php
                        $msgDate = $msg->created_at->format('Y-m-d');
                        $isMine  = $msg->sender_id === auth()->id();
                    @endphp

                    @if($msgDate !== $lastDate)
                        <div class="chat-date-sep">
                            {{ $msg->created_at->isToday() ? "Aujourd'hui" : ($msg->created_at->isYesterday() ? 'Hier' : $msg->created_at->translatedFormat('d F Y')) }}
                        </div>
                        @php $lastDate = $msgDate; @endphp
                    @endif

                    <div class="msg-row {{ $isMine ? 'mine' : '' }}" data-msg-id="{{ $msg->id }}">
                        @if(!$isMine)
                            <div class="msg-bubble-avatar">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                        @endif

                        <div class="msg-bubble">{{ $msg->body }}</div>
                        <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                @empty
                    <div class="chat-empty">
                        <div class="chat-empty-icon">👋</div>
                        <div class="chat-empty-title">Démarrez la conversation</div>
                        <div class="chat-empty-desc">
                            Envoie ton premier message à {{ $user->name }} !
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Input ──────────────────────────────── --}}
            <div class="chat-input-area">
                <div class="chat-input-form">
                    <textarea
                        class="chat-input"
                        id="msgInput"
                        placeholder="Écrire un message…"
                        rows="1"
                        maxlength="2000"
                    ></textarea>
                    <button class="chat-send-btn" id="sendBtn" disabled title="Envoyer">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </div>
            </div>

        @else

            {{-- Pas de conversation sélectionnée ──────── --}}
            <div class="chat-placeholder">
                <div class="chat-placeholder-icon">💬</div>
                <div class="chat-placeholder-title">Tes messages</div>
                <p style="font-size:.86rem;line-height:1.6;max-width:300px;">
                    Envoie des messages privés aux créateurs et membres de MelanoGeek.
                </p>
            </div>

        @endif

    </div>
</div>
</div>

{{-- ══ MODAL NOUVEAU MESSAGE ══ --}}
<div class="new-msg-overlay" id="newMsgOverlay" style="display:none;" onclick="if(event.target===this)closeNewMsg()">
    <div class="new-msg-modal">
        <div class="new-msg-head">
            <span class="new-msg-title">Nouvelle conversation</span>
            <button class="new-msg-close" onclick="closeNewMsg()" type="button">✕</button>
        </div>
        <div class="new-msg-search-wrap">
            <input type="text" class="new-msg-search" id="newMsgSearch"
                   placeholder="Rechercher un utilisateur…" autocomplete="off">
        </div>
        <div class="new-msg-results" id="newMsgResults">
            <div class="new-msg-hint">Tape un nom ou un @username pour trouver quelqu'un</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
@if(isset($user))
(function () {
    const chatMessages = document.getElementById('chatMessages');
    const msgInput     = document.getElementById('msgInput');
    const sendBtn      = document.getElementById('sendBtn');
    const partnerUsername = '{{ $user->username }}';
    const myName      = '{{ auth()->user()->name }}';
    const partnerName = '{{ $user->name }}';
    const partnerAvatar = '{{ $user->avatar ? Storage::url($user->avatar) : '' }}';

    // Scroller en bas au chargement
    function scrollBottom(smooth = false) {
        chatMessages.scrollTo({
            top: chatMessages.scrollHeight,
            behavior: smooth ? 'smooth' : 'instant'
        });
    }
    scrollBottom();

    // Activer/désactiver le bouton envoyer
    msgInput.addEventListener('input', () => {
        sendBtn.disabled = msgInput.value.trim().length === 0;
        // Auto-resize
        msgInput.style.height = 'auto';
        msgInput.style.height = Math.min(msgInput.scrollHeight, 120) + 'px';
    });

    // Envoyer avec Entrée (Shift+Entrée = saut de ligne)
    msgInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (!sendBtn.disabled) sendMessage();
        }
    });

    sendBtn.addEventListener('click', sendMessage);

    function sendMessage() {
        const body = msgInput.value.trim();
        if (!body) return;

        sendBtn.disabled = true;
        msgInput.value   = '';
        msgInput.style.height = 'auto';

        // Affichage optimiste
        appendMessage({ body, is_mine: true, created_at: new Date().toISOString(), id: null });
        scrollBottom(true);

        // Retirer l'état vide si présent
        const emptyState = chatMessages.querySelector('.chat-empty');
        if (emptyState) emptyState.remove();

        fetch(`/messages/${partnerUsername}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ body }),
        })
        .then(r => r.ok ? r.json() : Promise.reject(r))
        .catch(() => {
            // Erreur silencieuse — le message optimiste reste affiché
        });
    }

    function appendMessage(msg) {
        const now = new Date(msg.created_at);
        const timeStr = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');

        const row = document.createElement('div');
        row.className = 'msg-row' + (msg.is_mine ? ' mine' : '');

        if (!msg.is_mine) {
            const av = document.createElement('div');
            av.className = 'msg-bubble-avatar';
            if (partnerAvatar) {
                av.innerHTML = `<img src="${partnerAvatar}" alt="${partnerName}">`;
            } else {
                av.textContent = partnerName.charAt(0).toUpperCase();
            }
            row.appendChild(av);
        }

        const bubble = document.createElement('div');
        bubble.className = 'msg-bubble';
        bubble.textContent = msg.body;
        row.appendChild(bubble);

        const time = document.createElement('div');
        time.className = 'msg-time';
        time.textContent = timeStr;
        row.appendChild(time);

        chatMessages.appendChild(row);
    }

    // Polling toutes les 5 secondes pour les nouveaux messages
    let lastMsgId = {{ $messages->last()?->id ?? 0 }};

    setInterval(() => {
        fetch(`/messages/${partnerUsername}/poll?after=${lastMsgId}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.ok ? r.json() : null)
        .then(data => {
            if (!data || !data.messages || data.messages.length === 0) return;
            data.messages.forEach(m => {
                appendMessage(m);
                lastMsgId = Math.max(lastMsgId, m.id);
            });
            scrollBottom(true);
        })
        .catch(() => {});
    }, 5000);

})();
@endif

// Recherche dans la sidebar
document.getElementById('searchConv')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.conv-item').forEach(el => {
        const name = el.querySelector('.conv-name')?.textContent.toLowerCase() ?? '';
        el.style.display = name.includes(q) ? '' : 'none';
    });
});

/* ── Modal Nouveau message ── */
function openNewMsg() {
    document.getElementById('newMsgOverlay').style.display = 'flex';
    setTimeout(() => document.getElementById('newMsgSearch')?.focus(), 80);
}
function closeNewMsg() {
    document.getElementById('newMsgOverlay').style.display = 'none';
    document.getElementById('newMsgSearch').value = '';
    document.getElementById('newMsgResults').innerHTML = '<div class="new-msg-hint">Tape un nom ou un @username pour trouver quelqu\'un</div>';
}

let newMsgTimer = null;
document.getElementById('newMsgSearch')?.addEventListener('input', function () {
    clearTimeout(newMsgTimer);
    const q = this.value.trim();
    if (q.length < 2) {
        document.getElementById('newMsgResults').innerHTML = '<div class="new-msg-hint">Tape au moins 2 caractères…</div>';
        return;
    }
    document.getElementById('newMsgResults').innerHTML = '<div class="new-msg-hint">Recherche…</div>';
    newMsgTimer = setTimeout(() => searchUsers(q), 300);
});

async function searchUsers(q) {
    try {
        const res   = await fetch(`/messages/search-users?q=${encodeURIComponent(q)}`, {
            headers: { 'Accept': 'application/json' }
        });
        const users = await res.json();
        const el    = document.getElementById('newMsgResults');

        if (!users.length) {
            el.innerHTML = '<div class="new-msg-hint">Aucun utilisateur trouvé.</div>';
            return;
        }

        el.innerHTML = users.map(u => {
            const avi = u.avatar
                ? `<img src="${u.avatar}" alt="">`
                : `<span>${(u.name || '?')[0].toUpperCase()}</span>`;
            return `<a href="/messages/${u.username}" class="new-msg-result" onclick="closeNewMsg()">
                <div class="new-msg-avi">${avi}</div>
                <div>
                    <div class="new-msg-name">${u.name}</div>
                    <div class="new-msg-handle">@${u.username}</div>
                </div>
            </a>`;
        }).join('');
    } catch (e) {
        document.getElementById('newMsgResults').innerHTML = '<div class="new-msg-hint">Erreur de chargement.</div>';
    }
}

// Fermer avec Échap
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeNewMsg();
});
</script>
@endpush
