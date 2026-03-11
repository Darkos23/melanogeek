@extends('cm.layout')

@section('title', 'Page d\'accueil')
@section('page-title', '🏠 Page d\'accueil')

@push('styles')
<style>
    .editor-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 900px) { .editor-grid { grid-template-columns: 1fr; } }

    .editor-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }
    .editor-card-head {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 10px;
    }
    .editor-card-head-icon { font-size: 1.1rem; }
    .editor-card-head-title {
        font-family: var(--font-head);
        font-size: .88rem; font-weight: 700;
    }
    .editor-card-body { padding: 18px 20px; display: flex; flex-direction: column; gap: 14px; }

    .field-group { display: flex; flex-direction: column; gap: 5px; }
    .field-label {
        font-size: .72rem; font-weight: 700; letter-spacing: .05em;
        text-transform: uppercase; color: var(--text-muted);
    }
    .field-label span { color: var(--cm); font-weight: 700; }
    .field-input, .field-textarea {
        background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 10px; padding: 9px 13px;
        color: var(--text); font-family: var(--font-body); font-size: .85rem;
        outline: none; transition: border-color .2s; width: 100%;
    }
    .field-input:focus, .field-textarea:focus { border-color: var(--cm); }
    .field-textarea { resize: vertical; min-height: 72px; line-height: 1.55; }
    .field-hint { font-size: .7rem; color: var(--text-faint); }

    .val-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 640px) { .val-grid { grid-template-columns: 1fr; } }

    .val-card {
        background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 12px; padding: 14px; display: flex; flex-direction: column; gap: 10px;
    }
    .val-card-num {
        font-size: .7rem; font-weight: 700; letter-spacing: .06em;
        text-transform: uppercase; color: var(--cm);
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
    .btn-reset {
        background: transparent; border: 1px solid var(--border);
        color: var(--text-muted); padding: 10px 18px;
        border-radius: 100px; font-family: var(--font-body);
        font-size: .82rem; font-weight: 500;
        cursor: pointer; text-decoration: none;
        transition: all .2s;
    }
    .btn-reset:hover { border-color: var(--border-hover); color: var(--text); }

    .section-span { grid-column: 1 / -1; }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('cm.homepage.update') }}">
    @csrf
    @method('PATCH')

    <div class="editor-grid">

        {{-- ── HERO ── --}}
        <div class="editor-card">
            <div class="editor-card-head">
                <span class="editor-card-head-icon">🦸</span>
                <span class="editor-card-head-title">Section Hero</span>
            </div>
            <div class="editor-card-body">

                <div class="field-group">
                    <label class="field-label">Pill d'intro <span>(badge rouge)</span></label>
                    <input class="field-input" type="text" name="home_hero_pill" value="{{ $settings['home_hero_pill'] }}" maxlength="80">
                </div>

                <div class="field-group">
                    <label class="field-label">Titre H1 — <span>ligne 1</span></label>
                    <input class="field-input" type="text" name="home_hero_h1_1" value="{{ $settings['home_hero_h1_1'] }}" maxlength="40">
                </div>
                <div class="field-group">
                    <label class="field-label">Titre H1 — <span>ligne 2 (italique couleur)</span></label>
                    <input class="field-input" type="text" name="home_hero_h1_2" value="{{ $settings['home_hero_h1_2'] }}" maxlength="40">
                </div>
                <div class="field-group">
                    <label class="field-label">Titre H1 — <span>ligne 3</span></label>
                    <input class="field-input" type="text" name="home_hero_h1_3" value="{{ $settings['home_hero_h1_3'] }}" maxlength="40">
                </div>
                <div class="field-group">
                    <label class="field-label">Titre H1 — <span>ligne 4 (outline)</span></label>
                    <input class="field-input" type="text" name="home_hero_h1_4" value="{{ $settings['home_hero_h1_4'] }}" maxlength="40">
                </div>

                <div class="field-group">
                    <label class="field-label">Description <span>(paragraphe)</span></label>
                    <textarea class="field-textarea" name="home_hero_desc" maxlength="400">{{ $settings['home_hero_desc'] }}</textarea>
                </div>

            </div>
        </div>

        {{-- ── MANIFESTE ── --}}
        <div class="editor-card">
            <div class="editor-card-head">
                <span class="editor-card-head-icon">✊</span>
                <span class="editor-card-head-title">Section Manifeste</span>
            </div>
            <div class="editor-card-body">

                <div class="field-group">
                    <label class="field-label">Label section <span>(petit texte au-dessus)</span></label>
                    <input class="field-input" type="text" name="home_manifeste_eye" value="{{ $settings['home_manifeste_eye'] }}" maxlength="60">
                </div>

                <div class="field-group">
                    <label class="field-label">Citation — <span>texte principal</span></label>
                    <textarea class="field-textarea" name="home_manifeste_quote" maxlength="200">{{ $settings['home_manifeste_quote'] }}</textarea>
                    <span class="field-hint">Le dernier mot mis en valeur est défini séparément ci-dessous.</span>
                </div>

                <div class="field-group">
                    <label class="field-label">Citation — <span>mot final coloré</span></label>
                    <input class="field-input" type="text" name="home_manifeste_quote_end" value="{{ $settings['home_manifeste_quote_end'] }}" maxlength="40">
                </div>

                <div class="field-group">
                    <label class="field-label">Sous-description</label>
                    <textarea class="field-textarea" name="home_manifeste_sub" style="min-height:96px;" maxlength="500">{{ $settings['home_manifeste_sub'] }}</textarea>
                </div>

            </div>
        </div>

        {{-- ── VALEURS ── --}}
        <div class="editor-card section-span">
            <div class="editor-card-head">
                <span class="editor-card-head-icon">🏅</span>
                <span class="editor-card-head-title">4 valeurs (section Manifeste)</span>
            </div>
            <div class="editor-card-body">
                <div class="val-grid">
                    @foreach([1,2,3,4] as $n)
                    <div class="val-card">
                        <div class="val-card-num">Valeur {{ $n }}</div>
                        <div class="field-group">
                            <label class="field-label">Icône <span>(emoji)</span></label>
                            <input class="field-input" type="text" name="home_val{{ $n }}_icon" value="{{ $settings['home_val'.$n.'_icon'] }}" maxlength="8" style="max-width:80px;">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Titre</label>
                            <input class="field-input" type="text" name="home_val{{ $n }}_title" value="{{ $settings['home_val'.$n.'_title'] }}" maxlength="60">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Description</label>
                            <textarea class="field-textarea" name="home_val{{ $n }}_desc" maxlength="200">{{ $settings['home_val'.$n.'_desc'] }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── CTA ── --}}
        <div class="editor-card section-span">
            <div class="editor-card-head">
                <span class="editor-card-head-icon">🚀</span>
                <span class="editor-card-head-title">Section CTA final <span style="font-weight:400;color:var(--text-muted);">(visible visiteurs non connectés)</span></span>
            </div>
            <div class="editor-card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

                <div class="field-group">
                    <label class="field-label">Label <span>(petit texte au-dessus)</span></label>
                    <input class="field-input" type="text" name="home_cta_eye" value="{{ $settings['home_cta_eye'] }}" maxlength="60">
                </div>

                <div class="field-group">
                    <label class="field-label">Sous-paragraphe</label>
                    <textarea class="field-textarea" name="home_cta_sub" maxlength="300">{{ $settings['home_cta_sub'] }}</textarea>
                </div>

                <div style="grid-column:1/-1;">
                    <p class="field-hint" style="margin-bottom:10px;">Le titre H2 est en 3 parties : la 1ère et 3ème lignes sont normales, la 2ème est mise en couleur.</p>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                        <div class="field-group">
                            <label class="field-label">H2 — <span>ligne 1</span></label>
                            <input class="field-input" type="text" name="home_cta_h2_1" value="{{ $settings['home_cta_h2_1'] }}" maxlength="40">
                        </div>
                        <div class="field-group">
                            <label class="field-label">H2 — <span>mot coloré</span></label>
                            <input class="field-input" type="text" name="home_cta_h2_2" value="{{ $settings['home_cta_h2_2'] }}" maxlength="40">
                        </div>
                        <div class="field-group">
                            <label class="field-label">H2 — <span>ligne 3</span></label>
                            <input class="field-input" type="text" name="home_cta_h2_3" value="{{ $settings['home_cta_h2_3'] }}" maxlength="40">
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- /editor-grid -->

    <div class="submit-bar">
        <span class="submit-bar-note">Les modifications sont visibles immédiatement sur le site.</span>
        <div style="display:flex;gap:10px;align-items:center;">
            <a href="{{ route('home') }}" class="btn-reset" target="_blank">Prévisualiser →</a>
            <button type="submit" class="btn-save">💾 Enregistrer</button>
        </div>
    </div>

</form>
@endsection
