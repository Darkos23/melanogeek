@extends('layouts.blog')

@section('title', $thread->title . ' — Forum MelanoGeek')

@php $threadDesc = Str::limit(strip_tags($thread->body ?? ''), 155); @endphp
@section('meta_description', $threadDesc)
@section('og_title', $thread->title . ' — Forum MelanoGeek')
@section('og_description', $threadDesc)
@section('canonical', route('forum.show', $thread))

@push('styles')
<style>
.thread-wrap { max-width: 760px; }

/* Thread post */
.thread-post {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 20px;
}
.thread-post-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    gap: 12px; flex-wrap: wrap;
}
.thread-avi {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    overflow: hidden; display: flex; align-items: center; justify-content: center;
    font-size: .9rem; font-weight: 700; color: white;
}
.thread-avi img { width:100%; height:100%; object-fit:cover; }
.thread-author-name {
    font-family: var(--font-head); font-size: .88rem; font-weight: 700;
    color: var(--text); text-decoration: none;
}
.thread-author-name:hover { color: var(--terra); }
.thread-author-meta { font-size: .7rem; color: var(--text-muted); margin-top: 2px; }
.thread-tag {
    font-size: .54rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    padding: 3px 8px; border-radius: 5px;
    color: rgba(255,255,255,.55); background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.10);
}
.thread-title-h {
    font-family: var(--font-head); font-size: 1.2rem; font-weight: 800;
    letter-spacing: -.02em; color: var(--text); line-height: 1.3;
    padding: 16px 20px 0;
}
.thread-body-text {
    font-size: .9rem; line-height: 1.75; color: var(--text-muted);
    padding: 12px 20px 20px;
    word-break: break-word;
}
.thread-body-text.prose p { margin-bottom: 8px; }
.thread-body-text.prose h2 { font-family: var(--font-head); font-size: 1.1rem; font-weight: 700; color: var(--text); margin: 14px 0 6px; }
.thread-body-text.prose h3 { font-family: var(--font-head); font-size: .95rem; font-weight: 600; color: var(--text); margin: 10px 0 4px; }
.thread-body-text.prose strong { color: var(--text); font-weight: 600; }
.thread-body-text.prose em { color: var(--text-muted); font-style: italic; }
.thread-body-text.prose a { color: var(--terra); text-decoration: underline; text-underline-offset: 3px; }
.thread-body-text.prose blockquote {
    border-left: 3px solid var(--terra);
    padding-left: 14px;
    color: var(--text-faint);
    font-style: italic;
    margin: 10px 0;
}
.thread-body-text.prose code {
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 5px;
    padding: 1px 6px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .82em;
    color: var(--gold);
}
.thread-body-text.prose pre {
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px 16px;
    margin: 10px 0;
    overflow-x: auto;
    font-family: 'JetBrains Mono', monospace;
    font-size: .82em;
    color: var(--gold);
    white-space: pre-wrap;
}
.thread-body-text.prose ul, .thread-body-text.prose ol { padding-left: 20px; margin: 8px 0; }
.thread-body-text.prose li { margin-bottom: 4px; }
.thread-actions-row {
    display: flex; align-items: center; gap: 8px;
    padding: 12px 16px; border-top: 1px solid var(--border);
}
.btn-del-thread {
    background: none; border: none; color: var(--text-muted); font-size: .75rem;
    cursor: pointer; padding: 4px 10px; border-radius: 8px;
    transition: color .2s, background .2s;
}
.btn-del-thread:hover { color: #E05555; background: rgba(224,85,85,.08); }

/* Replies */
.replies-section { margin-bottom: 24px; }
.replies-title {
    font-family: var(--font-head); font-size: .75rem; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--text-muted); margin-bottom: 12px;
}
.reply-item {
    display: flex; gap: 12px;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 14px; padding: 16px 18px; margin-bottom: 8px;
}
.reply-avi {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--terra), var(--gold));
    overflow: hidden; display: flex; align-items: center; justify-content: center;
    font-size: .8rem; font-weight: 700; color: white;
}
.reply-avi img { width:100%; height:100%; object-fit:cover; }
.reply-content { flex: 1; min-width: 0; }
.reply-header { display: flex; align-items: baseline; gap: 8px; margin-bottom: 6px; flex-wrap: wrap; }
.reply-author {
    font-size: .84rem; font-weight: 700; color: var(--text);
    text-decoration: none;
}
.reply-author:hover { color: var(--terra); }
.reply-ago { font-size: .7rem; color: var(--text-muted); }
.reply-body {
    font-size: .875rem; color: var(--text-muted); line-height: 1.6;
    white-space: pre-wrap; word-break: break-word;
}
.btn-del-reply {
    background: none; border: none; color: var(--text-muted); font-size: .7rem;
    cursor: pointer; padding: 2px 6px; border-radius: 6px;
    margin-left: auto; flex-shrink: 0;
    transition: color .2s, background .2s;
}
.btn-del-reply:hover { color: #E05555; background: rgba(224,85,85,.08); }

/* Reply form */
.reply-form-wrap {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 14px; overflow: hidden;
}
.reply-form-head {
    padding: 14px 18px; border-bottom: 1px solid var(--border);
    font-size: .7rem; font-weight: 700; letter-spacing: .07em;
    text-transform: uppercase; color: var(--text-muted);
}
.reply-textarea {
    width: 100%; background: transparent; border: none; outline: none;
    color: var(--text); font-family: var(--font-body); font-size: .9rem;
    line-height: 1.65; padding: 14px 18px; min-height: 100px; resize: none;
}
.reply-textarea::placeholder { color: var(--text-faint); }
.reply-form-actions {
    display: flex; justify-content: flex-end;
    padding: 10px 16px; border-top: 1px solid var(--border);
}
.btn-reply-submit {
    background: var(--terra); border: none; color: white;
    padding: 9px 22px; border-radius: 100px;
    font-family: var(--font-head); font-size: .86rem; font-weight: 700;
    cursor: pointer; transition: background .2s;
}
.btn-reply-submit:hover { background: var(--accent); }
.reply-guest {
    text-align: center; padding: 20px;
    font-size: .84rem; color: var(--text-muted);
}
.reply-guest a { color: var(--terra); text-decoration: none; font-weight: 600; }

@media (max-width: 640px) {
    .thread-wrap { padding: 0; }
    .thread-title-h { font-size: 1rem; padding: 14px 16px 0; }
    .thread-body-text { padding: 10px 16px 16px; font-size: .86rem; }
    .thread-post-head { padding: 12px 16px; }
    .reply-form-wrap { border-radius: 12px; }
    .reply-form-head { padding: 10px 14px; font-size: .8rem; }
    .reply-form-actions { padding: 8px 12px; }
}
</style>
@endpush

@section('main')
<div class="thread-wrap">

<a href="{{ route('forum.index') }}" style="font-size:.82rem;color:var(--text-muted);text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:16px;"><x-icon name="arrow-left" :size="14"/> Forum</a>

@if(session('status') === 'reply-added')
<div style="background:rgba(45,90,61,.12);border:1px solid rgba(45,90,61,.25);color:#6DC48A;padding:12px 18px;border-radius:12px;font-size:.85rem;margin-bottom:16px;">
    <span style="display:inline-flex;align-items:center;gap:6px;"><x-icon name="check-circle" :size="14"/> Réponse ajoutée.</span>
</div>
@endif
@if(session('status') === 'thread-updated')
<div style="background:rgba(45,90,61,.12);border:1px solid rgba(45,90,61,.25);color:#6DC48A;padding:12px 18px;border-radius:12px;font-size:.85rem;margin-bottom:16px;">
    <span style="display:inline-flex;align-items:center;gap:6px;"><x-icon name="check-circle" :size="14"/> Sujet modifié.</span>
</div>
@endif
@if(session('status') === 'reply-updated')
<div style="background:rgba(45,90,61,.12);border:1px solid rgba(45,90,61,.25);color:#6DC48A;padding:12px 18px;border-radius:12px;font-size:.85rem;margin-bottom:16px;">
    <span style="display:inline-flex;align-items:center;gap:6px;"><x-icon name="check-circle" :size="14"/> Réponse modifiée.</span>
</div>
@endif

{{-- Thread principal --}}
<div class="thread-post">
    <div class="thread-post-head">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('profile.show', $thread->user->username) }}" class="thread-avi">
                @if($thread->user->avatar && Storage::disk('public')->exists($thread->user->avatar))
                    <img src="{{ Storage::disk('public')->url($thread->user->avatar) }}" alt="">
                @else
                    {{ mb_strtoupper(mb_substr($thread->user->name, 0, 1)) }}
                @endif
            </a>
            <div>
                <a href="{{ route('profile.show', $thread->user->username) }}" class="thread-author-name">
                    {{ $thread->user->name }}
                </a>
                <div class="thread-author-meta">{{ $thread->created_at->diffForHumans() }}</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            @php
            $catSvgMap = [
                'manga-anime'  => '<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>',
                'gaming'       => '<rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 12h4m-2-2v4"/><circle cx="16" cy="10" r="1.2" fill="currentColor"/><circle cx="18" cy="12" r="1.2" fill="currentColor"/>',
                'tech'         => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
                'culture'      => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
                'cosplay'      => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
                'off-topic'    => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
            ];
            @endphp
            <span class="thread-tag" style="display:inline-flex;align-items:center;gap:5px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $catSvgMap[$thread->category] ?? '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>' !!}</svg> {{ $thread->category_label }}</span>
            @if($thread->is_pinned)
                <span class="thread-tag" style="color:var(--gold);background:var(--gold-soft);border-color:rgba(184,120,32,.2);display:inline-flex;align-items:center;gap:4px;"><x-icon name="pin" :size="11"/> Épinglé</span>
            @endif
        </div>
    </div>

    <div class="thread-title-h">{{ $thread->title }}</div>
    <div class="thread-body-text prose">{!! $thread->body !!}</div>

    <div class="thread-actions-row">
        {{-- Stats visibles par tout le monde --}}
        <span style="font-size:.72rem;color:var(--text-faint);">
            {{ number_format($thread->replies_count) }} réponse{{ $thread->replies_count != 1 ? 's' : '' }}
        </span>

        {{-- Vues — pill dorée visible seulement à l'auteur/admin --}}
        @auth
            @if(auth()->id() === $thread->user_id || auth()->user()->isAdmin())
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:100px;background:rgba(212,168,67,.07);border:1px solid rgba(212,168,67,.18);color:rgba(212,168,67,.75);font-size:.75rem;font-weight:600;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                {{ number_format($thread->views_count) }} vue{{ $thread->views_count != 1 ? 's' : '' }}
            </span>
            <div style="margin-left:auto;display:flex;align-items:center;gap:6px;">
                <button type="button" class="btn-del-thread" onclick="toggleThreadEdit()" style="display:inline-flex;align-items:center;gap:5px;"><x-icon name="edit" :size="13"/> Modifier</button>
                <form method="POST" action="{{ route('forum.thread.destroy', $thread) }}" onsubmit="return confirm('Supprimer ce sujet ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-del-thread" style="display:inline-flex;align-items:center;gap:5px;"><x-icon name="trash" :size="13"/> Supprimer</button>
                </form>
            </div>
            @else
            <span style="font-size:.72rem;color:var(--text-faint);margin-left:auto;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="vertical-align:middle;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                {{ number_format($thread->views_count) }}
            </span>
            @endif
        @else
        <span style="font-size:.72rem;color:var(--text-faint);margin-left:auto;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="vertical-align:middle;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            {{ number_format($thread->views_count) }}
        </span>
        @endauth
    </div>
</div>

{{-- Formulaire d'édition du thread (masqué par défaut) --}}
@auth
@if(auth()->id() === $thread->user_id || auth()->user()->isAdmin())
<div id="threadEditWrap" style="display:none;margin-bottom:20px;">
    <form method="POST" action="{{ route('forum.thread.update', $thread) }}">
        @csrf @method('PATCH')
        <div style="background:var(--bg-card);border:1px solid var(--terra);border-radius:16px;overflow:hidden;">
            <div style="padding:12px 18px;border-bottom:1px solid var(--border);font-size:.7rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--terra);display:flex;align-items:center;gap:6px;">
                <x-icon name="edit" :size="13"/> Modifier le sujet
            </div>
            <div style="padding:14px 18px 10px;">
                <input type="text" name="title" value="{{ old('title', $thread->title) }}"
                       style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.9rem;margin-bottom:10px;box-sizing:border-box;outline:none;"
                       placeholder="Titre du sujet" maxlength="200">
                @error('title')<div style="font-size:.75rem;color:#E05555;margin:-6px 0 8px;">{{ $message }}</div>@enderror
                <textarea name="body" rows="7"
                          style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.9rem;line-height:1.65;resize:vertical;box-sizing:border-box;outline:none;">{{ old('body', $thread->body) }}</textarea>
                @error('body')<div style="font-size:.75rem;color:#E05555;margin:4px 0;">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;padding:10px 16px;border-top:1px solid var(--border);">
                <button type="button" onclick="toggleThreadEdit()"
                        style="background:none;border:1px solid var(--border);color:var(--text-muted);padding:8px 18px;border-radius:100px;font-family:var(--font-head);font-size:.84rem;font-weight:600;cursor:pointer;">
                    Annuler
                </button>
                <button type="submit"
                        style="background:var(--terra);border:none;color:white;padding:8px 22px;border-radius:100px;font-family:var(--font-head);font-size:.86rem;font-weight:700;cursor:pointer;">
                    Sauvegarder
                </button>
            </div>
        </div>
    </form>
</div>
@endif
@endauth

{{-- Réponses --}}
<div class="replies-section" id="replies">
    @if($replies->isNotEmpty())
    <div class="replies-title">{{ number_format($replies->total()) }} réponse{{ $replies->total() > 1 ? 's' : '' }}</div>

    @foreach($replies as $reply)
    <div class="reply-item" id="reply-{{ $reply->id }}">
        <a href="{{ route('profile.show', $reply->user->username) }}" class="reply-avi">
            @if($reply->user->avatar && Storage::disk('public')->exists($reply->user->avatar))
                <img src="{{ Storage::disk('public')->url($reply->user->avatar) }}" alt="">
            @else
                {{ mb_strtoupper(mb_substr($reply->user->name, 0, 1)) }}
            @endif
        </a>
        <div class="reply-content">
            <div class="reply-header">
                <a href="{{ route('profile.show', $reply->user->username) }}" class="reply-author">{{ $reply->user->name }}</a>
                <span class="reply-ago">{{ $reply->created_at->diffForHumans() }}</span>
                @auth
                    @if(auth()->id() === $reply->user_id || auth()->user()->isAdmin())
                    <div style="margin-left:auto;display:flex;align-items:center;gap:4px;">
                        <button type="button" class="btn-del-reply" onclick="toggleReplyEdit({{ $reply->id }})" title="Modifier" style="display:inline-flex;align-items:center;gap:3px;"><x-icon name="edit" :size="11"/></button>
                        <form method="POST" action="{{ route('forum.reply.destroy', $reply) }}" onsubmit="return confirm('Supprimer cette réponse ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del-reply" title="Supprimer"><x-icon name="trash" :size="11"/></button>
                        </form>
                    </div>
                    @endif
                @endauth
            </div>
            {{-- Corps de la réponse --}}
            <div class="reply-body" id="reply-body-{{ $reply->id }}">{{ $reply->body }}</div>
            {{-- Formulaire d'édition inline --}}
            @auth
            @if(auth()->id() === $reply->user_id || auth()->user()->isAdmin())
            <div id="reply-edit-{{ $reply->id }}" style="display:none;margin-top:8px;">
                <form method="POST" action="{{ route('forum.reply.update', $reply) }}">
                    @csrf @method('PATCH')
                    <textarea name="body" rows="4"
                              style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.875rem;line-height:1.6;resize:vertical;box-sizing:border-box;outline:none;">{{ $reply->body }}</textarea>
                    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:8px;">
                        <button type="button" onclick="toggleReplyEdit({{ $reply->id }})"
                                style="background:none;border:1px solid var(--border);color:var(--text-muted);padding:6px 14px;border-radius:100px;font-family:var(--font-head);font-size:.8rem;font-weight:600;cursor:pointer;">
                            Annuler
                        </button>
                        <button type="submit"
                                style="background:var(--terra);border:none;color:white;padding:6px 16px;border-radius:100px;font-family:var(--font-head);font-size:.82rem;font-weight:700;cursor:pointer;">
                            Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
            @endif
            @endauth
        </div>
    </div>
    @endforeach

    {{-- Pagination --}}
    @if($replies->hasPages())
    <div style="display:flex;justify-content:center;gap:6px;margin-top:16px;">
        @if(!$replies->onFirstPage())
            <a href="{{ $replies->previousPageUrl() }}#replies" style="padding:7px 14px;border:1px solid var(--border);border-radius:8px;color:var(--text-muted);text-decoration:none;font-size:.78rem;">‹ Précédent</a>
        @endif
        @if($replies->hasMorePages())
            <a href="{{ $replies->nextPageUrl() }}#replies" style="padding:7px 14px;border:1px solid var(--border);border-radius:8px;color:var(--text-muted);text-decoration:none;font-size:.78rem;">Suivant ›</a>
        @endif
    </div>
    @endif
    @endif
</div>

{{-- Formulaire de réponse --}}
<div class="reply-form-wrap">
    <div class="reply-form-head">Répondre au sujet</div>
    @auth
    <form method="POST" action="{{ route('forum.reply', $thread) }}">
        @csrf
        <textarea name="body" class="reply-textarea"
                  placeholder="Ta réponse…" maxlength="5000">{{ old('body') }}</textarea>
        @error('body')
        <div style="padding:0 18px 8px;font-size:.78rem;color:#E05555;">{{ $message }}</div>
        @enderror
        <div class="reply-form-actions">
            <button type="submit" class="btn-reply-submit">Envoyer la réponse →</button>
        </div>
    </form>
    @else
    <div class="reply-guest">
        <a href="{{ route('login') }}">Connecte-toi</a> pour répondre à ce sujet
    </div>
    @endauth
</div>

</div>

@push('scripts')
<script>
function toggleThreadEdit() {
    const wrap = document.getElementById('threadEditWrap');
    if (!wrap) return;
    const isHidden = wrap.style.display === 'none';
    wrap.style.display = isHidden ? 'block' : 'none';
    if (isHidden) wrap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function toggleReplyEdit(id) {
    const editDiv  = document.getElementById('reply-edit-' + id);
    const bodyDiv  = document.getElementById('reply-body-' + id);
    if (!editDiv) return;
    const isHidden = editDiv.style.display === 'none';
    editDiv.style.display = isHidden ? 'block' : 'none';
    if (bodyDiv) bodyDiv.style.display = isHidden ? 'none' : 'block';
    if (isHidden) {
        const ta = editDiv.querySelector('textarea');
        if (ta) { ta.focus(); ta.setSelectionRange(ta.value.length, ta.value.length); }
    }
}

// Si on revient avec status thread-updated ou reply-updated, scroll vers l'élément
@if(session('status') === 'thread-updated')
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.thread-post')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
});
@endif
</script>
@endpush
@endsection
