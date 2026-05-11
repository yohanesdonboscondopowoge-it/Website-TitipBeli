@extends('layouts.app')

@section('title', 'Jelajah Perjalanan')

@section('content')
<style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body.trips-page{background:#08080f!important;color:#f0f0f5!important;overflow-x:hidden}

    /* ═══ BG ═══ */
    .trips-bg{position:fixed;inset:0;z-index:0;pointer-events:none}
    .trips-bg-photo{
        position:absolute;inset:0;
        background:url('https://i1-c.pinimg.com/1200x/f2/32/6a/f2326aad4c4f77beaef072a45fed18ce.jpg') center/cover no-repeat;
        opacity:0;transition:opacity 2s ease;transform:scale(1.03);
    }
    .trips-bg-photo.loaded{opacity:.40}
    .trips-bg-fallback{position:absolute;inset:0;background:#08080f}
    .trips-bg-overlay{
        position:absolute;inset:0;
        background:linear-gradient(180deg,rgba(8,8,15,.68) 0%,rgba(8,8,15,.58) 40%,rgba(8,8,15,.82) 100%);
    }
    .trips-bg-grid{
        position:absolute;inset:0;
        background-image:linear-gradient(rgba(201,162,39,.012) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.012) 1px,transparent 1px);
        background-size:80px 80px;
        mask-image:radial-gradient(ellipse at 50% 20%,black 5%,transparent 55%);-webkit-mask-image:radial-gradient(ellipse at 50% 20%,black 5%,transparent 55%);
    }
    .trips-deco-ring{
        position:fixed;border-radius:50%;border:1px solid rgba(201,162,39,.04);pointer-events:none;z-index:0;
        animation:ringPulse 7s ease-in-out infinite;
    }
    @keyframes ringPulse{0%,100%{opacity:.05;transform:scale(1)}50%{opacity:.1;transform:scale(1.04)}}

    .trips-content{position:relative;z-index:1;max-width:1100px;margin:0 auto;padding:32px 20px 60px}

    /* ═══ HEADER ═══ */
    .trips-header{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:32px}
    .trips-header-left{display:flex;align-items:center;gap:14px}
    .trips-header-icon{
        width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;
        background:linear-gradient(145deg,rgba(201,162,39,.1),rgba(201,162,39,.03));
        border:1px solid rgba(201,162,39,.1);position:relative;overflow:hidden;flex-shrink:0;
    }
    .trips-header-icon::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.08),transparent 50%);pointer-events:none}
    .trips-header-icon svg{width:22px;height:22px;color:rgba(201,162,39,.7);position:relative;z-index:1}
    .trips-header h1{font-family:'Inter',sans-serif;font-size:clamp(22px,3.5vw,28px);font-weight:800;color:#f0f0f5;letter-spacing:-.02em}
    .trips-header p{font-size:13px;color:rgba(255,255,255,.25);font-weight:300;margin-top:2px}

    .btn-gold{
        display:inline-flex;align-items:center;gap:8px;padding:11px 26px;
        background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);
        background-size:200% 200%;color:#0c0c18;font-family:'Inter',sans-serif;
        font-size:13.5px;font-weight:700;border:none;border-radius:12px;
        text-decoration:none;cursor:pointer;position:relative;overflow:hidden;
        box-shadow:0 2px 20px rgba(201,162,39,.15),inset 0 1px 0 rgba(255,255,255,.2);
        transition:all .35s cubic-bezier(.23,1,.32,1);
    }
    .btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 35px rgba(201,162,39,.28);background-position:100% 100%}
    .btn-gold:active{transform:translateY(0) scale(.98)}
    .btn-gold svg{width:16px;height:16px}

    /* ═══ SEARCH ═══ */
    .search-bar{
        display:flex;flex-wrap:wrap;align-items:center;gap:10px;padding:16px 20px;border-radius:18px;
        background:rgba(255,255,255,.03);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
        border:1px solid rgba(255,255,255,.05);margin-bottom:12px;
    }
    .search-field{display:flex;align-items:center;gap:10px;flex:1;min-width:150px}
    .search-field svg{width:16px;height:16px;color:rgba(255,255,255,.15);flex-shrink:0}
    .search-input{
        width:100%;padding:10px 14px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);
        border-radius:11px;color:rgba(255,255,255,.85);font-size:13.5px;font-family:'Inter',sans-serif;outline:none;transition:all .3s;
    }
    .search-input::placeholder{color:rgba(255,255,255,.15)}
    .search-input:focus{background:rgba(255,255,255,.05);border-color:rgba(201,162,39,.18)}
    .search-input[type="date"]::-webkit-calendar-picker-indicator{filter:invert(.7);opacity:.4;cursor:pointer}
    .search-divider{width:1px;height:28px;background:rgba(255,255,255,.04);flex-shrink:0}
    .search-btn{
        display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:11px;
        background:linear-gradient(135deg,rgba(201,162,39,.85),rgba(176,138,34,.85));
        color:#0c0c18;font-family:'Inter',sans-serif;font-size:13.5px;font-weight:700;
        border:none;cursor:pointer;transition:all .3s;flex-shrink:0;
    }
    .search-btn:hover{box-shadow:0 4px 20px rgba(201,162,39,.2);transform:translateY(-1px)}
    .search-btn svg{width:15px;height:15px}

    /* ═══ TAGS ═══ */
    .city-tags{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:32px;overflow-x:auto;padding-bottom:2px}
    .city-tags-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.18);flex-shrink:0;padding:0 4px}
    .city-tag{
        display:inline-flex;align-items:center;gap:5px;padding:7px 16px;border-radius:10px;
        font-size:12px;font-weight:500;color:rgba(255,255,255,.35);text-decoration:none;
        background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.04);transition:all .3s;flex-shrink:0;
    }
    .city-tag:hover{background:rgba(201,162,39,.06);border-color:rgba(201,162,39,.12);color:rgba(201,162,39,.8)}
    .city-tag.active{background:rgba(201,162,39,.08);border-color:rgba(201,162,39,.15);color:rgba(201,162,39,.9)}
    .city-tag-reset{background:rgba(239,68,68,.05);border-color:rgba(239,68,68,.1);color:rgba(248,113,113,.75)}
    .city-tag-reset:hover{background:rgba(239,68,68,.08);border-color:rgba(239,68,68,.15);color:rgba(248,113,113,.9)}

    /* ═══ FEATURED ═══ */
    .featured-card{
        position:relative;padding:36px;border-radius:24px;overflow:hidden;margin-bottom:40px;
        background:rgba(255,255,255,.03);backdrop-filter:blur(36px);-webkit-backdrop-filter:blur(36px);
        border:1px solid rgba(255,255,255,.05);
    }
    .featured-card::before{
        content:'';position:absolute;top:0;left:12%;right:12%;height:1px;
        background:linear-gradient(90deg,transparent,rgba(201,162,39,.12),transparent);pointer-events:none;
    }
    .featured-card::after{
        content:'';position:absolute;bottom:0;left:20%;right:20%;height:2px;border-radius:2px;
        background:linear-gradient(90deg,rgba(201,162,39,.4),rgba(212,176,64,.6),rgba(201,162,39,.4));
    }
    .featured-inner{position:relative;z-index:1;display:flex;flex-direction:column;gap:16px}
    .featured-badges{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
    .f-badge{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:4px 12px;border-radius:7px}
    .fb-featured{background:rgba(201,162,39,.1);border:1px solid rgba(201,162,39,.15);color:rgba(201,162,39,.9)}
    .fb-open{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.12);color:rgba(74,222,128,.9)}
    .fb-full{background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.12);color:rgba(251,191,36,.9)}
    .fb-done{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);color:rgba(255,255,255,.25)}

    .transport-badge{
        display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:7px;
        font-size:10.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;white-space:nowrap;
    }
    .transport-badge svg{width:13px;height:13px}
    .tp-pesawat{background:rgba(201,162,39,.1);border:1px solid rgba(201,162,39,.15);color:rgba(201,162,39,.85)}
    .tp-kereta{background:rgba(139,92,246,.08);border:1px solid rgba(139,92,246,.12);color:rgba(167,139,250,.85)}
    .tp-mobil{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.12);color:rgba(74,222,128,.85)}
    .tp-bus{background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.12);color:rgba(251,191,36,.85)}
    .tp-motor{background:rgba(244,114,182,.08);border:1px solid rgba(244,114,182,.12);color:rgba(244,114,182,.85)}
    .tp-kapal{background:rgba(6,182,212,.08);border:1px solid rgba(6,182,212,.12);color:rgba(34,211,238,.85)}
    .tp-default{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);color:rgba(255,255,255,.35)}

    .featured-user{display:flex;align-items:center;gap:12px}
    .featured-avatar{width:44px;height:44px;border-radius:50%;padding:1.5px;flex-shrink:0;background:linear-gradient(135deg,rgba(201,162,39,.5),rgba(176,138,34,.4))}
    .featured-avatar-inner{width:100%;height:100%;border-radius:50%;background:#12121e;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:rgba(255,255,255,.8)}
    .featured-user-name{font-size:14px;font-weight:600;color:rgba(255,255,255,.8)}
    .featured-user-meta{display:flex;align-items:center;gap:6px;margin-top:2px}
    .featured-user-meta svg{width:12px;height:12px;color:rgba(201,162,39,.6)}
    .featured-user-meta span{font-size:12px;color:rgba(201,162,39,.65);font-weight:600}
    .featured-user-meta .meta-dot{color:rgba(255,255,255,.12);font-size:10px}

    .featured-route{display:flex;align-items:center;gap:20px;flex-wrap:wrap}
    .featured-route-city{text-align:center}
    .featured-route-city .lbl{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.18);margin-bottom:4px}
    .featured-route-city .city-name{font-size:clamp(20px,3vw,28px);font-weight:800;color:#f0f0f5}
    .featured-route-arrow{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:rgba(201,162,39,.06);border:1px solid rgba(201,162,39,.08)}
    .featured-route-arrow svg{width:16px;height:16px;color:rgba(201,162,39,.5)}

    .featured-info{display:flex;align-items:center;gap:16px;flex-wrap:wrap}
    .featured-info-item{display:flex;align-items:center;gap:8px}
    .featured-info-icon{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .featured-info-icon svg{width:14px;height:14px}
    .featured-info-item span{font-size:13px;color:rgba(255,255,255,.4);font-weight:400}

    .featured-slot{display:flex;align-items:center;gap:10px;margin-bottom:4px}
    .featured-slot-bar{flex:1;max-width:160px;height:4px;background:rgba(255,255,255,.06);border-radius:2px;overflow:hidden}
    .featured-slot-fill{height:100%;border-radius:2px;transition:width .5s}
    .slot-gold{background:linear-gradient(90deg,#c9a227,#d4b040);box-shadow:0 0 8px rgba(201,162,39,.3)}
    .slot-warn{background:linear-gradient(90deg,#f59e0b,#fbbf24);box-shadow:0 0 8px rgba(245,158,11,.3)}
    .slot-red{background:linear-gradient(90deg,#ef4444,#f87171);box-shadow:0 0 8px rgba(239,68,68,.3)}
    .featured-slot-text{font-size:12px;font-weight:700;min-width:40px}

    .btn-outline{
        display:inline-flex;align-items:center;gap:8px;padding:11px 26px;border-radius:12px;
        background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
        color:rgba(255,255,255,.65);font-family:'Inter',sans-serif;font-size:13.5px;font-weight:600;
        text-decoration:none;cursor:pointer;transition:all .3s;
    }
    .btn-outline:hover{background:rgba(255,255,255,.07);border-color:rgba(255,255,255,.12);color:rgba(255,255,255,.85)}
    .btn-outline svg{width:16px;height:16px}

    .featured-right{display:none}
    @media(min-width:768px){
        .featured-inner{flex-direction:row;align-items:center;gap:40px}
        .featured-right{display:flex;flex-shrink:0;align-items:center;justify-content:center;width:200px}
        .featured-orb{width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,.025);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.05);display:flex;align-items:center;justify-content:center}
        .featured-orb-inner{width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.04);display:flex;align-items:center;justify-content:center}
        .featured-orb-icon{width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,rgba(201,162,39,.08),rgba(201,162,39,.02));border:1px solid rgba(201,162,39,.08);display:flex;align-items:center;justify-content:center}
        .featured-orb-icon svg{width:28px;height:28px;color:rgba(201,162,39,.5)}
    }

    /* ═══ SECTION TITLE ═══ */
    .section-title{display:flex;align-items:center;gap:12px;margin-bottom:8px}
    .section-title-bar{width:3px;height:20px;border-radius:2px;background:linear-gradient(180deg,rgba(201,162,39,.6),rgba(201,162,39,.25))}
    .section-title h2{font-size:17px;font-weight:700;color:rgba(255,255,255,.8)}
    .section-subtitle{font-size:12px;color:rgba(255,255,255,.18);margin-bottom:24px;padding-left:15px}

    /* ═══ TRIP CARD ═══ */
    .card-grid{display:grid;grid-template-columns:1fr;gap:16px;margin-bottom:32px}
    @media(min-width:768px){.card-grid{grid-template-columns:repeat(2,1fr)}}
    @media(min-width:1100px){.card-grid{grid-template-columns:repeat(3,1fr);gap:20px}}

    .trip-card{
        position:relative;border-radius:20px;overflow:hidden;
        background:rgba(12,12,24,.5);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
        border:1px solid rgba(255,255,255,.05);transition:all .45s cubic-bezier(.23,1,.32,1);
    }
    .trip-card::before{
        content:'';position:absolute;top:0;left:8%;right:8%;height:1px;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,.06),transparent);pointer-events:none;z-index:5;
    }
    .trip-card:hover{
        background:rgba(12,12,24,.6);border-color:rgba(201,162,39,.1);
        transform:translateY(-4px);box-shadow:0 20px 60px rgba(0,0,0,.3);
    }
    .trip-card .glow-line{position:absolute;top:0;left:0;right:0;height:2px;opacity:0;transition:opacity .5s;z-index:6}
    .trip-card:hover .glow-line{opacity:1}
    .glow-open{background:linear-gradient(90deg,transparent 5%,rgba(74,222,128,.35) 50%,transparent 95%)}
    .glow-full{background:linear-gradient(90deg,transparent 5%,rgba(251,191,36,.35) 50%,transparent 95%)}
    .glow-done{background:linear-gradient(90deg,transparent 5%,rgba(255,255,255,.06) 50%,transparent 95%)}

    .card-route{
        position:relative;height:80px;display:flex;align-items:center;padding:0 20px;
        background:linear-gradient(135deg,rgba(201,162,39,.02),rgba(201,162,39,.01));
    }
    .card-route-line{
        position:absolute;top:50%;left:80px;right:80px;height:1px;
        background:repeating-linear-gradient(90deg,rgba(201,162,39,.12) 0px,rgba(201,162,39,.12) 6px,transparent 6px,transparent 12px);
        pointer-events:none;
    }
    .card-route-city{position:relative;z-index:2;min-width:60px}
    .card-route-city .r-name{font-size:13px;font-weight:700;color:rgba(255,255,255,.88)}
    .card-route-city .r-label{font-size:9px;letter-spacing:.12em;text-transform:uppercase;font-weight:600;color:rgba(255,255,255,.16);margin-top:2px}
    .card-route-mid{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;position:relative;z-index:2}
    .card-route-arrow{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:rgba(201,162,39,.05);border:1px solid rgba(201,162,39,.06)}
    .card-route-arrow svg{width:13px;height:13px;color:rgba(201,162,39,.4)}
    .card-transport{font-size:9px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:2px 8px;border-radius:5px;white-space:nowrap}

    .card-head{padding:16px 20px 12px}
    .card-head-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:12px}
    .card-user{display:flex;align-items:center;gap:10px;min-width:0}
    .card-avatar{width:28px;height:28px;border-radius:50%;padding:1px;flex-shrink:0;background:linear-gradient(135deg,rgba(201,162,39,.4),rgba(176,138,34,.3))}
    .card-avatar-inner{width:100%;height:100%;border-radius:50%;background:#12121e;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:rgba(255,255,255,.7)}
    .card-user-info{min-width:0}
    .card-user-name{font-size:12px;font-weight:600;color:rgba(255,255,255,.75);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100px}
    .card-user-rating{display:flex;align-items:center;gap:3px;margin-top:1px}
    .card-user-rating svg{width:10px;height:10px;color:rgba(201,162,39,.5)}
    .card-user-rating span{font-size:10px;color:rgba(201,162,39,.6);font-weight:500}
    .card-status{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;padding:3px 10px;border-radius:6px;flex-shrink:0}
    .cs-open{background:rgba(34,197,94,.08);color:rgba(74,222,128,.85)}
    .cs-full{background:rgba(245,158,11,.08);color:rgba(251,191,36,.85)}
    .cs-done{background:rgba(255,255,255,.03);color:rgba(255,255,255,.2)}

    .card-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px}
    .card-info-box{background:rgba(255,255,255,.015);border:1px solid rgba(255,255,255,.025);border-radius:10px;padding:10px 12px}
    .card-info-box .lbl{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.15);margin-bottom:4px}
    .card-info-box .val{font-size:12px;font-weight:600;color:rgba(255,255,255,.5)}

    .card-slot{margin-bottom:10px}
    .card-slot-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:4px}
    .card-slot-bar{height:3px;background:rgba(255,255,255,.06);border-radius:2px;overflow:hidden}
    .card-slot-fill{height:100%;border-radius:2px;transition:width .5s}
    .card-slot-text{font-size:10px;font-weight:700}

    .card-note{background:rgba(201,162,39,.02);border-left:2px solid rgba(201,162,39,.1);border-radius:0 8px 8px 0;padding:8px 12px}
    .card-note p{font-size:11px;color:rgba(255,255,255,.28);line-height:1.5}

    .card-body{padding:0 20px 56px}

    .card-dock{
        position:absolute;bottom:0;left:0;right:0;
        background:rgba(8,8,15,.85);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
        padding:10px 16px;opacity:0;transform:translateY(6px);
        transition:all .35s cubic-bezier(.23,1,.32,1);pointer-events:none;
        display:flex;align-items:center;gap:8px;
    }
    .trip-card:hover .card-dock{opacity:1;transform:translateY(0);pointer-events:auto}
    .dock-btn{
        flex:1;display:flex;align-items:center;justify-content:center;gap:6px;
        padding:8px 0;border-radius:9px;font-size:12px;font-weight:600;text-decoration:none;transition:all .25s;
    }
    .dock-btn svg{width:14px;height:14px}
    .dock-view{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);color:rgba(255,255,255,.55)}
    .dock-view:hover{background:rgba(255,255,255,.07);color:rgba(255,255,255,.8)}
    .dock-action{background:linear-gradient(135deg,rgba(201,162,39,.8),rgba(176,138,34,.8));color:#0c0c18;box-shadow:0 2px 16px rgba(201,162,39,.15)}
    .dock-action:hover{box-shadow:0 4px 24px rgba(201,162,39,.3)}

    /* ═══ PAGINATION ═══ */
    .trips-pagination{display:flex;justify-content:center}
    .trips-pagination .pagination{display:flex;gap:4px;flex-wrap:wrap;justify-content:center}
    .trips-pagination .pagination a,
    .trips-pagination .pagination span{
        display:inline-flex;align-items:center;justify-content:center;
        min-width:38px;height:38px;padding:0 10px;border-radius:10px;
        font-size:13px;font-weight:500;color:rgba(255,255,255,.35);
        background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);
        text-decoration:none;transition:all .3s;
    }
    .trips-pagination .pagination a:hover{background:rgba(201,162,39,.06);border-color:rgba(201,162,39,.12);color:rgba(201,162,39,.8)}
    .trips-pagination .pagination .active,
    .trips-pagination .pagination .page-link.active{background:rgba(201,162,39,.12);border-color:rgba(201,162,39,.2);color:#c9a227;font-weight:700}
    .trips-pagination .pagination .disabled span{opacity:.25}

    /* ═══ EMPTY ═══ */
    .trips-empty{
        text-align:center;padding:80px 32px;border-radius:24px;
        background:rgba(12,12,24,.4);border:1px solid rgba(255,255,255,.04);
        backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);
    }
    .trips-empty-icon{
        width:88px;height:88px;margin:0 auto 28px;border-radius:24px;
        background:linear-gradient(145deg,rgba(201,162,39,.08),rgba(201,162,39,.02));
        border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center;
        position:relative;overflow:hidden;
    }
    .trips-empty-icon::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.06),transparent 50%);pointer-events:none}
    .trips-empty-icon svg{width:40px;height:40px;color:rgba(201,162,39,.3);position:relative;z-index:1}
    .trips-empty h2{font-size:22px;font-weight:700;color:rgba(255,255,255,.6);margin-bottom:8px}
    .trips-empty p{font-size:14px;color:rgba(255,255,255,.2);margin-bottom:36px;line-height:1.6;max-width:400px;margin-left:auto;margin-right:auto}

    /* ═══ ANIMATIONS ═══ */
    @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
    .fade-up{opacity:0;animation:fadeUp .65s cubic-bezier(.23,1,.32,1) forwards}
    .d1{animation-delay:.05s}.d2{animation-delay:.12s}.d3{animation-delay:.2}
    .d4{animation-delay:.28s}.d5{animation-delay:.36s}
    .card-anim{opacity:0;transform:translateY(20px);transition:all .5s cubic-bezier(.23,1,.32,1)}
    .card-anim.visible{opacity:1;transform:translateY(0)}

    @media(max-width:767px){
        .trips-content{padding:20px 14px 48px}
        .search-field{min-width:120px}
        .featured-route{gap:14px}
        .featured-route-city .city-name{font-size:20px}
    }
    @media(prefers-reduced-motion:reduce){
        .fade-up,.card-anim{animation:none!important;transition:none!important;opacity:1!important;transform:none!important}
        *{animation:none!important;transition-duration:0s!important}
    }
