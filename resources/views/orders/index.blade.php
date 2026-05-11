@extends('layouts.app')

@section('title', 'Order Saya')

@section('content')
<style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body.orders-page{background:#08080f!important;color:#f0f0f5!important;overflow-x:hidden}

    .orders-bg{position:fixed;inset:0;z-index:0;pointer-events:none}
    .orders-bg-photo{
        position:absolute;inset:0;
        background:url('https://i1-c.pinimg.com/1200x/5b/a2/cd/5ba2cdf02b39a46348815851c8fbaaf3.jpg') center/cover no-repeat;
        opacity:0;transition:opacity 2s ease;transform:scale(1.03);
    }
    .orders-bg-photo.loaded{opacity:.50}
    .orders-bg-fallback{position:absolute;inset:0;background:#08080f}
    .orders-bg-overlay{
        position:absolute;inset:0;
        background:linear-gradient(180deg,rgba(8,8,15,.72) 0%,rgba(8,8,15,.62) 40%,rgba(8,8,15,.78) 100%);
    }
    .orders-bg-grid{
        position:absolute;inset:0;
        background-image:linear-gradient(rgba(201,162,39,.015) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.015) 1px,transparent 1px);
        background-size:80px 80px;
        mask-image:radial-gradient(ellipse at 50% 30%,black 5%,transparent 55%);-webkit-mask-image:radial-gradient(ellipse at 50% 30%,black 5%,transparent 55%);
    }

    .orders-deco-ring{
        position:fixed;border-radius:50%;border:1px solid rgba(201,162,39,.05);pointer-events:none;z-index:0;
        animation:ringPulse 7s ease-in-out infinite;
    }
    @keyframes ringPulse{0%,100%{opacity:.06;transform:scale(1)}50%{opacity:.12;transform:scale(1.04)}}

    .orders-content{position:relative;z-index:1;max-width:960px;margin:0 auto;padding:32px 20px 60px}

    .orders-header{margin-bottom:32px}
    .orders-header-badge{
        display:inline-flex;align-items:center;gap:8px;padding:5px 14px;border-radius:20px;
        background:rgba(201,162,39,.08);border:1px solid rgba(201,162,39,.12);
        backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);margin-bottom:16px;
    }
    .orders-header-badge-dot{width:6px;height:6px;border-radius:50%;background:#c9a227;animation:float 2.5s ease-in-out infinite}
    .orders-header-badge span{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.12em;color:rgba(201,162,39,.8)}
    .orders-header h1{
        font-family:'Inter',sans-serif;font-size:clamp(28px,4.5vw,40px);font-weight:800;
        line-height:1.1;letter-spacing:-.03em;color:#f0f0f5;margin-bottom:8px;
    }
    .orders-header h1 .text-gold{
        background:linear-gradient(135deg,#c9a227,#e0c05a,#c9a227);-webkit-background-clip:text;background-clip:text;color:transparent;
    }
    .orders-header p{font-size:15px;color:rgba(255,255,255,.35);font-weight:300;line-height:1.5}

    .orders-tabs{
        display:flex;gap:6px;flex-wrap:wrap;padding:6px;border-radius:16px;
        background:rgba(255,255,255,.035);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
        border:1px solid rgba(255,255,255,.06);margin-bottom:28px;
    }
    .orders-tab{
        display:inline-flex;align-items:center;gap:7px;padding:10px 20px;border-radius:11px;
        font-size:13px;font-weight:600;color:rgba(255,255,255,.4);text-decoration:none;
        transition:all .35s cubic-bezier(.23,1,.32,1);position:relative;overflow:hidden;
    }
    .orders-tab:hover{color:rgba(255,255,255,.65);background:rgba(255,255,255,.04)}
    .orders-tab.active{
        color:#0c0c18;background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);
        background-size:200% 200%;box-shadow:0 2px 20px rgba(201,162,39,.22),inset 0 1px 0 rgba(255,255,255,.25);
    }
    .orders-tab.active:hover{background-position:100% 100%}
    .orders-tab .tab-icon{width:16px;height:16px;flex-shrink:0}
    .orders-tab .tab-count{
        font-size:10px;font-weight:700;padding:1px 7px;border-radius:6px;
        background:rgba(255,255,255,.08);color:rgba(255,255,255,.45);min-width:20px;text-align:center;
    }
    .orders-tab.active .tab-count{background:rgba(0,0,0,.15);color:rgba(12,12,24,.6)}

    .order-card{
        position:relative;padding:24px 28px;border-radius:20px;
        background:rgba(12,12,24,.55);backdrop-filter:blur(24px) saturate(1.2);-webkit-backdrop-filter:blur(24px) saturate(1.2);
        border:1px solid rgba(255,255,255,.06);
        transition:all .4s cubic-bezier(.23,1,.32,1);overflow:hidden;
    }
    .order-card::before{
        content:'';position:absolute;top:0;left:0;right:0;height:1px;
        background:linear-gradient(90deg,transparent,rgba(201,162,39,.08),transparent);
        opacity:0;transition:opacity .4s;pointer-events:none;
    }
    .order-card:hover{
        background:rgba(12,12,24,.6);border-color:rgba(201,162,39,.12);
        transform:translateY(-3px);box-shadow:0 16px 56px rgba(0,0,0,.3),0 0 0 1px rgba(201,162,39,.06);
    }
    .order-card:hover::before{opacity:1}
    .order-card-inner{position:relative;z-index:1;display:flex;flex-direction:column;gap:16px}

    .order-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px}
    .order-route{display:flex;align-items:center;gap:14px}
    .order-route-icon{
        width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;
        background:linear-gradient(145deg,rgba(201,162,39,.1),rgba(201,162,39,.03));
        border:1px solid rgba(201,162,39,.1);flex-shrink:0;position:relative;overflow:hidden;
        transition:all .4s;
    }
    .order-route-icon::after{
        content:'';position:absolute;inset:0;
        background:linear-gradient(180deg,rgba(255,255,255,.08),transparent 50%);pointer-events:none;
    }
    .order-card:hover .order-route-icon{background:linear-gradient(145deg,rgba(201,162,39,.16),rgba(201,162,39,.06));border-color:rgba(201,162,39,.2);transform:scale(1.05)}
    .order-route-icon svg{width:22px;height:22px;color:rgba(201,162,39,.7);position:relative;z-index:1}
    .order-route-text{display:flex;flex-direction:column;gap:3px}
    .order-route-cities{display:flex;align-items:center;gap:10px}
    .order-route-city{font-size:17px;font-weight:700;color:#f0f0f5;letter-spacing:-.01em}
    .order-route-arrow-wrap{
        display:flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:7px;
        background:rgba(201,162,39,.06);border:1px solid rgba(201,162,39,.08);
    }
    .order-route-arrow-wrap svg{width:13px;height:13px;color:rgba(201,162,39,.5)}
    .order-route-label{font-size:12px;color:rgba(255,255,255,.22);font-weight:400;display:flex;align-items:center;gap:5px}
    .order-route-label svg{width:13px;height:13px;color:rgba(255,255,255,.15)}

    .order-meta{display:flex;flex-wrap:wrap;gap:16px 28px;padding:14px 0 0;border-top:1px solid rgba(255,255,255,.04)}
    .order-meta-item{display:flex;flex-direction:column;gap:3px}
    .order-meta-label{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.13em;color:rgba(255,255,255,.18);display:flex;align-items:center;gap:4px}
    .order-meta-label svg{width:11px;height:11px;opacity:.5}
    .order-meta-value{font-size:13.5px;font-weight:500;color:rgba(255,255,255,.6)}
    .order-meta-value.price{color:#c9a227;font-weight:700;font-size:15px;display:flex;align-items:center;gap:4px}
    .order-meta-value.price svg{width:14px;height:14px}
    .order-meta-value.person{color:rgba(255,255,255,.8);font-weight:600;display:flex;align-items:center;gap:5px}
    .order-meta-value.person svg{width:14px;height:14px;color:rgba(201,162,39,.4)}
    .order-meta-value.order-id{font-family:'JetBrains Mono','SF Mono',monospace;font-size:11.5px;color:rgba(255,255,255,.18);letter-spacing:.02em}

    .order-status{
        display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:10px;
        font-size:12px;font-weight:600;white-space:nowrap;flex-shrink:0;
        backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
    }
    .order-status .status-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0}
    .status-pending{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.15);color:rgba(251,191,36,.9)}
    .status-pending .status-dot{background:rgba(245,158,11,.7);animation:statusPulse 2s ease-in-out infinite}
    .status-accepted{background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.15);color:rgba(96,165,250,.9)}
    .status-accepted .status-dot{background:rgba(59,130,246,.7)}
    .status-payment_uploaded{background:rgba(168,85,247,.1);border:1px solid rgba(168,85,247,.15);color:rgba(192,132,252,.9)}
    .status-payment_uploaded .status-dot{background:rgba(168,85,247,.7)}
    .status-payment_verified{background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.15);color:rgba(129,140,248,.9)}
    .status-payment_verified .status-dot{background:rgba(99,102,241,.7)}
    .status-purchased{background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.15);color:rgba(251,146,60,.9)}
    .status-purchased .status-dot{background:rgba(249,115,22,.7)}
    .status-in_transit{background:rgba(6,182,212,.1);border:1px solid rgba(6,182,212,.15);color:rgba(34,211,238,.9)}
    .status-in_transit .status-dot{background:rgba(6,182,212,.7);animation:statusPulse 1.5s ease-in-out infinite}
    .status-delivered{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.15);color:rgba(74,222,128,.9)}
    .status-delivered .status-dot{background:rgba(34,197,94,.7)}
    .status-completed{background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.18);color:rgba(74,222,128,1)}
    .status-completed .status-dot{background:rgba(34,197,94,.8)}
    .status-cancelled{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.15);color:rgba(248,113,113,.9)}
    .status-cancelled .status-dot{background:rgba(239,68,68,.7)}
    .status-disputed{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.18);color:rgba(248,113,113,1)}
    .status-disputed .status-dot{background:rgba(239,68,68,.8);animation:statusPulse 1s ease-in-out infinite}
    .status-default{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);color:rgba(255,255,255,.5)}
    .status-default .status-dot{background:rgba(255,255,255,.25)}

    @keyframes statusPulse{0%,100%{opacity:.5;transform:scale(1)}50%{opacity:1;transform:scale(1.4)}}

    .order-bottom{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .order-time{font-size:12px;color:rgba(255,255,255,.18);font-weight:400;display:flex;align-items:center;gap:5px}
    .order-time svg{width:13px;height:13px;opacity:.4}

    .btn-detail{
        display:inline-flex;align-items:center;gap:8px;padding:10px 24px;border-radius:12px;
        font-family:'Inter',sans-serif;font-size:13px;font-weight:600;
        color:rgba(255,255,255,.65);text-decoration:none;cursor:pointer;
        background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
        transition:all .3s cubic-bezier(.23,1,.32,1);position:relative;overflow:hidden;
    }
    .btn-detail:hover{
        background:rgba(201,162,39,.1);border-color:rgba(201,162,39,.2);
        color:rgba(201,162,39,.95);transform:translateY(-1px);box-shadow:0 4px 20px rgba(201,162,39,.1);
    }
    .btn-detail:hover .btn-detail-arrow{transform:translateX(3px);color:rgba(201,162,39,.8)}
    .btn-detail svg{width:15px;height:15px;transition:all .3s}
    .btn-detail-arrow{transition:all .3s}

    .orders-pagination{margin-top:32px;display:flex;justify-content:center}
    .orders-pagination .pagination{display:flex;gap:4px;flex-wrap:wrap;justify-content:center}
    .orders-pagination .pagination a,
    .orders-pagination .pagination span{
        display:inline-flex;align-items:center;justify-content:center;
        min-width:38px;height:38px;padding:0 10px;border-radius:10px;
        font-size:13px;font-weight:500;color:rgba(255,255,255,.35);
        background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);
        text-decoration:none;transition:all .3s;
    }
    .orders-pagination .pagination a:hover{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.08);color:rgba(255,255,255,.6)}
    .orders-pagination .pagination .active,
    .orders-pagination .pagination .page-link.active{background:rgba(201,162,39,.12);border-color:rgba(201,162,39,.2);color:#c9a227;font-weight:700}
    .orders-pagination .pagination .disabled span{opacity:.25;cursor:default}

    .orders-empty{text-align:center;padding:72px 32px;border-radius:24px;background:rgba(12,12,24,.45);border:1px solid rgba(255,255,255,.05);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px)}
    .orders-empty-icon{
        width:88px;height:88px;margin:0 auto 28px;border-radius:24px;
        background:linear-gradient(145deg,rgba(201,162,39,.08),rgba(201,162,39,.02));
        border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center;
        position:relative;overflow:hidden;
    }
    .orders-empty-icon::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.06),transparent 50%);pointer-events:none}
    .orders-empty-icon svg{width:40px;height:40px;color:rgba(201,162,39,.4);position:relative;z-index:1}
    .orders-empty h2{font-size:22px;font-weight:700;color:rgba(255,255,255,.65);margin-bottom:10px}
    .orders-empty p{font-size:14px;color:rgba(255,255,255,.22);margin-bottom:36px;line-height:1.65;max-width:400px;margin-left:auto;margin-right:auto}
    .orders-empty-btns{display:flex;flex-wrap:wrap;gap:10px;justify-content:center}

    .btn-gold{
        display:inline-flex;align-items:center;gap:8px;padding:13px 30px;
        background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);
        background-size:200% 200%;color:#0c0c18;font-family:'Inter',sans-serif;
        font-size:14px;font-weight:700;border:none;border-radius:13px;
        text-decoration:none;cursor:pointer;position:relative;overflow:hidden;
        box-shadow:0 2px 20px rgba(201,162,39,.15),inset 0 1px 0 rgba(255,255,255,.2);
        transition:all .35s cubic-bezier(.23,1,.32,1);
    }
    .btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 35px rgba(201,162,39,.28);background-position:100% 100%}
    .btn-gold:active{transform:translateY(0) scale(.98)}
    .btn-gold svg{width:16px;height:16px}
    .btn-glass{
        display:inline-flex;align-items:center;gap:8px;padding:13px 30px;
        background:rgba(255,255,255,.04);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
        border:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.7);
        font-family:'Inter',sans-serif;font-size:14px;font-weight:600;border-radius:13px;
        text-decoration:none;cursor:pointer;transition:all .3s;
    }
    .btn-glass:hover{background:rgba(255,255,255,.07);border-color:rgba(201,162,39,.12);color:#f0f0f5}
    .btn-glass svg{width:16px;height:16px}

    @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
    @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}

    .fade-up{opacity:0;animation:fadeUp .65s cubic-bezier(.23,1,.32,1) forwards}
    .d1{animation-delay:.05s}.d2{animation-delay:.1s}.d3{animation-delay:.16s}
    .d4{animation-delay:.22s}.d5{animation-delay:.28s}.d6{animation-delay:.34s}

    .order-card-anim{opacity:0;transform:translateY(20px);transition:all .5s cubic-bezier(.23,1,.32,1)}
    .order-card-anim.visible{opacity:1;transform:translateY(0)}

    @media(max-width:767px){
        .orders-content{padding:20px 14px 48px}
        .order-card{padding:18px 18px;border-radius:16px}
        .order-top{flex-direction:column;gap:12px}
        .order-meta{gap:12px 18px}
        .order-bottom{flex-direction:column;align-items:stretch;gap:10px}
        .btn-detail{justify-content:center}
        .order-status{align-self:flex-start}
        .orders-tab{padding:8px 14px;font-size:12px}
        .order-route-icon{width:42px;height:42px;border-radius:12px}
    }
    @media(max-width:400px){
        .order-route-icon{width:38px;height:38px;border-radius:10px}
        .order-route-icon svg{width:18px;height:18px}
        .order-route-city{font-size:14px}
        .order-meta{gap:10px 14px}
    }
    @media(prefers-reduced-motion:reduce){
        .fade-up,.order-card-anim{animation:none!important;transition:none!important;opacity:1!important;transform:none!important}
        *{animation:none!important;transition-duration:0s!important}
    }
