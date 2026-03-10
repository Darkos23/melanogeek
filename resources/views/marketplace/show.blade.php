@extends('layouts.app')

@section('title', $service->title . ' — MelanoGeek Marketplace')

@section('content')
<style>
.svc-wrap { max-width: 960px; margin: 0 auto; padding: 88px 20px 80px; }
.svc-back { display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; color: var(--text-muted); text-decoration: none; margin-bottom: 28px; transition: color .15s; }
.svc-back:hover { color: var(--text); }
.svc-layout { display: grid; grid-template-columns: 1fr 320px; gap: 28px; align-items: start; }
@media (max-width: 720px) { .svc-layout { grid-template-columns: 1fr; } }

/* Left col */
.svc-cover { width: 100%; aspect-ratio: 16/9; border-radius: 16px; overflow: hidden; background: var(--bg-card2); margin-bottom: 24px; }
.svc-cover img { width: 100%; height: 100%; object-fit: cover; }
.svc-cover-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 4rem; }
.svc-title { font-family: var(--font-head); font-size: 1.5rem; font-weight: 800; color: var(--text); margin-bottom: 12px; line-height: 1.3; }
.svc-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
.svc-badge { font-size: .72rem; font-weight: 700; padding: 4px 12px; border-radius: 100px; background: var(--terra-soft); color: var(--terra); border: 1px solid rgba(200,82,42,.2); }
.svc-badge-gray { background: var(--bg-card2); color: var(--text-muted); border-color: var(--border); }
.svc-desc-title { font-family: var(--font-head); font-size: .95rem; font-weight: 700; color: var(--text); margin-bottom: 12px; margin-top: 28px; }
.svc-desc { font-size: .88rem; color: var(--text-muted); line-height: 1.7; white-space: pre-wrap; }

/* Creator card */
.creator-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 20px; margin-top: 28px; }
.creator-card-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.creator-avi { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, var(--terra), var(--gold)); padding: 2px; flex-shrink: 0; }
.creator-avi-inner { width: 100%; height: 100%; border-radius: 50%; background: var(--bg-card2); display: flex; align-items: center; justify-content: center; font-size: .9rem; font-weight: 700; overflow: hidden; }
.creator-avi-inner img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.creator-name { font-family: var(--font-head); font-size: .95rem; font-weight: 700; color: var(--text); }
.creator-sub { font-size: .75rem; color: var(--text-muted); }
.creator-bio { font-size: .82rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 12px; }
.creator-link { font-size: .8rem; color: var(--terra); text-decoration: none; font-weight: 600; }
.creator-link:hover { text-decoration: underline; }

