@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body.dash-page{background:#08080f!important;color:#f0f0f5!important;overflow-x:hidden}

    .dash-bg{position:fixed;inset:0;z-index:0;pointer-events:none}
    .dash-bg-photo{
        position:absolute;inset:0;
        background:url('https://i1-c.pinimg.com/1200x/0f/85/cd/0f85cd93dceae6e0d4f6967947bb15a5.jpg') center/cover no-repeat;
        opacity:0;transition:opacity 2s ease;transform:scale(1.03);
    }
    .dash-bg-photo.loaded{opacity:.55}
    .dash-bg-fallback{position:absolute;inset:0;background:#08080f}
    .dash-bg-overlay{
        position:absolute;inset:0;
        background:linear-gradient(180deg,rgba(8,8,15,.78) 0%,rgba(8,8,15,.68) 40%,rgba(8,8,15,.88) 100%);
    }
    .dash-bg-grid{
        position:absolute;inset:0;
        background-image:linear-gradient(rgba(201,162,39,.012) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.012) 1px,transparent 1px);
        background-size:80px 80px;
        mask-image:radial-gradient(ellipse at 50% 20%,black 5%,transparent 55%);-webkit-mask-image:radial-gradient(ellipse at 50% 20%,black 5%,transparent 55%);
    }
    .dash-deco-ring{position:fixed;border-radius:50%;border:1px solid rgba(201,162,39,.04);pointer-events:none;z-index:0;animation:ringPulse 7s ease-in-out infinite}
    @keyframes ringPulse{0%,100%{opacity:.05;transform:scale(1)}50%{opacity:.1;transform:scale(1.04)}}

    .dash-content{position:relative;z-index:1;max-width:960px;margin:0 auto;padding:32px 20px 60px}

    /* ═══ GREETING ═══ */
    .dash-greeting{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:32px}
    .dash-greeting-left{display:flex;flex-direction:column;gap:4px}
    .dash-greeting-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(201,162,39,.6);display:flex;align-items:center;gap:6px}
    .dash-greeting-dot{width:6px;height:6px;border-radius:50%;background:#c9a227;animation:dotPulse 2.5s ease-in-out infinite}
    @keyframes dotPulse{0%,100%{opacity:.4}50%{opacity:1}}
    .dash-greeting h1{font-family:'Inter',sans-serif;font-size:clamp(24px,3.5vw,32px);font-weight:800;color:#f0f0f5;letter-spacing:-.02em}
    .dash-greeting p{font-size:14px;color:rgba(255,255,255,.25);font-weight:300}
    .dash-greeting-right{display:flex;align-items:center;gap:12px}
    .dash-avatar{width:44px;height:44px;border-radius:50%;padding:2px;flex-shrink:0;background:linear-gradient(135deg,rgba(201,162,39,.5),rgba(176,138,34,.4))}
    .dash-avatar-inner{width:100%;height:100%;border-radius:50%;background:#12121e;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:rgba(255,255,255,.8)}
    .dash-user-info p{font-size:13px;font-weight:600;color:rgba(255,255,255,.75)}
    .dash-user-online{display:flex;align-items:center;gap:5px}
    .dash-user-dot{width:5px;height:5px;border-radius:50%;background:#4ade80;animation:dotPulse 2s ease-in-out infinite}
    .dash-user-online span{font-size:10.5px;color:rgba(255,255,255,.2);font-weight:500}

    /* ═══ GLASS CARD ═══ */
    .g-card{
        background:rgba(12,12,24,.5);backdrop-filter:blur(24px) saturate(1.2);-webkit-backdrop-filter:blur(24px) saturate(1.2);
        border:1px solid rgba(255,255,255,.05);border-radius:20px;position:relative;overflow:hidden;
        transition:all .4s cubic-bezier(.23,1,.32,1);
    }
    .g-card::before{content:'';position:absolute;top:0;left:10%;right:10%;height:1px;background:linear-gradient(90deg,transparent,rgba(201,162,39,.08),transparent);pointer-events:none}
    .g-card:hover{border-color:rgba(255,255,255,.08);background:rgba(12,12,24,.55)}

    /* ═══ STAT CARDS ═══ */
    .stat-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:24px}
    @media(min-width:768px){.stat-grid{grid-template-columns:repeat(4,1fr);gap:16px}}
    .stat-card{padding:20px;position:relative;overflow:hidden}
    .stat-card::after{content:'';position:absolute;top:0;left:0;width:3px;height:100%;border-radius:0 2px 2px 0;transition:height .3s}
    .stat-card.sc-trust::after{background:linear-gradient(180deg,rgba(201,162,39,.6),rgba(201,162,39,.2))}
    .stat-card.sc-rating::after{background:linear-gradient(180deg,rgba(167,139,250,.6),rgba(139,92,246,.2))}
    .stat-card.sc-trans::after{background:linear-gradient(180deg,rgba(74,222,128,.6),rgba(34,197,94,.2))}
    .stat-card.sc-verify::after{background:linear-gradient(180deg,rgba(251,191,36,.6),rgba(245,158,11,.2))}
    .stat-card:hover{transform:translateY(-3px);box-shadow:0 12px 40px rgba(0,0,0,.25)}
    .stat-head{display:flex;align-items:center;gap:8px;margin-bottom:14px}
    .stat-icon{width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .stat-icon svg{width:14px;height:14px}
    .stat-icon.si-trust{background:rgba(201,162,39,.08);border:1px solid rgba(201,162,39,.1)}
    .stat-icon.si-trust svg{color:rgba(201,162,39,.6)}
    .stat-icon.si-rating{background:rgba(167,139,250,.08);border:1px solid rgba(167,139,250,.1)}
    .stat-icon.si-rating svg{color:rgba(167,139,250,.6)}
    .stat-icon.si-trans{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.1)}
    .stat-icon.si-trans svg{color:rgba(34,197,94,.6)}
    .stat-icon.si-verify{background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.1)}
    .stat-icon.si-verify svg{color:rgba(251,191,36,.6)}
    .stat-label{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.2)}
    .stat-value{font-size:clamp(24px,3vw,30px);font-weight:800;line-height:1;letter-spacing:-.02em;margin-bottom:8px}
    .stat-value.sv-trust{color:#c9a227;text-shadow:0 0 20px rgba(201,162,39,.35)}
    .stat-value.sv-rating{color:rgba(167,139,250,.9);text-shadow:0 0 20px rgba(139,92,246,.3)}
    .stat-value.sv-trans{color:rgba(74,222,128,.9);text-shadow:0 0 20px rgba(34,197,94,.3)}
    .stat-bar{height:3px;background:rgba(255,255,255,.06);border-radius:2px;overflow:hidden}
    .stat-bar-fill{height:100%;border-radius:2px;transition:width 1s ease}
    .sb-trust{background:linear-gradient(90deg,#c9a227,#d4b040);box-shadow:0 0 10px rgba(201,162,39,.35)}
    .stat-sub{font-size:11px;color:rgba(255,255,255,.2);font-weight:400}
    .stat-stars{display:flex;gap:2px;margin-bottom:4px}
    .stat-stars svg{width:12px;height:12px}
    .verify-badges{display:flex;gap:6px;flex-wrap:wrap}
    .v-badge{font-size:10px;font-weight:600;padding:3px 10px;border-radius:6px;display:inline-flex;align-items:center;gap:4px}
    .v-yes{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.12);color:rgba(74,222,128,.85)}
    .v-no{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);color:rgba(255,255,255,.25)}
    .v-yes svg,.v-no svg{width:10px;height:10px}

    /* ═══ ACTION CARDS ═══ */
    .action-grid{display:grid;grid-template-columns:1fr;gap:14px;margin-bottom:24px}
    @media(min-width:640px){.action-grid{grid-template-columns:1fr 1fr}}
    .action-card{
        padding:22px;position:relative;overflow:hidden;cursor:pointer;text-decoration:none;
        background:rgba(12,12,24,.5);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px) saturate(1.2);
        border:1px solid rgba(255,255,255,.05);border-radius:20px;
        transition:all .4s cubic-bezier(.23,1,.32,1);
    }
    .action-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;transform:scaleX(0);transform-origin:left;transition:transform .4s}
    .action-card:hover::before{transform:scaleX(1)}
    .action-card.ac-trip::before{background:linear-gradient(90deg,rgba(201,162,39,.5),rgba(212,176,64,.5))}
    .action-card.ac-req::before{background:linear-gradient(90deg,rgba(244,114,182,.5),rgba(201,162,39,.5))}
    .action-card:hover{border-color:rgba(201,162,39,.1);background:rgba(12,12,24,.6);transform:translateY(-3px);box-shadow:0 16px 50px rgba(0,0,0,.3)}
    .action-inner{display:flex;align-items:center;gap:14px;position:relative;z-index:1}
    .action-icon{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:transform .4s}
    .action-card:hover .action-icon{transform:scale(1.08)}
    .ai-trip{background:linear-gradient(145deg,rgba(201,162,39,.1),rgba(201,162,39,.03));border:1px solid rgba(201,162,39,.1)}
    .ai-trip svg{width:22px;height:22px;color:rgba(201,162,39,.65)}
    .ai-req{background:linear-gradient(145deg,rgba(244,114,182,.08),rgba(244,114,182,.02));border:1px solid rgba(244,114,182,.08)}
    .ai-req svg{width:22px;height:22px;color:rgba(244,114,182,.55)}
    .action-text h3{font-size:15px;font-weight:700;color:rgba(255,255,255,.8);letter-spacing:-.01em;transition:color .3s}
    .action-card:hover .ac-trip h3{color:rgba(201,162,39,.9)}
    .action-card:hover .ac-req h3{color:rgba(244,114,182,.85)}
    .action-text p{font-size:12px;color:rgba(255,255,255,.22);margin-top:2px}
    .action-arrow{width:16px;height:16px;color:rgba(255,255,255,.12);flex-shrink:0;transition:all .3s}
    .action-card:hover .action-arrow{transform:translateX(3px);color:rgba(255,255,255,.3)}

    /* ═══ MAIN GRID ═══ */
    .dash-grid{display:grid;grid-template-columns:1fr;gap:20px}
    @media(min-width:1024px){.dash-grid{grid-template-columns:1fr 320px}}

    /* ═══ SECTION ═══ */
    .dash-section{padding:24px;margin-bottom:20px}
    .dash-section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
    .dash-section-title{display:flex;align-items:center;gap:10px}
    .dash-section-icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:linear-gradient(145deg,rgba(201,162,39,.08),rgba(201,162,39,.02));border:1px solid rgba(201,162,39,.08)}
    .dash-section-icon svg{width:16px;height:16px;color:rgba(201,162,39,.6)}
    .dash-section-title h2{font-size:16px;font-weight:700;color:rgba(255,255,255,.8)}
    .dash-section-link{font-size:12px;color:rgba(201,162,39,.5);text-decoration:none;font-weight:600;transition:color .3s;display:flex;align-items:center;gap:4px}
    .dash-section-link:hover{color:rgba(201,162,39,.9)}
    .dash-section-link svg{width:12px;height:12px;transition:transform .3s}
    .dash-section-link:hover svg{transform:translateX(2px)}

    /* ═══ ORDER ITEMS ═══ */
    .order-list{display:flex;flex-direction:column;gap:2px}
    .order-item{
        display:flex;align-items:center;gap:12px;padding:14px;border-radius:12px;
        background:rgba(255,255,255,.015);border:1px solid rgba(255,255,255,.025);
        transition:all .3s;text-decoration:none;
    }
    .order-item:hover{background:rgba(255,255,255,.03);border-color:rgba(255,255,255,.05)}
    .order-item-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .order-item-icon svg{width:16px;height:16px}
    .oi-completed{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.1)}
    .oi-completed svg{color:rgba(74,222,128,.6)}
    .oi-pending{background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.1)}
    .oi-pending svg{color:rgba(251,191,36,.6)}
    .oi-active{background:rgba(59,130,246,.08);border:1px solid rgba(59,130,246,.1)}
    .oi-active svg{color:rgba(96,165,250,.6)}
    .oi-default{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.04)}
    .oi-default svg{color:rgba(255,255,255,.2)}
    .order-item-info{flex:1;min-width:0}
    .order-item-route{font-size:13px;font-weight:600;color:rgba(255,255,255,.75);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .order-item-time{font-size:11px;color:rgba(255,255,255,.18);margin-top:2px}
    .order-item-badge{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;padding:3px 10px;border-radius:6px;flex-shrink:0;white-space:nowrap}
    .ob-completed{background:rgba(34,197,94,.08);color:rgba(74,222,128,.85)}
    .ob-pending{background:rgba(245,158,11,.08);color:rgba(251,191,36,.85)}
    .ob-active{background:rgba(59,130,246,.08);color:rgba(96,165,250,.85)}
    .ob-default{background:rgba(255,255,255,.03);color:rgba(255,255,255,.3)}
    .ob-verified{background:rgba(99,102,241,.08);color:rgba(129,140,248,.85)}
    .ob-purchased{background:rgba(249,115,22,.08);color:rgba(251,146,60,.85)}
    .ob-in_transit{background:rgba(6,182,212,.08);color:rgba(34,211,238,.85)}
    .ob-delivered{background:rgba(34,197,94,.08);color:rgba(74,222,128,.85)}
    .ob-payment_uploaded{background:rgba(168,85,247,.08);color:rgba(192,132,252,.85)}
    .ob-cancelled{background:rgba(239,68,68,.08);color:rgba(248,113,113,.85)}

    /* ═══ TIP CARDS ═══ */
    .tip-list{display:flex;flex-direction:column;gap:8px}
    .tip-item{display:flex;align-items:center;gap:12px;padding:12px;border-radius:12px;background:rgba(255,255,255,.015);border:1px solid rgba(255,255,255,.025);transition:all .3s}
    .tip-item:hover{background:rgba(255,255,255,.03);border-color:rgba(255,255,255,.05);transform:translateX(4px)}
    .tip-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .tip-icon svg{width:14px;height:14px}
    .ti-gold{background:rgba(201,162,39,.06);border:1px solid rgba(201,162,39,.08)}
    .ti-gold svg{color:rgba(201,162,39,.5)}
    .tip-text{font-size:12.5px;color:rgba(255,255,255,.3);line-height:1.5}
    .tip-text strong{color:rgba(255,255,255,.6)}

    /* ═══ PROFILE COMPLETION ═══ */
    .profile-comp-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
    .profile-comp-pct{font-size:12px;font-weight:700;min-width:36px;text-align:right}
    .pc-done{color:rgba(74,222,128,.8)}
    .pc-half{color:rgba(251,191,36,.8)}
    .profile-comp-bar{height:5px;background:rgba(255,255,255,.06);border-radius:3px;overflow:hidden;margin-bottom:16px}
    .profile-comp-fill{height:100%;border-radius:3px;transition:width 1s ease}
    .pcf-done{background:linear-gradient(90deg,#c9a227,#d4b040);box-shadow:0 0 12px rgba(201,162,39,.3)}
    .pcf-half{background:linear-gradient(90deg,#f59e0b,#fbbf24);box-shadow:0 0 12px rgba(245,158,11,.3)}
    .profile-comp-link{display:block;text-align:center;font-size:12.5px;font-weight:600;padding:10px;border-radius:10px;text-decoration:none;background:rgba(201,162,39,.06);border:1px solid rgba(201,162,39,.1);color:rgba(201,162,39,.7);transition:all .3s}
    .profile-comp-link:hover{background:rgba(201,162,39,.1);border-color:rgba(201,162,39,.15);color:rgba(201,162,39,.95);box-shadow:0 4px 16px rgba(201,162,39,.12)}
    .profile-comp-done{text-align:center;font-size:12px;color:rgba(74,222,128,.4);padding:10px}

    /* ═══ EMPTY ═══ */
    .orders-empty{text-align:center;padding:32px 16px}
    .orders-empty-icon{width:48px;height:48px;margin:0 auto 12px;border-radius:14px;background:rgba(201,162,39,.05);border:1px solid rgba(201,162,39,.08);display:flex;align-items:center;justify-content:center}
    .orders-empty-icon svg{width:22px;height:22px;color:rgba(201,162,39,.25)}
    .orders-empty p{font-size:13px;color:rgba(255,255,255,.22);line-height:1.5}
    .orders-empty p strong{color:rgba(255,255,255,.35)}

    /* ═══ ANIMATIONS ═══ */
    @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
    .fade-up{opacity:0;animation:fadeUp .65s cubic-bezier(.23,1,.32,1) forwards}
    .d1{animation-delay:.05s}.d2{animation-delay:.12s}.d3{animation-delay:.2s}
    .d4{animation-delay:.28s}.d5{animation-delay:.36s}.d6{animation-delay:.44s}
    .d7{animation-delay:.52s}

    @media(max-width:767px){
        .dash-content{padding:20px 14px 48px}
        .dash-section{padding:20px}
        .dash-greeting h1{font-size:22px}
    }
    @media(prefers-reduced-motion:reduce){
        .fade-up{animation:none!important;opacity:1!important;transform:none!important}
        *{animation:none!important;transition-duration:0s!important}
    }
</style>

<!-- Background -->
<div class="dash-bg">
    <div class="dash-bg-fallback"></div>
    <div class="dash-bg-photo" id="dashBgPhoto"></div>
    <div class="dash-bg-overlay"></div>
    <div class="dash-bg-grid"></div>
</div>
<div class="dash-deco-ring" style="width:300px;height:300px;top:3%;right:-3%"></div>
<div class="dash-deco-ring" style="width:200px;height:200px;bottom:10%;left:-2%;animation-delay:-3s"></div>

<div class="dash-content">

    <!-- Greeting -->
    <div class="dash-greeting fade-up d1">
        <div class="dash-greeting-left">
            <div class="dash-greeting-label">
                <div class="dash-greeting-dot"></div>
                Dashboard
            </div>
            <h1>Halo, {{ Auth::user()->name }}</h1>
            <p>Mau titip apa hari ini?</p>
        </div>
        <div class="dash-greeting-right">
            <div class="dash-avatar"><div class="dash-avatar-inner">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div></div>
            <div>
                <p>{{ Auth::user()->name }}</p>
                <div class="dash-user-online">
                    <div class="dash-user-dot"></div>
                    <span>Online</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stat-grid fade-up d2">
        <div class="g-card stat-card sc-trust">
            <div class="stat-head">
                <div class="stat-icon si-trust">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749V7.5A4.5 4.5 0 017.5 3h.75a5.996 5.996 0 015.058 2.772m0 0a3 3 0 00-5.184 0"/></svg>
                </div>
                <span class="stat-label">Trust Score</span>
            </div>
            <p class="stat-value sv-trust">{{ Auth::user()->trust_score }}</p>
            <div class="stat-bar"><div class="stat-bar-fill sb-trust" style="width:{{ min(Auth::user()->trust_score, 100) }}%"></div></div>
        </div>

        <div class="g-card stat-card sc-rating">
            <div class="stat-head">
                <div class="stat-icon si-rating">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <span class="stat-label">Rating</span>
            </div>
            <div style="display:flex;align-items:flex-end;gap:8px;margin-bottom:6px">
                <span class="stat-value sv-rating">{{ number_format(Auth::user()->rating_avg, 1) }}</span>
                <div class="stat-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <svg fill="{{ $i <= round(Auth::user()->rating_avg) ? 'rgba(167,139,250,.8)' : 'rgba(255,255,255,.08)' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
            </div>
            <p class="stat-sub">{{ Auth::user()->total_ratings }} ulasan</p>
        </div>

        <div class="g-card stat-card sc-trans">
            <div class="stat-head">
                <div class="stat-icon si-trans">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0 4.5h16.5M3.75 20.25v-15a2.25 2.25 0 012.25-2.25h12a2.25 2.25 0 012.25 2.25v15m-16.5 0v-7.5A2.25 2.25 0 016.75 5.25h12a2.25 2.25 0 012.25 2.25v7.5"/></svg>
                </div>
                <span class="stat-label">Transaksi</span>
            </div>
            <p class="stat-value sv-trans">{{ Auth::user()->total_trips }}</p>
            <p class="stat-sub">kali sukses</p>
        </div>

        <div class="g-card stat-card sc-verify">
            <div class="stat-head">
                <div class="stat-icon si-verify">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <span class="stat-label">Verifikasi</span>
            </div>
            <div class="verify-badges">
                <span class="v-badge {{ Auth::user()->is_ktp_verified ? 'v-yes' : 'v-no' }}">
                    @if(Auth::user()->is_ktp_verified)
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-6M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 4.636l-3.536 3.536m0 5.656l3.536 3.536m0 5.656l-3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l3.29-3.29m7.532 7.532l3.29 3.29M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9 9 0 1118 0 9 9 0 0118 0z"/></svg>
                    @endif
                    KTP
                </span>
                <span class="v-badge {{ Auth::user()->is_phone_verified ? 'v-yes' : 'v-no' }}">
                    @if(Auth::user()->is_phone_verified)
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 1.5 1.5 0 013 3h.75a1.5 1.5 0 013 3v10.5a1.5 1.5 0 01-3 0v-10.5a1.5 1.5 0 00-3 0H3.75a1.5 1.5 0 00-1.5-1.5v-1.5c0-1.5 1.5-3 1.5-3h10.5c1.5 0 1.5 1.5 1.5 3v1.5"/></svg>
                    @else
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    @endif
                    HP
                </span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="action-grid fade-up d3">
        <a href="{{ route('trips.create') }}" class="action-card ac-trip">
            <div class="action-inner">
                <div class="action-icon ai-trip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                </div>
                <div class="action-text" style="flex:1">
                    <h3>Posting Perjalanan</h3>
                    <p>Buka jasa titip & dapat cuan</p>
                </div>
                <svg class="action-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </div>
        </a>
        <a href="{{ route('requests.create') }}" class="action-card ac-req">
            <div class="action-inner">
                <div class="action-icon ai-req">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                <div class="action-text" style="flex:1">
                    <h3>Cari Titipan</h3>
                    <p>Cari traveller untuk bantu beli</p>
                </div>
                <svg class="action-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </div>
        </a>
    </div>

    <!-- Main Grid -->
    <div class="dash-grid">

        <!-- Orders -->
        <div class="g-card dash-section fade-up d4">
            <div class="dash-section-head">
                <div class="dash-section-title">
                    <div class="dash-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    </div>
                    <h2>Order Terbaru</h2>
                </div>
                @php
                    $recentOrders = App\Models\Order::where('traveller_id', Auth::id())
                        ->orWhere('requester_id', Auth::id())
                        ->latest()->take(5)->get();
                @endphp
                @if($recentOrders->count() > 0)
                    <a href="{{ route('orders.index') }}" class="dash-section-link">
                        Lihat Semua
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                @endif
            </div>
            @if($recentOrders->count() > 0)
                <div class="order-list">
                    @foreach($recentOrders as $order)
                        @php
                            $statusIconMap = [
                                'completed' => 'oi-completed','delivered' => 'oi-completed',
                                'pending' => 'oi-pending','accepted' => 'oi-active',
                                'payment_uploaded' => 'oi-active','payment_verified' => 'oi-active',
                                'purchased' => 'oi-active','in_transit' => 'oi-active',
                            ];
                            $statusBadgeMap = [
                                'pending' => 'ob-pending','accepted' => 'ob-active',
                                'payment_uploaded' => 'ob-payment_uploaded','payment_verified' => 'ob-verified',
                                'purchased' => 'ob-purchased','in_transit' => 'ob-in_transit',
                                'delivered' => 'ob-delivered','completed' => 'ob-completed',
                                'cancelled' => 'ob-cancelled',
                            ];
                            $oiCls = $statusIconMap[$order->status] ?? 'oi-default';
                            $obCls = $statusBadgeMap[$order->status] ?? 'ob-default';
                            $oRoute = ($order->trip ? $order->trip->origin_city . ' → ' . $order->trip->destination_city : 'Titipan');
                        @endphp
                        <a href="{{ route('orders.show', $order) }}" style="text-decoration:none">
                            <div class="order-item">
                                <div class="order-item-icon {{ $oiCls }}">
                                    @if(in_array($order->status, ['completed','delivered']))
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749V7.5A4.5 4.5 0 017.5 3h.75a5.996 5.996 0 015.058 2.772m0 0a3 3 0 00-5.184 0"/></svg>
                                    @elseif($order->status === 'pending')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h.01M12 18h.01M10.29 3.86L22.727 9.411a3.375 3.375 0 00-2.897-1.08l-6.63-4.284a3.375 3.375 0 00-2.896 1.08h0c-.621 0-1.125.504-1.125 1.125v12.75c0 .621.504 1.125 1.125 1.125 1.125h12a1.125 1.125 0 001.125-1.125v-12.75c0-.621-.504-1.125-1.125-1.125-1.125h-12a1.125 1.125 0 00-1.125 1.125v12.75M12 18.75a3 3 0 01-3-3v-1.5m0-10.5a3 3 0 116 0 3 3 0 016 0z"/></svg>
                                    @elseif(in_array($order->status, ['in_transit']))
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                    @endif
                                </div>
                                <div class="order-item-info">
                                    <p class="order-item-route">{{ $oRoute }}</p>
                                    <p class="order-item-time">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="order-item-badge {{ $obCls }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="orders-empty">
                    <div class="orders-empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    </div>
                    <p><strong>Belum ada order</strong></p>
                    <p>Mulai transaksi pertamamu</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Tips -->
            <div class="g-card dash-section fade-up d5">
                <div class="dash-section-title">
                    <div class="dash-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m-7.5-7.5h15a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0113.5 21h-9A2.25 2.25 0 014.25 18.75V8.25A2.25 2.25 0 006.75 6h9a2.25 2.25 0 002.25 2.25v10.5A2.25 2.25 0 0113.5 21h9a2.25 2.25 0 002.25 2.25V8.25A2.25 2.25 0 006.75 6h-9A2.25 2.25 0 004.5 8.25v10.5A2.25 2.25 0 006.75 21h9a2.25 2.25 0 002.25 2.25V8.25z"/></svg>
                    </div>
                    <h2>Tips Transaksi</h2>
                </div>
                <div class="tip-list">
                    <div class="tip-item">
                        <div class="tip-icon ti-gold">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="tip-text"><strong>Escrow</strong> untuk transaksi > Rp 500rb</p>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon ti-gold">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h.93A2 2 0 0012 4.89V7h-1.76a1.5 1.5 0 00-1.344.896l-3.253 2.063a1.5 1.5 0 00-1.344 0V4.89a1.5 1.5 0 012.698-1.5h1.688a1.5 1.5 0 011.272.704V7.594c0-.376-.15-.735-.416-1.007-.456a1.5 1.5 0 00-1.5-1.5V6.75a1.5 1.5 0 011.5-1.5h0V9.75a1.5 1.5 0 001.5 1.5h1.5a1.5 1.5 0 001.5 1.5v.04c0 .14-.026.276-.074.406-.22a1.5 1.5 0 00-1.094-.776z"/></svg>
                        </div>
                        <p class="text"><strong>Foto + nota</strong> sebagai bukti pembelian</p>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon ti-gold">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749V7.5A4.5 4.5 0 017.5 3h.75a5.996 5.996 0 015.058 2.772m0 0a3 3 0 00-5.184 0"/></svg>
                        </div>
                        <p class="text"><strong>Rating & trust score</strong> sebelum deal</p>
                    </div>
                </div>
            </div>

            <!-- Profile Completion -->
            <div class="g-card dash-section fade-up d6">
                <div class="profile-comp-head">
                    <div class="dash-section-title">
                        <div class="dash-section-icon" style="background:linear-gradient(145deg,rgba(255,255,255,.05),rgba(255,255,255,.01));border-color:rgba(255,255,255,.05)">
                            <svg fill="none" stroke="rgba(255,255,255,.4)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 10-7.5 0 3.75 3.75 0 017.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </div>
                        <h2>Kelengkapan Profil</h2>
                    </div>
                    <span class="profile-comp-pct {{ Auth::user()->is_ktp_verified && Auth::user()->is_phone_verified ? 'pc-done' : 'pc-half' }}">
                        {{ Auth::user()->is_ktp_verified && Auth::user()->is_phone_verified ? '100%' : '50%' }}
                    </span>
                </div>
                <div class="profile-comp-bar">
                    <div class="profile-comp-fill {{ Auth::user()->is_ktp_verified && Auth::user()->is_phone_verified ? 'pcf-done' : 'pcf-half' }}" style="width:{{ Auth::user()->is_ktp_verified && Auth::user()->is_phone_verified ? '100' : '50' }}%"></div>
                </div>
                @if(!Auth::user()->is_ktp_verified || !Auth::user()->is_phone_verified)
                    <a href="{{ route('profile.edit') }}" class="profile-comp-link">
                        Lengkapi Sekarang
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                @else
                    <div class="profile-comp-done">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749V7.5A4.5 4.5 0 017.5 3h.75a5.996 5.996 0 015.058 2.772m0 0a3 3 0 00-5.184 0"/></svg>
                        Profil sudah lengkap
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var bgPhoto = document.getElementById('dashBgPhoto');
    if (bgPhoto) {
        var img = new Image();
        img.onload = function() { bgPhoto.classList.add('loaded'); };
        img.src = 'https://i.pinimg.com/736x/91/e2/b2/91e2b24b89293458e673c1840d6c39cc.jpg';
    }
    if (window.innerWidth >= 1024 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.addEventListener('mousemove', function(e) {
            if (!bgPhoto) return;
            var x = (e.clientX / window.innerWidth - 0.5) * -8;
            var y = (e.clientY / window.innerHeight - 0.5) * -8;
            bgPhoto.style.transform = 'scale(1.04) translate(' + x + 'px, ' + y + 'px)';
            bgPhoto.style.transition = 'transform 0.3s ease-out';
        });
    }
});
</script>
@endsection