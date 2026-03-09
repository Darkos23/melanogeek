@extends('layouts.app')
@section('title', 'Nouveau service — MelanoGeek')
@section('content')
<style>
.svc-form-wrap { max-width: 640px; margin: 0 auto; padding: 44px 20px 80px; }
.svc-form-wrap h1 { font-family: var(--font-head); font-size: 1.6rem; font-weight: 800; color: var(--text); margin-bottom: 6px; }
.svc-form-wrap .sub { font-size: .86rem; color: var(--text-muted); margin-bottom: 32px; }
.form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 28px; margin-bottom: 20px; }
.form-card h2 { font-family: var(--font-head); font-size: .95rem; font-weight: 700; color: var(--text); margin-bottom: 18px; }
.form-group { margin-bottom: 18px; }
.form-label { display: block; font-size: .82rem; font-weight: 600; color: var(--text); margin-bottom: 7px; }
.form-input, .form-select, .form-textarea {
    width: 100%; background: var(--bg-card2); border: 1px solid var(--border);
    border-radius: 10px; padding: 10px 13px; font-size: .86rem;
    font-family: var(--font-body); color: var(--text); outline: none;
    transition: border-color .15s;
}
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--terra); }
.form-input::placeholder, .form-textarea::placeholder { color: var(--text-faint); }
.form-textarea { resize: vertical; min-height: 120px; }
.form-hint { font-size: .74rem; color: var(--text-muted); margin-top: 5px; }
.price-row { display: grid; grid-template-columns: 1fr auto; gap: 10px; }
.form-err { font-size: .78rem; color: #E05555; margin-top: 4px; }
.submit-btn {
    width: 100%; padding: 13px; border-radius: 12px;
    background: var(--terra); color: white; border: none;
    font-family: var(--font-head); font-size: .95rem; font-weight: 700;
    cursor: pointer; transition: opacity .2s;
}
.submit-btn:hover { opacity: .88; }
.back-link { display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; color: var(--text-muted); text-decoration: none; margin-bottom: 24px; }
.back-link:hover { color: var(--text); }
.cover-preview { width: 100%; aspect-ratio: 16/9; border-radius: 10px; object-fit: cover; margin-top: 10px; display: none; }
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
</style>

<div class="svc-form-wrap">
    <a href="{{ route('services.manage') }}" class="back-link">← Mes services</a>
    <h1>Nouveau service</h1>
    <p class="sub">Décris ce que tu proposes — sois précis pour attirer les bons clients.</p>

    <form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-card">
            <h2>Informations générales</h2>

            <div class="form-group">
                <label class="form-label">Titre du service *</label>
                <input type="text" name="title" class="form-input" placeholder="Ex: Shooting photo portrait professionnel à Dakar" value="{{ old('title') }}" maxlength="120">
                @error('title')<div class="form-err">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Catégorie *</label>
                <select name="category" class="form-select">
                    <option value="">Choisir une catégorie</option>
                    @foreach($categories as $key => $cat)
                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $cat['icon'] }} {{ $cat['label'] }}</option>
                    @endforeach
                </select>
                @error('category')<div class="form-err">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description détaillée *</label>
                <textarea name="description" class="form-textarea" placeholder="Explique en détail ce que tu fais, comment tu travailles, ce qui est inclus, les délais, etc.">{{ old('description') }}</textarea>
                <div class="form-hint">Minimum 50 caractères — sois précis et convaincant.</div>
                @error('description')<div class="form-err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-card">
            <h2>Tarification & délai</h2>

            <div class="form-group">
                <label class="form-label">Type de tarification *</label>
                <div class="price-type-tabs">
                    <div class="price-type-tab {{ old('price_type', 'fixed') !== 'quote' ? 'active' : '' }}" onclick="setPriceType('fixed', this)">
                        💰 Prix fixe
                    </div>
                    <div class="price-type-tab {{ old('price_type') === 'quote' ? 'active' : '' }}" onclick="setPriceType('quote', this)">
                        📩 Prix sur devis
                    </div>
                </div>
                <input type="hidden" name="price_type" id="priceTypeInput" value="{{ old('price_type', 'fixed') }}">
            </div>

            <div id="fixedPriceSection" style="{{ old('price_type') === 'quote' ? 'display:none;' : '' }}">
                <div class="form-group">
                    <label class="form-label">Prix *</label>
                    <div class="price-row">
                        <input type="number" name="price" id="priceInput" class="form-input" placeholder="2500" min="100" step="100" value="{{ old('price') }}">
                        <select name="currency" class="form-select" style="width:110px;">
                            <option value="XOF" {{ old('currency', 'XOF') === 'XOF' ? 'selected' : '' }}>FCFA</option>
                            <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>€ EUR</option>
                        </select>
                    </div>
                    <div class="form-hint">Prix minimum : 100 FCFA ou 1 €</div>
                    @error('price')<div class="form-err">{{ $message }}</div>@enderror
                </div>
            </div>

            <div id="quotePriceSection" style="{{ old('price_type') !== 'quote' ? 'display:none;' : '' }}">
                <div class="form-group">
                    <div class="quote-badge">
                        📩 Le client t'enverra ses besoins et tu lui proposeras un montant personnalisé.
                    </div>
                    <div class="form-hint" style="margin-top:8px;">Idéal pour les projets sur-mesure (shooting, beat, design unique…)</div>
                    <input type="hidden" name="currency" id="quoteCurrency" value="{{ old('currency', 'XOF') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Délai de livraison (jours) *</label>
                <input type="number" name="delivery_days" class="form-input" placeholder="7" min="1" max="90" value="{{ old('delivery_days', 7) }}" style="max-width:160px;">
                @error('delivery_days')<div class="form-err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-card">
            <h2>Image de couverture</h2>
            <div class="form-group">
                <label class="form-label">Photo / Illustration <span style="color:var(--text-muted);font-weight:400;">(optionnel)</span></label>
                <input type="file" name="cover_image" class="form-input" accept="image/*" onchange="previewCover(this)" style="padding:8px;">
                <div class="form-hint">Format recommandé : 16/9, max 4 Mo</div>
                <img id="coverPreview" class="cover-preview" alt="Aperçu">
                @error('cover_image')<div class="form-err">{{ $message }}</div>@enderror
            </div>
        </div>

        <button type="submit" class="submit-btn">Publier le service →</button>
    </form>
</div>

<script>
function previewCover(input) {
    const preview = document.getElementById('coverPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}

function setPriceType(type, el) {
    document.getElementById('priceTypeInput').value = type;
    document.querySelectorAll('.price-type-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('fixedPriceSection').style.display = type === 'fixed' ? '' : 'none';
    document.getElementById('quotePriceSection').style.display = type === 'quote' ? '' : 'none';
}
</script>
@endsection
