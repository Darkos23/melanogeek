@extends('layouts.app')

@section('title', $lesson->title . ' — ' . $course->title)

@push('styles')
<style>
.lesson-page {
    padding-top: calc(70px + env(safe-area-inset-top));
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 320px;
    grid-template-rows: auto 1fr;
    max-height: 100vh;
    overflow: hidden;
}

/* Player zone */
.lesson-main {
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}
.video-player-wrap {
    background: #000;
    width: 100%;
    aspect-ratio: 16/9;
    position: relative;
}
.video-player-wrap video {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.no-video {
    width: 100%;
    aspect-ratio: 16/9;
    background: var(--bg-card);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    font-size: .9rem;
    flex-direction: column;
    gap: 8px;
}
.no-video svg { width: 48px; height: 48px; opacity: .4; }

.lesson-info {
    padding: 24px 28px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
}
.lesson-breadcrumb {
    font-size: .78rem;
    color: var(--text-muted);
    margin-bottom: 8px;
}
.lesson-breadcrumb a { color: var(--terra); text-decoration: none; }
.lesson-title {
    font-family: var(--font-head);
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 10px;
}
.lesson-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}
.btn-complete {
    padding: 9px 20px;
    background: var(--terra);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: .88rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .2s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-complete:hover { opacity: .85; }
.btn-complete.completed {
    background: var(--green);
    cursor: default;
}
.btn-complete.completed:hover { opacity: 1; }
.lesson-nav {
    display: flex;
    gap: 8px;
    margin-left: auto;
}
.btn-nav {
    padding: 8px 14px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: .82rem;
    color: var(--text);
    text-decoration: none;
    transition: background .2s;
}
.btn-nav:hover { background: var(--bg-hover); }

.lesson-description {
    padding: 20px 28px;
    color: var(--text-muted);
    font-size: .9rem;
    line-height: 1.7;
}

/* Sidebar programme */
.lesson-sidebar {
    border-left: 1px solid var(--border);
    overflow-y: auto;
    background: var(--bg-card);
    display: flex;
    flex-direction: column;
    grid-row: 1 / 3;
}
.sidebar-header {
    padding: 16px 18px;
    border-bottom: 1px solid var(--border);
    font-family: var(--font-head);
    font-weight: 700;
    font-size: .95rem;
    position: sticky;
    top: 0;
    background: var(--bg-card);
    z-index: 1;
}
.sidebar-section { border-bottom: 1px solid var(--border); }
.sidebar-section-title {
    padding: 10px 16px;
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--text-muted);
    background: var(--bg-card2);
}
.sidebar-lesson {
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 10px 16px;
    font-size: .85rem;
    color: var(--text-muted);
    text-decoration: none;
    border-bottom: 1px solid var(--border);
    transition: background .15s;
}
.sidebar-lesson:last-child { border-bottom: none; }
.sidebar-lesson:hover { background: var(--bg-hover); color: var(--text); }
.sidebar-lesson.active { background: var(--terra-soft); color: var(--terra); font-weight: 600; }
.sidebar-lesson.completed-lesson { color: var(--text-muted); }
.sidebar-lesson.completed-lesson .check-icon { color: var(--green); }
.check-icon { width: 16px; height: 16px; flex-shrink: 0; }
.lesson-duration-small { margin-left: auto; font-size: .75rem; white-space: nowrap; }

/* Progress global */
.course-progress-bar {
    padding: 10px 16px;
    background: var(--bg-card2);
    border-bottom: 1px solid var(--border);
}
.course-progress-label {
    font-size: .75rem;
    color: var(--text-muted);
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
}
.bar-track { height: 4px; background: var(--border); border-radius: 2px; overflow: hidden; }
.bar-fill { height: 100%; background: var(--terra); border-radius: 2px; }

@media (max-width: 900px) {
    .lesson-page {
        grid-template-columns: 1fr;
        grid-template-rows: auto;
        overflow: auto;
        max-height: none;
    }
    .lesson-sidebar {
        grid-row: auto;
        border-left: none;
        border-top: 1px solid var(--border);
        max-height: 400px;
    }
    .lesson-info { padding: 16px; }
    .lesson-description { padding: 16px; }
}
</style>
@endpush

@section('content')
@php
    // Calculer leçon précédente et suivante
    $allLessons = $course->sections->flatMap->lessons;
    $currentIndex = $allLessons->search(fn($l) => $l->id === $lesson->id);
    $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
    $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
    $totalLessons = $allLessons->count();
    $completedCount = $progress->count();
    $progressPct = $totalLessons > 0 ? round($completedCount / $totalLessons * 100) : 0;
