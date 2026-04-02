@extends('layouts.app')

@section('title', 'Espace Instructeur — Waxtu')

@push('styles')
<style>
.instructor-page {
    padding-top: calc(80px + env(safe-area-inset-top));
    min-height: 100vh;
}
.instructor-inner {
    max-width: 1000px;
    margin: 0 auto;
    padding: 40px 32px 80px;
}
.instructor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}
.instructor-title {
    font-family: var(--font-head);
    font-size: 1.6rem;
    font-weight: 800;
}
.instructor-title span { color: var(--gold); }
.btn-create {
    padding: 10px 22px;
    background: var(--terra);
    color: #fff;
    border-radius: 10px;
    font-weight: 700;
    font-size: .88rem;
    text-decoration: none;
    transition: opacity .2s;
}
.btn-create:hover { opacity: .85; }

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 36px;
}
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px;
    text-align: center;
}
.stat-value {
    font-family: var(--font-head);
    font-size: 2rem;
    font-weight: 800;
    color: var(--gold);
}
.stat-label { font-size: .8rem; color: var(--text-muted); margin-top: 4px; }

/* Liste cours */
.courses-list { display: flex; flex-direction: column; gap: 12px; }
.course-row {
    display: flex;
    gap: 16px;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 14px 16px;
}
.course-row-thumb {
    width: 80px;
    height: 46px;
    border-radius: 6px;
    object-fit: cover;
    background: var(--bg-card2);
    flex-shrink: 0;
}
.course-row-placeholder {
    width: 80px;
    height: 46px;
    border-radius: 6px;
    background: linear-gradient(135deg, var(--terra-soft), var(--gold-soft));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.course-row-info { flex: 1; min-width: 0; }
.course-row-title { font-weight: 700; font-size: .92rem; margin-bottom: 2px; }
.course-row-meta { font-size: .78rem; color: var(--text-muted); }
.course-row-actions { display: flex; gap: 8px; }
.badge-status {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
}
.badge-published { background: rgba(42,122,72,.15); color: var(--green); }
.badge-draft { background: var(--gold-soft); color: var(--gold); }
.badge-archived { background: var(--border); color: var(--text-muted); }

.btn-edit {
    padding: 6px 14px;
    background: var(--bg-card2);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 7px;
    font-size: .8rem;
    text-decoration: none;
    transition: background .15s;
}
.btn-edit:hover { background: var(--bg-hover); }

.empty-state {
    text-align: center;
    padding: 50px;
    color: var(--text-muted);
}

@media (max-width: 640px) {
    .instructor-inner { padding: 24px 16px 80px; }
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .instructor-header { gap: 12px; }
    .instructor-title { font-size: 1.2rem; }
}
</style>
@endpush

@section('content')
<div class="instructor-page">
    <div class="instructor-inner">
        <div class="instructor-header">
            <h1 class="instructor-title">Espace <span>instructeur</span></h1>
            <a href="{{ route('instructor.courses.create') }}" class="btn-create">+ Nouveau cours</a>
        </div>

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $courses->count() }}</div>
                <div class="stat-label">Cours créés</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $courses->sum('enrollments_count') }}</div>
                <div class="stat-label">Étudiants inscrits</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $courses->where('status', 'published')->count() }}</div>
                <div class="stat-label">Cours publiés</div>
            </div>
        </div>

        {{-- Cours --}}
        @if($courses->isEmpty())
            <div class="empty-state">
                <p>Vous n'avez pas encore créé de cours.</p>
                <a href="{{ route('instructor.courses.create') }}" style="color:var(--terra)">Créer votre premier cours →</a>
            </div>
        @else
            <div class="courses-list">
                @foreach($courses as $course)
                <div class="course-row">
                    @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" class="course-row-thumb" alt="">
                    @else
                        <div class="course-row-placeholder">📚</div>
                    @endif
                    <div class="course-row-info">
                        <div class="course-row-title">{{ $course->title }}</div>
                        <div class="course-row-meta">
                            {{ $course->total_lessons }} leçons ·
                            {{ $course->enrollments_count }} inscrits ·
                            <span class="badge-status badge-{{ $course->status }}">{{ $course->status }}</span>
                        </div>
                    </div>
                    <div class="course-row-actions">
                        <a href="{{ route('instructor.courses.edit', $course->id) }}" class="btn-edit">Modifier</a>
                        @if($course->status === 'published')
                            <a href="{{ route('courses.show', $course->slug) }}" class="btn-edit">Voir</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
