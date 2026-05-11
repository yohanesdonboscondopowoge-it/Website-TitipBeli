@extends('layouts.app')

@section('title', 'Jelajah Permintaan Titip')

@section('content')
<style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body.requests-page{background:#08080f!important;color:#f0f0f5!important;overflow-x:hidden}

    /* ═══ BG ═══ */
    .req-bg{position:fixed;inset:0;z-index:0;pointer-events:none}
    .req-bg-photo{
        position:absolute;inset:0;
        background:url('https://i.pinimg.com/736x/e9/85/9b/e9859bcd90c3b9933f71d826eb608267.jpg') center/cover no-repeat;
        opacity:0;transition:opacity 2s ease;transform:scale(1.03);
    }
    .req-bg-photo.loaded{opacity:.40}
    .req-bg-fallback{position:absolute;inset:0;background:#08080f}
    .req-bg-overlay{
        position:absolute;inset:0;
        background:linear-gradient(180deg,rgba(8,8,15,.7) 0%,rgba(8,8,15,.6) 40%,rgba(8,8,15,.82) 100%);
    }
    .req-bg-grid{
        position:absolute;inset:0;
        background-image:linear-gradient(rgba(201,162,39,.012) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.012) 1px,transparent 1px);
        background-size:80px 80px;
        mask-image:radial-gradient(ellipse at 50% 20%,black 5%,transparent 55%);-webkit-mask-image:radial-gradient(ellipse at 50% 20%,black 5%,transparent 55%);
    }
    .req-deco-ring{
        position:fixed;border-radius:50%;border:1px solid rgba(201,162,39,.04);pointer-events:none;z-index:0;
        animation:ringPulse 7s ease-in-out infinite;
    }
    @keyframes ringPulse{0%,100%{opacity:.05;transform:scale(1)}50%{opacity:.1;transform:scale(1.04)}}

    .req-content{position:relative;z-index:1;max-width:1100px;margin:0 auto;padding:32px 20px 60px}

    /* ═══ HEADER ═══ */
    .req-header{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:32px}
    .req-header-left{display:flex;align-items:center;gap:14px}
    .req-header-icon{
        width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;
        background:linear-gradient(145deg,rgba(201,162,39,.1),rgba(201,162,39,.03));
        border:1px solid rgba(201,162,39,.1);position:relative;overflow:hidden;flex-shrink:0;
    }
    .req-header-icon::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.08),transparent 50%);pointer-events:none}
    .req-header-icon svg{width:22px;height:22px;color:rgba(201,162,39,.7);position:relative;z-index:1}
    .req-header h1{font-family:'Inter',sans-serif;font-size:clamp(22px,3.5vw,28px);font-weight:800;color:#f0f0f5;letter-spacing:-.02em}
    .req-header p{font-size:13px;color:rgba(255,255,255,.25);font-weight:300;margin-top:2px}

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

    /* ═══ SEARCH BAR ═══ */
    .search-bar{
        display:flex;flex-wrap:wrap;align-items:center;gap:10px;padding:16px 20px;border-radius:18px;
        background:rgba(255,255,255,.03);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
        border:1px solid rgba(255,255,255,.05);margin-bottom:12px;
    }
    .search-field{display:flex;align-items:center;gap:10px;flex:1;min-width:160px}
    .search-field svg{width:16px;height:16px;color:rgba(255,255,255,.15);flex-shrink:0}
    .search-input{
        width:100%;padding:10px 14px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);
        border-radius:11px;color:rgba(255,255,255,.85);font-size:13.5px;font-family:'Inter',sans-serif;
        outline:none;transition:all .3s;
    }
    .search-input::placeholder{color:rgba(255,255,255,.15)}
    .search-input:focus{background:rgba(255,255,255,.05);border-color:rgba(201,162,39,.18)}
    .search-divider{width:1px;height:28px;background:rgba(255,255,255,.04);flex-shrink:0}
    .search-select{
        padding:10px 36px 10px 14px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);
        border-radius:11px;color:rgba(255,255,255,.85);font-size:13.5px;font-family:'Inter',sans-serif;
        outline:none;transition:all .3s;appearance:none;flex:1;min-width:140px;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='rgba(255,255,255,0.2)' viewBox='0 0 16 16'%3E%3Cpath d='M8 12L2 6h12L8 12z'/%3E%3C/svg%3E");
        background-repeat:no-repeat;background-position:right 12px center;
    }
    .search-select:focus{background-color:rgba(255,255,255,.05);border-color:rgba(201,162,39,.18)}
    .search-select option{background:#12121e;color:rgba(255,255,255,.8)}
    .search-btn{
        display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:11px;
        background:linear-gradient(135deg,rgba(201,162,39,.85),rgba(176,138,34,.85));
        color:#0c0c18;font-family:'Inter',sans-serif;font-size:13.5px;font-weight:700;
        border:none;cursor:pointer;transition:all .3s;flex-shrink:0;
    }
    .search-btn:hover{box-shadow:0 4px 20px rgba(201,162,39,.2);transform:translateY(-1px)}
    .search-btn svg{width:15px;height:15px}

    /* ═══ CATEGORY TAGS ═══ */
    .cat-tags{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:32px;overflow-x:auto;padding-bottom:2px}
    .cat-tags-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.18);flex-shrink:0;padding:0 4px}
    .cat-tag{
        display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border-radius:10px;
        font-size:12px;font-weight:500;color:rgba(255,255,255,.35);text-decoration:none;
        background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.04);
        transition:all .3s;flex-shrink:0;
    }
    .cat-tag:hover{background:rgba(201,162,39,.06);border-color:rgba(201,162,39,.12);color:rgba(201,162,39,.8)}
    .cat-tag.active{background:rgba(201,162,39,.08);border-color:rgba(201,162,39,.15);color:rgba(201,162,39,.9)}
    .cat-tag svg{width:13px;height:13px}
    .cat-tag-reset{
        background:rgba(239,68,68,.05);border-color:rgba(239,68,68,.1);color:rgba(248,113,113,.75);
    }
    .cat-tag-reset:hover{background:rgba(239,68,68,.08);border-color:rgba(239,68,68,.15);color:rgba(248,113,113,.9)}

    /* ═══ FEATURED CARD ═══ */
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
    .featured-inner{position:relative;z-index:1;display:flex;flex-direction:column;gap:20px}
    .featured-top{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
    .featured-badge{
        font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;
        padding:4px 12px;border-radius:7px;
    }
    .fb-featured{background:rgba(201,162,39,.1);border:1px solid rgba(201,162,39,.15);color:rgba(201,162,39,.9)}
    .fb-open{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.12);color:rgba(74,222,128,.9)}
    .fb-done{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);color:rgba(255,255,255,.25)}
    .featured-cat{
        display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;
        font-size:11px;font-weight:600;
    }
    .fc-makanan{background:rgba(251,191,36,.08);color:rgba(251,191,36,.85)}
    .fc-elektronik{background:rgba(59,130,246,.08);color:rgba(96,165,250,.85)}
    .fc-fashion{background:rgba(244,114,182,.08);color:rgba(244,114,182,.85)}
    .fc-dokumen{background:rgba(34,197,94,.08);color:rgba(74,222,128,.85)}
    .fc-default{background:rgba(255,255,255,.04);color:rgba(255,255,255,.35)}
    .featured-cat svg{width:13px;height:13px}

    .featured-user{display:flex;align-items:center;gap:12px}
    .featured-avatar{
        width:44px;height:44px;border-radius:50%;padding:1.5px;flex-shrink:0;
        background:linear-gradient(135deg,rgba(201,162,39,.5),rgba(176,138,34,.4));
    }
    .featured-avatar-inner{
        width:100%;height:100%;border-radius:50%;background:#12121e;
        display:flex;align-items:center;justify-content:center;
        font-size:15px;font-weight:700;color:rgba(255,255,255,.8);
    }
    .featured-user-name{font-size:14px;font-weight:600;color:rgba(255,255,255,.8)}
    .featured-user-rating{display:flex;align-items:center;gap:4px;margin-top:2px}
    .featured-user-rating svg{width:12px;height:12px;color:rgba(201,162,39,.6)}
    .featured-user-rating span{font-size:12px;color:rgba(201,162,39,.7);font-weight:600}

    .featured-title{font-size:clamp(24px,3.5vw,34px);font-weight:800;color:#f0f0f5;letter-spacing:-.025em;line-height:1.15}
    .featured-budget{
        display:inline-flex;align-items:center;gap:8px;padding:8px 18px;border-radius:14px;
        background:rgba(201,162,39,.07);border:1px solid rgba(201,162,39,.1);
    }
    .featured-budget svg{width:16px;height:16px;color:rgba(201,162,39,.6)}
    .featured-budget span{font-size:15px;font-weight:700;color:rgba(201,162,39,.9)}

    .featured-route{display:flex;align-items:center;gap:20px;flex-wrap:wrap}
    .featured-route-city{text-align:center}
    .featured-route-city .lbl{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.18);margin-bottom:4px}
    .featured-route-city .city-name{font-size:20px;font-weight:800;color:#f0f0f5}
    .featured-route-arrow{
        width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;
        background:rgba(201,162,39,.06);border:1px solid rgba(201,162,39,.08);
    }
    .featured-route-arrow svg{width:16px;height:16px;color:rgba(201,162,39,.5)}

    .featured-meta{display:flex;align-items:center;gap:16px;flex-wrap:wrap}
    .featured-meta-item{display:flex;align-items:center;gap:8px}
    .featured-meta-icon{
        width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;
    }
    .featured-meta-icon svg{width:14px;height:14px}
    .featured-meta-item span{font-size:13px;color:rgba(255,255,255,.4);font-weight:400}

    .featured-right{display:none}

    /* ═══ SECTION TITLE ═══ */
    .section-title{display:flex;align-items:center;gap:12px;margin-bottom:8px}
    .section-title-bar{width:3px;height:20px;border-radius:2px;background:linear-gradient(180deg,rgba(201,162,39,.6),rgba(201,162,39,.25))}
    .section-title h2{font-size:17px;font-weight:700;color:rgba(255,255,255,.8)}
    .section-subtitle{font-size:12px;color:rgba(255,255,255,.18);margin-bottom:24px;padding-left:15px}

    /* ═══ REQUEST CARD ═══ */
    .card-grid{display:grid;grid-template-columns:1fr;gap:16px;margin-bottom:32px}
    @media(min-width:768px){.card-grid{grid-template-columns:repeat(2,1fr)}}
    @media(min-width:1100px){.card-grid{grid-template-columns:repeat(3,1fr);gap:20px}}

    .req-card{
        position:relative;border-radius:20px;overflow:hidden;
        background:rgba(12,12,24,.5);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
        border:1px solid rgba(255,255,255,.05);transition:all .45s cubic-bezier(.23,1,.32,1);
    }
    .req-card::before{
        content:'';position:absolute;top:0;left:8%;right:8%;height:1px;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,.06),transparent);pointer-events:none;z-index:5;
    }
    .req-card:hover{
        background:rgba(12,12,24,.6);border-color:rgba(201,162,39,.1);
        transform:translateY(-4px);box-shadow:0 20px 60px rgba(0,0,0,.3);
    }
    .req-card .glow-line{position:absolute;top:0;left:0;right:0;height:2px;opacity:0;transition:opacity .5s;z-index:6}
    .req-card:hover .glow-line{opacity:1}
    .glow-open{background:linear-gradient(90deg,transparent 5%,rgba(74,222,128,.35) 50%,transparent 95%)}
    .glow-done{background:linear-gradient(90deg,transparent 5%,rgba(255,255,255,.06) 50%,transparent 95%)}

    .card-head{padding:18px 20px 16px;background:rgba(255,255,255,.01);border-bottom:1px solid rgba(255,255,255,.03)}
    .card-head-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:12px}
    .card-user{display:flex;align-items:center;gap:10px;min-width:0}
    .card-avatar{
        width:32px;height:32px;border-radius:50%;padding:1px;flex-shrink:0;
        background:linear-gradient(135deg,rgba(201,162,39,.4),rgba(176,138,34,.3));
    }
    .card-avatar-inner{
        width:100%;height:100%;border-radius:50%;background:#12121e;
        display:flex;align-items:center;justify-content:center;
        font-size:11px;font-weight:700;color:rgba(255,255,255,.7);
    }
    .card-user-info{min-width:0}
    .card-user-name{font-size:12px;font-weight:600;color:rgba(255,255,255,.75);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px}
    .card-user-rating{display:flex;align-items:center;gap:3px;margin-top:1px}
    .card-user-rating svg{width:10px;height:10px;color:rgba(201,162,39,.5)}
    .card-user-rating span{font-size:10px;color:rgba(201,162,39,.6);font-weight:500}
    .card-status{
        font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;
        padding:3px 10px;border-radius:6px;flex-shrink:0;
    }
    .cs-open{background:rgba(34,197,94,.08);color:rgba(74,222,128,.85)}
    .cs-done{background:rgba(255,255,255,.03);color:rgba(255,255,255,.2)}

    .card-item-name{font-size:15px;font-weight:700;color:rgba(255,255,255,.88);margin-bottom:10px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .card-pills{display:flex;align-items:center;gap:6px;flex-wrap:wrap}
    .card-pill{
        display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:7px;
        font-size:10.5px;font-weight:600;
    }
    .card-pill svg{width:11px;height:11px}
    .cp-makanan{background:rgba(251,191,36,.07);color:rgba(251,191,36,.8)}
    .cp-elektronik{background:rgba(59,130,246,.07);color:rgba(96,165,250,.8)}
    .cp-fashion{background:rgba(244,114,182,.07);color:rgba(244,114,182,.8)}
    .cp-dokumen{background:rgba(34,197,94,.07);color:rgba(74,222,128,.8)}
    .cp-default{background:rgba(255,255,255,.03);color:rgba(255,255,255,.3)}
    .cp-budget{background:rgba(201,162,39,.07);color:rgba(201,162,39,.85)}

    .card-route{position:relative;height:60px;display:flex;align-items:center;padding:0 20px}
    .card-route-line{position:absolute;top:50%;left:80px;right:80px;height:1px;background:linear-gradient(90deg,rgba(201,162,39,.08),rgba(255,255,255,.03),rgba(201,162,39,.08));pointer-events:none}
    .card-route-city{position:relative;z-index:2;min-width:60px}
    .card-route-city .r-name{font-size:13px;font-weight:700;color:rgba(255,255,255,.85)}
    .card-route-city .r-label{font-size:9px;letter-spacing:.12em;text-transform:uppercase;font-weight:600;color:rgba(255,255,255,.16);margin-top:2px}
    .card-route-mid{flex:1;display:flex;justify-content:center;position:relative;z-index:2}

    .card-body{padding:16px 20px 60px}
    .card-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px}
    .card-info-box{background:rgba(255,255,255,.015);border:1px solid rgba(255,255,255,.025);border-radius:10px;padding:10px 12px}
    .card-info-box .lbl{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.15);margin-bottom:4px}
    .card-info-box .val{font-size:12px;font-weight:600;color:rgba(255,255,255,.5)}
    .card-note{background:rgba(201,162,39,.02);border-left:2px solid rgba(201,162,39,.12);border-radius:0 8px 8px 0;padding:8px 12px}
    .card-note p{font-size:11.5px;color:rgba(255,255,255,.3);line-height:1.5}

    .card-dock{
        position:absolute;bottom:0;left:0;right:0;
        background:rgba(8,8,15,.85);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
        padding:10px 16px;opacity:0;transform:translateY(6px);
        transition:all .35s cubic-bezier(.23,1,.32,1);pointer-events:none;
        display:flex;align-items:center;gap:8px;
    }
    .req-card:hover .card-dock{opacity:1;transform:translateY(0);pointer-events:auto}
    .dock-btn{
        flex:1;display:flex;align-items:center;justify-content:center;gap:6px;
        padding:8px 0;border-radius:9px;font-size:12px;font-weight:600;
        text-decoration:none;transition:all .25s;
    }
    .dock-view{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);color:rgba(255,255,255,.55)}
    .dock-view:hover{background:rgba(255,255,255,.07);color:rgba(255,255,255,.8)}
    .dock-action{background:linear-gradient(135deg,rgba(201,162,39,.8),rgba(176,138,34,.8));color:#0c0c18;box-shadow:0 2px 16px rgba(201,162,39,.15)}
    .dock-action:hover{box-shadow:0 4px 24px rgba(201,162,39,.3)}
    .dock-btn svg{width:14px;height:14px}

    /* ═══ PAGINATION ═══ */
    .req-pagination{display:flex;justify-content:center}
    .req-pagination .pagination{display:flex;gap:4px;flex-wrap:wrap;justify-content:center}
    .req-pagination .pagination a,
    .req-pagination .pagination span{
        display:inline-flex;align-items:center;justify-content:center;
        min-width:38px;height:38px;padding:0 10px;border-radius:10px;
        font-size:13px;font-weight:500;color:rgba(255,255,255,.35);
        background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);
        text-decoration:none;transition:all .3s;
    }
    .req-pagination .pagination a:hover{background:rgba(201,162,39,.06);border-color:rgba(201,162,39,.12);color:rgba(201,162,39,.8)}
    .req-pagination .pagination .active,
    .req-pagination .pagination .page-link.active{background:rgba(201,162,39,.12);border-color:rgba(201,162,39,.2);color:#c9a227;font-weight:700}
    .req-pagination .pagination .disabled span{opacity:.25}

    /* ═══ EMPTY STATE ═══ */
    .req-empty{
        text-align:center;padding:80px 32px;border-radius:24px;
        background:rgba(12,12,24,.4);border:1px solid rgba(255,255,255,.04);
        backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);
    }
    .req-empty-icon{
        width:88px;height:88px;margin:0 auto 28px;border-radius:24px;
        background:linear-gradient(145deg,rgba(201,162,39,.08),rgba(201,162,39,.02));
        border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center;
        position:relative;overflow:hidden;
    }
    .req-empty-icon::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.06),transparent 50%);pointer-events:none}
    .req-empty-icon svg{width:40px;height:40px;color:rgba(201,162,39,.3);position:relative;z-index:1}
    .req-empty h2{font-size:22px;font-weight:700;color:rgba(255,255,255,.6);margin-bottom:8px}
    .req-empty p{font-size:14px;color:rgba(255,255,255,.2);margin-bottom:36px;line-height:1.6;max-width:400px;margin-left:auto;margin-right:auto}

    /* ═══ ANIMATIONS ═══ */
    @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
    @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
    .fade-up{opacity:0;animation:fadeUp .65s cubic-bezier(.23,1,.32,1) forwards}
    .d1{animation-delay:.05s}.d2{animation-delay:.12s}.d3{animation-delay:.2s}
    .d4{animation-delay:.28s}.d5{animation-delay:.36s}
    .card-anim{opacity:0;transform:translateY(20px);transition:all .5s cubic-bezier(.23,1,.32,1)}
    .card-anim.visible{opacity:1;transform:translateY(0)}

    /* ═══ RESPONSIVE ═══ */
    @media(min-width:768px){
        .featured-inner{flex-direction:row;align-items:center;gap:40px}
        .featured-right{display:flex;flex-shrink:0;align-items:center;justify-content:center;width:200px}
        .featured-orb{width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,.025);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.05);display:flex;align-items:center;justify-content:center}
        .featured-orb-inner{width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.04);display:flex;align-items:center;justify-content:center}
        .featured-orb-icon{width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,rgba(201,162,39,.08),rgba(201,162,39,.02));border:1px solid rgba(201,162,39,.08);display:flex;align-items:center;justify-content:center}
        .featured-orb-icon svg{width:28px;height:28px;color:rgba(201,162,39,.6)}
        .search-divider{display:block}
    }
    @media(max-width:767px){
        .req-content{padding:20px 14px 48px}
        .search-field{min-width:120px}
        .featured-route{gap:14px}
        .featured-route-city .city-name{font-size:17px}
    }
    @media(prefers-reduced-motion:reduce){
        .fade-up,.card-anim{animation:none!important;transition:none!important;opacity:1!important;transform:none!important}
        *{animation:none!important;transition-duration:0s!important}
    }
