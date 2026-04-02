@extends('layouts.app')

@section('title', 'Mes formations — Waxtu')

@push('styles')
<style>
.my-courses-page {
    padding-top: calc(80px + env(safe-area-inset-top));
    min-height: 100vh;
}
.my-courses-inner {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 32px 80px;
}
.page-title {
    font-family: var(--font-head);
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 28px;
}
.page-title span { color: var(--gold); }

.enrollments-list { display: flex; flex-direction: column; gap: 16px; }
.enrollment-card {
    display: flex;
    gap: 18px;
    align-items: center;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 16px;
    text-decoration: none;
    color: inherit;
    transition: border-color .2s, transform .15s;
}
.enrollment-card:hover { border-color: var(--border-hover); transform: translateY(-2px); }
.enrollment-thumb {
    width: 120px;
    height: 68px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    background: var(--bg-card2);
}
.enrollment-thumb-placeholder {
    width: 120px;
    height: 68px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--terra-soft), var(--gold-soft));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    flex-shrink: 0;
}
.enrollment-info { flex: 1; min-width: 0; }
.enrollment-title {
    font-weight: 700;
    font-size: .95rem;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.enrollment-instructor { font-size: .8rem; color: var(--text-muted); margin-bottom: 10px; }
.progress-bar { height: 5px; background: var(--border); border-radius: 3px; overflow: hidden; margin-bottom: 4px; }
.progress-fill { height: 100%; background: var(--terra); border-radius: 3px; }
.progress-text { font-size: .75rem; color: var(--text-muted); }
.btn-continue {
    padding: 8px 16px;
    background: var(--terra);
    color: #fff;
    border-radius: 8px;
    font-size: .82rem;
    font-weight: 600;
    text-decoration: none;
    flex-shrink: 0;
    white-space: nowrap;
}

.empty-state {
    text-align: center;
    padding: 60px 0;
    color: var(--text-muted);
}
.empty-state svg { width: 64px; height: 64px; margin-bottom: 16px; opacity: .3; }
.empty-state a {
    display: inline-block;
    margin-top: 16px;
    padding: 12px 24px;
    background: var(--terra);
    color: #fff;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
}

@media (max-width: 640px) {
    .my-courses-inner { padding: 24px 16px 80px; }
    .enrollment-card { gap: 12px; }
    .enrollment-thumb, .enrollment-thumb-placeholder { width: 80px; height: 46px; }
    .btn-continue { display: none; }
}
</style>
@endpush

@section('content')
<div class="my-courses-page">
    <div class="my-courses-inner">
        <h1 class="page-title">Mes <span>formations</span></h1>

        @if($enrollments->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <p>Tu n'es inscrit à aucune formation pour l'instant.</p>
                <a href="{{ route('courses.index') }}">Découvrir les formations</a>
            </div>
        @else
            <div class="enrollments-list">
                @foreach($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                    $progress = $course->getProgressFor(auth()->user());
                    $firstLesson = $course->lessons()->first();
                @endphp
                <div class="enrollment-card">
                    @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="enrollment-thumb">
                    @else
                        <div class="enrollment-thumb-placeholder">📚</div>
                    @endif
                    <div class="enrollment-info">
                        <div class="enrollment-title">{{ $course->title }}</div>
                        <div class="enrollment-instructor">Par {{ $course->instructor->name }}</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:{{ $progress }}%"></div>
                        </div>
                        <div class="progress-text">{{ $progress }}% complété</div>
                    </div>
                    @if($firstLesson)
                        <a href="{{ route('lessons.show', [$course->slug, $firstLesson->id]) }}" class="btn-continue">
                            {{ $progress > 0 ? 'Continuer' : 'Commencer' }}
                        </a>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
