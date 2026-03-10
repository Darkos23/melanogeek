@extends('layouts.app')

@section('title', 'Modifier mon profil')

@push('styles')
<style>
    .edit-page { padding-top: 80px; min-height: 100vh; }

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

    /* ── NICHE SELECT ── */
    .niche-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-top: 4px;
    }
    @media (min-width: 600px) {
        .niche-grid { grid-template-columns: repeat(5, 1fr); }
    }
    .niche-option { position: relative; }
    .niche-option input[type="radio"] { position:absolute;opacity:0;width:0;height:0; }
    .niche-option label {
        display: flex; flex-direction: column; align-items: center; gap: 4px;
        padding: 12px 8px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: var(--bg-card2);
        cursor: pointer;
        transition: all .2s;
        text-align: center;
    }
    .niche-option label:hover { border-color: rgba(212,168,67,.4); }
    .niche-option input:checked + label {
        border-color: var(--terra);
        background: var(--terra-soft);
        box-shadow: 0 0 0 2px rgba(200,82,42,.12);
    }
    .niche-emoji { font-size: 1.3rem; }
    .niche-label { font-size: .68rem; font-weight: 600; color: var(--text-muted); }

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
        color: #3D8A58;
        padding: 10px 16px;
        border-radius: 12px;
        font-size: .84rem;
        font-weight: 500;
    }
    [data-theme="dark"] .status-saved { color: #6DC48A; }

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
        .niche-grid { grid-template-columns: repeat(3, 1fr); }
        .avatar-upload-area { flex-direction: column; text-align: center; }
    }
</style>
@endpush

