@extends('layouts.app')

@section('title', 'Order #' . substr($order->id, 0, 8))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="preload" as="image" href="https://i1-c.pinimg.com/1200x/a9/ce/50/a9ce50d5bb0e183316d95aa7e6582a07.jpg">
<style>
*,*::before,*::after{box-sizing:border-box}
body{font-family:'Inter',sans-serif!important;background:#08080f!important;color:#f0f0f5!important;overflow-x:hidden}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:#08080f}
::-webkit-scrollbar-thumb{background:rgba(201,162,39,.2);border-radius:3px}
::-webkit-scrollbar-thumb:hover{background:rgba(201,162,39,.35)}
html{scrollbar-width:thin;scrollbar-color:rgba(201,162,39,.2) #08080f}

nav,header{background:rgba(8,8,15,.85)!important;backdrop-filter:blur(20px)!important;-webkit-backdrop-filter:blur(20px)!important;border-bottom:1px solid rgba(255,255,255,.04)!important}

input:-webkit-autofill,input:-webkit-autofill:hover,input:-webkit-autofill:focus,
textarea:-webkit-autofill,select:-webkit-autofill{-webkit-text-fill-color:rgba(255,255,255,.9)!important;-webkit-box-shadow:0 0 0 1000px rgba(10,10,20,.95) inset!important;transition:background-color 5000s ease-in-out 0s}

/* ═══ BACKGROUND IMAGE ═══ */
.bg-fallback{position:fixed;inset:0;z-index:-1;background:#08080f}
.bg-photo{
    position:fixed;inset:0;z-index:0;
    background:url('https://i1-c.pinimg.com/1200x/a9/ce/50/a9ce50d5bb0e183316d95aa7e6582a07.jpg') center/cover no-repeat;
    opacity:0;transition:opacity 1.8s ease;transform:scale(1.03);
}
.bg-photo.loaded{opacity:1}

/* ═══ OVERLAYS ═══ */
.overlay-left{
    position:fixed;left:0;top:0;width:50%;height:100%;z-index:1;
    background:linear-gradient(155deg,rgba(8,8,15,.92) 0%,rgba(8,8,15,.78) 55%,rgba(8,8,15,.65) 100%);
    pointer-events:none;
}
.overlay-right{
    position:fixed;right:0;top:0;width:50%;height:100%;z-index:1;
    background:rgba(8,8,15,.25);
    pointer-events:none;
}
.overlay-mobile{
    display:none;position:fixed;inset:0;z-index:1;
    background:linear-gradient(180deg,rgba(8,8,15,.2) 0%,rgba(8,8,15,.4) 35%,rgba(8,8,15,.88) 100%);
    pointer-events:none;
}
/* Subtle grid texture */
.overlay-grid{
    position:fixed;inset:0;z-index:1;pointer-events:none;
    background-image:linear-gradient(rgba(201,162,39,.012) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.012) 1px,transparent 1px);
    background-size:60px 60px;
    mask-image:radial-gradient(ellipse at 50% 30%,black 5%,transparent 50%);
    -webkit-mask-image:radial-gradient(ellipse at 50% 30%,black 5%,transparent 50%);
}

@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes shimmerBtn{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
@keyframes dotBreath{0%,100%{opacity:.12}50%{opacity:.4}}
@keyframes pulseGlow{0%,100%{box-shadow:0 0 0 0 rgba(201,162,39,.15)}50%{box-shadow:0 0 20px 4px rgba(201,162,39,.08)}}
.fade-up{opacity:0;animation:fadeUp .65s cubic-bezier(.23,1,.32,1) forwards}
.s1{animation-delay:.05s}.s2{animation-delay:.1s}.s3{animation-delay:.16s}
.s4{animation-delay:.22s}.s5{animation-delay:.28s}.s6{animation-delay:.34s}
.s7{animation-delay:.4s}.s8{animation-delay:.46s}.s9{animation-delay:.52s}
.s10{animation-delay:.58s}.s11{animation-delay:.64s}

.glass{background:rgba(10,10,22,.55);backdrop-filter:blur(24px) saturate(1.2);-webkit-backdrop-filter:blur(24px) saturate(1.2);border:1px solid rgba(255,255,255,.055);border-radius:20px;box-shadow:0 4px 40px rgba(0,0,0,.2),inset 0 1px 0 rgba(255,255,255,.03);transition:all .4s cubic-bezier(.23,1,.32,1)}
.glass:hover{border-color:rgba(255,255,255,.08);box-shadow:0 8px 50px rgba(0,0,0,.25),inset 0 1px 0 rgba(255,255,255,.04)}
.glass-gold:hover{border-color:rgba(201,162,39,.1);box-shadow:0 8px 50px rgba(0,0,0,.25),0 0 30px rgba(201,162,39,.03),inset 0 1px 0 rgba(255,255,255,.04)}

.sec-label{display:flex;align-items:center;gap:10px;margin-bottom:20px}
.sec-label .dot{width:6px;height:6px;border-radius:50%;background:rgba(201,162,39,.4);flex-shrink:0}
.sec-label .line{flex:1;height:1px;background:linear-gradient(90deg,rgba(201,162,39,.08),transparent)}
.sec-label span{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.18em;color:rgba(201,162,39,.45);white-space:nowrap}

.status-badge{display:inline-flex;align-items:center;gap:7px;padding:7px 18px;border-radius:20px;font-size:12.5px;font-weight:700;letter-spacing:.02em;backdrop-filter:blur(8px);border:1px solid}
.status-badge svg{flex-shrink:0}

.progress-track{position:relative;display:flex;align-items:center;justify-content:space-between;padding:8px 0}
.progress-line{position:absolute;top:50%;left:8%;right:8%;height:2px;background:rgba(255,255,255,.04);transform:translateY(-50%);border-radius:1px;z-index:0}
.progress-line-fill{position:absolute;top:50%;left:8%;height:2px;background:linear-gradient(90deg,rgba(201,162,39,.3),rgba(201,162,39,.6));transform:translateY(-50%);border-radius:1px;z-index:1;transition:width .8s cubic-bezier(.23,1,.32,1)}
.step-dot{position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;gap:6px;cursor:default}
.step-circle{width:38px;height:38px;border-radius:12px;display:flex;align-items:center;justify-content:center;transition:all .4s cubic-bezier(.23,1,.32,1);border:1px solid transparent}
.step-circle.done{background:linear-gradient(135deg,rgba(201,162,39,.15),rgba(201,162,39,.05));border-color:rgba(201,162,39,.2);color:#c9a227}
.step-circle.done:hover{transform:scale(1.1) rotate(-3deg)}
.step-circle.current{background:linear-gradient(135deg,#c9a227,#b08a22);border-color:rgba(201,162,39,.4);color:#0c0c18;animation:pulseGlow 2.5s ease-in-out infinite;box-shadow:0 4px 20px rgba(201,162,39,.2)}
.step-circle.pending{background:rgba(255,255,255,.025);border-color:rgba(255,255,255,.05);color:rgba(255,255,255,.15)}
.step-label{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;transition:color .3s}
.step-label.done{color:rgba(201,162,39,.7)}
.step-label.current{color:rgba(201,162,39,1)}
.step-label.pending{color:rgba(255,255,255,.15)}

.party-card{position:relative;overflow:hidden;border-radius:16px;padding:24px;text-align:center;transition:all .4s cubic-bezier(.23,1,.32,1)}
.party-card::before{content:'';position:absolute;inset:0;pointer-events:none}
.party-card.blue{background:rgba(59,130,246,.04);border:1px solid rgba(59,130,246,.06)}
.party-card.blue::before{background:radial-gradient(ellipse at 50% 0%,rgba(59,130,246,.04),transparent 60%)}
.party-card.blue:hover{border-color:rgba(59,130,246,.12);transform:translateY(-3px)}
.party-card.purple{background:rgba(168,85,247,.04);border:1px solid rgba(168,85,247,.06)}
.party-card.purple::before{background:radial-gradient(ellipse at 50% 0%,rgba(168,85,247,.04),transparent 60%)}
.party-card.purple:hover{border-color:rgba(168,85,247,.12);transform:translateY(-3px)}
.party-avatar{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;margin:0 auto 12px;transition:transform .4s}
.party-card:hover .party-avatar{transform:scale(1.08)}
.party-role{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;margin-bottom:8px;display:flex;align-items:center;justify-content:center;gap:5px}
.party-name{font-size:15px;font-weight:700;color:#f0f0f5;margin-bottom:4px}
.party-rating{font-size:12px;color:rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;gap:4px}
.party-rating svg{color:#c9a227;flex-shrink:0}

.detail-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0}
.detail-row+.detail-row{border-top:1px solid rgba(255,255,255,.03)}
.detail-label{font-size:13.5px;color:rgba(255,255,255,.3);font-weight:400}
.detail-value{font-size:14px;font-weight:600;color:rgba(255,255,255,.8);text-align:right}
.detail-value.gold{color:rgba(201,162,39,.9)}
.detail-value.big{font-size:20px;font-weight:800;color:#c9a227}

.img-display{position:relative;border-radius:14px;overflow:hidden;border:1px solid rgba(255,255,255,.05);transition:all .4s}
.img-display:hover{border-color:rgba(201,162,39,.12);transform:scale(1.01)}
.img-display img{width:100%;max-width:360px;display:block;border-radius:13px}
.img-display::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,transparent 60%,rgba(8,8,15,.3));pointer-events:none;border-radius:13px}

.log-item{display:flex;gap:14px;padding:12px 0;position:relative}
.log-item+.log-item{border-top:1px solid rgba(255,255,255,.025)}
.log-dot-wrap{display:flex;flex-direction:column;align-items:center;flex-shrink:0;width:20px;padding-top:4px}
.log-dot{width:10px;height:10px;border-radius:50%;border:2px solid rgba(201,162,39,.25);background:rgba(201,162,39,.08);flex-shrink:0;transition:all .3s}
.log-item:first-child .log-dot{border-color:rgba(201,162,39,.5);background:rgba(201,162,39,.2);box-shadow:0 0 8px rgba(201,162,39,.15)}
.log-line{width:1px;flex:1;background:rgba(255,255,255,.03);margin-top:4px;min-height:20px}
.log-status{font-size:13.5px;font-weight:600;color:rgba(255,255,255,.7)}
.log-item:first-child .log-status{color:#f0f0f5}
.log-note{font-size:12.5px;color:rgba(255,255,255,.25);margin-top:2px;line-height:1.5}
.log-time{font-size:11px;color:rgba(255,255,255,.15);margin-top:3px}

.form-field{position:relative}
.form-field::after{content:'';position:absolute;bottom:0;left:50%;width:0;height:2px;background:linear-gradient(90deg,transparent,#c9a227,transparent);transition:all .4s cubic-bezier(.23,1,.32,1);transform:translateX(-50%);border-radius:1px}
.form-field:focus-within::after{width:92%}
.form-input,.form-select,.form-textarea{width:100%;padding:12px 16px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.055);border-radius:12px;color:rgba(255,255,255,.9);font-size:13.5px;font-family:'Inter',sans-serif;font-weight:400;outline:none;transition:all .3s}
.form-input::placeholder,.form-textarea::placeholder{color:rgba(255,255,255,.12)}
.form-input:focus,.form-select:focus,.form-textarea:focus{background:rgba(255,255,255,.05);border-color:rgba(201,162,39,.16)}
.form-select{appearance:none;cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='rgba(255,255,255,0.2)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:36px}
.form-select option{background:#0c0c18;color:#f0f0f5}
.form-textarea{resize:vertical;min-height:64px}
.form-label{display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.22);margin-bottom:6px;transition:color .3s}
.form-field:focus-within .form-label{color:rgba(201,162,39,.5)}
.form-file{width:100%;padding:10px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.055);border-radius:12px;color:rgba(255,255,255,.5);font-size:12.5px;font-family:'Inter',sans-serif;cursor:pointer;transition:all .3s}
.form-file:hover{border-color:rgba(201,162,39,.12);background:rgba(255,255,255,.04)}
.form-file::file-selector-button{padding:6px 14px;border-radius:8px;border:1px solid rgba(201,162,39,.15);background:rgba(201,162,39,.06);color:rgba(201,162,39,.8);font-size:11.5px;font-weight:600;font-family:'Inter',sans-serif;cursor:pointer;margin-right:10px;transition:all .3s}
.form-file::file-selector-button:hover{background:rgba(201,162,39,.12);border-color:rgba(201,162,39,.25)}

.btn-gold{width:100%;padding:13px;border:none;border-radius:13px;font-family:'Inter',sans-serif;font-size:14px;font-weight:700;cursor:pointer;position:relative;overflow:hidden;background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);background-size:200% 200%;color:#0c0c18;box-shadow:0 2px 20px rgba(201,162,39,.12),inset 0 1px 0 rgba(255,255,255,.2);transition:all .35s cubic-bezier(.23,1,.32,1);display:flex;align-items:center;justify-content:center;gap:8px}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 35px rgba(201,162,39,.25),inset 0 1px 0 rgba(255,255,255,.25);background-position:100% 100%}
.btn-gold:active{transform:translateY(0) scale(.98)}
.btn-gold::after{content:'';position:absolute;inset:0;background:linear-gradient(105deg,transparent 40%,rgba(255,255,255,.22) 50%,transparent 60%);transform:translateX(-100%)}
.btn-gold:hover::after{animation:shimmerBtn 1.8s ease-in-out infinite}

.btn-green{width:100%;padding:13px;border:none;border-radius:13px;font-family:'Inter',sans-serif;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,rgba(34,197,94,.2),rgba(34,197,94,.08));border:1px solid rgba(34,197,94,.15);color:rgba(134,239,172,.9);transition:all .3s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-green:hover{background:linear-gradient(135deg,rgba(34,197,94,.25),rgba(34,197,94,.12));border-color:rgba(34,197,94,.25);transform:translateY(-1px)}

.btn-outline-red{width:100%;padding:12px;border:1px solid rgba(239,68,68,.12);border-radius:13px;font-family:'Inter',sans-serif;font-size:13px;font-weight:600;cursor:pointer;background:rgba(239,68,68,.03);color:rgba(252,165,165,.7);transition:all .3s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-outline-red:hover{background:rgba(239,68,68,.06);border-color:rgba(239,68,68,.2);color:rgba(252,165,165,.9)}

.btn-red{width:100%;padding:13px;border:none;border-radius:13px;font-family:'Inter',sans-serif;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,rgba(239,68,68,.2),rgba(239,68,68,.08));border:1px solid rgba(239,68,68,.15);color:rgba(252,165,165,.9);transition:all .3s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-red:hover{background:linear-gradient(135deg,rgba(239,68,68,.25),rgba(239,68,68,.12));border-color:rgba(239,68,68,.25);transform:translateY(-1px)}

.btn-yellow{width:100%;padding:13px;border:none;border-radius:13px;font-family:'Inter',sans-serif;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,rgba(234,179,8,.2),rgba(234,179,8,.08));border:1px solid rgba(234,179,8,.15);color:rgba(250,204,21,.9);transition:all .3s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-yellow:hover{background:linear-gradient(135deg,rgba(234,179,8,.25),rgba(234,179,8,.12));border-color:rgba(234,179,8,.25);transform:translateY(-1px)}

.rating-form{background:rgba(234,179,8,.03);border:1px solid rgba(234,179,8,.08);border-radius:16px;padding:20px;position:relative;overflow:hidden}
.rating-form::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:60%;height:1px;background:linear-gradient(90deg,transparent,rgba(234,179,8,.15),transparent)}

.rated-badge{display:flex;align-items:center;justify-content:center;gap:8px;padding:14px;border-radius:12px;background:rgba(34,197,94,.04);border:1px solid rgba(34,197,94,.08);color:rgba(134,239,172,.7);font-size:13px;font-weight:600}

.dispute-form{background:rgba(239,68,68,.02);border:1px solid rgba(239,68,68,.06);border-radius:16px;padding:18px}

.link-back{color:rgba(255,255,255,.18);text-decoration:none;font-weight:500;transition:all .3s;display:inline-flex;align-items:center;gap:6px;font-size:13px}
.link-back:hover{color:rgba(255,255,255,.4)}
.link-back svg{transition:transform .3s}
.link-back:hover svg{transform:translateX(-3px)}

@media(max-width:1023px){
    .overlay-left,.overlay-right{display:none}
    .overlay-mobile{display:block}
    .order-grid{grid-template-columns:1fr!important}
    .order-sidebar{order:-1}
}
@media(max-width:640px){
    .parties-grid{grid-template-columns:1fr!important}
    .progress-track{overflow-x:auto;padding-bottom:8px}
    .step-label{font-size:9px}
    .step-circle{width:32px;height:32px;border-radius:10px}
}

@media(prefers-reduced-motion:reduce){
    .fade-up{animation:none!important;opacity:1!important}
    *{animation-duration:0s!important;transition-duration:0s!important}
}
</style>
@endpush

@section('content')
<!-- Dark fallback -->
<div class="bg-fallback"></div>

<!-- Background photo -->
<div class="bg-photo" id="bgPhoto"></div>

<!-- Overlays -->
<div class="overlay-left"></div>
<div class="overlay-right"></div>
<div class="overlay-mobile"></div>
<div class="overlay-grid"></div>

<div style="max-width:1120px;margin:0 auto;padding:24px 16px 60px;position:relative;z-index:2">

    <!-- Back Link -->
    <div class="fade-up s1" style="margin-bottom:24px">
        <a href="{{ route('orders.index') }}" class="link-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Pesanan
        </a>
    </div>

    @php
        $statusConfig = [
            'pending'           => ['bg'=>'rgba(234,179,8,.06)','border'=>'rgba(234,179,8,.12)','text'=>'rgba(250,204,21,.85)'],
            'accepted'          => ['bg'=>'rgba(59,130,246,.06)','border'=>'rgba(59,130,246,.12)','text'=>'rgba(147,197,253,.85)'],
            'payment_uploaded'  => ['bg'=>'rgba(168,85,247,.06)','border'=>'rgba(168,85,247,.12)','text'=>'rgba(196,181,253,.85)'],
            'payment_verified'  => ['bg'=>'rgba(99,102,241,.06)','border'=>'rgba(99,102,241,.12)','text'=>'rgba(165,180,252,.85)'],
            'purchased'         => ['bg'=>'rgba(249,115,22,.06)','border'=>'rgba(249,115,22,.12)','text'=>'rgba(253,186,116,.85)'],
            'in_transit'        => ['bg'=>'rgba(6,182,212,.06)','border'=>'rgba(6,182,212,.12)','text'=>'rgba(103,232,249,.85)'],
            'delivered'         => ['bg'=>'rgba(34,197,94,.06)','border'=>'rgba(34,197,94,.12)','text'=>'rgba(134,239,172,.85)'],
            'completed'         => ['bg'=>'rgba(34,197,94,.1)','border'=>'rgba(34,197,94,.2)','text'=>'rgba(74,222,128,.9)'],
            'cancelled'         => ['bg'=>'rgba(239,68,68,.06)','border'=>'rgba(239,68,68,.12)','text'=>'rgba(252,165,165,.85)'],
            'disputed'          => ['bg'=>'rgba(239,68,68,.1)','border'=>'rgba(239,68,68,.2)','text'=>'rgba(252,165,165,.9)'],
        ];
        $statusLabels = [
            'pending'=>'Menunggu','accepted'=>'Diterima','payment_uploaded'=>'Verifikasi Pembayaran',
            'payment_verified'=>'Siap Dibeli','purchased'=>'Dibeli','in_transit'=>'Dalam Perjalanan',
            'delivered'=>'Sampai','completed'=>'Selesai','cancelled'=>'Dibatalkan','disputed'=>'Dispute',
        ];
        $statusIcons = [
            'pending'          => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'accepted'         => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'payment_uploaded' => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
            'payment_verified' => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
            'purchased'        => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>',
            'in_transit'       => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>',
            'delivered'        => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
            'completed'        => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'cancelled'        => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'disputed'         => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
        ];
        $cfg = $statusConfig[$order->status] ?? ['bg'=>'rgba(255,255,255,.04)','border'=>'rgba(255,255,255,.08)','text'=>'rgba(255,255,255,.6)'];
        $statusIconHtml = $statusIcons[$order->status] ?? $statusIcons['pending'];
        $statusLabel = $statusLabels[$order->status] ?? $order->status;

        $steps = ['pending','accepted','payment_verified','purchased','in_transit','delivered','completed'];
        $stepNames = ['pending'=>'Request','accepted'=>'Accept','payment_verified'=>'Bayar','purchased'=>'Beli','in_transit'=>'Kirim','delivered'=>'Sampai','completed'=>'Rate'];
        $stepSvgIcons = [
            'pending'          => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'accepted'         => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>',
            'payment_verified' => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
            'purchased'        => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>',
            'in_transit'       => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"/></svg>',
            'delivered'        => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
            'completed'        => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
        ];
        $currentStep = array_search($order->status, $steps);
        if ($currentStep === false) $currentStep = -1;
        $isCancelled = in_array($order->status, ['cancelled','disputed']);
        $fillPct = $isCancelled ? 0 : (($currentStep >= 0) ? (($currentStep / (count($steps)-1)) * 84) : 0);
        $deliveryBtns = [
            'payment_verified' => ['status'=>'purchased','label'=>'Barang Sudah Dibeli','btnClass'=>'btn-gold','icon'=>'<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>'],
            'purchased'        => ['status'=>'in_transit','label'=>'Barang Dalam Perjalanan','btnClass'=>'btn-gold','icon'=>'<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"/></svg>'],
            'in_transit'       => ['status'=>'delivered','label'=>'Barang Sudah Sampai','btnClass'=>'btn-green','icon'=>'<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'],
        ];
    @endphp

    <!-- HEADER CARD -->
    <div class="glass fade-up s2" style="padding:28px 32px;margin-bottom:24px">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:28px">
            <div style="display:flex;align-items:center;gap:14px">
                <div style="width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,rgba(201,162,39,.12),rgba(201,162,39,.04));border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="22" height="22" fill="none" stroke="#c9a227" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h1 style="font-size:22px;font-weight:800;color:#f0f0f5;letter-spacing:-.02em">Order #{{ substr($order->id, 0, 8) }}</h1>
                    <p style="font-size:12px;color:rgba(255,255,255,.2);margin-top:2px">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
            <span class="status-badge" style="background:{{ $cfg['bg'] }};border-color:{{ $cfg['border'] }};color:{{ $cfg['text'] }}">
                {!! $statusIconHtml !!}
                {{ $statusLabel }}
            </span>
        </div>

        <!-- Progress Steps -->
        <div class="progress-track">
            <div class="progress-line"></div>
            @if(!$isCancelled)
                <div class="progress-line-fill" style="width:{{ $fillPct }}%"></div>
            @endif
            @foreach($steps as $index => $step)
                @php
                    $isDone = !$isCancelled && $index < $currentStep;
                    $isCurrent = !$isCancelled && $index === $currentStep;
                    $stateClass = $isDone ? 'done' : ($isCurrent ? 'current' : 'pending');
                @endphp
                <div class="step-dot">
                    <div class="step-circle {{ $stateClass }}">
                        {!! $stepSvgIcons[$step] !!}
                    </div>
                    <span class="step-label {{ $stateClass }}">{{ $stepNames[$step] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="order-grid" style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

        <!-- LEFT COLUMN -->
        <div style="display:flex;flex-direction:column;gap:24px">

            <!-- Parties -->
            <div class="glass glass-gold fade-up s3" style="padding:28px">
                <div class="sec-label">
                    <div class="dot"></div>
                    <span>Pihak Terkait</span>
                    <div class="line"></div>
                </div>
                <div class="parties-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="party-card blue">
                        <div class="party-role" style="color:rgba(147,197,253,.5)">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Traveller
                        </div>
                        <div class="party-avatar" style="background:linear-gradient(135deg,rgba(59,130,246,.12),rgba(59,130,246,.04));border:1px solid rgba(59,130,246,.1);color:rgba(147,197,253,.8)">
                            {{ substr($order->traveller->name, 0, 1) }}
                        </div>
                        <div class="party-name">{{ $order->traveller->name }}</div>
                        <div class="party-rating">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            {{ number_format($order->traveller->rating_avg, 1) }}
                        </div>
                    </div>
                    <div class="party-card purple">
                        <div class="party-role" style="color:rgba(196,181,253,.5)">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Peminta
                        </div>
                        <div class="party-avatar" style="background:linear-gradient(135deg,rgba(168,85,247,.12),rgba(168,85,247,.04));border:1px solid rgba(168,85,247,.1);color:rgba(196,181,253,.8)">
                            {{ substr($order->requester->name, 0, 1) }}
                        </div>
                        <div class="party-name">{{ $order->requester->name }}</div>
                        <div class="party-rating">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            {{ number_format($order->requester->rating_avg, 1) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="glass fade-up s4" style="padding:28px">
                <div class="sec-label">
                    <div class="dot"></div>
                    <span>Detail Transaksi</span>
                    <div class="line"></div>
                </div>
                <div>
                    <div class="detail-row">
                        <span class="detail-label">Harga Disepakati</span>
                        <span class="detail-value gold">Rp {{ number_format($order->agreed_price, 0, ',', '.') }}</span>
                    </div>
                    @if($order->service_fee > 0)
                    <div class="detail-row">
                        <span class="detail-label">Biaya Layanan</span>
                        <span class="detail-value">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="detail-row" style="border-top:1px solid rgba(201,162,39,.08);padding-top:14px;margin-top:4px">
                        <span class="detail-label" style="font-weight:700;color:rgba(255,255,255,.5);font-size:14px">Total</span>
                        <span class="detail-value big">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($order->payment_proof)
            <div class="glass fade-up s5" style="padding:28px">
                <div class="sec-label">
                    <div class="dot"></div>
                    <span>Bukti Pembayaran</span>
                    <div class="line"></div>
                </div>
                <div class="img-display">
                    <img src="{{ Storage::url($order->payment_proof) }}" alt="Bukti bayar">
                </div>
            </div>
            @endif

            @if($order->item_photo)
            <div class="glass fade-up s6" style="padding:28px">
                <div class="sec-label">
                    <div class="dot"></div>
                    <span>Foto Barang</span>
                    <div class="line"></div>
                </div>
                <div class="img-display">
                    <img src="{{ Storage::url($order->item_photo) }}" alt="Barang">
                </div>
            </div>
            @endif

            <!-- Order Logs -->
            <div class="glass fade-up s7" style="padding:28px">
                <div class="sec-label">
                    <div class="dot"></div>
                    <span>Riwayat Status</span>
                    <div class="line"></div>
                </div>
                <div>
                    @foreach($order->logs->sortByDesc('created_at') as $log)
                        <div class="log-item">
                            <div class="log-dot-wrap">
                                <div class="log-dot"></div>
                                @if(!$loop->last)
                                    <div class="log-line"></div>
                                @endif
                            </div>
                            <div style="padding-bottom:4px">
                                <div class="log-status">{{ $statusLabels[$log->status] ?? $log->status }}</div>
                                @if($log->note)
                                    <div class="log-note">{{ $log->note }}</div>
                                @endif
                                <div class="log-time">{{ $log->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="order-sidebar" style="display:flex;flex-direction:column;gap:24px;position:sticky;top:24px">

            <div class="glass glass-gold fade-up s5" style="padding:24px">
                <div class="sec-label" style="margin-bottom:16px">
                    <div class="dot"></div>
                    <span>Aksi</span>
                    <div class="line"></div>
                </div>
                <div style="display:flex;flex-direction:column;gap:12px">

                    @if($order->traveller_id === Auth::id() && $order->status === 'pending')
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn-gold">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Terima Titipan
                            </button>
                        </form>
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn-outline-red">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak
                            </button>
                        </form>
                    @endif

                    @if($order->requester_id === Auth::id() && $order->status === 'accepted')
                        <form action="{{ route('orders.uploadPayment', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="form-label">Upload Bukti Transfer</label>
                            <input type="file" name="payment_proof" accept="image/*" required class="form-file" style="margin-bottom:12px">
                            <button type="submit" class="btn-gold">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Upload Bukti Bayar
                            </button>
                        </form>
                    @endif

                    @if($order->traveller_id === Auth::id() && isset($deliveryBtns[$order->status]))
                        @php $next = $deliveryBtns[$order->status]; @endphp
                        <form action="{{ route('orders.updateDelivery', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="status" value="{{ $next['status'] }}">
                            <label class="form-label" style="display:flex;align-items:center;gap:5px">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Foto Barang <span style="color:rgba(255,255,255,.1)">(opsional)</span>
                            </label>
                            <input type="file" name="item_photo" accept="image/*" class="form-file" style="margin-bottom:10px">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" rows="2" placeholder="Catatan tambahan..." class="form-textarea" style="margin-bottom:14px"></textarea>
                            <button type="submit" class="{{ $next['btnClass'] }}">
                                {!! $next['icon'] !!}
                                {{ $next['label'] }}
                            </button>
                        </form>
                    @endif

                    @if($order->requester_id === Auth::id() && $order->status === 'delivered')
                        <form action="{{ route('orders.confirmReceived', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Konfirmasi Barang Diterima
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'completed' && $order->isParticipant(Auth::user()))
                        @php $hasRated = $order->ratings->where('rater_id', Auth::id())->count() > 0; @endphp
                        @if(!$hasRated)
                            <form action="{{ route('ratings.store', $order) }}" method="POST" class="rating-form">
                                @csrf
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px">
                                    <svg width="16" height="16" fill="#c9a227" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <span style="font-size:13px;font-weight:700;color:#f0f0f5">Beri Rating</span>
                                </div>
                                <select name="score" required class="form-select" style="margin-bottom:10px">
                                    <option value="">Pilih rating...</option>
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Baik</option>
                                    <option value="4">⭐⭐⭐⭐ Baik</option>
                                    <option value="3">⭐⭐⭐ Cukup</option>
                                    <option value="2">⭐⭐ Kurang</option>
                                    <option value="1">⭐ Sangat Buruk</option>
                                </select>
                                <textarea name="comment" rows="2" placeholder="Komentar (opsional)..." class="form-textarea" style="margin-bottom:12px"></textarea>
                                <button type="submit" class="btn-yellow">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    Kirim Rating
                                </button>
                            </form>
                        @else
                            <div class="rated-badge">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Kamu sudah memberi rating
                            </div>
                        @endif
                    @endif

                    @if($order->requester_id === Auth::id() && in_array($order->status, ['pending', 'accepted', 'payment_uploaded']))
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Yakin batalkan order?')">
                            @csrf
                            <button type="submit" class="btn-outline-red">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Batalkan Order
                            </button>
                        </form>
                    @endif

                    @if(in_array($order->status, ['accepted', 'payment_verified', 'purchased', 'in_transit', 'delivered']))
                        <div class="dispute-form">
                            <form action="{{ route('orders.dispute', $order) }}" method="POST" onsubmit="return confirm('Laporkan masalah? Admin akan meninjau.')">
                                @csrf
                                <label class="form-label" style="color:rgba(252,165,165,.4);display:flex;align-items:center;gap:5px">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Laporkan Masalah
                                </label>
                                <textarea name="reason" rows="2" required placeholder="Jelaskan masalahnya..." class="form-textarea" style="margin-bottom:10px;border-color:rgba(239,68,68,.08)"></textarea>
                                <button type="submit" class="btn-red">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Kirim Laporan
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Order ID card -->
            <div class="glass fade-up s7" style="padding:18px 22px;text-align:center">
                <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.18em;color:rgba(255,255,255,.12);margin-bottom:6px">Order ID</div>
                <div style="font-size:15px;font-weight:700;color:rgba(255,255,255,.4);font-variant-numeric:tabular-nums;letter-spacing:.04em">{{ $order->id }}</div>
                <div style="display:flex;justify-content:center;gap:5px;margin-top:12px">
                    <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.15);animation:dotBreath 3s ease-in-out infinite"></div>
                    <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.15);animation:dotBreath 3s ease-in-out infinite;animation-delay:.5s"></div>
                    <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.15);animation:dotBreath 3s ease-in-out infinite;animation-delay:1s"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isDesktop = window.innerWidth >= 1024;

    /* ═══ 1. Load background image with fade-in ═══ */
    const bgPhoto = document.getElementById('bgPhoto');
    if (bgPhoto) {
        const img = new Image();
        img.onload = () => bgPhoto.classList.add('loaded');
        img.onerror = () => {};
        img.src = 'https://i1-c.pinimg.com/1200x/a9/ce/50/a9ce50d5bb0e183316d95aa7e6582a07.jpg';
    }

    if (reducedMotion) {
        document.querySelectorAll('.fade-up').forEach(el => { el.style.animation = 'none'; el.style.opacity = '1'; });
        return;
    }

    /* ═══ 2. Background parallax on mousemove (desktop) ═══ */
    if (isDesktop && bgPhoto) {
        document.addEventListener('mousemove', e => {
            const x = (e.clientX / window.innerWidth - 0.5) * -10;
            const y = (e.clientY / window.innerHeight - 0.5) * -10;
            bgPhoto.style.transform = `scale(1.04) translate(${x}px, ${y}px)`;
            bgPhoto.style.transition = 'transform 0.2s ease-out';
        });
        document.addEventListener('mouseleave', () => {
            bgPhoto.style.transform = 'scale(1.04) translate(0, 0)';
            bgPhoto.style.transition = 'transform 0.6s ease-out';
        });
    }

    /* ═══ 3. Glass card tilt (desktop) ═══ */
    if (isDesktop) {
        document.querySelectorAll('.glass-gold').forEach(card => {
            card.addEventListener('mouseenter', () => { card.style.transition = 'transform 0.1s ease-out'; });
            card.addEventListener('mousemove', e => {
                const r = card.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - 0.5;
                const y = (e.clientY - r.top) / r.height - 0.5;
                card.style.transform = `perspective(800px) rotateY(${x * 1.5}deg) rotateX(${y * -1.5}deg) translateY(-4px)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(800px) rotateY(0) rotateX(0) translateY(0)';
                card.style.transition = 'transform 0.5s ease-out';
            });
        });
    }

    /* ═══ 4. Button ripple ═══ */
    if (!document.getElementById('ripple-style')) {
        const s = document.createElement('style');
        s.id = 'ripple-style';
        s.textContent = '@keyframes rippleAnim{to{transform:scale(4);opacity:0}}';
        document.head.appendChild(s);
    }
    document.querySelectorAll('.btn-gold, .btn-green, .btn-yellow, .btn-red').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const rect = btn.getBoundingClientRect();
            const cx = e.clientX ?? rect.left + rect.width / 2;
            const cy = e.clientY ?? rect.top + rect.height / 2;
            const span = document.createElement('span');
            const sz = Math.max(rect.width, rect.height) * 1.2;
            span.style.cssText = `position:absolute;border-radius:50%;background:rgba(255,255,255,.2);width:${sz}px;height:${sz}px;left:${cx - rect.left - sz/2}px;top:${cy - rect.top - sz/2}px;transform:scale(0);animation:rippleAnim .6s ease-out forwards;pointer-events:none`;
            btn.style.position = 'relative';
            btn.style.overflow = 'hidden';
            btn.appendChild(span);
            setTimeout(() => span.remove(), 600);
        });
    });
});
</script>
@endsection