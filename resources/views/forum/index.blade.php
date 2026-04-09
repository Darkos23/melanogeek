@extends('layouts.blog')

@section('title', 'Forum')

@section('main')

{{-- ── EN-TÊTE ── --}}
<div style="margin-bottom:32px">
    <div style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--terra);margin-bottom:8px">Communauté</div>
    <h1 style="font-family:var(--font-head);font-size:clamp(1.6rem,3vw,2.4rem);font-weight:800;letter-spacing:-.03em;line-height:1.1;color:var(--text);margin-bottom:10px">
        Le <span style="color:var(--terra)">forum</span> des geeks africains
    </h1>
    <p style="font-size:.82rem;color:var(--text-muted);line-height:1.65;max-width:520px">
        Débats, recommandations, créations et discussions dans une ambiance bienveillante.
    </p>
</div>

{{-- ── BARRE D'ACTION ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:28px;flex-wrap:wrap">
    {{-- Filtre rapide --}}
    <div style="display:flex;gap:4px">
        @foreach(['Tous' => '', 'Récents' => 'recent', 'Populaires' => 'popular', 'Sans réponse' => 'unanswered'] as $label => $val)
        <a href="{{ route('forum.index') }}{{ $val ? '?sort='.$val : '' }}"
           style="padding:7px 14px;border-radius:7px;font-size:.7rem;font-weight:600;text-decoration:none;border:1px solid {{ request('sort',$val===''?'':null)===$val ? 'var(--terra)' : 'var(--border)' }};background:{{ (request('sort',$val===''?'':null)===$val) ? 'var(--terra-soft)' : 'transparent' }};color:{{ (request('sort',$val===''?'':null)===$val) ? 'var(--terra)' : 'var(--text-muted)' }};transition:all .18s">
            {{ $label }}
        </a>
        @endforeach
    </div>

    @auth
    <a href="#" style="display:inline-flex;align-items:center;gap:7px;background:var(--terra);color:white;border:none;padding:8px 18px;border-radius:8px;font-size:.72rem;font-weight:700;text-decoration:none;transition:all .18s" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--terra)'">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Nouveau sujet
    </a>
    @else
    <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:7px;background:var(--terra);color:white;border:none;padding:8px 18px;border-radius:8px;font-size:.72rem;font-weight:700;text-decoration:none">
        Connexion pour poster
    </a>
    @endauth
</div>

{{-- ── CATÉGORIES DU FORUM ── --}}
<div style="margin-bottom:28px">
    <div style="font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-bottom:12px">Catégories</div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--border)">
        @php
        $forumCats = [
            ['icon'=>'🎌','name'=>'Manga & Animé','desc'=>'Débats, recommandations, news','count'=>312,'active'=>true],
            ['icon'=>'🎮','name'=>'Gaming','desc'=>'Reviews, tournois, guides','count'=>248,'active'=>false],
            ['icon'=>'💻','name'=>'Tech & IA','desc'=>'Projets, outils, tutoriels','count'=>187,'active'=>false],
            ['icon'=>'🌍','name'=>'Culture africaine','desc'=>'Mythes, arts, traditions geek','count'=>143,'active'=>false],
            ['icon'=>'🎭','name'=>'Cosplay','desc'=>'Créations, conseils, photos','count'=>95,'active'=>false],
            ['icon'=>'☕','name'=>'Off-topic','desc'=>'Détente & bavardages','count'=>124,'active'=>false],
        ];
        @endphp

        @foreach($forumCats as $cat)
        <a href="{{ route('forum.index') }}?cat={{ strtolower(str_replace([' ','&','é','â'],['','','e','a'],$cat['name'])) }}"
           style="background:var(--bg-card);padding:20px 22px;text-decoration:none;display:flex;align-items:center;gap:14px;transition:background .18s;"
           onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='var(--bg-card)'">
            <div style="width:40px;height:40px;border-radius:10px;background:var(--bg-card2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">{{ $cat['icon'] }}</div>
            <div style="flex:1;min-width:0">
                <div style="font-size:.78rem;font-weight:600;color:var(--text);margin-bottom:2px">{{ $cat['name'] }}</div>
                <div style="font-size:.62rem;color:var(--text-muted)">{{ $cat['desc'] }}</div>
            </div>
            <div style="font-family:var(--font-head);font-size:.78rem;font-weight:700;color:var(--text-faint);flex-shrink:0">{{ $cat['count'] }}</div>
        </a>
        @endforeach
    </div>
</div>

