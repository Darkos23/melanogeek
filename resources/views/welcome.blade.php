@extends('layouts.app')

@section('title', 'MelanoGeek — Le Réseau des Créateurs')

@push('styles')
<style>
/* ── Variables ── */
.home-page {
    --bg:#F5EDD6; --bg2:#EDE0C0; --card:#FBF5E6;
    --ink:#1E0E04; --ink2:rgba(30,14,4,.52); --ink3:rgba(30,14,4,.14);
    --terra:#C84818; --terra2:#E85A1A; --gold:#B87820; --green:#1A5A30;
    position:relative; background:var(--bg);
}


/* ══ HERO ══ */
.hero { min-height:100vh;padding-top:72px;position:relative;display:grid;grid-template-rows:1fr auto;overflow:hidden; }
.hero-adinkra { position:absolute;right:-80px;top:50%;transform:translateY(-50%);width:560px;height:560px;opacity:.13;pointer-events:none;z-index:0; }
.mudcloth-tl,.mudcloth-br { position:absolute;width:220px;height:220px;opacity:.13;pointer-events:none;z-index:0; }
.mudcloth-tl{top:72px;left:0;} .mudcloth-br{bottom:0;right:0;}
.hero-inner { position:relative;z-index:1;padding:64px 52px;display:grid;grid-template-columns:1.05fr .95fr;gap:52px;align-items:center; }

.hero-pill { display:inline-flex;align-items:center;gap:8px;background:rgba(200,72,24,.09);border:1px solid rgba(200,72,24,.22);color:var(--terra);font-family:'Unbounded',sans-serif;font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:5px 14px;border-radius:100px;margin-bottom:32px;width:fit-content; }
.pill-dot { width:6px;height:6px;background:var(--terra);border-radius:50%;animation:pulse 2s infinite;flex-shrink:0; }
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.7)}}

.hero-h1 { font-family:'Unbounded',sans-serif;font-size:clamp(2.6rem,5vw,5.2rem);font-weight:900;line-height:.98;letter-spacing:-.04em;color:var(--ink);margin-bottom:24px; }
.hero-h1 em { color:var(--terra);font-style:normal; }
.hero-h1 .outline { -webkit-text-stroke:2.5px var(--terra);color:transparent; }
.hero-desc { font-size:.9rem;line-height:1.82;color:var(--ink2);max-width:400px;margin-bottom:40px;font-weight:300; }
.hero-btns { display:flex;align-items:center;gap:16px;flex-wrap:wrap; }
.btn-main { background:var(--terra);color:white !important;border:none;padding:14px 30px;border-radius:6px;font-family:'Unbounded',sans-serif;font-size:.7rem;font-weight:700;cursor:pointer;transition:all .25s;text-decoration:none;display:inline-flex;align-items:center;gap:8px; }
.btn-main:hover { background:var(--terra2);transform:translateY(-2px);box-shadow:0 12px 32px rgba(200,72,24,.28); }
.btn-sec { color:var(--ink2) !important;font-family:'Sora',sans-serif;font-size:.78rem;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:10px;transition:color .2s; }
.btn-sec:hover { color:var(--terra) !important; }
.play-o { width:38px;height:38px;border:1.5px solid var(--ink3);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.6rem;transition:all .2s;color:var(--ink2); }
.btn-sec:hover .play-o { border-color:var(--terra);color:var(--terra); }

