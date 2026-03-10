@extends('cm.layout')

@section('title', 'Signalements')
@section('page-title', '🚩 Signalements')

@push('styles')
<style>
    .report-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        transition: border-color .2s, box-shadow .2s;
    }
    .report-card:hover { border-color: var(--cm-border); box-shadow: 0 4px 20px var(--cm-glow); }
    .report-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .report-card-reason { font-family: var(--font-head); font-size: .92rem; font-weight: 700; color: var(--text); }
    .report-card-date { font-size: .74rem; color: var(--text-muted); white-space: nowrap; }
    .report-card-people { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .report-person { display: flex; align-items: center; gap: 8px; }
    .report-person-label { font-size: .7rem; color: var(--text-faint); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 2px; }
    .report-person-name { font-size: .82rem; font-weight: 600; }
    .report-separator { color: var(--text-faint); font-size: .8rem; }
    .report-post-preview {
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 12px 14px;
        font-size: .8rem;
        color: var(--text-muted);
        line-height: 1.6;
    }
    .report-post-title { font-weight: 600; color: var(--text); margin-bottom: 4px; font-size: .84rem; }
    .report-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .report-note-input {
        flex: 1; min-width: 200px;
        background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 8px; padding: 7px 12px;
        color: var(--text); font-family: var(--font-body); font-size: .8rem;
        outline: none; transition: border-color .2s;
    }
    .report-note-input:focus { border-color: var(--cm); }
    .report-note-input::placeholder { color: var(--text-faint); }

    .reports-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 480px), 1fr)); gap: 16px; }
</style>
@endpush

@section('content')

{{-- Filter tabs --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    @foreach(['pending' => '🚩 En attente', 'resolved' => '✅ Résolus', 'dismissed' => '↩ Ignorés'] as $key => $label)
        <a href="{{ route('cm.reports', ['status' => $key]) }}"
           style="padding:7px 16px;border-radius:100px;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .2s;border:1px solid var(--border);
                  {{ request('status', 'pending') === $key ? 'background:var(--cm);color:#040E0C;border-color:var(--cm);' : 'color:var(--text-muted);' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

@if($reports->isEmpty())
    <div style="text-align:center;padding:64px;color:var(--text-muted);">
        @if(request('status', 'pending') === 'pending')
            <div style="font-size:2.5rem;margin-bottom:12px;">✅</div>
            <div style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;margin-bottom:6px;">File propre !</div>
            <div style="font-size:.84rem;">Aucun signalement en attente. Excellent travail. 🎉</div>
        @else
            <div style="font-size:.84rem;">Aucun signalement dans cette catégorie.</div>
        @endif
    </div>
@else
    <div class="reports-grid">
        @foreach($reports as $report)
        <div class="report-card">

            {{-- Header --}}
            <div class="report-card-header">
                <div>
                    <div class="report-card-reason">{{ $report->reason ?? 'Signalement' }}</div>
                    <div style="font-size:.74rem;color:var(--text-muted);margin-top:3px;">
                        Signalement #{{ $report->id }} · {{ $report->created_at->diffForHumans() }}
                    </div>
                </div>
                <span class="badge {{ $report->status === 'pending' ? 'badge-red' : ($report->status === 'resolved' ? 'badge-cm' : 'badge-gray') }}">
                    {{ $report->status === 'pending' ? '🔴 En attente' : ($report->status === 'resolved' ? '✅ Résolu' : '↩ Ignoré') }}
                </span>
            </div>

            {{-- Parties --}}
            <div class="report-card-people">
                <div class="report-person">
                    <div class="user-avatar-mini" style="width:28px;height:28px;">
                        <div class="user-avatar-mini-inner" style="font-size:.65rem;">
                            @if($report->reporter?->avatar)<img src="{{ Storage::url($report->reporter->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($report->reporter?->name ?? '?', 0, 1)) }}@endif
                        </div>
                    </div>
                    <div>
                        <div class="report-person-label">Signaleur</div>
                        <div class="report-person-name">&#64;{{ $report->reporter?->username ?? '?' }}</div>
                    </div>
                </div>

                <span class="report-separator">→</span>

                <div class="report-person">
                    <div class="user-avatar-mini" style="width:28px;height:28px;background:linear-gradient(135deg,#E05555,#C8522A);">
                        <div class="user-avatar-mini-inner" style="font-size:.65rem;">
                            @if($report->post?->user?->avatar)<img src="{{ Storage::url($report->post->user->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($report->post?->user?->name ?? '?', 0, 1)) }}@endif
                        </div>
                    </div>
                    <div>
                        <div class="report-person-label">Auteur du post</div>
                        <div class="report-person-name">&#64;{{ $report->post?->user?->username ?? '?' }}</div>
                    </div>
                </div>
            </div>

            {{-- Post preview --}}
            @if($report->post)
                <div class="report-post-preview">
                    <div class="report-post-title">{{ $report->post->title ?: '(sans titre)' }}</div>
                    @if($report->post->body)
                        {{ Str::limit(strip_tags($report->post->body), 100) }}
                    @endif
                    @if(!$report->post->trashed())
                        <div style="margin-top:6px;">
                            <a href="{{ route('posts.show', $report->post->id) }}" target="_blank" style="color:var(--cm);font-size:.75rem;text-decoration:none;">↗ Voir le post</a>
                        </div>
                    @else
                        <div style="margin-top:6px;font-size:.75rem;color:#E05555;">Post déjà supprimé</div>
                    @endif
                </div>
            @else
                <div class="report-post-preview" style="color:#E05555;">Post introuvable (supprimé)</div>
            @endif

            {{-- Note admin si existe --}}
            @if($report->admin_note)
                <div style="font-size:.78rem;color:var(--text-muted);font-style:italic;padding:8px 12px;background:var(--bg-card2);border-radius:8px;">
                    💬 Note : {{ $report->admin_note }}
                </div>
            @endif

            {{-- Actions --}}
            @if($report->status === 'pending')
                <form method="POST" action="{{ route('cm.reports.resolve', $report->id) }}" class="report-actions">
                    @csrf @method('PATCH')
                    <input type="text" name="note" class="report-note-input" placeholder="Note optionnelle…">
                    @if($report->post && !$report->post->trashed())
                        <button type="submit" name="action" value="resolved" class="btn-action danger"
                                onclick="return confirm('Résoudre et supprimer le post signalé ?')">
                            🗑 Supprimer le post
                        </button>
                    @endif
                    <button type="submit" name="action" value="dismissed" class="btn-action warn">
                        ↩ Ignorer
                    </button>
                </form>
            @endif

        </div>
        @endforeach
    </div>

    @if($reports->hasPages())
        <div style="display:flex;justify-content:center;margin-top:20px;">
            {{ $reports->links() }}
        </div>
    @endif
@endif

@endsection