/* Order box */
.order-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; position: sticky; top: 80px; }
.order-box-price { font-family: var(--font-head); font-size: 2rem; font-weight: 800; color: var(--text); margin-bottom: 4px; }
.order-box-period { font-size: .8rem; color: var(--text-muted); margin-bottom: 20px; }
.order-features { list-style: none; margin-bottom: 20px; display: flex; flex-direction: column; gap: 9px; }
.order-features li { display: flex; align-items: center; gap: 8px; font-size: .83rem; color: var(--text-muted); }
.order-features li::before { content: '✓'; color: #2A7A48; font-weight: 700; font-size: .8rem; }
.order-btn {
    display: block; width: 100%; padding: 13px; border-radius: 12px;
    background: var(--terra); color: white; border: none;
    font-family: var(--font-head); font-size: .95rem; font-weight: 700;
    text-align: center; text-decoration: none; transition: opacity .2s;
}
.order-btn:hover { opacity: .88; }

/* More services */
.more-services { margin-top: 48px; }
.more-title { font-family: var(--font-head); font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 16px; }
.more-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
.more-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 12px; overflow: hidden; text-decoration: none;
    transition: border-color .15s;
}
.more-card:hover { border-color: var(--border-hover); }
.more-card-cover { width: 100%; aspect-ratio: 16/9; object-fit: cover; background: var(--bg-card2); }
.more-card-cover-ph { width: 100%; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; background: var(--bg-card2); font-size: 1.8rem; }
.more-card-body { padding: 12px; }
.more-card-title { font-size: .84rem; font-weight: 600; color: var(--text); margin-bottom: 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.more-card-price { font-size: .88rem; font-weight: 700; color: var(--terra); }

/* Order modal */
.order-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 200; align-items: center; justify-content: center; padding: 20px; }
.order-modal-overlay.open { display: flex; }
.order-modal { background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px; width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto; padding: 28px; }
.order-modal h2 { font-family: var(--font-head); font-size: 1.2rem; font-weight: 800; color: var(--text); margin-bottom: 4px; }
.order-modal .sub { font-size: .82rem; color: var(--text-muted); margin-bottom: 24px; }
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: .82rem; font-weight: 600; color: var(--text); margin-bottom: 7px; }
.form-input, .form-textarea {
    width: 100%; background: var(--bg-card2); border: 1px solid var(--border);
    border-radius: 10px; padding: 10px 13px; font-size: .86rem;
    font-family: var(--font-body); color: var(--text); outline: none;
    transition: border-color .15s;
}
.form-input:focus, .form-textarea:focus { border-color: var(--terra); }
.form-input::placeholder, .form-textarea::placeholder { color: var(--text-faint); }
.form-textarea { resize: vertical; min-height: 90px; }
.method-opts { display: flex; flex-direction: column; gap: 8px; }
.method-opt { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1.5px solid var(--border); border-radius: 10px; cursor: pointer; background: var(--bg-card2); transition: border-color .15s; }
.method-opt:has(input:checked) { border-color: var(--terra); background: var(--terra-soft); }
.method-opt input { accent-color: var(--terra); }
.payment-instr { background: rgba(212,168,67,.08); border: 1px solid rgba(212,168,67,.2); border-radius: 10px; padding: 14px 16px; margin: 12px 0 0; font-size: .8rem; color: var(--text-muted); line-height: 1.6; }
.payment-instr strong { color: var(--gold); display: block; margin-bottom: 6px; font-size: .82rem; }
.merchant-num { display: inline-block; font-family: monospace; font-weight: 700; font-size: .95rem; color: var(--text); background: var(--bg-card); border: 1px solid var(--border); border-radius: 6px; padding: 4px 10px; margin: 4px 0 8px; }
.submit-btn { width: 100%; padding: 13px; border-radius: 12px; background: var(--terra); color: white; border: none; font-family: var(--font-head); font-size: .95rem; font-weight: 700; cursor: pointer; transition: opacity .2s; margin-top: 8px; }
.submit-btn:hover { opacity: .88; }
.alert-info { background: rgba(212,168,67,.1); border: 1px solid rgba(212,168,67,.3); color: var(--gold); padding: 10px 14px; border-radius: 8px; font-size: .82rem; margin-bottom: 14px; }
</style>

