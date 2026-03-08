@extends('owner.layout')

@section('title', 'Dashboard Owner')
@section('page-title', 'Vue d\'ensemble — Owner')

@section('content')

{{-- Stat principale --}}
<div class="stat-grid">
    <div class="stat-card owner-accent">
        <div class="stat-card-label">Utilisateurs</div>
        <div class="stat-card-value">{{ number_format($stats['users_total']) }}</div>
        <div class="stat-card-sub">{{ $stats['users_active'] }} actifs · +{{ $stats['users_new_week'] }} cette semaine</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Creators</div>
        <div class="stat-card-value">{{ number_format($stats['creators_total']) }}</div>
        <div class="stat-card-sub">{{ $stats['requests_pending'] }} demande(s) en attente</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Admins</div>
        <div class="stat-card-value">{{ $stats['admins_total'] }}</div>
        <div class="stat-card-sub"><a href="{{ route('owner.staff') }}" style="color:var(--owner);text-decoration:none;">Gérer →</a></div>
    </div>
    <div class="stat-card gold">
        <div class="stat-card-label">Revenus XOF</div>
        <div class="stat-card-value">{{ number_format($stats['revenue_xof']) }}</div>
        <div class="stat-card-sub">abonnements actifs</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-card-label">Revenus EUR</div>
        <div class="stat-card-value">{{ number_format($stats['revenue_eur'], 2) }}</div>
        <div class="stat-card-sub">abonnements actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Abonnements</div>
        <div class="stat-card-value">{{ $stats['subs_active'] }}</div>
        <div class="stat-card-sub">{{ $stats['subs_african'] }} african · {{ $stats['subs_global'] }} global</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Publications</div>
        <div class="stat-card-value">{{ number_format($stats['posts_total']) }}</div>
        <div class="stat-card-sub">&nbsp;</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Revenus ce mois</div>
        <div class="stat-card-value">{{ number_format($stats['revenue_month']) }}</div>
        <div class="stat-card-sub">{{ now()->translatedFormat('F Y') }}</div>
    </div>
</div>

{{-- Raccourcis owner --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;">
    @php
    $shortcuts = [
        ['route' => 'owner.finances', 'icon' => '💰', 'label' => 'Finances', 'desc' => 'Revenus & abonnements'],
        ['route' => 'owner.staff',    'icon' => '🛡️', 'label' => 'Staff',    'desc' => 'Gérer les admins'],
        ['route' => 'owner.settings', 'icon' => '⚙️', 'label' => 'Paramètres','desc' => 'Config plateforme'],
        ['route' => 'owner.logs',     'icon' => '📋', 'label' => 'Logs',     'desc' => 'Activité staff'],
    ];
    @endphp
    @foreach($shortcuts as $s)
    <a href="{{ route($s['route']) }}" style="display:flex;align-items:center;gap:12px;background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:16px;text-decoration:none;color:var(--text);transition:all .2s;" onmouseover="this.style.borderColor='rgba(123,63,212,.35)'" onmouseout="this.style.borderColor='var(--border)'">
        <span style="font-size:1.4rem;">{{ $s['icon'] }}</span>
        <div>
            <div style="font-weight:700;font-size:.88rem;font-family:var(--font-head);">{{ $s['label'] }}</div>
            <div style="font-size:.72rem;color:var(--text-muted);">{{ $s['desc'] }}</div>
        </div>
    </a>
    @endforeach
</div>

{{-- Dernières activités staff --}}
<div class="owner-table-wrap">
    <div class="owner-table-header">
        <div class="owner-table-title">📋 Dernières actions staff</div>
        <a href="{{ route('owner.logs') }}" style="font-size:.78rem;color:var(--owner);text-decoration:none;">Voir tout →</a>
    </div>
    @if($recent_logs->isEmpty())
        <div style="padding:32px;text-align:center;color:var(--text-muted);font-size:.84rem;">Aucune activité enregistrée.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Staff</th>
                    <th>Action</th>
                    <th>Quand</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_logs as $log)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.84rem;">{{ $log->staff?->name ?? 'Système' }}</div>
                        <div style="font-size:.72rem;color:var(--text-muted);">{{ $log->staff?->isOwner() ? 'Owner' : 'Admin' }}</div>
                    </td>
                    <td style="font-size:.82rem;">{{ $log->description }}</td>
                    <td style="font-size:.75rem;color:var(--text-muted);white-space:nowrap;">{{ $log->created_at?->diffForHumans() ?? "-" }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
