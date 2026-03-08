@extends('layouts.app')

@section('title', 'Abonnement ' . ($details['name'] ?? '') . ' — MelanoGeek')

@section('content')
<style>
.checkout-wrap {
    max-width: 560px;
    margin: 0 auto;
    padding: 48px 20px 80px;
}
.checkout-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .82rem;
    color: var(--text-muted);
    text-decoration: none;
    margin-bottom: 28px;
    transition: color .15s;
}
.checkout-back:hover { color: var(--text); }
.checkout-header {
    margin-bottom: 32px;
}
.checkout-header h1 {
    font-family: var(--font-head);
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 6px;
}
.checkout-header p {
    font-size: .88rem;
    color: var(--text-muted);
}
.checkout-summary {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.checkout-summary-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.checkout-icon {
    font-size: 1.8rem;
}
.checkout-plan-name {
    font-family: var(--font-head);
    font-size: .95rem;
    font-weight: 700;
    color: var(--text);
}
.checkout-plan-sub {
    font-size: .75rem;
    color: var(--text-muted);
    margin-top: 2px;
}
.checkout-price {
    text-align: right;
}
.checkout-price-amount {
    font-family: var(--font-head);
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text);
}
.checkout-price-period {
    font-size: .72rem;
    color: var(--text-muted);
}
.checkout-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 28px 24px;
    margin-bottom: 20px;
}
.checkout-card h2 {
    font-family: var(--font-head);
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 18px;
}
.method-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 6px;
}
.method-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
    background: var(--bg-card2);
}
.method-option:has(input:checked) {
    border-color: var(--terra);
    background: var(--terra-soft);
}
.method-option input[type=radio] {
    accent-color: var(--terra);
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}
.method-option-icon { font-size: 1.2rem; }
.method-option-label {
    flex: 1;
}
.method-option-name {
    font-size: .88rem;
    font-weight: 600;
    color: var(--text);
}
.method-option-desc {
    font-size: .74rem;
    color: var(--text-muted);
    margin-top: 2px;
}
.payment-instructions {
    background: rgba(212,168,67,.08);
    border: 1px solid rgba(212,168,67,.25);
    border-radius: 12px;
    padding: 18px 20px;
    margin: 20px 0 4px;
}
.payment-instructions h3 {
    font-size: .86rem;
    font-weight: 700;
    color: var(--gold);
    margin-bottom: 12px;
}
.payment-step {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    font-size: .82rem;
    color: var(--text-muted);
    line-height: 1.4;
}
.payment-step:last-child { margin-bottom: 0; }
.step-num {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: rgba(212,168,67,.2);
    color: var(--gold);
    font-size: .7rem;
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 1px;
}
.merchant-number {
    display: inline-block;
    font-family: monospace;
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 6px 14px;
    margin: 8px 0;
    letter-spacing: .05em;
}
.form-group {
    margin-bottom: 18px;
}
.form-label {
    display: block;
    font-size: .82rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
}
.form-input {
    width: 100%;
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 11px 14px;
    font-size: .88rem;
    font-family: var(--font-body);
    color: var(--text);
    outline: none;
    transition: border-color .15s;
}
.form-input:focus { border-color: var(--terra); }
.form-input::placeholder { color: var(--text-faint); }
.form-hint {
    font-size: .75rem;
    color: var(--text-muted);
    margin-top: 6px;
}
.submit-btn {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    background: var(--terra);
    color: white;
    border: none;
    font-family: var(--font-head);
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    transition: opacity .2s, transform .15s;
}
.submit-btn:hover { opacity: .88; transform: translateY(-1px); }
.submit-btn:disabled { opacity: .5; cursor: not-allowed; }
.stripe-coming-soon {
    background: var(--bg-card2);
    border: 1px dashed var(--border-hover);
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    margin: 20px 0;
}
.stripe-coming-soon p {
    font-size: .84rem;
    color: var(--text-muted);
    line-height: 1.5;
}
.stripe-coming-soon strong {
    display: block;
    font-family: var(--font-head);
    font-size: .95rem;
    color: var(--text);
    margin-bottom: 8px;
}
.already-pending {
    background: rgba(212,168,67,.1);
    border: 1px solid rgba(212,168,67,.3);
    border-radius: 12px;
    padding: 16px 18px;
    font-size: .84rem;
    color: var(--gold);
    margin-bottom: 20px;
}
.alert-err {
    background: rgba(224,85,85,.1);
    border: 1px solid rgba(224,85,85,.3);
    color: #E05555;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: .82rem;
    margin-bottom: 16px;
}
</style>

