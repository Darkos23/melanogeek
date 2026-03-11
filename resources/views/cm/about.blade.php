@extends('cm.layout')

@section('title', 'À propos')
@section('page-title', '📖 Page "À propos"')

@push('styles')
<style>
    .about-editor-wrap { max-width: 760px; }

    .editor-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .editor-card-head {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 10px;
    }
    .editor-card-head-title {
        font-family: var(--font-head);
        font-size: .88rem; font-weight: 700;
    }
    .editor-card-head-sub {
        font-size: .75rem; color: var(--text-muted);
        margin-left: auto;
    }
    .editor-card-body { padding: 20px; display: flex; flex-direction: column; gap: 16px; }

    .field-group { display: flex; flex-direction: column; gap: 5px; }
    .field-label {
        font-size: .72rem; font-weight: 700; letter-spacing: .05em;
        text-transform: uppercase; color: var(--text-muted);
    }
    .field-label span { color: var(--cm); }
    .field-input, .field-textarea {
        background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 10px; padding: 9px 13px;
        color: var(--text); font-family: var(--font-body); font-size: .85rem;
        outline: none; transition: border-color .2s; width: 100%;
    }
    .field-input:focus, .field-textarea:focus { border-color: var(--cm); }
    .field-textarea { resize: vertical; min-height: 120px; line-height: 1.65; }
    .field-hint { font-size: .7rem; color: var(--text-faint); }

    .preview-notice {
        background: var(--cm-soft); border: 1px solid var(--cm-border);
        border-radius: 10px; padding: 10px 14px;
        font-size: .8rem; color: var(--cm);
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 20px;
    }

    .submit-bar {
        position: sticky; bottom: 0;
        background: var(--bg-card); border-top: 1px solid var(--border);
        padding: 14px 28px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        z-index: 10;
    }
    .submit-bar-note { font-size: .8rem; color: var(--text-muted); }
    .btn-save {
        background: var(--cm); color: #040E0C;
        border: none; padding: 10px 28px;
        border-radius: 100px; font-family: var(--font-head);
        font-size: .84rem; font-weight: 700;
        cursor: pointer; transition: opacity .2s;
    }
    .btn-save:hover { opacity: .85; }
    .btn-preview {
        background: transparent; border: 1px solid var(--border);
        color: var(--text-muted); padding: 10px 18px;
        border-radius: 100px; font-family: var(--font-body);
        font-size: .82rem; font-weight: 500;
        cursor: pointer; text-decoration: none;
        transition: all .2s;
    }
    .btn-preview:hover { border-color: var(--border-hover); color: var(--text); }
</style>
@endpush

@section('content')

<div class="about-editor-wrap">

    <div class="preview-notice">
        ℹ️ Les sections Mission, Histoire et Valeurs n'apparaissent que si elles sont renseignées. Laisser vide masque la section.
    </div>

    <form method="POST" action="{{ route('cm.about.update') }}">
        @csrf
        @method('PATCH')

        {{-- Titre & tagline --}}
        <div class="editor-card">
            <div class="editor-card-head">
                <span>🏷️</span>
                <span class="editor-card-head-title">En-tête</span>
            </div>
            <div class="editor-card-body">
                <div class="field-group">
                    <label class="field-label">Titre principal <span>(H1)</span></label>
                    <input class="field-input" type="text" name="about_title"
                           value="{{ $settings['about_title'] }}"
                           placeholder="La plateforme des créateurs africains"
                           maxlength="200">
                    <span class="field-hint">Laisse vide pour afficher le titre par défaut avec le mot coloré.</span>
                </div>
                <div class="field-group">
                    <label class="field-label">Sous-titre <span>(tagline)</span></label>
                    <textarea class="field-textarea" name="about_tagline" style="min-height:72px;" maxlength="400"
                              placeholder="Une courte phrase d'accroche sous le titre...">{{ $settings['about_tagline'] }}</textarea>
                </div>
            </div>
        </div>

        {{-- Mission --}}
        <div class="editor-card">
            <div class="editor-card-head">
                <span>🎯</span>
                <span class="editor-card-head-title">Notre mission</span>
                <span class="editor-card-head-sub">Section masquée si vide</span>
            </div>
            <div class="editor-card-body">
                <div class="field-group">
                    <label class="field-label">Texte</label>
                    <textarea class="field-textarea" name="about_mission" style="min-height:160px;" maxlength="2000"
                              placeholder="Décris la mission de MelanoGeek...">{{ $settings['about_mission'] }}</textarea>
                    <span class="field-hint">Supporte les sauts de ligne (Enter).</span>
                </div>
            </div>
        </div>

        {{-- Histoire --}}
        <div class="editor-card">
            <div class="editor-card-head">
                <span>📖</span>
                <span class="editor-card-head-title">Notre histoire</span>
                <span class="editor-card-head-sub">Section masquée si vide</span>
            </div>
            <div class="editor-card-body">
                <div class="field-group">
                    <label class="field-label">Texte</label>
                    <textarea class="field-textarea" name="about_story" style="min-height:160px;" maxlength="2000"
                              placeholder="Comment MelanoGeek a été créé...">{{ $settings['about_story'] }}</textarea>
                </div>
            </div>
        </div>

        {{-- Valeurs --}}
        <div class="editor-card">
            <div class="editor-card-head">
                <span>✨</span>
                <span class="editor-card-head-title">Nos valeurs</span>
                <span class="editor-card-head-sub">Section masquée si vide</span>
            </div>
            <div class="editor-card-body">
                <div class="field-group">
                    <label class="field-label">Texte</label>
                    <textarea class="field-textarea" name="about_values" style="min-height:160px;" maxlength="2000"
                              placeholder="Les valeurs fondamentales de la plateforme...">{{ $settings['about_values'] }}</textarea>
                </div>
            </div>
        </div>

        <div class="submit-bar">
            <span class="submit-bar-note">Modifications visibles immédiatement.</span>
            <div style="display:flex;gap:10px;align-items:center;">
                <a href="{{ route('about') }}" class="btn-preview" target="_blank">Prévisualiser →</a>
                <button type="submit" class="btn-save">💾 Enregistrer</button>
            </div>
        </div>

    </form>

</div>
@endsection
