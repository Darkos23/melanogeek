@extends('layouts.app')

@section('title', 'Ajouter un projet — Portfolio')

@push('styles')
<style>
    .pf-form-page { max-width: 740px; margin: 0 auto; padding: 80px 24px 60px; }
    .pf-back { display:inline-flex;align-items:center;gap:6px;font-size:.82rem;color:var(--text-muted);text-decoration:none;margin-bottom:28px;transition:color .2s; }
    .pf-back:hover { color:var(--text); }
    .pf-heading { font-family:var(--font-head);font-size:1.6rem;font-weight:800;margin-bottom:6px; }
    .pf-sub { font-size:.9rem;color:var(--text-muted);margin-bottom:36px;line-height:1.5; }

    .pf-card { background:var(--bg-card);border:1px solid var(--border);border-radius:20px;padding:32px;margin-bottom:20px;transition:border-color .4s; }
    .pf-section-title { font-family:var(--font-head);font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:20px; }

    .pf-field { margin-bottom:20px; }
    .pf-label { display:block;font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px; }
    .pf-label span { font-weight:400;text-transform:none;letter-spacing:0;font-size:.75rem;color:var(--text-faint); }
    .pf-input, .pf-textarea, .pf-select {
        width:100%; padding:11px 14px;
        background:var(--bg-card2); border:1px solid var(--border);
        border-radius:12px; color:var(--text);
        font-family:var(--font-body); font-size:.9rem;
        outline:none; transition:border-color .2s;
        box-sizing:border-box;
    }
    .pf-input:focus, .pf-textarea:focus, .pf-select:focus { border-color:var(--terra); }
    .pf-textarea { resize:vertical; min-height:120px; }

    /* Category grid */
    .pf-cat-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:10px; }
    .pf-cat-option { display:none; }
    .pf-cat-label {
        display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;
        padding:14px 10px; border-radius:14px;
        background:var(--bg-card2); border:1.5px solid var(--border);
        font-size:.78rem;font-weight:600;color:var(--text-muted);
        cursor:pointer;transition:all .2s;text-align:center;
    }
    .pf-cat-label .pf-cat-icon { font-size:1.4rem; }
    .pf-cat-label:hover { border-color:var(--border-hover);color:var(--text); }
    .pf-cat-option:checked + .pf-cat-label {
        background:var(--terra-soft);border-color:var(--terra);color:var(--terra);
    }

    /* Cover image upload */
    .pf-cover-zone {
        border:2px dashed var(--border); border-radius:16px;
        aspect-ratio:16/9; display:flex;flex-direction:column;
        align-items:center;justify-content:center;gap:10px;
        cursor:pointer;transition:border-color .2s;overflow:hidden;
        position:relative; background:var(--bg-card2);
    }
    .pf-cover-zone:hover { border-color:var(--terra); }
    .pf-cover-zone input { position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%; }
    .pf-cover-zone-icon { font-size:2rem; }
    .pf-cover-zone-text { font-size:.82rem;color:var(--text-muted);text-align:center;line-height:1.5; }
    .pf-cover-zone-text strong { color:var(--terra);font-size:.85rem; }
    .pf-cover-preview { width:100%;height:100%;object-fit:cover;position:absolute;inset:0;display:none; }

    /* Tags */
    .pf-tags-hint { font-size:.72rem;color:var(--text-faint);margin-top:5px; }

    /* Submit */
    .pf-submit-row { display:flex;gap:12px;margin-top:32px; }
    .pf-btn-submit {
        flex:1;padding:14px 24px;border-radius:100px;
        background:var(--terra);color:white;border:none;
        font-family:var(--font-head);font-size:.95rem;font-weight:700;
        cursor:pointer;transition:background .2s;
    }
    .pf-btn-submit:hover { background:var(--accent); }
    .pf-btn-cancel {
        padding:14px 24px;border-radius:100px;
        background:var(--bg-card2);color:var(--text-muted);
        border:1px solid var(--border);text-decoration:none;
        font-size:.9rem;font-weight:600;font-family:var(--font-body);
        display:inline-flex;align-items:center;transition:all .2s;
    }
    .pf-btn-cancel:hover { color:var(--text);border-color:var(--border-hover); }

    @media(max-width:600px) { .pf-cat-grid { grid-template-columns:repeat(2,1fr); } }
