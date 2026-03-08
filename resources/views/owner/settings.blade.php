@extends('owner.layout')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres plateforme')

@section('content')

<div style="max-width:680px;">

    @if($errors->any())
        <div class="owner-flash error">✗ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('owner.settings.update') }}">
        @csrf @method('PATCH')

        {{-- Mode maintenance --}}
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:20px;">
            <div style="font-family:var(--font-head);font-size:.88rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);margin-bottom:18px;">
                🔧 Disponibilité
            </div>

            <div class="toggle-row">
                <div class="toggle-label">
                    Mode maintenance
                    <small>Le site affiche une page de maintenance aux visiteurs non connectés</small>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ $settings['maintenance_mode'] === '1' ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="toggle-row">
                <div class="toggle-label">
                    Inscriptions ouvertes
                    <small>Autoriser les nouveaux utilisateurs à s'inscrire</small>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="allow_registrations" value="1" {{ $settings['allow_registrations'] === '1' ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        {{-- Annonce globale --}}
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:20px;">
            <div style="font-family:var(--font-head);font-size:.88rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);margin-bottom:18px;">
                📢 Annonce globale
            </div>

            <div class="field">
                <label>Message d'annonce <span style="font-weight:400;text-transform:none;">(laissez vide pour désactiver)</span></label>
                <textarea name="site_announcement" placeholder="Ex: Maintenance prévue samedi 15h–16h. Merci pour votre patience." maxlength="500">{{ old('site_announcement', $settings['site_announcement']) }}</textarea>
                <div style="font-size:.72rem;color:var(--text-faint);margin-top:4px;">Max 500 caractères. Affiché en bannière sur tout le site.</div>
            </div>

            <div class="field">
                <label>Type d'annonce</label>
                <select name="site_announcement_type">
                    <option value="info"    {{ ($settings['site_announcement_type'] ?? 'info') === 'info'    ? 'selected' : '' }}>ℹ️ Information</option>
                    <option value="warning" {{ ($settings['site_announcement_type'] ?? '') === 'warning' ? 'selected' : '' }}>⚠️ Avertissement</option>
                    <option value="success" {{ ($settings['site_announcement_type'] ?? '') === 'success' ? 'selected' : '' }}>✓ Succès / Bonne nouvelle</option>
                </select>
            </div>

            {{-- Préview de l'annonce si active --}}
            @if($settings['site_announcement'])
                <div style="margin-top:12px;padding:12px 16px;border-radius:10px;font-size:.84rem;
                    {{ $settings['site_announcement_type'] === 'warning' ? 'background:rgba(212,168,67,.1);border:1px solid rgba(212,168,67,.3);color:var(--gold);' : '' }}
                    {{ $settings['site_announcement_type'] === 'success' ? 'background:rgba(45,90,61,.1);border:1px solid rgba(45,90,61,.25);color:#3D8A58;' : '' }}
                    {{ $settings['site_announcement_type'] === 'info'    ? 'background:var(--owner-soft);border:1px solid rgba(123,63,212,.25);color:var(--owner);' : '' }}">
                    <strong>Aperçu :</strong> {{ $settings['site_announcement'] }}
                </div>
            @endif
        </div>

        <button type="submit" class="btn-owner">💾 Sauvegarder les paramètres</button>
    </form>
</div>

@endsection
