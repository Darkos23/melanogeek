@extends('layouts.app')

@section('title', 'Créer un compte')

@push('styles')
<style>
    /* cache la nav sur les pages auth pour un rendu full-screen propre */
    nav { display: none !important; }

    .auth-page {
        min-height: 100vh;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    /* ── LEFT ── */
    .auth-left {
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 44px 48px;
        background: linear-gradient(160deg, #1A0E06 0%, #0A0705 60%);
    }
    .auth-left-bg {
        position: absolute; inset: 0; z-index: 0;
        background:
            radial-gradient(ellipse 80% 60% at 20% 70%, rgba(200,82,42,.22) 0%, transparent 55%),
            radial-gradient(ellipse 60% 40% at 85% 20%, rgba(212,168,67,.12) 0%, transparent 50%);
    }
    .auth-left-pattern {
        position: absolute; inset: 0; z-index: 0;
        opacity: .03;
        background-image: repeating-linear-gradient(45deg, #D4A843 0, #D4A843 1px, transparent 0, transparent 50%);
        background-size: 20px 20px;
    }
    .auth-z { position: relative; z-index: 1; }

    /* Brand top */
    .auth-brand { display:flex;align-items:center;gap:12px;text-decoration:none; }
    .auth-brand-name { font-family:var(--font-head);font-weight:800;font-size:1.3rem;color:#F5EFE6; }
    .auth-brand-name span { color:var(--gold); }

    .auth-top-row { display:flex;align-items:center; }

    /* Middle : feature cards */
    .auth-middle {
        display: flex;
        flex-direction: column;
        gap: 16px;
        padding: 24px 0;
    }

    /* Stat bar */
    .auth-stat-bar {
        display: flex;
        gap: 0;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        overflow: hidden;
    }
    .auth-stat-item {
        flex: 1;
        padding: 14px 10px;
        text-align: center;
        border-right: 1px solid rgba(255,255,255,0.06);
    }
    .auth-stat-item:last-child { border-right: none; }
    .auth-stat-num {
        font-family: var(--font-head);
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--gold);
        line-height: 1;
        margin-bottom: 3px;
    }
    .auth-stat-lbl {
        font-size: .6rem;
        color: rgba(245,239,230,0.35);
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    /* Feature grid */
    .feat-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .feat-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 18px;
        padding: 20px 16px;
        position: relative;
        overflow: hidden;
        will-change: transform;
    }
    .feat-card::before {
        content: '';
        position: absolute; inset: 0;
        opacity: 0;
        transition: opacity .3s;
    }
    .feat-card:nth-child(1)::before { background: radial-gradient(ellipse at 0% 0%, rgba(200,82,42,.12), transparent 70%); }
    .feat-card:nth-child(2)::before { background: radial-gradient(ellipse at 100% 0%, rgba(212,168,67,.10), transparent 70%); }
    .feat-card:nth-child(3)::before { background: radial-gradient(ellipse at 0% 100%, rgba(45,90,61,.12), transparent 70%); }
    .feat-card:nth-child(4)::before { background: radial-gradient(ellipse at 100% 100%, rgba(200,82,42,.10), transparent 70%); }
    .feat-card:hover::before { opacity: 1; }

    .feat-icon {
        font-size: 1.6rem;
        margin-bottom: 10px;
        display: block;
        line-height: 1;
    }
    .feat-title {
        font-family: var(--font-head);
        font-size: .82rem;
        font-weight: 800;
        color: #F5EFE6;
        margin-bottom: 4px;
        letter-spacing: -.01em;
    }
    .feat-desc {
        font-size: .68rem;
        color: rgba(245,239,230,.4);
        line-height: 1.5;
    }

    /* Float animations — GPU only (transform) */
    @keyframes mg-float {
        0%, 100% { transform: translateY(0px); }
        50%       { transform: translateY(-7px); }
    }
    .feat-card:nth-child(1) { animation: mg-float 4.2s ease-in-out infinite; }
    .feat-card:nth-child(2) { animation: mg-float 4.2s ease-in-out infinite; animation-delay: -1.05s; }
    .feat-card:nth-child(3) { animation: mg-float 4.2s ease-in-out infinite; animation-delay: -2.1s; }
    .feat-card:nth-child(4) { animation: mg-float 4.2s ease-in-out infinite; animation-delay: -3.15s; }

    /* Bottom tagline */
    .auth-tagline {
        font-family: var(--font-head);
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.35;
        color: #F5EFE6;
        margin-bottom: 6px;
    }
    .auth-tagline span { color: var(--gold); }
    .auth-tagline .terra { color: var(--terracotta); }
    .auth-left-subdesc {
        font-size: 0.8rem;
        color: rgba(245,239,230,0.4);
        line-height: 1.6;
    }

    /* ── RIGHT ── */
    .auth-right {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px 40px;
        background: var(--bg);
        transition: background .35s;
        overflow-y: auto;
    }
    .auth-form-wrap { width:100%; max-width:440px; padding: 20px 0; }

    .auth-title { font-family:var(--font-head);font-size:1.9rem;font-weight:800;letter-spacing:-.02em;color:var(--cream);margin-bottom:6px; }
    .auth-subtitle { font-size:.88rem;color:var(--cream-muted);margin-bottom:28px; }
    .auth-subtitle a { color:var(--gold);text-decoration:none;font-weight:500; }
    .auth-subtitle a:hover { text-decoration:underline; }

    .form-group { margin-bottom:16px; }
    .form-grid { display:grid;grid-template-columns:1fr 1fr;gap:12px; }
    .form-label { display:block;font-size:.76rem;font-weight:600;letter-spacing:.04em;color:var(--cream-muted);margin-bottom:7px;text-transform:uppercase; }
    .form-input { width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:12px 16px;color:var(--cream);font-family:var(--font-body);font-size:.92rem;transition:border-color .2s,box-shadow .2s;outline:none;cursor:none; }
    .form-input::placeholder { color:var(--muted); }
    .form-input:focus { border-color:var(--terracotta);box-shadow:0 0 0 3px rgba(200,82,42,.1); }
    .form-input.is-error { border-color:#E05555; }
    .form-error { font-size:.75rem;color:#E05555;margin-top:5px; }

    .country-select { display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:4px; }
    .country-option { position:relative; }
    .country-option input[type="radio"] { position:absolute;opacity:0;width:0;height:0; }
    .country-option label { display:flex;flex-direction:column;align-items:center;gap:3px;padding:12px 6px;border-radius:12px;border:1px solid var(--border);cursor:none;transition:all .2s;text-align:center;background:var(--bg-card); }
    .country-option label:hover { border-color:rgba(212,168,67,.4); }
    .country-option input:checked + label { border-color:var(--terracotta);background:rgba(200,82,42,.08);box-shadow:0 0 0 2px rgba(200,82,42,.15); }
    .country-flag { font-size:1.3rem; display:flex; align-items:center; justify-content:center; height:1.6rem; }
    .country-name { font-size:.68rem;font-weight:600;color:var(--cream-muted); }
    .country-price { font-size:.62rem;color:var(--gold);font-weight:700; }

    .btn-submit { width:100%;background:var(--terracotta);color:#fff;border:none;border-radius:12px;padding:14px;font-family:var(--font-head);font-size:1rem;font-weight:700;cursor:none;transition:background .2s,transform .15s,box-shadow .2s;margin-top:6px; }
    .btn-submit:hover { background:var(--accent);transform:translateY(-1px);box-shadow:0 12px 28px rgba(200,82,42,.3); }
    .terms-text { font-size:.73rem;color:var(--muted);text-align:center;margin-top:12px;line-height:1.6; }
    .terms-text a { color:var(--gold);text-decoration:none; }

    @@media (max-width: 768px) {
        .auth-page { grid-template-columns:1fr; }
        .auth-left { display:none; }
        .auth-right { padding:60px 24px 40px; align-items:flex-start; }
        .form-grid { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
<div class="auth-page">

    <!-- ── LEFT ── -->
    <div class="auth-left">
        <div class="auth-left-bg"></div>
        <div class="auth-left-pattern"></div>

        <!-- TOP : brand -->
        <div class="auth-top-row auth-z">
            <a href="{{ route('home') }}" class="auth-brand">
                <svg width="34" height="34" viewBox="0 0 42 42" fill="none">
                    <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="#1A1208" stroke="#D4A843" stroke-width="0.8"/>
                    <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#C8522A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    <circle cx="21" cy="9" r="2.5" fill="#D4A843"/>
                </svg>
                <span class="auth-brand-name">Melano<span>Geek</span></span>
            </a>
        </div>

        <!-- MIDDLE : feature cards -->
        <div class="auth-middle auth-z">

            <!-- Stats bar -->
            <div class="auth-stat-bar">
                <div class="auth-stat-item">
                    <div class="auth-stat-num">2 400+</div>
                    <div class="auth-stat-lbl">Créateurs</div>
                </div>
                <div class="auth-stat-item">
                    <div class="auth-stat-num">15K+</div>
                    <div class="auth-stat-lbl">Publications</div>
                </div>
                <div class="auth-stat-item">
                    <div class="auth-stat-num">100%</div>
                    <div class="auth-stat-lbl">Africain</div>
                </div>
            </div>

            <!-- Feature grid 2×2 -->
            <div class="feat-grid">
                <div class="feat-card">
                    <span class="feat-icon">🎨</span>
                    <div class="feat-title">Publie ton art</div>
                    <div class="feat-desc">Photos, vidéos, sons — sans algorithme qui punit.</div>
                </div>
                <div class="feat-card">
                    <span class="feat-icon">🛍️</span>
                    <div class="feat-title">Vends tes services</div>
                    <div class="feat-desc">Marketplace intégré pour monétiser ton talent.</div>
                </div>
                <div class="feat-card">
                    <span class="feat-icon">💬</span>
                    <div class="feat-title">Messagerie directe</div>
                    <div class="feat-desc">Connecte-toi avec tes fans et d'autres créateurs.</div>
                </div>
                <div class="feat-card">
                    <span class="feat-icon">✅</span>
                    <div class="feat-title">Créateurs vérifiés</div>
                    <div class="feat-desc">Communauté exclusive, chaque profil est validé.</div>
                </div>
            </div>

        </div>

        <!-- BOTTOM : tagline -->
        <div class="auth-z">
            <div class="auth-tagline">Rejoins <span>2 400+</span><br>créateurs <span class="terra">sénégalais.</span></div>
            <div class="auth-left-subdesc">Dakar, Saint-Louis, Ziguinchor et toute l'Afrique — ta communauté t'attend.</div>
        </div>
    </div>

    <!-- ── RIGHT : formulaire ── -->
    <div class="auth-right">
        <div class="auth-form-wrap">
            <div class="auth-title">Rejoindre MelanoGeek</div>
            <div class="auth-subtitle">Déjà membre ? <a href="{{ route('login') }}">Se connecter →</a></div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Choix du type de compte --}}
                <div class="form-group">
                    <label class="form-label">Je suis…</label>
                    <div class="country-select" style="grid-template-columns:1fr 1fr;">
                        <div class="country-option">
                            <input type="radio" id="at_fan" name="account_type" value="fan" {{ old('account_type', 'fan') === 'fan' ? 'checked' : '' }} onchange="toggleAccountType()">
                            <label for="at_fan">
                                <span class="country-flag">👁️</span>
                                <span class="country-name">Fan / Spectateur</span>
                                <span class="country-price">Accès immédiat</span>
                            </label>
                        </div>
                        <div class="country-option">
                            <input type="radio" id="at_creator" name="account_type" value="creator" {{ old('account_type') === 'creator' ? 'checked' : '' }} onchange="toggleAccountType()">
                            <label for="at_creator">
                                <span class="country-flag">🎨</span>
                                <span class="country-name">Créateur</span>
                                <span class="country-price">Candidature</span>
                            </label>
                        </div>
                    </div>
                    @error('account_type')<div class="form-error">⚠ {{ $message }}</div>@enderror
                </div>

                {{-- Bannière dynamique --}}
                <div id="banner-fan" style="background:rgba(45,90,61,.1);border:1px solid rgba(45,90,61,.25);border-radius:12px;padding:10px 14px;margin-bottom:16px;font-size:.8rem;color:var(--cream-muted);line-height:1.5;{{ old('account_type') === 'creator' ? 'display:none' : '' }}">
                    👁️ <strong style="color:#7DDF9A;">Accès immédiat</strong> — suis tes créateurs préférés, like, commente et découvre.
                </div>
                <div id="banner-creator" style="background:rgba(212,168,67,.08);border:1px solid rgba(212,168,67,.2);border-radius:12px;padding:10px 14px;margin-bottom:16px;font-size:.8rem;color:var(--cream-muted);line-height:1.5;{{ old('account_type') !== 'creator' ? 'display:none' : '' }}">
                    🎨 <strong style="color:var(--gold);">Candidature créateur</strong> — examinée sous 48h avant activation.
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="name">Nom complet</label>
                        <input class="form-input {{ $errors->has('name') ? 'is-error' : '' }}" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ton nom" required autofocus>
                        @error('name')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="username">Pseudo</label>
                        <input class="form-input {{ $errors->has('username') ? 'is-error' : '' }}" type="text" id="username" name="username" value="{{ old('username') }}" placeholder="ton.pseudo-95" required>
                        @error('username')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ton@email.com" required>
                    @error('email')<div class="form-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="password">Mot de passe</label>
                        <input class="form-input {{ $errors->has('password') ? 'is-error' : '' }}" type="password" id="password" name="password" placeholder="••••••••" required>
                        @error('password')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirmer</label>
                        <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tu viens d'où ?</label>
                    <div class="country-select">
                        <div class="country-option">
                            <input type="radio" id="ct_senegal" name="country_type" value="senegal" {{ old('country_type','senegal') === 'senegal' ? 'checked' : '' }}>
                            <label for="ct_senegal">
                                <span class="country-flag">
                                    <svg viewBox="0 0 22 15" xmlns="http://www.w3.org/2000/svg" style="border-radius:2px;display:block;height:1.3rem;width:auto;">
                                        <rect width="8" height="15" fill="#00A859"/>
                                        <rect x="7" width="8" height="15" fill="#FDEF42"/>
                                        <rect x="14" width="8" height="15" fill="#E31B23"/>
                                        <polygon points="11,3 11.7,5.2 14,5.2 12.2,6.5 12.9,8.7 11,7.4 9.1,8.7 9.8,6.5 8,5.2 10.3,5.2" fill="#00A859"/>
                                    </svg>
                                </span>
                                <span class="country-name">Sénégal</span>
                                <span class="country-price">1 mois offert</span>
                            </label>
                        </div>
                        <div class="country-option">
                            <input type="radio" id="ct_africa" name="country_type" value="africa" {{ old('country_type') === 'africa' ? 'checked' : '' }}>
                            <label for="ct_africa">
                                <span class="country-flag">🌍</span>
                                <span class="country-name">Afrique</span>
                                <span class="country-price">2 500 FCFA</span>
                            </label>
                        </div>
                        <div class="country-option">
                            <input type="radio" id="ct_diaspora" name="country_type" value="diaspora" {{ old('country_type') === 'diaspora' ? 'checked' : '' }}>
                            <label for="ct_diaspora">
                                <span class="country-flag">✈️</span>
                                <span class="country-name">Diaspora</span>
                                <span class="country-price">9,99€/mois</span>
                            </label>
                        </div>
                    </div>
                    @error('country_type')<div class="form-error">⚠ {{ $message }}</div>@enderror
                </div>

                {{-- Champs créateur (cachés pour les fans) --}}
                <div id="creator-fields" style="{{ old('account_type') !== 'creator' ? 'display:none' : '' }}">
                    <div class="form-group">
                        <label class="form-label" for="creator_category">Ta catégorie créateur</label>
                        <select class="form-input {{ $errors->has('creator_category') ? 'is-error' : '' }}" id="creator_category" name="creator_category">
                            <option value="" disabled {{ !old('creator_category') ? 'selected' : '' }}>Choisir une catégorie…</option>
                            <option value="Mode & Beauté" {{ old('creator_category') === 'Mode & Beauté' ? 'selected' : '' }}>👗 Mode & Beauté</option>
                            <option value="Musique" {{ old('creator_category') === 'Musique' ? 'selected' : '' }}>🎵 Musique</option>
                            <option value="Humour & Divertissement" {{ old('creator_category') === 'Humour & Divertissement' ? 'selected' : '' }}>😂 Humour & Divertissement</option>
                            <option value="Cuisine & Food" {{ old('creator_category') === 'Cuisine & Food' ? 'selected' : '' }}>🍲 Cuisine & Food</option>
                            <option value="Photographie" {{ old('creator_category') === 'Photographie' ? 'selected' : '' }}>📸 Photographie</option>
                            <option value="Vidéo & Film" {{ old('creator_category') === 'Vidéo & Film' ? 'selected' : '' }}>🎬 Vidéo & Film</option>
                            <option value="Fitness & Bien-être" {{ old('creator_category') === 'Fitness & Bien-être' ? 'selected' : '' }}>💪 Fitness & Bien-être</option>
                            <option value="Business & Entrepreneuriat" {{ old('creator_category') === 'Business & Entrepreneuriat' ? 'selected' : '' }}>💼 Business & Entrepreneuriat</option>
                            <option value="Design & Art" {{ old('creator_category') === 'Design & Art' ? 'selected' : '' }}>🎨 Design & Art</option>
                            <option value="Écriture & Poésie" {{ old('creator_category') === 'Écriture & Poésie' ? 'selected' : '' }}>✍️ Écriture & Poésie</option>
                            <option value="Coaching & Formation" {{ old('creator_category') === 'Coaching & Formation' ? 'selected' : '' }}>🎓 Coaching & Formation</option>
                            <option value="Lifestyle & Voyage" {{ old('creator_category') === 'Lifestyle & Voyage' ? 'selected' : '' }}>✈️ Lifestyle & Voyage</option>
                            <option value="Autre" {{ old('creator_category') === 'Autre' ? 'selected' : '' }}>💡 Autre</option>
                        </select>
                        @error('creator_category')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="creator_bio">Présente-toi en tant que créateur</label>
                        <textarea class="form-input {{ $errors->has('creator_bio') ? 'is-error' : '' }}" id="creator_bio" name="creator_bio" rows="3" placeholder="Ce que tu crées, ton univers, pourquoi rejoindre MelanoGeek…" style="resize:vertical;">{{ old('creator_bio') }}</textarea>
                        @error('creator_bio')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="creator_social">Lien vers ton contenu <span style="color:var(--terracotta);font-size:.7rem;">*obligatoire</span></label>
                        <input class="form-input {{ $errors->has('creator_social') ? 'is-error' : '' }}" type="url" id="creator_social" name="creator_social" value="{{ old('creator_social') }}" placeholder="https://instagram.com/tonpseudo">
                        <div style="font-size:.72rem;color:var(--muted);margin-top:5px;">Instagram, TikTok, YouTube, SoundCloud… — sert à vérifier que tu es bien créateur.</div>
                        @error('creator_social')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    <span id="submit-fan">Créer mon compte →</span>
                    <span id="submit-creator" style="display:none;">Envoyer ma candidature →</span>
                </button>
                <div class="terms-text">
                    En créant un compte tu acceptes nos <a href="#">Conditions d'utilisation</a> et notre <a href="#">Politique de confidentialité</a>.
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleAccountType() {
    const isCreator = document.getElementById('at_creator').checked;

    document.getElementById('creator-fields').style.display = isCreator ? 'block' : 'none';
    document.getElementById('banner-fan').style.display     = isCreator ? 'none'  : 'block';
    document.getElementById('banner-creator').style.display = isCreator ? 'block' : 'none';
    document.getElementById('submit-fan').style.display     = isCreator ? 'none'  : 'inline';
    document.getElementById('submit-creator').style.display = isCreator ? 'inline': 'none';

    // Désactiver les champs créateur quand ils sont cachés
    // → les exclut de la validation HTML5 ET de l'envoi du formulaire
    const creatorInputs = document.querySelectorAll('#creator-fields input, #creator-fields select, #creator-fields textarea');
    creatorInputs.forEach(el => {
        el.disabled = !isCreator;
    });
}

// Initialisation au chargement : désactiver si fan sélectionné par défaut
document.addEventListener('DOMContentLoaded', function () {
    toggleAccountType();
});
</script>
@endpush
@endsection

