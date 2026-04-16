@extends('layouts.blog')

@section('title', 'Nouveau sujet — Forum MelanoGeek')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
/* ── QUILL DARK THEME ── */
.ql-toolbar.ql-snow {
    border: none;
    border-bottom: 1px solid var(--border);
    background: var(--bg-card2);
    padding: 10px 16px;
    flex-wrap: wrap;
    gap: 4px;
    border-radius: 10px 10px 0 0;
}
.ql-toolbar.ql-snow .ql-formats { margin-right: 8px; }
.ql-toolbar.ql-snow button,
.ql-toolbar.ql-snow .ql-picker-label {
    color: var(--text-muted);
    border-radius: 6px;
    transition: background .15s, color .15s;
}
.ql-toolbar.ql-snow button:hover,
.ql-toolbar.ql-snow .ql-picker-label:hover { background: rgba(255,255,255,.06); color: var(--text); }
.ql-toolbar.ql-snow button.ql-active,
.ql-toolbar.ql-snow .ql-picker-label.ql-active { color: var(--terra); }
.ql-toolbar.ql-snow .ql-stroke { stroke: currentColor; }
.ql-toolbar.ql-snow .ql-fill  { fill:   currentColor; }
.ql-toolbar.ql-snow .ql-picker-options {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
}
.ql-container.ql-snow {
    border: none;
    font-family: var(--font-body);
    font-size: .95rem;
    line-height: 1.7;
}
.ql-editor {
    min-height: 220px;
    padding: 16px 20px;
    color: var(--text);
    caret-color: var(--terra);
}
.ql-editor.ql-blank::before {
    color: var(--text-faint);
    font-style: normal;
    left: 20px;
}
.ql-editor p { margin-bottom: 6px; }
.ql-editor h2 { font-family: var(--font-head); font-size: 1.2rem; font-weight: 700; margin: 12px 0 6px; }
.ql-editor h3 { font-family: var(--font-head); font-size: 1rem; font-weight: 600; margin: 10px 0 4px; }
.ql-editor blockquote {
    border-left: 3px solid var(--terra);
    padding-left: 14px;
    color: var(--text-muted);
    margin: 10px 0;
    font-style: italic;
}
.ql-editor code, .ql-editor pre {
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 6px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .85em;
    color: var(--gold);
}
.ql-editor pre { padding: 12px 16px; margin: 8px 0; white-space: pre-wrap; }
.ql-editor a { color: var(--terra); }
.ql-editor ul, .ql-editor ol { padding-left: 20px; }

/* Quill wrapper border */
.cf-quill-wrap {
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    transition: border-color .2s;
    background: var(--bg-card2);
}
.cf-quill-wrap:focus-within { border-color: var(--terra); }
</style>
<style>
/* ── WRAPPER ── */
.cf-wrap { max-width: 740px; }

/* ── PAGE HEADER ── */
.cf-page-header { margin-bottom: 32px; }
.cf-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .68rem; letter-spacing: .06em; text-transform: uppercase;
    color: var(--text-faint); text-decoration: none;
    transition: color .18s;
    margin-bottom: 18px;
}
.cf-back:hover { color: var(--text-muted); }
.cf-back svg { transition: transform .18s; }
.cf-back:hover svg { transform: translateX(-3px); }
.cf-page-title {
    font-family: var(--font-head);
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800;
    letter-spacing: -.03em;
    line-height: 1.1;
    color: var(--text);
    margin-bottom: 8px;
}
.cf-page-sub {
    font-size: .82rem;
    color: var(--text-muted);
    font-family: 'JetBrains Mono', monospace;
    letter-spacing: .02em;
}

/* ── ERROR ── */
.cf-error {
    background: rgba(224,85,85,.07);
    border: 1px solid rgba(224,85,85,.22);
    color: #f87171;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 24px;
    font-size: .85rem;
    display: flex; align-items: center; gap: 10px;
}

/* ── CARD ── */
.cf-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}

/* ── SECTIONS ── */
.cf-section {
    padding: 22px 26px;
    border-bottom: 1px solid var(--border);
}
.cf-section:last-child { border-bottom: none; }
.cf-label {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px;
}
.cf-label-text {
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--gold);
}
.cf-label-hint {
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem;
    color: var(--text-faint);
    letter-spacing: .04em;
}

/* ── CATEGORY PILLS ── */
.cf-cat-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.cf-cat-pill {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 14px;
    border-radius: 100px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-muted);
    font-family: var(--font-body);
    font-size: .82rem;
    font-weight: 500;
    cursor: pointer;
    transition: all .18s;
    user-select: none;
}
.cf-cat-pill:hover {
    border-color: var(--border-hover);
    color: var(--text);
    background: rgba(255,255,255,.03);
}
.cf-cat-pill.selected {
    border-color: var(--terra);
    background: var(--terra-soft);
    color: var(--text);
}
.cf-cat-pill .pill-icon { font-size: 1rem; line-height: 1; }