<div class="checkout-wrap">

    <a href="{{ route('subscription.pricing') }}" class="checkout-back">← Retour aux plans</a>

    <div class="checkout-header">
        <h1>{{ $details['icon'] }} {{ $details['name'] }}</h1>
        <p>{{ $details['tagline'] }}</p>
    </div>

    @if($activeSub && $activeSub->plan === $plan && $activeSub->status === 'pending')
    <div class="already-pending">
        ⏳ Tu as déjà une demande en attente pour ce plan. Elle sera traitée sous 24h.
    </div>
    @endif

    @if($activeSub && $activeSub->plan === $plan && $activeSub->status === 'active')
    <div class="already-pending" style="background:rgba(42,122,72,.1);border-color:rgba(42,122,72,.3);color:#2A7A48;">
        ✅ Tu es déjà abonné à ce plan jusqu'au {{ $activeSub->expires_at?->format('d/m/Y') }}.
    </div>
    @endif

    {{-- Récap commande --}}
    <div class="checkout-summary">
        <div class="checkout-summary-left">
            <div class="checkout-icon">{{ $details['icon'] }}</div>
            <div>
                <div class="checkout-plan-name">{{ $details['name'] }}</div>
                <div class="checkout-plan-sub">Abonnement mensuel</div>
            </div>
        </div>
        <div class="checkout-price">
            <div class="checkout-price-amount">{{ $details['amount_str'] }}</div>
            <div class="checkout-price-period">par mois</div>
        </div>
    </div>

    <form method="POST" action="{{ route('subscription.store', $plan) }}" id="checkoutForm">
        @csrf

        {{-- Méthode de paiement --}}
        <div class="checkout-card">
            <h2>Mode de paiement</h2>

            <div class="method-options">
                @if(in_array('wave', $details['methods']))
                <label class="method-option">
                    <input type="radio" name="payment_method" value="wave" {{ old('payment_method') === 'wave' || count($details['methods']) === 1 && $details['methods'][0] === 'wave' ? 'checked' : '' }} onchange="updatePayment()">
                    <div class="method-option-icon">〰</div>
                    <div class="method-option-label">
                        <div class="method-option-name">Wave</div>
                        <div class="method-option-desc">Mobile money — Sénégal, Côte d'Ivoire, Mali…</div>
                    </div>
                </label>
                @endif

                @if(in_array('orange_money', $details['methods']))
                <label class="method-option">
                    <input type="radio" name="payment_method" value="orange_money" {{ old('payment_method') === 'orange_money' ? 'checked' : '' }} onchange="updatePayment()">
                    <div class="method-option-icon">🟠</div>
                    <div class="method-option-label">
                        <div class="method-option-name">Orange Money</div>
                        <div class="method-option-desc">Mobile money Orange</div>
                    </div>
                </label>
                @endif

                @if(in_array('stripe', $details['methods']))
                <label class="method-option">
                    <input type="radio" name="payment_method" value="stripe" {{ old('payment_method') === 'stripe' || count($details['methods']) === 1 && $details['methods'][0] === 'stripe' ? 'checked' : '' }} onchange="updatePayment()">
                    <div class="method-option-icon">💳</div>
                    <div class="method-option-label">
                        <div class="method-option-name">Carte bancaire</div>
                        <div class="method-option-desc">Visa, Mastercard, etc.</div>
                    </div>
                </label>
                @endif
            </div>

            @error('payment_method')
            <div class="alert-err">{{ $message }}</div>
            @enderror
        </div>

        {{-- Instructions Wave/OM --}}
        <div id="mobileMoneySection" class="checkout-card" style="display:none;">
            <h2>Instructions de paiement</h2>

            <div class="payment-instructions" id="waveInstructions" style="display:none;">
                <h3>〰 Payer via Wave</h3>
                <div class="payment-step">
                    <div class="step-num">1</div>
                    <div>Ouvre ton application <strong>Wave</strong> et effectue un transfert de <strong>2 500 FCFA</strong> vers :</div>
                </div>
                <div style="text-align:center;margin:4px 0 12px;">
                    <div class="merchant-number">{{ $merchant['wave'] }}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);">MelanoGeek — Abonnement mensuel</div>
                </div>
                <div class="payment-step">
                    <div class="step-num">2</div>
                    <div>Copie l'<strong>ID de transaction</strong> affiché dans Wave après le paiement.</div>
                </div>
                <div class="payment-step">
                    <div class="step-num">3</div>
                    <div>Colle cet ID dans le champ ci-dessous et valide ta demande.</div>
                </div>
            </div>

            <div class="payment-instructions" id="omInstructions" style="display:none;">
                <h3>🟠 Payer via Orange Money</h3>
                <div class="payment-step">
                    <div class="step-num">1</div>
                    <div>Compose <strong>#144#</strong> ou ouvre l'app Orange Money et envoie <strong>2 500 FCFA</strong> au :</div>
                </div>
                <div style="text-align:center;margin:4px 0 12px;">
                    <div class="merchant-number">{{ $merchant['orange_money'] }}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);">MelanoGeek — Abonnement mensuel</div>
                </div>
                <div class="payment-step">
                    <div class="step-num">2</div>
                    <div>Conserve le <strong>code de confirmation</strong> envoyé par SMS.</div>
                </div>
                <div class="payment-step">
                    <div class="step-num">3</div>
                    <div>Entre ce code dans le champ ci-dessous et valide ta demande.</div>
                </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
                <label class="form-label">ID / Code de transaction</label>
                <input type="text" name="transaction_id" class="form-input"
                       placeholder="Ex: W-1234567890 ou 12345678"
                       value="{{ old('transaction_id') }}"
                       autocomplete="off">
                @error('transaction_id')
                <div class="alert-err" style="margin-top:8px;">{{ $message }}</div>
                @enderror
                <div class="form-hint">Copie exactement l'ID affiché dans ton application après le paiement.</div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Confirmer l'abonnement →
            </button>
            <div style="text-align:center;font-size:.75rem;color:var(--text-muted);margin-top:10px;">
                Activation sous 24h après vérification du paiement
            </div>
        </div>

        {{-- Stripe (coming soon) --}}
        <div id="stripeSection" class="checkout-card" style="display:none;">
            <h2>Paiement par carte</h2>
            <div class="stripe-coming-soon">
                <strong>💳 Paiement carte — Bientôt disponible</strong>
                <p>L'intégration du paiement par carte bancaire est en cours de déploiement. En attendant, contacte-nous directement pour t'abonner au Plan Diaspora.</p>
            </div>
            <a href="mailto:contact@melanogeek.com?subject=Abonnement Plan Diaspora"
               style="display:block;width:100%;padding:12px;border-radius:12px;background:var(--terra);color:white;text-align:center;font-family:var(--font-head);font-size:.9rem;font-weight:700;text-decoration:none;margin-top:8px;">
                Contacter par email →
            </a>
        </div>

    </form>

</div>

<script>
function updatePayment() {
    const method = document.querySelector('input[name="payment_method"]:checked')?.value;
    const mobileSection = document.getElementById('mobileMoneySection');
    const stripeSection  = document.getElementById('stripeSection');
    const waveInstr = document.getElementById('waveInstructions');
    const omInstr   = document.getElementById('omInstructions');

    mobileSection.style.display = 'none';
    stripeSection.style.display  = 'none';
    waveInstr.style.display = 'none';
    omInstr.style.display   = 'none';

    if (method === 'wave') {
        mobileSection.style.display = 'block';
        waveInstr.style.display = 'block';
    } else if (method === 'orange_money') {
        mobileSection.style.display = 'block';
        omInstr.style.display = 'block';
    } else if (method === 'stripe') {
        stripeSection.style.display = 'block';
    }
}

// Init on load
document.addEventListener('DOMContentLoaded', function() {
    const checked = document.querySelector('input[name="payment_method"]:checked');
    if (checked) updatePayment();

    // Auto-select first method
    const first = document.querySelector('input[name="payment_method"]');
    if (first && !checked) { first.checked = true; updatePayment(); }
});
</script>
@endsection
