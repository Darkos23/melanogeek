@extends('cm.layout')

@section('title', 'Commentaires')
@section('page-title', '💬 Commentaires')

@section('content')

<div class="cm-table-wrap">
    <div class="cm-table-header">
        <div class="cm-table-title">Commentaires ({{ $comments->total() }})</div>
        <form method="GET" action="{{ route('cm.comments') }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <input type="text" name="q" class="cm-search" placeholder="Rechercher un commentaire…" value="{{ request('q') }}" style="width:220px;">
            <button type="submit" class="btn-action success">🔍</button>
            @if(request('q'))
                <a href="{{ route('cm.comments') }}" class="btn-action">✕ Reset</a>
            @endif
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Commentaire</th>
                <th>Post</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comments as $comment)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="user-avatar-mini">
                            <div class="user-avatar-mini-inner">
                                @if($comment->user?->avatar)<img src="{{ Storage::url($comment->user->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($comment->user?->name ?? '?', 0, 1)) }}@endif
                            </div>
                        </div>
                        <div>
                            <div style="font-size:.82rem;font-weight:600;">{{ $comment->user?->name ?? '?' }}</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">&#64;{{ $comment->user?->username ?? '?' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="max-width:300px;font-size:.82rem;color:var(--text);line-height:1.5;">
                        {{ Str::limit($comment->body, 100) }}
                    </div>
                </td>
                <td>
                    @if($comment->post)
                        <a href="{{ route('posts.show', $comment->post->id) }}" target="_blank"
                           style="font-size:.78rem;color:var(--cm);text-decoration:none;display:flex;align-items:center;gap:4px;white-space:nowrap;">
                            ↗ {{ Str::limit($comment->post->title ?: '(sans titre)', 30) }}
                        </a>
                    @else
                        <span style="font-size:.78rem;color:var(--text-faint);">Post supprimé</span>
                    @endif
                </td>
                <td style="font-size:.78rem;color:var(--text-muted);white-space:nowrap;">
                    {{ $comment->created_at->format('d/m/Y H:i') }}
                </td>
                <td>
                    <div class="action-row">
                        <form method="POST" action="{{ route('cm.comments.delete', $comment->id) }}"
                              onsubmit="return confirm('Supprimer ce commentaire ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action danger" title="Supprimer">🗑 Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:48px;color:var(--text-muted);">
                    Aucun commentaire trouvé.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($comments->hasPages())
        <div class="pagination-wrap">
            {{ $comments->links() }}
        </div>
    @endif
</div>

@endsection
