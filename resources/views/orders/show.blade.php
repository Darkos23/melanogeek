@extends('layouts.app')
@section('title', 'Commande #' . $order->id . ' — MelanoGeek')
@section('content')
<style>
.ord-wrap { max-width: 760px; margin: 0 auto; padding: 88px 20px 80px; }
.back-link { display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; color: var(--text-muted); text-decoration: none; margin-bottom: 24px; transition: color .15s; }
.back-link:hover { color: var(--text); }
.ord-hero { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 28px; }
.ord-hero-thumb { width: 72px; height: 72px; border-radius: 12px; object-fit: cover; background: var(--bg-card2); display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0; overflow: hidden; }
.ord-hero-thumb img { width: 100%; height: 100%; object-fit: cover; }
.ord-hero-title { font-family: var(--font-head); font-size: 1.3rem; font-weight: 800; color: var(--text); margin-bottom: 6px; line-height: 1.3; }
.ord-hero-sub { font-size: .82rem; color: var(--text-muted); }

/* Status tracker */
.status-track { display: flex; align-items: center; gap: 0; margin-bottom: 32px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 20px; overflow-x: auto; }
.track-step { display: flex; flex-direction: column; align-items: center; gap: 6px; min-width: 80px; }
.track-dot { width: 28px; height: 28px; border-radius: 50%; background: var(--bg-card2); border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: .7rem; transition: all .2s; flex-shrink: 0; }
.track-dot.done { background: var(--terra); border-color: var(--terra); color: white; }
.track-dot.current { background: var(--gold); border-color: var(--gold); color: #1C1208; }
.track-label { font-size: .68rem; font-weight: 600; color: var(--text-muted); text-align: center; white-space: nowrap; }
.track-label.current { color: var(--gold); }
.track-label.done { color: var(--text); }
.track-line { flex: 1; height: 2px; background: var(--border); min-width: 20px; }
.track-line.done { background: var(--terra); }

/* Cards */
.info-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 22px; margin-bottom: 16px; }
.info-card h3 { font-family: var(--font-head); font-size: .9rem; font-weight: 700; color: var(--text); margin-bottom: 14px; }
.info-row { display: flex; justify-content: space-between; align-items: baseline; padding: 6px 0; border-bottom: 1px solid var(--border); font-size: .84rem; }
.info-row:last-child { border-bottom: none; }
.info-label { color: var(--text-muted); }
.info-val { color: var(--text); font-weight: 600; }

.req-text { font-size: .84rem; color: var(--text-muted); line-height: 1.6; white-space: pre-wrap; background: var(--bg-card2); border-radius: 8px; padding: 12px 14px; }

/* User mini */
.user-mini { display: flex; align-items: center; gap: 10px; }
.user-mini-avi { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--terra), var(--gold)); padding: 1.5px; flex-shrink: 0; }
.user-mini-avi-inner { width: 100%; height: 100%; border-radius: 50%; background: var(--bg-card2); display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; overflow: hidden; }
.user-mini-avi-inner img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.user-mini-name { font-size: .86rem; font-weight: 600; color: var(--text); }
.user-mini-sub { font-size: .72rem; color: var(--text-muted); }

/* Actions */
.action-section { display: flex; flex-direction: column; gap: 12px; margin-top: 24px; }
.action-section h3 { font-family: var(--font-head); font-size: .95rem; font-weight: 700; color: var(--text); }
.btn-action-big {
    display: block; width: 100%; padding: 12px; border-radius: 12px;
    font-family: var(--font-head); font-size: .9rem; font-weight: 700;
    border: none; cursor: pointer; text-align: center; text-decoration: none;
    transition: opacity .2s;
}
.btn-action-big:hover { opacity: .85; }
.btn-green { background: rgba(42,122,72,.15); color: #2A7A48; border: 1px solid rgba(42,122,72,.3) !important; }
.btn-terra { background: var(--terra); color: white; }
.btn-purple { background: rgba(155,95,209,.12); color: #9B5FD1; border: 1px solid rgba(155,95,209,.3) !important; }
.btn-danger { background: rgba(224,85,85,.1); color: #E05555; border: 1px solid rgba(224,85,85,.3) !important; }
.btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-muted); }

/* Deliver form */
.deliver-form { background: var(--bg-card2); border: 1px solid var(--border); border-radius: 12px; padding: 16px; }
.form-label { display: block; font-size: .82rem; font-weight: 600; color: var(--text); margin-bottom: 7px; }
.form-textarea { width: 100%; background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 10px 13px; font-size: .84rem; font-family: var(--font-body); color: var(--text); outline: none; resize: vertical; min-height: 80px; }
.form-textarea:focus { border-color: var(--terra); }

.status-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 100px; font-size: .78rem; font-weight: 700; }