/* Carte profil */
.hero-r { position:relative; }
.kente-bg { position:absolute;inset:-20px;border-radius:24px;background-image:repeating-linear-gradient(0deg,rgba(200,72,24,.07) 0,rgba(200,72,24,.07) 2px,transparent 2px,transparent 20px),repeating-linear-gradient(90deg,rgba(184,120,32,.06) 0,rgba(184,120,32,.06) 2px,transparent 2px,transparent 20px);z-index:0; }
.card-prof { position:relative;z-index:1;background:var(--card);border:1px solid var(--ink3);border-radius:20px;overflow:hidden;box-shadow:0 28px 72px rgba(30,14,4,.11);max-width:370px;margin:0 auto; }
.cp-cover { height:152px;background:linear-gradient(135deg,#2A1206,#7A3010,#C84818);position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;font-size:3.5rem; }
.cp-cover::before { content:'';position:absolute;inset:0;background-image:url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='white' stroke-width='0.5' opacity='0.12'%3E%3Ccircle cx='20' cy='20' r='12'/%3E%3Ccircle cx='20' cy='20' r='6'/%3E%3Cline x1='0' y1='20' x2='40' y2='20'/%3E%3Cline x1='20' y1='0' x2='20' y2='40'/%3E%3Cpath d='M8 8 L32 32 M32 8 L8 32'/%3E%3C/g%3E%3C/svg%3E");background-size:40px 40px; }
.cp-cover::after { content:'';position:absolute;inset:0;background:linear-gradient(to top,rgba(30,14,4,.55) 0%,transparent 55%); }
.cp-live { position:absolute;top:10px;right:10px;z-index:2;background:rgba(190,30,10,.88);color:white;font-family:'Unbounded',sans-serif;font-size:.55rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:3px 9px;border-radius:100px;display:flex;align-items:center;gap:4px; }
.cp-live-d { width:5px;height:5px;background:white;border-radius:50%;animation:pulse 1.5s infinite; }
.cp-body { padding:18px 20px; }
.cp-top { display:flex;align-items:center;justify-content:space-between;margin-bottom:12px; }
.cp-av { width:50px;height:50px;border-radius:13px;background:linear-gradient(135deg,var(--terra),var(--gold));display:flex;align-items:center;justify-content:center;font-size:1.3rem;border:3px solid var(--card);margin-top:-28px;position:relative;z-index:2; }
.cp-bdg { font-family:'Unbounded',sans-serif;font-size:.55rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;padding:3px 8px;border-radius:4px;background:rgba(200,72,24,.09);border:1px solid rgba(200,72,24,.18);color:var(--terra); }
.cp-name { font-family:'Unbounded',sans-serif;font-size:.85rem;font-weight:700;color:var(--ink);margin-bottom:2px; }
.cp-niche { font-family:'Sora',sans-serif;font-size:.68rem;color:var(--ink2);margin-bottom:13px; }
.cp-stats { display:flex;border-top:1px solid var(--ink3);border-bottom:1px solid var(--ink3);padding:9px 0;margin-bottom:13px; }
.cps { flex:1;text-align:center; }
.cps-n { font-family:'Unbounded',sans-serif;font-size:.85rem;font-weight:700;color:var(--ink); }
.cps-l { font-family:'Sora',sans-serif;font-size:.56rem;color:var(--ink2);text-transform:uppercase;letter-spacing:.05em;margin-top:1px; }
.cp-acts { display:flex;gap:8px; }
.cp-act { flex:1;padding:9px;border-radius:6px;font-family:'Sora',sans-serif;font-size:.7rem;font-weight:600;cursor:pointer;transition:all .2s;text-align:center;text-decoration:none; }
.cp-act.f { background:var(--terra);color:white !important;border:none; } .cp-act.f:hover { background:var(--terra2); }
.cp-act.m { background:var(--bg2);border:1px solid var(--ink3);color:var(--ink2) !important; }

/* Badges flottants */
.badge-f { position:absolute;background:var(--card);border:1px solid var(--ink3);border-radius:12px;padding:9px 13px;box-shadow:0 10px 28px rgba(30,14,4,.1);animation:float 4s ease-in-out infinite;z-index:2; }
.badge-f.a { top:10%;left:-32px;animation-delay:.3s; } .badge-f.b { bottom:14%;right:-24px;animation-delay:.9s; }
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
.bf-l { font-family:'Sora',sans-serif;font-size:.52rem;text-transform:uppercase;letter-spacing:.07em;color:var(--ink2);margin-bottom:1px; }
.bf-v { font-family:'Unbounded',sans-serif;font-size:.85rem;font-weight:700;color:var(--terra); }
.bf-s { font-family:'Sora',sans-serif;font-size:.55rem;color:var(--ink2);margin-top:1px; }

/* Stats bas hero */
.hero-stats { position:relative;z-index:1;border-top:1px solid var(--ink3);display:flex;background:rgba(245,237,214,.7);backdrop-filter:blur(8px); }
.hs { flex:1;text-align:center;padding:18px;border-right:1px solid var(--ink3); } .hs:last-child { border-right:none; }
.hs-n { font-family:'Unbounded',sans-serif;font-size:1.4rem;font-weight:800;color:var(--ink);letter-spacing:-.02em; }
.hs-l { font-family:'Sora',sans-serif;font-size:.6rem;color:var(--ink2);text-transform:uppercase;letter-spacing:.07em;margin-top:2px; }

/* ══ TICKER ══ */
.ticker { position:relative;z-index:1;background:var(--terra);padding:10px 0;overflow:hidden;white-space:nowrap; }
.ticker::before,.ticker::after { content:'';position:absolute;top:0;bottom:0;width:40px;z-index:2; }
.ticker::before { left:0;background:linear-gradient(90deg,var(--terra),transparent); }
.ticker::after { right:0;background:linear-gradient(-90deg,var(--terra),transparent); }
.ticker-t { display:inline-flex;animation:tickr 24s linear infinite; }
@keyframes tickr{from{transform:translateX(0)}to{transform:translateX(-50%)}}
.tt { font-family:'Unbounded',sans-serif;font-size:.6rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.88);padding:0 24px;display:inline-flex;align-items:center;gap:12px; }
.tt-dot { width:4px;height:4px;background:rgba(255,255,255,.35);border-radius:50%;flex-shrink:0; }

/* ══ SECTIONS ══ */
.section { padding:88px 52px;position:relative; }
.section.alt { background:var(--bg2); }
.section::before { content:'';position:absolute;right:0;top:0;bottom:0;width:300px;background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C84818' stroke-width='0.8' opacity='0.13'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3Ccircle cx='30' cy='30' r='10'/%3E%3Ccircle cx='30' cy='30' r='3'/%3E%3Cline x1='10' y1='30' x2='50' y2='30'/%3E%3Cline x1='30' y1='10' x2='30' y2='50'/%3E%3Cpath d='M16 16 L44 44 M44 16 L16 44'/%3E%3C/g%3E%3C/svg%3E");background-size:60px 60px;pointer-events:none;opacity:.85; }
.s-eye { font-family:'Sora',sans-serif;font-size:.6rem;letter-spacing:.12em;text-transform:uppercase;color:var(--terra);margin-bottom:9px; }
.s-tit { font-family:'Unbounded',sans-serif;font-size:clamp(1.5rem,2.8vw,2.5rem);font-weight:800;letter-spacing:-.03em;line-height:1.1;color:var(--ink); }
.s-tit span { color:var(--terra); }
.s-head { display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:44px; }
.s-link { font-family:'Sora',sans-serif;font-size:.66rem;letter-spacing:.06em;text-transform:uppercase;color:var(--ink2) !important;text-decoration:none;border-bottom:1px solid var(--ink3);padding-bottom:2px;transition:all .2s;cursor:pointer; }
.s-link:hover { color:var(--terra) !important;border-color:var(--terra); }

/* ══ CRÉATEURS ══ */
.cr-row { display:flex;gap:12px;overflow-x:auto;padding-bottom:14px;scroll-snap-type:x mandatory;-ms-overflow-style:none;scrollbar-width:none; }
.cr-row::-webkit-scrollbar { display:none; }
.cr-card { flex:0 0 188px;scroll-snap-align:start;background:var(--card);border:1px solid var(--ink3);border-radius:16px;padding:18px 15px;transition:all .3s;position:relative;overflow:hidden;display:block;text-decoration:none;color:inherit; }
.cr-card::before { content:'';position:absolute;top:0;right:0;width:60px;height:60px;background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C84818' stroke-width='0.7' opacity='0.1'%3E%3Cpath d='M60 0 L30 30 L60 60'/%3E%3Cpath d='M60 15 L45 30 L60 45'/%3E%3Ccircle cx='55' cy='5' r='4'/%3E%3C/g%3E%3C/svg%3E");pointer-events:none;opacity:0;transition:opacity .3s; }
.cr-card::after { content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:var(--terra);transform:scaleX(0);transition:transform .3s;transform-origin:left; }
.cr-card:hover { border-color:rgba(200,72,24,.3);transform:translateY(-4px);box-shadow:0 18px 44px rgba(30,14,4,.1); }
.cr-card:hover::before { opacity:1; } .cr-card:hover::after { transform:scaleX(1); }
.cr-top { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:13px; }
.cr-av { width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;position:relative; }
.cr-ring { position:absolute;inset:-2px;border-radius:14px;border:2px solid var(--terra);opacity:0;transition:opacity .3s; }
.cr-card:hover .cr-ring { opacity:1; }
.cr-bdg { font-family:'Unbounded',sans-serif;font-size:.54rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;padding:3px 7px;border-radius:4px; }
.cr-bdg.v { background:rgba(200,72,24,.08);border:1px solid rgba(200,72,24,.18);color:var(--terra); }
.cr-bdg.n { background:rgba(26,90,48,.08);border:1px solid rgba(26,90,48,.18);color:var(--green); }
.cr-name { font-family:'Unbounded',sans-serif;font-size:.76rem;font-weight:700;color:var(--ink);margin-bottom:3px; }
.cr-niche { font-family:'Sora',sans-serif;font-size:.66rem;color:var(--ink2);margin-bottom:13px; }
.cr-stats { display:flex;gap:12px; }
.cr-sn { font-family:'Unbounded',sans-serif;font-size:.8rem;font-weight:700;color:var(--ink); }
.cr-sl { font-family:'Sora',sans-serif;font-size:.54rem;color:var(--ink2);text-transform:uppercase;letter-spacing:.05em; }

/* ══ MANIFESTE ══ */
.manifeste { padding:88px 52px;background:var(--bg2);border-top:1px solid var(--ink3);border-bottom:1px solid var(--ink3);position:relative;overflow:hidden; }
.manifeste::before { content:'';position:absolute;left:0;top:0;bottom:0;width:8px;background:repeating-linear-gradient(180deg,var(--terra) 0,var(--terra) 12px,var(--gold) 12px,var(--gold) 24px,#1E0E04 24px,#1E0E04 36px,var(--gold) 36px,var(--gold) 48px);opacity:.55; }
.manifeste::after { content:'';position:absolute;inset:0;background-image:url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%231E0E04' stroke-width='0.6' opacity='0.09'%3E%3Crect x='20' y='20' width='15' height='15'/%3E%3Crect x='65' y='20' width='15' height='15'/%3E%3Crect x='20' y='65' width='15' height='15'/%3E%3Crect x='65' y='65' width='15' height='15'/%3E%3Ccircle cx='50' cy='50' r='18'/%3E%3Cpath d='M27 27 L73 73 M73 27 L27 73'/%3E%3Cline x1='50' y1='5' x2='50' y2='95'/%3E%3Cline x1='5' y1='50' x2='95' y2='50'/%3E%3C/g%3E%3C/svg%3E");background-size:100px 100px;pointer-events:none; }
.manifeste-inner { position:relative;z-index:1;padding-left:32px;display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center; }
.m-quote { font-family:'Unbounded',sans-serif;font-size:clamp(1.3rem,2.2vw,2rem);font-weight:800;letter-spacing:-.03em;line-height:1.25;color:var(--ink);margin-bottom:20px; }
.m-quote span { color:var(--terra); }
.m-sub { font-family:'Sora',sans-serif;font-size:.86rem;line-height:1.82;color:var(--ink2);font-weight:300; }
.v-grid { display:grid;grid-template-columns:1fr 1fr;gap:12px; }
.v-item { background:rgba(245,237,214,.7);border:1px solid var(--ink3);border-radius:14px;padding:18px 16px;transition:all .3s;cursor:pointer;position:relative;overflow:hidden; }
.v-item::after { content:'';position:absolute;bottom:-8px;right:-8px;width:40px;height:40px;background-image:url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='14' fill='none' stroke='%23C84818' stroke-width='1' opacity='0.12'/%3E%3Ccircle cx='20' cy='20' r='7' fill='none' stroke='%23C84818' stroke-width='1' opacity='0.12'/%3E%3C/g%3E%3C/svg%3E");pointer-events:none; }
.v-item:hover { border-color:rgba(200,72,24,.25);box-shadow:0 6px 20px rgba(30,14,4,.06); }
.v-ico { font-size:1.4rem;margin-bottom:9px; }
.v-tit { font-family:'Unbounded',sans-serif;font-size:.66rem;font-weight:700;color:var(--ink);margin-bottom:5px; }
.v-dsc { font-family:'Sora',sans-serif;font-size:.7rem;line-height:1.6;color:var(--ink2);font-weight:300; }

/* ══ TARIFS ══ */
.tarifs { padding:88px 52px;position:relative;overflow:hidden; }
.tarifs::before { content:'';position:absolute;inset:0;background-image:url("data:image/svg+xml,%3Csvg width='50' height='50' viewBox='0 0 50 50' xmlns='http://www.w3.org/2000/svg'%3E%3Cpolygon points='25,5 45,40 5,40' fill='none' stroke='%23B87820' stroke-width='0.6' opacity='0.11'/%3E%3Cpolygon points='25,45 5,10 45,10' fill='none' stroke='%23C84818' stroke-width='0.5' opacity='0.08'/%3E%3C/svg%3E");background-size:50px 50px;pointer-events:none; }
.t-inner { position:relative;z-index:1; }
.pr-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:48px;max-width:880px; }
.pr-c { background:var(--card);border:1px solid var(--ink3);border-radius:20px;padding:30px 24px;position:relative;transition:all .3s; }
.pr-c:hover { border-color:rgba(200,72,24,.28);transform:translateY(-4px);box-shadow:0 18px 44px rgba(30,14,4,.1); }
.pr-c.hot { background:var(--terra);border-color:var(--terra); } .pr-c.hot:hover { background:var(--terra2); }
.pr-hot { position:absolute;top:-10px;left:22px;background:var(--gold);color:white !important;font-family:'Unbounded',sans-serif;font-size:.52rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:3px 10px;border-radius:100px; }
.pr-fl { font-size:1.6rem;margin-bottom:12px; }
.pr-nm { font-family:'Unbounded',sans-serif;font-size:.62rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink2);margin-bottom:16px; }
.pr-c.hot .pr-nm { color:rgba(255,255,255,.6); }
.pr-p { font-family:'Unbounded',sans-serif;font-size:2rem;font-weight:900;color:var(--ink);letter-spacing:-.03em;line-height:1; }
.pr-c.hot .pr-p { color:white; }
.pr-p sub { font-family:'Sora',sans-serif;font-size:.6rem;font-weight:400;color:var(--ink2);vertical-align:bottom;margin-left:2px; }
.pr-c.hot .pr-p sub { color:rgba(255,255,255,.55); }
.pr-free { font-family:'Unbounded',sans-serif;font-size:1.3rem;font-weight:900;color:var(--green); }
.pr-pd { font-family:'Sora',sans-serif;font-size:.65rem;color:var(--ink2);margin-top:3px;margin-bottom:22px; }
.pr-c.hot .pr-pd { color:rgba(255,255,255,.5); }
.pr-ul { list-style:none;display:flex;flex-direction:column;gap:7px;margin-bottom:22px; }
.pr-ul li { font-family:'Sora',sans-serif;font-size:.74rem;color:var(--ink2);display:flex;align-items:center;gap:6px; }
.pr-ul li::before { content:'✓';color:var(--terra);font-weight:700;flex-shrink:0; }
.pr-c.hot .pr-ul li { color:rgba(255,255,255,.72); } .pr-c.hot .pr-ul li::before { color:rgba(255,255,255,.55); }
.btn-pr { display:block;width:100%;text-align:center;padding:11px;border-radius:8px;font-family:'Unbounded',sans-serif;font-size:.62rem;font-weight:700;letter-spacing:.03em;transition:all .2s;text-decoration:none;cursor:pointer; }
.btn-pr.o { background:none;border:1px solid var(--ink3);color:var(--ink2) !important; }
.btn-pr.o:hover { border-color:var(--terra);color:var(--terra) !important;background:rgba(200,72,24,.04); }
.btn-pr.f { background:white;border:none;color:var(--terra) !important; } .btn-pr.f:hover { background:var(--bg); }

/* ══ CTA FINAL ══ */
.cta-s { padding:96px 52px;background:#1E0E04;position:relative;overflow:hidden;display:grid;grid-template-columns:1.4fr 1fr;gap:56px;align-items:center; }
.cta-s::before { content:'';position:absolute;inset:0;background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23F5EDD6' stroke-width='0.5' opacity='0.04'%3E%3Crect x='5' y='5' width='20' height='20'/%3E%3Crect x='35' y='5' width='20' height='20'/%3E%3Crect x='5' y='35' width='20' height='20'/%3E%3Crect x='35' y='35' width='20' height='20'/%3E%3Cline x1='0' y1='30' x2='60' y2='30'/%3E%3Cline x1='30' y1='0' x2='30' y2='60'/%3E%3C/g%3E%3C/svg%3E");background-size:60px 60px;pointer-events:none; }
.cta-kente { position:absolute;left:0;top:0;bottom:0;width:6px;background:repeating-linear-gradient(180deg,var(--terra) 0,var(--terra) 10px,var(--gold) 10px,var(--gold) 20px,rgba(245,237,214,.3) 20px,rgba(245,237,214,.3) 30px,var(--gold) 30px,var(--gold) 40px);opacity:.6; }
.cta-glow { position:absolute;inset:0;background:radial-gradient(ellipse 50% 70% at 35% 50%,rgba(200,72,24,.1) 0%,transparent 60%);pointer-events:none; }
.cta-l { position:relative;z-index:1; }
.cta-eye { font-family:'Sora',sans-serif;font-size:.6rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(245,237,214,.32);margin-bottom:14px; }
.cta-h { font-family:'Unbounded',sans-serif;font-size:clamp(1.8rem,3.8vw,3.5rem);font-weight:900;letter-spacing:-.04em;line-height:1;color:#F5EDD6;margin-bottom:12px; }
.cta-h span { color:var(--terra2); }
.cta-sub { font-family:'Sora',sans-serif;font-size:.86rem;color:rgba(245,237,214,.42);font-weight:300;line-height:1.75; }
.cta-r { position:relative;z-index:1;display:flex;flex-direction:column;gap:11px; }
.btn-ctam { background:var(--terra);color:white !important;border:none;padding:14px 26px;border-radius:8px;font-family:'Unbounded',sans-serif;font-size:.7rem;font-weight:700;letter-spacing:.02em;cursor:pointer;transition:all .25s;text-decoration:none;text-align:center;display:block; }
.btn-ctam:hover { background:var(--terra2);transform:translateY(-2px);box-shadow:0 12px 32px rgba(200,72,24,.35); }
.btn-ctag { background:none;border:1px solid rgba(245,237,214,.1);color:rgba(245,237,214,.42) !important;padding:14px 26px;border-radius:8px;font-family:'Sora',sans-serif;font-size:.78rem;cursor:pointer;transition:all .2s;text-decoration:none;text-align:center;display:block; }
.btn-ctag:hover { border-color:rgba(245,237,214,.28);color:rgba(245,237,214,.75) !important; }
.cta-note { font-family:'Sora',sans-serif;font-size:.6rem;color:rgba(245,237,214,.2);text-align:center; }

/* ══ FOOTER ══ */
.home-page footer { background:#1E0E04;padding:28px 52px;display:flex;justify-content:space-between;align-items:center;border-top:1px solid rgba(245,237,214,.05); }
.f-logo { font-family:'Unbounded',sans-serif;font-size:.78rem;font-weight:700;color:rgba(245,237,214,.3); }
.f-logo span { color:var(--terra); }
.f-links { display:flex;gap:18px;list-style:none; }
.f-links a { font-family:'Sora',sans-serif;font-size:.6rem;color:rgba(245,237,214,.2) !important;text-decoration:none;letter-spacing:.04em;transition:color .2s; }
.f-links a:hover { color:rgba(245,237,214,.55) !important; }
.f-copy { font-family:'Sora',sans-serif;font-size:.6rem;color:rgba(245,237,214,.16);letter-spacing:.04em; }

/* ══ ANIMATIONS ══ */
@keyframes fadeup{from{opacity:0;transform:translateY(26px)}to{opacity:1;transform:translateY(0)}}
.hero-pill { animation:fadeup .7s ease both; }
.hero-h1   { animation:fadeup .7s .1s ease both; }
.hero-desc { animation:fadeup .7s .2s ease both; }
.hero-btns { animation:fadeup .7s .3s ease both; }
.card-prof  { animation:fadeup .8s .15s ease both; }
.reveal { opacity:0;transform:translateY(22px);transition:opacity .7s ease,transform .7s ease; }
.reveal.visible { opacity:1;transform:translateY(0); }

/* ══ RESPONSIVE ══ */
@media(max-width:768px){
    .hero-inner { grid-template-columns:1fr;padding:32px 20px; }
    .hero-r,.hero-adinkra,.mudcloth-tl,.mudcloth-br { display:none; }
    .hero-stats { flex-wrap:wrap; } .hs { min-width:50%; }
    .section,.manifeste,.tarifs,.cta-s { padding:60px 20px; }
    .manifeste-inner,.cta-s { grid-template-columns:1fr; }
    .cr-grid { grid-template-columns:1fr 1fr; }
    .pr-grid { grid-template-columns:1fr; }
    .home-page footer { flex-direction:column;gap:16px;padding:24px 20px;align-items:flex-start; }
    .manifeste-inner { padding-left:16px; }
}

/* ══ THÈME SOMBRE ══ */
[data-theme="dark"] .home-page { --bg:#0D0905;--bg2:#141009;--card:#1C1810;--ink:#F0E8D8;--ink2:rgba(240,232,216,.55);--ink3:rgba(240,232,216,.18);--terra:#C8522A;--terra2:#E06030;--gold:#D4A843;--green:#2A7A48;background:#0D0905; }
[data-theme="dark"] .home-page::before { opacity:.3; }
[data-theme="dark"] .hero-stats { background:rgba(13,9,5,.8);border-top-color:rgba(240,232,216,.1); }
[data-theme="dark"] .hs { border-right-color:rgba(240,232,216,.1); }
[data-theme="dark"] .card-prof { background:#1C1810;border-color:rgba(240,232,216,.1); }
[data-theme="dark"] .cp-body { background:#1C1810; }
[data-theme="dark"] .cp-name { color:#F0E8D8 !important; }
[data-theme="dark"] .cp-av { border-color:#1C1810; }
[data-theme="dark"] .badge-f { background:#1C1810;border-color:rgba(240,232,216,.1);box-shadow:0 10px 28px rgba(0,0,0,.6); }
[data-theme="dark"] .cr-card { background:#1C1810;border-color:rgba(240,232,216,.1); }
[data-theme="dark"] .cr-name { color:#F0E8D8; } [data-theme="dark"] .cr-sn { color:#F0E8D8; }
[data-theme="dark"] .manifeste { background:#141009;border-color:rgba(240,232,216,.1); }
[data-theme="dark"] .v-item { background:rgba(28,24,16,.8);border-color:rgba(240,232,216,.1); }
[data-theme="dark"] .v-tit { color:#F0E8D8 !important; }
[data-theme="dark"] .pr-c { background:#1C1810;border-color:rgba(240,232,216,.1); }
[data-theme="dark"] .pr-p { color:#F0E8D8 !important; } [data-theme="dark"] .pr-nm { color:rgba(240,232,216,.45) !important; }
[data-theme="dark"] .cta-s { background:#0D0905 !important; }
[data-theme="dark"] .home-page footer { background:#141009 !important;border-top-color:rgba(240,232,216,.06) !important; }

/* ══ THÈME HOGWARTS ══ */
[data-theme="hogwarts"] .home-page { --bg:#05040F;--bg2:#0D0B1E;--card:#130F2A;--ink:#EFE5C8;--ink2:rgba(239,229,200,.55);--ink3:rgba(180,148,60,.18);--terra:#9B5FD1;--terra2:#B87AE8;--gold:#D4AF37;--green:#2A7A48;background:#05040F; }
[data-theme="hogwarts"] .home-page::before { opacity:.25; }
[data-theme="hogwarts"] .ticker { background:#2A1460; }
[data-theme="hogwarts"] .hero-pill { background:rgba(155,95,209,.15);border-color:rgba(155,95,209,.35);color:#D4AF37; }
[data-theme="hogwarts"] .pill-dot { background:#D4AF37; }
[data-theme="hogwarts"] .hero-h1 { color:#EFE5C8; } [data-theme="hogwarts"] .hero-h1 em { color:#D4AF37; }
[data-theme="hogwarts"] .hero-h1 .outline { -webkit-text-stroke-color:#9B5FD1; }
[data-theme="hogwarts"] .btn-main { background:linear-gradient(135deg,#6B35A8,#9B5FD1) !important; }
[data-theme="hogwarts"] .hero-stats { background:rgba(5,4,15,.8);border-top-color:rgba(180,148,60,.14); }
[data-theme="hogwarts"] .hs { border-right-color:rgba(180,148,60,.14); } [data-theme="hogwarts"] .hs-n { color:#D4AF37; }
[data-theme="hogwarts"] .card-prof { background:#0D0B1E;border-color:rgba(180,148,60,.2); }
[data-theme="hogwarts"] .cp-body { background:#0D0B1E; } [data-theme="hogwarts"] .cp-name { color:#EFE5C8 !important; }
[data-theme="hogwarts"] .badge-f { background:#0D0B1E;border-color:rgba(180,148,60,.18); }
[data-theme="hogwarts"] .cr-card { background:#0D0B1E;border-color:rgba(180,148,60,.12); }
[data-theme="hogwarts"] .cr-card::after { background:#D4AF37; } [data-theme="hogwarts"] .cr-ring { border-color:#D4AF37; }
[data-theme="hogwarts"] .cr-name { color:#EFE5C8; } [data-theme="hogwarts"] .cr-sn { color:#EFE5C8; }
[data-theme="hogwarts"] .manifeste { background:#0D0B1E;border-color:rgba(180,148,60,.12); }
[data-theme="hogwarts"] .v-item { background:rgba(13,11,30,.8);border-color:rgba(180,148,60,.12); }
[data-theme="hogwarts"] .v-tit { color:#EFE5C8 !important; }
[data-theme="hogwarts"] .pr-c { background:#0D0B1E;border-color:rgba(180,148,60,.14); }
[data-theme="hogwarts"] .pr-c.hot { background:linear-gradient(155deg,#1C1740,#2A1A55);border-color:rgba(180,148,60,.3); }
[data-theme="hogwarts"] .pr-p { color:#EFE5C8 !important; } [data-theme="hogwarts"] .pr-free { color:#D4AF37 !important; }
[data-theme="hogwarts"] .pr-ul li::before { color:#D4AF37; }
[data-theme="hogwarts"] .btn-ctam { background:linear-gradient(135deg,#6B35A8,#9B5FD1) !important; }
[data-theme="hogwarts"] .cta-s { background:#0D0B1E !important; }
[data-theme="hogwarts"] .home-page footer { background:#05040F !important;border-top-color:rgba(180,148,60,.06) !important; }
[data-theme="hogwarts"] .f-logo { color:rgba(239,229,200,.3) !important; }
[data-theme="hogwarts"] .f-links a { color:rgba(239,229,200,.2) !important; }
[data-theme="hogwarts"] .f-links a:hover { color:rgba(239,229,200,.55) !important; }
[data-theme="hogwarts"] .f-copy { color:rgba(239,229,200,.16) !important; }
</style>
@endpush

@section('content')
@php
    $fmt = fn($n) => $n >= 1000000 ? round($n/1000000,1).'M' : ($n >= 1000 ? round($n/1000,1).'K' : $n);
    $crBgs = [
        'linear-gradient(135deg,#3A1A08,#8B3A18)',
        'linear-gradient(135deg,#0D2015,#1A5A30)',
        'linear-gradient(135deg,#120D25,#2A1E60)',
        'linear-gradient(135deg,#1E0A1A,#5A1A5A)',
    ];
    $demoCreators = [
        ['emoji'=>'📸','name'=>'Fatou Diallo','niche'=>'Photographe · Dakar','bg'=>$crBgs[0],'ab'=>'8.4K','po'=>'342','b'=>'v','bt'=>'✓ Vérifié'],
        ['emoji'=>'🎵','name'=>'Ibou Seck','niche'=>'Musicien · Saint-Louis','bg'=>$crBgs[1],'ab'=>'21K','po'=>'187','b'=>'v','bt'=>'✓ Vérifié'],
        ['emoji'=>'🎨','name'=>'Cheikh Ndiaye','niche'=>'Artiste digital · Thiès','bg'=>$crBgs[2],'ab'=>'34K','po'=>'98','b'=>'n','bt'=>'🆕 Nouveau'],
        ['emoji'=>'👗','name'=>'Mariama Ba','niche'=>'Styliste · Ziguinchor','bg'=>$crBgs[3],'ab'=>'15K','po'=>'214','b'=>'v','bt'=>'✓ Vérifié'],
    ];
@endphp
<div class="home-page">

{{-- HERO --}}
<section class="hero">

    {{-- Grand adinkra SVG --}}
    <svg class="hero-adinkra" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="200" cy="200" r="190" stroke="#C84818" stroke-width="2"/>
        <circle cx="200" cy="200" r="150" stroke="#C84818" stroke-width="1.5"/>
        <circle cx="200" cy="200" r="110" stroke="#B87820" stroke-width="1"/>
        <circle cx="200" cy="200" r="70" stroke="#C84818" stroke-width="1.5"/>
        <circle cx="200" cy="200" r="30" stroke="#B87820" stroke-width="1"/>
        <line x1="10" y1="200" x2="390" y2="200" stroke="#C84818" stroke-width="1"/>
        <line x1="200" y1="10" x2="200" y2="390" stroke="#C84818" stroke-width="1"/>
        <line x1="55" y1="55" x2="345" y2="345" stroke="#B87820" stroke-width=".8"/>
        <line x1="345" y1="55" x2="55" y2="345" stroke="#B87820" stroke-width=".8"/>
        <path d="M200 10 L260 110 L140 110 Z" stroke="#C84818" stroke-width="1" fill="none"/>
        <path d="M200 390 L260 290 L140 290 Z" stroke="#C84818" stroke-width="1" fill="none"/>
        <path d="M10 200 L110 140 L110 260 Z" stroke="#B87820" stroke-width="1" fill="none"/>
        <path d="M390 200 L290 140 L290 260 Z" stroke="#B87820" stroke-width="1" fill="none"/>
        <rect x="185" y="100" width="30" height="200" rx="4" stroke="#C84818" stroke-width="1" fill="none"/>
        <rect x="100" y="185" width="200" height="30" rx="4" stroke="#C84818" stroke-width="1" fill="none"/>
    </svg>

    {{-- Mudcloth coins --}}
    <svg class="mudcloth-tl" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="10" y="10" width="30" height="30" stroke="#C84818" stroke-width="1.2"/>
        <rect x="50" y="10" width="30" height="30" stroke="#B87820" stroke-width="1"/>
        <rect x="10" y="50" width="30" height="30" stroke="#B87820" stroke-width="1"/>
        <path d="M10 10 L40 40 M40 10 L10 40" stroke="#C84818" stroke-width=".8"/>
        <path d="M50 10 L80 40 M80 10 L50 40" stroke="#B87820" stroke-width=".8"/>
        <path d="M10 50 L40 80 M40 50 L10 80" stroke="#C84818" stroke-width=".8"/>
        <circle cx="100" cy="30" r="15" stroke="#C84818" stroke-width="1"/>
        <circle cx="100" cy="30" r="6" stroke="#B87820" stroke-width=".8"/>
        <circle cx="30" cy="110" r="15" stroke="#B87820" stroke-width="1"/>
        <line x1="0" y1="95" x2="200" y2="95" stroke="#C84818" stroke-width=".6"/>
        <line x1="95" y1="0" x2="95" y2="200" stroke="#B87820" stroke-width=".6"/>
    </svg>
    <svg class="mudcloth-br" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="160" y="160" width="30" height="30" stroke="#C84818" stroke-width="1.2"/>
        <rect x="120" y="160" width="30" height="30" stroke="#B87820" stroke-width="1"/>
        <rect x="160" y="120" width="30" height="30" stroke="#B87820" stroke-width="1"/>
        <path d="M160 160 L190 190 M190 160 L160 190" stroke="#C84818" stroke-width=".8"/>
        <circle cx="100" cy="160" r="15" stroke="#C84818" stroke-width="1"/>
        <circle cx="160" cy="100" r="15" stroke="#B87820" stroke-width="1"/>
        <line x1="0" y1="105" x2="200" y2="105" stroke="#C84818" stroke-width=".6"/>
        <line x1="105" y1="0" x2="105" y2="200" stroke="#B87820" stroke-width=".6"/>
    </svg>

    <div class="hero-inner">
        <div>
            <div class="hero-pill"><div class="pill-dot"></div> Réseau des créateurs africains</div>
            <h1 class="hero-h1">
                Ta<br>
                <em>créativité</em><br>
                mérite une<br>
                <span class="outline">vraie scène.</span>
            </h1>
            <p class="hero-desc">MelanoGeek connecte photographes, musiciens, artistes et stylistes sénégalais. Partage ton art, construis ta communauté. 100% gratuit pour le Sénégal.</p>
            <div class="hero-btns">
                @guest
                    <a href="{{ route('register') }}" class="btn-main">Créer mon profil →</a>
                    <a href="{{ route('login') }}" class="btn-sec"><div class="play-o">▶</div> Se connecter</a>
                @else
                    <a href="{{ route('feed') }}" class="btn-main">Voir mon fil →</a>
                    <a href="{{ route('profile.show', auth()->user()->username) }}" class="btn-sec"><div class="play-o">👤</div> Mon profil</a>
                @endguest
            </div>
        </div>

        <div class="hero-r">
            <div class="kente-bg"></div>
            <div class="badge-f a">
                <div class="bf-l">Nouveaux abonnés</div>
                <div class="bf-v">+{{ $fmt($stats['new_members_month']) }}</div>
                <div class="bf-s">ce mois · Dakar 🇸🇳</div>
            </div>
            <div class="card-prof">
                @if($topCreator)
                <div class="cp-cover" style="{{ $topCreator->cover_photo ? 'background:url('.asset('storage/'.$topCreator->cover_photo).') center/cover no-repeat;' : '' }}">
                    @if(!$topCreator->cover_photo)🎨@endif
                    <div class="cp-live"><div class="cp-live-d"></div> Top créateur</div>
                </div>
                <div class="cp-body">
                    <div class="cp-top">
                        <div class="cp-av" style="{{ $topCreator->avatar ? '' : 'font-size:1.1rem;' }}">
                            @if($topCreator->avatar)
                                <img src="{{ asset('storage/'.$topCreator->avatar) }}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;" alt="">
                            @else
                                {{ mb_strtoupper(mb_substr($topCreator->username, 0, 1)) }}
                            @endif
                        </div>
                        <span class="cp-bdg">{{ $topCreator->is_verified ? '✓ Vérifié' : '🆕 Créateur' }}</span>
                    </div>
                    <div class="cp-name">{{ $topCreator->username }}</div>
                    <div class="cp-niche">🎨 {{ $topCreator->niche ?: 'Créateur' }}{{ $topCreator->location ? ' · '.$topCreator->location : '' }}</div>
                    <div class="cp-stats">
                        <div class="cps"><div class="cps-n">{{ $fmt($topCreator->followers_count) }}</div><div class="cps-l">Abonnés</div></div>
                        <div class="cps"><div class="cps-n">{{ $topCreator->published_posts }}</div><div class="cps-l">Posts</div></div>
                        <div class="cps"><div class="cps-n">#1</div><div class="cps-l">Rang</div></div>
                    </div>
                    <div class="cp-acts">
                        <a href="{{ route('profile.show', $topCreator->username) }}" class="cp-act f">Voir le profil →</a>
                        @auth
                            @if(auth()->id() !== $topCreator->id)
                                <a href="{{ route('messages.show', $topCreator) }}" class="cp-act m">💬 Message</a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="cp-act m">💬 Message</a>
                        @endauth
                    </div>
                </div>
                @else
                {{-- Fallback si aucun créateur en base --}}
                <div class="cp-cover">🎨
                    <div class="cp-live"><div class="cp-live-d"></div> Bientôt</div>
                </div>
                <div class="cp-body">
                    <div class="cp-top">
                        <div class="cp-av">🌟</div>
                        <span class="cp-bdg">Créateur</span>
                    </div>
                    <div class="cp-name">Ton profil ici</div>
                    <div class="cp-niche">🎨 Deviens le premier créateur</div>
                    <div class="cp-stats">
                        <div class="cps"><div class="cps-n">0</div><div class="cps-l">Abonnés</div></div>
                        <div class="cps"><div class="cps-n">0</div><div class="cps-l">Posts</div></div>
                        <div class="cps"><div class="cps-n">#1</div><div class="cps-l">Rang</div></div>
                    </div>
                    <div class="cp-acts">
                        <a href="{{ route('register') }}" class="cp-act f">Créer mon profil →</a>
                        <a href="{{ route('register') }}" class="cp-act m">💬 Message</a>
                    </div>
                </div>
                @endif
            </div>
            <div class="badge-f b">
                <div class="bf-l">Publications</div>
                <div class="bf-v">{{ $fmt($stats['posts']) }}</div>
                <div class="bf-s">sur la plateforme</div>
            </div>
        </div>
    </div>

    <div class="hero-stats">
        <div class="hs"><div class="hs-n">{{ $fmt($stats['members']) }}</div><div class="hs-l">Membres</div></div>
        <div class="hs"><div class="hs-n">{{ $fmt($stats['creators']) }}</div><div class="hs-l">Créateurs</div></div>
        <div class="hs"><div class="hs-n">{{ $fmt($stats['posts']) }}</div><div class="hs-l">Publications</div></div>
        <div class="hs"><div class="hs-n">🇸🇳</div><div class="hs-l">Fait à Dakar</div></div>
    </div>
</section>

{{-- TICKER --}}
@php
$tickerNiches = [
    '🎵 Musique','📸 Photographie','👗 Mode & Style','💄 Beauté & Soins',
    '🍽️ Cuisine','🎬 Vidéo & Vlog','🎨 Art & Illustration','💃 Danse',
    '😂 Comédie & Humour','💼 Business','🌍 Voyage & Culture','⚽ Sport & Fitness',
    '🪡 Artisanat','📚 Éducation','🎙️ Podcast','✨ Lifestyle',
];
@endphp
<div class="ticker">
    <div class="ticker-t">
        @foreach(array_merge($tickerNiches, $tickerNiches) as $tn)
            <span class="tt">{{ $tn }}<span class="tt-dot"></span></span>
        @endforeach
    </div>
</div>

{{-- CRÉATEURS --}}
@php
$nicheEmojis = [
    'Musique'=>'🎵','Photographie'=>'📸','Mode & Style'=>'👗','Beauté & Soins'=>'💄',
    'Cuisine'=>'🍽️','Vidéo & Vlog'=>'🎬','Art & Illustration'=>'🎨','Danse'=>'💃',
    'Comédie & Humour'=>'😂','Business'=>'💼','Voyage & Culture'=>'🌍','Sport & Fitness'=>'⚽',
    'Artisanat'=>'🪡','Éducation'=>'📚','Podcast'=>'🎙️','Lifestyle'=>'✨',
    // anciens labels (compat)
    'Photographe'=>'📸','Musicien'=>'🎵','Vidéaste'=>'🎬','Artiste digital'=>'🎨',
    'Styliste'=>'👗','Danseur'=>'💃','Cuisinier'=>'🍽️','Podcasteur'=>'🎙️',
    'Illustrateur'=>'✏️','Comédien'=>'🎭','Influenceur'=>'⭐',
];
@endphp
<section class="section reveal">
    <div class="s-head">
        <div><div class="s-eye">Nos créateurs</div><h2 class="s-tit">Ils créent.<br><span>Tu découvres.</span></h2></div>
        <a href="{{ route('creators') }}" class="s-link">Voir tous →</a>
    </div>
    <div class="cr-row">
        @if($featuredCreators->isNotEmpty())
            @foreach($featuredCreators as $i => $cr)
            @php $ne = $nicheEmojis[$cr->niche ?? ''] ?? '🌟'; @endphp
            <a href="{{ route('profile.show', $cr->username) }}" class="cr-card">
                <div class="cr-top">
                    <div class="cr-av" style="background:{{ $crBgs[$i % 4] }};">
                        @if($cr->avatar)
                            <img src="{{ asset('storage/'.$cr->avatar) }}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;" alt="">
                        @else
                            {{ mb_strtoupper(mb_substr($cr->username, 0, 1)) }}
                        @endif
                        <div class="cr-ring"></div>
                    </div>
                    <span class="cr-bdg {{ $cr->is_verified ? 'v' : 'n' }}">{{ $cr->is_verified ? '✓ Vérifié' : '🆕 Nouveau' }}</span>
                </div>
                <div class="cr-name">{{ $cr->username }}</div>
                <div class="cr-niche">{{ $ne }} {{ $cr->niche ?: ($cr->location ?: 'Créateur') }}</div>
                <div class="cr-stats">
                    <div><div class="cr-sn">{{ $fmt($cr->followers_count) }}</div><div class="cr-sl">Abonnés</div></div>
                    <div><div class="cr-sn">{{ $cr->published_posts }}</div><div class="cr-sl">Posts</div></div>
                </div>
            </a>
            @endforeach
        @else
            @foreach($demoCreators as $c)
            <div class="cr-card">
                <div class="cr-top">
                    <div class="cr-av" style="background:{{ $c['bg'] }};">{{ $c['emoji'] }}<div class="cr-ring"></div></div>
                    <span class="cr-bdg {{ $c['b'] }}">{{ $c['bt'] }}</span>
                </div>
                <div class="cr-name">{{ $c['name'] }}</div>
                <div class="cr-niche">{{ $c['emoji'] }} {{ $c['niche'] }}</div>
                <div class="cr-stats">
                    <div><div class="cr-sn">{{ $c['ab'] }}</div><div class="cr-sl">Abonnés</div></div>
                    <div><div class="cr-sn">{{ $c['po'] }}</div><div class="cr-sl">Posts</div></div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</section>

{{-- MANIFESTE --}}

<section class="manifeste reveal">
    <div class="manifeste-inner">
        <div>
            <div class="s-eye">Notre mission</div>
            <div class="m-quote">La créativité africaine mérite une plateforme à sa <span>hauteur.</span></div>
            <div class="m-sub">Pas d'algorithme qui punit. Pas de barrières. Juste de la création pure, une vraie communauté, et un espace où le talent sénégalais rayonne au-delà des frontières.</div>
        </div>
        <div class="v-grid">
            <div class="v-item"><div class="v-ico">🇸🇳</div><div class="v-tit">100% Local</div><div class="v-dsc">Conçu à Dakar, pour les créateurs sénégalais en premier.</div></div>
            <div class="v-item"><div class="v-ico">🎁</div><div class="v-tit">1 mois gratuit</div><div class="v-dsc">Essai gratuit d'un mois pour tous les Sénégalais.</div></div>
            <div class="v-item"><div class="v-ico">⚡</div><div class="v-tit">Sans censure</div><div class="v-dsc">Ton contenu est vu par ceux qui t'ont choisi.</div></div>
            <div class="v-item"><div class="v-ico">💳</div><div class="v-tit">Paiement local</div><div class="v-dsc">Wave et Orange Money. Pas besoin de carte.</div></div>
        </div>
    </div>
</section>

{{-- TARIFS --}}
<section class="tarifs reveal" id="tarifs">
    <div class="t-inner">
        <div class="s-eye">Nos offres</div>
        <h2 class="s-tit">Simple.<br><span>Transparent.</span></h2>
        <div class="pr-grid">
            <div class="pr-c">
                <div class="pr-fl">
                    <svg width="32" height="21" viewBox="0 0 32 21" xmlns="http://www.w3.org/2000/svg" style="border-radius:3px;display:block;">
                        <rect width="11" height="21" fill="#00A859"/>
                        <rect x="10" width="12" height="21" fill="#FDEF42"/>
                        <rect x="21" width="11" height="21" fill="#E31B23"/>
                        <polygon points="16,4.5 17,7.5 20.2,7.5 17.6,9.3 18.6,12.3 16,10.5 13.4,12.3 14.4,9.3 11.8,7.5 15,7.5" fill="#00A859"/>
                    </svg>
                </div><div class="pr-nm">Sénégal</div>
                <div class="pr-free">GRATUIT</div>
                <div class="pr-pd">1 mois offert · Aucune carte</div>
                <ul class="pr-ul"><li>Profil créateur complet</li><li>Publications illimitées</li><li>Messagerie directe</li><li>Accès au marketplace</li></ul>
                <a href="{{ route('register') }}" class="btn-pr o">Créer mon compte</a>
            </div>
            <div class="pr-c hot">
                <div class="pr-hot">🔥 Populaire</div>
                <div class="pr-fl">🌍</div><div class="pr-nm">Afrique</div>
                <div class="pr-p">2 500<sub>FCFA/mois</sub></div>
                <div class="pr-pd">~4€ · Wave & Orange Money</div>
                <ul class="pr-ul"><li>Tout du plan Sénégal</li><li>Badge 🌍 Afrique sur ton profil</li><li>Paiement Wave & Orange Money</li><li>Réponse support sous 48h</li></ul>
                @auth
                    <a href="{{ route('subscription.checkout', 'african') }}" class="btn-pr f">Commencer →</a>
                @else
                    <a href="{{ route('register') }}" class="btn-pr f">Commencer →</a>
                @endauth
            </div>
            <div class="pr-c">
                <div class="pr-fl">✈️</div><div class="pr-nm">Diaspora</div>
                <div class="pr-p">9,99<sub>€/mois</sub></div>
                <div class="pr-pd">Stripe · Carte bancaire</div>
                <ul class="pr-ul"><li>Tout du plan Afrique</li><li>Badge ✈️ Diaspora sur ton profil</li><li>Paiement par carte bancaire</li><li>Réponse support sous 24h</li></ul>
                @auth
                    <a href="{{ route('subscription.checkout', 'global') }}" class="btn-pr o">S'abonner</a>
                @else
                    <a href="{{ route('register') }}" class="btn-pr o">S'abonner</a>
                @endauth
            </div>
        </div>
    </div>
</section>

{{-- CTA FINAL --}}
@guest
<section class="cta-s reveal">
    <div class="cta-kente"></div>
    <div class="cta-glow"></div>
    <div class="cta-l">
        <div class="cta-eye">Rejoins la communauté</div>
        <h2 class="cta-h">Prêt à faire<br><span>rayonner</span><br>ton art ?</h2>
        <p class="cta-sub">Inscription gratuite en 2 minutes. Aucune carte requise pour les Sénégalais.</p>
    </div>
    <div class="cta-r">
        <a href="{{ route('register') }}" class="btn-ctam">Créer mon profil gratuitement</a>
        <a href="{{ route('explore') }}" class="btn-ctag">Explorer les créateurs</a>
        <div class="cta-note">🇸🇳 Fait à Dakar avec ❤️</div>
    </div>
</section>
@endguest

{{-- FOOTER --}}
<footer>
    <div class="f-logo">Melano<span>Geek</span></div>
    <ul class="f-links">
        <li><a href="{{ route('about') }}">À propos</a></li>
        <li><a href="#">Confidentialité</a></li>
        <li><a href="#">CGU</a></li>
        <li><a href="#">Contact</a></li>
    </ul>
    <div class="f-copy">© {{ date('Y') }} MelanoGeek · Dakar 🇸🇳</div>
</footer>

</div>
@endsection

@push('scripts')
<script>
    const obs = new IntersectionObserver(e => e.forEach(el => {
        if (el.isIntersecting) el.target.classList.add('visible');
    }), { threshold: .1 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
</script>
@endpush
