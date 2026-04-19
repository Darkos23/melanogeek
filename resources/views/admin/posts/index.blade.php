@extends('admin.layout')

@section('title', 'Publications')
@section('page-title', 'Publications')

@section('content')

{{-- Onglets --}}
<div style="display:flex;gap:4px;margin-bottom:20px;border-bottom:1px solid var(--border);padding-bottom:0;">
    <a href="{{ route('admin.posts') }}"
       style="padding:8px 18px;font-size:.82rem;font-weight:600;text-decoration:none;border-radius:8px 8px 0 0;
              {{ $tab === 'all' ? 'background:var(--terra);color:#fff;' : 'color:var(--text-muted);' }}">
        Toutes
    </a>
    <a href="{{ route('admin.posts', ['tab' => 'pending']) }}"
       style="padding:8px 18px;font-size:.82rem;font-weight:600;text-decoration:none;border-radius:8px 8px 0 0;display:flex;align-items:center;gap:8px;
              {{ $tab === 'pending' ? 'background:var(--terra);color:#fff;' : 'color:var(--text-muted);' }}">
        ⏳ En attente
        @if($pendingCount > 0)
            <span style="background:#e55;color:#fff;border-radius:100px;font-size:.72rem;padding:1px 7px;font-weight:700;">{{ $pendingCount }}</span>
        @endif
    </a>
</div>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <div class="admin-table-title">
            @if($tab === 'pending')
                ⏳ {{ $posts->total() }} article(s) en attente de validation
            @else
                📝 {{ $posts->total() }} publications
            @endif
        </div>
        @if($tab === 'all')
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
            <input type="hidden" name="tab" value="all">
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
        @endif
    </div>

    @if(session('success'))
        <div style="background:rgba(80,180,80,.12);border:1px solid rgba(80,180,80,.3);color:#7ecf7e;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:.82rem;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Publication</th>
                <th>Auteur</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr style="{{ $post->trashed() ? 'opacity:.4;' : '' }}">
                <td style="max-width:260px;">
                    <div style="font-weight:500;font-size:.84rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $post->title ?: Str::limit($post->body, 60) ?: '(média seul)' }}
                    </div>
                    @if($post->pending_review && $post->rejection_reason)
                        <div style="font-size:.72rem;color:#e88;margin-top:2px;">Raison précédente : {{ $post->rejection_reason }}</div>
                    @endif
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
                <td>
                    @if($post->trashed())          <span class="badge badge-red">Supprimé</span>
                    @elseif($post->pending_review) <span class="badge" style="background:rgba(230,160,30,.15);color:#e6a01e;border:1px solid rgba(230,160,30,.3);">⏳ En attente</span>
                    @elseif($post->is_published)   <span class="badge badge-green">Publié</span>
                    @else                          <span class="badge badge-gray">Brouillon</span>
                    @endif
                </td>
                <td style="color:var(--text-muted);font-size:.78rem;">{{ $post->created_at->format('d/m/Y') }}</td>
                <td>
                    @if(! $post->trashed())
                    <div class="action-row">
                        <a href="{{ route('posts.show', $post->id) }}" class="btn-action" target="_blank">👁</a>

                        @if($post->pending_review)
                            {{-- Approuver --}}
                            <form method="POST" action="{{ route('admin.posts.approve', $post->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-action" style="color:#7ecf7e;" title="Approuver">✓ Approuver</button>
                            </form>
                            {{-- Rejeter --}}
                            <form method="POST" action="{{ route('admin.posts.reject', $post->id) }}"
                                  onsubmit="return confirmReject(this, event)">
                                @csrf @method('PATCH')
                                <input type="hidden" name="reason" class="reject-reason-input" value="">
                                <button type="submit" class="btn-action danger" title="Rejeter">✗ Rejeter</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.posts.toggle', $post->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-action">{{ $post->is_published ? '⏸ Masquer' : '▶ Publier' }}</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.posts.delete', $post->id) }}"
                              onsubmit="return confirm('Supprimer cette publication ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action danger">🗑</button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:32px;color:var(--text-faint);font-size:.84rem;">
                    @if($tab === 'pending') Aucun article en attente. @else Aucune publication. @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $posts->links() }}
    </div>
</div>

<script>
function confirmReject(form, e) {
    e.preventDefault();
    const reason = prompt('Raison du rejet (optionnel) :') ?? '';
    form.querySelector('.reject-reason-input').value = reason;
    if (confirm('Confirmer le rejet de cet article ?')) {
        form.submit();
    }
    return false;
}
</script>

@endsection
