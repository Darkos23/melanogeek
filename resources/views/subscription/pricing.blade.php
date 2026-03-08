@extends('layouts.app')

@section('title', 'Abonnements — MelanoGeek')

@section('content')
<style>
.pricing-wrap {
    max-width: 860px;
    margin: 0 auto;
    padding: 96px 20px 80px;
}
.pricing-hero {
    text-align: center;
    margin-bottom: 56px;
}
.pricing-hero-eyebrow {
    display: inline-block;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--terra);
    background: var(--terra-soft);
    padding: 4px 14px;
    border-radius: 100px;
    margin-bottom: 16px;
}
.pricing-hero h1 {
    font-family: var(--font-head);
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    font-weight: 800;
    color: var(--text);
    line-height: 1.2;
    margin-bottom: 14px;
}
.pricing-hero p {
    font-size: 1rem;
    color: var(--text-muted);
    max-width: 520px;
    margin: 0 auto;
    line-height: 1.6;
}
.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 48px;
}
.pricing-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 32px 28px;
    position: relative;
    transition: border-color .2s, transform .2s;
}
.pricing-card:hover {
    border-color: var(--border-hover);
    transform: translateY(-2px);
}
.pricing-card.popular {
    border-color: rgba(212,168,67,.4);
    background: linear-gradient(160deg, var(--bg-card) 0%, rgba(212,168,67,.04) 100%);
}
.popular-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--gold);
    color: #1C1208;
    font-size: .7rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 4px 14px;
    border-radius: 100px;
    white-space: nowrap;
}
.pricing-card-icon {
    font-size: 2rem;
    margin-bottom: 12px;
}
.pricing-card-name {
    font-family: var(--font-head);
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 4px;
}
.pricing-card-tagline {
    font-size: .8rem;
    color: var(--text-muted);
    margin-bottom: 24px;
}
.pricing-card-price {
    display: flex;
    align-items: baseline;
    gap: 4px;
    margin-bottom: 8px;
}
.pricing-card-amount {
    font-family: var(--font-head);
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--text);
}
.pricing-card-period {
    font-size: .85rem;
    color: var(--text-muted);
}
.pricing-card-divider {
    height: 1px;
    background: var(--border);
    margin: 24px 0;
}
.pricing-features {
    list-style: none;
    margin-bottom: 28px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.pricing-features li {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .85rem;
    color: var(--text-muted);
}
.pricing-features li::before {
    content: '✓';
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: rgba(42,122,72,.15);
    color: #2A7A48;
    font-size: .7rem;
    font-weight: 700;
    flex-shrink: 0;
}
.pricing-btn {
    display: block;
    width: 100%;
    padding: 13px;
    border-radius: 12px;
    font-family: var(--font-head);
    font-size: .9rem;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    border: none;
    transition: opacity .2s, transform .15s;
}
.pricing-btn:hover { opacity: .85; transform: translateY(-1px); }
.pricing-btn-gold {
    background: var(--gold);
    color: #1C1208;
}
.pricing-btn-terra {
    background: var(--terra);
    color: white;
}
.pricing-btn-outline {
    background: transparent;
    border: 1px solid var(--border-hover);
    color: var(--text-muted);
    font-size: .82rem;
    padding: 10px;
}
.pricing-methods {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 14px;
}
.method-pill {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .72rem;
    font-weight: 600;
    color: var(--text-muted);
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 100px;
    padding: 3px 10px;
}
.pricing-faq {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 32px;
}
.pricing-faq h3 {
    font-family: var(--font-head);
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 20px;
}
.faq-item {
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
}
.faq-item:last-child { border-bottom: none; }
.faq-q {
    font-size: .85rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 6px;
}
.faq-a {
    font-size: .82rem;
    color: var(--text-muted);
    line-height: 1.5;
}
.alert-info {
    background: rgba(212,168,67,.1);
    border: 1px solid rgba(212,168,67,.3);
    color: var(--gold);
    padding: 12px 16px;
    border-radius: 10px;
    font-size: .84rem;
    margin-bottom: 24px;
}
.alert-success {
    background: rgba(42,122,72,.12);
    border: 1px solid rgba(42,122,72,.3);
    color: #2A7A48;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: .84rem;
    margin-bottom: 24px;
}
.current-plan-banner {
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 14px 18px;
    font-size: .83rem;
    color: var(--text-muted);
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    gap: 10px;
}
</style>

<div class="pricing-wrap">

    @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('info'))
    <div class="alert-info">ℹ {{ session('info') }}</div>
    @endif

    <div class="pricing-hero">
        <div class="pricing-hero-eyebrow">Abonnements</div>
        <h1>Accède à tous les contenus exclusifs</h1>
        <p>Choisis le plan adapté à ta situation. Tous les plans donnent accès au même contenu exclusif des créateurs MelanoGeek.</p>
    </div>

    @auth
        @if($activeSub)
        <div class="current-plan-banner" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <span>
                @if($activeSub->status === 'active')
                    ✅ Tu es actuellement abonné au <strong style="color:var(--text);margin:0 4px;">Plan {{ $activeSub->plan === 'african' ? 'Afrique' : 'Diaspora' }}</strong> — expire le {{ $activeSub->expires_at?->format('d/m/Y') ?? '—' }}
                @else
                    ⏳ Ton abonnement <strong style="color:var(--text);margin:0 4px;">Plan {{ $activeSub->plan === 'african' ? 'Afrique' : 'Diaspora' }}</strong> est en attente de validation.
                @endif
            </span>
            <form method="POST" action="{{ route('subscription.cancel') }}"
                  onsubmit="return confirm('Annuler ton abonnement ? Il restera actif jusqu\'à expiration.')">
                @csrf @method('DELETE')
                <button type="submit" style="background:none;border:1px solid rgba(224,85,85,.4);color:#E05555;padding:5px 14px;border-radius:8px;font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;"
                        onmouseover="this.style.background='rgba(224,85,85,.1)'" onmouseout="this.style.background='none'">
                    Annuler l'abonnement
                </button>
            </form>
        </div>
        @endif
    @endauth

    <div class="pricing-grid">

        {{-- Plan Afrique --}}
        <div class="pricing-card popular">
            <div class="popular-badge">Populaire</div>
            <div class="pricing-card-icon">🌍</div>
            <div class="pricing-card-name">Plan Afrique</div>
            <div class="pricing-card-tagline">Pour la communauté africaine</div>
            <div class="pricing-card-price">
                <div class="pricing-card-amount">2 500</div>
                <div class="pricing-card-period">FCFA / mois</div>
            </div>
            <div class="pricing-card-divider"></div>
            <ul class="pricing-features">
                <li>Accès à tous les contenus exclusifs</li>
                <li>Messagerie directe avec les créateurs</li>
                <li>Commentaires & réactions illimités</li>
                <li>Paiement Wave & Orange Money</li>
            </ul>

            @auth
                @if($activeSub && $activeSub->plan === 'african')
                    <div class="pricing-btn pricing-btn-outline" style="cursor:default;">Plan actuel</div>
                @else
                    <a href="{{ route('subscription.checkout', 'african') }}" class="pricing-btn pricing-btn-gold">
                        S'abonner avec Wave / OM
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="pricing-btn pricing-btn-gold">Connexion pour s'abonner</a>
            @endauth

            <div class="pricing-methods" style="margin-top:16px;">
                <div class="method-pill">〰 Wave</div>
                <div class="method-pill">🟠 Orange Money</div>
            </div>
        </div>

        {{-- Plan Diaspora --}}
        <div class="pricing-card">
            <div class="pricing-card-icon">✈️</div>
            <div class="pricing-card-name">Plan Diaspora</div>
            <div class="pricing-card-tagline">Pour la diaspora sénégalaise</div>
            <div class="pricing-card-price">
                <div class="pricing-card-amount">9,99 €</div>
                <div class="pricing-card-period">/ mois</div>
            </div>
            <div class="pricing-card-divider"></div>
            <ul class="pricing-features">
                <li>Accès à tous les contenus exclusifs</li>
                <li>Messagerie directe avec les créateurs</li>
                <li>Commentaires & réactions illimités</li>
                <li>Paiement par carte bancaire</li>
            </ul>

            @auth
                @if($activeSub && $activeSub->plan === 'global')
                    <div class="pricing-btn pricing-btn-outline" style="cursor:default;">Plan actuel</div>
                @else
                    <a href="{{ route('subscription.checkout', 'global') }}" class="pricing-btn pricing-btn-terra">
                        S'abonner par carte
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="pricing-btn pricing-btn-terra">Connexion pour s'abonner</a>
            @endauth

            <div class="pricing-methods" style="margin-top:16px;">
                <div class="method-pill">💳 Carte bancaire</div>
            </div>
        </div>

    </div>

    {{-- FAQ --}}
    <div class="pricing-faq">
        <h3>Questions fréquentes</h3>
        <div class="faq-item">
            <div class="faq-q">Comment fonctionne le paiement Wave / Orange Money ?</div>
            <div class="faq-a">Tu effectues un virement depuis ton application Wave ou Orange Money vers notre numéro marchand, puis tu colles l'ID de transaction dans le formulaire. L'abonnement est activé sous 24h après vérification.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">Quelle est la durée de l'abonnement ?</div>
            <div class="faq-a">Tous les abonnements sont mensuels (30 jours). Tu peux renouveler à tout moment en soumettant une nouvelle demande.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">Puis-je annuler mon abonnement ?</div>
            <div class="faq-a">Ton abonnement ne se renouvelle pas automatiquement. Il expire simplement au bout de 30 jours. Pas de prélèvement automatique.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">Les utilisateurs sénégalais ont-ils un accès gratuit ?</div>
            <div class="faq-a">Pendant la phase de lancement, certains utilisateurs locaux bénéficient d'un accès offert. Le Plan Sénégal à 2 500 FCFA/mois reste la solution la plus accessible pour soutenir les créateurs.</div>
        </div>
    </div>

</div>
@endsection
