@extends('layouts.blog')

@section('title', 'Blog')

@section('main')

{{-- ── EN-TÊTE PAGE ── --}}
<div style="margin-bottom:36px">
    <div style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--terra);margin-bottom:8px">Le blog MelanoGeek</div>
    <h1 style="font-family:var(--font-head);font-size:clamp(1.6rem,3vw,2.4rem);font-weight:800;letter-spacing:-.03em;line-height:1.1;color:var(--text);margin-bottom:10px">
        Culture geek, <span style="color:var(--terra)">vue d'Afrique</span>
    </h1>
    <p style="font-size:.82rem;color:var(--text-muted);line-height:1.65;max-width:520px">
        Articles, reviews, analyses et coups de cœur autour du manga, gaming, tech et de la culture nerd africaine.
    </p>
</div>

{{-- ── FILTRES CATÉGORIES ── --}}
<div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid var(--border)">
    @php
    $cats = [
        '' => ['icon' => '✦', 'label' => 'Tout'],
        'manga' => ['icon' => '🎌', 'label' => 'Manga'],
        'gaming' => ['icon' => '🎮', 'label' => 'Gaming'],
        'tech' => ['icon' => '💻', 'label' => 'Tech'],
        'cinema' => ['icon' => '🎬', 'label' => 'Cinéma'],
        'bd' => ['icon' => '📚', 'label' => 'BD'],
        'cosplay' => ['icon' => '🎭', 'label' => 'Cosplay'],
        'culture' => ['icon' => '🌍', 'label' => 'Culture'],
    ];
    $activeCat = request('cat', '');
    @endphp

    @foreach($cats as $slug => $cat)
    <a href="{{ route('blog.index') }}{{ $slug ? '?cat='.$slug : '' }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:.7rem;font-weight:600;text-decoration:none;border:1px solid {{ $activeCat === $slug ? 'var(--terra)' : 'var(--border)' }};background:{{ $activeCat === $slug ? 'var(--terra-soft)' : 'transparent' }};color:{{ $activeCat === $slug ? 'var(--terra)' : 'var(--text-muted)' }};transition:all .18s;">
        <span>{{ $cat['icon'] }}</span> {{ $cat['label'] }}
    </a>
    @endforeach
</div>

{{-- ── ARTICLE FEATURED ── --}}
<a href="#" style="display:block;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden;text-decoration:none;margin-bottom:20px;transition:background .2s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='var(--bg-card)'">
    <div style="aspect-ratio:16/6;background:linear-gradient(135deg,#2A1206,#7A3010,#C84818);position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;font-size:4rem">
        <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(13,9,5,.7) 0%,transparent 60%)"></div>
        🎌
        <div style="position:absolute;inset:0;background-image:url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='white' stroke-width='0.5' opacity='0.08'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3Ccircle cx='30' cy='30' r='10'/%3E%3Cline x1='10' y1='30' x2='50' y2='30'/%3E%3Cline x1='30' y1='10' x2='30' y2='50'/%3E%3C/g%3E%3C/svg%3E\");background-size:60px 60px"></div>
        <div style="position:absolute;bottom:24px;left:28px;right:28px">
            <div style="font-size:.58rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.7);margin-bottom:8px">À la une · Manga africain</div>
            <div style="font-family:var(--font-head);font-size:clamp(1.1rem,2.5vw,1.8rem);font-weight:800;letter-spacing:-.02em;color:white;line-height:1.2;text-shadow:0 2px 12px rgba(0,0,0,.4)">
                Anansi Chronicles : le manga afrofuturiste qui redéfinit le genre
            </div>
        </div>
    </div>
    <div style="padding:24px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
        <div style="flex:1;min-width:0">
            <p style="font-size:.78rem;color:var(--text-muted);line-height:1.6;margin-bottom:14px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                Comment un auteur ghanéen a réussi à fusionner la mythologie Akan avec les codes du shonen japonais pour créer une œuvre qui fait vibrer toute la diaspora.
            </p>
            <div style="display:flex;align-items:center;gap:10px;font-size:.62rem;color:var(--text-muted)">
                <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,var(--terra),var(--gold));display:flex;align-items:center;justify-content:center;font-size:.62rem;font-weight:700;color:white;flex-shrink:0">K</div>
                <span>Kemi Adeyemi</span>
                <span style="width:3px;height:3px;background:var(--text-faint);border-radius:50%"></span>
                <span>12 jan. 2026</span>
                <span style="width:3px;height:3px;background:var(--text-faint);border-radius:50%"></span>
                <span>8 min de lecture</span>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:.68rem;font-weight:600;color:var(--terra);white-space:nowrap">
            Lire l'article
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </div>
    </div>
