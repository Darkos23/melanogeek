@extends('owner.layout')

@section('title', 'overview')
@section('page-title', 'overview')

@section('content')
@php
    $laravelVersion = app()->version();
    $phpVersion     = phpversion();
    $environment    = app()->environment();
    $dbOk = true;
    try { \DB::connection()->getPdo(); } catch (\Exception $e) { $dbOk = false; }
@endphp

<style>
/* ── STATS ── */
.ow-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 40px;
}
.ow-stat {
    padding: 24px 22px;
    border-right: 1px solid rgba(255,255,255,.08);
}
.ow-stat:last-child { border-right: none; }
.ow-stat-label {
    font-size: .58rem;
    letter-spacing: .10em;
    text-transform: uppercase;
    color: rgba(255,255,255,.22);
    margin-bottom: 10px;
}
.ow-stat-val {
    font-size: 2.4rem;
    font-weight: 700;
    letter-spacing: -.05em;
    line-height: 1;
    color: rgba(255,255,255,.88);
    margin-bottom: 6px;
}
.ow-stat-sub { font-size: .62rem; color: rgba(255,255,255,.20); }
.ow-stat-sub .up { color: #6ee7b7; }
.ow-stat-sub a { color: #a78bfa; text-decoration: none; }
.ow-stat-sub a:hover { text-decoration: underline; }

/* ── ENV BAR ── */
.ow-env-bar {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 12px 0;
    border-top: 1px solid rgba(255,255,255,.05);
    border-bottom: 1px solid rgba(255,255,255,.05);
    margin-bottom: 40px;
    flex-wrap: wrap;
}
.ow-env-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: .65rem;
    color: rgba(255,255,255,.30);
}
.ow-env-item strong { color: rgba(255,255,255,.55); font-weight: 500; }
.ow-dot {
    width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0;
}
.ow-dot.green  { background: #6ee7b7; box-shadow: 0 0 5px rgba(110,231,183,.5); }
.ow-dot.yellow { background: #fcd34d; box-shadow: 0 0 5px rgba(252,211,77,.5); }
.ow-dot.red    { background: #f87171; box-shadow: 0 0 5px rgba(248,113,113,.5); }

/* ── GRID 2 COL ── */
.ow-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}

/* ── BLOCK ── */
.ow-block-title {
    font-size: .58rem;
    letter-spacing: .10em;
    text-transform: uppercase;
    color: rgba(255,255,255,.20);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ow-block-title a {
    color: rgba(255,255,255,.18);
    text-decoration: none;
    transition: color .12s;
}
.ow-block-title a:hover { color: rgba(255,255,255,.45); }

/* ── REPORT ROWS ── */
.ow-report-row {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: start;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,.05);
    gap: 12px;
}
.ow-report-row:last-child { border-bottom: none; }
.ow-report-reason {
    font-size: .7rem;
    color: rgba(255,255,255,.55);
    margin-bottom: 3px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ow-report-meta { font-size: .62rem; color: rgba(255,255,255,.22); }
.ow-report-meta span { color: #f87171; }
.ow-report-badge {
    font-size: .58rem;
    padding: 2px 7px;
    border-radius: 3px;
    background: rgba(248,113,113,.08);
    border: 1px solid rgba(248,113,113,.18);
    color: #f87171;
    white-space: nowrap;
    flex-shrink: 0;
}

/* ── POST ROWS ── */
.ow-post-row {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: start;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,.05);
    gap: 12px;
}
.ow-post-row:last-child { border-bottom: none; }
.ow-post-title {
    font-size: .7rem;
    color: rgba(255,255,255,.55);
    margin-bottom: 3px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ow-post-meta { font-size: .62rem; color: rgba(255,255,255,.22); }
.ow-post-badge {
    font-size: .58rem;
    padding: 2px 7px;
    border-radius: 3px;
    white-space: nowrap;
    flex-shrink: 0;
}
.ow-post-badge.pub {
    background: rgba(110,231,183,.08);
    border: 1px solid rgba(110,231,183,.18);
    color: #6ee7b7;
}
.ow-post-badge.draft {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.08);
    color: rgba(255,255,255,.25);
}

/* ── LOGS ── */
.ow-log-row {
    display: grid;
    grid-template-columns: 72px 110px 1fr;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,.05);
    gap: 16px;
    align-items: baseline;
}
.ow-log-row:last-child { border-bottom: none; }
.ow-log-time { font-size: .62rem; color: rgba(255,255,255,.18); }
.ow-log-who  { font-size: .68rem; color: #a78bfa; }
.ow-log-desc { font-size: .68rem; color: rgba(255,255,255,.40); }

.ow-empty {
    font-size: .68rem;
    color: rgba(255,255,255,.18);
    padding: 8px 0;
}
.ow-empty::before { content: '$ '; color: #a78bfa; }

@media (max-width: 900px) {
    .ow-stats { grid-template-columns: repeat(2, 1fr); }
    .ow-grid  { grid-template-columns: 1fr; }
}
</style>

{{-- ── STATS ── --}}
<div class="ow-stats">
    <div class="ow-stat">
        <div class="ow-stat-label">users_total</div>
        <div class="ow-stat-val">{{ number_format($stats['users_total']) }}</div>
        <div class="ow-stat-sub"><span class="up">+{{ $stats['users_new_week'] }}</span> this week</div>
    </div>
    <div class="ow-stat">
        <div class="ow-stat-label">active_users</div>
        <div class="ow-stat-val">{{ number_format($stats['users_active']) }}</div>
        <div class="ow-stat-sub">registered &amp; active</div>
    </div>
    <div class="ow-stat">
        <div class="ow-stat-label">posts_total</div>
        <div class="ow-stat-val">{{ number_format($stats['posts_total']) }}</div>
        <div class="ow-stat-sub">all content</div>
    </div>
    <div class="ow-stat">
        <div class="ow-stat-label">staff_count</div>
        <div class="ow-stat-val">{{ $stats['admins_total'] }}</div>
        <div class="ow-stat-sub"><a href="{{ route('owner.staff') }}">manage →</a></div>
    </div>
</div>

{{-- ── ENV BAR ── --}}
<div class="ow-env-bar">
    @foreach([
        ['Laravel',   'v'.$laravelVersion,                               'green'],
        ['PHP',       'v'.$phpVersion,                                   'green'],
        ['DB',        $dbOk ? 'connected' : 'error',                    $dbOk ? 'green' : 'red'],
        ['ENV',       $environment,                                       $environment === 'production' ? 'green' : 'yellow'],
        ['Cache',     config('cache.default'),                            'green'],
        ['Queue',     config('queue.default'),                            config('queue.default') === 'sync' ? 'yellow' : 'green'],
    ] as [$key, $val, $dot])
    <div class="ow-env-item">
        <div class="ow-dot {{ $dot }}"></div>
        {{ $key }} <strong>{{ $val }}</strong>
    </div>
    @endforeach
</div>

{{-- ── GRID : REPORTS + POSTS ── --}}
<div class="ow-grid" style="margin-bottom:40px">

    {{-- Reports --}}
    <div>
        <div class="ow-block-title">
            <span>pending_reports
                @if($pending_reports->count() > 0)
                    <span style="color:#f87171;margin-left:8px">{{ $pending_reports->count() }}</span>
                @endif
            </span>
            <a href="{{ route('admin.dashboard') }}">view all →</a>
        </div>
        @forelse($pending_reports as $report)
        <div class="ow-report-row">
            <div style="min-width:0">
                <div class="ow-report-reason">{{ $report->reason }}</div>
                <div class="ow-report-meta">
                    by <span style="color:rgba(255,255,255,.35)">@{{ $report->reporter?->username ?? '?' }}</span>
                    · {{ $report->created_at?->diffForHumans() ?? '—' }}
                </div>
            </div>
            <div class="ow-report-badge">pending</div>
        </div>
        @empty
        <div class="ow-empty">no pending reports</div>
        @endforelse
    </div>

    {{-- Latest posts --}}
    <div>
        <div class="ow-block-title">
            <span>latest_posts</span>
            <a href="{{ route('admin.posts') }}">view all →</a>
        </div>
        @forelse($latest_posts as $post)
        <div class="ow-post-row">
            <div style="min-width:0">
                <div class="ow-post-title">{{ $post->title }}</div>
                <div class="ow-post-meta">
                    @{{ $post->user?->username ?? '?' }}
                    · {{ $post->created_at?->diffForHumans() ?? '—' }}
                </div>
            </div>
            <div class="ow-post-badge {{ $post->is_published ? 'pub' : 'draft' }}">
                {{ $post->is_published ? 'pub' : 'draft' }}
            </div>
        </div>
        @empty
        <div class="ow-empty">no posts yet</div>
        @endforelse
    </div>

</div>

{{-- ── LOGS ── --}}
<div class="ow-block-title">
    <span>activity_log</span>
    <a href="{{ route('owner.logs') }}">view all →</a>
</div>
@forelse($recent_logs as $log)
<div class="ow-log-row">
    <div class="ow-log-time">{{ $log->created_at?->diffForHumans() ?? '—' }}</div>
    <div class="ow-log-who">@{{ $log->staff?->username ?? 'system' }}</div>
    <div class="ow-log-desc">{{ $log->description }}</div>
</div>
@empty
<div class="ow-empty">no activity recorded</div>
@endforelse

@endsection
