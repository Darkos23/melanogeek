@extends('layouts.app')

@section('title', $course->title . ' — Waxtu')

@push('styles')
<style>
.course-show {
    padding-top: calc(80px + env(safe-area-inset-top));
    min-height: 100vh;
}

/* Hero */
.course-hero {
    background: var(--bg-card);
    border-bottom: 1px solid var(--border);
    padding: 40px 32px;
}
.course-hero-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 40px;
    align-items: start;
}
.course-hero-category {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--terra);
    margin-bottom: 10px;
}
.course-hero-title {
    font-family: var(--font-head);
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.25;
    margin-bottom: 14px;
}
.course-hero-desc {
    color: var(--text-muted);
    line-height: 1.7;
    margin-bottom: 20px;
}
.course-hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: .85rem;
    color: var(--text-muted);
}
.course-hero-meta strong { color: var(--text); }

/* Card d'inscription */
.enroll-card {
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
    position: sticky;
    top: 100px;
}
.enroll-thumb {
    width: 100%;
    aspect-ratio: 16/9;
    object-fit: cover;
    background: var(--bg-hover);
}
.enroll-thumb-placeholder {
    width: 100%;
    aspect-ratio: 16/9;
    background: linear-gradient(135deg, var(--terra-soft), var(--gold-soft));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}
.enroll-body { padding: 20px; }
.enroll-price {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--gold);
    margin-bottom: 16px;
}
.enroll-price.free { color: var(--green); }
.btn-enroll {
    display: block;
    width: 100%;
    padding: 14px;
    background: var(--terra);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: opacity .2s;
}
.btn-enroll:hover { opacity: .88; }
.btn-enroll.enrolled {
    background: var(--green);
}

.enroll-includes {
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.enroll-includes li {
    display: flex;
    gap: 10px;
    align-items: center;
    font-size: .85rem;
    color: var(--text-muted);
    list-style: none;
}

/* Contenu */
.course-content {
    max-width: 1100px;
    margin: 0 auto;
    padding: 40px 32px 80px;
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 40px;
    align-items: start;
}

/* Ce que tu apprendras */
.section-title {
    font-family: var(--font-head);
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 16px;
}
.learn-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 32px;
}
.learn-item {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    font-size: .88rem;
    color: var(--text-muted);
}
.learn-item svg { flex-shrink: 0; margin-top: 2px; color: var(--terra); }

/* Programme */
.curriculum { margin-bottom: 32px; }
.section-block {
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 8px;
}
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    background: var(--bg-card);
    cursor: pointer;
    user-select: none;
}
.section-header-title {
    font-weight: 600;
    font-size: .92rem;
}
.section-header-count {
    font-size: .78rem;
    color: var(--text-muted);
}
.section-lessons {
    border-top: 1px solid var(--border);
}
.lesson-row {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 10px 18px;
    font-size: .88rem;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border);
}
.lesson-row:last-child { border-bottom: none; }
.lesson-row svg { flex-shrink: 0; }
.lesson-row a { color: var(--terra); text-decoration: none; }
.lesson-row a:hover { text-decoration: underline; }
.lesson-duration { margin-left: auto; font-size: .78rem; }
.badge-preview {
    font-size: .68rem;
    padding: 2px 6px;
    border-radius: 4px;
    background: var(--gold-soft);
    color: var(--gold);
    font-weight: 600;
}

/* Instructeur */
.instructor-card {
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    margin-bottom: 32px;
}
.instructor-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.instructor-name { font-weight: 700; margin-bottom: 2px; }
.instructor-specialty { font-size: .82rem; color: var(--text-muted); }

/* Avis */
.reviews-list { display: flex; flex-direction: column; gap: 12px; }
.review-item {
    padding: 14px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
}
.review-header { display: flex; gap: 10px; align-items: center; margin-bottom: 6px; }
.review-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
.review-name { font-weight: 600; font-size: .88rem; }
.review-stars { color: var(--gold); font-size: .85rem; margin-left: auto; }
.review-text { font-size: .85rem; color: var(--text-muted); }

/* Progress bar */
.progress-wrap { margin-bottom: 20px; }
.progress-label { font-size: .82rem; color: var(--text-muted); margin-bottom: 6px; display: flex; justify-content: space-between; }
.progress-bar { height: 6px; background: var(--border); border-radius: 4px; overflow: hidden; }
.progress-fill { height: 100%; background: var(--terra); border-radius: 4px; transition: width .5s; }

