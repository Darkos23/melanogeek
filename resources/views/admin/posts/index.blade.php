@extends('admin.layout')

@section('title', 'Publications')
@section('page-title', 'Publications')

@section('content')

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <div class="admin-table-title">📝 {{ $posts->total() }} publications</div>
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
            <input type="text" name="search" class="admin-search" placeholder="Rechercher..." value="{{ request('search') }}">
            <select name="published" class="admin-search">
                <option value="">Tous</option>
                <option value="1" {{ request('published') === '1' ? 'selected' : '' }}>Publiées</option>
                <option value="0" {{ request('published') === '0' ? 'selected' : '' }}>Brouillons</option>
            </select>
            <button type="submit" style="background:var(--terra);border:none;color:white;padding:8px 16px;border-radius:10px;font-size:.8rem;font-weight:600;cursor:pointer;">Filtrer</button>
            @if(request('search') || request()->has('published'))
                <a href="{{ route('admin.posts') }}" style="padding:8px 12px;border-radius:10px;border:1px solid var(--border);color:var(--text-muted);font-size:.8rem;text-decoration:none;">✕</a>
            @endif
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Publication</th>
                <th>Auteur</th>
                <th>Type</th>
                <th>Likes</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr style="{{ $post->trashed() ? 'opacity:.4;' : '' }}">
                <td style="max-width:240px;">
                    <div style="font-weight:500;font-size:.84rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $post->title ?: Str::limit($post->body, 50) ?: '(média seul)' }}
                    </div>
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="user-avatar-mini">
                            <div class="user-avatar-mini-inner">
                                @if($post->user->avatar)<img src="{{ Storage::url($post->user->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($post->user->name,0,1)) }}@endif
                            </div>
                        </div>
                        <span style="font-size:.8rem;">&#64;{{ $post->user->username }}</span>
                    </div>
                </td>
                <td>
                    @if($post->media_type === 'image')  <span class="badge badge-gray">🖼 Image</span>
                    @elseif($post->media_type === 'video') <span class="badge badge-gray">🎬 Vidéo</span>
                    @else <span class="badge badge-gray">✍️ Texte</span>
                    @endif
                </td>
                <td style="color:var(--text-muted);font-size:.82rem;">{{ number_format($post->likes_count) }}</td>
                <td>
                    @if($post->trashed()) <span class="badge badge-red">Supprimé</span>
                    @elseif($post->is_published) <span class="badge badge-green">Publié</span>
                    @else <span class="badge badge-gray">Brouillon</span>
                    @endif
                </td>
                <td style="color:var(--text-muted);font-size:.78rem;">{{ $post->created_at->format('d/m/Y') }}</td>
                <td>
                    @if(! $post->trashed())
                    <div class="action-row">
                        <a href="{{ route('posts.show', $post->id) }}" class="btn-action" target="_blank">👁</a>
                        <form method="POST" action="{{ route('admin.posts.toggle', $post->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-action">{{ $post->is_published ? '⏸ Masquer' : '▶ Publier' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.posts.delete', $post->id) }}"
                              onsubmit="return confirm('Supprimer cette publication ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action danger">🗑</button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $posts->links() }}
    </div>
</div>

@endsection