{{-- ── THREADS RÉCENTS ── --}}
<div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:28px">

    {{-- En-tête tableau --}}
    <div style="display:grid;grid-template-columns:1fr 80px 80px 120px;gap:0;padding:10px 24px;border-bottom:1px solid var(--border);background:var(--bg-card2)">
        <div style="font-size:.58rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-faint)">Sujet</div>
        <div style="font-size:.58rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-faint);text-align:center">Rép.</div>
        <div style="font-size:.58rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-faint);text-align:center">Vues</div>
        <div style="font-size:.58rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-faint);text-align:right">Activité</div>
    </div>

    @php
    $threads = [
        ['avi'=>'N','color'=>'linear-gradient(135deg,#C84818,#D4A843)','cat'=>'Gaming','pin'=>true,'title'=>'Quelqu\'un a testé Anansi Chronicles sur PS5 ? Mon avis après 20h de jeu','author'=>'Nana Osei','replies'=>24,'views'=>'1.2k','time'=>'2h','hot'=>true],
        ['avi'=>'Z','color'=>'linear-gradient(135deg,#1A5A30,#B87820)','cat'=>'Manga','pin'=>false,'title'=>'Top 10 des mangas afrofuturistes — ma liste après 3 ans de lecture intensive','author'=>'Zeynab Ibrahim','replies'=>61,'views'=>'3.8k','time'=>'5h','hot'=>true],
        ['avi'=>'M','color'=>'linear-gradient(135deg,#0A3A7A,#C84818)','cat'=>'Tech & IA','pin'=>false,'title'=>'Comment j\'ai créé une IA qui parle lingala — retour d\'expérience complet','author'=>'Mwana Kitoko','replies'=>38,'views'=>'2.1k','time'=>'8h','hot'=>false],
        ['avi'=>'A','color'=>'linear-gradient(135deg,#7A2080,#D4A843)','cat'=>'Cosplay','pin'=>false,'title'=>'Partage de costume — Shuri (Black Panther) fait maison, budget 25€','author'=>'Aïssata Barry','replies'=>17,'views'=>'890','time'=>'12h','hot'=>false],
        ['avi'=>'O','color'=>'linear-gradient(135deg,#5A3010,#E85A1A)','cat'=>'Cinéma','pin'=>true,'title'=>'Débat : Wakanda Forever était-il à la hauteur des attentes ?','author'=>'Olu Adebayo','replies'=>112,'views'=>'8.4k','time'=>'1j','hot'=>true],
        ['avi'=>'S','color'=>'linear-gradient(135deg,#2A1260,#9B5FD1)','cat'=>'Gaming','pin'=>false,'title'=>'Tournoi communautaire MelanoGeek — FIFA 2026, inscriptions ouvertes','author'=>'Seun Lagos','replies'=>43,'views'=>'1.9k','time'=>'2j','hot'=>false],
        ['avi'=>'I','color'=>'linear-gradient(135deg,#1A5A30,#2DB8A0)','cat'=>'Culture','pin'=>false,'title'=>'Les symboles Adinkra dans les jeux vidéo — un relevé exhaustif','author'=>'Ifeoma C.','replies'=>29,'views'=>'1.1k','time'=>'2j','hot'=>false],
        ['avi'=>'K','color'=>'linear-gradient(135deg,#7A1A10,#C84818)','cat'=>'Manga','pin'=>false,'title'=>'Discussion : l\'influence des griots dans la narration manga africaine','author'=>'Kofi Mensah','replies'=>55,'views'=>'2.7k','time'=>'3j','hot'=>false],
    ];
    @endphp

    @foreach($threads as $t)
    <a href="#" style="display:grid;grid-template-columns:1fr 80px 80px 120px;align-items:center;padding:16px 24px;border-bottom:1px solid var(--border);text-decoration:none;background:var(--bg-card);transition:background .16s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='var(--bg-card)'">
        <div style="display:flex;align-items:flex-start;gap:12px;min-width:0">
            <div style="width:36px;height:36px;border-radius:9px;background:{{ $t['color'] }};display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;color:white;flex-shrink:0">{{ $t['avi'] }}</div>
            <div style="min-width:0">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;flex-wrap:wrap">
                    <span style="font-size:.56rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:var(--terra);background:var(--terra-soft);border:1px solid rgba(200,72,24,.18);padding:2px 7px;border-radius:4px">{{ $t['cat'] }}</span>
                    @if($t['pin'])
                    <span style="font-size:.54rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--gold);background:var(--gold-soft);border:1px solid rgba(184,120,32,.2);padding:2px 7px;border-radius:4px">📌 Épinglé</span>
                    @endif
                    @if($t['hot'])
                    <span style="font-size:.54rem;font-weight:700;color:rgba(232,90,26,.8)">🔥</span>
                    @endif
                </div>
                <div style="font-size:.8rem;font-weight:600;color:var(--text);line-height:1.3;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden">{{ $t['title'] }}</div>
                <div style="font-size:.6rem;color:var(--text-muted);margin-top:4px">par {{ $t['author'] }}</div>
            </div>
        </div>
        <div style="text-align:center">
            <div style="font-family:var(--font-head);font-size:.82rem;font-weight:700;color:var(--text)">{{ $t['replies'] }}</div>
            <div style="font-size:.55rem;color:var(--text-faint);text-transform:uppercase;letter-spacing:.06em">rép.</div>
        </div>
        <div style="text-align:center">
            <div style="font-size:.78rem;font-weight:600;color:var(--text-muted)">{{ $t['views'] }}</div>
            <div style="font-size:.55rem;color:var(--text-faint);text-transform:uppercase;letter-spacing:.06em">vues</div>
        </div>
        <div style="text-align:right;font-size:.65rem;color:var(--text-muted)">{{ $t['time'] }}</div>
    </a>
    @endforeach
