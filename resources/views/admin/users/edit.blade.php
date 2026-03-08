@extends('admin.layout')

@section('title', 'Éditer ' . $user->name)
@section('page-title', 'Éditer · ' . $user->name)

@push('styles')
<style>
    .edit-grid { display:grid;grid-template-columns:1fr 1fr;gap:20px; }
    .edit-card { background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px; }
    .edit-card-title { font-family:var(--font-head);font-size:.88rem;font-weight:700;margin-bottom:18px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em; }
    .field { margin-bottom:16px; }
    .field label { display:block;font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px; }
    .field input, .field select { width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.88rem;outline:none;transition:border-color .2s; }
    .field input:focus, .field select:focus { border-color:var(--terra); }
    .toggle-row { display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border); }
    .toggle-row:last-child { border-bottom:none; }
    .toggle-label { font-size:.84rem; }
    .toggle-label small { display:block;font-size:.72rem;color:var(--text-muted); }
    .btn-save-admin { background:var(--terra);border:none;color:white;padding:11px 24px;border-radius:100px;font-family:var(--font-head);font-size:.9rem;font-weight:700;cursor:pointer;transition:background .2s; }
    .btn-save-admin:hover { background:var(--accent); }
</style>
@endpush

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.users') }}" style="color:var(--text-muted);text-decoration:none;font-size:.84rem;">← Retour aux utilisateurs</a>
</div>

@if($errors->any())
    <div class="admin-flash error">✗ {{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('admin.users.update', $user->username) }}">
    @csrf @method('PATCH')

    <div class="edit-grid">

        {{-- Identité --}}
        <div class="edit-card">
            <div class="edit-card-title">Identité</div>

            <div class="field">
                <label>Nom complet</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="field">
                <label>Rôle</label>
                <select name="role">
                    <option value="user"    {{ $user->role === 'user'    ? 'selected' : '' }}>User</option>
                    <option value="creator" {{ $user->role === 'creator' ? 'selected' : '' }}>Creator</option>
                    @if(auth()->user()->isOwner())
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>Owner</option>
                    @endif
                </select>
            </div>
            <div class="field">
                <label>Plan</label>
                <select name="plan">
                    <option value="free" {{ ($user->plan === 'free' || !$user->plan) ? 'selected' : '' }}>free</option>
                    <option value="african" {{ $user->plan === 'african' ? 'selected' : '' }}>african</option>
                    <option value="global"  {{ $user->plan === 'global'  ? 'selected' : '' }}>global</option>
                </select>
            </div>
            <div class="field">
                <label>Type pays</label>
                <select name="country_type">
                    <option value=""           {{ !$user->country_type ? 'selected' : '' }}>Non défini</option>
                    <option value="senegal"    {{ $user->country_type === 'senegal'       ? 'selected' : '' }}>Sénégal</option>
                    <option value="africa"     {{ $user->country_type === 'africa'        ? 'selected' : '' }}>Afrique</option>
                    <option value="international" {{ $user->country_type === 'international' ? 'selected' : '' }}>International</option>
                </select>
            </div>
        </div>

        {{-- Statuts --}}
        <div class="edit-card">
            <div class="edit-card-title">Statuts & permissions</div>

            <div class="toggle-row">
                <div class="toggle-label">
                    Compte actif
                    <small>L'utilisateur peut se connecter</small>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="toggle-row">
                <div class="toggle-label">
                    Badge vérifié
                    <small>Affiche le badge ✓ sur le profil</small>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', $user->is_verified) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            {{-- Infos lecture seule --}}
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                <div style="font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-faint);margin-bottom:12px;">Infos</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div style="font-size:.78rem;color:var(--text-muted);">Publications<br><strong style="color:var(--text);">{{ $user->posts()->count() }}</strong></div>
                    <div style="font-size:.78rem;color:var(--text-muted);">Abonnés<br><strong style="color:var(--text);">{{ $user->followers()->count() }}</strong></div>
                    <div style="font-size:.78rem;color:var(--text-muted);">Inscrit le<br><strong style="color:var(--text);">{{ $user->created_at->format('d/m/Y') }}</strong></div>
                    <div style="font-size:.78rem;color:var(--text-muted);">Niche<br><strong style="color:var(--text);">{{ $user->niche ?: '—' }}</strong></div>
                </div>
            </div>
        </div>

    </div>

    <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:12px;">
        <a href="{{ route('admin.users') }}" style="padding:11px 24px;border-radius:100px;border:1px solid var(--border);color:var(--text-muted);text-decoration:none;font-size:.88rem;">Annuler</a>
        <button type="submit" class="btn-save-admin">💾 Sauvegarder</button>
    </div>
</form>

@push('styles')
<style>
    .toggle-switch { position:relative;display:inline-block;width:42px;height:24px; }
    .toggle-switch input { opacity:0;width:0;height:0; }
    .toggle-slider { position:absolute;cursor:pointer;inset:0;background:var(--bg-card2);border:1px solid var(--border);border-radius:100px;transition:background .2s; }
    .toggle-slider::before { content:'';position:absolute;width:16px;height:16px;border-radius:50%;background:var(--text-faint);left:3px;top:50%;transform:translateY(-50%);transition:transform .2s,background .2s; }
    .toggle-switch input:checked + .toggle-slider { background:var(--terra-soft);border-color:rgba(200,82,42,.4); }
    .toggle-switch input:checked + .toggle-slider::before { transform:translate(18px,-50%);background:var(--terra); }
</style>
@endpush

@endsection
