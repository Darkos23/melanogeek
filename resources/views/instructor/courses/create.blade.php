@extends('layouts.app')

@section('title', 'Créer un cours — Waxtu')

@push('styles')
<style>
.create-course-page {
    padding-top: calc(80px + env(safe-area-inset-top));
    min-height: 100vh;
    padding-bottom: 60px;
}
.create-course-inner {
    max-width: 720px;
    margin: 0 auto;
    padding: 40px 32px;
}
.page-title {
    font-family: var(--font-head);
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 28px;
}
.form-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 20px;
}
.form-section-title {
    font-weight: 700;
    font-size: .95rem;
    margin-bottom: 18px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    font-size: .78rem;
}
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: 7px; }
.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 11px 14px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    font-size: .9rem;
    outline: none;
    transition: border-color .2s;
    font-family: var(--font-body);
}
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--terra); }
.form-textarea { resize: vertical; min-height: 100px; }
.form-hint { font-size: .75rem; color: var(--text-muted); margin-top: 5px; }
.error { color: #e53e3e; font-size: .8rem; margin-top: 4px; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

.toggle-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.toggle {
    width: 44px; height: 24px;
    background: var(--border);
    border-radius: 12px;
    cursor: pointer;
    position: relative;
    transition: background .2s;
    -webkit-appearance: none;
    appearance: none;
    border: none;
    outline: none;
    flex-shrink: 0;
}
.toggle:checked { background: var(--terra); }
.toggle::after {
    content: '';
    position: absolute;
    top: 3px; left: 3px;
    width: 18px; height: 18px;
    background: #fff;
    border-radius: 50%;
    transition: transform .2s;
}
.toggle:checked::after { transform: translateX(20px); }

.btn-submit {
    padding: 13px 32px;
    background: var(--terra);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    transition: opacity .2s;
}
.btn-submit:hover { opacity: .88; }
.btn-cancel {
    padding: 13px 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 12px;
    font-size: .9rem;
    text-decoration: none;
}

@media (max-width: 640px) {
    .create-course-inner { padding: 24px 16px; }
    .form-row { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="create-course-page">
    <div class="create-course-inner">
        <h1 class="page-title">Créer un cours</h1>

        @if($errors->any())
            <div style="padding:14px;background:rgba(229,62,62,.1);border:1px solid rgba(229,62,62,.3);border-radius:10px;margin-bottom:20px;font-size:.85rem;color:#e53e3e">
                @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('instructor.courses.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Informations principales --}}
            <div class="form-section">
                <div class="form-section-title">Informations générales</div>

                <div class="form-group">
                    <label class="form-label" for="title">Titre du cours *</label>
                    <input type="text" name="title" id="title" class="form-input"
                           value="{{ old('title') }}" placeholder="Ex: Introduction aux réseaux CCNA" required>
                    @error('title')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description *</label>
                    <textarea name="description" id="description" class="form-textarea"
                              placeholder="Décrivez ce que les étudiants vont apprendre..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Catégorie *</label>
                        <select name="category" class="form-select" required>
                            <option value="">Choisir...</option>
                            <option value="reseaux_securite" @selected(old('category') === 'reseaux_securite')>Réseaux & Sécurité</option>
                            <option value="informatique_gestion" @selected(old('category') === 'informatique_gestion')>Informatique de gestion</option>
                            <option value="bases_informatiques" @selected(old('category') === 'bases_informatiques')>Bases informatiques</option>
                        </select>
                        @error('category')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Niveau *</label>
                        <select name="level" class="form-select" required>
                            <option value="debutant" @selected(old('level') === 'debutant')>Débutant</option>
                            <option value="intermediaire" @selected(old('level') === 'intermediaire')>Intermédiaire</option>
                            <option value="avance" @selected(old('level') === 'avance')>Avancé</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="thumbnail">Image de couverture</label>
                    <input type="file" name="thumbnail" id="thumbnail" class="form-input" accept="image/*">
                    <div class="form-hint">JPG, PNG, WebP — recommandé 1280×720px</div>
                </div>
            </div>

            {{-- Tarification --}}
            <div class="form-section">
                <div class="form-section-title">Tarification</div>

                <div class="form-group">
                    <div class="toggle-wrap">
                        <input type="checkbox" name="is_free" id="is_free" class="toggle"
                               @checked(old('is_free')) onchange="document.getElementById('priceGroup').style.display=this.checked?'none':'block'">
                        <label for="is_free" class="form-label" style="margin:0">Cours gratuit</label>
                    </div>
                </div>

                <div class="form-group" id="priceGroup" style="{{ old('is_free') ? 'display:none' : '' }}">
                    <label class="form-label" for="price">Prix (FCFA)</label>
                    <input type="number" name="price" id="price" class="form-input"
                           value="{{ old('price', 0) }}" min="0" step="500">
                    @error('price')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Contenu pédagogique --}}
            <div class="form-section">
                <div class="form-section-title">Contenu pédagogique</div>

                <div class="form-group">
                    <label class="form-label" for="what_you_learn">Ce que les étudiants vont apprendre</label>
                    <textarea name="what_you_learn" id="what_you_learn" class="form-textarea" rows="5"
                              placeholder="Un élément par ligne&#10;Configurer un routeur Cisco&#10;Comprendre le modèle OSI&#10;...">{{ old('what_you_learn') }}</textarea>
                    <div class="form-hint">Un élément par ligne</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="requirements">Prérequis</label>
                    <textarea name="requirements" id="requirements" class="form-textarea" rows="3"
                              placeholder="Un prérequis par ligne&#10;Connaissances de base en informatique">{{ old('requirements') }}</textarea>
                    <div class="form-hint">Un prérequis par ligne</div>
                </div>
            </div>

            <div style="display:flex;gap:12px;align-items:center">
                <button type="submit" class="btn-submit">Créer le cours</button>
                <a href="{{ route('instructor.dashboard') }}" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
