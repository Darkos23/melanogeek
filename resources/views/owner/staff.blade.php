@extends('owner.layout')

@section('title', 'Gestion du staff')
@section('page-title', 'Gestion du staff')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,340px),1fr));gap:24px;">

    {{-- Admins actuels --}}
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
    </div>

    {{-- Promouvoir un user --}}
    <div>
        <div class="owner-table-wrap">
            <div class="owner-table-header">
                <div class="owner-table-title">➕ Promouvoir en admin</div>
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
                    <div style="padding:12px 0;font-size:.78rem;color:var(--text-muted);line-height:1.6;">
                        ⚠️ L'admin aura accès au dashboard de modération. Il ne pourra pas gérer d'autres admins ni modifier les paramètres plateforme.
                    </div>
                    <button type="submit" class="btn-owner">🛡️ Promouvoir en admin</button>
                </form>
            </div>
        </div>

        {{-- Résumé permissions --}}
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:20px;margin-top:16px;">
            <div style="font-family:var(--font-head);font-size:.88rem;font-weight:700;margin-bottom:14px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">Permissions comparées</div>
            @php
            $perms = [
                ['label' => 'Dashboard modération',        'admin' => true,  'owner' => true],
                ['label' => 'Gérer users & posts',         'admin' => true,  'owner' => true],
                ['label' => 'Approuver creators',          'admin' => true,  'owner' => true],
                ['label' => 'Modifier rôle → creator',     'admin' => true,  'owner' => true],
                ['label' => 'Supprimer/éditer un admin',   'admin' => false, 'owner' => true],
                ['label' => 'Modifier rôle → admin/owner', 'admin' => false, 'owner' => true],
                ['label' => 'Finances avancées',           'admin' => false, 'owner' => true],
                ['label' => 'Paramètres plateforme',       'admin' => false, 'owner' => true],
                ['label' => 'Logs d\'activité',            'admin' => false, 'owner' => true],
                ['label' => 'Gestion du staff',            'admin' => false, 'owner' => true],
            ];
            @endphp
            <table>
                <thead>
                    <tr>
                        <th>Fonctionnalité</th>
                        <th style="text-align:center;">Admin</th>
                        <th style="text-align:center;">Owner</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perms as $p)
                    <tr>
                        <td style="font-size:.78rem;">{{ $p['label'] }}</td>
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