</div>

{{-- ── PAGINATION ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
    <div style="font-size:.68rem;color:var(--text-muted)">Page <strong style="color:var(--text)">1</strong> sur <strong style="color:var(--text)">138</strong></div>
    <div style="display:flex;gap:4px">
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none;opacity:.4">‹</a>
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--terra);background:var(--terra-soft);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:var(--terra);text-decoration:none">1</a>
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none;transition:all .18s" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">2</a>
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none;transition:all .18s" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">3</a>
        <span style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-faint)">…</span>
        <a href="#" style="width:34px;height:34px;border-radius:7px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--text-muted);text-decoration:none;transition:all .18s" onmouseover="this.style.borderColor='var(--terra)';this.style.color='var(--terra)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">›</a>
    </div>
</div>

@endsection

@section('sidebar')
{{-- Stats forum --}}
<div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--bg-card)">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-family:var(--font-head);font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted)">Statistiques</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border)">
        @foreach(['Sujets' => '1.1k', 'Réponses' => '8.4k', 'Membres' => '2.4k', 'En ligne' => '38'] as $label => $val)
        <div style="background:var(--bg-card);padding:16px 14px;text-align:center">
            <div style="font-family:var(--font-head);font-size:1.1rem;font-weight:800;color:var(--terra);letter-spacing:-.02em">{{ $val }}</div>
            <div style="font-size:.58rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-top:3px">{{ $label }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Catégories --}}
<div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--bg-card)">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-family:var(--font-head);font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted)">Catégories</div>
    @foreach([['🎌','Manga & Animé',312],['🎮','Gaming',248],['💻','Tech & IA',187],['🌍','Culture',143],['☕','Off-topic',124],['🎭','Cosplay',95]] as [$icon,$name,$count])
    <a href="#" style="display:flex;align-items:center;gap:10px;padding:12px 18px;border-bottom:1px solid var(--border);text-decoration:none;font-size:.75rem;color:var(--text-muted);transition:all .16s;" onmouseover="this.style.background='var(--bg-hover)';this.style.color='var(--terra)'" onmouseout="this.style.background='';this.style.color='var(--text-muted)'">
        <span style="font-size:1rem">{{ $icon }}</span>
        <span style="flex:1;font-weight:500">{{ $name }}</span>
        <span style="font-family:var(--font-head);font-size:.65rem;font-weight:700;color:var(--text-faint)">{{ $count }}</span>
    </a>
    @endforeach
</div>

{{-- Membres actifs --}}
<div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--bg-card)">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-family:var(--font-head);font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted)">Top contributeurs</div>
    @foreach([['N','Nana Osei','#C84818','312 posts'],['Z','Zeynab I.','#1A5A30','287 posts'],['O','Olu Adebayo','#7A2080','241 posts'],['K','Kofi M.','#0A3A7A','198 posts']] as [$avi,$name,$bg,$sub])
    <div style="display:flex;align-items:center;gap:10px;padding:12px 18px;border-bottom:1px solid var(--border);">
        <div style="width:30px;height:30px;border-radius:8px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:white;flex-shrink:0">{{ $avi }}</div>
        <div>
            <div style="font-size:.75rem;font-weight:600;color:var(--text)">{{ $name }}</div>
            <div style="font-size:.6rem;color:var(--text-muted)">{{ $sub }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- CTA rejoindre --}}
@guest
<div style="border:1px solid var(--border);border-radius:12px;padding:20px 18px;background:var(--bg-card);text-align:center">
    <div style="font-size:1.6rem;margin-bottom:10px">🌍</div>
    <div style="font-family:var(--font-head);font-size:.82rem;font-weight:700;color:var(--text);margin-bottom:8px">Rejoins la conversation</div>
    <p style="font-size:.7rem;color:var(--text-muted);line-height:1.55;margin-bottom:14px">Crée ton compte pour poster, voter et participer aux débats.</p>
    <a href="{{ route('register') }}" style="display:block;background:var(--terra);color:white;text-align:center;padding:10px;border-radius:8px;font-size:.72rem;font-weight:700;text-decoration:none;transition:background .18s" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--terra)'">Créer un compte gratuit</a>
</div>
@endguest
@endsection
