@extends('admin.layout')

@section('title', 'Abonnements')
@section('page-title', 'Abonnements')

@section('content')

@if(session('success'))
<div style="background:rgba(42,122,72,.15);border:1px solid rgba(42,122,72,.3);color:#2A7A48;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:.85rem;">
    ✓ {{ session('success') }}
</div>
@endif

{{-- Revenus --}}
<div class="stat-grid" style="margin-bottom:24px;">
    <div class="stat-card accent">
        <div class="stat-card-label">Abonnements actifs</div>
        <div class="stat-card-value">{{ $revenue['total'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Revenus XOF</div>
        <div class="stat-card-value">{{ number_format($revenue['xof']) }}</div>
        <div class="stat-card-sub">Francs CFA</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Revenus EUR</div>
        <div class="stat-card-value">{{ number_format($revenue['eur']) }}</div>
        <div class="stat-card-sub">Euros</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">En attente</div>
        <div class="stat-card-value" style="color:var(--gold);">{{ $revenue['pending'] }}</div>
        <div class="stat-card-sub">à valider</div>
    </div>
</div>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <div class="admin-table-title">💳 {{ $subscriptions->total() }} abonnements
            @if($revenue['pending'] > 0)
                <span style="background:var(--terra);color:white;font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:100px;margin-left:8px;">{{ $revenue['pending'] }} en attente</span>
            @endif
        </div>
        <form method="GET" style="display:flex;gap:8px;">
            <select name="status" class="admin-search" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Actifs</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>En attente</option>
                <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Expirés</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulés</option>
            </select>
        </form>
    </div>

    @if($subscriptions->isEmpty())
    <div style="padding:60px 20px;text-align:center;color:var(--text-muted);">
        <div style="font-size:2.5rem;margin-bottom:12px;">💳</div>
        <div style="font-family:var(--font-head);font-size:1rem;font-weight:700;color:var(--text);margin-bottom:6px;">Aucun abonnement</div>
    </div>
    @else
    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Plan</th>
                <th>Montant</th>
                <th>Paiement</th>
                <th>Transaction</th>
                <th>Statut</th>
                <th>Expire le</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $sub)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="user-avatar-mini">
                            <div class="user-avatar-mini-inner">
                                @if($sub->user->avatar)<img src="{{ Storage::url($sub->user->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($sub->user->name,0,1)) }}@endif
                            </div>
                        </div>
                        <div>
                            <div style="font-size:.84rem;font-weight:600;">{{ $sub->user->name }}</div>
                            <div style="font-size:.7rem;color:var(--text-muted);">&#64;{{ $sub->user->username }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($sub->plan === 'african')
                        <span class="badge badge-gold">🌍 Sénégal</span>
                    @else
                        <span class="badge badge-gray">🌐 Global</span>
                    @endif
                </td>
                <td style="font-weight:700;">{{ number_format($sub->amount) }} {{ $sub->currency }}</td>
                <td>
                    @if($sub->payment_method === 'wave')         <span class="badge badge-gray">〰 Wave</span>
                    @elseif($sub->payment_method === 'orange_money') <span class="badge badge-gray">🟠 OM</span>
                    @elseif($sub->payment_method === 'stripe')    <span class="badge badge-gray">💳 Stripe</span>
                    @else <span class="badge badge-gray">{{ $sub->payment_method ?? '—' }}</span>
                    @endif
                </td>
                <td style="font-size:.75rem;color:var(--text-muted);font-family:monospace;" title="{{ $sub->transaction_id }}">
                    {{ $sub->transaction_id ? Str::limit($sub->transaction_id, 16) : '—' }}
                </td>
                <td>
                    @if($sub->status === 'active')     <span class="badge badge-green">Actif</span>
                    @elseif($sub->status === 'pending') <span class="badge badge-gold">En attente</span>
                    @elseif($sub->status === 'expired') <span class="badge badge-red">Expiré</span>
                    @else                               <span class="badge badge-gray">Annulé</span>
                    @endif
                </td>
                <td style="font-size:.78rem;color:var(--text-muted);">{{ $sub->expires_at?->format('d/m/Y') ?? '—' }}</td>
                <td style="font-size:.78rem;color:var(--text-muted);">{{ $sub->created_at->format('d/m/Y') }}</td>
                <td>
                    @if($sub->status === 'pending')
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        <form method="POST" action="{{ route('admin.subscriptions.approve', $sub) }}">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:rgba(42,122,72,.12);border:1px solid rgba(42,122,72,.3);color:#2A7A48;font-size:.75rem;font-weight:600;padding:5px 10px;border-radius:8px;cursor:pointer;">
                                ✓ Approuver
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.subscriptions.cancel', $sub) }}"
                              onsubmit="return confirm('Annuler cet abonnement ?')">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:var(--bg-card2);border:1px solid var(--border);color:var(--text-muted);font-size:.75rem;font-weight:600;padding:5px 10px;border-radius:8px;cursor:pointer;">
                                Annuler
                            </button>
                        </form>
                    </div>
                    @elseif($sub->status === 'active')
                    <form method="POST" action="{{ route('admin.subscriptions.cancel', $sub) }}"
                          onsubmit="return confirm('Révoquer cet abonnement actif ?')">
                        @csrf @method('PATCH')
                        <button type="submit" style="background:var(--bg-card2);border:1px solid var(--border);color:var(--text-muted);font-size:.75rem;font-weight:600;padding:5px 10px;border-radius:8px;cursor:pointer;">
                            Révoquer
                        </button>
                    </form>
                    @else
                    <span style="font-size:.75rem;color:var(--text-faint);">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($subscriptions->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">
        {{ $subscriptions->links() }}
    </div>
    @endif
    @endif

</div>

@endsection