</style>

<!-- Background -->
<div class="trips-bg">
    <div class="trips-bg-fallback"></div>
    <div class="trips-bg-photo" id="tripsBgPhoto"></div>
    <div class="trips-bg-overlay"></div>
    <div class="trips-bg-grid"></div>
</div>
<div class="trips-deco-ring" style="width:350px;height:350px;top:5%;right:-5%"></div>
<div class="trips-deco-ring" style="width:220px;height:220px;bottom:15%;left:-3%;animation-delay:-3s"></div>

<div class="trips-content">

    <!-- Header -->
    <div class="trips-header fade-up d1">
        <div class="trips-header-left">
            <div class="trips-header-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
            </div>
            <div>
                <h1>Jelajah Perjalanan</h1>
                <p>Temukan traveller untuk bantu titip barang</p>
            </div>
        </div>
        @auth
            <a href="{{ route('trips.create') }}" class="btn-gold">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Posting Perjalanan
            </a>
        @endauth
    </div>

    <!-- Search -->
    <div class="fade-up d2">
        <form action="{{ route('trips.index') }}" method="GET">
            <div class="search-bar">
                <div class="search-field">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    <input type="text" name="origin" value="{{ request('origin') }}" placeholder="Kota asal..." class="search-input">
                </div>
                <div class="search-divider"></div>
                <div class="search-field">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>
                    <input type="text" name="destination" value="{{ request('destination') }}" placeholder="Kota tujuan..." class="search-input">
                </div>
                <div class="search-divider"></div>
                <div class="search-field" style="min-width:140px">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <input type="date" name="date" value="{{ request('date') }}" class="search-input">
                </div>
                <button type="submit" class="search-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    Cari
                </button>
            </div>
        </form>

        <div class="city-tags">
            <span class="city-tags-label">Populer</span>
            <a href="{{ route('trips.index', ['origin' => 'Jakarta']) }}" class="city-tag">Jakarta</a>
            <a href="{{ route('trips.index', ['destination' => 'Surabaya']) }}" class="city-tag">Surabaya</a>
            <a href="{{ route('trips.index', ['destination' => 'Bandung']) }}" class="city-tag">Bandung</a>
            <a href="{{ route('trips.index', ['origin' => 'Surabaya', 'destination' => 'Jakarta']) }}" class="city-tag">SBI → JKT</a>
            <a href="{{ route('trips.index', ['destination' => 'Malang']) }}" class="city-tag">Malang</a>
            <a href="{{ route('trips.index', ['destination' => 'Yogyakarta']) }}" class="city-tag">Yogyakarta</a>
            @if(request('origin') || request('destination') || request('date'))
                <a href="{{ route('trips.index') }}" class="city-tag city-tag-reset">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </a>
            @endif
        </div>
    </div>

    @php
        $featured = $trips->firstWhere('status', 'open') ?? $trips->first();
        $fMode = strtolower($featured->transport_mode ?? '');
        $fTC = str_contains($fMode,'pesawat') ? 'tp-pesawat' : (str_contains($fMode,'kereta') ? 'tp-kereta' : (str_contains($fMode,'mobil') ? 'tp-mobil' : (str_contains($fMode,'bus') ? 'tp-bus' : (str_contains($fMode,'motor') ? 'tp-motor' : (str_contains($fMode,'kapal') ? 'tp-kapal' : 'tp-default')))));
    @endphp

    @if($featured && $trips->count() > 0)
        <!-- Featured -->
        <div class="featured-card fade-up d3">
            <div class="featured-inner">
                <div style="flex:1">
                    <div class="featured-badges">
                        <span class="f-badge fb-featured">Featured</span>
                        <span class="f-badge {{ $featured->status === 'open' ? 'fb-open' : ($featured->status === 'full' ? 'fb-full' : 'fb-done') }}">
                            {{ $featured->status === 'open' ? 'Slot Tersedia' : ($featured->status === 'full' ? 'Penuh' : 'Selesai') }}
                        </span>
                        <span class="transport-badge {{ $fTC }}">
                            @if(str_contains($fMode,'pesawat'))
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                            @elseif(str_contains($fMode,'kereta'))
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                            @elseif(str_contains($fMode,'mobil'))
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                            @elseif(str_contains($fMode,'bus'))
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                            @elseif(str_contains($fMode,'motor'))
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif(str_contains($fMode,'kapal'))
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            @else
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                            @endif
                            {{ $featured->transport_mode ?? 'Perjalanan' }}
                        </span>
                    </div>

                    <div class="featured-user">
                        <div class="featured-avatar"><div class="featured-avatar-inner">{{ strtoupper(substr($featured->user->name ?? 'U', 0, 1)) }}</div></div>
                        <div>
                            <p class="featured-user-name">{{ $featured->user->name ?? 'Unknown' }}</p>
                            <div class="featured-user-meta">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span>{{ number_format($featured->user->rating_avg ?? 0, 1) }}</span>
                                <span class="meta-dot">•</span>
                                <span style="color:rgba(255,255,255,.25)">{{ $featured->departure_date->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="featured-route" style="margin:8px 0">
                        <div class="featured-route-city"><p class="lbl">Dari</p><p class="city-name">{{ $featured->origin_city }}</p></div>
                        <div class="featured-route-arrow"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg></div>
                        <div class="featured-route-city"><p class="lbl">Ke</p><p class="city-name">{{ $featured->destination_city }}</p></div>
                    </div>

                    <div class="featured-info" style="margin-bottom:12px">
                        <div class="featured-info-item">
                            <div class="featured-info-icon" style="background:rgba(201,162,39,.06)">
                                <svg fill="none" stroke="rgba(201,162,39,.5)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            </div>
                            <span>{{ $featured->baggage_capacity ?? 'Standar' }}</span>
                        </div>
                        <div class="featured-info-item">
                            <div class="featured-info-icon" style="background:rgba(201,162,39,.06)">
                                <svg fill="none" stroke="rgba(201,162,39,.5)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            </div>
                            <span>{{ $featured->remaining_slots }}/{{ $featured->max_requests }} slot</span>
                        </div>
                    </div>

                    @php
                        $fSlotPct = $featured->max_requests > 0 ? (($featured->max_requests - $featured->remaining_slots) / $featured->max_requests) * 100 : 0;
                        $fSlotCls = $featured->remaining_slots === 0 ? 'slot-red' : ($fSlotPct > 70 ? 'slot-warn' : 'slot-gold');
                        $fSlotColor = $featured->remaining_slots === 0 ? 'color:rgba(239,68,68,.8)' : ($fSlotPct > 70 ? 'color:rgba(251,191,36,.8)' : 'color:rgba(201,162,39,.8)');
                    @endphp
                    <div class="featured-slot">
                        <div class="featured-slot-bar"><div class="featured-slot-fill {{ $fSlotCls }}" style="width:{{ $fSlotPct }}%"></div></div>
                        <span class="featured-slot-text" style="{{ $fSlotColor }}">{{ $featured->remaining_slots }}/{{ $featured->max_requests }}</span>
                    </div>

                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:16px">
                        <a href="{{ route('trips.show', $featured) }}" class="btn-gold">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Lihat Detail
                        </a>
                        @auth
                            @if($featured->status === 'open' && $featured->user_id !== Auth::id())
                                <a href="{{ route('trips.show', $featured) }}#order-form" class="btn-outline">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                                    Titip Barang
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="featured-right">
                    <div class="featured-orb">
                        <div class="featured-orb-inner">
                            <div class="featured-orb-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($trips->count() > 0)
        <!-- Section Title -->
        <div class="fade-up d4">
            <div class="section-title"><div class="section-title-bar"></div><h2>Semua Perjalanan</h2></div>
            <p class="section-subtitle">{{ $trips->count() }} perjalanan ditemukan</p>
        </div>

        <!-- Grid -->
        <div class="card-grid">
            @foreach($trips as $trip)
                @php
                    $tM = strtolower($trip->transport_mode ?? '');
                    $tC = str_contains($tM,'pesawat') ? 'tp-pesawat' : (str_contains($tM,'kereta') ? 'tp-kereta' : (str_contains($tM,'mobil') ? 'tp-mobil' : (str_contains($tM,'bus') ? 'tp-bus' : (str_contains($tM,'motor') ? 'tp-motor' : (str_contains($tM,'kapal') ? 'tp-kapal' : 'tp-default')))));
                    $sPct = $trip->max_requests > 0 ? (($trip->max_requests - $trip->remaining_slots) / $trip->max_requests) * 100 : 0;
                    $sCls = $trip->remaining_slots === 0 ? 'slot-red' : ($sPct > 70 ? 'slot-warn' : 'slot-gold');
                    $sColor = $trip->remaining_slots === 0 ? 'color:rgba(239,68,68,.8)' : ($sPct > 70 ? 'color:rgba(251,191,36,.8)' : 'color:rgba(201,162,39,.8)');
                    $glowCls = $trip->status === 'open' ? 'glow-open' : ($trip->status === 'full' ? 'glow-full' : 'glow-done');
                @endphp

                <div class="trip-card card-anim">
                    <div class="glow-line {{ $glowCls }}"></div>

                    <div class="card-route">
                        <div class="card-route-line"></div>
                        <div class="card-route-city"><p class="r-name">{{ $trip->origin_city }}</p><p class="r-label">Dari</p></div>
                        <div class="card-route-mid">
                            <div class="card-route-arrow"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg></div>
                            <span class="card-transport {{ $tC }}">
                                @if(str_contains($tM,'pesawat'))
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:10px;height:10px"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                @elseif(str_contains($tM,'kereta'))
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:10px;height:10px"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                @else
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:10px;height:10px"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                @endif
                                {{ $trip->transport_mode ?? 'Trip' }}
                            </span>
                        </div>
                        <div class="card-route-city" style="text-align:right"><p class="r-name">{{ $trip->destination_city }}</p><p class="r-label">Ke</p></div>
                    </div>

                    <div class="card-body">
                        <div class="card-head">
                            <div class="card-head-top">
                                <div class="card-user">
                                    <div class="card-avatar"><div class="card-avatar-inner">{{ strtoupper(substr($trip->user->name ?? 'U', 0, 1)) }}</div></div>
                                    <div class="card-user-info">
                                        <p class="card-user-name">{{ $trip->user->name ?? 'Unknown' }}</p>
                                        <div class="card-user-rating">
                                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            <span>{{ number_format($trip->user->rating_avg ?? 0, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="card-status {{ $trip->status === 'open' ? 'cs-open' : ($trip->status === 'full' ? 'cs-full' : 'cs-done') }}">
                                    {{ $trip->status === 'open' ? 'Buka' : ($trip->status === 'full' ? 'Penuh' : 'Selesai') }}
                                </span>
                            </div>

                            <div class="card-info-grid">
                                <div class="card-info-box">
                                    <p class="lbl">Berangkat</p>
                                    <p class="val">{{ $trip->departure_date->format('d M Y') }}</p>
                                </div>
                                <div class="card-info-box">
                                    <p class="lbl">Kapasitas</p>
                                    <p class="val">{{ $trip->baggage_capacity ?? 'Standar' }}</p>
                                </div>
                            </div>

                            <div class="card-slot">
                                <div class="card-slot-header">
                                    <span class="lbl">Slot</span>
                                    <span class="card-slot-text" style="{{ $sColor }}">{{ $trip->remaining_slots }}/{{ $trip->max_requests }}</span>
                                </div>
                                <div class="card-slot-bar"><div class="card-slot-fill {{ $sCls }}" style="width:{{ $sPct }}%"></div></div>
                            </div>

                            @if($trip->notes)
                                <div class="card-note"><p>{{ Str::limit($trip->notes, 80) }}</p></div>
                            @endif
                        </div>
                    </div>

                    <div class="card-dock">
                        <a href="{{ route('trips.show', $trip) }}" class="dock-btn dock-view">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Detail
                        </a>
                        @auth
                            @if($trip->status === 'open' && $trip->user_id !== Auth::id())
                                <a href="{{ route('trips.show', $trip) }}#order-form" class="dock-btn dock-action">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                                    Titip
                                </a>
                            @else
                                <div style="flex:1"></div>
                            @endif
                        @else
                            <div style="flex:1"></div>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <div class="trips-pagination">{{ $trips->links() }}</div>
    @else

        <div class="trips-empty fade-up d3">
            <div class="trips-empty-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
            </div>
            <h2>Belum Ada Perjalanan</h2>
            <p>Jadi yang pertama posting perjalananmu dan bantu orang titip barang!</p>
            @auth
                <a href="{{ route('trips.create') }}" class="btn-gold">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Posting Perjalanan
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-gold">Daftar Sekarang</a>
            @endauth
        </div>

    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var bgPhoto = document.getElementById('tripsBgPhoto');

    if (bgPhoto) {
        var img = new Image();
        img.onload = function() { bgPhoto.classList.add('loaded'); };
        img.src = 'https://i.pinimg.com/736x/91/e2/b2/91e2b24b89293458e673c1840d6c39cc.jpg';
    }

    if (!reducedMotion) {
        var cards = document.querySelectorAll('.card-anim');
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.06, rootMargin: '0px 0px -20px 0px' });
        cards.forEach(function(c) { observer.observe(c); });
    } else {
        document.querySelectorAll('.card-anim').forEach(function(c) { c.classList.add('visible'); });
    }

    if (window.innerWidth >= 1024 && !reducedMotion) {
        document.addEventListener('mousemove', function(e) {
            if (!bgPhoto) return;
            var x = (e.clientX / window.innerWidth - 0.5) * -10;
            var y = (e.clientY / window.innerHeight - 0.5) * -10;
            bgPhoto.style.transform = 'scale(1.05) translate(' + x + 'px, ' + y + 'px)';
            bgPhoto.style.transition = 'transform 0.3s ease-out';
        });
    }
});
</script>
@endsection