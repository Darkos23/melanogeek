@extends('layouts.app')

@section('title', 'Connexion — MelanoGeek')

@push('styles')
<style>
.mg-nav { display: none !important; }

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
    padding: 48px;
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
        radial-gradient(ellipse 60% 50% at 20% 65%, rgba(212,168,67,.12) 0%, transparent 60%),
        radial-gradient(ellipse 50% 40% at 80% 25%, rgba(200,82,42,.08) 0%, transparent 50%);
}
.auth-z { position: relative; z-index: 1; }

.auth-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.auth-brand-name { font-family: var(--font-head); font-weight: 800; font-size: 1.25rem; color: #f5f5f5; }
.auth-brand-name span { color: var(--gold); }

.auth-tagline {
    font-family: var(--font-head);
    font-size: clamp(1.7rem, 2.8vw, 2.6rem);
    font-weight: 800;
    line-height: 1.15;
    color: #f0f0f0;
    margin-bottom: 16px;
    letter-spacing: -.03em;
}
.auth-tagline .gold { color: var(--gold); }
.auth-left-desc {
    font-size: .88rem;
    line-height: 1.7;
    color: rgba(255,255,255,.38);
    max-width: 340px;
    margin-bottom: 32px;
}
.auth-avatars { display: flex; align-items: center; margin-bottom: 10px; }
.auth-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    border: 2px solid #0e0e0e;
    margin-right: -8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem;
    background: var(--bg-card2);
}
.auth-avatars-text {
    margin-left: 18px;
    font-size: .78rem;
    color: rgba(255,255,255,.38);
}
.auth-avatars-text strong { color: var(--gold); font-weight: 600; }

/* ── RIGHT ── */
.auth-right {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    background: var(--bg);
}
.auth-form-wrap { width: 100%; max-width: 400px; }

.auth-title {
    font-family: var(--font-head);
    font-size: 1.9rem;
    font-weight: 800;
    letter-spacing: -.02em;
    color: var(--text);
    margin-bottom: 6px;
}
.auth-subtitle {
    font-size: .88rem;
    color: var(--text-muted);
    margin-bottom: 32px;
}
.auth-subtitle a { color: var(--gold); text-decoration: none; font-weight: 500; }
.auth-subtitle a:hover { text-decoration: underline; }

.form-group { margin-bottom: 18px; }
.form-label {
    display: block;
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 8px;
}
.form-input {
    width: 100%;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 13px 16px;
    color: var(--text);
    font-family: var(--font-body);
    font-size: .94rem;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.form-input::placeholder { color: var(--text-faint); }
.form-input:focus {
    border-color: rgba(255,255,255,.30);
    box-shadow: 0 0 0 3px rgba(255,255,255,.05);
}
.form-input.is-error { border-color: #f87171; }
.form-error { font-size: .76rem; color: #f87171; margin-top: 5px; }

.form-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
}
.form-check { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.form-check input { width: 15px; height: 15px; accent-color: var(--gold); cursor: pointer; }
.form-check label { font-size: .82rem; color: var(--text-muted); cursor: pointer; }
.form-link { font-size: .82rem; color: var(--text-muted); text-decoration: none; transition: color .15s; }
.form-link:hover { color: var(--text); }

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
    transition: background .15s, transform .15s;
}
.btn-submit:hover { background: #fff; }
.btn-submit:active { transform: scale(.98); }

.auth-divider {
    display: flex; align-items: center; gap: 14px;
    margin: 24px 0;
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
    .auth-right { padding: 100px 24px 48px; align-items: flex-start; }
}
</style>
@endpush

@section('content')
<div class="auth-page">

    <!-- LEFT -->
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

        <div class="auth-z">
            <h2 class="auth-tagline">La culture <span class="gold">geek</span>,<br>vue d'Afrique.</h2>
            <p class="auth-left-desc">Articles, débats, reviews et discussions autour du manga, du gaming, de la tech et de la culture nerd — par et pour la communauté africaine.</p>
            <div class="auth-avatars">
                <div class="auth-avatar">🎮</div>
                <div class="auth-avatar">🎌</div>
                <div class="auth-avatar">💻</div>
                <div class="auth-avatar">🌍</div>
                <div class="auth-avatar">🎭</div>
            </div>
            <div class="auth-avatars-text"><strong>2 400+</strong> membres actifs sur la plateforme</div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <div class="auth-form-wrap">
            <div class="auth-title">Connexion</div>
            <div class="auth-subtitle">Pas encore membre ? <a href="{{ route('register') }}">Créer un compte →</a></div>

            @if(session('status'))
                <div style="margin-bottom:18px;padding:12px 16px;background:rgba(110,231,183,.08);border:1px solid rgba(110,231,183,.2);border-radius:10px;font-size:.84rem;color:#6ee7b7;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ton@email.com" required autofocus>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input class="form-input {{ $errors->has('password') ? 'is-error' : '' }}" type="password" id="password" name="password" placeholder="••••••••" required>
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-row">
                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="form-link">Mot de passe oublié ?</a>
                    @endif
                </div>
                <button type="submit" class="btn-submit">Se connecter →</button>
            </form>

            @if(session('error'))
                <div style="margin-top:14px;padding:10px 14px;background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.2);border-radius:8px;font-size:.82rem;color:#f87171;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="auth-divider"><span>ou</span></div>

            <a href="{{ route('auth.google') }}" class="btn-social">
                <svg width="17" height="17" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Continuer avec Google
            </a>
        </div>
    </div>
</div>
@endsection
