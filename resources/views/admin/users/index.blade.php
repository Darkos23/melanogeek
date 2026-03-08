@extends('admin.layout')

@section('title', 'Utilisateurs')
@section('page-title', 'Utilisateurs')

@section('content')

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <div class="admin-table-title">👥 {{ $users->total() }} utilisateurs</div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <form method="GET" style="display:flex;gap:8px;">
                <input type="text" name="search" class="admin-search" placeholder="Nom, pseudo, email..." value="{{ request('search') }}">
                <select name="role" class="admin-search" style="min-width:120px;">
                    <option value="">Tous les rôles</option>
                    <option value="user"  {{ request('role') === 'user'  ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                </select>
                <button type="submit" style="background:var(--terra);border:none;color:white;padding:8px 16px;border-radius:10px;font-size:.8rem;font-weight:600;cursor:pointer;">Filtrer</button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users') }}" style="padding:8px 12px;border-radius:10px;border:1px solid var(--border);color:var(--text-muted);font-size:.8rem;text-decoration:none;">✕</a>
                @endif
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Plan</th>
                <th>Statut</th>
                <th>Inscrit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr style="{{ $u->trashed() ? 'opacity:.5;' : '' }}">
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="user-avatar-mini">
                            <div class="user-avatar-mini-inner">
                                @if($u->avatar)<img src="{{ Storage::url($u->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($u->name,0,1)) }}@endif
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
                    @if($u->role === 'owner')       <span class="badge badge-terra">✦ Owner</span>
                    @elseif($u->role === 'admin')   <span class="badge badge-gold">Admin</span>
                    @else                           <span class="badge badge-gray">User</span>
                    @endif
                </td>
                <td><span class="badge badge-gray">{{ $u->plan ?? 'free' }}</span></td>
                <td>
                    @if($u->trashed()) <span class="badge badge-red">Supprimé</span>
                    @elseif(!$u->is_active) <span class="badge badge-red">Suspendu</span>
                    @else <span class="badge badge-green">Actif</span>
                    @endif
                    @if($u->is_verified) <span class="badge badge-gold" style="margin-left:4px;">✓</span> @endif
                </td>
                <td style="color:var(--text-muted);font-size:.78rem;">{{ $u->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="action-row">
                        @if($u->trashed())
                            <form method="POST" action="{{ route('admin.users.restore', $u->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-action success">↩ Restaurer</button>
                            </form>
                        @else
                            <a href="{{ route('admin.users.edit', $u->username) }}" class="btn-action">✎ Éditer</a>
                            <form method="POST" action="{{ route('admin.users.toggle', $u->username) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-action {{ $u->is_active ? 'danger' : 'success' }}">
                                    {{ $u->is_active ? '⏸ Suspendre' : '▶ Activer' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.verify', $u->username) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-action">{{ $u->is_verified ? '✕ Vérif.' : '✓ Vérifier' }}</button>
                            </form>
                            @if($u->role !== 'owner')
                            <form method="POST" action="{{ route('admin.users.delete', $u->username) }}"
                                  onsubmit="return confirm('Supprimer {{ $u->name }} ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action danger">🗑</button>
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