.alert-success { background: rgba(42,122,72,.12); border: 1px solid rgba(42,122,72,.3); color: #2A7A48; padding: 12px 16px; border-radius: 10px; font-size: .84rem; margin-bottom: 20px; }
</style>

<div class="ord-wrap">
    <a href="{{ route('orders.index') }}" class="back-link">← Mes commandes</a>

    @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    {{-- Hero --}}
    <div class="ord-hero">
        <div class="ord-hero-thumb">
            @if($order->service->cover_image)
                <img src="{{ Storage::url($order->service->cover_image) }}" alt="">
            @else
                {{ $order->service->category_icon ?? '🛍️' }}
            @endif
        </div>
        <div>
            <div class="ord-hero-title">{{ $order->service->title }}</div>
            <div class="ord-hero-sub">Commande #{{ $order->id }} · {{ $order->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- Tracker de statut --}}
    @php
        $steps = [
            'pending'     => ['label' => 'En attente', 'icon' => '⏳'],
            'accepted'    => ['label' => 'Acceptée',   'icon' => '✓'],
            'in_progress' => ['label' => 'En cours',   'icon' => '⚙'],
            'delivered'   => ['label' => 'Livrée',     'icon' => '📦'],
            'completed'   => ['label' => 'Terminée',   'icon' => '🎉'],
        ];
        $stepKeys   = array_keys($steps);
        $currentIdx = array_search($order->status, $stepKeys);
        if ($order->status === 'cancelled') $currentIdx = -1;
    @endphp

    @if($order->status !== 'cancelled')
    <div class="status-track">
        @foreach($steps as $key => $step)
            @php $idx = array_search($key, $stepKeys); @endphp
            <div class="track-step">
                <div class="track-dot {{ $idx < $currentIdx ? 'done' : ($idx === $currentIdx ? 'current' : '') }}">
                    {{ $idx < $currentIdx ? '✓' : $step['icon'] }}
                </div>
                <div class="track-label {{ $idx < $currentIdx ? 'done' : ($idx === $currentIdx ? 'current' : '') }}">
                    {{ $step['label'] }}
                </div>
            </div>
            @if(! $loop->last)
                <div class="track-line {{ $idx < $currentIdx ? 'done' : '' }}"></div>
            @endif
        @endforeach
    </div>
    @else
    <div style="background:rgba(224,85,85,.1);border:1px solid rgba(224,85,85,.3);border-radius:12px;padding:14px 18px;margin-bottom:24px;font-size:.84rem;color:#E05555;font-weight:600;">
        ✕ Commande annulée
    </div>
    @endif

    {{-- Infos --}}
    <div class="info-card">
        <h3>Détails de la commande</h3>
        <div class="info-row"><span class="info-label">Service</span><span class="info-val">{{ Str::limit($order->service->title, 40) }}</span></div>
        <div class="info-row">
            <span class="info-label">Montant</span>
            <span class="info-val" style="color:var(--terra);font-size:1rem;">{{ $order->price_formatted }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Paiement</span>
            <span class="info-val">
                @if($order->payment_method === 'wave') 〰 Wave
                @elseif($order->payment_method === 'orange_money') 🟠 Orange Money
                @elseif($order->payment_method === 'stripe') 💳 Carte
                @else {{ $order->payment_method ?? '—' }}
                @endif
                ·
                @if($order->payment_status === 'paid')
                    <span style="color:#2A7A48;">Payé ✓</span>
                @else
                    <span style="color:var(--gold);">En attente</span>
                @endif
            </span>
        </div>
        @if($order->transaction_id)
        <div class="info-row"><span class="info-label">ID Transaction</span><span class="info-val" style="font-family:monospace;font-size:.8rem;">{{ $order->transaction_id }}</span></div>
        @endif
        <div class="info-row"><span class="info-label">Délai prévu</span><span class="info-val">{{ $order->service->delivery_days }} jour(s)</span></div>
        <div class="info-row"><span class="info-label">Commandé le</span><span class="info-val">{{ $order->created_at->format('d/m/Y à H:i') }}</span></div>
    </div>

    {{-- Parties --}}
    <div class="info-card">
        <h3>Acheteur & Vendeur</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <div style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Acheteur</div>
                <div class="user-mini">
                    <div class="user-mini-avi"><div class="user-mini-avi-inner">
                        @if($order->buyer->avatar)<img src="{{ Storage::url($order->buyer->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($order->buyer->name,0,1)) }}@endif
                    </div></div>
                    <div>
                        <div class="user-mini-name">{{ $order->buyer->name }}</div>
                        <div class="user-mini-sub">&#64;{{ $order->buyer->username }}</div>
                    </div>
                </div>
            </div>
            <div>
                <div style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Créateur</div>
                <div class="user-mini">
                    <div class="user-mini-avi"><div class="user-mini-avi-inner">
                        @if($order->seller->avatar)<img src="{{ Storage::url($order->seller->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($order->seller->name,0,1)) }}@endif
                    </div></div>
                    <div>
                        <div class="user-mini-name">{{ $order->seller->name }}</div>
                        <div class="user-mini-sub">&#64;{{ $order->seller->username }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Instructions acheteur --}}
    @if($order->requirements)
    <div class="info-card">
        <h3>Instructions de l'acheteur</h3>
        <div class="req-text">{{ $order->requirements }}</div>
    </div>
    @endif

    {{-- Note de livraison --}}
    @if($order->seller_note)
    <div class="info-card">
        <h3>📦 Note de livraison du créateur</h3>
        <div class="req-text">{{ $order->seller_note }}</div>
    </div>
    @endif

    {{-- ACTIONS --}}
    @php $me = auth()->user(); @endphp

    {{-- ── VENDEUR ── --}}
    @if($me->id === $order->seller_id)
        @if($order->status === 'pending')
        <div class="action-section">
            <h3>Actions créateur</h3>
            <form method="POST" action="{{ route('orders.accept', $order) }}">
                @csrf @method('PATCH')
                <button class="btn-action-big btn-green">✓ Accepter la commande</button>
            </form>
            <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Annuler cette commande ?')">
                @csrf @method('PATCH')
                <button class="btn-action-big btn-danger">Annuler</button>
            </form>
        </div>

        @elseif($order->status === 'accepted')
        <div class="action-section">
            <h3>Actions créateur</h3>
            <form method="POST" action="{{ route('orders.start-work', $order) }}">
                @csrf @method('PATCH')
                <button class="btn-action-big btn-purple">⚙ Commencer la réalisation</button>
            </form>
            <form method="POST" action="{{ route('orders.deliver', $order) }}" class="deliver-form" style="margin-top:8px;">
                @csrf @method('PATCH')
                <div style="margin-bottom:10px;">
                    <label class="form-label">Ou livrer directement <span style="font-weight:400;color:var(--text-muted);">(optionnel : ajoute une note)</span></label>
                    <textarea name="seller_note" class="form-textarea" placeholder="Explique ce que tu as réalisé, liens de téléchargement, instructions…"></textarea>
                </div>
                <button type="submit" class="btn-action-big btn-terra">📦 Marquer comme livré</button>
            </form>
        </div>

        @elseif($order->status === 'in_progress')
        <div class="action-section">
            <h3>Marquer comme livré</h3>
            <form method="POST" action="{{ route('orders.deliver', $order) }}" class="deliver-form">
                @csrf @method('PATCH')
                <div style="margin-bottom:10px;">
                    <label class="form-label">Note de livraison <span style="font-weight:400;color:var(--text-muted);">(optionnel)</span></label>
                    <textarea name="seller_note" class="form-textarea" placeholder="Explique ce que tu as réalisé, liens de téléchargement, instructions…"></textarea>
                </div>
                <button type="submit" class="btn-action-big btn-terra">📦 Marquer comme livré</button>
            </form>
        </div>
        @endif

    {{-- ── ACHETEUR ── --}}
    @elseif($me->id === $order->buyer_id)
        @if($order->status === 'delivered')
        <div class="action-section">
            <h3>Le créateur a livré ton service</h3>
            <form method="POST" action="{{ route('orders.complete', $order) }}">
                @csrf @method('PATCH')
                <button class="btn-action-big btn-green">🎉 Confirmer la réception</button>
            </form>
        </div>

        @elseif($order->status === 'completed')
        {{-- Formulaire d'avis --}}
        @if(! $order->review)
        <div class="action-section">
            <h3>⭐ Laisser un avis</h3>
            <form method="POST" action="{{ route('orders.review', $order) }}" class="deliver-form">
                @csrf
                <div style="margin-bottom:14px;">
                    <div style="font-size:.82rem;font-weight:600;color:var(--text);margin-bottom:8px;">Note</div>
                    <div class="star-picker" id="starPicker">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="star-btn" data-val="{{ $i }}" onclick="setStar({{ $i }})">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="">
                </div>
                <div style="margin-bottom:10px;">
                    <label class="form-label">Commentaire <span style="font-weight:400;color:var(--text-muted);">(optionnel)</span></label>
                    <textarea name="comment" class="form-textarea" placeholder="Décris ton expérience avec ce créateur…" maxlength="500"></textarea>
                </div>
                <button type="submit" class="btn-action-big btn-gold" id="reviewSubmitBtn" disabled
                        style="background:var(--gold);color:#1C1208;opacity:.5;cursor:not-allowed;">
                    Publier mon avis
                </button>
            </form>
        </div>
        @else
        <div class="info-card" style="border-color:rgba(212,168,67,.3);">
            <h3>⭐ Ton avis</h3>
            <div style="font-size:1.3rem;color:var(--gold);margin-bottom:6px;">
                @for($i = 1; $i <= 5; $i++){{ $i <= $order->review->rating ? '★' : '☆' }}@endfor
            </div>
            @if($order->review->comment)
            <div class="req-text">{{ $order->review->comment }}</div>
            @endif
        </div>
        @endif

        @elseif(in_array($order->status, ['pending', 'accepted']))
        <div class="action-section">
            <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Annuler cette commande ?')">
                @csrf @method('PATCH')
                <button class="btn-action-big btn-danger">Annuler la commande</button>
            </form>
        </div>
        @endif
    @endif

</div>

@push('scripts')
<style>
.star-picker { display:flex;gap:6px;margin-bottom:4px; }
.star-btn {
    font-size:1.6rem;color:var(--border);background:none;border:none;
    cursor:pointer;transition:color .15s, transform .1s;line-height:1;padding:0;
}
.star-btn.active,.star-btn:hover { color:var(--gold); transform:scale(1.15); }
.btn-action-big.btn-gold { background:var(--gold);color:#1C1208; }
</style>
<script>
function setStar(val) {
    document.getElementById('ratingInput').value = val;
    document.querySelectorAll('.star-btn').forEach((b, i) => {
        b.classList.toggle('active', i < val);
    });
    const btn = document.getElementById('reviewSubmitBtn');
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.style.cursor = 'pointer';
}
</script>
@endpush
@endsection
