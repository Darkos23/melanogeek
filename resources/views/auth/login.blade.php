@extends('layouts.app')

@section('title', 'Connexion')

@push('styles')
<style>
    .auth-page { min-height:100vh;display:grid;grid-template-columns:1fr 1fr; }

    /* LEFT */
    .auth-left { position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between;padding:48px;background:linear-gradient(160deg,#1A0E06 0%,#0A0705 60%); }
    .auth-left-bg { position:absolute;inset:0;z-index:0;background:radial-gradient(ellipse 70% 50% at 30% 60%,rgba(200,82,42,.18) 0%,transparent 60%),radial-gradient(ellipse 50% 40% at 80% 20%,rgba(212,168,67,.1) 0%,transparent 50%); }
    .auth-left-pattern { position:absolute;inset:0;z-index:0;opacity:.035;background-image:repeating-linear-gradient(45deg,var(--gold) 0,var(--gold) 1px,transparent 0,transparent 50%);background-size:20px 20px; }
    .auth-left-content { position:relative;z-index:1; }
    .auth-brand { display:flex;align-items:center;gap:12px;text-decoration:none; }
    .auth-brand-name { font-family:var(--font-head);font-weight:800;font-size:1.3rem;color:#F5EFE6; }
    .auth-brand-name span { color:var(--gold); }
    .auth-left-bottom { position:relative;z-index:1; }
    .auth-tagline { font-family:var(--font-head);font-size:clamp(1.8rem,3vw,2.8rem);font-weight:800;line-height:1.1;color:#F5EFE6;margin-bottom:20px; }
    .auth-tagline span { color:var(--gold); }
    .auth-tagline .terra { color:var(--terracotta); }
    .auth-left-desc { font-size:.95rem;line-height:1.7;color:rgba(245,239,230,.55);max-width:360px;margin-bottom:32px; }
    .auth-creators { display:flex;align-items:center;gap:0;margin-bottom:10px; }
    .auth-creator-avi { width:36px;height:36px;border-radius:50%;border:2px solid #0A0705;display:flex;align-items:center;justify-content:center;margin-right:-10px;background:linear-gradient(135deg,var(--terracotta),var(--gold));padding:2px; }
    .auth-creator-avi-inner { width:100%;height:100%;border-radius:50%;background:#1e1810;display:flex;align-items:center;justify-content:center;font-size:.85rem; }
    .auth-creators-text { margin-left:18px;font-size:.8rem;color:rgba(245,239,230,.5); }
    .auth-creators-text strong { color:var(--gold);font-weight:600; }

    /* RIGHT */
    .auth-right { display:flex;align-items:center;justify-content:center;padding:48px 40px;background:var(--bg);transition:background .35s; }
    .auth-form-wrap { width:100%;max-width:420px; }
    .auth-title { font-family:var(--font-head);font-size:2rem;font-weight:800;letter-spacing:-.02em;color:var(--cream);margin-bottom:6px; }
    .auth-subtitle { font-size:.9rem;color:var(--cream-muted);margin-bottom:36px; }
    .auth-subtitle a { color:var(--gold);text-decoration:none;font-weight:500; }
    .auth-subtitle a:hover { text-decoration:underline; }

    /* FORM */
    .form-group { margin-bottom:20px; }
    .form-label { display:block;font-size:.82rem;font-weight:600;letter-spacing:.03em;color:var(--cream-muted);margin-bottom:8px;text-transform:uppercase; }
    .form-input { width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:14px 16px;color:var(--cream);font-family:var(--font-body);font-size:.95rem;transition:border-color .2s,box-shadow .2s;outline:none;cursor:text;touch-action:manipulation; }
    .form-input::placeholder { color:var(--muted); }
    .form-input:focus { border-color:var(--terracotta);box-shadow:0 0 0 3px rgba(200,82,42,.12); }
    .form-input.is-error { border-color:#E05555;box-shadow:0 0 0 3px rgba(224,85,85,.1); }
    .form-error { font-size:.78rem;color:#E05555;margin-top:6px; }
    .form-row { display:flex;justify-content:space-between;align-items:center;margin-bottom:24px; }
    .form-check { display:flex;align-items:center;gap:8px;cursor:pointer; }
    .form-check input { width:16px;height:16px;accent-color:var(--terracotta);cursor:pointer; }
    .form-check label { font-size:.85rem;color:var(--cream-muted);cursor:pointer; }
    .form-link { font-size:.85rem;color:var(--gold);text-decoration:none;font-weight:500; }
    .form-link:hover { text-decoration:underline; }
    .btn-submit { width:100%;background:var(--terracotta);color:#fff;border:none;border-radius:12px;padding:15px;font-family:var(--font-head);font-size:1rem;font-weight:700;cursor:pointer;touch-action:manipulation;transition:background .2s,transform .15s,box-shadow .2s;letter-spacing:.01em; }
    .btn-submit:hover { background:var(--accent);transform:translateY(-1px);box-shadow:0 12px 30px rgba(200,82,42,.35); }
    .auth-divider { display:flex;align-items:center;gap:14px;margin:24px 0; }
    .auth-divider::before,.auth-divider::after { content:'';flex:1;height:1px;background:var(--border); }
    .auth-divider span { font-size:.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;white-space:nowrap; }
    .btn-social { width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:13px;color:var(--cream);font-family:var(--font-body);font-size:.9rem;font-weight:500;cursor:pointer;touch-action:manipulation;transition:border-color .2s,background .2s;display:flex;align-items:center;justify-content:center;gap:10px;text-decoration:none;margin-bottom:10px; }
    .btn-social:hover { border-color:var(--border-hover);background:var(--bg-card2); }

    @@media (max-width:768px) {
        .auth-page { grid-template-columns:1fr; }
        .auth-left { display:none; }
        .auth-right { padding:100px 24px 40px;align-items:flex-start; }
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <!-- LEFT -->
    <div class="auth-left">
        <div class="auth-left-bg"></div>
        <div class="auth-left-pattern"></div>
        <div class="auth-left-content">
            <a href="{{ route('home') }}" class="auth-brand">
                <svg width="36" height="36" viewBox="0 0 42 42" fill="none">
                    <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="#1A1208" stroke="#D4A843" stroke-width="0.8"/>
                    <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#C8522A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    <circle cx="21" cy="9" r="2.5" fill="#D4A843"/>
                </svg>
                <span class="auth-brand-name">Melano<span>Geek</span></span>
            </a>
        </div>
        <div class="auth-left-bottom">
            <h2 class="auth-tagline">Bienvenue<br>dans ta <span>communauté</span><br><span class="terra">créative.</span></h2>
            <p class="auth-left-desc">Des milliers de créateurs sénégalais partagent leur art, leur musique, leur mode et leur culture sur MelanoGeek.</p>
            <div class="auth-creators">
                <div class="auth-creator-avi"><div class="auth-creator-avi-inner">👩🏾</div></div>
                <div class="auth-creator-avi"><div class="auth-creator-avi-inner">🎵</div></div>
                <div class="auth-creator-avi"><div class="auth-creator-avi-inner">📸</div></div>
                <div class="auth-creator-avi"><div class="auth-creator-avi-inner">🎨</div></div>
                <div class="auth-creator-avi"><div class="auth-creator-avi-inner">🎬</div></div>
            </div>
            <div class="auth-creators-text"><strong>2 400+</strong> créateurs actifs ce mois 🇸🇳</div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <div class="auth-form-wrap">
            <div class="auth-title">Connexion</div>
            <div class="auth-subtitle">Pas encore membre ? <a href="{{ route('register') }}">Créer un compte →</a></div>

            @if(session('status'))
                <div class="flash success" style="margin-bottom:20px;">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ton@email.com" required autofocus>
                    @error('email')<div class="form-error">⚠ {{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input class="form-input {{ $errors->has('password') ? 'is-error' : '' }}" type="password" id="password" name="password" placeholder="••••••••" required>
                    @error('password')<div class="form-error">⚠ {{ $message }}</div>@enderror
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

            <div class="auth-divider"><span>ou continuer avec</span></div>

            @if(session('error'))
                <div class="form-error" style="margin-bottom:12px;background:rgba(224,85,85,.08);border:1px solid rgba(224,85,85,.2);padding:10px 14px;border-radius:8px;">
                    ⚠ {{ session('error') }}
                </div>
            @endif

            <a href="{{ route('auth.google') }}" class="btn-social">
                <svg width="18" height="18" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Continuer avec Google
            </a>
        </div>
    </div>
</div>
@endsection