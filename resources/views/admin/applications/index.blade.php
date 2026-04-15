@extends('admin.layout')

@section('title', 'Candidatures')

@section('content')

@php $total = max(1, $pending->total() + $approved + $rejected); @endphp

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,200px),1fr));gap:16px;margin-bottom:28px;">
    <div style="background:var(--bg-card);border:1px solid rgba(212,168,67,.25);border-radius:18px;padding:22px 24px;position:relative;overflow:hidden;">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 0% 0%,rgba(212,168,67,.08),transparent 60%);pointer-events:none;"></div>
        <div style="display:flex;align-items:flex-start;justify-content:space-between;position:relative;">
            <div>
                <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:10px;">En attente</div>
                <div style="font-family:var(--font-head);font-size:2.4rem;font-weight:800;color:var(--gold);line-height:1;">{{ $pending->total() }}</div>
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:6px;">candidature{{ $pending->total() > 1 ? 's' : '' }} a traiter</div>
            </div>
            <div style="width:44px;height:44px;background:rgba(212,168,67,.12);border:1px solid rgba(212,168,67,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">...</div>
        </div>
        <div style="margin-top:16px;padding-top:14px;border-top:1px solid rgba(212,168,67,.12);position:relative;">
            <div style="height:4px;background:rgba(212,168,67,.1);border-radius:100px;">
                <div style="height:100%;width:{{ round($pending->total() / $total * 100) }}%;background:var(--gold);border-radius:100px;"></div>
            </div>
        </div>
    </div>

    <a href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}" style="text-decoration:none;">
        <div style="background:var(--bg-card);border:1px solid rgba(45,90,61,.3);border-radius:18px;padding:22px 24px;position:relative;overflow:hidden;transition:border-color .2s;{{ $currentStatus === 'approved' ? 'border-color:rgba(125,223,154,.4);' : '' }}">
            <div style="position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 0% 0%,rgba(45,90,61,.1),transparent 60%);pointer-events:none;"></div>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;position:relative;">
                <div>
                    <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:10px;">Approuvees</div>
                    <div style="font-family:var(--font-head);font-size:2.4rem;font-weight:800;color:#7DDF9A;line-height:1;">{{ $approved }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:6px;">createur{{ $approved > 1 ? 's' : '' }} actif{{ $approved > 1 ? 's' : '' }}</div>
                </div>
                <div style="width:44px;height:44px;background:rgba(45,90,61,.2);border:1px solid rgba(125,223,154,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">OK</div>
            </div>
            <div style="margin-top:16px;padding-top:14px;border-top:1px solid rgba(45,90,61,.2);position:relative;">
                <div style="height:4px;background:rgba(45,90,61,.15);border-radius:100px;">
                    <div style="height:100%;width:{{ round($approved / $total * 100) }}%;background:#7DDF9A;border-radius:100px;"></div>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}" style="text-decoration:none;">
        <div style="background:var(--bg-card);border:1px solid rgba(224,85,85,.2);border-radius:18px;padding:22px 24px;position:relative;overflow:hidden;transition:border-color .2s;{{ $currentStatus === 'rejected' ? 'border-color:rgba(224,85,85,.4);' : '' }}">
            <div style="position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 0% 0%,rgba(224,85,85,.07),transparent 60%);pointer-events:none;"></div>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;position:relative;">
                <div>
                    <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:10px;">Refusees</div>
                    <div style="font-family:var(--font-head);font-size:2.4rem;font-weight:800;color:#E05555;line-height:1;">{{ $rejected }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:6px;">candidature{{ $rejected > 1 ? 's' : '' }} refusee{{ $rejected > 1 ? 's' : '' }}</div>
                </div>
                <div style="width:44px;height:44px;background:rgba(224,85,85,.1);border:1px solid rgba(224,85,85,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">X</div>
            </div>
            <div style="margin-top:16px;padding-top:14px;border-top:1px solid rgba(224,85,85,.12);position:relative;">
                <div style="height:4px;background:rgba(224,85,85,.08);border-radius:100px;">
                    <div style="height:100%;width:{{ round($rejected / $total * 100) }}%;background:#E05555;border-radius:100px;"></div>
                </div>
            </div>
        </div>
    </a>
</div>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <div style="font-family:var(--font-head);font-size:1.1rem;font-weight:800;color:var(--text);">
            @if($currentStatus === 'pending') Candidatures en attente
            @elseif($currentStatus === 'approved') Candidatures approuvees
            @else Candidatures refusees
            @endif
        </div>
        <div style="font-size:.78rem;color:var(--text-muted);margin-top:3px;">
            {{ $pending->total() }} resultat{{ $pending->total() > 1 ? 's' : '' }}
        </div>
    </div>
    <div style="display:flex;gap:6px;background:var(--bg);border:1px solid var(--border);border-radius:12px;padding:4px;">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" style="padding:6px 14px;border-radius:8px;font-family:var(--font-head);font-size:.78rem;font-weight:700;text-decoration:none;transition:all .2s;{{ $currentStatus === 'pending' ? 'background:var(--bg-card);color:var(--gold);box-shadow:0 1px 4px rgba(0,0,0,.2);' : 'color:var(--text-muted);' }}">En attente</a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}" style="padding:6px 14px;border-radius:8px;font-family:var(--font-head);font-size:.78rem;font-weight:700;text-decoration:none;transition:all .2s;{{ $currentStatus === 'approved' ? 'background:var(--bg-card);color:#7DDF9A;box-shadow:0 1px 4px rgba(0,0,0,.2);' : 'color:var(--text-muted);' }}">Approuvees</a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}" style="padding:6px 14px;border-radius:8px;font-family:var(--font-head);font-size:.78rem;font-weight:700;text-decoration:none;transition:all .2s;{{ $currentStatus === 'rejected' ? 'background:var(--bg-card);color:#E05555;box-shadow:0 1px 4px rgba(0,0,0,.2);' : 'color:var(--text-muted);' }}">Refusees</a>
    </div>
