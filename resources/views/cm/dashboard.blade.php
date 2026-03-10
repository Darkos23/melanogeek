@extends('cm.layout')

@section('title', 'Dashboard CM')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ── HERO BANNER ── */
    .cm-hero {
        background: linear-gradient(135deg, var(--cm-soft) 0%, transparent 60%);
        border: 1px solid var(--cm-border);
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }
    .cm-hero::before {
        content: '🛡️';
        position: absolute;
        right: 28px; top: 50%; transform: translateY(-50%);
        font-size: 5rem;
        opacity: .08;
        pointer-events: none;
    }
    .cm-hero-title {
        font-family: var(--font-head);
        font-size: 1.4rem; font-weight: 800;
        color: var(--text);
        margin-bottom: 6px;
    }
    .cm-hero-title span { color: var(--cm); }
    .cm-hero-subtitle { font-size: .84rem; color: var(--text-muted); line-height: 1.6; }

    /* ── RECENT CARDS (dashboard 2-col) ── */
    .cm-dash-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    @media (max-width: 900px) { .cm-dash-grid { grid-template-columns: 1fr; } }

    .report-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .report-item:last-child { border-bottom: none; }
    .report-item:hover { background: var(--bg-hover); }
    .report-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #E05555; flex-shrink: 0; margin-top: 6px;
    }
    .report-reason { font-size: .8rem; font-weight: 600; color: var(--text); }
    .report-meta { font-size: .74rem; color: var(--text-muted); margin-top: 2px; }

    .post-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 20px;
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .post-item:last-child { border-bottom: none; }
    .post-item:hover { background: var(--bg-hover); }
    .post-title { font-size: .82rem; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px; }
    .post-meta { font-size: .73rem; color: var(--text-muted); margin-top: 2px; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="cm-hero">
    <div>
        <div class="cm-hero-title">Bonjour, <span>{{ auth()->user()->name }}</span> 👋</div>
        <div class="cm-hero-subtitle">
            Bienvenue dans votre espace Community Manager.<br>
            Gardez la communauté saine et bienveillante.
        </div>
    </div>
</div>

{{-- Stat cards --}}
<div class="stat-grid">
    <div class="stat-card {{ $stats['reports_pending'] > 0 ? 'accent' : '' }}">
        <div class="stat-card-label">🚩 Signalements</div>
        <div class="stat-card-value">{{ $stats['reports_pending'] }}</div>
        <div class="stat-card-sub">en attente de traitement</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">📝 Posts aujourd'hui</div>
        <div class="stat-card-value">{{ $stats['posts_today'] }}</div>
        <div class="stat-card-sub">publications du jour</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">💬 Commentaires</div>
        <div class="stat-card-value">{{ $stats['comments_today'] }}</div>
        <div class="stat-card-sub">commentaires du jour</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">👥 Membres</div>
        <div class="stat-card-value">{{ number_format($stats['users_total']) }}</div>
        <div class="stat-card-sub">utilisateurs inscrits</div>
    </div>
</div>

{{-- Two column --}}
<div class="cm-dash-grid">

    {{-- Signalements récents --}}
    <div class="cm-table-wrap">
        <div class="cm-table-header">
            <div class="cm-table-title">🚩 Signalements récents</div>
            <a href="{{ route('cm.reports') }}" class="btn-action success" style="font-size:.72rem;">Voir tout →</a>
        </div>
        @forelse($recentReports as $report)
            <div class="report-item">
                <div class="report-dot"></div>
                <div style="flex:1;min-width:0;">
                    <div class="report-reason">{{ $report->reason ?? 'Signalement' }}</div>
                    <div class="report-meta">
                        par <strong>@{{ $report->reporter?->username ?? '?' }}</strong>
                        · post de <strong>@{{ $report->post?->user?->username ?? '?' }}</strong>
                        · {{ $report->created_at->diffForHumans() }}
                    </div>
                </div>
                <a href="{{ route('cm.reports') }}" class="btn-action" style="font-size:.7rem;white-space:nowrap;">Traiter</a>
            </div>
        @empty
            <div style="padding:32px;text-align:center;color:var(--text-muted);font-size:.84rem;">
                ✅ Aucun signalement en attente
            </div>
        @endforelse
    </div>

    {{-- Publications récentes --}}
    <div class="cm-table-wrap">
        <div class="cm-table-header">
            <div class="cm-table-title">📝 Publications récentes</div>
            <a href="{{ route('cm.posts') }}" class="btn-action success" style="font-size:.72rem;">Voir tout →</a>
        </div>
        @forelse($recentPosts as $post)
            <div class="post-item">
                <div class="user-avatar-mini">
                    <div class="user-avatar-mini-inner">
                        @if($post->user?->avatar)
                            <img src="{{ Storage::url($post->user->avatar) }}" alt="">
                        @else
                            {{ mb_strtoupper(mb_substr($post->user?->name ?? '?', 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="post-title">{{ $post->title ?: '(sans titre)' }}</div>
                    <div class="post-meta">@{{ $post->user?->username ?? '?' }} · {{ $post->created_at->diffForHumans() }}</div>
                </div>
                @if($post->is_exclusive)
                    <span class="badge badge-gold" style="font-size:.65rem;">✦</span>
                @endif
            </div>
        @empty
            <div style="padding:32px;text-align:center;color:var(--text-muted);font-size:.84rem;">
                Aucune publication récente.
            </div>
        @endforelse
    </div>

</div>

@endsection
