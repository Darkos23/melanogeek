@extends('layouts.app')

@section('title', 'Certificat — ' . $certificate->course->title)

@push('styles')
<style>
.cert-page {
    padding-top: calc(80px + env(safe-area-inset-top));
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding-bottom: 60px;
}
.cert-actions {
    display: flex;
    gap: 12px;
    margin-bottom: 28px;
    margin-top: 32px;
}
.btn-download {
    padding: 10px 22px;
    background: var(--terra);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: .9rem;
    text-decoration: none;
    cursor: pointer;
    transition: opacity .2s;
}
.btn-download:hover { opacity: .85; }
.btn-back {
    padding: 10px 22px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 10px;
    font-size: .9rem;
    text-decoration: none;
}

/* Certificat */
.certificate {
    width: 800px;
    max-width: 95vw;
    background: linear-gradient(135deg, #FBF5E6 0%, #F5EDD6 100%);
    border: 3px solid #B87820;
    border-radius: 20px;
    padding: 56px 60px;
    text-align: center;
    color: #1E0E04;
    box-shadow: 0 20px 60px rgba(0,0,0,.25);
    position: relative;
    overflow: hidden;
}
.cert-decoration {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    pointer-events: none;
    opacity: .06;
    background-image: repeating-linear-gradient(
        45deg, #B87820 0px, #B87820 1px, transparent 0px, transparent 50%
    );
    background-size: 20px 20px;
}
.cert-logo {
    font-family: 'Georgia', serif;
    font-size: 2rem;
    font-weight: 700;
    color: #B87820;
    letter-spacing: .15em;
    text-transform: uppercase;
    margin-bottom: 6px;
}
.cert-tagline {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .15em;
    color: #8B6020;
    margin-bottom: 32px;
}
.cert-divider {
    width: 80px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #B87820, transparent);
    margin: 0 auto 28px;
}
.cert-headline {
    font-size: .9rem;
    text-transform: uppercase;
    letter-spacing: .15em;
    color: #8B6020;
    margin-bottom: 14px;
}
.cert-name {
    font-family: 'Georgia', serif;
    font-size: 2.4rem;
    font-weight: 700;
    color: #1E0E04;
    margin-bottom: 14px;
}
.cert-text {
    font-size: .92rem;
    color: #5A3010;
    line-height: 1.7;
    margin-bottom: 14px;
}
.cert-course {
    font-family: 'Georgia', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #B87820;
    margin-bottom: 28px;
    line-height: 1.4;
}
.cert-meta {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1px solid rgba(184,120,32,0.3);
}
.cert-meta-item { text-align: center; }
.cert-meta-label {
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #8B6020;
    margin-bottom: 4px;
}
.cert-meta-value { font-size: .85rem; font-weight: 700; color: #1E0E04; }
.cert-number {
    font-size: .72rem;
    color: #8B6020;
    margin-top: 24px;
    font-family: monospace;
}

@media (max-width: 640px) {
    .certificate { padding: 32px 24px; }
    .cert-name { font-size: 1.6rem; }
    .cert-course { font-size: 1.1rem; }
    .cert-meta { gap: 20px; }
}
</style>
@endpush

@section('content')
<div class="cert-page">
    <div class="cert-actions">
        <a href="{{ route('courses.my') }}" class="btn-back">← Mes formations</a>
        <a href="{{ route('certificates.download', $certificate->id) }}" class="btn-download">
            ⬇ Télécharger le PDF
        </a>
    </div>

    <div class="certificate" id="certificate">
        <div class="cert-decoration"></div>
        <div class="cert-logo">Waxtu</div>
        <div class="cert-tagline">Plateforme de formation numérique</div>
        <div class="cert-divider"></div>
        <div class="cert-headline">Certificat de réussite</div>
        <div class="cert-name">{{ $certificate->user->name }}</div>
        <div class="cert-text">a complété avec succès la formation</div>
        <div class="cert-course">{{ $certificate->course->title }}</div>
        <div class="cert-text">
            dispensée par <strong>{{ $certificate->course->instructor->name }}</strong>
        </div>
        <div class="cert-meta">
            <div class="cert-meta-item">
                <div class="cert-meta-label">Date d'obtention</div>
                <div class="cert-meta-value">{{ $certificate->issued_at->format('d/m/Y') }}</div>
            </div>
            <div class="cert-meta-item">
                <div class="cert-meta-label">Durée de la formation</div>
                <div class="cert-meta-value">{{ $certificate->course->total_duration }} min</div>
            </div>
            <div class="cert-meta-item">
                <div class="cert-meta-label">Niveau</div>
                <div class="cert-meta-value">
                    {{ match($certificate->course->level) {
                        'debutant'      => 'Débutant',
                        'intermediaire' => 'Intermédiaire',
                        'avance'        => 'Avancé',
                        default => $certificate->course->level
                    } }}
                </div>
            </div>
        </div>
        <div class="cert-number">N° {{ $certificate->certificate_number }}</div>
    </div>
</div>
@endsection
