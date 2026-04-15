@extends('owner.layout')

@section('title', 'Vue d’ensemble')
@section('page-title', 'Vue d’ensemble')

@section('content')
<style>
.owner-overview {
    font-family: var(--font-body);
    color: rgba(255,255,255,.86);
}

.owner-hero {
    position: relative;
    overflow: hidden;
    margin-bottom: 28px;
    padding: 34px 36px;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 24px;
    background:
        radial-gradient(circle at top left, rgba(212,168,67,.14), transparent 34%),
        radial-gradient(circle at 85% 20%, rgba(255,255,255,.035), transparent 28%),
        linear-gradient(135deg, rgba(255,255,255,.03), rgba(255,255,255,.01));
}

.owner-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
    background-size: 28px 28px;
    mask-image: linear-gradient(180deg, rgba(0,0,0,.65), transparent 90%);
}

.owner-hero-inner {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(280px, .9fr);
    gap: 28px;
    align-items: end;
}

.owner-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    font-size: .74rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(255,255,255,.42);
}

.owner-eyebrow::before {
    content: '';
    width: 28px;
    height: 1px;
    background: rgba(212,168,67,.7);
}

.owner-title {
    max-width: 760px;
    margin: 0 0 12px;
    font-family: var(--font-head);
    font-weight: 800;
    font-size: clamp(2rem, 4vw, 3.35rem);
    line-height: .98;
    letter-spacing: -.04em;
    color: #f5efe3;
}

.owner-title span {
    color: #D4A843;
}

.owner-subtitle {
    max-width: 720px;
    margin: 0;
    font-size: .98rem;
    line-height: 1.75;
    color: rgba(255,255,255,.58);
}

.owner-hero-meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.owner-meta-card {
    padding: 16px 18px;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 16px;
    background: rgba(255,255,255,.03);
    backdrop-filter: blur(10px);
}

.owner-meta-label {
    margin-bottom: 8px;
    font-size: .7rem;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(255,255,255,.34);
}

.owner-meta-value {
    font-size: 1.15rem;
    font-weight: 700;
    color: #f4ecde;
}

.owner-meta-note {
    margin-top: 4px;
    font-size: .78rem;
    color: rgba(255,255,255,.42);
}

.owner-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.owner-stat {
    padding: 20px 20px 18px;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 18px;
    background: rgba(255,255,255,.025);
}

.owner-stat-label {
    margin-bottom: 12px;
    font-size: .72rem;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(255,255,255,.34);
}

.owner-stat-value {
    margin: 0 0 6px;
    font-size: 2.2rem;
    line-height: 1;
    letter-spacing: -.05em;
    color: #f5efe3;
}

.owner-stat-sub {
    font-size: .82rem;
    color: rgba(255,255,255,.46);
    line-height: 1.55;
}

.owner-stat-sub a,
.owner-panel-link,
.owner-link {
    color: #D4A843;
    text-decoration: none;
}

.owner-stat-sub a:hover,
.owner-panel-link:hover,
.owner-link:hover {
    text-decoration: underline;
}

.owner-focus {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.owner-focus-card {
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 18px;
    padding: 18px 20px;
    background: rgba(255,255,255,.02);
}

.owner-focus-kicker {
    font-size: .68rem;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(255,255,255,.34);
    margin-bottom: 8px;
}

.owner-focus-title {
    font-family: var(--font-head);
    font-size: 1.02rem;
    font-weight: 800;
    color: #f5efe3;
    margin-bottom: 8px;
}

.owner-focus-copy {
    font-size: .84rem;
    line-height: 1.65;
    color: rgba(255,255,255,.5);
}

.owner-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
    gap: 22px;
}

.owner-panel {
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 22px;
    overflow: hidden;
    background: rgba(255,255,255,.02);
}

.owner-panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 20px;
    border-bottom: 1px solid rgba(255,255,255,.06);
}

.owner-panel-kicker {
    font-size: .68rem;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(255,255,255,.34);
    margin-bottom: 6px;
}

.owner-panel-title {
    font-family: var(--font-head);
    font-size: 1.22rem;
    font-weight: 800;
    letter-spacing: -.03em;
    color: #f3ebdd;
}

.owner-list {
    padding: 4px 20px 8px;
}

.owner-item {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 14px;
    align-items: start;
    padding: 16px 0;
    border-bottom: 1px solid rgba(255,255,255,.05);
}

.owner-item:last-child {
    border-bottom: none;
}

.owner-item-title {
    font-size: .96rem;
    font-weight: 600;
    color: rgba(255,255,255,.86);
    margin-bottom: 5px;
}

.owner-item-meta {
    font-size: .8rem;
    line-height: 1.6;
    color: rgba(255,255,255,.44);
}

.owner-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 88px;
    padding: 7px 10px;
    border-radius: 999px;
    font-size: .72rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    border: 1px solid transparent;
}

.owner-badge.alert {
    background: rgba(248,113,113,.08);
    border-color: rgba(248,113,113,.18);
    color: #f87171;
}

