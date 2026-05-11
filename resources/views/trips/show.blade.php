@extends('layouts.app')

@section('title', $trip->origin_city . ' → ' . $trip->destination_city)

@section('content')
<style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body.trip-detail-page{background:#08080f!important;color:#f0f0f5!important;overflow-x:hidden}

    .td-bg{position:fixed;inset:0;z-index:0;pointer-events:none}
    .td-bg-photo{
        position:absolute;inset:0;
        background:url('https://i1-c.pinimg.com/1200x/0a/d7/e2/0ad7e20749eee1590952fe282127b0a2.jpg') center/cover no-repeat;
        opacity:0;transition:opacity 2s ease;transform:scale(1.03);
    }
    .td-bg-photo.loaded{opacity:.95}
    .td-bg-fallback{position:absolute;inset:0;background:#08080f}
    .td-bg-overlay{
        position:absolute;inset:0;
        background:linear-gradient(180deg,rgba(8,8,15,.78) 0%,rgba(8,8,15,.7) 40%,rgba(8,8,15,.88) 100%);
    }
    .td-bg-grid{
        position:absolute;inset:0;
        background-image:linear-gradient(rgba(201,162,39,.01) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.01) 1px,transparent 1px);
        background-size:80px 80px;
        mask-image:radial-gradient(ellipse at 50% 20%,black 5%,transparent 55%);-webkit-mask-image:radial-gradient(ellipse at 50% 20%,black 5%,transparent 55%);
    }
    .td-deco-ring{
        position:fixed;border-radius:50%;border:1px solid rgba(201,162,39,.03);pointer-events:none;z-index:0;
        animation:ringPulse 7s ease-in-out infinite;
    }
    @keyframes ringPulse{0%,100%{opacity:.04;transform:scale(1)}50%{opacity:.08;transform:scale(1.04)}}

    .td-content{position:relative;z-index:1;max-width:960px;margin:0 auto;padding:32px 20px 60px}

    /* ═══ BACK LINK ═══ */
    .td-back{
        display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,.18);
        text-decoration:none;font-size:12.5px;font-weight:500;transition:all .3s;margin-bottom:20px;
    }
    .td-back:hover{color:rgba(255,255,255,.45)}
    .td-back:hover svg{transform:translateX(-3px)}
    .td-back svg{width:14px;height:14px;transition:transform .3s}

    /* ═══ GLASS CARD ═══ */
    .g-card{
        background:rgba(12,12,24,.5);backdrop-filter:blur(24px) saturate(1.2);-webkit-backdrop-filter:blur(24px) saturate(1.2);
        border:1px solid rgba(255,255,255,.05);border-radius:20px;position:relative;overflow:hidden;
        transition:all .4s cubic-bezier(.23,1,.32,1);
    }
    .g-card::before{
        content:'';position:absolute;top:0;left:10%;right:10%;height:1px;
        background:linear-gradient(90deg,transparent,rgba(201,162,39,.08),transparent);pointer-events:none;
    }
    .g-card:hover{border-color:rgba(255,255,255,.08)}
    .g-card-accent{
        background:rgba(12,12,24,.55);backdrop-filter:blur(24px) saturate(1.2);-webkit-backdrop-filter:blur(24px) saturate(1.2);
        border:1px solid rgba(201,162,39,.1);border-radius:20px;position:relative;overflow:hidden;
    }
    .g-card-accent::before{
        content:'';position:absolute;top:0;left:8%;right:8%;height:1px;
        background:linear-gradient(90deg,transparent,rgba(201,162,39,.15),transparent);pointer-events:none;
    }

    /* ═══ HEADER CARD ═══ */
    .td-header{padding:28px;margin-bottom:20px}
    .td-header-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:20px}
    .td-route{display:flex;align-items:center;gap:14px;flex-wrap:wrap}
    .td-route-icon{
        width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;
        background:linear-gradient(145deg,rgba(201,162,39,.12),rgba(201,162,39,.04));
        border:1px solid rgba(201,162,39,.12);position:relative;overflow:hidden;flex-shrink:0;
    }
    .td-route-icon::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.08),transparent 50%);pointer-events:none}
    .td-route-icon svg{width:24px;height:24px;color:rgba(201,162,39,.7);position:relative;z-index:1}
    .td-route-text{display:flex;flex-direction:column;gap:2px}
    .td-route-cities{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
    .td-route-city{font-size:clamp(20px,3.5vw,28px);font-weight:800;color:#f0f0f5;letter-spacing:-.02em}
    .td-route-arrow{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:rgba(201,162,39,.06);border:1px solid rgba(201,162,39,.08)}
    .td-route-arrow svg{width:14px;height:14px;color:rgba(201,162,39,.5)}
    .td-route-sub{font-size:13px;color:rgba(255,255,255,.25);font-weight:400;display:flex;align-items:center;gap:5px}
    .td-route-sub svg{width:13px;height:13px;color:rgba(255,255,255,.12)}

    .td-status{
        display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border-radius:10px;
        font-size:12px;font-weight:600;white-space:nowrap;flex-shrink:0;
        backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
    }
    .td-status .s-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0}
    .ts-open{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.15);color:rgba(74,222,128,.9)}
    .ts-open .s-dot{background:rgba(34,197,94,.7);animation:statusPulse 2s ease-in-out infinite}
    .ts-full{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.15);color:rgba(251,191,36,.9)}
    .ts-full .s-dot{background:rgba(245,158,11,.7)}
    .ts-done{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);color:rgba(255,255,255,.3)}
    .ts-done .s-dot{background:rgba(255,255,255,.2)}
    @keyframes statusPulse{0%,100%{opacity:.5;transform:scale(1)}50%{opacity:1;transform:scale(1.4)}}

    .td-header-meta{display:flex;flex-wrap:wrap;gap:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,.04)}
    .td-meta-item{display:flex;align-items:center;gap:8px}
    .td-meta-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .td-meta-icon svg{width:15px;height:15px}
    .td-meta-text{display:flex;flex-direction:column;gap:1px}
    .td-meta-label{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.18)}
    .td-meta-value{font-size:13.5px;font-weight:600;color:rgba(255,255,255,.7)}

    .td-slot-bar{height:4px;background:rgba(255,255,255,.06);border-radius:2px;overflow:hidden;flex:1;max-width:180px}
    .td-slot-fill{height:100%;border-radius:2px;transition:width .5s}
    .slot-gold{background:linear-gradient(90deg,#c9a227,#d4b040);box-shadow:0 0 8px rgba(201,162,39,.3)}
    .slot-warn{background:linear-gradient(90deg,#f59e0b,#fbbf24);box-shadow:0 0 8px rgba(245,158,11,.3)}
    .slot-red{background:linear-gradient(90deg,#ef4444,#f87171);box-shadow:0 0 8px rgba(239,68,68,.3)}
    .td-slot-text{font-size:12px;font-weight:700;min-width:44px}

    /* ═══ MAIN GRID ═══ */
    .td-grid{display:grid;grid-template-columns:1fr;gap:20px}
    @media(min-width:768px){.td-grid{grid-template-columns:1fr 320px}}

    /* ═══ SECTION CARD ═══ */
    .td-section{padding:24px;margin-bottom:20px}
    .td-section-title{display:flex;align-items:center;gap:10px;margin-bottom:18px}
    .td-section-icon{
        width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;
        background:linear-gradient(145deg,rgba(201,162,39,.08),rgba(201,162,39,.02));
        border:1px solid rgba(201,162,39,.08);
    }
    .td-section-icon svg{width:17px;height:17px;color:rgba(201,162,39,.6)}
    .td-section-title h2{font-size:16px;font-weight:700;color:rgba(255,255,255,.8);letter-spacing:-.01em}

    /* ═══ INFO GRID ═══ */
    .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .info-box{background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.03);border-radius:12px;padding:14px}
    .info-box .lbl{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.16);margin-bottom:6px;display:flex;align-items:center;gap:4px}
    .info-box .lbl svg{width:10px;height:10px;opacity:.5}
    .info-box .val{font-size:14px;font-weight:600;color:rgba(255,255,255,.75)}

    /* ═══ PROFILE ═══ */
    .profile-row{display:flex;align-items:center;gap:16px}
    .profile-avatar{width:60px;height:60px;border-radius:50%;padding:2px;flex-shrink:0;background:linear-gradient(135deg,rgba(201,162,39,.5),rgba(176,138,34,.4))}
    .profile-avatar-inner{width:100%;height:100%;border-radius:50%;background:#12121e;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;color:rgba(255,255,255,.8)}
    .profile-name{font-size:18px;font-weight:700;color:rgba(255,255,255,.85)}
    .profile-username{font-size:13px;color:rgba(255,255,255,.25);margin-top:1px}
    .profile-stats{display:flex;align-items:center;gap:12px;margin-top:8px;flex-wrap:wrap}
    .profile-stat{display:flex;align-items:center;gap:5px;font-size:12px;font-weight:500}
    .profile-stat svg{width:13px;height:13px}
    .profile-stat.gold{color:rgba(201,162,39,.7)}
    .profile-stat.blue{color:rgba(96,165,250,.7)}
    .profile-stat.muted{color:rgba(255,255,255,.25)}
    .stat-dot{color:rgba(255,255,255,.08);font-size:10px}

    /* ═══ NOTES ═══ */
    .td-note{background:rgba(201,162,39,.03);border:1px solid rgba(201,162,39,.06);border-radius:12px;padding:14px 16px}
    .td-note-label{font-size:11px;font-weight:600;color:rgba(201,162,39,.6);margin-bottom:6px;display:flex;align-items:center;gap:5px}
    .td-note-label svg{width:13px;height:13px}
    .td-note p{font-size:13.5px;color:rgba(255,255,255,.45);line-height:1.6}

    /* ═══ FORM ═══ */
    .form-field{position:relative}
    .form-field::after{content:'';position:absolute;bottom:0;left:50%;width:0;height:2px;background:linear-gradient(90deg,transparent,#c9a227,transparent);transition:all .4s cubic-bezier(.23,1,.32,1);transform:translateX(-50%);border-radius:1px}
    .form-field:focus-within::after{width:92%}
    .form-input{
        width:100%;padding:12px 16px 12px 44px;background:rgba(255,255,255,.035);
        border:1px solid rgba(255,255,255,.055);border-radius:12px;
        color:rgba(255,255,255,.9);font-size:14px;font-family:'Inter',sans-serif;font-weight:400;
        outline:none;transition:all .3s;resize:vertical;
    }
    .form-input::placeholder{color:rgba(255,255,255,.13)}
    .form-input:focus{background:rgba(255,255,255,.055);border-color:rgba(201,162,39,.16)}
    .form-field:focus-within .field-icon{color:#c9a227}
    .form-field:focus-within .field-label{color:rgba(201,162,39,.55)}
    .field-icon{position:absolute;left:14px;top:14px;color:rgba(255,255,255,.12);transition:all .3s;pointer-events:none}
    .field-label{display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.24);margin-bottom:7px;transition:color .3s}

    .btn-gold{
        display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px;border:none;border-radius:13px;
        font-family:'Inter',sans-serif;font-size:15px;font-weight:700;cursor:pointer;
        background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);
        background-size:200% 200%;color:#0c0c18;position:relative;overflow:hidden;
        box-shadow:0 2px 20px rgba(201,162,39,.15),inset 0 1px 0 rgba(255,255,255,.2);
        transition:all .35s cubic-bezier(.23,1,.32,1);
    }
    .btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 35px rgba(201,162,39,.28);background-position:100% 100%}
    .btn-gold:active{transform:translateY(0) scale(.98)}
    .btn-gold svg{width:17px;height:17px}

    /* ═══ ORDER LIST ═══ */
    .order-item{
        padding:14px;border-radius:12px;background:rgba(255,255,255,.02);
        border:1px solid rgba(255,255,255,.03);transition:all .3s;
    }
    .order-item:hover{background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.06)}
    .order-item-name{font-size:13px;font-weight:600;color:rgba(255,255,255,.75)}
    .order-item-price{font-size:13px;font-weight:700;color:rgba(201,162,39,.8);margin-top:3px}
    .order-item-status{font-size:10px;font-weight:600;padding:2px 8px;border-radius:5px;display:inline-flex;align-items:center;gap:4px;margin-top:6px}
    .os-pending{background:rgba(245,158,11,.08);color:rgba(251,191,36,.8)}
    .os-accepted{background:rgba(59,130,246,.08);color:rgba(96,165,250,.8)}
    .os-payment_uploaded{background:rgba(168,85,247,.08);color:rgba(192,132,252,.8)}
    .os-payment_verified{background:rgba(99,102,241,.08);color:rgba(129,140,248,.8)}
    .os-purchased{background:rgba(249,115,22,.08);color:rgba(251,146,60,.8)}
    .os-in_transit{background:rgba(6,182,212,.08);color:rgba(34,211,238,.8)}
    .os-delivered{background:rgba(34,197,94,.08);color:rgba(74,222,128,.8)}
    .os-completed{background:rgba(34,197,94,.1);color:rgba(74,222,128,.9)}
    .os-cancelled{background:rgba(239,68,68,.08);color:rgba(248,113,113,.8)}
    .os-default{background:rgba(255,255,255,.03);color:rgba(255,255,255,.3)}

    /* ═══ MANAGEMENT BTNS ═══ */
    .btn-manage{
        display:flex;align-items:center;justify-content:center;gap:7px;width:100%;padding:10px;border-radius:11px;
        font-family:'Inter',sans-serif;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .3s;
    }
    .btn-manage svg{width:15px;height:15px}
    .btn-edit{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);color:rgba(255,255,255,.6)}
    .btn-edit:hover{background:rgba(255,255,255,.07);border-color:rgba(255,255,255,.1);color:rgba(255,255,255,.85)}
    .btn-delete{background:rgba(239,68,68,.05);border:1px solid rgba(239,68,68,.08);color:rgba(248,113,113,.65)}
    .btn-delete:hover{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.15);color:rgba(248,113,113,.9)}

    /* ═══ LOGIN CTA ═══ */
    .login-cta{text-align:center;padding:8px 0}
    .login-cta p{font-size:14px;color:rgba(255,255,255,.35);margin-bottom:14px}
    .btn-login-sm{
        display:inline-flex;align-items:center;gap:7px;padding:10px 28px;border-radius:11px;
        background:linear-gradient(135deg,rgba(201,162,39,.85),rgba(176,138,34,.85));
        color:#0c0c18;font-family:'Inter',sans-serif;font-size:13.5px;font-weight:700;
        border:none;text-decoration:none;transition:all .3s;
    }
    .btn-login-sm:hover{box-shadow:0 4px 20px rgba(201,162,39,.2);transform:translateY(-1px)}
    .btn-login-sm svg{width:15px;height:15px}

    /* ═══ EMPTY ═══ */
    .orders-empty{text-align:center;padding:20px 0}
    .orders-empty p{font-size:13px;color:rgba(255,255,255,.2);line-height:1.5}

    /* ═══ FORM ERROR ═══ */
    .field-error{font-size:11.5px;color:rgba(252,165,165,.8);margin-top:5px;display:flex;align-items:center;gap:4px}
    .field-error svg{width:11px;height:11px;flex-shrink:0}

    /* ═══ ANIMATIONS ═══ */
    @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
    .fade-up{opacity:0;animation:fadeUp .65s cubic-bezier(.23,1,.32,1) forwards}
    .d1{animation-delay:.05s}.d2{animation-delay:.12s}.d3{animation-delay:.2s}
    .d4{animation-delay:.28s}.d5{animation-delay:.36s}.d6{animation-delay:.44s}
    .d7{animation-delay:.52s}.d8{animation-delay:.6s}

    @media(max-width:767px){
        .td-content{padding:20px 14px 48px}
        .td-section{padding:20px}
        .info-grid{grid-template-columns:1fr}
        .td-header-meta{gap:12px}
        .profile-avatar{width:48px;height:48px}
        .profile-avatar-inner{font-size:18px}
    }
    @media(prefers-reduced-motion:reduce){
        .fade-up{animation:none!important;opacity:1!important;transform:none!important}
        *{animation:none!important;transition-duration:0s!important}
    }
</style>

<!-- Background -->
<div class="td-bg">
    <div class="td-bg-fallback"></div>
    <div class="td-bg-photo" id="tdBgPhoto"></div>
    <div class="td-bg-overlay"></div>
    <div class="td-bg-grid"></div>
</div>
<div class="td-deco-ring" style="width:300px;height:300px;top:3%;right:-3%"></div>
<div class="td-deco-ring" style="width:200px;height:200px;bottom:10%;left:-2%;animation-delay:-3s"></div>

<div class="td-content">

    <!-- Back -->
    <a href="{{ URL::previous() !== url()->current() ? URL::previous() : route('trips.index') }}" class="td-back fade-up d1">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>

    <!-- Header Card -->
    <div class="g-card td-header fade-up d2">
        <div class="td-header-top">
            <div class="td-route">
                <div class="td-route-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                </div>
                <div class="td-route-text">
                    <div class="td-route-cities">
                        <span class="td-route-city">{{ $trip->origin_city }}</span>
                        <div class="td-route-arrow"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg></div>
                        <span class="td-route-city">{{ $trip->destination_city }}</span>
                    </div>
                    <div class="td-route-sub">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        Diposting oleh <strong style="color:rgba(255,255,255,.55)">{{ $trip->user->name }}</strong>
                    </div>
                </div>
            </div>
            <div class="td-status {{ $trip->status === 'open' ? 'ts-open' : ($trip->status === 'full' ? 'ts-full' : 'ts-done') }}">
                <span class="s-dot"></span>
                {{ $trip->status === 'open' ? 'Buka' : ($trip->status === 'full' ? 'Penuh' : 'Selesai') }}
            </div>
        </div>

        <div class="td-header-meta">
            @php
                $sPct = $trip->max_requests > 0 ? (($trip->max_requests - $trip->remaining_slots) / $trip->max_requests) * 100 : 0;
                $sCls = $trip->remaining_slots === 0 ? 'slot-red' : ($sPct > 70 ? 'slot-warn' : 'slot-gold');
                $sColor = $trip->remaining_slots === 0 ? 'color:rgba(239,68,68,.8)' : ($sPct > 70 ? 'color:rgba(251,191,36,.8)' : 'color:rgba(201,162,39,.8)');
            @endphp
            <div class="td-meta-item">
                <div class="td-meta-icon" style="background:rgba(201,162,39,.06)">
                    <svg fill="none" stroke="rgba(201,162,39,.5)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <div class="td-meta-text">
                    <span class="td-meta-label">Berangkat</span>
                    <span class="td-meta-value">{{ $trip->departure_date->format('d M Y') }}</span>
                </div>
            </div>
            <div class="td-meta-item">
                <div class="td-meta-icon" style="background:rgba(201,162,39,.06)">
                    <svg fill="none" stroke="rgba(201,162,39,.5)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <div class="td-meta-text">
                    <span class="td-meta-label">Tiba</span>
                    <span class="td-meta-value">{{ $trip->arrival_date ? $trip->arrival_date->format('d M Y') : '-' }}</span>
                </div>
            </div>
            <div class="td-meta-item">
                <div class="td-meta-icon" style="background:rgba(201,162,39,.06)">
                    <svg fill="none" stroke="rgba(201,162,39,.5)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                </div>
                <div class="td-meta-text">
                    <span class="td-meta-label">Transportasi</span>
                    <span class="td-meta-value">{{ $trip->transport_mode ?? '-' }}</span>
                </div>
            </div>
            <div class="td-meta-item">
                <div class="td-meta-icon" style="background:rgba(201,162,39,.06)">
                    <svg fill="none" stroke="rgba(201,162,39,.5)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                </div>
                <div class="td-meta-text">
                    <span class="td-meta-label">Kapasitas</span>
                    <span class="td-meta-value">{{ $trip->baggage_capacity ?? 'Standar' }}</span>
                </div>
            </div>
            <div class="td-meta-item" style="flex-direction:column;align-items:flex-start;gap:4px">
                <div style="display:flex;align-items:center;gap:6px">
                    <svg width="13" height="13" fill="none" stroke="rgba(201,162,39,.5)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    <span class="td-meta-label" style="margin:0">Slot</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;width:100%">
                    <div class="td-slot-bar"><div class="td-slot-fill {{ $sCls }}" style="width:{{ $sPct }}%"></div></div>
                    <span class="td-slot-text" style="{{ $sColor }}">{{ $trip->remaining_slots }}/{{ $trip->max_requests }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid -->
    <div class="td-grid">

        <!-- Left Column -->
        <div>
            <!-- Detail Section -->
            <div class="g-card td-section fade-up d3">
                <div class="td-section-title">
                    <div class="td-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </div>
                    <h2>Detail Perjalanan</h2>
                </div>
                <div class="info-grid">
                    <div class="info-box">
                        <p class="lbl"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg> Berangkat</p>
                        <p class="val">{{ $trip->departure_date->format('d M Y') }}</p>
                    </div>
                    <div class="info-box">
                        <p class="lbl"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg> Tiba</p>
                        <p class="val">{{ $trip->arrival_date ? $trip->arrival_date->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="info-box">
                        <p class="lbl"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg> Transportasi</p>
                        <p class="val">{{ $trip->transport_mode ?? '-' }}</p>
                    </div>
                    <div class="info-box">
                        <p class="lbl"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg> Kapasitas</p>
                        <p class="val">{{ $trip->baggage_capacity ?? 'Standar' }}</p>
                    </div>
                </div>
                @if($trip->notes)
                    <div class="td-note" style="margin-top:14px">
                        <div class="td-note-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.29.166 3.418 0 1.583-1.344 2.91-3.122 3.09-1.022.166-2.055.166-3.074 0-1.645-1.123-2.994-2.707-3.227C6.772 11.334 5.65 9.986 5.65 8.34c0-1.644 1.122-2.993 2.707-3.226 1.129-.167 2.29-.167 3.418 0 1.584 1.344 2.91 3.122 3.09 1.023.167 2.055.167 3.074 0z"/></svg>
                            Catatan Traveller
                        </div>
                        <p>{{ $trip->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Profile Section -->
            <div class="g-card td-section fade-up d4">
                <div class="td-section-title">
                    <div class="td-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <h2>Profil Traveller</h2>
                </div>
                <div class="profile-row">
                    <div class="profile-avatar"><div class="profile-avatar-inner">{{ strtoupper(substr($trip->user->name, 0, 1)) }}</div></div>
                    <div>
                        <p class="profile-name">{{ $trip->user->name }}</p>
                        <p class="profile-username">&commat;{{ $trip->user->username }}</p>
                        <div class="profile-stats">
                            <div class="profile-stat gold">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                {{ number_format($trip->user->rating_avg ?? 0, 1) }}
                            </div>
                            <span class="stat-dot">•</span>
                            <div class="profile-stat blue">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749V7.5A4.5 4.5 0 017.5 3h.75a5.996 5.996 0 015.058 2.772m0 0a3 3 0 00-5.184 0"/></svg>
                                {{ $trip->user->trust_score }} TP
                            </div>
                            <span class="stat-dot">•</span>
                            <div class="profile-stat muted">{{ $trip->user->total_trips }} transaksi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Form -->
            @auth
                @if($trip->status === 'open' && $trip->user_id !== Auth::id())
                    <div id="order-form" class="g-card-accent td-section fade-up d5">
                        <div class="td-section-title">
                            <div class="td-section-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            </div>
                            <h2>Ajukan Titip</h2>
                        </div>
                        <form action="{{ route('orders.createFromTrip', $trip) }}" method="POST">
                            @csrf
                            <div style="display:flex;flex-direction:column;gap:16px">
                                <div class="fade-up d6">
                                    <label class="field-label">Nama Barang *</label>
                                    <div class="form-field">
                                        <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                        <input type="text" name="item_name" required placeholder="Contoh: Bolen Meranti, Baju Batik" class="form-input">
                                    </div>
                                    @error('item_name')
                                        <p class="field-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="fade-up d7">
                                    <label class="field-label">Deskripsi</label>
                                    <div class="form-field">
                                        <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                        <textarea name="description" rows="2" maxlength="500" placeholder="Detail barang, merek, ukuran, warna..." class="form-input" style="min-height:80px"></textarea>
                                    </div>
                                </div>
                                <div class="fade-up d7">
                                    <label class="field-label">Budget (Rp) *</label>
                                    <div class="form-field">
                                        <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <input type="number" name="budget" required min="1000" placeholder="50000" class="form-input">
                                    </div>
                                    @error('budget')
                                        <p class="field-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="fade-up d8">
                                    <label class="field-label">Catatan untuk Traveller</label>
                                    <div class="form-field">
                                        <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.29.166 3.418 0 1.583-1.344 2.91-3.122 3.09-1.022.166-2.055.166-3.074 0-1.645-1.123-2.994-2.707-3.227C6.772 11.334 5.65 9.986 5.65 8.34c0-1.644 1.122-2.993 2.707-3.226 1.129-.167 2.29-.167 3.418 0 1.584 1.344 2.91 3.122 3.09 1.023.167 2.055.167 3.074 0z"/></svg>
                                        <textarea name="notes_from_requester" rows="2" maxlength="500" placeholder="Pesan khusus, lokasi beli, dll..." class="form-input" style="min-height:80px"></textarea>
                                    </div>
                                </div>
                                <div class="fade-up d8">
                                    <button type="submit" class="btn-gold">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                        Kirim Permintaan Titip
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            @else
                <div class="g-card td-section fade-up d5">
                    <div class="login-cta">
                        <div style="width:56px;height:56px;margin:0 auto 16px;border-radius:16px;background:linear-gradient(145deg,rgba(201,162,39,.08),rgba(201,162,39,.02));border:1px solid rgba(201,162,39,.08);display:flex;align-items:center;justify-content:center">
                            <svg width="24" height="24" fill="none" stroke="rgba(201,162,39,.4)" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h.008v.008h-.008v-.008zm0 3.75h.008v.008h-.008v-.008zm0-3.75h.008v.008h-.008v-.008zm3-9h.008v.008h-.008V3.75zm0 3.75h.008v.008h-.008V7.5zm0 3.75h.008v.008h-.008v-.008zm0 3.75h.008v.008h-.008v-.008z"/></svg>
                        </div>
                        <p>Mau titip? Login dulu ya!</p>
                        <a href="{{ route('login') }}" class="btn-login-sm">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Masuk
                        </a>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Right Sidebar -->
        <div>
            <!-- Orders List -->
            <div class="g-card td-section fade-up d4" style="margin-bottom:20px">
                <div class="td-section-title">
                    <div class="td-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    </div>
                    <h2>Titipan Aktif</h2>
                </div>
                @if($trip->orders->count() > 0)
                    <div style="display:flex;flex-direction:column;gap:10px">
                        @foreach($trip->orders as $order)
                            @php
                                $osMap = [
                                    'pending' => 'os-pending','accepted' => 'os-accepted',
                                    'payment_uploaded' => 'os-payment_uploaded','payment_verified' => 'os-payment_verified',
                                    'purchased' => 'os-purchased','in_transit' => 'os-in_transit',
                                    'delivered' => 'os-delivered','completed' => 'os-completed',
                                    'cancelled' => 'os-cancelled',
                                ];
                                $osCls = $osMap[$order->status] ?? 'os-default';
                            @endphp
                            <a href="{{ route('orders.show', $order) }}" style="text-decoration:none">
                                <div class="order-item">
                                    <p class="order-item-name">{{ $order->requester->name }}</p>
                                    @if($order->agreed_price)
                                        <p class="order-item-price">Rp {{ number_format($order->agreed_price, 0, ',', '.') }}</p>
                                    @endif
                                    <span class="order-item-status {{ $osCls }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="orders-empty">
                        <svg width="40" height="40" fill="none" stroke="rgba(201,162,39,.15)" viewBox="0 0 24 24" stroke-width="1.2" style="margin:0 auto 10px;display:block"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                        <p>Belum ada titipan.<br>Jadi yang pertama!</p>
                    </div>
                @endif
            </div>

            <!-- Management -->
            @auth
                @if($trip->user_id === Auth::id())
                    <div class="g-card td-section fade-up d5">
                        <div class="td-section-title">
                            <div class="td-section-icon" style="background:linear-gradient(145deg,rgba(255,255,255,.05),rgba(255,255,255,.01));border-color:rgba(255,255,255,.05)">
                                <svg fill="none" stroke="rgba(255,255,255,.4)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.525 1.075.843 1.836.645 3.364-.072 5.703-1.89 6.772-1.093.56-2.343-.05-2.343-1.89 0-1.507.645-2.893 1.682-3.962.542-.56 1.362-1.083 2.343-1.083h8.564c.782 0 1.8.523 2.343 1.083 1.037 1.07 1.14 2.455 1.682 3.962.078 1.84-.85 2.893-1.89 6.772-1.093 1.56-3.463 2.45-5.703 1.89-.782 0-1.8-.523-2.343-1.083-1.037-1.07-1.14-2.455-1.682-3.962-.078-1.84.85-2.893 1.89-6.772z"/></svg>
                            </div>
                            <h2>Kelola</h2>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:10px">
                            <a href="{{ route('trips.edit', $trip) }}" class="btn-manage btn-edit">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652-2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                Edit Perjalanan
                            </a>
                            <form action="{{ route('trips.destroy', $trip) }}" method="POST" onsubmit="return confirm('Yakin hapus perjalanan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-manage btn-delete">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    Hapus Perjalanan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var bgPhoto = document.getElementById('tdBgPhoto');
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

    if (window.location.hash === '#order-form') {
        setTimeout(function() {
            var el = document.getElementById('order-form');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }
});
</script>
@endsection