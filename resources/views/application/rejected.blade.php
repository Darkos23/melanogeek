@extends('layouts.app')

@section('title', 'Candidature non retenue')

@push('styles')
<style>
    nav { display: none !important; }
    .rejected-page {
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        background: var(--bg); padding: 40px 20px;
    }
    .rejected-card {
        max-width: 520px; width: 100%;
        background: var(--bg-card);
        border: 1px solid rgba(224,85,85,.25);
        border-radius: 24px; padding: 48px 40px; text-align: center;
    }
    .rejected-icon { font-size: 3.5rem; margin-bottom: 20px; display: block; }
    .rejected-title {
        font-family: var(--font-head); font-size: 1.7rem; font-weight: 800;
        color: var(--cream); margin-bottom: 12px; letter-spacing: -.02em;
    }
    .rejected-sub { font-size: .95rem; color: var(--cream-muted); line-height: 1.7; margin-bottom: 28px; }
    .reason-box {
        background: rgba(224,85,85,.07); border: 1px solid rgba(224,85,85,.2);
        border-radius: 14px; padding: 18px 22px; margin-bottom: 28px;
        text-align: left; font-size: .88rem; color: var(--cream-muted); line-height: 1.6;
    }
    .reason-label { font-weight: 700; color: #E05555; margin-bottom: 6px; font-size: .8rem; text-transform: uppercase; letter-spacing: .04em; }
    .btn-contact {
        display: inline-block; padding: 13px 28px;
        background: var(--terracotta); color: white; border-radius: 12px;
        font-family: var(--font-head); font-size: .9rem; font-weight: 700;
        text-decoration: none; margin-bottom: 12px; width: 100%;
    }
    .btn-logout {
        display: inline-block; padding: 12px 28px;
        background: transparent; border: 1px solid var(--border); border-radius: 12px;
        color: var(--cream-muted); font-family: var(--font-head); font-size: .88rem;
        font-weight: 600; text-decoration: none; cursor: none; width: 100%;
        transition: border-color .2s, color .2s;
    }
    .btn-logout:hover { border-color: var(--terracotta); color: var(--terracotta); }
    .brand { display: flex; align-items: center; gap: 10px; justify-content: center; margin-bottom: 28px; text-decoration: none; }
    .brand-name { font-family: var(--font-head); font-weight: 800; font-size: 1.1rem; color: var(--cream); }
    .brand-name span { color: var(--gold); }
</style>
@endpush

@section('content')
<div class="rejected-page">
    <div class="rejected-card">

        <a href="{{ route('home') }}" class="brand">
            <svg width="30" height="30" viewBox="0 0 42 42" fill="none">
                <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="#1A1208" stroke="#D4A843" stroke-width="0.8"/>
                <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#C8522A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <circle cx="21" cy="9" r="2.5" fill="#D4A843"/>
            </svg>
            <span class="brand-name">Melano<span>Geek</span></span>
        </a>

        <span class="rejected-icon">😔</span>
        <div class="rejected-title">Candidature non retenue</div>
        <p class="rejected-sub">
            Nous avons examiné ta candidature avec attention, mais elle ne correspond pas
            aux critères actuels de la plateforme. Tu peux nous contacter pour en savoir plus.
        </p>

        @if(auth()->user()->rejection_reason)
        <div class="reason-box">
            <div class="reason-label">Motif</div>
            {{ auth()->user()->rejection_reason }}
        </div>
        @endif

        <a href="mailto:contact@melanogeek.com?subject=Candidature refusée - {{ auth()->user()->email }}" class="btn-contact">
            Contacter l'équipe →
        </a>

        <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
            @csrf
            <button type="submit" class="btn-logout">Se déconnecter</button>
        </form>

    </div>
</div>
@endsection