<div class="svc-wrap">
    <a href="{{ route('marketplace.index') }}" class="svc-back">← Retour au marketplace</a>

    <div class="svc-layout">
        {{-- COLONNE GAUCHE --}}
        <div>
            {{-- Cover --}}
            <div class="svc-cover">
                @if($service->cover_image)
                    <img src="{{ Storage::url($service->cover_image) }}" alt="{{ $service->title }}">
                @else
                    <div class="svc-cover-placeholder">{{ $service->category_icon }}</div>
                @endif
            </div>

            {{-- Titre & badges --}}
            <h1 class="svc-title">{{ $service->title }}</h1>
            <div class="svc-badges">
                <span class="svc-badge">{{ $service->category_icon }} {{ $service->category_label }}</span>
                <span class="svc-badge svc-badge-gray">⏱ Livraison {{ $service->delivery_days }}j</span>
                <span class="svc-badge svc-badge-gray">🛒 {{ $service->orders_count }} commande(s)</span>
            </div>

            {{-- Description --}}
            <div class="svc-desc-title">À propos de ce service</div>
            <div class="svc-desc">{{ $service->description }}</div>

            {{-- Créateur --}}
            <div class="creator-card">
                <div class="creator-card-header">
                    <div class="creator-avi">
                        <div class="creator-avi-inner">
                            @if($service->user->avatar)<img src="{{ Storage::url($service->user->avatar) }}" alt="">@else{{ mb_strtoupper(mb_substr($service->user->name,0,1)) }}@endif
                        </div>
                    </div>
                    <div>
                        <div class="creator-name" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                            {{ $service->user->username }}
                            <span style="font-size:.68rem;font-weight:600;padding:2px 8px;border-radius:100px;
                                {{ $service->user->is_available ? 'background:rgba(34,197,94,.12);color:#16a34a;' : 'background:rgba(224,85,85,.1);color:#E05555;' }}">
                                {{ $service->user->is_available ? '🟢 Disponible' : '🔴 Non disponible' }}
                            </span>
                        </div>
                        <div class="creator-sub">&#64;{{ $service->user->username }} · {{ $service->user->niche ?: 'Créateur' }}</div>
                    </div>
                </div>
                @if($service->user->bio)
                <div class="creator-bio">{{ Str::limit($service->user->bio, 180) }}</div>
                @endif
                @if($totalReviews > 0)
                <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:10px;">
                    <span style="color:#F59E0B;">★</span> {{ number_format($avgRating,1) }}/5 · {{ $totalReviews }} avis
                </div>
                @endif
                <a href="{{ route('profile.show', $service->user->username) }}" class="creator-link">Voir le profil →</a>
            </div>
        </div>

        {{-- COLONNE DROITE --}}
        <div>
            <div class="order-box">
                <div class="order-box-price">{{ $service->price_formatted }}</div>
                <div class="order-box-period">Service unique · {{ $service->delivery_days }} jour(s) de délai</div>

                <ul class="order-features">
                    <li>Livraison en {{ $service->delivery_days }} jour(s)</li>
                    <li>Communication directe</li>
                    <li>Révisions incluses</li>
                </ul>

                @guest
                    <a href="{{ route('login') }}" class="order-btn">Connexion pour commander</a>
                @else
                    @if($service->user_id === auth()->id())
                        <div style="text-align:center;font-size:.82rem;color:var(--text-muted);padding:12px 0;">C'est ton service.</div>
                        <a href="{{ route('services.edit', $service) }}" class="order-btn" style="background:var(--bg-card2);color:var(--text);border:1px solid var(--border);">Modifier le service</a>
                    @elseif(! $service->user->is_available)
                        <div style="text-align:center;padding:12px 0;">
                            <div style="font-size:.85rem;font-weight:700;color:#E05555;margin-bottom:4px;">🔴 Créateur non disponible</div>
                            <div style="font-size:.78rem;color:var(--text-muted);">Ce créateur n'accepte pas de nouvelles commandes en ce moment.</div>
                        </div>
                    @else
                        <button class="order-btn" onclick="openOrderModal()">Commander ce service</button>
                    @endif
                @endguest
            </div>
        </div>
    </div>

    {{-- Autres services du créateur --}}
    @if($moreServices->count())
    <div class="more-services">
        <div class="more-title">Autres services de {{ $service->user->username }}</div>
        <div class="more-grid">
            @foreach($moreServices as $ms)
            <a href="{{ route('marketplace.show', $ms) }}" class="more-card">
                @if($ms->cover_image)
                    <img src="{{ Storage::url($ms->cover_image) }}" alt="" class="more-card-cover">
                @else
                    <div class="more-card-cover-ph">{{ $ms->category_icon }}</div>
                @endif
                <div class="more-card-body">
                    <div class="more-card-title">{{ $ms->title }}</div>
                    <div class="more-card-price">{{ $ms->price_formatted }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Avis clients --}}
    @if($totalReviews > 0)
    <div class="more-services">
        <div class="more-title">
            Avis clients
            <span style="font-size:.82rem;font-weight:500;color:var(--text-muted);margin-left:8px;">
                {{ number_format($avgRating, 1) }}/5 ★ · {{ $totalReviews }} avis
            </span>
        </div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            @foreach($reviews as $review)
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:16px 18px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--terra),var(--gold));display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;overflow:hidden;flex-shrink:0;">
                        @if($review->reviewer->avatar)
                            <img src="{{ Storage::url($review->reviewer->avatar) }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        @else
                            {{ mb_strtoupper(mb_substr($review->reviewer->name,0,1)) }}
                        @endif
                    </div>
                    <div>
                        <div style="font-size:.85rem;font-weight:600;color:var(--text);">{{ $review->reviewer->name }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);">@&#64;{{ $review->reviewer->username }}</div>
                    </div>
                    <div style="margin-left:auto;color:#F59E0B;font-size:.9rem;letter-spacing:1px;">
                        @for($i=1;$i<=5;$i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                    </div>
                </div>
                @if($review->comment)
                <p style="font-size:.84rem;color:var(--text-muted);margin:0;line-height:1.6;">{{ $review->comment }}</p>
                @endif
                <div style="font-size:.72rem;color:var(--text-faint);margin-top:6px;">{{ $review->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Modal commande --}}
@auth
@if($service->user_id !== auth()->id())
<div class="order-modal-overlay" id="orderOverlay" onclick="if(event.target===this)closeOrderModal()">
    <div class="order-modal">
        <h2>Commander ce service</h2>
        <div class="sub">{{ $service->title }} — {{ $service->price_formatted }}</div>

        <form method="POST" action="{{ route('orders.store') }}">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">

            <div class="form-group">
                <label class="form-label">Tes instructions / besoins <span style="color:var(--text-muted);font-weight:400;">(optionnel)</span></label>
                <textarea name="requirements" class="form-textarea" placeholder="Décris précisément ce que tu attends du créateur…"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Mode de paiement</label>
                <div class="method-opts">
                    @if($service->currency === 'XOF')
                    <label class="method-opt">
                        <input type="radio" name="payment_method" value="wave" checked onchange="updatePayInstr()">
                        <span>〰 Wave</span>
                    </label>
                    <label class="method-opt">
                        <input type="radio" name="payment_method" value="orange_money" onchange="updatePayInstr()">
                        <span>🟠 Orange Money</span>
                    </label>
                    @else
                    <label class="method-opt">
                        <input type="radio" name="payment_method" value="stripe" checked onchange="updatePayInstr()">
                        <span>💳 Carte bancaire</span>
                    </label>
                    @endif
                </div>

                <div class="payment-instr" id="payInstr">
                    <strong>〰 Payer via Wave</strong>
                    Envoie <strong>{{ $service->price_formatted }}</strong> au :<br>
                    <span class="merchant-num">77 000 00 00</span><br>
                    Puis colle l'ID de transaction ci-dessous.
                </div>
            </div>

            <div class="form-group" id="txIdGroup">
                <label class="form-label">ID de transaction</label>
                <input type="text" name="transaction_id" class="form-input" placeholder="Ex: W-1234567890">
            </div>

            <button type="submit" class="submit-btn">Confirmer la commande →</button>
            <div style="text-align:center;font-size:.74rem;color:var(--text-muted);margin-top:8px;">Le créateur recevra ta demande et commencera après vérification du paiement.</div>
        </form>
    </div>
</div>
@endif
@endauth

<script>
function openOrderModal() {
    document.getElementById('orderOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeOrderModal() {
    document.getElementById('orderOverlay').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeOrderModal(); });

function updatePayInstr() {
    const method = document.querySelector('input[name="payment_method"]:checked')?.value;
    const instr  = document.getElementById('payInstr');
    const txGroup = document.getElementById('txIdGroup');
    if (!instr) return;

    if (method === 'wave') {
        instr.style.display = 'block';
        instr.innerHTML = '<strong>〰 Payer via Wave</strong>Envoie <strong>{{ $service->price_formatted }}</strong> au :<br><span class="merchant-num">77 000 00 00</span><br>Puis colle l\'ID de transaction ci-dessous.';
        txGroup.style.display = 'block';
    } else if (method === 'orange_money') {
        instr.style.display = 'block';
        instr.innerHTML = '<strong>🟠 Payer via Orange Money</strong>Envoie <strong>{{ $service->price_formatted }}</strong> au :<br><span class="merchant-num">77 000 00 00</span><br>Puis colle le code de confirmation SMS ci-dessous.';
        txGroup.style.display = 'block';
    } else {
        instr.style.display = 'none';
        txGroup.style.display = 'none';
    }
}
</script>
@endsection
