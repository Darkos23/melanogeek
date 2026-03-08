@extends('layouts.app')

@section('title', 'Candidature en cours d\'examen')

@push('styles')
<style>
    nav { display: none !important; }
    .pending-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg);
        padding: 40px 20px;
    }
    .pending-card {
        max-width: 520px;
        width: 100%;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 48px 40px;
        text-align: center;
    }
    .pending-icon {
        font-size: 3.5rem;
        margin-bottom: 20px;
        display: block;
    }
    .pending-title {
        font-family: var(--font-head);
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--cream);
        margin-bottom: 12px;
        letter-spacing: -.02em;
    }
    .pending-sub {
        font-size: .95rem;
        color: var(--cream-muted);
        line-height: 1.7;
        margin-bottom: 32px;
    }
    .pending-info {
        background: rgba(212,168,67,.08);
        border: 1px solid rgba(212,168,67,.2);
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 32px;
        text-align: left;
    }
    .pending-info-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: .88rem;
        color: var(--cream-muted);
        margin-bottom: 10px;
    }
    .pending-info-row:last-child { margin-bottom: 0; }
    .pending-info-row span:first-child { flex-shrink: 0; }
    .pending-steps {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 32px;
        text-align: left;
    }
    .pending-step {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        background: var(--bg);
        border-radius: 12px;
        font-size: .88rem;
        color: var(--cream-muted);
    }
    .step-num {
        width: 28px; height: 28px;
        background: var(--terracotta);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-family: var(--font-head);
        font-size: .75rem;
        font-weight: 800;
        color: white;
        flex-shrink: 0;
    }
    .btn-logout {
        display: inline-block;
        padding: 12px 28px;
        background: transparent;
        border: 1px solid var(--border);
        border-radius: 12px;
        color: var(--cream-muted);
        font-family: var(--font-head);
        font-size: .88rem;
        font-weight: 600;
        text-decoration: none;
        cursor: none;
        transition: border-color .2s, color .2s;
    }
    .btn-logout:hover { border-color: var(--terracotta); color: var(--terracotta); }
    .brand {
        display: flex; align-items: center; gap: 10px;
        justify-content: center;
        margin-bottom: 28px;
        text-decoration: none;
    }
    .brand-name { font-family: var(--font-head); font-weight: 800; font-size: 1.1rem; color: var(--cream); }
    .brand-name span { color: var(--gold); }
</style>
@endpush

@section('content')
<div class="pending-page">
    <div class="pending-card">

        <a href="{{ route('home') }}" class="brand">
            <svg width="30" height="30" viewBox="0 0 42 42" fill="none">
                <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="#1A1208" stroke="#D4A843" stroke-width="0.8"/>
                <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#C8522A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <circle cx="21" cy="9" r="2.5" fill="#D4A843"/>
            </svg>
            <span class="brand-name">Melano<span>Geek</span></span>
        </a>

        <span class="pending-icon">⏳</span>
        <div class="pending-title">Candidature en cours d'examen</div>
        <p class="pending-sub">
            Merci <strong style="color:var(--cream);">{{ auth()->user()->name }}</strong> ! Ta candidature a bien été reçue.
            Notre équipe l'examine et tu recevras une réponse sous <strong style="color:var(--gold);">48h</strong>.
        </p>

        <div class="pending-info">
            <div class="pending-info-row">
                <span>🎨</span>
                <span><strong style="color:var(--cream);">Catégorie :</strong>
                    {{ auth()->user()->creator_category ?? '—' }}
                </span>
            </div>
            <div class="pending-info-row">
                <span>📧</span>
                <span><strong style="color:var(--cream);">Email :</strong>
                    {{ auth()->user()->email }}
                </span>
            </div>
        </div>

        <div class="pending-steps">
            <div class="pending-step">
                <div class="step-num" style="background:var(--gold);">✓</div>
                <span><strong style="color:var(--cream);">Candidature envoyée</strong> — reçue avec succès</span>
            </div>
            <div class="pending-step">
                <div class="step-num">2</div>
                <span><strong style="color:var(--cream);">Examen en cours</strong> — notre équipe vérifie ton profil</span>
            </div>
            <div class="pending-step">
                <div class="step-num">3</div>
                <span><strong style="color:var(--cream);">Réponse par email</strong> — sous 48h après soumission</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Se déconnecter</button>
        </form>

    </div>
</div>
@endsection
