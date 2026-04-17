@extends('layouts.app')

@section('title', 'Nouvelle publication')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    /* ── QUILL DARK THEME OVERRIDE ── */
    .ql-toolbar.ql-snow {
        border: none;
        border-top: 1px solid var(--border);
        background: var(--bg-card2);
        padding: 10px 16px;
        flex-wrap: wrap !important;
        overflow-x: hidden !important;
        gap: 4px;
    }
    .ql-editor { overflow-x: hidden !important; word-break: break-word; }
    .ql-container { max-width: 100%; }
    .create-page { overflow-x: hidden; }
    .create-wrap { overflow-x: hidden; }
    .ql-toolbar.ql-snow .ql-formats { margin-right: 8px; }
    .ql-toolbar.ql-snow button,
    .ql-toolbar.ql-snow .ql-picker-label {
        color: var(--text-muted);
        border-radius: 6px;
        transition: background .15s, color .15s;
    }
    .ql-toolbar.ql-snow button:hover,
    .ql-toolbar.ql-snow .ql-picker-label:hover { background: var(--bg-card); color: var(--text); }
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
        font-size: 1rem;
        line-height: 1.7;
    }
    .ql-editor {
        min-height: 180px;
        padding: 14px 24px;
        color: var(--text);
        caret-color: var(--terra);
    }
    .ql-editor.ql-blank::before {
        color: var(--text-faint);
        font-style: normal;
        left: 24px;
    }
    .ql-editor h2 { font-family: var(--font-head); font-size: 1.3rem; font-weight: 700; margin: 12px 0 6px; }
    .ql-editor h3 { font-family: var(--font-head); font-size: 1.1rem; font-weight: 600; margin: 10px 0 4px; }
    .ql-editor blockquote {
        border-left: 3px solid var(--terra);
        padding-left: 14px;
        color: var(--text-muted);
        margin: 10px 0;
        font-style: italic;
    }
    .ql-editor pre.ql-syntax {
        background: var(--bg-card2);
        border-radius: 8px;
        padding: 12px 14px;
        font-size: .88rem;
        color: var(--text);
        border: 1px solid var(--border);
    }
    .ql-editor a { color: var(--terra); }
    .ql-editor ul, .ql-editor ol { padding-left: 20px; }

    /* ══════════════════════════════════════ */

    .create-page { padding-top: calc(80px + env(safe-area-inset-top)); min-height: 100vh; }

    .create-wrap {
        max-width: 680px;
        margin: 0 auto;
        padding: 32px 24px 60px;
    }

    /* ── HEADER ── */
    .create-header {
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 28px;
    }
    .create-back {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: var(--bg-card);
        border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; color: var(--text-muted);
        font-size: .95rem; flex-shrink: 0;
        transition: all .2s; cursor: pointer;
    }
    .create-back:hover { border-color: var(--border-hover); color: var(--text); }
    .create-title {
        font-family: var(--font-head);
        font-size: 1.4rem; font-weight: 800;
        letter-spacing: -.02em;
    }

    /* ── CARD ── */
    .create-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
    }

    /* ── AUTHOR ROW ── */
    .author-row {
        display: flex; align-items: center; gap: 12px;
        padding: 20px 24px 0;
    }
    .author-avatar {
        width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px; overflow: hidden;
    }
    .author-avatar-inner {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; font-weight: 700; overflow: hidden;
    }
    .author-avatar-inner img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .author-name { font-family: var(--font-head); font-size: .9rem; font-weight: 700; }
    .author-handle { font-size: .78rem; color: var(--text-muted); }

    /* ── TEXTAREA & TITLE ── */
    /* Cover image zone */
    .cover-upload-zone {
        position: relative;
        margin: 0 0 4px;
        aspect-ratio: 16/5;
        border-radius: 10px;
        border: 1.5px dashed var(--border-hover);
        background: var(--bg);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        overflow: hidden;
        transition: border-color .2s, background .2s;
    }
    .cover-upload-zone:hover { border-color: var(--terra); background: rgba(200,82,42,.04); }
    .cover-upload-zone.has-cover { border-style: solid; border-color: transparent; }
    .cover-placeholder {
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        color: var(--text-muted); font-size: .72rem; font-family: var(--font-body);
        pointer-events: none;
    }
    .cover-remove-btn {
        position: absolute; top: 8px; right: 8px;
        width: 26px; height: 26px;
        background: rgba(0,0,0,.6); color: #fff;
        border: none; border-radius: 50%;
        font-size: .72rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        z-index: 2;
        transition: background .2s;
    }
    .cover-remove-btn:hover { background: rgba(200,50,50,.8); }

    .post-title-input {
        width: 100%;
        background: transparent; border: none; outline: none;
        font-family: var(--font-head);
        font-size: 1.25rem; font-weight: 700;
        color: var(--text);
        padding: 20px 24px 0;
        resize: none; line-height: 1.4;
    }
    .post-title-input::placeholder { color: var(--text-faint); }

    .post-body-input {
        width: 100%;
        background: transparent; border: none; outline: none;
        font-family: var(--font-body);
        font-size: 1rem; line-height: 1.7;
        color: var(--text);
        padding: 14px 24px;
        resize: none; min-height: 160px;
    }
    .post-body-input::placeholder { color: var(--text-faint); }

    .post-meta-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 24px 16px;
    }
    .post-meta-label {
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--text-faint);
        flex-shrink: 0;
    }
    .post-category-select {
        width: 100%;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 12px;
        color: var(--text);
        font-family: var(--font-body);
        font-size: .9rem;
        padding: 12px 14px;
        outline: none;
        transition: border-color .2s, background .2s;
    }
    .post-category-select:focus {
        border-color: var(--terra);
        background: rgba(255,255,255,.03);
    }
    .post-category-select option {
        background: var(--bg-card);
        color: var(--text);
    }

    /* ── MEDIA PREVIEW ── */
    .media-preview-wrap {
        margin: 0 24px 16px;
        border-radius: 14px;
        overflow: hidden;
        position: relative;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        display: none;
    }
    .media-preview-wrap.visible { display: block; }
    .media-preview-wrap video {
        width: 100%; max-height: 400px;
        object-fit: cover; display: block;
    }
    .media-preview-remove {
        position: absolute; top: 10px; right: 10px;
        width: 30px; height: 30px; border-radius: 50%;
        background: rgba(0,0,0,0.6);
        border: 1px solid rgba(255,255,255,0.15);
        color: white; font-size: .8rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .2s;
    }
    .media-preview-remove:hover { background: rgba(224,85,85,0.7); }

    /* ── MULTI-IMAGE GRID ── */
    .img-grid-wrap {
        margin: 0 24px 16px;
        display: none;
    }
    .img-grid-wrap.visible { display: block; }

    /* Grid layouts (1–4+ items) */
    .img-grid {
        display: grid;
        gap: 4px;
        border-radius: 14px;
        overflow: hidden;
        max-height: 420px;
    }
    .img-grid.count-1 { grid-template-columns: 1fr; }
    .img-grid.count-2 { grid-template-columns: 1fr 1fr; }
    .img-grid.count-3 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
    .img-grid.count-3 .img-thumb:first-child { grid-row: 1 / 3; }
    .img-grid.count-4 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
    .img-grid.count-many { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }

    .img-thumb {
        position: relative;
        overflow: hidden;
        background: var(--bg-card2);
        aspect-ratio: 1;
        cursor: pointer;
    }
    .img-grid.count-1 .img-thumb { aspect-ratio: 4/3; max-height: 420px; }
    .img-thumb img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
    }
    .img-thumb-remove {
        position: absolute; top: 6px; right: 6px;
        width: 26px; height: 26px; border-radius: 50%;
        background: rgba(0,0,0,.65);
        border: 1px solid rgba(255,255,255,.2);
        color: white; font-size: .7rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .2s; z-index: 2;
    }
    .img-thumb-remove:hover { background: rgba(224,85,85,.8); }
    .img-thumb-more {
        position: absolute; inset: 0;
        background: rgba(0,0,0,.55);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; font-weight: 800; color: white;
        pointer-events: none;
    }

    /* ── AUDIO PREVIEW ── */
    .audio-preview-wrap {
        margin: 0 24px 16px;
        display: none;
        align-items: center; gap: 12px;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 10px 14px;
    }
    .audio-preview-wrap.visible { display: flex; }
    .audio-preview-icon { font-size: 1.3rem; flex-shrink: 0; }
    .audio-preview-name {
        flex: 1; font-size: .83rem; font-weight: 600;
        color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .audio-preview-remove {
        width: 26px; height: 26px; border-radius: 50%;
        background: var(--bg-card); border: 1px solid var(--border);
        color: var(--text-muted); font-size: .7rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .2s; flex-shrink: 0;
    }
    .audio-preview-remove:hover { border-color: #E05555; color: #E05555; }

    /* ── EMOJI PICKER ── */
    .emoji-picker-wrap { position: relative; }
    .emoji-panel {
        position: absolute;
        bottom: calc(100% + 10px);
        left: 0;
        width: 288px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,.22);
        padding: 10px;
        display: none; z-index: 200;
    }
    .emoji-panel.open { display: block; }
    .emoji-grid { display: flex; flex-wrap: wrap; gap: 2px; }
    .emoji-item {
        width: 34px; height: 34px;
        border: none; background: transparent;
        border-radius: 8px; font-size: 1.15rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .15s; flex-shrink: 0;
        line-height: 1;
    }
    .emoji-item:hover { background: var(--bg-card2); }

    /* Add more button */
    .img-add-more {
        margin-top: 8px;
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .78rem; color: var(--text-muted);
        background: var(--bg-card2); border: 1px dashed var(--border);
        border-radius: 100px; padding: 5px 14px; cursor: pointer;
        transition: border-color .2s, color .2s;
    }
    .img-add-more:hover { border-color: var(--terra); color: var(--terra); }

    /* ── TOOLBAR ── */
    .post-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        gap: 8px; flex-wrap: wrap;
    }
    .toolbar-left { display: flex; gap: 4px; align-items: center; }
    .toolbar-btn {
        width: 36px; height: 36px; border-radius: 10px;
        background: transparent; border: 1px solid transparent;
        color: var(--text-muted); font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .2s;
    }
    .toolbar-btn:hover { background: var(--bg-card2); border-color: var(--border); color: var(--text); }
    .toolbar-btn.active { background: var(--terra-soft); border-color: rgba(200,82,42,.3); color: var(--terra); }

    /* Compteur de caractères */
    .char-counter {
        font-size: .75rem; color: var(--text-faint);
        font-variant-numeric: tabular-nums;
        transition: color .2s;
    }
    .char-counter.warn { color: var(--gold); }
    .char-counter.over { color: #E05555; }

    /* ── VISIBILITY ── */
    .visibility-row {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 24px;
        border-top: 1px solid var(--border);
    }
    .visibility-label { font-size: .8rem; color: var(--text-muted); flex: 1; }
    .toggle-switch { position: relative; display: inline-block; width: 42px; height: 24px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute; cursor: pointer; inset: 0;
        background: var(--bg-card2); border: 1px solid var(--border);
        border-radius: 100px; transition: background .2s;
    }
    .toggle-slider::before {
        content: ''; position: absolute;
        width: 16px; height: 16px; border-radius: 50%;
        background: var(--text-faint);
        left: 3px; top: 50%; transform: translateY(-50%);
        transition: transform .2s, background .2s;
    }
    .toggle-switch input:checked + .toggle-slider { background: var(--terra-soft); border-color: rgba(200,82,42,.4); }
    .toggle-switch input:checked + .toggle-slider::before { transform: translate(18px, -50%); background: var(--terra); }
    .visibility-text { font-size: .8rem; font-weight: 600; color: var(--text); min-width: 60px; text-align: right; }

    /* ── SUBMIT AREA ── */
    .submit-row {
        display: flex; align-items: center; justify-content: flex-end;
        gap: 10px; padding: 16px 20px;
        border-top: 1px solid var(--border);
    }
    .btn-draft {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-muted);
        padding: 10px 20px; border-radius: 100px;
        font-family: var(--font-body); font-size: .86rem; font-weight: 500;
        cursor: pointer; transition: all .2s;
    }
    .btn-draft:hover { border-color: var(--border-hover); color: var(--text); }
    .btn-publish {
        background: var(--terra); border: none; color: white;
        padding: 10px 24px; border-radius: 100px;
        font-family: var(--font-head); font-size: .9rem; font-weight: 700;
        cursor: pointer; transition: background .2s, transform .15s, box-shadow .2s;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-publish:hover {
        background: var(--accent); transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(200,82,42,.3);
    }
    .btn-publish:disabled { opacity: .5; transform: none; box-shadow: none; cursor: not-allowed; }

    /* ── TIPS ── */
    .create-tips {
        margin-top: 20px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 18px 20px;
    }
    .tips-title { font-size: .75rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 12px; }
    .tip-item { display: flex; align-items: flex-start; gap: 10px; font-size: .8rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 8px; }
    .tip-item:last-child { margin-bottom: 0; }
    .tip-icon { flex-shrink: 0; margin-top: 1px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 640px) {
        .create-page { padding-top: 70px; }
        .create-wrap { padding: 20px 12px 40px; }
        .create-title { font-size: 1.15rem; }

        .author-row { padding: 16px 16px 0; }

        .post-title-input { padding: 14px 16px 0; font-size: 1.1rem; }
        .post-body-input  { padding: 12px 16px; font-size: .95rem; min-height: 120px; }
        .post-meta-row { padding: 0 16px 12px; flex-direction: column; align-items: stretch; gap: 8px; }

        .media-preview-wrap { margin: 0 12px 12px; }
        .img-grid-wrap      { margin: 0 12px 12px; }
        .audio-preview-wrap { margin: 0 12px 12px; }

        .post-toolbar { padding: 10px 12px; gap: 6px; }

        .visibility-row { padding: 10px 16px; gap: 8px; }
        .visibility-label { font-size: .75rem; }

        .submit-row { padding: 12px 16px; }
        .btn-draft   { flex: 1; text-align: center; padding: 11px 12px; }
        .btn-publish { flex: 1; justify-content: center; padding: 11px 12px; }

        .emoji-panel { width: 256px; }
        .emoji-item  { width: 30px; height: 30px; font-size: 1rem; }

        .create-tips { margin-top: 14px; padding: 14px 16px; }
    }
</style>
@endpush

@section('content')
<div class="create-page">
<div class="create-wrap">

    <div class="create-header">
        <a href="{{ route('profile.show', auth()->user()->username) }}" class="create-back">←</a>
        <div class="create-title">Nouvelle publication</div>
    </div>

    @if($errors->any())
        <div class="flash error" style="margin-bottom:16px;">
            ✗ {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" id="postForm">
        @csrf
        @php
            $postCategories = \App\Models\Post::CATEGORIES;
        @endphp

        <div class="create-card">

            <!-- Auteur -->
            <div class="author-row">
                <div class="author-avatar">
                    <div class="author-avatar-inner">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="">
                        @else
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div>
                    <div class="author-name">{{ auth()->user()->name }}</div>
                    <div class="author-handle">&#64;{{ auth()->user()->username }}</div>
                </div>
            </div>

            <!-- Image de couverture -->
            <div class="cover-upload-zone" id="coverZone" onclick="document.getElementById('coverInput').click()">
                <img id="coverPreview" src="" alt="" style="display:none;position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:10px;">
                <div class="cover-placeholder" id="coverPlaceholder">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.4"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    <span>Ajouter une image de couverture</span>
                </div>
                <button type="button" class="cover-remove-btn" id="coverRemoveBtn" style="display:none;" onclick="removeCover(event)">✕</button>
                <input type="file" id="coverInput" name="thumbnail" accept="image/jpeg,image/png,image/webp" style="display:none">
            </div>

            <!-- Titre (optionnel) -->
            <textarea
                class="post-title-input"
                name="title"
                placeholder="Titre (optionnel)"
                rows="1"
                maxlength="150"
                oninput="autoResize(this)"
            >{{ old('title') }}</textarea>

            <!-- Corps (Quill editor) -->
            <div id="quillEditor" style="border:none;"></div>
            <input type="hidden" name="body" id="postBody" value="{{ old('body') }}">

            <div class="post-meta-row">
                <label for="postCategory" class="post-meta-label">Catégorie</label>
                <select name="category" id="postCategory" class="post-category-select">
                    <option value="">Choisir une catégorie</option>
                    @foreach($postCategories as $slug => $label)
                        <option value="{{ $slug }}" @selected(old('category') === $slug)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Multi-image grid -->
            <div class="img-grid-wrap" id="imgGridWrap">
                <div class="img-grid" id="imgGrid"></div>
                <label for="imgMoreInput" class="img-add-more" id="imgAddMore" style="display:none;">
                    + Ajouter des photos
                </label>
            </div>

            <!-- Aperçu vidéo -->
            <div class="media-preview-wrap" id="mediaPreview"></div>

            <!-- Aperçu audio -->
            <div class="audio-preview-wrap" id="audioPreview">
                <div class="audio-preview-icon">🎵</div>
                <div class="audio-preview-name" id="audioPreviewName">—</div>
                <button type="button" class="audio-preview-remove" onclick="removeAudio()">✕</button>
            </div>

            <!-- Toolbar -->
            <div class="post-toolbar">
                <div class="toolbar-left">
                    <label for="imgPickerInput" class="toolbar-btn" title="Ajouter des photos" id="imgPickerBtn">
                        📷
                    </label>
                    <label for="videoInput" class="toolbar-btn" title="Ajouter une vidéo">
                        🎬
                    </label>
                    <label for="audioInput" class="toolbar-btn" title="Ajouter une musique de fond" id="audioPickerBtn">
                        🎵
                    </label>
                    <div class="emoji-picker-wrap">
                        <button type="button" class="toolbar-btn" id="emojiPickerBtn" title="Emojis" onclick="toggleEmojiPanel(event)">😊</button>
                        <div class="emoji-panel" id="emojiPanel">
                            <div class="emoji-grid" id="emojiGrid"></div>
                        </div>
                    </div>
                    {{-- Images: multiple, stored as images[] --}}
                    <input type="file" id="imgPickerInput" accept="image/jpeg,image/png,image/gif,image/webp" multiple style="display:none">
                    <input type="file" id="imgMoreInput"   accept="image/jpeg,image/png,image/gif,image/webp" multiple style="display:none">
                    {{-- Vidéo: single --}}
                    <input type="file" id="videoInput" name="media" style="display:none" accept="video/mp4,video/quicktime,video/webm">
                    {{-- Audio de fond --}}
                    <input type="file" id="audioInput" name="audio" style="display:none" accept="audio/mpeg,audio/ogg,audio/wav,audio/mp4,audio/x-m4a">
                </div>
                <span class="char-counter" id="charCounter">0 / 5000</span>
                <span id="quillWordCount" style="font-size:.72rem;color:var(--text-faint);"></span>
            </div>

            <!-- Visibilité -->
            <div class="visibility-row">
                <span style="font-size:1rem;">👁</span>
                <span class="visibility-label">Publier immédiatement</span>
                <label class="toggle-switch">
                    <input type="checkbox" name="is_published" value="1" checked id="publishToggle">
                    <span class="toggle-slider"></span>
                </label>
                <span class="visibility-text" id="visibilityText">Publié</span>
            </div>

            <!-- Boutons submit -->
            <div class="submit-row">
                <button type="submit" name="_action" value="draft" class="btn-draft" id="draftBtn">
                    Brouillon
                </button>
                <button type="submit" name="_action" value="publish" class="btn-publish" id="publishBtn">
                    ✦ Publier
                </button>
            </div>

        </div>
    </form>

    <!-- Tips -->
    <div class="create-tips">
        <div class="tips-title">Conseils</div>
        <div class="tip-item"><span class="tip-icon">📸</span> Jusqu'à 10 images JPG, PNG, GIF ou WEBP (max 20 Mo chacune)</div>
        <div class="tip-item"><span class="tip-icon">🎬</span> Ou une vidéo MP4, MOV ou WEBM (max 50 Mo) — pas mixable avec des images</div>
        <div class="tip-item"><span class="tip-icon">🎵</span> Ajoute une musique de fond MP3, OGG, WAV ou M4A (max 20 Mo)</div>
        <div class="tip-item"><span class="tip-icon">✍️</span> Tu peux publier du texte seul, sans média</div>
        <div class="tip-item"><span class="tip-icon">💾</span> Enregistre en brouillon pour publier plus tard</div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    /* ── Auto-resize textareas ── */
    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }

    /* ── Quill rich editor ── */
    const quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Quoi de neuf ? Partage ton contenu avec la communauté...',
        modules: {
            toolbar: [
                [{ header: [2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link'],
                ['clean'],
            ],
        },
    });

    // Pre-fill if old value exists (validation error)
    const oldBody = document.getElementById('postBody').value;
    if (oldBody) quill.clipboard.dangerouslyPasteHTML(oldBody);

    // Sync hidden input + char counter on every change
    quill.on('text-change', function() {
        const html = quill.getSemanticHTML();
        const hidden = document.getElementById('postBody');
        hidden.value = html;
        const len = quill.getText().trim().length;
        const counter = document.getElementById('charCounter');
        counter.textContent = len + ' / 5000';
        counter.className = 'char-counter' + (len > 4800 ? ' over' : len > 4000 ? ' warn' : '');
    });

    /* ══════════════════════════════════════
       MULTI-IMAGE PICKER
    ══════════════════════════════════════ */
    const MAX_IMAGES  = 10;
    let selectedFiles = []; // Array of File objects

    const gridWrap   = document.getElementById('imgGridWrap');
    const grid       = document.getElementById('imgGrid');
    const addMoreBtn = document.getElementById('imgAddMore');
    const pickerBtn  = document.getElementById('imgPickerBtn');
    const pickerInput = document.getElementById('imgPickerInput');
    const moreInput  = document.getElementById('imgMoreInput');
    const form       = document.getElementById('postForm');

    /* Handle new files selected (initial or "add more") */
    function handleNewFiles(files) {
        const remaining = MAX_IMAGES - selectedFiles.length;
        const toAdd = Array.from(files).slice(0, remaining);
        selectedFiles = [...selectedFiles, ...toAdd];
        renderGrid();
        syncHiddenInputs();
    }

    pickerInput.addEventListener('change', function() {
        if (this.files && this.files.length) handleNewFiles(this.files);
        this.value = ''; // reset so same files can be re-added if removed
    });

    moreInput.addEventListener('change', function() {
        if (this.files && this.files.length) handleNewFiles(this.files);
        this.value = '';
    });

    function removeImage(idx) {
        selectedFiles.splice(idx, 1);
        renderGrid();
        syncHiddenInputs();
    }

    /* Render the thumbnail grid */
    function renderGrid() {
        const n = selectedFiles.length;

        if (n === 0) {
            gridWrap.classList.remove('visible');
            addMoreBtn.style.display = 'none';
            // Re-enable picker button
            pickerBtn.style.display = '';
            return;
        }

        gridWrap.classList.add('visible');
        pickerBtn.style.display = 'none'; // Hide image toolbar btn (use "add more" instead)
        addMoreBtn.style.display = n < MAX_IMAGES ? 'inline-flex' : 'none';

        const countClass = n === 1 ? 'count-1'
            : n === 2 ? 'count-2'
            : n === 3 ? 'count-3'
            : n === 4 ? 'count-4'
            : 'count-many';

        // Show max 4 thumbs, indicate overflow on last
        const displayCount = Math.min(n, 4);
        const overflow = n - 4;

        grid.className = `img-grid ${countClass}`;
        grid.innerHTML = '';

        for (let i = 0; i < displayCount; i++) {
            const file = selectedFiles[i];
            const url  = URL.createObjectURL(file);
            const isLast = i === 3 && overflow > 0;

            const div = document.createElement('div');
            div.className = 'img-thumb';
            div.innerHTML = `<img src="${url}" alt="">
                <button type="button" class="img-thumb-remove" data-idx="${i}">✕</button>
                ${isLast ? `<div class="img-thumb-more">+${overflow}</div>` : ''}`;
            grid.appendChild(div);
        }

        grid.querySelectorAll('.img-thumb-remove').forEach(btn => {
            btn.addEventListener('click', () => removeImage(parseInt(btn.dataset.idx)));
        });
    }

    /* Inject hidden file inputs so the form submits them as images[] */
    function syncHiddenInputs() {
        // Remove old hidden inputs
        form.querySelectorAll('input[name="images[]"]').forEach(el => el.remove());

        // For each selected file, create a DataTransfer + hidden input
        selectedFiles.forEach(file => {
            const dt    = new DataTransfer();
            dt.items.add(file);
            const input = document.createElement('input');
            input.type  = 'file';
            input.name  = 'images[]';
            input.style.display = 'none';
            input.files = dt.files;
            form.appendChild(input);
        });
    }

    /* ── Vidéo ── */
    document.getElementById('videoInput').addEventListener('change', function() {
        if (!this.files || !this.files[0]) return;

        // Can't mix video + images
        if (selectedFiles.length > 0) {
            alert('Tu ne peux pas mélanger des images et une vidéo.');
            this.value = '';
            return;
        }

        const wrap = document.getElementById('mediaPreview');
        const url  = URL.createObjectURL(this.files[0]);
        wrap.innerHTML = `
            <video src="${url}" controls style="width:100%;max-height:400px;display:block;" id="videoPreviewEl"></video>
            <button type="button" class="media-preview-remove" onclick="removeVideo()">✕</button>`;
        wrap.classList.add('visible');
    });

    function removeVideo() {
        const wrap = document.getElementById('mediaPreview');
        wrap.innerHTML = '';
        wrap.classList.remove('visible');
        document.getElementById('videoInput').value = '';
    }

    /* ── Audio ── */
    document.getElementById('audioInput').addEventListener('change', function() {
        if (!this.files || !this.files[0]) return;
        const name = this.files[0].name.replace(/\.[^.]+$/, ''); // sans extension
        document.getElementById('audioPreviewName').textContent = name;
        document.getElementById('audioPreview').classList.add('visible');
        document.getElementById('audioPickerBtn').classList.add('active');
    });

    function removeAudio() {
        document.getElementById('audioInput').value = '';
        document.getElementById('audioPreview').classList.remove('visible');
        document.getElementById('audioPickerBtn').classList.remove('active');
    }

    /* ── Cover image ── */
    document.getElementById('coverInput').addEventListener('change', function() {
        if (!this.files || !this.files[0]) return;
        const url = URL.createObjectURL(this.files[0]);
        const preview = document.getElementById('coverPreview');
        preview.src = url;
        preview.style.display = 'block';
        document.getElementById('coverPlaceholder').style.display = 'none';
        document.getElementById('coverRemoveBtn').style.display = 'flex';
        document.getElementById('coverZone').classList.add('has-cover');
    });

    function removeCover(e) {
        e.stopPropagation();
        document.getElementById('coverInput').value = '';
        document.getElementById('coverPreview').style.display = 'none';
        document.getElementById('coverPreview').src = '';
        document.getElementById('coverPlaceholder').style.display = 'flex';
        document.getElementById('coverRemoveBtn').style.display = 'none';
        document.getElementById('coverZone').classList.remove('has-cover');
    }

    /* ── Toggle visibilité ── */
    const toggle  = document.getElementById('publishToggle');
    const visText = document.getElementById('visibilityText');
    const draftBtn = document.getElementById('draftBtn');

    toggle.addEventListener('change', function() {
        visText.textContent = this.checked ? 'Publié' : 'Brouillon';
    });

    draftBtn.addEventListener('click', function() {
        toggle.checked = false;
        visText.textContent = 'Brouillon';
    });

    /* ── Emoji Picker ── */
    const EMOJIS = [
        '😀','😂','😍','🥰','😎','😭','😤','🤔','😏','🙄',
        '😊','🥺','😘','😜','🤩','😅','😇','🤗','😬','🤯',
        '❤️','🧡','💛','💚','💙','💜','🖤','🤍','💕','✨',
        '🔥','⭐','🌟','💫','👏','👍','🙌','💪','🤞','🫶',
        '📸','🎵','🎶','🎤','🎨','🏆','💯','📝','🌹','🌙',
        '☀️','🌸','🌈','🦋','👑','💀','🐍','🦁','🦊','🐺',
        '😈','👻','🤑','🧠','💔','💩','🎭','🌊','🍑','🫦',
    ];

    (function buildEmojiGrid() {
        const grid = document.getElementById('emojiGrid');
        EMOJIS.forEach(emoji => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'emoji-item';
            btn.textContent = emoji;
            btn.addEventListener('click', () => insertEmoji(emoji));
            grid.appendChild(btn);
        });
    })();

    function toggleEmojiPanel(e) {
        e.stopPropagation();
        document.getElementById('emojiPanel').classList.toggle('open');
    }

    function insertEmoji(emoji) {
        const ta = document.getElementById('postBody');
        const start = ta.selectionStart;
        const end   = ta.selectionEnd;
        ta.setRangeText(emoji, start, end, 'end');
        ta.dispatchEvent(new Event('input'));
        ta.focus();
    }

    document.addEventListener('click', function(e) {
        const panel = document.getElementById('emojiPanel');
        if (!panel.contains(e.target) && e.target.id !== 'emojiPickerBtn') {
            panel.classList.remove('open');
        }
    });

    /* ── Init ── */
    document.querySelectorAll('.post-title-input').forEach(autoResize);
</script>
@endpush
