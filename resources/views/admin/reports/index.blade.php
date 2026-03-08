@extends('admin.layout')

@section('title', 'Signalements')
@section('page-title', 'Signalements')

@section('content')

@if(session('success'))
<div style="background:rgba(42,122,72,.15);border:1px solid rgba(42,122,72,.3);color:#2A7A48;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:.85rem;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <div class="admin-table-title">🚩 {{ $reports->total() }} signalement{{ $reports->total() > 1 ? 's' : '' }}
            @if($pendingCount > 0)
                <span style="background:var(--terra);color:white;font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:100px;margin-left:8px;">{{ $pendingCount }} en attente</span>
            @endif
        </div>
        <form method="GET" style="display:flex;gap:8px;">
            <select name="status" class="admin-search" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="pending"    {{ request('status') === 'pending'    ? 'selected' : '' }}>En attente</option>
                <option value="reviewed"   {{ request('status') === 'reviewed'   ? 'selected' : '' }}>Traités</option>
                <option value="dismissed"  {{ request('status') === 'dismissed'  ? 'selected' : '' }}>Ignorés</option>
            </select>
        </form>
    </div>

    @if($reports->isEmpty())
    <div style="padding:60px 20px;text-align:center;color:var(--text-muted);">
        <div style="font-size:2.5rem;margin-bottom:12px;">✅</div>
        <div style="font-family:var(--font-head);font-size:1rem;font-weight:700;color:var(--text);margin-bottom:6px;">Aucun signalement</div>
        <div style="font-size:.84rem;">La communauté se comporte bien 👍</div>
    </div>
    @else
    <table>
        <thead>
            <tr>
                <th>Publication signalée</th>
                <th>Signalé par</th>
                <th>Raison</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td style="max-width:220px;">
                    @if($report->post)
                        <div style="font-size:.8rem;font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ Str::limit($report->post->title ?: $report->post->body, 50) ?: '(sans texte)' }}
                        </div>
                        <div style="font-size:.72rem;color:var(--text-muted);margin-top:2px;">
                            par @{{ $report->post->user->username ?? '?' }}
                        </div>
                        <a href="{{ route('posts.show', $report->post_id) }}" target="_blank"
                           style="font-size:.7rem;color:var(--terra);text-decoration:none;">↗ Voir le post</a>
                    @else
                        <span style="font-size:.8rem;color:var(--text-faint);font-style:italic;">Post supprimé</span>
                    @endif
                </td>
                <td>
                    @if($report->reporter)
                    <div style="font-size:.82rem;font-weight:600;">{{ $report->reporter->name }}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);">@{{ $report->reporter->username }}</div>
                    @else
                    <span style="color:var(--text-faint);font-size:.8rem;">Compte supprimé</span>
                    @endif
                </td>
                <td style="max-width:200px;">
                    <span style="font-size:.8rem;line-height:1.4;">{{ $report->reason }}</span>
                </td>
                <td>
                    @if($report->status === 'pending')
                        <span style="background:rgba(212,168,67,.15);color:var(--gold);font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:100px;">En attente</span>
                    @elseif($report->status === 'reviewed')
                        <span style="background:rgba(42,122,72,.12);color:#2A7A48;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:100px;">Traité</span>
                    @else
                        <span style="background:var(--bg-card2);color:var(--text-muted);font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:100px;">Ignoré</span>
                    @endif
                </td>
                <td style="font-size:.78rem;color:var(--text-muted);">{{ $report->created_at->diffForHumans() }}</td>
                <td>
                    @if($report->status === 'pending')
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        @if($report->post)
                        <form method="POST" action="{{ route('admin.reports.remove-post', $report) }}"
                              onsubmit="return confirm('Supprimer cette publication et résoudre tous ses signalements ?')">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:rgba(224,85,85,.1);border:1px solid rgba(224,85,85,.3);color:#E05555;font-size:.75rem;font-weight:600;padding:5px 10px;border-radius:8px;">
                                🗑 Supprimer post
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.reports.dismiss', $report) }}">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:var(--bg-card2);border:1px solid var(--border);color:var(--text-muted);font-size:.75rem;font-weight:600;padding:5px 10px;border-radius:8px;">
                                Ignorer
                            </button>
                        </form>
                    </div>
                    @else
                    <span style="font-size:.75rem;color:var(--text-faint);">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($reports->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">
        {{ $reports->links() }}
    </div>
    @endif
    @endif

</div>

@endsection