</style>
@endpush

@section('content')
<div class="pf-form-page">

    <a href="{{ route('profile.show', auth()->user()->username) }}" class="pf-back">
        ← Retour au profil
    </a>

    <div class="pf-heading">Nouveau projet</div>
    <div class="pf-sub">Ajoute un projet à ton portfolio. Montre ton meilleur travail.</div>

    @if($errors->any())
    <div style="background:rgba(224,85,85,.1);border:1px solid rgba(224,85,85,.3);border-radius:12px;padding:14px 18px;margin-bottom:24px;">
        @foreach($errors->all() as $e)
            <div style="font-size:.84rem;color:#E05555;">⚠ {{ $e }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('portfolio.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Infos de base --}}
        <div class="pf-card">
            <div class="pf-section-title">Informations</div>

            <div class="pf-field">
                <label class="pf-label" for="title">Titre du projet <span>*</span></label>
                <input class="pf-input" id="title" name="title" type="text"
                    value="{{ old('title') }}" placeholder="Ex: Identité visuelle Djolof Music" required maxlength="120">
            </div>

            <div class="pf-field">
                <label class="pf-label" for="description">Description <span>(optionnel)</span></label>
                <textarea class="pf-textarea" id="description" name="description"
                    placeholder="Décris le projet, ton rôle, les outils utilisés…" maxlength="2000">{{ old('description') }}</textarea>
            </div>

            <div class="pf-field">
                <label class="pf-label" for="external_url">Lien externe <span>(optionnel)</span></label>
                <input class="pf-input" id="external_url" name="external_url" type="url"
                    value="{{ old('external_url') }}" placeholder="https://…">
            </div>

            <div class="pf-field">
                <label class="pf-label" for="tags">Tags <span>(optionnel, séparés par des virgules)</span></label>
                <input class="pf-input" id="tags" name="tags" type="text"
                    value="{{ old('tags') }}" placeholder="branding, logo, afrique, typographie">
                <div class="pf-tags-hint">Ex : branding, logo, typographie</div>
            </div>
        </div>

        {{-- Catégorie --}}
        <div class="pf-card">
            <div class="pf-section-title">Catégorie</div>
            <div class="pf-cat-grid">
                @foreach($categories as $key => $label)
                @php [$icon, $name] = explode(' ', $label, 2); @endphp
                <div>
                    <input class="pf-cat-option" type="radio" name="category" id="cat_{{ $key }}"
                        value="{{ $key }}" {{ old('category', 'design') === $key ? 'checked' : '' }}>
                    <label class="pf-cat-label" for="cat_{{ $key }}">
                        <span class="pf-cat-icon">{{ $icon }}</span>
                        <span>{{ $name }}</span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Image de couverture --}}
        <div class="pf-card">
            <div class="pf-section-title">Image de couverture</div>
            <div class="pf-cover-zone" id="coverZone" onclick="document.getElementById('cover_image').click()">
                <input type="file" id="cover_image" name="cover_image" accept="image/*"
                    onchange="previewCover(this)" style="display:none;">
                <img class="pf-cover-preview" id="coverPreview" alt="">
                <div id="coverPlaceholder" style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                    <div class="pf-cover-zone-icon">🖼️</div>
                    <div class="pf-cover-zone-text">
                        <strong>Clique pour choisir une image</strong><br>
                        JPG, PNG, WebP — max 4 Mo
                    </div>
                </div>
            </div>
        </div>

        <div class="pf-submit-row">
            <a href="{{ route('profile.show', auth()->user()->username) }}" class="pf-btn-cancel">Annuler</a>
            <button type="submit" class="pf-btn-submit">✦ Publier le projet</button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
function previewCover(input) {
    const preview = document.getElementById('coverPreview');
    const placeholder = document.getElementById('coverPlaceholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
// Allow drag & drop on cover zone
const zone = document.getElementById('coverZone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = 'var(--terra)'; });
zone.addEventListener('dragleave', () => { zone.style.borderColor = ''; });
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.style.borderColor = '';
    const files = e.dataTransfer.files;
    if (files.length) {
        const inp = document.getElementById('cover_image');
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        inp.files = dt.files;
        previewCover(inp);
    }
});
</script>
@endpush
