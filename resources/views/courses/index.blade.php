@extends('layouts.app')

@section('title', 'Catalogue des cours — Waxtu')

@push('styles')
<style>
.courses-page {
    padding-top: calc(80px + env(safe-area-inset-top));
    min-height: 100vh;
}
.courses-hero {
    max-width: 1100px;
    margin: 0 auto;
    padding: 44px 32px 28px;
}
.courses-hero-title {
    font-family: var(--font-head);
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -0.03em;
    color: var(--text);
    margin-bottom: 6px;
}
.courses-hero-title span { color: var(--gold); }
.courses-hero-sub {
    font-size: .92rem;
    color: var(--text-muted);
    margin-bottom: 28px;
}

/* Filtres */
.courses-filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 32px;
}
.filter-input {
    flex: 1;
    min-width: 200px;
    padding: 10px 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    font-size: .9rem;
    outline: none;
    transition: border-color .2s;
}
.filter-input:focus { border-color: var(--terra); }
.filter-select {
    padding: 10px 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    font-size: .9rem;
    outline: none;
    cursor: pointer;
}

/* Grille */
.courses-grid {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 32px 80px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
}

/* Carte cours */
.course-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s, border-color .2s;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
}
.course-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--border-hover);
}
.course-thumb {
    width: 100%;
    aspect-ratio: 16/9;
    object-fit: cover;
    background: var(--bg-card2);
}
.course-thumb-placeholder {
    width: 100%;
    aspect-ratio: 16/9;
    background: linear-gradient(135deg, var(--terra-soft), var(--gold-soft));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
}
.course-body {
    padding: 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.course-category {
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--terra);
}
.course-title {
    font-family: var(--font-head);
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
    line-height: 1.4;
}
.course-instructor {
    font-size: .82rem;
    color: var(--text-muted);
}
.course-meta {
    display: flex;
    gap: 12px;
    font-size: .78rem;
    color: var(--text-muted);
    margin-top: auto;
    padding-top: 8px;
    border-top: 1px solid var(--border);
}
.course-price {
    font-weight: 700;
    color: var(--gold);
    font-size: .95rem;
}
.course-price.free { color: var(--green); }

.badge-level {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 600;
    background: var(--gold-soft);
    color: var(--gold);
}

.empty-state {
    max-width: 1100px;
    margin: 60px auto;
    padding: 0 32px;
    text-align: center;
    color: var(--text-muted);
}
.empty-state svg { width: 64px; height: 64px; margin-bottom: 16px; opacity: .4; }

@media (max-width: 640px) {
    .courses-hero, .courses-grid { padding-left: 16px; padding-right: 16px; }
    .courses-hero-title { font-size: 1.5rem; }
    .courses-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="courses-page">
    <div class="courses-hero">
        <h1 class="courses-hero-title">Nos <span>formations</span></h1>
        <p class="courses-hero-sub">Apprenez les compétences du numérique à votre rythme.</p>

        <form class="courses-filters" method="GET" action="{{ route('courses.index') }}">
            <input type="text" name="q" class="filter-input" placeholder="Rechercher une formation..."
                   value="{{ request('q') }}">
            <select name="category" class="filter-select" onchange="this.form.submit()">
                <option value="">Toutes les catégories</option>
                <option value="reseaux_securite"     @selected(request('category') === 'reseaux_securite')>Réseaux & Sécurité</option>
                <option value="informatique_gestion" @selected(request('category') === 'informatique_gestion')>Informatique de gestion</option>
                <option value="bases_informatiques"  @selected(request('category') === 'bases_informatiques')>Bases informatiques</option>
            </select>
            <select name="level" class="filter-select" onchange="this.form.submit()">
                <option value="">Tous les niveaux</option>
                <option value="debutant"      @selected(request('level') === 'debutant')>Débutant</option>
                <option value="intermediaire" @selected(request('level') === 'intermediaire')>Intermédiaire</option>
                <option value="avance"        @selected(request('level') === 'avance')>Avancé</option>
            </select>
        </form>
    </div>

    @if($courses->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            <p>Aucune formation trouvée.</p>
        </div>
    @else
        <div class="courses-grid">
            @foreach($courses as $course)
            <a href="{{ route('courses.show', $course->slug) }}" class="course-card">
                @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="course-thumb">
                @else
                    <div class="course-thumb-placeholder">📚</div>
                @endif
                <div class="course-body">
                    <span class="course-category">
                        {{ match($course->category) {
                            'reseaux_securite'     => 'Réseaux & Sécurité',
                            'informatique_gestion' => 'Informatique de gestion',
                            'bases_informatiques'  => 'Bases informatiques',
                            default => $course->category
                        } }}
                    </span>
                    <div class="course-title">{{ $course->title }}</div>
                    <div class="course-instructor">Par {{ $course->instructor->name }}</div>
                    <span class="badge-level">
                        {{ match($course->level) {
                            'debutant'      => 'Débutant',
                            'intermediaire' => 'Intermédiaire',
                            'avance'        => 'Avancé',
                            default => $course->level
                        } }}
                    </span>
                    <div class="course-meta">
                        <span>{{ $course->total_lessons }} leçons</span>
                        <span>{{ $course->total_duration }}min</span>
                        <span class="course-price {{ $course->is_free ? 'free' : '' }}" style="margin-left:auto">
                            {{ $course->is_free ? 'Gratuit' : number_format($course->price, 0, ',', ' ') . ' FCFA' }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div style="max-width:1100px;margin:0 auto;padding:0 32px 40px;display:flex;justify-content:center">
            {{ $courses->links() }}
        </div>
    @endif
</div>
@endsection
