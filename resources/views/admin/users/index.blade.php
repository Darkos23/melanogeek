@extends('admin.layout')

@section('title', 'Utilisateurs')
@section('page-title', 'Utilisateurs')

@section('content')

<style>
    .users-role-badge,
    .users-status-badges .badge {
        min-height: 32px;
        padding: 0 12px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: .74rem;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }

    .users-role-badge.owner {
        background: rgba(200,82,42,.12);
        border: 1px solid rgba(200,82,42,.28);
        color: #df7448;
    }

    .users-role-badge.admin {
        background: rgba(212,168,67,.12);
        border: 1px solid rgba(212,168,67,.24);
        color: #dcb45a;
    }

    .users-role-badge.user {
        background: var(--bg-card2);
        border: 1px solid var(--border);
        color: var(--text-muted);
    }

    .users-status-badges {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
    }

    .users-status-badges .badge-verified {
        min-width: 32px;
        padding: 0 10px;
    }
</style>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <div class="admin-table-title">{{ $users->total() }} utilisateurs</div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <form method="GET" style="display:flex;gap:8px;">
                <input type="text" name="search" class="admin-search" placeholder="Nom, pseudo, email..." value="{{ request('search') }}">
                <select name="role" class="admin-search" style="min-width:120px;">
                    <option value="">Tous les roles</option>
                    <option value="user"  {{ request('role') === 'user'  ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                </select>
                <button type="submit" style="background:var(--terra);border:none;color:white;padding:8px 16px;border-radius:10px;font-size:.8rem;font-weight:600;cursor:pointer;">Filtrer</button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users') }}" style="padding:8px 12px;border-radius:10px;border:1px solid var(--border);color:var(--text-muted);font-size:.8rem;text-decoration:none;">X</a>
                @endif
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Role</th>
                <th>Plan</th>
                <th>Statut</th>
                <th>Inscrit</th>
                <th style="width: 1%; white-space: nowrap;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr style="{{ $u->trashed() ? 'opacity:.5;' : '' }}">
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="user-avatar-mini">
                            <div class="user-avatar-mini-inner">
                                @if($u->avatar_url)
                                    <img src="{{ $u->avatar_url }}" alt="">
                                @else
                                    {{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}
                                @endif
                            </div>
                        </div>
                        <div>
                            <div style="font-weight:600;">{{ $u->name }}</div>
                            <div style="font-size:.72rem;color:var(--text-muted);">&#64;{{ $u->username }}</div>
                        </div>
                    </div>
                </td>
                <td style="color:var(--text-muted);font-size:.82rem;">{{ $u->email }}</td>
                <td>
                    @if($u->role === 'owner')
                        <span class="users-role-badge owner">Owner</span>
                    @elseif($u->role === 'admin')
                        <span class="users-role-badge admin">Admin</span>
                    @else
                        <span class="users-role-badge user">User</span>
                    @endif
                </td>
                <td><span class="badge badge-gray">{{ $u->plan ?? 'free' }}</span></td>
                <td>
                    <div class="users-status-badges">
                        @if($u->trashed())
                            <span class="badge badge-red">Supprime</span>
                        @elseif(! $u->is_active)
                            <span class="badge badge-red">Suspendu</span>
                        @else
                            <span class="badge badge-green">Actif</span>
                        @endif

                        @if($u->is_verified)
                            <span class="badge badge-gold badge-verified">OK</span>
                        @endif
                    </div>
                </td>
                <td style="color:var(--text-muted);font-size:.78rem;">{{ $u->created_at->format('d/m/Y') }}</td>
                <td style="white-space: nowrap; min-width: 420px;">
                    <div class="action-row">
                        @if($u->trashed())
                            <form method="POST" action="{{ route('admin.users.restore', $u->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action success">Restaurer</button>
                            </form>
                        @else
                            <a href="{{ route('admin.users.edit', $u->username) }}" class="btn-action">Editer</a>

                            <form method="POST" action="{{ route('admin.users.toggle', $u->username) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action {{ $u->is_active ? 'danger' : 'success' }}">
                                    {{ $u->is_active ? 'Suspendre' : 'Activer' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.users.verify', $u->username) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action">
                                    {{ $u->is_verified ? 'Retirer verif.' : 'Verifier' }}
                                </button>
                            </form>

                            @if($u->role !== 'owner')
                            <form method="POST" action="{{ route('admin.users.delete', $u->username) }}"
                                  onsubmit="return confirm('Supprimer {{ $u->name }} ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action danger">Supprimer</button>
                            </form>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $users->links() }}
    </div>
</div>

@endsection
