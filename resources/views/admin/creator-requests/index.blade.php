@extends('admin.layout')

@section('title', 'Demandes creators')
@section('page-title', 'Demandes creators')

@section('content')

{{-- Filtres --}}
<div style="margin-bottom:20px;display:flex;gap:8px;flex-wrap:wrap;">
    @foreach(['pending' => 'En attente', 'approved' => 'Approuvées', 'rejected' => 'Refusées'] as $key => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}"
           style="padding:7px 16px;border-radius:100px;border:1px solid var(--border);font-size:.78rem;font-weight:600;text-decoration:none;
                  {{ request('status', 'pending') === $key ? 'background:var(--terra);border-color:var(--terra);color:white;' : 'color:var(--text-muted);background:var(--bg-card);' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <div class="admin-table-title">{{ $requests->total() }} demande(s)</div>
    </div>

    @if($requests->isEmpty())
        <div style="padding:48px;text-align:center;color:var(--text-muted);">
            <div style="font-size:2rem;margin-bottom:12px;">📭</div>
            <div>Aucune demande {{ request('status', 'pending') === 'pending' ? 'en attente' : '' }}.</div>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Motivation</th>
                    <th>Date</th>
                    <th>Statut</th>
                    @if(request('status', 'pending') !== 'pending')
                        <th>Traité par</th>
                    @endif
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="user-avatar-mini">
                                <div class="user-avatar-mini-inner">
                                    @if($req->user->avatar)
                                        <img src="{{ Storage::url($req->user->avatar) }}" alt="">
                                    @else
                                        {{ mb_strtoupper(mb_substr($req->user->name, 0, 1)) }}
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:.84rem;">{{ $req->user->name }}</div>
                                <div style="font-size:.74rem;color:var(--text-muted);">@{{ $req->user->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="max-width:320px;">
                        <div style="font-size:.82rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $req->motivation }}">
                            {{ $req->motivation }}
                        </div>
                    </td>
                    <td style="font-size:.78rem;color:var(--text-muted);white-space:nowrap;">
                        {{ $req->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        @if($req->status === 'pending')
                            <span class="badge badge-gold">⏳ En attente</span>
                        @elseif($req->status === 'approved')
                            <span class="badge badge-green">✓ Approuvée</span>
                        @else
                            <span class="badge badge-red">✗ Refusée</span>
                        @endif
                    </td>
                    @if(request('status', 'pending') !== 'pending')
                        <td style="font-size:.78rem;color:var(--text-muted);">
                            {{ $req->reviewer?->name ?? '—' }}<br>
                            {{ $req->reviewed_at?->format('d/m/Y') }}
                        </td>
                    @endif
                    <td>
                        <div class="action-row">
                            <a href="{{ route('admin.users.edit', $req->user->username) }}" class="btn-action">👤 Profil</a>
                            @if($req->status === 'pending')
                                <form method="POST" action="{{ route('admin.creator-requests.approve', $req) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action success">✓ Approuver</button>
                                </form>
                                <form method="POST" action="{{ route('admin.creator-requests.reject', $req) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action danger">✗ Refuser</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($requests->hasPages())
            <div class="pagination-wrap">
                {{ $requests->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
