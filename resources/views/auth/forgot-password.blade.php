@extends('layouts.app')

@section('title', 'Mot de passe oublié')

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
    .auth-left-desc { font-size:.95rem;line-height:1.7;color:rgba(245,239,230,.55);max-width:360px; }

    /* RIGHT */
    .auth-right { display:flex;align-items:center;justify-content:center;padding:48px 40px;background:var(--bg);transition:background .35s; }
    .auth-form-wrap { width:100%;max-width:420px; }
    .auth-title { font-family:var(--font-head);font-size:2rem;font-weight:800;letter-spacing:-.02em;color:var(--cream);margin-bottom:6px; }
    .auth-subtitle { font-size:.9rem;color:var(--cream-muted);margin-bottom:8px;line-height:1.6; }
    .auth-subtitle a { color:var(--gold);text-decoration:none;font-weight:500; }
    .auth-subtitle a:hover { text-decoration:underline; }
    .auth-desc { font-size:.88rem;color:var(--cream-muted);margin-bottom:32px;line-height:1.65;opacity:.75; }

    /* FORM */
    .form-group { margin-bottom:20px; }
    .form-label { display:block;font-size:.82rem;font-weight:600;letter-spacing:.03em;color:var(--cream-muted);margin-bottom:8px;text-transform:uppercase; }
    .form-input { width:100%;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:14px 16px;color:var(--cream);font-family:var(--font-body);font-size:.95rem;transition:border-color .2s,box-shadow .2s;outline:none; }
    .form-input::placeholder { color:var(--muted); }
    .form-input:focus { border-color:var(--terracotta);box-shadow:0 0 0 3px rgba(200,82,42,.12); }
    .form-input.is-error { border-color:#E05555;box-shadow:0 0 0 3px rgba(224,85,85,.1); }
    .form-error { font-size:.78rem;color:#E05555;margin-top:6px; }
    .btn-submit { width:100%;background:var(--terracotta);color:#fff;border:none;border-radius:12px;padding:15px;font-family:var(--font-head);font-size:1rem;font-weight:700;cursor:pointer;transition:background .2s,transform .15s,box-shadow .2s;letter-spacing:.01em; }
    .btn-submit:hover { background:var(--accent);transform:translateY(-1px);box-shadow:0 12px 30px rgba(200,82,42,.35); }
    .flash-success { background:rgba(45,184,160,.08);border:1px solid rgba(45,184,160,.25);color:#2DB8A0;padding:12px 16px;border-radius:10px;font-size:.85rem;margin-bottom:24px; }

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
            <h2 class="auth-tagline">Pas de panique,<br>on te <span>retrouve.</span></h2>
            <p class="auth-left-desc">Entre ton adresse email et on t'envoie un lien pour réinitialiser ton mot de passe.</p>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <div class="auth-form-wrap">
            <div class="auth-title">Mot de passe oublié ?</div>
            <div class="auth-subtitle"><a href="{{ route('login') }}">← Retour à la connexion</a></div>
            <p class="auth-desc">Saisis ton adresse email et nous t'enverrons un lien pour créer un nouveau mot de passe.</p>

            @if(session('status'))
                <div class="flash-success">✓ {{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                           type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="ton@email.com"
                           required autofocus>
                    @error('email')<div class="form-error">⚠ {{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn-submit">Envoyer le lien →</button>
            </form>
        </div>
    </div>

</div>
@endsection