@section('content')
<div class="edit-page">

    <!-- Header -->
    <div class="edit-header">
        <a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('profile.show', auth()->user()->username) }}" class="edit-header-back">←</a>
        <div>
            <div class="edit-header-title">Modifier mon profil</div>
            <div class="edit-header-sub">Ces informations sont visibles par tous les membres</div>
        </div>
    </div>

    <div class="edit-body">

        @if(session('status') === 'profile-updated')
            <div class="status-saved">✓ Profil mis à jour avec succès !</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- ── PHOTO ── -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="edit-card-icon">📸</div>
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
                        <label for="avatar" class="btn-upload">📁 Choisir</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
                    </div>

                    <!-- Cover -->
                    <div class="form-group">
                        <label class="form-label">Photo de couverture</label>
                        <label for="cover_photo" class="cover-upload-area" id="coverArea">
                            @if(auth()->user()->cover_photo)
                                <img src="{{ Storage::url(auth()->user()->cover_photo) }}" id="coverPreview">
                                <div class="cover-overlay">
                                    <div class="cover-upload-icon">✎</div>
                                    <div class="cover-upload-text">Modifier la couverture</div>
                                </div>
                            @else
                                <div class="cover-upload-icon">🖼</div>
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
                    <div class="edit-card-icon">👤</div>
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
                            @error('name')<div class="form-error">⚠ {{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="username">Pseudo <span>*</span></label>
                            <div class="input-group">
                                <span class="input-prefix">@</span>
                                <input class="form-input {{ $errors->has('username') ? 'is-error' : '' }}"
                                    type="text" id="username" name="username"
                                    value="{{ old('username', auth()->user()->username) }}" required>
                            </div>
                            @error('username')<div class="form-error">⚠ {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Adresse email <span>*</span></label>
                        <input class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                            type="email" id="email" name="email"
                            value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<div class="form-error">⚠ {{ $message }}</div>@enderror
                        @if(auth()->user()->email_verified_at)
                            <div class="form-hint" style="color:#3D8A58;">✓ Email vérifié</div>
                        @else
                            <div class="form-hint" style="color:#E09020;">⚠ Email non vérifié</div>
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

            <!-- ── NICHE ── -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="edit-card-icon">🎨</div>
                    <div>
                        <div class="edit-card-title">Ta niche créative</div>
                        <div class="edit-card-desc">Quel type de contenu tu crées ?</div>
                    </div>
                </div>
                <div class="edit-card-body">
                    <div class="niche-grid">
                        @foreach([
                            ['val'=>'Musique',           'emoji'=>'🎵'],
                            ['val'=>'Photographie',      'emoji'=>'📸'],
                            ['val'=>'Mode & Style',      'emoji'=>'👗'],
                            ['val'=>'Beauté & Soins',    'emoji'=>'💄'],
                            ['val'=>'Cuisine',           'emoji'=>'🍽️'],
                            ['val'=>'Vidéo & Vlog',      'emoji'=>'🎬'],
                            ['val'=>'Art & Illustration','emoji'=>'🎨'],
                            ['val'=>'Danse',             'emoji'=>'💃'],
                            ['val'=>'Comédie & Humour',  'emoji'=>'😂'],
                            ['val'=>'Business',          'emoji'=>'💼'],
                            ['val'=>'Voyage & Culture',  'emoji'=>'🌍'],
                            ['val'=>'Sport & Fitness',   'emoji'=>'⚽'],
                            ['val'=>'Artisanat',         'emoji'=>'🪡'],
                            ['val'=>'Éducation',         'emoji'=>'📚'],
                            ['val'=>'Podcast',           'emoji'=>'🎙️'],
                            ['val'=>'Lifestyle',         'emoji'=>'✨'],
                        ] as $n)
                        <div class="niche-option">
                            <input type="radio" id="niche_{{ $loop->index }}" name="niche"
                                value="{{ $n['val'] }}"
                                {{ old('niche', auth()->user()->niche) === $n['val'] ? 'checked' : '' }}>
                            <label for="niche_{{ $loop->index }}">
                                <span class="niche-emoji">{{ $n['emoji'] }}</span>
                                <span class="niche-label">{{ $n['val'] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ── RÉSEAUX SOCIAUX ── -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="edit-card-icon">🔗</div>
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
                                <span class="social-input-icon">📸</span>
                                <input class="form-input" type="text" id="instagram" name="instagram"
                                    placeholder="tonpseudo"
                                    value="{{ old('instagram', auth()->user()->instagram) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="tiktok">TikTok</label>
                            <div class="social-input-group">
                                <span class="social-input-icon">🎵</span>
                                <input class="form-input" type="text" id="tiktok" name="tiktok"
                                    placeholder="tonpseudo"
                                    value="{{ old('tiktok', auth()->user()->tiktok) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="youtube">YouTube</label>
                            <div class="social-input-group">
                                <span class="social-input-icon">▶️</span>
                                <input class="form-input" type="text" id="youtube" name="youtube"
                                    placeholder="@tachaine"
                                    value="{{ old('youtube', auth()->user()->youtube) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="twitter">X / Twitter</label>
                            <div class="social-input-group">
                                <span class="social-input-icon">✖</span>
                                <input class="form-input" type="text" id="twitter" name="twitter"
                                    placeholder="tonpseudo"
                                    value="{{ old('twitter', auth()->user()->twitter) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── PAIEMENT MOBILE ── -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="edit-card-icon">💸</div>
                    <div>
                        <div class="edit-card-title">Paiement mobile</div>
                        <div class="edit-card-desc">Numéros pour recevoir des paiements</div>
                    </div>
                </div>
                <div class="edit-card-body">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="wave_number">Wave</label>
                            <div class="social-input-group">
                                <span class="social-input-icon">〰</span>
                                <input class="form-input {{ $errors->has('wave_number') ? 'is-error' : '' }}"
                                    type="tel" id="wave_number" name="wave_number"
                                    placeholder="+221 77 000 00 00"
                                    value="{{ old('wave_number', auth()->user()->wave_number) }}">
                            </div>
                            @error('wave_number')<div class="form-error">⚠ {{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="orange_money_number">Orange Money</label>
                            <div class="social-input-group">
                                <span class="social-input-icon">🟠</span>
                                <input class="form-input {{ $errors->has('orange_money_number') ? 'is-error' : '' }}"
                                    type="tel" id="orange_money_number" name="orange_money_number"
                                    placeholder="+221 77 000 00 00"
                                    value="{{ old('orange_money_number', auth()->user()->orange_money_number) }}">
                            </div>
                            @error('orange_money_number')<div class="form-error">⚠ {{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-hint">Ces numéros permettront à tes abonnés de te payer directement.</div>
                </div>
            </div>

            <!-- ── CONFIDENTIALITÉ ── -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <div class="edit-card-icon">🔒</div>
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
                <button type="submit" class="btn-save">💾 Sauvegarder</button>
            </div>
        </form>

        <!-- ── DANGER ZONE ── -->
        <div class="edit-card danger-zone">
            <div class="edit-card-header">
                <div class="edit-card-icon">⚠️</div>
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
                        <div style="font-size:1.2rem;">✎</div>
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