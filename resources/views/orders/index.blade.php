@extends('layouts.app')
@section('title', 'Mes commandes — MelanoGeek')
@section('content')
<style>
.ord-wrap { max-width: 860px; margin: 0 auto; padding: 88px 20px 80px; }
.ord-wrap h1 { font-family: var(--font-head); font-size: 1.5rem; font-weight: 800; color: var(--text); margin-bottom: 24px; }
.tabs { display: flex; gap: 4px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 4px; margin-bottom: 24px; width: fit-content; }
.tab-btn { padding: 7px 18px; border-radius: 9px; font-size: .84rem; font-weight: 600; color: var(--text-muted); text-decoration: none; transition: all .15s; }
.tab-btn.active { background: var(--terra); color: white; }
.alert-success { background: rgba(42,122,72,.12); border: 1px solid rgba(42,122,72,.3); color: #2A7A48; padding: 12px 16px; border-radius: 10px; font-size: .84rem; margin-bottom: 20px; }
.alert-success-dark { color: #6DC48A; }

.ord-list { display: flex; flex-direction: column; gap: 12px; }
.ord-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 18px 20px; display: grid; grid-template-columns: auto 1fr auto; gap: 16px; align-items: center; text-decoration: none; transition: border-color .15s; }
.ord-card:hover { border-color: var(--border-hover); }
.ord-thumb { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; background: var(--bg-card2); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; overflow: hidden; }
.ord-thumb img { width: 100%; height: 100%; object-fit: cover; }
.ord-title { font-family: var(--font-head); font-size: .92rem; font-weight: 700; color: var(--text); margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.ord-sub { font-size: .76rem; color: var(--text-muted); }
.ord-status { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 100px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
.ord-price { font-family: var(--font-head); font-size: .95rem; font-weight: 700; color: var(--text); text-align: right; margin-top: 4px; }
.empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
.empty-state-icon { font-size: 2.8rem; margin-bottom: 12px; }
.empty-state-title { font-family: var(--font-head); font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 6px; }
</style>

<div class="ord-wrap">

    @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    <h1>Mes commandes</h1>

    <div class="tabs">
        <a href="{{ route('orders.index', ['tab' => 'purchases']) }}" class="tab-btn {{ $tab === 'purchases' ? 'active' : '' }}">🛒 Mes achats</a>
        @if(auth()->user()->isCreator())
        <a href="{{ route('orders.index', ['tab' => 'sales']) }}" class="tab-btn {{ $tab === 'sales' ? 'active' : '' }}">💼 Mes ventes</a>
        @endif
    </div>

    @if($orders->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">{{ $tab === 'sales' ? '💼' : '🛒' }}</div>
        <div class="empty-state-title">Aucune commande</div>
        @if($tab === 'purchases')
            <p style="font-size:.84rem;margin-bottom:16px;">Explore le marketplace pour découvrir des services.</p>
            <a href="{{ route('marketplace.index') }}" style="display:inline-block;padding:10px 20px;background:var(--terra);color:white;border-radius:10px;font-weight:700;text-decoration:none;font-size:.86rem;">Explorer le marketplace</a>
        @else
            <p style="font-size:.84rem;">Tu n'as pas encore reçu de commandes.</p>
        @endif
    </div>
    @else
    <div class="ord-list">
        @foreach($orders as $order)
        @php
            $statusInfo = \App\Models\Order::STATUS_LABELS[$order->status] ?? ['label' => $order->status, 'color' => '#888', 'bg' => '#eee'];
        @endphp
        <a href="{{ route('orders.show', $order) }}" class="ord-card">
            <div class="ord-thumb">
                @if($order->service->cover_image)
                    <img src="{{ Storage::url($order->service->cover_image) }}" alt="">
                @else
                    {{ $order->service->category_icon ?? '🛍️' }}
                @endif
            </div>
            <div>
                <div class="ord-title">{{ $order->service->title }}</div>
                <div class="ord-sub">
                    @if($tab === 'purchases')
                        de {{ $order->seller->name }} · {{ $order->created_at->diffForHumans() }}
                    @else
                        par {{ $order->buyer->name }} · {{ $order->created_at->diffForHumans() }}
                    @endif
                </div>
            </div>
            <div style="text-align:right;">
                <div class="ord-status" style="color:{{ $statusInfo['color'] }};background:{{ $statusInfo['bg'] }};border:1px solid {{ $statusInfo['color'] }}33;">
                    {{ $statusInfo['label'] }}
                </div>
                <div class="ord-price">{{ $order->price_formatted }}</div>
            </div>
        </a>
        @endforeach
    </div>

    @if($orders->hasPages())
    <div style="margin-top:24px;">{{ $orders->links() }}</div>
    @endif
    @endif

</div>
@endsection