</style>

<!-- Background -->
<div class="req-bg">
    <div class="req-bg-fallback"></div>
    <div class="req-bg-photo" id="reqBgPhoto"></div>
    <div class="req-bg-overlay"></div>
    <div class="req-bg-grid"></div>
</div>
<div class="req-deco-ring" style="width:350px;height:350px;top:5%;right:-5%"></div>
<div class="req-deco-ring" style="width:220px;height:220px;bottom:15%;left:-3%;animation-delay:-3s"></div>

<div class="req-content">

    <!-- Header -->
    <div class="req-header fade-up d1">
        <div class="req-header-left">
            <div class="req-header-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            </div>
            <div>
                <h1>Jelajah Permintaan</h1>
                <p>Cari barang yang butuh dititip</p>
            </div>
        </div>
        @auth
            <a href="{{ route('requests.create') }}" class="btn-gold">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Buat Permintaan
            </a>
        @endauth
    </div>

    <!-- Search -->
    <div class="fade-up d2">
        <form action="{{ route('requests.index') }}" method="GET">
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
                <select name="category" class="search-select">
                    <option value="">Semua Kategori</option>
                    <option value="makanan" {{ request('category') === 'makanan' ? 'selected' : '' }}>Makanan</option>
                    <option value="elektronik" {{ request('category') === 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="fashion" {{ request('category') === 'fashion' ? 'selected' : '' }}>Fashion</option>
                    <option value="dokumen" {{ request('category') === 'dokumen' ? 'selected' : '' }}>Dokumen</option>
                    <option value="lainnya" {{ request('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                <button type="submit" class="search-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    Cari
                </button>
            </div>
        </form>

        <div class="cat-tags">
            <span class="cat-tags-label">Kategori</span>
            <a href="{{ route('requests.index', ['category' => 'makanan']) }}" class="cat-tag {{ request('category') === 'makanan' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.126-6 .37m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.17c0 .62-.504 1.124-1.125 1.124H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12M12.265 3.11a.375.375 0 11-.53 0L12 2.845l.265.265z"/></svg>
                Makanan
            </a>
            <a href="{{ route('requests.index', ['category' => 'elektronik']) }}" class="cat-tag {{ request('category') === 'elektronik' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                Elektronik
            </a>
            <a href="{{ route('requests.index', ['category' => 'fashion']) }}" class="cat-tag {{ request('category') === 'fashion' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                Fashion
            </a>
            <a href="{{ route('requests.index', ['category' => 'dokumen']) }}" class="cat-tag {{ request('category') === 'dokumen' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Dokumen
            </a>
            <a href="{{ route('requests.index', ['category' => 'lainnya']) }}" class="cat-tag {{ request('category') === 'lainnya' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                Lainnya
            </a>
            @if(request('category') || request('origin') || request('destination'))
                <a href="{{ route('requests.index') }}" class="cat-tag cat-tag-reset">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </a>
            @endif
        </div>
    </div>

    @if($requests->count() > 0)

        <!-- Featured -->
        @php
            $featured = $requests->firstWhere('status', 'open');
            if (!$featured) { $featured = $requests->first(); }
            $fCat = strtolower($featured->category ?? '');
            $fCC = 'fc-default'; $fSvg = 'package';
            if (str_contains($fCat, 'makanan')) { $fCC = 'fc-makanan'; $fSvg = 'food'; }
            elseif (str_contains($fCat, 'elektronik')) { $fCC = 'fc-elektronik'; $fSvg = 'phone'; }
            elseif (str_contains($fCat, 'fashion')) { $fCC = 'fc-fashion'; $fSvg = 'bag'; }
            elseif (str_contains($fCat, 'dokumen')) { $fCC = 'fc-dokumen'; $fSvg = 'doc'; }
        @endphp

        <div class="featured-card fade-up d3">
            <div class="featured-inner">
                <div style="flex:1">
                    <div class="featured-top">
                        <span class="featured-badge fb-featured">Featured</span>
                        <span class="featured-badge {{ $featured->status === 'open' ? 'fb-open' : 'fb-done' }}">
                            {{ $featured->status === 'open' ? 'Bisa Diambil' : 'Selesai' }}
                        </span>
                        <span class="featured-cat {{ $fCC }}">
                            @if($fSvg === 'food')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5M6 12.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.126-6 .37"/></svg>
                            @elseif($fSvg === 'phone')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                            @elseif($fSvg === 'bag')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                            @elseif($fSvg === 'doc')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            @else
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            @endif
                            {{ $featured->category ?? 'Lainnya' }}
                        </span>
                    </div>

                    <div class="featured-user">
                        <div class="featured-avatar">
                            <div class="featured-avatar-inner">{{ strtoupper(substr($featured->user->name ?? 'U', 0, 1)) }}</div>
                        </div>
                        <div>
                            <p class="featured-user-name">{{ $featured->user->name ?? 'Unknown' }}</p>
                            <div class="featured-user-rating">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span>{{ number_format($featured->user->rating_avg ?? 0, 1) }}</span>
                            </div>
                        </div>
                    </div>

                    <h2 class="featured-title" style="margin:16px 0">{{ $featured->item_name }}</h2>

                    @if($featured->budget_max)
                        <div class="featured-budget" style="margin-bottom:20px">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Rp {{ number_format($featured->budget_max, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="featured-route" style="margin-bottom:20px">
                        <div class="featured-route-city"><p class="lbl">Dari</p><p class="city-name">{{ $featured->origin_city }}</p></div>
                        <div class="featured-route-arrow">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </div>
                        <div class="featured-route-city"><p class="lbl">Ke</p><p class="city-name">{{ $featured->destination_city }}</p></div>
                    </div>

                    <div class="featured-meta" style="margin-bottom:24px">
                        @if($featured->deadline)
                            <div class="featured-meta-item">
                                <div class="featured-meta-icon" style="background:rgba(201,162,39,.06)">
                                    <svg fill="none" stroke="rgba(201,162,39,.5)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                </div>
                                <span>{{ $featured->deadline->format('d M Y') }}</span>
                            </div>
                        @endif
                        @if($featured->weight_estimate)
                            <div class="featured-meta-item">
                                <div class="featured-meta-icon" style="background:rgba(201,162,39,.06)">
                                    <svg fill="none" stroke="rgba(201,162,39,.5)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z"/></svg>
                                </div>
                                <span>{{ $featured->weight_estimate }}</span>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('requests.show', $featured) }}" class="btn-gold">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Lihat Detail
                    </a>
                </div>

                <div class="featured-right">
                    <div class="featured-orb">
                        <div class="featured-orb-inner">
                            <div class="featured-orb-icon">
                                @if($fSvg === 'food')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5M6 12.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.126-6 .37"/></svg>
                                @elseif($fSvg === 'phone')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                                @elseif($fSvg === 'bag')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                                @elseif($fSvg === 'doc')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                @else
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Title -->
        <div class="fade-up d4">
            <div class="section-title">
                <div class="section-title-bar"></div>
                <h2>Semua Permintaan</h2>
            </div>
            <p class="section-subtitle">{{ $requests->count() }} permintaan</p>
        </div>

        <!-- Grid -->
        <div class="card-grid">
            @foreach($requests as $req)
                @php
                    $rC = strtolower($req->category ?? '');
                    $rCC = 'cp-default'; $rSvg = 'package';
                    if (str_contains($rC, 'makanan')) { $rCC = 'cp-makanan'; $rSvg = 'food'; }
                    elseif (str_contains($rC, 'elektronik')) { $rCC = 'cp-elektronik'; $rSvg = 'phone'; }
                    elseif (str_contains($rC, 'fashion')) { $rCC = 'cp-fashion'; $rSvg = 'bag'; }
                    elseif (str_contains($rC, 'dokumen')) { $rCC = 'cp-dokumen'; $rSvg = 'doc'; }
                @endphp

                <div class="req-card card-anim">
                    <div class="glow-line {{ $req->status === 'open' ? 'glow-open' : 'glow-done' }}"></div>

                    <div class="card-head">
                        <div class="card-head-top">
                            <div class="card-user">
                                <div class="card-avatar">
                                    <div class="card-avatar-inner">{{ strtoupper(substr($req->user->name ?? 'U', 0, 1)) }}</div>
                                </div>
                                <div class="card-user-info">
                                    <p class="card-user-name">{{ $req->user->name ?? 'Unknown' }}</p>
                                    <div class="card-user-rating">
                                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        <span>{{ number_format($req->user->rating_avg ?? 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="card-status {{ $req->status === 'open' ? 'cs-open' : 'cs-done' }}">
                                {{ $req->status === 'open' ? 'Buka' : 'Selesai' }}
                            </span>
                        </div>

                        <p class="card-item-name">{{ $req->item_name }}</p>

                        <div class="card-pills">
                            <span class="card-pill {{ $rCC }}">
                                @if($rSvg === 'food')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5M6 12.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.126-6 .37"/></svg>
                                @elseif($rSvg === 'phone')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                                @elseif($rSvg === 'bag')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                                @elseif($rSvg === 'doc')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                @else
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                @endif
                                {{ $req->category ?? 'Lainnya' }}
                            </span>
                            @if($req->budget_max)
                                <span class="card-pill cp-budget">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Rp {{ number_format($req->budget_max, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-route">
                        <div class="card-route-line"></div>
                        <div class="card-route-city"><p class="r-name">{{ $req->origin_city }}</p><p class="r-label">Dari</p></div>
                        <div class="card-route-mid">
                            <div class="featured-route-arrow" style="width:28px;height:28px;border-radius:8px">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </div>
                        </div>
                        <div class="card-route-city" style="text-align:right"><p class="r-name">{{ $req->destination_city }}</p><p class="r-label">Ke</p></div>
                    </div>

                    <div class="card-body">
                        <div class="card-info-grid">
                            <div class="card-info-box">
                                <p class="lbl">Deadline</p>
                                <p class="val">{{ $req->deadline ? $req->deadline->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="card-info-box">
                                <p class="lbl">Berat</p>
                                <p class="val">{{ $req->weight_estimate ?? '-' }}</p>
                            </div>
                        </div>
                        @if($req->description)
                            <div class="card-note">
                                <p>{{ Str::limit($req->description, 80) }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="card-dock">
                        <a href="{{ route('requests.show', $req) }}" class="dock-btn dock-view">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Detail
                        </a>
                        @auth
                            @if($req->status === 'open' && $req->user_id !== Auth::id())
                                <a href="{{ route('requests.show', $req) }}" class="dock-btn dock-action">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Ambil
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

        <div class="req-pagination">{{ $requests->links() }}</div>

    @else

        <div class="req-empty fade-up d3">
            <div class="req-empty-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            </div>
            <h2>Belum Ada Permintaan</h2>
            <p>Jadi yang pertama buat permintaan dan cari traveller yang bisa bantu</p>
            @auth
                <a href="{{ route('requests.create') }}" class="btn-gold">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Buat Permintaan
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
    var bgPhoto = document.getElementById('reqBgPhoto');

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