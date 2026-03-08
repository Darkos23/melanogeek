@extends('admin.layout')

@section('title', 'Page À propos')
@section('page-title', 'Page À propos')

@section('content')

<div style="max-width:760px;">

    @if($errors->any())
        <div class="admin-flash error">✗ {{ $errors->first() }}</div>
    @endif

    <div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div style="font-size:.84rem;color:var(--text-muted);">
            Ce contenu est affiché sur la page publique
            <a href="{{ route('about') }}" target="_blank" style="color:var(--terra);text-decoration:none;font-weight:600;">
                /a-propos ↗
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.about.update') }}">
        @csrf @method('PATCH')

        {{-- Titre --}}
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:16px;">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:18px;">🏷️ Identité</div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;">Titre de la page</label>
                <input type="text" name="about_title" value="{{ old('about_title', $settings['about_title']) }}"
                    placeholder="À propos de MelanoGeek"
                    maxlength="120"
                    style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.92rem;outline:none;transition:border-color .2s;box-sizing:border-box;">
            </div>

            <div>
                <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;">Tagline / Accroche</label>
                <input type="text" name="about_tagline" value="{{ old('about_tagline', $settings['about_tagline']) }}"
                    placeholder="La plateforme des créateurs africains"
                    maxlength="240"
                    style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.88rem;outline:none;transition:border-color .2s;box-sizing:border-box;">
                <div style="font-size:.7rem;color:var(--text-faint);margin-top:4px;">Affiché en sous-titre. Max 240 caractères.</div>
            </div>
        </div>

        {{-- Mission --}}
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:16px;">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:18px;">🎯 Mission</div>
            <textarea name="about_mission" rows="4" maxlength="2000"
                placeholder="Notre mission est de créer un espace dédié aux créateurs africains…"
                style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.88rem;resize:vertical;outline:none;box-sizing:border-box;line-height:1.6;">{{ old('about_mission', $settings['about_mission']) }}</textarea>
            <div style="font-size:.7rem;color:var(--text-faint);margin-top:4px;">Max 2000 caractères.</div>
        </div>

        {{-- Histoire --}}
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:16px;">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:18px;">📖 Notre histoire</div>
            <textarea name="about_story" rows="6" maxlength="5000"
                placeholder="MelanoGeek est née de la conviction que les créateurs africains méritent leur propre plateforme…"
                style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.88rem;resize:vertical;outline:none;box-sizing:border-box;line-height:1.6;">{{ old('about_story', $settings['about_story']) }}</textarea>
            <div style="font-size:.7rem;color:var(--text-faint);margin-top:4px;">Max 5000 caractères.</div>
        </div>

        {{-- Valeurs --}}
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:24px;">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:18px;">✨ Nos valeurs</div>
            <textarea name="about_values" rows="4" maxlength="3000"
                placeholder="Authenticité, Communauté, Créativité…"
                style="width:100%;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.88rem;resize:vertical;outline:none;box-sizing:border-box;line-height:1.6;">{{ old('about_values', $settings['about_values']) }}</textarea>
            <div style="font-size:.7rem;color:var(--text-faint);margin-top:4px;">Max 3000 caractères.</div>
        </div>

        <div style="display:flex;gap:12px;align-items:center;">
            <button type="submit" style="background:var(--terra);color:white;border:none;padding:11px 28px;border-radius:100px;font-family:var(--font-head);font-size:.88rem;font-weight:700;cursor:pointer;transition:background .2s;">
                💾 Sauvegarder
            </button>
            <a href="{{ route('about') }}" target="_blank" style="font-size:.84rem;color:var(--text-muted);text-decoration:none;">
                Voir la page publique ↗
            </a>
        </div>
    </form>

</div>

@endsection
