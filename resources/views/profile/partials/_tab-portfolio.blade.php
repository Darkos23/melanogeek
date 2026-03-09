{{-- ══ TAB : PORTFOLIO ══ --}}
<div id="tab-portfolio" style="display:none;">

    @php
        $cats = \App\Models\PortfolioItem::categories();
        $catLabels = ['tous' => '✦ Tous'] + $cats;
    @endphp

    @if($portfolio->isNotEmpty())
    {{-- Filters + Add button --}}
    <div class="pf-tab-header">
        <div class="pf-filter-chips">
            <button class="pf-chip active" data-pf-filter="tous">✦ Tous <span style="font-size:.65rem;font-weight:400;">({{ $portfolio->count() }})</span></button>
            @foreach($cats as $key => $label)
                @if($portfolio->where('category', $key)->isNotEmpty())
                    <button class="pf-chip" data-pf-filter="{{ $key }}">{{ $label }} <span style="font-size:.65rem;font-weight:400;">({{ $portfolio->where('category', $key)->count() }})</span></button>
                @endif
            @endforeach
        </div>
        @auth
            @if(auth()->id() === $user->id)
                <a href="{{ route('portfolio.create') }}" class="pf-add-btn">+ Ajouter</a>
            @endif
        @endauth
    </div>

    {{-- Masonry grid --}}
    <div class="pf-masonry" id="pfMasonry">
        @foreach($portfolio as $i => $item)
        <div class="pf-masonry-item" data-cat="{{ $item->category }}"
             style="animation-delay: {{ $i * 0.06 }}s;"
             onclick="pfOpenLightbox({{ $item->id }})">
            <div class="pf-card-inner">

                {{-- Cover image or placeholder --}}
                @if($item->cover_url)
                    <img class="pf-card-img" src="{{ $item->cover_url }}" alt="{{ $item->title }}" loading="lazy">
                @else
                    <div class="pf-card-no-img">
                        {{ explode(' ', $cats[$item->category] ?? '✦')[0] }}
                    </div>
                @endif

                {{-- Hover overlay --}}
                <div class="pf-card-overlay">
                    <div class="pf-card-title">{{ $item->title }}</div>
                    <div class="pf-card-cat">{{ $cats[$item->category] ?? $item->category }}</div>
                </div>

                {{-- External link badge --}}
                @if($item->external_url)
                    <div class="pf-card-link-badge">↗ Lien</div>
                @endif

                {{-- Owner: edit / delete --}}
                @auth
                @if(auth()->id() === $user->id)
                <div class="pf-card-actions" onclick="event.stopPropagation()">
                    <a href="{{ route('portfolio.edit', $item) }}" class="pf-card-action-btn" title="Modifier">✎</a>
                    <form method="POST" action="{{ route('portfolio.destroy', $item) }}" onsubmit="return confirm('Supprimer ce projet ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="pf-card-action-btn delete" title="Supprimer">✕</button>
                    </form>
                </div>
                @endif
                @endauth

            </div>
        </div>
        @endforeach
    </div>

    @else
    {{-- Empty state --}}
    <div style="text-align:center;padding:60px 20px;">
        <div style="font-size:3rem;margin-bottom:16px;">🎨</div>
        <div style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;margin-bottom:8px;color:var(--text);">
            Aucun projet dans le portfolio
        </div>
        <div style="font-size:.86rem;color:var(--text-muted);margin-bottom:24px;line-height:1.6;">
            @if(auth()->id() === $user->id)
                Ajoute tes meilleurs projets pour impressionner tes clients et abonnés.
            @else
                {{ $user->name }} n'a pas encore ajouté de projets à son portfolio.
            @endif
        </div>
        @auth
            @if(auth()->id() === $user->id)
                <a href="{{ route('portfolio.create') }}" class="pf-add-btn">✦ Ajouter mon premier projet</a>
            @endif
        @endauth
    </div>
    @endif

</div>

{{-- ══ LIGHTBOX PORTFOLIO ══ --}}
<div class="pf-lb-overlay" id="pfLbOverlay" onclick="pfLbClickOutside(event)">
    <div class="pf-lb-box">
        <div class="pf-lb-left" id="pfLbLeft">
            <div class="pf-lb-no-img">🎨</div>
        </div>
        <div class="pf-lb-right" id="pfLbRight">
            <button class="pf-lb-close" onclick="pfCloseLightbox()">✕</button>
            <div style="margin-top:8px;">
                <div class="pf-lb-cat-badge" id="pfLbCat">—</div>
                <div class="pf-lb-title" id="pfLbTitle">—</div>
                <div class="pf-lb-desc" id="pfLbDesc" style="display:none;"></div>
                <div class="pf-lb-tags" id="pfLbTags" style="display:none;"></div>
                <a href="#" class="pf-lb-link" id="pfLbLink" style="display:none;" target="_blank" rel="noopener">
                    ↗ Voir le projet
                </a>
            </div>
        </div>
    </div>
