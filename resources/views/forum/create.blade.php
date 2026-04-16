@extends('layouts.blog')

@section('title', 'Nouveau sujet — Forum')

@push('styles')
<style>
.create-forum-wrap { max-width: 680px; }
.create-forum-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    margin-top: 24px;
}
.cf-section { padding: 20px 24px; border-bottom: 1px solid var(--border); }
.cf-section:last-child { border-bottom: none; }
.cf-label {
    display: block;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 8px;
}
.cf-input, .cf-select, .cf-textarea {
    width: 100%;
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    font-family: var(--font-body);
    font-size: .9rem;
    padding: 11px 14px;
    outline: none;
    transition: border-color .2s;
}
.cf-input:focus, .cf-select:focus, .cf-textarea:focus { border-color: var(--terra); }
.cf-textarea { min-height: 200px; resize: vertical; line-height: 1.65; }
.cf-select option { background: var(--bg-card); }
.cf-actions { display: flex; justify-content: flex-end; gap: 10px; padding: 16px 20px; }
.cf-btn-cancel {
    background: transparent; border: 1px solid var(--border); color: var(--text-muted);
    padding: 10px 20px; border-radius: 100px; font-family: var(--font-body);
    font-size: .86rem; cursor: pointer; text-decoration: none; display: inline-flex;
    align-items: center; transition: all .2s;
}
.cf-btn-cancel:hover { border-color: var(--border-hover); color: var(--text); }
.cf-btn-submit {
    background: var(--terra); border: none; color: white;
    padding: 10px 24px; border-radius: 100px; font-family: var(--font-head);
    font-size: .9rem; font-weight: 700; cursor: pointer; transition: background .2s;
}
.cf-btn-submit:hover { background: var(--accent); }
</style>
@endpush

@section('main')
<div class="create-forum-wrap">

<div style="margin-bottom:4px">
    <a href="{{ route('forum.index') }}" style="font-size:.82rem;color:var(--text-muted);text-decoration:none;">← Forum</a>
</div>
<h1 style="font-family:var(--font-head);font-size:1.6rem;font-weight:800;letter-spacing:-.02em;margin-top:8px;">Nouveau sujet</h1>

@if($errors->any())
<div style="background:rgba(224,85,85,.08);border:1px solid rgba(224,85,85,.25);color:#E05555;border-radius:12px;padding:14px 18px;margin-top:16px;font-size:.85rem;">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('forum.store') }}">
    @csrf
    <div class="create-forum-card">

        <div class="cf-section">
            <label class="cf-label" for="cf_category">Catégorie</label>
            <select name="category" id="cf_category" class="cf-select" required>
                <option value="">Choisir une catégorie…</option>
                @foreach($categories as $slug => $info)
                    <option value="{{ $slug }}" @selected(old('category') === $slug)>
                        {{ $info['icon'] }} {{ $info['label'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="cf-section">
            <label class="cf-label" for="cf_title">Titre du sujet</label>
            <input type="text" name="title" id="cf_title" class="cf-input"
                   placeholder="Un titre clair et descriptif…"
                   value="{{ old('title') }}" maxlength="200" required>
        </div>

        <div class="cf-section">
            <label class="cf-label" for="cf_body">Contenu</label>
            <textarea name="body" id="cf_body" class="cf-textarea"
                      placeholder="Développe ton sujet ici…" maxlength="10000" required>{{ old('body') }}</textarea>
        </div>

        <div class="cf-actions">
            <a href="{{ route('forum.index') }}" class="cf-btn-cancel">Annuler</a>
            <button type="submit" class="cf-btn-submit">Publier le sujet →</button>
        </div>

    </div>
</form>

</div>
@endsection
