@extends('owner.layout')

@section('title', 'Logs d\'activité')
@section('page-title', 'Logs d\'activité staff')

@section('content')

{{-- Filtres --}}
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:16px 20px;margin-bottom:20px;display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;width:100%;">

        <div style="flex:1;min-width:160px;">
            <div style="font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px;">Catégorie</div>
            <select name="action" style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:8px 12px;color:var(--text);font-family:var(--font-body);font-size:.84rem;outline:none;">
                <option value="">Toutes</option>
                <option value="user"     {{ request('action') === 'user'     ? 'selected' : '' }}>👤 Utilisateurs</option>
                <option value="post"     {{ request('action') === 'post'     ? 'selected' : '' }}>📝 Publications</option>
                <option value="staff"    {{ request('action') === 'staff'    ? 'selected' : '' }}>🛡️ Staff</option>
                <option value="settings" {{ request('action') === 'settings' ? 'selected' : '' }}>⚙️ Paramètres</option>
            </select>
        </div>

        <div style="flex:1;min-width:160px;">
            <div style="font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px;">Membre du staff</div>
            <select name="staff" style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:8px 12px;color:var(--text);font-family:var(--font-body);font-size:.84rem;outline:none;">
                <option value="">Tous</option>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}" {{ request('staff') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }} ({{ ucfirst($s->role) }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" style="background:var(--owner-soft);border:1px solid rgba(123,63,212,.25);color:var(--owner);padding:8px 20px;border-radius:10px;font-family:var(--font-body);font-size:.84rem;font-weight:600;cursor:pointer;transition:all .2s;">
            Filtrer
        </button>
        @if(request('action') || request('staff'))
            <a href="{{ route('owner.logs') }}" style="color:var(--text-muted);font-size:.82rem;text-decoration:none;padding:8px 0;">✕ Réinitialiser</a>
        @endif
    </form>
</div>

{{-- Table logs --}}
<div class="owner-table-wrap">
    <div class="owner-table-header">
        <div class="owner-table-title">{{ $logs->total() }} entrée(s)</div>
    </div>

    @if($logs->isEmpty())
        <div style="padding:48px;text-align:center;color:var(--text-muted);">
            <div style="font-size:2rem;margin-bottom:12px;">📭</div>
            <div>Aucun log trouvé.</div>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Staff</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP</th>
                    <th>Quand</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.82rem;">{{ $log->staff?->name ?? 'Système' }}</div>
                        <div style="font-size:.7rem;">
                            @if($log->staff?->isOwner())
                                <span class="badge badge-owner" style="font-size:.62rem;">Owner</span>
                            @else
                                <span class="badge badge-admin" style="font-size:.62rem;">Admin</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @php
                            $actionColors = [
                                'user.'     => 'badge-gray',
                                'post.'     => 'badge-gray',
                                'staff.'    => 'badge-owner',
                                'settings.' => 'badge-green',
                            ];
                            $badgeClass = 'badge-gray';
                            foreach ($actionColors as $prefix => $class) {
                                if (str_starts_with($log->action, $prefix)) { $badgeClass = $class; break; }
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}" style="font-size:.68rem;">{{ $log->action }}</span>
                    </td>
                    <td style="font-size:.82rem;max-width:280px;">{{ $log->description }}</td>
                    <td style="font-size:.75rem;color:var(--text-muted);font-family:monospace;">{{ $log->ip_address ?? '—' }}</td>
                    <td style="font-size:.75rem;color:var(--text-muted);white-space:nowrap;">
                        {{ $log->created_at?->format('d/m/Y H:i') ?? '—' }}<br>
                        <span style="font-size:.7rem;">{{ $log->created_at?->diffForHumans() ?? "-" }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($logs->hasPages())
            <div class="pagination-wrap">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