@endphp

<div class="lesson-page">
    {{-- Main --}}
    <div class="lesson-main">
        {{-- Vidéo --}}
        @if($lesson->video_path)
            <div class="video-player-wrap">
                <video controls controlsList="nodownload" preload="metadata" id="lessonVideo"
                       onended="markComplete()">
                    <source src="{{ route('lesson.video', $lesson->id) }}" type="video/mp4">
                </video>
            </div>
        @else
            <div class="no-video">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/>
                </svg>
                <span>Vidéo bientôt disponible</span>
            </div>
        @endif

        {{-- Info leçon --}}
        <div class="lesson-info">
            <div class="lesson-breadcrumb">
                <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                <span> › {{ $lesson->section->title }}</span>
            </div>
            <h1 class="lesson-title">{{ $lesson->title }}</h1>
            <div class="lesson-actions">
                @if($isCompleted)
                    <button class="btn-complete completed" disabled>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Terminée
                    </button>
                @else
                    <button class="btn-complete" id="completeBtn" onclick="markComplete()">
                        Marquer comme terminée
                    </button>
                @endif
                <div class="lesson-nav">
                    @if($prevLesson)
                        <a href="{{ route('lessons.show', [$course->slug, $prevLesson->id]) }}" class="btn-nav">← Précédente</a>
                    @endif
                    @if($nextLesson)
                        <a href="{{ route('lessons.show', [$course->slug, $nextLesson->id]) }}" class="btn-nav">Suivante →</a>
                    @endif
                </div>
            </div>
        </div>

        @if($lesson->description)
        <div class="lesson-description">{{ $lesson->description }}</div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="lesson-sidebar">
        <div class="sidebar-header">Programme du cours</div>
        <div class="course-progress-bar">
            <div class="course-progress-label">
                <span>Progression</span>
                <span id="progressPct">{{ $progressPct }}%</span>
            </div>
            <div class="bar-track">
                <div class="bar-fill" id="progressFill" style="width:{{ $progressPct }}%"></div>
            </div>
        </div>
        @foreach($course->sections as $section)
        <div class="sidebar-section">
            <div class="sidebar-section-title">{{ $section->title }}</div>
            @foreach($section->lessons as $l)
            @php $isActive = $l->id === $lesson->id; $isDone = $progress->contains($l->id); @endphp
            <a href="{{ route('lessons.show', [$course->slug, $l->id]) }}"
               class="sidebar-lesson {{ $isActive ? 'active' : '' }} {{ $isDone && !$isActive ? 'completed-lesson' : '' }}">
                @if($isDone)
                    <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-5"/>
                    </svg>
                @else
                    <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                @endif
                <span>{{ $l->title }}</span>
                <span class="lesson-duration-small">{{ $l->duration }}min</span>
            </a>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
let completing = false;

async function markComplete() {
    if (completing) return;
    completing = true;

    const btn = document.getElementById('completeBtn');
    if (btn) {
        btn.textContent = '...';
        btn.disabled = true;
    }

    try {
        const res = await fetch('{{ route('lessons.complete', [$course->slug, $lesson->id]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });
        const data = await res.json();

        // Mettre à jour la progression
        document.getElementById('progressPct').textContent = data.progress + '%';
        document.getElementById('progressFill').style.width = data.progress + '%';

        if (btn) {
            btn.classList.add('completed');
            btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Terminée';
        }

        if (data.certificate_issued) {
            showCertificateNotif();
        }

        // Auto-avancer à la leçon suivante après 2s
        @if($nextLesson)
        setTimeout(() => {
            window.location.href = '{{ route('lessons.show', [$course->slug, $nextLesson->id]) }}';
        }, 2000);
        @endif

    } catch (e) {
        completing = false;
        if (btn) { btn.textContent = 'Marquer comme terminée'; btn.disabled = false; }
    }
}

function showCertificateNotif() {
    const notif = document.createElement('div');
    notif.style.cssText = `
        position:fixed;bottom:30px;left:50%;transform:translateX(-50%);
        background:var(--gold);color:#000;padding:14px 24px;border-radius:12px;
        font-weight:700;z-index:9999;box-shadow:var(--shadow-md);
        animation: slideUp .3s ease;
    `;
    notif.textContent = '🎓 Félicitations ! Votre certificat est disponible.';
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 5000);
}
</script>
@endpush
@endsection
