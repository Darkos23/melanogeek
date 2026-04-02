<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: Georgia, serif;
    background: #FBF5E6;
    color: #1E0E04;
    width: 100%;
    padding: 0;
}
.certificate {
    width: 100%;
    min-height: 100vh;
    padding: 60px 70px;
    text-align: center;
    border: 6px solid #B87820;
    position: relative;
}
.cert-logo {
    font-size: 2.2rem;
    font-weight: 700;
    color: #B87820;
    letter-spacing: .15em;
    text-transform: uppercase;
    margin-bottom: 4px;
}
.cert-tagline {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .15em;
    color: #8B6020;
    margin-bottom: 30px;
    font-family: Arial, sans-serif;
}
.cert-divider {
    width: 80px;
    height: 2px;
    background: #B87820;
    margin: 0 auto 24px;
}
.cert-headline {
    font-size: .85rem;
    text-transform: uppercase;
    letter-spacing: .15em;
    color: #8B6020;
    margin-bottom: 12px;
    font-family: Arial, sans-serif;
}
.cert-name {
    font-size: 2.6rem;
    font-weight: 700;
    color: #1E0E04;
    margin-bottom: 12px;
}
.cert-text {
    font-size: .88rem;
    color: #5A3010;
    line-height: 1.7;
    margin-bottom: 10px;
    font-family: Arial, sans-serif;
}
.cert-course {
    font-size: 1.5rem;
    font-weight: 700;
    color: #B87820;
    margin-bottom: 24px;
    line-height: 1.4;
}
.cert-meta {
    display: table;
    width: 100%;
    margin-top: 28px;
    padding-top: 18px;
    border-top: 1px solid #D4A843;
}
.cert-meta-item {
    display: table-cell;
    text-align: center;
    width: 33%;
}
.cert-meta-label {
    font-size: .68rem;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #8B6020;
    margin-bottom: 3px;
    font-family: Arial, sans-serif;
}
.cert-meta-value {
    font-size: .82rem;
    font-weight: 700;
    color: #1E0E04;
    font-family: Arial, sans-serif;
}
.cert-number {
    font-size: .68rem;
    color: #8B6020;
    margin-top: 22px;
    font-family: 'Courier New', monospace;
}
</style>
</head>
<body>
<div class="certificate">
    <div class="cert-logo">Waxtu</div>
    <div class="cert-tagline">Plateforme de formation numérique</div>
    <div class="cert-divider"></div>
    <div class="cert-headline">Certificat de réussite</div>
    <div class="cert-name">{{ $certificate->user->name }}</div>
    <div class="cert-text">a complété avec succès la formation</div>
    <div class="cert-course">{{ $certificate->course->title }}</div>
    <div class="cert-text">dispensée par <strong>{{ $certificate->course->instructor->name }}</strong></div>
    <div class="cert-meta">
        <div class="cert-meta-item">
            <div class="cert-meta-label">Date d'obtention</div>
            <div class="cert-meta-value">{{ $certificate->issued_at->format('d/m/Y') }}</div>
        </div>
        <div class="cert-meta-item">
            <div class="cert-meta-label">Durée</div>
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
</body>
</html>