/* ── INPUTS ── */
.cf-input-wrap { position: relative; }
.cf-input {
    width: 100%;
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    font-family: var(--font-body);
    font-size: .95rem;
    padding: 12px 16px;
    outline: none;
    transition: border-color .2s, background .2s;
}
.cf-input:focus {
    border-color: var(--terra);
    background: rgba(255,255,255,.02);
}
.cf-input::placeholder { color: var(--text-faint); }

.cf-char-count {
    position: absolute;
    right: 14px; bottom: 12px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem;
    color: var(--text-faint);
    pointer-events: none;
    transition: color .2s;
}
.cf-char-count.warn { color: #f59e0b; }
.cf-char-count.over { color: #f87171; }

/* ── TEXTAREA ── */
.cf-textarea-wrap { position: relative; }
.cf-textarea {
    width: 100%;
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    font-family: var(--font-body);
    font-size: .92rem;
    line-height: 1.7;
    padding: 14px 16px;
    outline: none;
    resize: vertical;
    min-height: 220px;
    transition: border-color .2s, background .2s;
}
.cf-textarea:focus {
    border-color: var(--terra);
    background: rgba(255,255,255,.02);
}
.cf-textarea::placeholder { color: var(--text-faint); }
.cf-textarea-count {
    position: absolute;
    right: 14px; bottom: 12px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem;
    color: var(--text-faint);
    pointer-events: none;
    transition: color .2s;
}
.cf-textarea-count.warn { color: #f59e0b; }
.cf-textarea-count.over { color: #f87171; }

/* ── ACTIONS ── */
.cf-actions {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 26px;
    background: var(--bg-card2);
    border-top: 1px solid var(--border);
    gap: 12px;
}
.cf-actions-left {
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem;
    color: var(--text-faint);
    letter-spacing: .04em;
}
.cf-actions-right { display: flex; align-items: center; gap: 10px; }
.cf-btn-cancel {
    background: transparent; border: 1px solid var(--border);
    color: var(--text-muted); padding: 10px 20px;
    border-radius: 100px; font-family: var(--font-body);
    font-size: .86rem; cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 6px;
    transition: all .2s;
}
.cf-btn-cancel:hover { border-color: var(--border-hover); color: var(--text); }
.cf-btn-submit {
    background: var(--terra); border: none; color: white;
    padding: 10px 24px; border-radius: 100px;
    font-family: var(--font-head);
    font-size: .9rem; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
    transition: background .2s, transform .15s;
    letter-spacing: -.01em;
}
.cf-btn-submit:hover { background: var(--accent); transform: translateY(-1px); }
.cf-btn-submit:active { transform: translateY(0); }

/* ── TIPS (under form) ── */
.cf-tips {
    margin-top: 20px;
    padding: 16px 20px;
    border: 1px solid var(--border);
    border-radius: 12px;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.cf-tip {
    display: flex; align-items: flex-start; gap: 9px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .62rem;
    color: var(--text-faint);
    letter-spacing: .02em;
    line-height: 1.55;
    flex: 1; min-width: 160px;
}
.cf-tip-icon { font-size: .9rem; flex-shrink: 0; margin-top: 1px; }

/* ── RESPONSIVE ── */
@media (max-width: 600px) {
    .cf-section { padding: 18px 16px; }
    .cf-actions { padding: 14px 16px; flex-direction: column; align-items: stretch; }
    .cf-actions-left { display: none; }
    .cf-actions-right { flex-direction: row-reverse; }
    .cf-btn-cancel, .cf-btn-submit { flex: 1; justify-content: center; }
    .cf-tips { gap: 12px; }
    .cf-tip { min-width: 100%; }
}
</style>
@endpush

@section('main')
<div class="cf-wrap">

    {{-- ── PAGE HEADER ── --}}
    <div class="cf-page-header">
        <a href="{{ route('forum.index') }}" class="cf-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5"/><path d="M12 5l-7 7 7 7"/></svg>
            Retour au forum
        </a>
        <h1 class="cf-page-title">Nouveau sujet</h1>
        <p class="cf-page-sub">Partage une question, une découverte ou lance le débat</p>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="cf-error">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ $errors->first() }}
    </div>
    @endif

    {{-- ── FORM ── --}}
    <form method="POST" action="{{ route('forum.store') }}" id="cfForm">
        @csrf
        <div class="cf-card">

            {{-- Catégorie --}}
            <div class="cf-section">
                <div class="cf-label">
                    <span class="cf-label-text">Catégorie</span>
                    <span class="cf-label-hint">Choisir un thème</span>
                </div>
                <input type="hidden" name="category" id="cfCategoryInput" value="{{ old('category') }}" required>
                <div class="cf-cat-grid" id="cfCatGrid">
                    @foreach($categories as $slug => $info)
                    <button type="button" class="cf-cat-pill {{ old('category') === $slug ? 'selected' : '' }}"
                            data-slug="{{ $slug }}">
                        <span class="pill-icon">{{ $info['icon'] }}</span>
                        {{ $info['label'] }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Titre --}}
            <div class="cf-section">
                <div class="cf-label">
                    <span class="cf-label-text">Titre du sujet</span>
                    <span class="cf-label-hint" id="cfTitleHint">0 / 200</span>
                </div>
                <div class="cf-input-wrap">
                    <input type="text" name="title" id="cfTitle" class="cf-input"
                           placeholder="Un titre clair et accrocheur…"
                           value="{{ old('title') }}" maxlength="200" required autocomplete="off">
                </div>
            </div>

            {{-- Contenu --}}
            <div class="cf-section">
                <div class="cf-label">
                    <span class="cf-label-text">Contenu</span>
                    <span class="cf-label-hint" id="cfBodyHint">0 / 10 000 mots</span>
                </div>
                <input type="hidden" name="body" id="cfBodyInput">
                <div class="cf-quill-wrap">
                    <div id="cfQuill"></div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="cf-actions">
                <span class="cf-actions-left">Les règles du forum s'appliquent · Sois respectueux</span>
                <div class="cf-actions-right">
                    <a href="{{ route('forum.index') }}" class="cf-btn-cancel">Annuler</a>
                    <button type="submit" class="cf-btn-submit" id="cfSubmit">
                        Publier le sujet
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

        </div>
    </form>

    {{-- ── TIPS ── --}}
    <div class="cf-tips">
        <div class="cf-tip">
            <span class="cf-tip-icon">✦</span>
            <span>Un bon titre résume ton sujet en une phrase et donne envie de cliquer.</span>
        </div>
        <div class="cf-tip">
            <span class="cf-tip-icon">💬</span>
            <span>Plus tu es précis dans ton contenu, plus tu obtiendras des réponses utiles.</span>
        </div>
        <div class="cf-tip">
            <span class="cf-tip-icon">🌍</span>
            <span>Reste respectueux et constructif — c'est ici qu'on construit la communauté.</span>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
(function () {

    /* ── Category pills ── */
    const hiddenInput = document.getElementById('cfCategoryInput');
    const pills = document.querySelectorAll('#cfCatGrid .cf-cat-pill');

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.classList.remove('selected'));
            pill.classList.add('selected');
            hiddenInput.value = pill.dataset.slug;
        });
    });

    /* ── Title counter ── */
    const titleEl   = document.getElementById('cfTitle');
    const titleHint = document.getElementById('cfTitleHint');
    const MAX_TITLE = 200;
    function updateTitle() {
        const n = titleEl.value.length;
        titleHint.textContent = n + ' / ' + MAX_TITLE;
        titleHint.className = 'cf-label-hint' + (n >= MAX_TITLE ? ' over' : n >= MAX_TITLE * .85 ? ' warn' : '');
    }
    titleEl.addEventListener('input', updateTitle);
    updateTitle();

    /* ── Quill editor ── */
    const quill = new Quill('#cfQuill', {
        theme: 'snow',
        placeholder: 'Développe ton sujet, pose ta question ou lance le débat…',
        modules: {
            toolbar: [
                [{ header: [2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    /* Pré-remplir si old() */
    @if(old('body'))
    quill.root.innerHTML = {!! json_encode(old('body')) !!};
    @endif

    /* Compteur de mots ── */
    const bodyHint  = document.getElementById('cfBodyHint');
    const bodyInput = document.getElementById('cfBodyInput');
    const MAX_WORDS = 10000;

    function countWords(text) {
        return text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
    }
    function updateBody() {
        const text = quill.getText();
        const n    = countWords(text);
        bodyHint.textContent = n.toLocaleString('fr') + ' / ' + MAX_WORDS.toLocaleString('fr') + ' mots';
        bodyHint.className   = 'cf-label-hint' + (n >= MAX_WORDS ? ' over' : n >= MAX_WORDS * .85 ? ' warn' : '');
        bodyInput.value      = quill.root.innerHTML;
    }
    quill.on('text-change', updateBody);
    updateBody();

    /* ── Submit guard ── */
    document.getElementById('cfForm').addEventListener('submit', function (e) {
        /* Sync Quill → hidden input */
        bodyInput.value = quill.root.innerHTML;

        /* Check catégorie */
        if (!hiddenInput.value) {
            e.preventDefault();
            const grid = document.getElementById('cfCatGrid');
            grid.style.animation = 'none';
            grid.offsetHeight;
            grid.style.animation = 'cfShake .3s ease';
            grid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        /* Check contenu non vide */
        if (quill.getText().trim().length < 10) {
            e.preventDefault();
            document.querySelector('.cf-quill-wrap').style.borderColor = 'var(--terra)';
            quill.focus();
        }
    });

})();
</script>
<style>
@keyframes cfShake {
    0%,100%{transform:translateX(0)}
    20%{transform:translateX(-6px)}
    40%{transform:translateX(6px)}
    60%{transform:translateX(-4px)}
    80%{transform:translateX(4px)}
}
</style>
@endpush