.owner-badge.live {
    background: rgba(110,231,183,.08);
    border-color: rgba(110,231,183,.18);
    color: #6ee7b7;
}

.owner-badge.draft {
    background: rgba(255,255,255,.04);
    border-color: rgba(255,255,255,.08);
    color: rgba(255,255,255,.34);
}

.owner-empty {
    padding: 26px 0;
    font-size: .84rem;
    color: rgba(255,255,255,.34);
}

@media (max-width: 1100px) {
    .owner-hero-inner,
    .owner-grid,
    .owner-focus {
        grid-template-columns: 1fr;
    }

    .owner-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 720px) {
    .owner-hero {
        padding: 24px 20px;
    }

    .owner-stats {
        grid-template-columns: 1fr;
    }

    .owner-hero-meta,
    .owner-item {
        grid-template-columns: 1fr;
    }

    .owner-panel-head {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>

<div class="owner-overview">
    <section class="owner-hero">
        <div class="owner-hero-inner">
            <div>
                <div class="owner-eyebrow">Propriétaire · Direction</div>
                <h1 class="owner-title">Garder le cap de <span>MelanoGeek</span>.</h1>
                <p class="owner-subtitle">
                    Ce tableau de bord reste pensé pour un média vivant, mais avec le bon niveau
                    de recul pour le propriétaire: arbitrer la ligne, suivre l’équipe, protéger
                    la communauté et garder le blog comme le forum dans la bonne direction.
                </p>
            </div>

            <div class="owner-hero-meta">
                <div class="owner-meta-card">
                    <div class="owner-meta-label">Arbitrages</div>
                    <div class="owner-meta-value">{{ $pending_reports->count() }}</div>
                    <div class="owner-meta-note">décisions de modération à trancher</div>
                </div>
                <div class="owner-meta-card">
                    <div class="owner-meta-label">Bibliothèque éditoriale</div>
                    <div class="owner-meta-value">{{ number_format($stats['posts_total']) }}</div>
                    <div class="owner-meta-note">contenus qui façonnent l’image du site</div>
                </div>
                <div class="owner-meta-card">
                    <div class="owner-meta-label">Communauté</div>
                    <div class="owner-meta-value">{{ number_format($stats['users_total']) }}</div>
                    <div class="owner-meta-note">lecteurs, membres et futurs contributeurs</div>
                </div>
                <div class="owner-meta-card">
                    <div class="owner-meta-label">Équipe staff</div>
                    <div class="owner-meta-value">{{ $stats['admins_total'] }}</div>
                    <div class="owner-meta-note">cadre éditorial et modération active</div>
                </div>
            </div>
        </div>
    </section>

    <section class="owner-stats">
        <article class="owner-stat">
            <div class="owner-stat-label">Communauté</div>
            <div class="owner-stat-value">{{ number_format($stats['users_total']) }}</div>
            <div class="owner-stat-sub"><strong>{{ number_format($stats['users_active']) }}</strong> membres actifs sur la période récente</div>
        </article>
        <article class="owner-stat">
            <div class="owner-stat-label">Croissance</div>
            <div class="owner-stat-value">+{{ $stats['users_new_week'] }}</div>
            <div class="owner-stat-sub">nouveaux comptes cette semaine pour nourrir le blog et le forum</div>
        </article>
        <article class="owner-stat">
            <div class="owner-stat-label">Rythme éditorial</div>
            <div class="owner-stat-value">{{ number_format($stats['posts_total']) }}</div>
            <div class="owner-stat-sub">articles, billets et contenus déjà publiés sur le site</div>
        </article>
        <article class="owner-stat">
            <div class="owner-stat-label">Équipe</div>
            <div class="owner-stat-value">{{ $stats['admins_total'] }}</div>
            <div class="owner-stat-sub"><a href="{{ route('owner.staff') }}">voir l’équipe éditoriale et modération →</a></div>
        </article>
    </section>

    <section class="owner-focus">
        <article class="owner-focus-card">
            <div class="owner-focus-kicker">Cap éditorial</div>
            <div class="owner-focus-title">Protéger l’identité du média</div>
            <div class="owner-focus-copy">
                Le rôle du propriétaire n’est pas seulement de surveiller les chiffres:
                il fixe le ton, la cohérence des catégories et ce que MelanoGeek veut vraiment raconter.
            </div>
        </article>
        <article class="owner-focus-card">
            <div class="owner-focus-kicker">Forum & communauté</div>
            <div class="owner-focus-title">Encadrer sans étouffer</div>
            <div class="owner-focus-copy">
                Le forum doit prolonger les sujets du blog, ouvrir le débat et rester sain.
                Les arbitrages servent d’abord la qualité des échanges.
            </div>
        </article>
        <article class="owner-focus-card">
            <div class="owner-focus-kicker">Pilotage</div>
            <div class="owner-focus-title">Décider où mettre l’attention</div>
            <div class="owner-focus-copy">
                Staff, réglages, modération et contenus ne sont pas séparés:
                ce sont les leviers qui permettent au propriétaire de garder une vision claire.
            </div>
        </article>
    </section>

    <section class="owner-grid" style="margin-bottom:22px;">
        <article class="owner-panel">
            <div class="owner-panel-head">
                <div>
                    <div class="owner-panel-kicker">Décisions propriétaire</div>
                    <div class="owner-panel-title">Chantiers à garder sous contrôle</div>
                </div>
                <a href="{{ route('owner.settings') }}" class="owner-panel-link">Ouvrir les réglages →</a>
            </div>
            <div class="owner-list">
                <div class="owner-item">
                    <div>
                        <div class="owner-item-title">Ligne éditoriale et cadre public</div>
                        <div class="owner-item-meta">
                            Vérifier que la page d’accueil, le blog, le forum et la page À propos racontent
                            la même vision de MelanoGeek.
                        </div>
                    </div>
                    <a href="{{ route('admin.about') }}" class="owner-link">À propos</a>
                </div>
                <div class="owner-item">
                    <div>
                        <div class="owner-item-title">Équipe et responsabilités</div>
                        <div class="owner-item-meta">
                            Garder une équipe légère, claire dans ses rôles, capable de publier et modérer sans bruit inutile.
                        </div>
                    </div>
                    <a href="{{ route('owner.staff') }}" class="owner-link">Équipe</a>
                </div>
                <div class="owner-item">
                    <div>
                        <div class="owner-item-title">Cadre de la plateforme</div>
                        <div class="owner-item-meta">
                            Ajuster les paramètres qui ont un impact direct sur la communauté, les annonces et l’ouverture du site.
                        </div>
                    </div>
                    <a href="{{ route('owner.settings') }}" class="owner-link">Paramètres</a>
                </div>
            </div>
        </article>

        <article class="owner-panel">
            <div class="owner-panel-head">
                <div>
                    <div class="owner-panel-kicker">Vue rapide</div>
                    <div class="owner-panel-title">Ce qui demande ton attention</div>
                </div>
            </div>
            <div class="owner-list">
                <div class="owner-item">
                    <div>
                        <div class="owner-item-title">Signalements en attente</div>
                        <div class="owner-item-meta">Décisions sensibles qui peuvent impacter l’ambiance du forum et l’image du site.</div>
                    </div>
                    <div class="owner-badge alert">{{ $pending_reports->count() }}</div>
                </div>
                <div class="owner-item">
                    <div>
                        <div class="owner-item-title">Nouveaux membres cette semaine</div>
                        <div class="owner-item-meta">Un bon indicateur pour voir si la promesse éditoriale du site attire encore.</div>
                    </div>
                    <div class="owner-meta-value">+{{ $stats['users_new_week'] }}</div>
                </div>
                <div class="owner-item">
                    <div>
                        <div class="owner-item-title">Staff actif</div>
                        <div class="owner-item-meta">Le noyau qui tient la publication, la modération et le cadre quotidien.</div>
                    </div>
                    <div class="owner-badge live">{{ $stats['admins_total'] }}</div>
                </div>
            </div>
        </article>
    </section>

    <section class="owner-grid">
        <article class="owner-panel">
            <div class="owner-panel-head">
                <div>
                    <div class="owner-panel-kicker">Modération</div>
                    <div class="owner-panel-title">Signalements à traiter</div>
                </div>
                <a href="{{ route('admin.reports') }}" class="owner-panel-link">Voir la modération →</a>
            </div>
            <div class="owner-list">
                @forelse($pending_reports as $report)
                    <div class="owner-item">
                        <div>
                            <div class="owner-item-title">{{ $report->reason }}</div>
                            <div class="owner-item-meta">
                                Signalé par <strong>&#64;{{ $report->reporter?->username ?? '?' }}</strong>
                                · {{ $report->created_at?->diffForHumans() ?? '—' }}
                            </div>
                        </div>
                        <div class="owner-badge alert">Alerte</div>
                    </div>
                @empty
                    <div class="owner-empty">Aucun signalement en attente pour le moment.</div>
                @endforelse
            </div>
        </article>

        <article class="owner-panel">
            <div class="owner-panel-head">
                <div>
                    <div class="owner-panel-kicker">Éditorial</div>
                    <div class="owner-panel-title">Publications récentes</div>
                </div>
                <a href="{{ route('admin.posts') }}" class="owner-panel-link">Ouvrir les publications →</a>
            </div>
            <div class="owner-list">
                @forelse($latest_posts as $post)
                    <div class="owner-item">
                        <div>
                            <div class="owner-item-title">{{ $post->title ?: 'Publication sans titre' }}</div>
                            <div class="owner-item-meta">
                                &#64;{{ $post->user?->username ?? '?' }}
                                · {{ $post->created_at?->diffForHumans() ?? '—' }}
                            </div>
                        </div>
                        <div class="owner-badge {{ $post->is_published ? 'live' : 'draft' }}">
                            {{ $post->is_published ? 'Publié' : 'Brouillon' }}
                        </div>
                    </div>
                @empty
                    <div class="owner-empty">Aucune publication récente à afficher.</div>
                @endforelse
            </div>
        </article>
    </section>
</div>
@endsection
