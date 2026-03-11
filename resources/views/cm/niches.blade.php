@extends('cm.layout')

@section('title', 'Niches')
@section('page-title', '🏷️ Niches créateurs')

@push('styles')
<style>
    .niches-wrap { max-width: 640px; }

    .editor-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden; margin-bottom: 20px;
    }
    .editor-card-head {
        padding: 14px 20px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 10px;
    }
    .editor-card-head-title { font-family: var(--font-head); font-size: .88rem; font-weight: 700; }
    .editor-card-body { padding: 20px; display: flex; flex-direction: column; gap: 14px; }

    .field-label { font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted); }
    .field-label span { color: var(--cm); }
    .field-textarea {
        background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 10px; padding: 12px 14px;
        color: var(--text); font-family: 'Courier New', monospace; font-size: .88rem;
        outline: none; transition: border-color .2s; width: 100%;
        resize: vertical; min-height: 380px; line-height: 1.9;
    }
    .field-textarea:focus { border-color: var(--cm); }
    .field-hint { font-size: .72rem; color: var(--text-faint); line-height: 1.6; }

    .preview-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 8px;
    }
    .preview-chip {
        background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 8px; padding: 8px 10px;
        display: flex; align-items: center; gap: 7px;
        font-size: .78rem; font-weight: 500; color: var(--text-muted);
    }

    .info-box {
        background: var(--cm-soft); border: 1px solid var(--cm-border);
        border-radius: 10px; padding: 12px 16px;
        font-size: .8rem; color: var(--cm); line-height: 1.6;
    }

    .error-box {
        background: rgba(224,85,85,.08); border: 1px solid rgba(224,85,85,.25);
        border-radius: 10px; padding: 10px 14px;
        font-size: .82rem; color: #E05555;
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
        background: var(--cm); color: #040E0C; border: none;
        padding: 10px 28px; border-radius: 100px;
        font-family: var(--font-head); font-size: .84rem; font-weight: 700;
        cursor: pointer; transition: opacity .2s;
    }
    .btn-save:hover { opacity: .85; }
</style>
@endpush

@section('content')
<div class="niches-wrap">

    @if($errors->any())
        <div class="error-box" style="margin-bottom:16px;">✗ {{ $errors->first() }}</div>
    @endif

    <div class="info-box" style="margin-bottom:20px;">
        ℹ️ Ces niches s'appliquent partout : <strong>ticker de la page d'accueil</strong>, <strong>grille de sélection du profil</strong> et <strong>formulaire d'inscription</strong>.<br>
        Format : <code style="background:rgba(45,184,160,.15);padding:1px 6px;border-radius:4px;">emoji Nom de la niche</code> — une niche par ligne.
    </div>

    <form method="POST" action="{{ route('cm.niches.update') }}">
        @csrf
        @method('PATCH')

        <div class="editor-card">
            <div class="editor-card-head">
                <span>✏️</span>
                <span class="editor-card-head-title">Liste des niches</span>
                <span style="margin-left:auto;font-size:.75rem;color:var(--text-muted);" id="nicheCount">…</span>
            </div>
            <div class="editor-card-body">
                <div>
                    <label class="field-label" style="display:block;margin-bottom:8px;">Une niche par ligne <span>· emoji espace Nom</span></label>
                    <textarea class="field-textarea" name="niches_text" id="nichesTextarea" spellcheck="false">{{ $nichesText }}</textarea>
                    <p class="field-hint" style="margin-top:6px;">
                        Ex : <code>🎵 Musique</code> · <code>📸 Photographie</code> · <code>👗 Mode & Style</code><br>
                        Lignes vides ignorées automatiquement.
                    </p>
                </div>
            </div>
        </div>

        {{-- Aperçu live --}}
        <div class="editor-card">
            <div class="editor-card-head">
                <span>👁️</span>
                <span class="editor-card-head-title">Aperçu</span>
            </div>
            <div class="editor-card-body">
                <div class="preview-grid" id="previewGrid">
                    {{-- rempli par JS --}}
                </div>
            </div>
        </div>

        <div class="submit-bar">
            <span class="submit-bar-note">Modifications visibles immédiatement sur tout le site.</span>
            <button type="submit" class="btn-save">💾 Enregistrer</button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const ta      = document.getElementById('nichesTextarea');
    const grid    = document.getElementById('previewGrid');
    const counter = document.getElementById('nicheCount');

    function refresh() {
        const lines = ta.value.split('\n').map(l => l.trim()).filter(Boolean);
        counter.textContent = lines.length + ' niche' + (lines.length > 1 ? 's' : '');
        grid.innerHTML = lines.map(line => {
            const parts = line.match(/^(\S+)\s+(.+)$/);
            if (!parts) return '';
            return `<div class="preview-chip"><span>${parts[1]}</span><span>${parts[2]}</span></div>`;
        }).join('');
    }

    ta.addEventListener('input', refresh);
    refresh();
})();
</script>
@endpush