</style>

<div class="orders-bg">
    <div class="orders-bg-fallback"></div>
    <div class="orders-bg-photo" id="ordersBgPhoto"></div>
    <div class="orders-bg-overlay"></div>
    <div class="orders-bg-grid"></div>
</div>

<div class="orders-deco-ring" style="width:350px;height:350px;top:5%;right:-5%"></div>
<div class="orders-deco-ring" style="width:220px;height:220px;bottom:15%;left:-3%;animation-delay:-3s"></div>

<div class="orders-content">

    <div class="orders-header fade-up d1">
        <div class="orders-header-badge">
            <div class="orders-header-badge-dot"></div>
            <span>Order Management</span>
        </div>
        <h1>Order <span class="text-gold">Saya</span></h1>
        <p>Kelola dan pantau semua transaksi titip beli kamu di satu tempat.</p>
    </div>

    <div class="orders-tabs fade-up d2">
        <a href="{{ route('orders.index') }}" class="orders-tab {{ !request('type') || request('type') === 'all' ? 'active' : '' }}">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <span>Semua</span>
            <span class="tab-count">{{ $orders->total() }}</span>
        </a>
        <a href="{{ route('orders.index', ['type' => 'as_traveller']) }}" class="orders-tab {{ request('type') === 'as_traveller' ? 'active' : '' }}">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
            <span>Sebagai Traveller</span>
        </a>
        <a href="{{ route('orders.index', ['type' => 'as_requester']) }}" class="orders-tab {{ request('type') === 'as_requester' ? 'active' : '' }}">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            <span>Sebagai Peminta</span>
        </a>
    </div>

    @if($orders->count() > 0)
        <div style="display:flex;flex-direction:column;gap:14px">
            @foreach($orders as $index => $order)
                @php
                    $statusMap = [
                        'pending' => ['class' => 'status-pending', 'label' => 'Pending'],
                        'accepted' => ['class' => 'status-accepted', 'label' => 'Diterima'],
                        'payment_uploaded' => ['class' => 'status-payment_uploaded', 'label' => 'Bayar Diupload'],
                        'payment_verified' => ['class' => 'status-payment_verified', 'label' => 'Bayar Verified'],
                        'purchased' => ['class' => 'status-purchased', 'label' => 'Dibeli'],
                        'in_transit' => ['class' => 'status-in_transit', 'label' => 'Dalam Perjalanan'],
                        'delivered' => ['class' => 'status-delivered', 'label' => 'Sampai'],
                        'completed' => ['class' => 'status-completed', 'label' => 'Selesai'],
                        'cancelled' => ['class' => 'status-cancelled', 'label' => 'Dibatalkan'],
                        'disputed' => ['class' => 'status-disputed', 'label' => 'Dispute'],
                    ];
                    $st = $statusMap[$order->status] ?? ['class' => 'status-default', 'label' => $order->status];
                    $delay = 0.05 + ($index * 0.07);
                @endphp

                <div class="order-card order-card-anim" style="transition-delay:{{ $delay }}s">
                    <div class="order-card-inner">
                        <div class="order-top">
                            <div class="order-route">
                                <div class="order-route-icon">
                                    @if($order->trip)
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                    @endif
                                </div>
                                <div class="order-route-text">
                                    @if($order->trip)
                                        <div class="order-route-cities">
                                            <span class="order-route-city">{{ $order->trip->origin_city }}</span>
                                            <div class="order-route-arrow-wrap">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                            </div>
                                            <span class="order-route-city">{{ $order->trip->destination_city }}</span>
                                        </div>
                                        <div class="order-route-label">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                            {{ $order->trip->departure_date ? \Carbon\Carbon::parse($order->trip->departure_date)->format('d M Y') : 'Tanggal TBD' }}
                                        </div>
                                    @else
                                        <span class="order-route-city">Titipan</span>
                                        <div class="order-route-label">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                                            Pesanan titipan
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="order-status {{ $st['class'] }}">
                                <span class="status-dot"></span>
                                {{ $st['label'] }}
                            </div>
                        </div>

                        <div class="order-meta">
                            <div class="order-meta-item">
                                <span class="order-meta-label">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                    @if($order->traveller_id === Auth::id())
                                        Peminta
                                    @else
                                        Traveller
                                    @endif
                                </span>
                                <span class="order-meta-value person">
                                    @if($order->traveller_id === Auth::id())
                                        {{ $order->requester->name }}
                                    @else
                                        {{ $order->traveller->name }}
                                    @endif
                                </span>
                            </div>
                            @if($order->agreed_price)
                                <div class="order-meta-item">
                                    <span class="order-meta-label">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Harga
                                    </span>
                                    <span class="order-meta-value price">
                                        Rp {{ number_format($order->agreed_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                            <div class="order-meta-item">
                                <span class="order-meta-label">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                                    ID Order
                                </span>
                                <span class="order-meta-value order-id">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>

                        <div class="order-bottom">
                            <div class="order-time">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                            <a href="{{ route('orders.show', $order) }}" class="btn-detail">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                Detail Order
                                <svg class="btn-detail-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="orders-pagination">
            {{ $orders->links() }}
        </div>
    @else
        <div class="orders-empty fade-up d3">
            <div class="orders-empty-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            </div>
            <h2>Belum Ada Order</h2>
            <p>Jelajahi perjalanan traveller atau buat permintaan titip untuk mulai transaksi pertamamu!</p>
            <div class="orders-empty-btns">
                <a href="{{ route('trips.index') }}" class="btn-gold">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                    Jelajah Perjalanan
                </a>
                <a href="{{ route('requests.index') }}" class="btn-glass">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    Buat Permintaan
                </a>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var bgPhoto = document.getElementById('ordersBgPhoto');

    if (bgPhoto) {
        var img = new Image();
        img.onload = function() { bgPhoto.classList.add('loaded'); };
        img.src = 'https://i.pinimg.com/736x/91/e2/b2/91e2b24b89293458e673c1840d6c39cc.jpg';
    }

    if (!reducedMotion) {
        var cards = document.querySelectorAll('.order-card-anim');
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
        document.querySelectorAll('.order-card-anim').forEach(function(c) { c.classList.add('visible'); });
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