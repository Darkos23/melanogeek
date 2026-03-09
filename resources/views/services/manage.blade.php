@extends('layouts.app')
@section('title', 'Mes services — MelanoGeek')
@section('content')
<style>
.mgr-wrap { max-width: 860px; margin: 0 auto; padding: 96px 20px 80px; }
.mgr-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 28px; }
.mgr-header h1 { font-family: var(--font-head); font-size: 1.5rem; font-weight: 800; color: var(--text); }
.btn-terra { background: var(--terra); color: white; border: none; padding: 10px 20px; border-radius: 10px; font-family: var(--font-head); font-size: .88rem; font-weight: 700; text-decoration: none; transition: opacity .2s; }
.btn-terra:hover { opacity: .85; }
.alert-success { background: rgba(42,122,72,.12); border: 1px solid rgba(42,122,72,.3); color: #2A7A48; padding: 12px 16px; border-radius: 10px; font-size: .84rem; margin-bottom: 20px; }

.svc-table { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
.svc-table-overflow { overflow-x: auto; }
.svc-table-header { padding: 16px 20px; border-bottom: 1px solid var(--border); font-family: var(--font-head); font-size: .88rem; font-weight: 700; color: var(--text); }
.svc-row { display: grid; grid-template-columns: 56px 1fr auto auto auto; gap: 12px; align-items: center; padding: 14px 20px; border-bottom: 1px solid var(--border); transition: background .15s; min-width: 560px; }
.svc-row:last-child { border-bottom: none; }
.svc-row:hover { background: var(--bg-hover); }
@media (max-width: 600px) {
    .mgr-wrap { padding-top: 80px; }
    .mgr-header h1 { font-size: 1.2rem; }
}
.svc-thumb { width: 64px; height: 40px; border-radius: 8px; object-fit: cover; background: var(--bg-card2); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; overflow: hidden; flex-shrink: 0; }
.svc-thumb img { width: 100%; height: 100%; object-fit: cover; }
.svc-info-title { font-size: .88rem; font-weight: 600; color: var(--text); margin-bottom: 3px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.svc-info-sub { font-size: .74rem; color: var(--text-muted); }
.svc-orders-count { font-family: var(--font-head); font-size: 1rem; font-weight: 700; color: var(--text); text-align: center; }
.svc-orders-label { font-size: .68rem; color: var(--text-muted); text-align: center; }
.svc-status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 100px; font-size: .72rem; font-weight: 700; }
.badge-active { background: rgba(42,122,72,.12); color: #2A7A48; border: 1px solid rgba(42,122,72,.2); }
.badge-inactive { background: var(--bg-card2); color: var(--text-muted); border: 1px solid var(--border); }
.svc-actions { display: flex; gap: 6px; }
.btn-sm { padding: 5px 12px; border-radius: 8px; font-size: .75rem; font-weight: 600; text-decoration: none; border: 1px solid var(--border); color: var(--text-muted); background: transparent; transition: all .15s; }
.btn-sm:hover { border-color: var(--terra); color: var(--terra); }
.btn-sm-danger:hover { border-color: #E05555; color: #E05555; }
.empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
.empty-state-icon { font-size: 2.8rem; margin-bottom: 12px; }
.empty-state-title { font-family: var(--font-head); font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 6px; }
</style>

<div class="mgr-wrap">

    @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    <div class="mgr-header">
        <h1>Mes services</h1>
        <a href="{{ route('services.create') }}" class="btn-terra">+ Nouveau service</a>
    </div>

    <div class="svc-table"><div class="svc-table-overflow">
        <div class="svc-table-header">
            {{ $services->count() }} service(s) au total
        </div>

        @if($services->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">🛍️</div>
            <div class="empty-state-title">Tu n'as pas encore de service</div>
            <p style="font-size:.84rem;margin-bottom:16px;">Crée ton premier service pour apparaître sur le marketplace.</p>
            <a href="{{ route('services.create') }}" class="btn-terra">Créer mon premier service</a>
        </div>
        @else
        @foreach($services as $service)
        <div class="svc-row">
            {{-- Thumbnail --}}
            <div class="svc-thumb">
                @if($service->cover_image)
                    <img src="{{ Storage::url($service->cover_image) }}" alt="">
                @else
                    {{ $service->category_icon }}
                @endif
            </div>

            {{-- Infos --}}
            <div>
                <div class="svc-info-title">{{ $service->title }}</div>
                <div class="svc-info-sub">{{ $service->category_label }} · {{ $service->price_formatted }} · {{ $service->delivery_days }}j</div>
            </div>

            {{-- Commandes --}}
            <div>
                <div class="svc-orders-count">{{ $service->orders_count }}</div>
                <div class="svc-orders-label">commandes</div>
            </div>

            {{-- Statut --}}
            <div>
                @if($service->is_active)
                    <span class="svc-status-badge badge-active">● Actif</span>
                @else
                    <span class="svc-status-badge badge-inactive">● Inactif</span>
                @endif
            </div>

            {{-- Actions --}}
            <div class="svc-actions">
                <a href="{{ route('marketplace.show', $service) }}" class="btn-sm" target="_blank">Voir</a>
                <a href="{{ route('services.edit', $service) }}" class="btn-sm">Modifier</a>
                <form method="POST" action="{{ route('services.destroy', $service) }}" onsubmit="return confirm('Supprimer ce service ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-sm btn-sm-danger">Suppr.</button>
                </form>
            </div>
        </div>
        @endforeach
        @endif
    </div></div>{{-- svc-table-overflow --}}

</div>
@endsection