</a>

{{-- ── GRILLE ARTICLES ── --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1px;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--border);margin-bottom:28px">
    @php
    $articles = [
        ['icon'=>'🎮','cat'=>'Gaming','title'=>'African Game Week 2026 : les studios locaux à surveiller','excerpt'=>'Tour d\'horizon des développeurs indépendants africains qui repoussent les limites.','author'=>'Djiby Fall','avi'=>'D','date'=>'9 jan. 2026','time'=>'5'],
        ['icon'=>'💻','cat'=>'Tech & IA','title'=>'L\'IA générative au service des langues africaines','excerpt'=>'Des chercheurs entraînent des LLMs sur le wolof, le yoruba et le swahili avec des résultats bluffants.','author'=>'Amara Koné','avi'=>'A','date'=>'8 jan. 2026','time'=>'6'],
        ['icon'=>'🎬','cat'=>'Cinéma','title'=>'Nollywood SF : quand Lagos se réinvente en dystopie','excerpt'=>'Top 5 des films de science-fiction nigérians à ne pas manquer en 2026.','author'=>'Fatou Diallo','avi'=>'F','date'=>'7 jan. 2026','time'=>'4'],
        ['icon'=>'📚','cat'=>'BD & Comics','title'=>'Wakanda, Aza et Kugali : la BD africaine conquiert le monde','excerpt'=>'Ces trois licences qui ont changé le regard sur la bande dessinée africaine en seulement 5 ans.','author'=>'Nana Osei','avi'=>'N','date'=>'6 jan. 2026','time'=>'7'],
        ['icon'=>'🌍','cat'=>'Culture','title'=>'Les Adinkra dans le pixel art : quand l\'art Akan rencontre les 8-bit','excerpt'=>'Un artiste ghanéen revisite les symboles ancestraux dans un style rétro-game saisissant.','author'=>'Zeynab Ibrahim','avi'=>'Z','date'=>'5 jan. 2026','time'=>'3'],
        ['icon'=>'🎭','cat'=>'Cosplay','title'=>'Cosplayer africain·e : identité, représentation et fierté','excerpt'=>'Rencontre avec 5 cosplayeurs d\'Abidjan, Nairobi et Lagos qui réinventent leurs personnages préférés.','author'=>'Mwana K.','avi'=>'M','date'=>'4 jan. 2026','time'=>'6'],
    ];
    @endphp

    @foreach($articles as $art)
    <a href="#" style="background:var(--bg-card);padding:24px 26px;text-decoration:none;display:block;transition:background .18s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='var(--bg-card)'">
        <div style="display:inline-flex;align-items:center;gap:5px;font-size:.56rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--terra);background:var(--terra-soft);border:1px solid rgba(200,72,24,.18);padding:3px 9px;border-radius:4px;margin-bottom:12px">
            {{ $art['icon'] }} {{ $art['cat'] }}
        </div>
        <div style="font-family:var(--font-head);font-size:.88rem;font-weight:700;line-height:1.35;color:var(--text);margin-bottom:8px">
            {{ $art['title'] }}
        </div>
        <div style="font-size:.72rem;color:var(--text-muted);line-height:1.6;margin-bottom:14px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
            {{ $art['excerpt'] }}
        </div>
        <div style="display:flex;align-items:center;gap:8px;font-size:.6rem;color:var(--text-muted)">
            <div style="width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,var(--terra),var(--gold));display:flex;align-items:center;justify-content:center;font-size:.58rem;font-weight:700;color:white;flex-shrink:0">{{ $art['avi'] }}</div>
            <span>{{ $art['author'] }}</span>
            <span style="width:3px;height:3px;background:var(--text-faint);border-radius:50%"></span>
            <span>{{ $art['date'] }}</span>
            <span style="width:3px;height:3px;background:var(--text-faint);border-radius:50%"></span>
            <span>{{ $art['time'] }} min</span>
        </div>
    </a>
    @endforeach
</div>

{{-- ── PAGINATION ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
    <div style="font-size:.68rem;color:var(--text-muted)">Affichage de <strong style="color:var(--text)">1–7</strong> sur <strong style="color:var(--text)">380</strong> articles</div>
    <div style="display:flex;gap:4px">
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none;transition:all .18s" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">‹</a>
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--terra);background:var(--terra-soft);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:var(--terra);text-decoration:none">1</a>
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none;transition:all .18s" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">2</a>
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none;transition:all .18s" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">3</a>
        <span style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-faint)">…</span>
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none;transition:all .18s" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">54</a>
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none;transition:all .18s" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">›</a>
    </div>
</div>

@endsection