</div>

@php
$pfData = $portfolio->map(function($i) {
    return [
        'id'             => $i->id,
        'title'          => $i->title,
        'description'    => $i->description,
        'category'       => $i->category,
        'category_label' => \App\Models\PortfolioItem::categories()[$i->category] ?? $i->category,
        'cover_url'      => $i->cover_url,
        'image_urls'     => $i->image_urls,
        'external_url'   => $i->external_url,
        'tags'           => $i->tags ?? [],
    ];
})->values()->all();
@endphp
@push('scripts')
<script>
// ── Portfolio data ──
const pfItems = @json($pfData);

// ── Filter chips ──
document.querySelectorAll('.pf-chip').forEach(chip => {
    chip.addEventListener('click', function() {
        document.querySelectorAll('.pf-chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.pfFilter;
        document.querySelectorAll('.pf-masonry-item').forEach(item => {
            const show = filter === 'tous' || item.dataset.cat === filter;
            item.style.display = show ? '' : 'none';
        });
    });
});

// ── Lightbox ──
let pfLbIdx   = 0;
let pfLbSlides = [];

function pfOpenLightbox(id) {
    const item = pfItems.find(i => i.id === id);
    if (!item) return;

    pfLbSlides = [];
    if (item.cover_url) pfLbSlides.push(item.cover_url);
    if (item.image_urls && item.image_urls.length) pfLbSlides.push(...item.image_urls);
    pfLbIdx = 0;

    // Build media panel
    const left = document.getElementById('pfLbLeft');
    if (pfLbSlides.length > 0) {
        const slides = pfLbSlides.map(url =>
            `<div class="pf-lb-car-slide"><img src="${url}" alt=""></div>`
        ).join('');
        const nav = pfLbSlides.length > 1
            ? `<button class="pf-lb-nav prev" onclick="pfLbMove(-1)">‹</button>
               <button class="pf-lb-nav next" onclick="pfLbMove(1)">›</button>
               <div class="pf-lb-counter" id="pfLbCounter">1 / ${pfLbSlides.length}</div>`
            : '';
        left.innerHTML = `<div style="width:100%;height:100%;overflow:hidden;position:relative;">
            <div style="display:flex;height:100%;transition:transform .3s;" id="pfLbTrack">${slides}</div>
            ${nav}
        </div>`;
    } else {
        const icon = item.category_label.split(' ')[0];
        left.innerHTML = `<div class="pf-lb-no-img">${icon}</div>`;
    }

    // Info panel
    document.getElementById('pfLbCat').textContent   = item.category_label;
    document.getElementById('pfLbTitle').textContent = item.title;

    const descEl = document.getElementById('pfLbDesc');
    if (item.description) {
        descEl.textContent  = item.description;
        descEl.style.display = '';
    } else {
        descEl.style.display = 'none';
    }

    const tagsEl = document.getElementById('pfLbTags');
    if (item.tags && item.tags.length) {
        tagsEl.innerHTML     = item.tags.map(t => `<span class="pf-lb-tag">${pfEsc(t)}</span>`).join('');
        tagsEl.style.display = '';
    } else {
        tagsEl.style.display = 'none';
    }

    const linkEl = document.getElementById('pfLbLink');
    if (item.external_url) {
        linkEl.href          = item.external_url;
        linkEl.style.display = '';
    } else {
        linkEl.style.display = 'none';
    }

    document.getElementById('pfLbOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function pfLbMove(dir) {
    pfLbIdx = (pfLbIdx + dir + pfLbSlides.length) % pfLbSlides.length;
    const track = document.getElementById('pfLbTrack');
    if (track) track.style.transform = `translateX(-${pfLbIdx * 100}%)`;
    const counter = document.getElementById('pfLbCounter');
    if (counter) counter.textContent = `${pfLbIdx + 1} / ${pfLbSlides.length}`;
}

function pfCloseLightbox() {
    document.getElementById('pfLbOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function pfLbClickOutside(e) {
    if (e.target === document.getElementById('pfLbOverlay')) pfCloseLightbox();
}

document.addEventListener('keydown', e => {
    if (!document.getElementById('pfLbOverlay').classList.contains('open')) return;
    if (e.key === 'Escape')      pfCloseLightbox();
    if (e.key === 'ArrowRight')  pfLbMove(1);
    if (e.key === 'ArrowLeft')   pfLbMove(-1);
});

function pfEsc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
</script>
@endpush
