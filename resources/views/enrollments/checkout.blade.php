@extends('layouts.app')

@section('title', 'Inscription — ' . $course->title)

@push('styles')
<style>
.checkout-page {
    padding-top: calc(80px + env(safe-area-inset-top));
    min-height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-bottom: 60px;
}
.checkout-wrap {
    width: 100%;
    max-width: 560px;
    padding: 40px 32px;
}
.checkout-title {
    font-family: var(--font-head);
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 6px;
}
.checkout-subtitle {
    color: var(--text-muted);
    font-size: .88rem;
    margin-bottom: 28px;
}

/* Résumé cours */
.course-summary {
    display: flex;
    gap: 14px;
    align-items: center;
    padding: 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 24px;
}
.summary-thumb {
    width: 72px;
    height: 40px;
    border-radius: 6px;
    object-fit: cover;
    background: var(--bg-card2);
    flex-shrink: 0;
}
.summary-thumb-placeholder {
    width: 72px;
    height: 40px;
    border-radius: 6px;
    background: linear-gradient(135deg, var(--terra-soft), var(--gold-soft));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.summary-title { font-weight: 700; font-size: .92rem; }
.summary-price { font-size: 1.1rem; font-weight: 800; color: var(--gold); margin-left: auto; }

/* Form */
.form-group { margin-bottom: 18px; }
.form-label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: 7px; }
.form-input {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    font-size: .9rem;
    outline: none;
    transition: border-color .2s;
}
.form-input:focus { border-color: var(--terra); }

.payment-methods {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 18px;
}
.payment-method-label {
    position: relative;
    cursor: pointer;
}
.payment-method-label input { position: absolute; opacity: 0; }
.payment-method-card {
    padding: 16px;
    background: var(--bg-card);
    border: 2px solid var(--border);
    border-radius: 12px;
    text-align: center;
    transition: border-color .2s, background .2s;
}
.payment-method-label input:checked + .payment-method-card {
    border-color: var(--terra);
    background: var(--terra-soft);
}
.payment-icon { font-size: 1.8rem; margin-bottom: 4px; }
.payment-name { font-size: .82rem; font-weight: 600; }

.instructions {
    padding: 14px;
    background: var(--gold-soft);
    border: 1px solid var(--gold);
    border-radius: 10px;
    font-size: .85rem;
    color: var(--text-muted);
    margin-bottom: 20px;
    line-height: 1.6;
}
.instructions strong { color: var(--text); }

.btn-pay {
    width: 100%;
    padding: 14px;
    background: var(--terra);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: opacity .2s;
}
.btn-pay:hover { opacity: .88; }

.error { color: #e53e3e; font-size: .82rem; margin-top: 5px; }

@media (max-width: 480px) {
    .checkout-wrap { padding: 24px 16px; }
    .checkout-title { font-size: 1.2rem; }
}
</style>
@endpush

@section('content')
<div class="checkout-page">
    <div class="checkout-wrap">
        <h1 class="checkout-title">S'inscrire à la formation</h1>
        <p class="checkout-subtitle">Paiement sécurisé — accès immédiat après confirmation</p>

        {{-- Résumé --}}
        <div class="course-summary">
            @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="summary-thumb">
            @else
                <div class="summary-thumb-placeholder">📚</div>
            @endif
            <div>
                <div class="summary-title">{{ $course->title }}</div>
                <div style="font-size:.78rem;color:var(--text-muted)">{{ $course->total_lessons }} leçons</div>
            </div>
            <div class="summary-price">{{ number_format($course->price, 0, ',', ' ') }} FCFA</div>
        </div>

        <form method="POST" action="{{ route('enrollments.store', $course->slug) }}">
            @csrf

            {{-- Méthode de paiement --}}
            <div class="form-group">
                <label class="form-label">Méthode de paiement</label>
                <div class="payment-methods">
                    <label class="payment-method-label">
                        <input type="radio" name="payment_method" value="wave" checked>
                        <div class="payment-method-card">
                            <div class="payment-icon">💙</div>
                            <div class="payment-name">Wave</div>
                        </div>
                    </label>
                    <label class="payment-method-label">
                        <input type="radio" name="payment_method" value="orange_money">
                        <div class="payment-method-card">
                            <div class="payment-icon">🟠</div>
                            <div class="payment-name">Orange Money</div>
                        </div>
                    </label>
                </div>
                @error('payment_method')<div class="error">{{ $message }}</div>@enderror
            </div>

            {{-- Instructions --}}
            <div class="instructions">
                <strong>Comment payer :</strong><br>
                Envoyez <strong>{{ number_format($course->price, 0, ',', ' ') }} FCFA</strong> au numéro
                <strong>{{ \App\Models\Setting::get('wave_number', '77 XXX XX XX') }}</strong>
                avec la référence <strong>WAXTU-{{ auth()->id() }}-{{ $course->id }}</strong>,
                puis entrez votre ID de transaction ci-dessous.
            </div>

            {{-- Transaction ID --}}
            <div class="form-group">
                <label class="form-label" for="transaction_id">ID de transaction</label>
                <input type="text" name="transaction_id" id="transaction_id" class="form-input"
                       placeholder="Ex: TRX-12345678" value="{{ old('transaction_id') }}" required>
                @error('transaction_id')<div class="error">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-pay">Confirmer l'inscription</button>
        </form>
    </div>
</div>
@endsection
