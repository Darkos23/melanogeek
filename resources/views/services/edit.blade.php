@extends('layouts.app')
@section('title', 'Modifier le service — MelanoGeek')
@section('content')
<style>
.svc-form-wrap { max-width: 640px; margin: 0 auto; padding: 88px 20px 80px; }
.svc-form-wrap h1 { font-family: var(--font-head); font-size: 1.5rem; font-weight: 800; color: var(--text); margin-bottom: 6px; }
.svc-form-wrap .sub { font-size: .86rem; color: var(--text-muted); margin-bottom: 32px; }
.form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 28px; margin-bottom: 20px; }
.form-card h2 { font-family: var(--font-head); font-size: .95rem; font-weight: 700; color: var(--text); margin-bottom: 18px; }
.form-group { margin-bottom: 18px; }
.form-label { display: block; font-size: .82rem; font-weight: 600; color: var(--text); margin-bottom: 7px; }
.form-input, .form-select, .form-textarea {
    width: 100%; background: var(--bg-card2); border: 1px solid var(--border);
    border-radius: 10px; padding: 10px 13px; font-size: .86rem;
    font-family: var(--font-body); color: var(--text); outline: none; transition: border-color .15s;
}
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--terra); }
.form-textarea { resize: vertical; min-height: 120px; }
.form-hint { font-size: .74rem; color: var(--text-muted); margin-top: 5px; }
.price-row { display: grid; grid-template-columns: 1fr auto; gap: 10px; }
.form-err { font-size: .78rem; color: #E05555; margin-top: 4px; }
.submit-btn { width: 100%; padding: 13px; border-radius: 12px; background: var(--terra); color: white; border: none; font-family: var(--font-head); font-size: .95rem; font-weight: 700; cursor: pointer; transition: opacity .2s; }
.submit-btn:hover { opacity: .88; }
.back-link { display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; color: var(--text-muted); text-decoration: none; margin-bottom: 24px; }
.back-link:hover { color: var(--text); }
.price-type-tabs { display: flex; gap: 8px; margin-bottom: 4px; }
.price-type-tab {
    flex: 1; padding: 10px; border-radius: 10px; border: 1px solid var(--border);
    background: var(--bg-card2); color: var(--text-muted); font-size: .83rem; font-weight: 600;
    cursor: pointer; text-align: center; transition: all .15s;
}
.price-type-tab.active { border-color: var(--terra); background: rgba(200,82,42,.08); color: var(--terra); }
.quote-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(212,168,67,.1); border: 1px solid rgba(212,168,67,.3);
    color: var(--gold); border-radius: 8px; padding: 10px 14px;
    font-size: .84rem; font-weight: 600; width: 100%;
}
.toggle-row { display: flex; align-items: center; justify-content: space-between; }
.toggle-label { font-size: .84rem; color: var(--text-muted); }
.toggle { width: 38px; height: 22px; background: var(--border); border-radius: 100px; position: relative; cursor: pointer; transition: background .2s; }
.toggle.on { background: var(--terra); }
.toggle::after { content: ''; position: absolute; top: 3px; left: 3px; width: 16px; height: 16px; background: white; border-radius: 50%; transition: left .2s; }
.toggle.on::after { left: 19px; }
</style>

