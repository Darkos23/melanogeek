@extends('owner.layout')

@section('title', 'Gestion du staff')
@section('page-title', 'Gestion du staff')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,340px),1fr));gap:24px;">

    {{-- Admins actifs --}}
    <div>
        <div class="owner-table-wrap">
            <div class="owner-table-header">
                <div class="owner-table-title">🛡️ Admins actifs ({{ count($admins) }})</div>
            </div>
            @if($admins->isEmpty())
                <div style="padding:32px;text-align:center;color:var(--text-muted);font-size:.84rem;">
                    Aucun admin pour le moment.
                </div>
            @else
                <table>
                    <thead>
                        <tr><th>Utilisateur</th><th>Inscrit</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="user-avatar-mini">
                                        <div class="user-avatar-mini-inner">
                                            @if($admin->avatar)<img src="{{ Storage::url($admin->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($admin->name,0,1)) }}@endif
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:.84rem;">{{ $admin->name }}</div>
                                        <div style="font-size:.72rem;color:var(--text-muted);">&#64;{{ $admin->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:.75rem;color:var(--text-muted);">{{ $admin->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('owner.staff.revoke', $admin->username) }}" onsubmit="return confirm('Révoquer les droits admin de {{ $admin->name }} ?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action danger">✕ Révoquer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- CMs actifs --}}
        <div class="owner-table-wrap" style="margin-top:16px;">
            <div class="owner-table-header">
                <div class="owner-table-title" style="color:#2DB8A0;">🎯 Community Managers ({{ count($cms) }})</div>
            </div>
            @if($cms->isEmpty())
                <div style="padding:32px;text-align:center;color:var(--text-muted);font-size:.84rem;">
                    Aucun CM pour le moment.
                </div>
            @else
                <table>
                    <thead>
                        <tr><th>Utilisateur</th><th>Inscrit</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($cms as $cm)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="user-avatar-mini" style="background:linear-gradient(135deg,#2DB8A0,var(--gold));">
                                        <div class="user-avatar-mini-inner">
                                            @if($cm->avatar)<img src="{{ Storage::url($cm->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($cm->name,0,1)) }}@endif
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:.84rem;">{{ $cm->name }}</div>
                                        <div style="font-size:.72rem;color:var(--text-muted);">&#64;{{ $cm->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:.75rem;color:var(--text-muted);">{{ $cm->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('owner.staff.revoke', $cm->username) }}" onsubmit="return confirm('Révoquer les droits CM de {{ $cm->name }} ?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action danger">✕ Révoquer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Promouvoir un user --}}
    <div>
        <div class="owner-table-wrap">
            <div class="owner-table-header">
                <div class="owner-table-title">➕ Promouvoir un membre</div>
            </div>
            <div style="padding:20px;">
                <form method="POST" action="{{ route('owner.staff.promote') }}">
                    @csrf
                    @if($errors->any())
                        <div class="owner-flash error" style="margin-bottom:16px;">✗ {{ $errors->first() }}</div>
                    @endif
                    <div class="field">
                        <label>Choisir un utilisateur</label>
                        <select name="user_id" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} (&#64;{{ $u->username }}) — {{ ucfirst($u->role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field" style="margin-top:14px;">
                        <label>Rôle à attribuer</label>
                        <select name="role" required>
                            <option value="cm"    {{ old('role') === 'cm'    ? 'selected' : '' }}>🎯 Community Manager</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>⚙️ Admin</option>
                        </select>
                    </div>
                    <div style="padding:12px 0;font-size:.78rem;color:var(--text-muted);line-height:1.6;">
                        ⚠️ Le CM peut modérer posts, commentaires et signalements.<br>
                        L'admin a en plus accès aux utilisateurs, candidatures et abonnements.
                    </div>
                    <button type="submit" class="btn-owner">✦ Promouvoir</button>
                </form>
            </div>
        </div>

        {{-- Résumé permissions --}}
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:20px;margin-top:16px;">
            <div style="font-family:var(--font-head);font-size:.88rem;font-weight:700;margin-bottom:14px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">Permissions comparées</div>
            @php
            $perms = [
                ['label' => 'Dashboard modération',         'cm' => true,  'admin' => true,  'owner' => true],
                ['label' => 'Modérer posts & commentaires', 'cm' => true,  'admin' => true,  'owner' => true],
                ['label' => 'Traiter les signalements',     'cm' => true,  'admin' => true,  'owner' => true],
                ['label' => 'Gérer les utilisateurs',       'cm' => false, 'admin' => true,  'owner' => true],
                ['label' => 'Approuver creators',           'cm' => false, 'admin' => true,  'owner' => true],
                ['label' => 'Abonnements',                  'cm' => false, 'admin' => true,  'owner' => true],
                ['label' => 'Supprimer/éditer un admin',    'cm' => false, 'admin' => false, 'owner' => true],
                ['label' => 'Finances avancées',            'cm' => false, 'admin' => false, 'owner' => true],
                ['label' => 'Paramètres plateforme',        'cm' => false, 'admin' => false, 'owner' => true],
                ['label' => 'Logs d\'activité',             'cm' => false, 'admin' => false, 'owner' => true],
                ['label' => 'Gestion du staff',             'cm' => false, 'admin' => false, 'owner' => true],
            ];
            @endphp
            <table>
                <thead>
                    <tr>
                        <th>Fonctionnalité</th>
                        <th style="text-align:center;color:#2DB8A0;">CM</th>
                        <th style="text-align:center;">Admin</th>
                        <th style="text-align:center;">Owner</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perms as $p)
                    <tr>
                        <td style="font-size:.78rem;">{{ $p['label'] }}</td>
                        <td style="text-align:center;color:#2DB8A0;">{{ $p['cm']    ? '✓' : '—' }}</td>
                        <td style="text-align:center;">{{ $p['admin'] ? '✓' : '—' }}</td>
                        <td style="text-align:center;color:var(--owner);font-weight:700;">✓</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
