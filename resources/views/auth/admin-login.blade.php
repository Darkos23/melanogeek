@extends('layouts.app')

@section('title', 'Connexion Staff')

@push('styles')
<style>
    .auth-page { min-height:100vh;display:grid;grid-template-columns:1fr 1fr; }

    .auth-left { position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between;padding:48px;background:linear-gradient(160deg,#0A0705 0%,#060402 60%); }
    .auth-left-bg { position:absolute;inset:0;z-index:0;background:radial-gradient(ellipse 60% 50% at 30% 60%,rgba(212,168,67,.12) 0%,transparent 60%); }
    .auth-left-pattern { position:absolute;inset:0;z-index:0;opacity:.025;background-image:repeating-linear-gradient(45deg,var(--gold) 0,var(--gold) 1px,transparent 0,transparent 50%);background-size:20px 20px; }
    .auth-left-content { position:relative;z-index:1; }
    .auth-brand { display:flex;align-items:center;gap:12px;text-decoration:none; }
    .auth-brand-name { font-family:var(--font-head);font-weight:800;font-size:1.3rem;color:#F5EFE6; }
    .auth-brand-name span { color:var(--gold); }
    .auth-left-bottom { position:relative;z-index:1; }
    .auth-tagline { font-family:var(--font-head);font-size:clamp(1.6rem,2.5vw,2.4rem);font-weight:800;line-height:1.15;color:#F5EFE6;margin-bottom:16px; }
    .auth-tagline span { color:var(--gold); }
    .auth-left-desc { font-size:.9rem;line-height:1.7;color:rgba(245,239,230,.45);max-width:340px; }
    .staff-notice { margin-top:28px;padding:16px;background:rgba(212,168,67,.08);border:1px solid rgba(212,168,67,.2);border-radius:12px; }
    .staff-notice p { font-size:.8rem;color:rgba(212,168,67,.7);line-height:1.6; }

    .auth-right { display:flex;align-items:center;justify-content:center;padding:48px 40px;background:var(--bg);transition:background .35s; }
    .auth-form-wrap { width:100%;max-width:400px; }
    .auth-title { font-family:var(--font-head);font-size:1.8rem;font-weight:800;letter-spacing:-.02em;color:var(--cream);margin-bottom:6px; }
    .auth-subtitle { font-size:.87rem;color:var(--cream-muted);margin-bottom:32px; }
    .auth-subtitle a { color:var(--gold);text-decoration:none;font-weight:500; }

    .form-group { margin-bottom:20px; }
    .form-label { display:block;font-size:.78rem;font-weight:600;letter-spacing:.03em;color:var(--cream-muted);margin-bottom:8px;text-transform:uppercase; }
    .form-input { width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:14px 16px;color:var(--cream);font-family:var(--font-body);font-size:.95rem;transition:border-color .2s,box-shadow .2s;outline:none; }
    .form-input::placeholder { color:var(--muted); }
    .form-input:focus { border-color:var(--gold);box-shadow:0 0 0 3px rgba(212,168,67,.1); }
    .form-input.is-error { border-color:#E05555;box-shadow:0 0 0 3px rgba(224,85,85,.1); }
    .form-error { font-size:.78rem;color:#E05555;margin-top:6px; }
    .btn-submit { width:100%;background:var(--gold);color:#0A0705;border:none;border-radius:12px;padding:15px;font-family:var(--font-head);font-size:1rem;font-weight:800;cursor:pointer;transition:all .2s;letter-spacing:.01em; }
    .btn-submit:hover { background:#c49a37;transform:translateY(-1px);box-shadow:0 12px 30px rgba(212,168,67,.25); }
    .back-link { display:inline-flex;align-items:center;gap:6px;font-size:.82rem;color:var(--cream-muted);text-decoration:none;margin-top:20px;transition:color .2s; }
    .back-link:hover { color:var(--cream); }

    @@media (max-width:768px) {
        .auth-page { grid-template-columns:1fr; }
        .auth-left { display:none; }
        .auth-right { padding:80px 24px 40px;align-items:flex-start; }
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
            <h2 class="auth-tagline">Portail <span>staff</span><br>MelanoGeek</h2>
            <p class="auth-left-desc">Accès réservé aux administrateurs et propriétaires de la plateforme.</p>
            <div class="staff-notice">
                <p>🔒 Ce portail est réservé au staff interne. Si vous êtes un utilisateur, connectez-vous sur <a href="{{ route('login') }}" style="color:var(--gold);">/login</a>.</p>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <div class="auth-form-wrap">
            <div class="auth-title">Accès staff</div>
            <div class="auth-subtitle">Connexion sécurisée pour l'administration</div>

            @if(session('status'))
                <div style="padding:12px 16px;border-radius:10px;background:rgba(45,90,61,.1);border:1px solid rgba(45,90,61,.2);color:#3D8A58;font-size:.84rem;margin-bottom:20px;">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="staff@melanogeek.com" required autofocus>
                    @error('email')<div class="form-error">⚠ {{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input class="form-input {{ $errors->has('password') ? 'is-error' : '' }}" type="password" id="password" name="password" placeholder="••••••••" required>
                    @error('password')<div class="form-error">⚠ {{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-submit">Accéder au dashboard →</button>
            </form>

            <a href="{{ route('home') }}" class="back-link">← Retour au site public</a>
        </div>
    </div>
</div>
@endsection