<div class="svc-form-wrap">
    <a href="{{ route('services.manage') }}" class="back-link">← Mes services</a>
    <h1>Modifier le service</h1>
    <p class="sub">{{ $service->title }}</p>

    <form method="POST" action="{{ route('services.update', $service) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-card">
            <h2>Statut</h2>
            <div class="toggle-row">
                <div>
                    <div class="form-label" style="margin:0;">Service actif / visible</div>
                    <div class="toggle-label">Désactiver pour retirer temporairement du marketplace</div>
                </div>
                <div class="toggle {{ $service->is_active ? 'on' : '' }}" id="activeToggle" onclick="toggleActive()"></div>
                <input type="hidden" name="is_active" id="isActiveInput" value="{{ $service->is_active ? '1' : '0' }}">
            </div>
        </div>

        <div class="form-card">
            <h2>Informations générales</h2>
            <div class="form-group">
                <label class="form-label">Titre *</label>
                <input type="text" name="title" class="form-input" value="{{ old('title', $service->title) }}" maxlength="120">
                @error('title')<div class="form-err">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Catégorie *</label>
                <select name="category" class="form-select">
                    @foreach($categories as $key => $cat)
                    <option value="{{ $key }}" {{ old('category', $service->category) === $key ? 'selected' : '' }}>{{ $cat['icon'] }} {{ $cat['label'] }}</option>
                    @endforeach
                </select>
                @error('category')<div class="form-err">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-textarea">{{ old('description', $service->description) }}</textarea>
                @error('description')<div class="form-err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-card">
            <h2>Tarification & délai</h2>

            @php $currentPriceType = old('price_type', $service->price_type ?? 'fixed'); @endphp

            <div class="form-group">
                <label class="form-label">Type de tarification *</label>
                <div class="price-type-tabs">
                    <div class="price-type-tab {{ $currentPriceType !== 'quote' ? 'active' : '' }}" onclick="setPriceType('fixed', this)">
                        💰 Prix fixe
                    </div>
                    <div class="price-type-tab {{ $currentPriceType === 'quote' ? 'active' : '' }}" onclick="setPriceType('quote', this)">
                        📩 Prix sur devis
                    </div>
                </div>
                <input type="hidden" name="price_type" id="priceTypeInput" value="{{ $currentPriceType }}">
            </div>

            <div id="fixedPriceSection" style="{{ $currentPriceType === 'quote' ? 'display:none;' : '' }}">
                <div class="form-group">
                    <label class="form-label">Prix *</label>
                    <div class="price-row">
                        <input type="number" name="price" id="priceInput" class="form-input" min="100" step="100" value="{{ old('price', $service->price) }}">
                        <select name="currency" class="form-select" style="width:110px;">
                            <option value="XOF" {{ old('currency', $service->currency) === 'XOF' ? 'selected' : '' }}>FCFA</option>
                            <option value="EUR" {{ old('currency', $service->currency) === 'EUR' ? 'selected' : '' }}>€ EUR</option>
                        </select>
                    </div>
                    @error('price')<div class="form-err">{{ $message }}</div>@enderror
                </div>
            </div>

            <div id="quotePriceSection" style="{{ $currentPriceType !== 'quote' ? 'display:none;' : '' }}">
                <div class="form-group">
                    <div class="quote-badge">
                        📩 Le client t'enverra ses besoins et tu lui proposeras un montant personnalisé.
                    </div>
                    <input type="hidden" name="currency" id="quoteCurrency" value="{{ old('currency', $service->currency ?? 'XOF') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Délai (jours) *</label>
                <input type="number" name="delivery_days" class="form-input" min="1" max="90" value="{{ old('delivery_days', $service->delivery_days) }}" style="max-width:160px;">
                @error('delivery_days')<div class="form-err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-card">
            <h2>Image de couverture</h2>
            @if($service->cover_image)
            <img src="{{ Storage::url($service->cover_image) }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:10px;margin-bottom:12px;" alt="">
            @endif
            <div class="form-group">
                <label class="form-label">Changer l'image <span style="color:var(--text-muted);font-weight:400;">(optionnel)</span></label>
                <input type="file" name="cover_image" class="form-input" accept="image/*" style="padding:8px;">
                <div class="form-hint">Laisse vide pour garder l'image actuelle</div>
            </div>
        </div>

        <button type="submit" class="submit-btn">Enregistrer les modifications</button>
    </form>
</div>

<script>
function setPriceType(type, el) {
    document.getElementById('priceTypeInput').value = type;
    document.querySelectorAll('.price-type-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('fixedPriceSection').style.display = type === 'fixed' ? '' : 'none';
    document.getElementById('quotePriceSection').style.display = type === 'quote' ? '' : 'none';
}

function toggleActive() {
    const t = document.getElementById('activeToggle');
    const i = document.getElementById('isActiveInput');
    const isOn = t.classList.toggle('on');
    i.value = isOn ? '1' : '0';
}
</script>
@endsection
