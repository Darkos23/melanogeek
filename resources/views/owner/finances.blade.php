@extends('owner.layout')

@section('title', 'Finances')
@section('page-title', 'Finances avancées')

@section('content')

{{-- Totaux --}}
<div class="stat-grid">
    <div class="stat-card gold">
        <div class="stat-card-label">Total XOF</div>
        <div class="stat-card-value">{{ number_format($totals['xof']) }}</div>
        <div class="stat-card-sub">abonnements actifs</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-card-label">Total EUR</div>
        <div class="stat-card-value">{{ number_format($totals['eur'], 2) }}</div>
        <div class="stat-card-sub">abonnements actifs</div>
    </div>
    <div class="stat-card owner-accent">
        <div class="stat-card-label">Actifs</div>
        <div class="stat-card-value">{{ $totals['active'] }}</div>
        <div class="stat-card-sub">abonnements en cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Expirés</div>
        <div class="stat-card-value">{{ $totals['expired'] }}</div>
        <div class="stat-card-sub">&nbsp;</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">En attente</div>
        <div class="stat-card-value">{{ $totals['pending'] }}</div>
        <div class="stat-card-sub">paiements pending</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,300px),1fr));gap:20px;margin-bottom:24px;">

    {{-- Par plan --}}
    <div class="owner-table-wrap">
        <div class="owner-table-header">
            <div class="owner-table-title">📊 Revenus par plan</div>
        </div>
        <table>
            <thead>
                <tr><th>Plan</th><th>Devise</th><th>Abonnés</th><th>Total</th></tr>
            </thead>
            <tbody>
                @forelse($byPlan as $row)
                <tr>
                    <td>
                        <span class="badge {{ $row->plan === 'global' ? 'badge-owner' : 'badge-admin' }}">
                            {{ ucfirst($row->plan) }}
                        </span>
                    </td>
                    <td style="font-size:.78rem;color:var(--text-muted);">{{ $row->currency }}</td>
                    <td style="font-weight:600;">{{ $row->count }}</td>
                    <td style="font-weight:700;color:var(--gold);">{{ number_format($row->total, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:24px;">Aucun abonnement actif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Par méthode de paiement --}}
    <div class="owner-table-wrap">
        <div class="owner-table-header">
            <div class="owner-table-title">💳 Par méthode de paiement</div>
        </div>
        <table>
            <thead>
                <tr><th>Méthode</th><th>Transactions</th><th>Total</th></tr>
            </thead>
            <tbody>
                @forelse($byMethod as $row)
                <tr>
                    <td>
                        @php
                            $icons = ['wave' => '🌊', 'orange_money' => '🟠', 'stripe' => '💳', 'card' => '💳'];
                        @endphp
                        {{ $icons[$row->payment_method] ?? '❓' }} {{ ucfirst(str_replace('_', ' ', $row->payment_method ?? 'inconnu')) }}
                    </td>
                    <td style="font-weight:600;">{{ $row->count }}</td>
                    <td style="font-weight:700;color:var(--gold);">{{ number_format($row->total, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:24px;">Aucune donnée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Revenus mensuels --}}
<div class="owner-table-wrap">
    <div class="owner-table-header">
        <div class="owner-table-title">📅 Revenus mensuels (6 derniers mois)</div>
    </div>
    <table>
        <thead>
            <tr><th>Mois</th><th>Devise</th><th>Abonnements</th><th>Total</th></tr>
        </thead>
        <tbody>
            @forelse($monthly as $row)
            <tr>
                <td style="font-weight:600;">
                    {{ \Carbon\Carbon::createFromDate($row->year, $row->month, 1)->translatedFormat('F Y') }}
                </td>
                <td><span class="badge badge-gray">{{ $row->currency }}</span></td>
                <td>{{ $row->count }}</td>
                <td style="font-weight:700;color:var(--gold);">{{ number_format($row->total, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:32px;">Aucune donnée sur les 6 derniers mois.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<a href="{{ route('admin.subscriptions') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--owner);text-decoration:none;font-size:.84rem;">
    Voir tous les abonnements →
</a>

@endsection
