@extends('admin.layout')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats ──────────────────────────────────────── --}}
<div class="stat-grid">
    <div class="stat-card accent">
        <div class="stat-card-label">Utilisateurs</div>
        <div class="stat-card-value">{{ number_format($stats['users_total']) }}</div>
        <div class="stat-card-sub">{{ $stats['users_active'] }} actifs · +{{ $stats['users_new'] }} cette semaine</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Publications</div>
        <div class="stat-card-value">{{ number_format($stats['posts_total']) }}</div>
        <div class="stat-card-sub">{{ $stats['posts_published'] }} publiées</div>
    </div>
</div>

{{-- Deux colonnes (1 colonne sur mobile) ──────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,300px),1fr));gap:20px;">

    {{-- Derniers inscrits --}}
    <div class="admin-table-wrap">
        <div class="admin-table-header">
            <div class="admin-table-title">👤 Derniers inscrits</div>
            <a href="{{ route('admin.users') }}" style="font-size:.78rem;color:var(--terra);text-decoration:none;">Voir tous →</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Inscrit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_users as $u)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="user-avatar-mini">
                                <div class="user-avatar-mini-inner">
                                    @if($u->avatar)<img src="{{ Storage::disk('public')->url($u->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($u->name,0,1)) }}@endif
                                </div>
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:.84rem;">{{ $u->name }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">&#64;{{ $u->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($u->role === 'owner') <span class="badge badge-terra">Owner</span>
                        @elseif($u->role === 'admin') <span class="badge badge-gold">Admin</span>
                        @else <span class="badge badge-gray">User</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:.78rem;">{{ $u->created_at?->diffForHumans() ?? "-" }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Dernières publications --}}
    <div class="admin-table-wrap">
        <div class="admin-table-header">
            <div class="admin-table-title">📝 Dernières publications</div>
            <a href="{{ route('admin.posts') }}" style="font-size:.78rem;color:var(--terra);text-decoration:none;">Voir toutes →</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Publication</th>
                    <th>Auteur</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_posts as $p)
                <tr>
                    <td style="max-width:200px;">
                        <div style="font-size:.84rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $p->title ?: Str::limit($p->body, 40) ?: '(média)' }}
                        </div>
                        <div style="font-size:.7rem;color:var(--text-muted);">{{ $p->created_at?->diffForHumans() ?? "-" }}</div>
                    </td>
                    <td style="font-size:.78rem;color:var(--text-muted);">&#64;{{ $p->user->username }}</td>
                    <td>
                        @if($p->is_published) <span class="badge badge-green">Publié</span>
                        @else <span class="badge badge-gray">Brouillon</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