</div>

@if(session('success'))
    <div class="alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
@endif

@if($pending->isEmpty())
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:18px;padding:60px 32px;text-align:center;">
        <div style="font-size:3rem;margin-bottom:16px;">@if($currentStatus === 'pending') * @elseif($currentStatus === 'approved') OK @else X @endif</div>
        <div style="font-family:var(--font-head);font-size:1rem;font-weight:700;color:var(--text);margin-bottom:8px;">
            @if($currentStatus === 'pending') Aucune candidature en attente
            @elseif($currentStatus === 'approved') Aucune candidature approuvee
            @else Aucune candidature refusee
            @endif
        </div>
        <div style="font-size:.83rem;color:var(--text-muted);">
            @if($currentStatus === 'pending') Tout est traite.
            @else Aucun resultat pour ce filtre.
            @endif
        </div>
    </div>
@else
    <div style="display:flex;flex-direction:column;gap:14px;">
        @foreach($pending as $user)
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;">
                <div style="display:flex;align-items:flex-start;gap:20px;flex-wrap:wrap;">
                    <div style="width:52px;height:52px;border-radius:50%;background:var(--terra-soft);border:2px solid var(--border);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-family:var(--font-head);font-weight:800;color:var(--terra);">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>

                    <div style="flex:1;min-width:200px;">
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px;">
                            <span style="font-family:var(--font-head);font-size:1rem;font-weight:800;color:var(--text);">{{ $user->name }}</span>
                            <span style="font-size:.78rem;color:var(--text-muted);">@{{ $user->username }}</span>
                            @if($user->creator_category)
                                <span style="background:rgba(212,168,67,.15);color:var(--gold);font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:100px;text-transform:uppercase;">{{ $user->creator_category }}</span>
                            @endif
                            @if($currentStatus === 'approved')
                                <span style="background:rgba(45,90,61,.2);color:#7DDF9A;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:100px;">Approuve</span>
                            @elseif($currentStatus === 'rejected')
                                <span style="background:rgba(224,85,85,.12);color:#E05555;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:100px;">Refuse</span>
                            @endif
                        </div>
                        <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:10px;">{{ $user->email }} · Inscrit {{ $user->created_at->diffForHumans() }}</div>

                        @if($user->creator_bio)
                            <div style="background:var(--bg);border-radius:10px;padding:12px 14px;font-size:.85rem;color:var(--text-muted);line-height:1.6;margin-bottom:10px;border-left:3px solid rgba(212,168,67,.25);">
                                "{{ $user->creator_bio }}"
                            </div>
                        @endif

                        @if($user->creator_socials)
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                @foreach($user->creator_socials as $link)
                                    <a href="{{ $link }}" target="_blank" style="font-size:.78rem;color:var(--gold);background:var(--gold-soft);padding:4px 10px;border-radius:8px;text-decoration:none;">Voir le contenu</a>
                                @endforeach
                            </div>
                        @endif

                        @if($currentStatus === 'rejected' && $user->rejection_reason)
                            <div style="margin-top:10px;background:rgba(224,85,85,.07);border:1px solid rgba(224,85,85,.2);border-radius:10px;padding:10px 14px;font-size:.8rem;color:#E05555;">
                                <strong>Motif :</strong> {{ $user->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    @if($currentStatus === 'pending')
                        <div style="display:flex;flex-direction:column;gap:8px;flex-shrink:0;">
                            <form method="POST" action="{{ route('admin.applications.approve', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" style="width:140px;padding:9px 16px;background:#2D5A3D;color:#7DDF9A;border:1px solid rgba(125,223,154,.25);border-radius:10px;font-family:var(--font-head);font-size:.82rem;font-weight:700;cursor:pointer;">
                                    Approuver
                                </button>
                            </form>
                            <button onclick="toggleRejectForm({{ $user->id }})" style="width:140px;padding:9px 16px;background:rgba(224,85,85,.1);color:#E05555;border:1px solid rgba(224,85,85,.25);border-radius:10px;font-family:var(--font-head);font-size:.82rem;font-weight:700;cursor:pointer;">
                                Refuser
                            </button>
                        </div>
                    @endif
                </div>

                @if($currentStatus === 'pending')
                    <div id="reject-form-{{ $user->id }}" style="display:none;margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                        <form method="POST" action="{{ route('admin.applications.reject', $user) }}">
                            @csrf @method('PATCH')
                            <div style="margin-bottom:10px;">
                                <label style="font-size:.76rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:6px;">Motif du refus (optionnel)</label>
                                <textarea name="rejection_reason" rows="2" style="width:100%;background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:var(--font-body);font-size:.88rem;resize:vertical;outline:none;" placeholder="Ex: Profil incomplet, contenu non conforme..."></textarea>
                            </div>
                            <button type="submit" style="padding:8px 20px;background:rgba(224,85,85,.15);color:#E05555;border:1px solid rgba(224,85,85,.3);border-radius:10px;font-family:var(--font-head);font-size:.82rem;font-weight:700;cursor:pointer;">
                                Confirmer le refus
                            </button>
                            <button type="button" onclick="toggleRejectForm({{ $user->id }})" style="padding:8px 16px;background:transparent;border:1px solid var(--border);border-radius:10px;font-family:var(--font-head);font-size:.82rem;color:var(--text-muted);cursor:pointer;margin-left:8px;">
                                Annuler
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div style="margin-top:24px;">
        {{ $pending->links() }}
    </div>
@endif

<script>
function toggleRejectForm(id) {
    const form = document.getElementById('reject-form-' + id);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection
