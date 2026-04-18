@extends('layouts.app')

@section('title', 'Créer un compte — MelanoGeek')

@push('styles')
<style>
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
    background: #0e0e0e;
}
.auth-left-grid {
    position: absolute; inset: 0; z-index: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
    background-size: 40px 40px;
    mask-image: radial-gradient(ellipse 80% 80% at 30% 50%, black 20%, transparent 100%);
}
.auth-left-glow {
    position: absolute; inset: 0; z-index: 0;
    background:
        radial-gradient(ellipse 70% 55% at 20% 60%, rgba(212,168,67,.11) 0%, transparent 60%),
        radial-gradient(ellipse 50% 35% at 85% 20%, rgba(200,82,42,.07) 0%, transparent 50%);
}
.auth-z { position: relative; z-index: 1; }

.auth-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.auth-brand-name { font-family: var(--font-head); font-weight: 800; font-size: 1.25rem; color: #f5f5f5; }
.auth-brand-name span { color: var(--gold); }

/* Stats bar */
.auth-stat-bar {
    display: flex;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
}
.auth-stat-item {
    flex: 1;
    padding: 14px 10px;
    text-align: center;
    border-right: 1px solid rgba(255,255,255,.06);
}
.auth-stat-item:last-child { border-right: none; }
.auth-stat-num {
    font-family: var(--font-head);
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--gold);
    line-height: 1;
    margin-bottom: 3px;
}
.auth-stat-lbl {
    font-size: .58rem;
    color: rgba(255,255,255,.28);
    text-transform: uppercase;
    letter-spacing: .06em;
}

/* Feature cards */
.feat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.feat-card {
    background: rgba(255,255,255,.03);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 16px;
    padding: 18px 14px;
}
.feat-icon { font-size: 1.5rem; margin-bottom: 9px; display: block; line-height: 1; }
.feat-title {
    font-family: var(--font-head);
    font-size: .8rem;
    font-weight: 700;
    color: #f0f0f0;
    margin-bottom: 3px;
}
.feat-desc { font-size: .66rem; color: rgba(255,255,255,.32); line-height: 1.5; }

/* Bottom tagline */
.auth-tagline {
    font-family: var(--font-head);
    font-size: 1.15rem;
    font-weight: 800;
    line-height: 1.35;
    color: #f0f0f0;
    margin-bottom: 5px;
    letter-spacing: -.02em;
}
.auth-tagline .gold { color: var(--gold); }
.auth-left-subdesc { font-size: .78rem; color: rgba(255,255,255,.30); line-height: 1.6; }

/* ── RIGHT ── */
.auth-right {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    background: var(--bg);
    overflow-y: auto;
}
.auth-form-wrap { width: 100%; max-width: 440px; padding: 20px 0; }

.auth-title {
    font-family: var(--font-head);
    font-size: 1.8rem;
    font-weight: 800;
    letter-spacing: -.02em;
    color: var(--text);
    margin-bottom: 6px;
}
.auth-subtitle { font-size: .88rem; color: var(--text-muted); margin-bottom: 26px; }
.auth-subtitle a { color: var(--gold); text-decoration: none; font-weight: 500; }
.auth-subtitle a:hover { text-decoration: underline; }

.form-group { margin-bottom: 16px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.form-label {
    display: block;
    font-size: .73rem;
    font-weight: 600;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 7px;
}
.form-input {
    width: 100%;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px 15px;
    color: var(--text);
    font-family: var(--font-body);
    font-size: .92rem;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.form-input::placeholder { color: var(--text-faint); }
.form-input:focus {
    border-color: rgba(255,255,255,.28);
    box-shadow: 0 0 0 3px rgba(255,255,255,.04);
}
.form-input.is-error { border-color: #f87171; }
.form-error { font-size: .74rem; color: #f87171; margin-top: 5px; }

.btn-submit {
    width: 100%;
    background: rgba(255,255,255,.90);
    color: rgba(0,0,0,.90);
    border: none;
    border-radius: 9999px;
    height: 48px;
    font-family: var(--font-head);
    font-size: .875rem;
    font-weight: 500;
    letter-spacing: .05em;
    text-transform: uppercase;
    cursor: pointer;
    margin-top: 6px;
    transition: background .15s, transform .15s;
}
.btn-submit:hover { background: #fff; }
.btn-submit:active { transform: scale(.98); }

.terms-text {
    font-size: .71rem;
    color: var(--text-faint);
    text-align: center;
    margin-top: 12px;
    line-height: 1.6;
}
.terms-text a { color: var(--text-muted); text-decoration: underline; text-underline-offset: 2px; }

.auth-divider {
    display: flex; align-items: center; gap: 14px;
    margin: 20px 0;
}
.auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
.auth-divider span { font-size: .72rem; color: var(--text-faint); text-transform: uppercase; letter-spacing: .06em; white-space: nowrap; }

.btn-social {
    width: 100%;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px;
    color: var(--text);
    font-family: var(--font-body);
    font-size: .88rem;
    font-weight: 500;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    text-decoration: none;
    transition: border-color .18s, background .18s;
}
.btn-social:hover { border-color: rgba(255,255,255,.2); background: var(--bg-card2); }

@@media (max-width: 768px) {
    .auth-page { grid-template-columns: 1fr; }
    .auth-left { display: none; }
    .auth-right { padding: 60px 24px 48px; align-items: flex-start; }
    .form-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="auth-page">

    <!-- ── LEFT ── -->
    <div class="auth-left">
        <div class="auth-left-grid"></div>
        <div class="auth-left-glow"></div>

        <div class="auth-z">
            <a href="{{ route('home') }}" class="auth-brand">
                <svg width="32" height="32" viewBox="0 0 42 42" fill="none">
                    <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="#1A1208" stroke="#D4A843" stroke-width="0.8"/>
                    <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#C8522A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    <circle cx="21" cy="9" r="2.5" fill="#D4A843"/>
                </svg>
                <span class="auth-brand-name">Melano<span>Geek</span></span>
            </a>
        </div>

        <div class="auth-z" style="display:flex;flex-direction:column;gap:16px">
            <div class="auth-stat-bar">
                <div class="auth-stat-item">
                    <div class="auth-stat-num">{{ number_format($membersCount) }}</div>
                    <div class="auth-stat-lbl">Membres</div>
                </div>
                <div class="auth-stat-item">
                    <div class="auth-stat-num">{{ number_format($postsCount) }}</div>
                    <div class="auth-stat-lbl">Articles</div>
                </div>
                <div class="auth-stat-item">
                    <div class="auth-stat-num">100%</div>
                    <div class="auth-stat-lbl">Africain</div>
                </div>
            </div>

            <div class="feat-grid">
                <div class="feat-card">
                    <span class="feat-icon">🎌</span>
                    <div class="feat-title">Blog & articles</div>
                    <div class="feat-desc">Manga, gaming, tech — la culture geek africaine.</div>
                </div>
                <div class="feat-card">
                    <span class="feat-icon">💬</span>
                    <div class="feat-title">Forum actif</div>
                    <div class="feat-desc">Débats, recommandations, discussions en communauté.</div>
                </div>
                <div class="feat-card">
                    <span class="feat-icon">🌍</span>
                    <div class="feat-title">Vue d'Afrique</div>
                    <div class="feat-desc">Un regard africain sur la pop culture mondiale.</div>
                </div>
                <div class="feat-card">
                    <span class="feat-icon">🎮</span>
                    <div class="feat-title">Reviews & tests</div>
                    <div class="feat-desc">Jeux, séries, mangas — les avis de la communauté.</div>
                </div>
            </div>
        </div>

        <div class="auth-z">
            <div class="auth-tagline">Rejoins la <span class="gold">communauté</span><br>geek africaine.</div>
            <div class="auth-left-subdesc">Dakar, Abidjan, Nairobi, Paris — ta communauté t'attend.</div>
        </div>
    </div>

    <!-- ── RIGHT ── -->
    <div class="auth-right">
        <div class="auth-form-wrap">
            <div class="auth-title">Créer un compte</div>
            <div class="auth-subtitle">Déjà membre ? <a href="{{ route('login') }}">Se connecter →</a></div>

            <a href="{{ route('auth.google') }}" class="btn-social" style="margin-bottom:20px;">
                <svg width="17" height="17" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Continuer avec Google
            </a>

            <div class="auth-divider"><span>ou avec un email</span></div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="name">Nom complet</label>
                        <input class="form-input {{ $errors->has('name') ? 'is-error' : '' }}" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ton nom" required autofocus>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="username">Pseudo</label>
                        <input class="form-input {{ $errors->has('username') ? 'is-error' : '' }}" type="text" id="username" name="username" value="{{ old('username') }}" placeholder="ton.pseudo" required>
                        @error('username')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ton@email.com" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="password">Mot de passe</label>
                        <input class="form-input {{ $errors->has('password') ? 'is-error' : '' }}" type="password" id="password" name="password" placeholder="••••••••" required>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirmer</label>
                        <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                    </div>
                </div>

<button type="submit" class="btn-submit">Créer mon compte →</button>

                <div class="terms-text">
                    En créant un compte tu acceptes nos <a href="#">Conditions d'utilisation</a> et notre <a href="#">Politique de confidentialité</a>.
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