@media (max-width: 900px) {
    .course-hero-inner, .course-content {
        grid-template-columns: 1fr;
    }
    .enroll-card { position: static; }
}
@media (max-width: 640px) {
    .course-hero { padding: 24px 16px; }
    .course-hero-title { font-size: 1.4rem; }
    .course-content { padding: 24px 16px 80px; }
    .learn-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="course-show">

    {{-- Hero --}}
    <div class="course-hero">
        <div class="course-hero-inner">
            <div>
                <div class="course-hero-category">
                    {{ match($course->category) {
                        'reseaux_securite'     => 'Réseaux & Sécurité',
                        'informatique_gestion' => 'Informatique de gestion',
                        'bases_informatiques'  => 'Bases informatiques',
                        default => $course->category
                    } }}
                </div>
                <h1 class="course-hero-title">{{ $course->title }}</h1>
                <p class="course-hero-desc">{{ $course->description }}</p>
                <div class="course-hero-meta">
                    <span>Par <strong>{{ $course->instructor->name }}</strong></span>
                    <span>{{ $course->total_lessons }} leçons</span>
                    <span>{{ $course->total_duration }} min</span>
                    <span>⭐ {{ $course->average_rating ?: 'Nouveau' }}</span>
                    <span>{{ $course->enrollments_count ?? $course->enrollments()->count() }} inscrits</span>
                </div>
            </div>

            {{-- Card inscription --}}
            <div class="enroll-card">
                @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="enroll-thumb">
                @else
                    <div class="enroll-thumb-placeholder">📚</div>
                @endif
                <div class="enroll-body">
                    @if($isEnrolled)
                        <div class="progress-wrap">
                            <div class="progress-label"><span>Progression</span><strong>{{ $progress }}%</strong></div>
                            <div class="progress-bar"><div class="progress-fill" style="width:{{ $progress }}%"></div></div>
                        </div>
                        @php $firstLesson = $course->lessons()->first(); @endphp
                        @if($firstLesson)
                        <a href="{{ route('lessons.show', [$course->slug, $firstLesson->id]) }}" class="btn-enroll enrolled">
                            Continuer la formation
                        </a>
                        @endif
                    @else
                        <div class="enroll-price {{ $course->is_free ? 'free' : '' }}">
                            {{ $course->is_free ? 'Gratuit' : number_format($course->price, 0, ',', ' ') . ' FCFA' }}
                        </div>
                        @auth
                            <a href="{{ route('enrollments.checkout', $course->slug) }}" class="btn-enroll">
                                {{ $course->is_free ? 'S\'inscrire gratuitement' : 'S\'inscrire maintenant' }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-enroll">Se connecter pour s'inscrire</a>
                        @endauth
                    @endif

                    <ul class="enroll-includes">
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.89L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                            {{ $course->total_lessons }} leçons vidéo
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Certificat à la fin
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Accès à vie
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Contenu --}}
    <div class="course-content">
        <div>
            {{-- Ce que tu apprendras --}}
            @if($course->what_you_learn)
            <h2 class="section-title">Ce que tu vas apprendre</h2>
            <div class="learn-grid">
                @foreach($course->what_you_learn as $item)
                <div class="learn-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $item }}
                </div>
                @endforeach
            </div>
            @endif

            {{-- Programme --}}
            <h2 class="section-title">Programme du cours</h2>
            <div class="curriculum">
                @foreach($course->sections as $section)
                <div class="section-block">
                    <div class="section-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none'">
                        <span class="section-header-title">{{ $section->title }}</span>
                        <span class="section-header-count">{{ $section->lessons->count() }} leçons</span>
                    </div>
                    <div class="section-lessons">
                        @foreach($section->lessons as $lesson)
                        <div class="lesson-row">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polygon points="10,8 16,12 10,16" fill="currentColor"/>
                            </svg>
                            @if($lesson->is_preview || $isEnrolled)
                                <a href="{{ route('lessons.show', [$course->slug, $lesson->id]) }}">{{ $lesson->title }}</a>
                            @else
                                <span>{{ $lesson->title }}</span>
                            @endif
                            @if($lesson->is_preview)
                                <span class="badge-preview">Aperçu</span>
                            @endif
                            <span class="lesson-duration">{{ $lesson->duration }}min</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Instructeur --}}
            <h2 class="section-title">L'instructeur</h2>
            <div class="instructor-card">
                <img src="{{ $course->instructor->avatar ? Storage::url($course->instructor->avatar) : asset('images/default-avatar.png') }}"
                     alt="{{ $course->instructor->name }}" class="instructor-avatar">
                <div>
                    <div class="instructor-name">{{ $course->instructor->name }}</div>
                    <div class="instructor-specialty">{{ $course->instructor->specialty ?? $course->instructor->niche }}</div>
                    <div style="font-size:.82rem;color:var(--text-muted);margin-top:4px">{{ $course->instructor->bio }}</div>
                </div>
            </div>

            {{-- Avis --}}
            @if($course->reviews->count())
            <h2 class="section-title">Avis des apprenants</h2>
            <div class="reviews-list">
                @foreach($course->reviews->take(5) as $review)
                <div class="review-item">
                    <div class="review-header">
                        <img src="{{ $review->user->avatar ? Storage::url($review->user->avatar) : asset('images/default-avatar.png') }}"
                             alt="{{ $review->user->name }}" class="review-avatar">
                        <span class="review-name">{{ $review->user->name }}</span>
                        <span class="review-stars">{{ str_repeat('★', $review->rating) }}</span>
                    </div>
                    <div class="review-text">{{ $review->comment }}</div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Sidebar mobile (vide, la card est en haut sur desktop) --}}
        <div></div>
    </div>
</div>
@endsection
