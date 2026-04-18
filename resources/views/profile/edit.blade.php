@extends('layouts.app')

@section('title', 'Modifier mon profil')

@push('styles')
<style>
    .edit-page { padding-top: calc(80px + env(safe-area-inset-top)); min-height: 100vh; }

    /* ── HEADER ── */
    .edit-header {
        max-width: 760px;
        margin: 0 auto;
        padding: 32px 32px 0;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
    }
    .edit-header-back {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: var(--bg-card);
        border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
        color: var(--text-muted);
        font-size: 1rem;
        transition: all .2s;
        cursor: pointer;
        flex-shrink: 0;
    }
    .edit-header-back:hover { border-color: var(--border-hover); color: var(--text); }
    .edit-header-title {
        font-family: var(--font-head);
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.02em;
    }
    .edit-header-sub { font-size: .84rem; color: var(--text-muted); margin-top: 2px; }

    /* ── LAYOUT ── */
    .edit-body {
        max-width: 760px;
        margin: 0 auto;
        padding: 0 32px 60px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* ── CARDS ── */
    .edit-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        transition: background .4s, border-color .4s;
    }
    .edit-card-header {
        padding: 20px 28px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .edit-card-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: var(--terra-soft);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .edit-card-title {
        font-family: var(--font-head);
        font-size: .95rem;
        font-weight: 700;
        color: var(--text);
    }
    .edit-card-desc { font-size: .78rem; color: var(--text-muted); margin-top: 1px; }
    .edit-card-body { padding: 0 28px 28px; }

    /* ── AVATAR UPLOAD ── */
    .avatar-upload-area {
        display: flex;
        align-items: center;
        gap: 24px;
        padding: 20px;
        background: var(--bg-card2);
        border-radius: 14px;
        border: 1px solid var(--border);
        transition: all .2s;
    }
    .avatar-preview {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra), var(--gold));
        padding: 2px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .avatar-preview-inner {
        width: 100%; height: 100%;
        border-radius: 50%;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem;
        overflow: hidden;
    }
    .avatar-preview-inner img { width:100%;height:100%;object-fit:cover;border-radius:50%; }
    .avatar-upload-info { flex: 1; }
    .avatar-upload-title { font-size: .88rem; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .avatar-upload-hint { font-size: .76rem; color: var(--text-muted); line-height: 1.5; }
    .btn-upload {
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text);
        padding: 8px 16px;
        border-radius: 100px;
        font-size: .8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .2s;
        font-family: var(--font-body);
        white-space: nowrap;
    }
    .btn-upload:hover { border-color: var(--terra); color: var(--terra); }

    /* Cover upload */
    .cover-upload-area {
        height: 120px;
        border-radius: 14px;
        border: 2px dashed var(--border);
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; gap: 8px;
        cursor: pointer;
        transition: all .2s;
        background: var(--bg-card2);
        position: relative;
        overflow: hidden;
    }
    .cover-upload-area:hover { border-color: var(--terra); background: var(--terra-soft); }
    .cover-upload-area img {
        position: absolute; inset: 0;
        width:100%; height:100%; object-fit:cover;
    }
    .cover-upload-area .cover-overlay {
        position: relative; z-index: 1;
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        background: rgba(0,0,0,0.4);
        padding: 8px 16px;
        border-radius: 8px;
    }
    .cover-upload-icon { font-size: 1.5rem; }
    .cover-upload-text { font-size: .78rem; color: var(--text-muted); }

    /* ── FORM ELEMENTS ── */
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group { margin-bottom: 16px; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label {
        display: block;
        font-size: .76rem;
        font-weight: 600;
        letter-spacing: .04em;
        color: var(--text-muted);
        margin-bottom: 7px;
        text-transform: uppercase;
    }
    .form-label span { color: var(--terra); }
    .form-input {
        width: 100%;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px 16px;
        color: var(--text);
        font-family: var(--font-body);
        font-size: .92rem;
        transition: border-color .2s, box-shadow .2s, background .4s;
        outline: none;
        cursor: pointer;
    }
    .form-input::placeholder { color: var(--text-faint); }
    .form-input:focus {
        border-color: var(--terra);
        box-shadow: 0 0 0 3px rgba(200,82,42,.1);
        background: var(--bg-card);
    }
    .form-input.is-error { border-color: #E05555; }
    .form-error { font-size: .75rem; color: #E05555; margin-top: 5px; }
    .form-hint { font-size: .74rem; color: var(--text-muted); margin-top: 5px; }

    textarea.form-input { resize: vertical; min-height: 90px; line-height: 1.6; }

    /* Input avec préfixe */
    .input-group { position: relative; }
    .input-prefix {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        font-size: .84rem; color: var(--text-muted);
        pointer-events: none;
    }
    .input-group .form-input { padding-left: 32px; }

    /* Input avec icône réseau social */
    .social-input-group { position: relative; }
    .social-input-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        font-size: .9rem; pointer-events: none;
    }
    .social-input-group .form-input { padding-left: 36px; }

    /* ── SAVE BUTTON ── */
    .edit-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 8px;
    }
    .btn-cancel {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-muted);
        padding: 11px 24px;
        border-radius: 100px;
        font-family: var(--font-body);
        font-size: .88rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
        display: inline-flex; align-items: center;
    }
    .btn-cancel:hover { border-color: var(--border-hover); color: var(--text); }
    .btn-save {
        background: var(--terra);
        border: none;
        color: white;
        padding: 11px 28px;
        border-radius: 100px;
        font-family: var(--font-head);
        font-size: .9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-save:hover {
        background: var(--accent);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(200,82,42,.3);
    }

    /* Status message */
    .status-saved {
        display: flex; align-items: center; gap: 8px;
        background: rgba(45,90,61,.12);
        border: 1px solid rgba(45,90,61,.25);
        color: #6DC48A;
        padding: 10px 16px;
        border-radius: 12px;
        font-size: .84rem;
        font-weight: 500;
    }

    /* Danger zone */
    .danger-zone {
        border-color: rgba(224,85,85,.25) !important;
    }
    .danger-zone .edit-card-icon { background: rgba(224,85,85,.1); }
    .btn-danger {
        background: transparent;
        border: 1px solid rgba(224,85,85,.4);
        color: #E05555;
        padding: 10px 20px;
        border-radius: 100px;
        font-family: var(--font-body);
        font-size: .84rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .2s;
    }
    .btn-danger:hover { background: rgba(224,85,85,.08); border-color: #E05555; }

    @@media (max-width: 768px) {
        .edit-header { padding: 20px 16px 0; }
        .edit-body { padding: 0 16px 40px; }
        .form-grid-2 { grid-template-columns: 1fr; }
        .avatar-upload-area { flex-direction: column; text-align: center; }
    }
</style>
@endpush

@section('content')
<div class="edit-page">

    <!-- Header -->
    <div class="edit-header">
        <a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('profile.show', auth()->user()->username) }}" class="edit-header-back"><x-icon name="arrow-left" :size="18"/></a>
        <div>
            <div class="edit-header-title">Modifier mon profil</div>
            <div class="edit-header-sub">Ces informations sont visibles par tous les membres</div>
        </div>
    </div>

    <div class="edit-body">

        @if(session('status') === 'profile-updated')
            <div class="status-saved" style="display:flex;align-items:center;gap:6px;"><x-icon name="check-circle" :size="15"/> Profil mis à jour avec succès !</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- ── PHOTO ── -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="edit-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
                    <div>
                        <div class="edit-card-title">Photos</div>
                        <div class="edit-card-desc">Avatar et photo de couverture</div>
                    </div>
                </div>
                <div class="edit-card-body">

                    <!-- Avatar -->
                    <div class="avatar-upload-area" style="margin-bottom:16px;">
                        <div class="avatar-preview">
                            <div class="avatar-preview-inner">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" id="avatarPreview">
                                @else
                                    <span id="avatarInitial">{{ mb_strtoupper(mb_substr(auth()->user()->name,0,1)) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="avatar-upload-info">
                            <div class="avatar-upload-title">Photo de profil</div>
                            <div class="avatar-upload-hint">JPG, PNG ou GIF · Max 2 Mo<br>Recommandé : 400×400 px</div>
                        </div>
                        <label for="avatar" class="btn-upload" style="display:inline-flex;align-items:center;gap:6px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Choisir</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
                    </div>

                    <!-- Cover -->
                    <div class="form-group">
                        <label class="form-label">Photo de couverture</label>
                        <label for="cover_photo" class="cover-upload-area" id="coverArea">
                            @if(auth()->user()->cover_photo)
                                <img src="{{ Storage::url(auth()->user()->cover_photo) }}" id="coverPreview">
                                <div class="cover-overlay">
                                    <div class="cover-upload-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></div>
                                    <div class="cover-upload-text">Modifier la couverture</div>
                                </div>
                            @else
                                <div class="cover-upload-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
                                <div class="cover-upload-text">Cliquer pour ajouter une photo de couverture</div>
                            @endif
                        </label>
                        <input type="file" id="cover_photo" name="cover_photo" accept="image/*" style="display:none;" onchange="previewCover(this)">
                    </div>
                </div>
            </div>

            <!-- ── IDENTITÉ ── -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="edit-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                    <div>
                        <div class="edit-card-title">Identité</div>
                        <div class="edit-card-desc">Nom, pseudo et biographie</div>
                    </div>
                </div>
                <div class="edit-card-body">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="name">Nom complet <span>*</span></label>
                            <input class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                                type="text" id="name" name="name"
                                value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')<div class="form-error" style="display:flex;align-items:center;gap:5px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> {{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="username">Pseudo <span>*</span></label>
                            <div class="input-group">
                                <span class="input-prefix">@</span>
                                <input class="form-input {{ $errors->has('username') ? 'is-error' : '' }}"
                                    type="text" id="username" name="username"
                                    value="{{ old('username', auth()->user()->username) }}" required>
                            </div>
                            @error('username')<div class="form-error" style="display:flex;align-items:center;gap:5px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Adresse email <span>*</span></label>
                        <input class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                            type="email" id="email" name="email"
                            value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<div class="form-error" style="display:flex;align-items:center;gap:5px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> {{ $message }}</div>@enderror
                        @if(auth()->user()->email_verified_at)
                            <div class="form-hint" style="color:#6DC48A;display:flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Email vérifié</div>
                        @else
                            <div class="form-hint" style="color:#E09020;display:flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Email non vérifié</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="bio">Biographie <span style="font-weight:400;color:var(--text-muted);">(Français)</span></label>
                        <textarea class="form-input" id="bio" name="bio"
                            placeholder="Parle de toi, de ton univers créatif...">{{ old('bio', auth()->user()->bio) }}</textarea>
                        <div class="form-hint">Max 200 caractères</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="bio_wolof">Biographie <span style="font-weight:400;color:var(--text-muted);">(Wolof — optionnel)</span></label>
                        <textarea class="form-input" id="bio_wolof" name="bio_wolof"
                            placeholder="Bul ko xamante, wolof bi rekk...">{{ old('bio_wolof', auth()->user()->bio_wolof) }}</textarea>
                        <div class="form-hint">Max 200 caractères — touche ton audience wolofophone directement</div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="location">Localisation</label>
                            <input class="form-input" type="text" id="location" name="location"
                                placeholder="Dakar, Sénégal"
                                value="{{ old('location', auth()->user()->location) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="website">Site web</label>
                            <input class="form-input" type="url" id="website" name="website"
                                placeholder="https://tonsite.com"
                                value="{{ old('website', auth()->user()->website) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── RÉSEAUX SOCIAUX ── -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="edit-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></div>
                    <div>
                        <div class="edit-card-title">Réseaux sociaux</div>
                        <div class="edit-card-desc">Tes autres plateformes</div>
                    </div>
                </div>
                <div class="edit-card-body">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="instagram">Instagram</label>
                            <div class="social-input-group">
                                <span class="social-input-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></span>
                                <input class="form-input" type="text" id="instagram" name="instagram"
                                    placeholder="tonpseudo"
                                    value="{{ old('instagram', auth()->user()->instagram) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="tiktok">TikTok</label>
                            <div class="social-input-group">
                                <span class="social-input-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z"/></svg></span>
                                <input class="form-input" type="text" id="tiktok" name="tiktok"
                                    placeholder="tonpseudo"
                                    value="{{ old('tiktok', auth()->user()->tiktok) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="youtube">YouTube</label>
                            <div class="social-input-group">
                                <span class="social-input-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg></span>
                                <input class="form-input" type="text" id="youtube" name="youtube"
                                    placeholder="@tachaine"
                                    value="{{ old('youtube', auth()->user()->youtube) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="twitter">X / Twitter</label>
                            <div class="social-input-group">
                                <span class="social-input-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></span>
                                <input class="form-input" type="text" id="twitter" name="twitter"
                                    placeholder="tonpseudo"
                                    value="{{ old('twitter', auth()->user()->twitter) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── CONFIDENTIALITÉ ── -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="edit-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
                    <div>
                        <div class="edit-card-title">Confidentialité</div>
                        <div class="edit-card-desc">Visibilité de ton profil</div>
                    </div>
                </div>
                <div class="edit-card-body">
                    <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;gap:16px;">
                        <div>
                            <div style="font-size:.88rem;font-weight:600;color:var(--text);margin-bottom:3px;">Profil privé</div>
                            <div style="font-size:.76rem;color:var(--text-muted);">Seul toi pourras voir ton profil. Les autres verront une page "Profil privé".</div>
                        </div>
                        <div style="position:relative;display:inline-block;width:46px;height:26px;flex-shrink:0;">
                            <input type="hidden" name="is_private" value="0">
                            <input type="checkbox" name="is_private" value="1" id="is_private"
                                {{ auth()->user()->is_private ? 'checked' : '' }}
                                style="opacity:0;width:0;height:0;position:absolute;">
                            <span id="toggleTrack" style="position:absolute;inset:0;border-radius:100px;background:{{ auth()->user()->is_private ? 'var(--terra)' : 'var(--bg-card2)' }};border:1px solid {{ auth()->user()->is_private ? 'rgba(200,82,42,.4)' : 'var(--border)' }};transition:background .2s;"></span>
                            <span id="toggleThumb" style="position:absolute;width:18px;height:18px;border-radius:50%;background:{{ auth()->user()->is_private ? 'var(--terra)' : 'var(--text-faint)' }};top:50%;transform:translate({{ auth()->user()->is_private ? '22px' : '3px' }},-50%);transition:transform .2s,background .2s;"></span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- ── BOUTONS ── -->
            <div class="edit-footer">
                <a href="{{ route('profile.show', auth()->user()->username) }}" class="btn-cancel">Annuler</a>
                <button type="submit" class="btn-save" style="display:inline-flex;align-items:center;gap:6px;"><x-icon name="check" :size="15"/> Sauvegarder</button>
            </div>
        </form>

        <!-- ── DANGER ZONE ── -->
        <div class="edit-card danger-zone">
            <div class="edit-card-header">
                <div class="edit-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                <div>
                    <div class="edit-card-title">Zone dangereuse</div>
                    <div class="edit-card-desc">Actions irréversibles</div>
                </div>
            </div>
            <div class="edit-card-body">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                    <div>
                        <div style="font-size:.88rem;font-weight:600;color:var(--text);margin-bottom:3px;">Supprimer mon compte</div>
                        <div style="font-size:.78rem;color:var(--text-muted);">Toutes tes données seront définitivement supprimées.</div>
                    </div>
                    <form method="POST" action="{{ route('profile.destroy') }}"
                        onsubmit="return confirm('Es-tu sûr ? Cette action est irréversible.')">
                        @csrf @method('DELETE')
                        <input type="hidden" name="password" value="">
                        <button type="submit" class="btn-danger">Supprimer mon compte</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview avatar
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const inner = document.querySelector('.avatar-preview-inner');
                inner.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Preview cover
    function previewCover(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const area = document.getElementById('coverArea');
                area.innerHTML = `
                    <img src="${e.target.result}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                    <div class="cover-overlay" style="position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;gap:6px;background:rgba(0,0,0,0.4);padding:8px 16px;border-radius:8px;">
                        <div><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></div>
                        <div style="font-size:.78rem;color:white;">Modifier</div>
                    </div>`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Toggle profil privé
    const privToggle = document.getElementById('is_private');
    if (privToggle) {
        privToggle.addEventListener('change', function() {
            const track = document.getElementById('toggleTrack');
            const thumb = document.getElementById('toggleThumb');
            if (this.checked) {
                track.style.background = 'var(--terra)';
                track.style.borderColor = 'rgba(200,82,42,.4)';
                thumb.style.background = 'var(--terra)';
                thumb.style.transform = 'translate(22px,-50%)';
            } else {
                track.style.background = 'var(--bg-card2)';
                track.style.borderColor = 'var(--border)';
                thumb.style.background = 'var(--text-faint)';
                thumb.style.transform = 'translate(3px,-50%)';
            }
        });
    }

    // Compteur bio
    const bio = document.getElementById('bio');
    if (bio) {
        bio.addEventListener('input', function() {
            const hint = this.nextElementSibling;
            const len = this.value.length;
            hint.textContent = `${len}/200 caractères`;
            hint.style.color = len > 180 ? 'var(--terra)' : 'var(--text-muted)';
        });
    }
</script>
@endpush
