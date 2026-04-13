@extends('cm.layout')

@section('title', 'Publications')
@section('page-title', '📝 Publications')

@section('content')

<div class="cm-table-wrap">
    <div class="cm-table-header">
        <div class="cm-table-title">Publications ({{ $posts->total() }})</div>
        <form method="GET" action="{{ route('cm.posts') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            <input type="text" name="q" class="cm-search" placeholder="Rechercher…" value="{{ request('q') }}" style="width:200px;">
            <select name="filter" class="cm-select" onchange="this.form.submit()">
                <option value="">Tous</option>
                <option value="deleted"     {{ request('filter') === 'deleted'     ? 'selected' : '' }}>Supprimés</option>
                <option value="exclusive"   {{ request('filter') === 'exclusive'   ? 'selected' : '' }}>Exclusifs</option>
                <option value="unpublished" {{ request('filter') === 'unpublished' ? 'selected' : '' }}>Non publiés</option>
            </select>
            <button type="submit" class="btn-action success">🔍</button>
            @if(request('q') || request('filter'))
                <a href="{{ route('cm.posts') }}" class="btn-action">✕ Reset</a>
            @endif
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Publication</th>
                <th>Auteur</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr class="{{ $post->trashed() ? 'opacity-50' : '' }}" style="{{ $post->trashed() ? 'opacity:.5;' : '' }}">
                <td>
                    <div style="max-width:280px;">
                        <div style="font-weight:600;font-size:.84rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $post->title ?: '(sans titre)' }}
                        </div>
                        @if($post->body)
                            <div style="font-size:.73rem;color:var(--text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ Str::limit(strip_tags($post->body), 60) }}
                            </div>
                        @endif
                    </div>
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="user-avatar-mini">
                            <div class="user-avatar-mini-inner">
                                @if($post->user?->avatar)<img src="{{ Storage::url($post->user->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($post->user?->name ?? '?', 0, 1)) }}@endif
                            </div>
                        </div>
                        <div>
                            <div style="font-size:.82rem;font-weight:600;">{{ $post->user?->name ?? '?' }}</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">&#64;{{ $post->user?->username ?? '?' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($post->trashed())
                        <span class="badge badge-red">🗑 Supprimé</span>
                    @elseif(!$post->is_published)
                        <span class="badge badge-gray">⏸ Brouillon</span>
                    @else
                        <span class="badge badge-cm">✓ Publié</span>
                    @endif
                </td>
                <td style="font-size:.78rem;color:var(--text-muted);white-space:nowrap;">
                    {{ $post->created_at->format('d/m/Y H:i') }}
                </td>
                <td>
                    <div class="action-row">
                        @if(!$post->trashed())
                            <a href="{{ route('posts.show', $post->id) }}" class="btn-action" target="_blank" title="Voir">👁</a>
                            <form method="POST" action="{{ route('cm.posts.delete', $post->id) }}" onsubmit="return confirm('Supprimer cette publication ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action danger" title="Supprimer">🗑</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('cm.posts.restore', $post->id) }}">
                                @csrf
                                <button type="submit" class="btn-action success" title="Restaurer">↩ Restaurer</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:48px;color:var(--text-muted);">
                    Aucune publication trouvée.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($posts->hasPages())
        <div class="pagination-wrap">
            {{ $posts->links() }}
        </div>
    @endif
</div>

@endsection
