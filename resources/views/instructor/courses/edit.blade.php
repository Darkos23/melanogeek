@extends('layouts.app')

@section('title', 'Modifier — ' . $course->title)

@push('styles')
<style>
.edit-course-page {
    padding-top: calc(80px + env(safe-area-inset-top));
    min-height: 100vh;
    padding-bottom: 60px;
}
.edit-inner {
    max-width: 860px;
    margin: 0 auto;
    padding: 40px 32px;
}
.edit-header {
    display: flex;
    gap: 12px;
    align-items: center;
    margin-bottom: 28px;
}
.page-title {
    font-family: var(--font-head);
    font-size: 1.4rem;
    font-weight: 800;
    flex: 1;
}
.tabs {
    display: flex;
    gap: 0;
    border-bottom: 2px solid var(--border);
    margin-bottom: 28px;
}
.tab {
    padding: 10px 20px;
    font-size: .88rem;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: color .2s;
    background: none;
    border-top: none;
    border-left: none;
    border-right: none;
}
.tab.active { color: var(--terra); border-bottom-color: var(--terra); }
.tab-content { display: none; }
.tab-content.active { display: block; }

.form-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 22px;
    margin-bottom: 16px;
}
.form-section-title {
    font-weight: 700;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--text-muted);
    margin-bottom: 16px;
}
.form-group { margin-bottom: 14px; }
.form-label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: 6px; }
.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 10px 14px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 9px;
    color: var(--text);
    font-size: .9rem;
    outline: none;
    transition: border-color .2s;
    font-family: var(--font-body);
}
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--terra); }
.form-textarea { resize: vertical; min-height: 90px; }
.form-hint { font-size: .75rem; color: var(--text-muted); margin-top: 4px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.error { color: #e53e3e; font-size: .8rem; margin-top: 4px; }

.toggle { width: 44px; height: 24px; background: var(--border); border-radius: 12px; cursor: pointer; position: relative; transition: background .2s; -webkit-appearance: none; appearance: none; border: none; outline: none; }
.toggle:checked { background: var(--terra); }
.toggle::after { content: ''; position: absolute; top: 3px; left: 3px; width: 18px; height: 18px; background: #fff; border-radius: 50%; transition: transform .2s; }
.toggle:checked::after { transform: translateX(20px); }

.btn-save {
    padding: 11px 28px;
    background: var(--terra);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: .9rem;
    cursor: pointer;
    transition: opacity .2s;
}
.btn-save:hover { opacity: .85; }

/* ── Curriculum ── */
.section-block {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 12px;
    overflow: hidden;
}
.section-head {
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 12px 16px;
    background: var(--bg-card2);
    border-bottom: 1px solid var(--border);
}
.section-head-title { font-weight: 700; flex: 1; font-size: .92rem; }
.btn-sm {
    padding: 5px 12px;
    border-radius: 7px;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid var(--border);
    background: var(--bg);
    color: var(--text);
    transition: background .15s;
    text-decoration: none;
}
.btn-sm:hover { background: var(--bg-hover); }
.btn-sm.danger { border-color: rgba(229,62,62,.3); color: #e53e3e; }
.btn-sm.danger:hover { background: rgba(229,62,62,.08); }

.lesson-list { padding: 0 4px; }
.lesson-item {
    display: flex;
    gap: 8px;
    align-items: center;
    padding: 9px 12px;
    border-bottom: 1px solid var(--border);
    font-size: .85rem;
}
.lesson-item:last-child { border-bottom: none; }
.lesson-item-title { flex: 1; color: var(--text-muted); }
.lesson-item-meta { font-size: .75rem; color: var(--text-muted); }

/* Formulaire ajout leçon */
.add-lesson-form {
    padding: 14px 16px;
    border-top: 1px solid var(--border);
    background: var(--bg);
}
.add-lesson-title {
    font-size: .8rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 10px;
}
.add-form-row { display: flex; gap: 8px; flex-wrap: wrap; align-items: flex-end; }
.add-form-row .form-input,
.add-form-row .form-select { flex: 1; min-width: 150px; }
.btn-add-lesson {
    padding: 9px 16px;
    background: var(--terra);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: .82rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
}

/* Formulaire ajout section */
.add-section-form {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-top: 16px;
}
.add-section-form .form-input { flex: 1; }
.btn-add-section {
    padding: 10px 18px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 9px;
    font-size: .88rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: background .15s;
}
.btn-add-section:hover { background: var(--bg-hover); }

@media (max-width: 640px) {
    .edit-inner { padding: 20px 14px; }
    .form-row { grid-template-columns: 1fr; }
    .tabs { overflow-x: auto; }
}
</style>
@endpush

@section('content')
<div class="edit-course-page">
    <div class="edit-inner">

        @if(session('success'))
            <div style="padding:12px 16px;background:rgba(42,122,72,.12);border:1px solid rgba(42,122,72,.3);border-radius:10px;margin-bottom:18px;font-size:.85rem;color:var(--green)">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="edit-header">
            <h1 class="page-title">{{ $course->title }}</h1>
            <a href="{{ route('instructor.dashboard') }}" class="btn-sm">← Retour</a>
            @if($course->status === 'published')
                <a href="{{ route('courses.show', $course->slug) }}" class="btn-sm">Voir le cours</a>
            @endif
        </div>

        {{-- Tabs --}}
        <div class="tabs">
            <button class="tab active" onclick="switchTab('infos', this)">Informations</button>
            <button class="tab" onclick="switchTab('curriculum', this)">Programme</button>
        </div>

        {{-- Tab: Infos --}}
        <div class="tab-content active" id="tab-infos">
            <form method="POST" action="{{ route('instructor.courses.update', $course->id) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="form-section">
                    <div class="form-section-title">Informations générales</div>
                    <div class="form-group">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" class="form-input" value="{{ old('title', $course->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea">{{ old('description', $course->description) }}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Catégorie</label>
                            <select name="category" class="form-select">
                                <option value="reseaux_securite" @selected(old('category', $course->category) === 'reseaux_securite')>Réseaux & Sécurité</option>
                                <option value="informatique_gestion" @selected(old('category', $course->category) === 'informatique_gestion')>Informatique de gestion</option>
                                <option value="bases_informatiques" @selected(old('category', $course->category) === 'bases_informatiques')>Bases informatiques</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Niveau</label>
                            <select name="level" class="form-select">
                                <option value="debutant" @selected(old('level', $course->level) === 'debutant')>Débutant</option>
                                <option value="intermediaire" @selected(old('level', $course->level) === 'intermediaire')>Intermédiaire</option>
                                <option value="avance" @selected(old('level', $course->level) === 'avance')>Avancé</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="draft" @selected($course->status === 'draft')>Brouillon</option>
                                <option value="published" @selected($course->status === 'published')>Publié</option>
                                <option value="archived" @selected($course->status === 'archived')>Archivé</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Prix (FCFA)</label>
                            <input type="number" name="price" class="form-input" value="{{ old('price', $course->price) }}" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="display:flex;gap:10px;align-items:center">
                            <input type="checkbox" name="is_free" id="is_free" class="toggle" @checked($course->is_free)>
                            <label for="is_free" class="form-label" style="margin:0">Cours gratuit</label>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Image & contenu pédagogique</div>
                    <div class="form-group">
                        <label class="form-label">Image de couverture</label>
                        @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="" style="height:80px;border-radius:6px;margin-bottom:8px;display:block">
                        @endif
                        <input type="file" name="thumbnail" class="form-input" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ce que les étudiants vont apprendre</label>
                        <textarea name="what_you_learn" class="form-textarea" rows="5" placeholder="Un élément par ligne">{{ old('what_you_learn', implode("\n", $course->what_you_learn ?? [])) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prérequis</label>
                        <textarea name="requirements" class="form-textarea" rows="3" placeholder="Un prérequis par ligne">{{ old('requirements', implode("\n", $course->requirements ?? [])) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn-save">Enregistrer</button>
            </form>
        </div>

        {{-- Tab: Curriculum --}}
        <div class="tab-content" id="tab-curriculum">

            @foreach($course->sections as $section)
            <div class="section-block">
                <div class="section-head">
                    <span class="section-head-title">{{ $section->title }}</span>
                    <form method="POST" action="{{ route('instructor.sections.destroy', $section->id) }}" style="display:inline"
                          onsubmit="return confirm('Supprimer cette section et toutes ses leçons ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-sm danger">Supprimer</button>
                    </form>
                </div>

                <div class="lesson-list">
                    @foreach($section->lessons as $lesson)
                    <div class="lesson-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polygon points="10,8 16,12 10,16" fill="currentColor"/>
                        </svg>
                        <span class="lesson-item-title">{{ $lesson->title }}</span>
                        @if($lesson->is_preview)
                            <span style="font-size:.68rem;padding:2px 6px;border-radius:4px;background:var(--gold-soft);color:var(--gold)">Aperçu</span>
                        @endif
                        <span class="lesson-item-meta">{{ $lesson->duration }}min</span>
                        <form method="POST" action="{{ route('instructor.lessons.destroy', $lesson->id) }}"
                              onsubmit="return confirm('Supprimer cette leçon ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm danger">✕</button>
                        </form>
                    </div>
                    @endforeach
                </div>

                {{-- Ajouter une leçon --}}
                <div class="add-lesson-form">
                    <div class="add-lesson-title">Ajouter une leçon</div>
                    <form method="POST" action="{{ route('instructor.lessons.store', $section->id) }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="add-form-row">
                            <input type="text" name="title" class="form-input" placeholder="Titre de la leçon" required>
                            <input type="number" name="duration" class="form-input" placeholder="Durée (min)" min="1" style="max-width:120px">
                            <input type="file" name="video" class="form-input" accept="video/*" style="flex:2">
                            <label style="display:flex;gap:6px;align-items:center;font-size:.8rem;white-space:nowrap">
                                <input type="checkbox" name="is_preview" value="1"> Aperçu gratuit
                            </label>
                            <button type="submit" class="btn-add-lesson">+ Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach

            {{-- Ajouter une section --}}
            <form method="POST" action="{{ route('instructor.sections.store', $course->id) }}" class="add-section-form">
                @csrf
                <input type="text" name="title" class="form-input" placeholder="Titre de la nouvelle section" required>
                <button type="submit" class="btn-add-section">+ Ajouter une section</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(id, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    btn.classList.add('active');
}
</script>
@endpush
@endsection
