@extends('layouts.app')

@section('title', 'Posting Perjalanan')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="preload" as="image" href="https://i1-c.pinimg.com/1200x/c7/db/43/c7db4392f4f568712b3284e0e9a485c5.jpg">
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

.bg-fallback{position:fixed;inset:0;z-index:-1;background:#08080f}
.bg-photo{
    position:fixed;inset:0;z-index:0;
    background:url('https://i1-c.pinimg.com/1200x/c7/db/43/c7db4392f4f568712b3284e0e9a485c5.jpg') center/cover no-repeat;
    opacity:0;transition:opacity 1.8s ease;transform:scale(1.03);
}
.bg-photo.loaded{opacity:.5}
.overlay-left{
    position:fixed;left:0;top:0;width:50%;height:100%;z-index:1;
    background:linear-gradient(155deg,rgba(8,8,15,.88) 0%,rgba(8,8,15,.72) 55%,rgba(8,8,15,.58) 100%);
    pointer-events:none;
}
.overlay-right{
    position:fixed;right:0;top:0;width:50%;height:100%;z-index:1;
    background:rgba(8,8,15,.18);
    pointer-events:none;
}
.overlay-mobile{
    display:none;position:fixed;inset:0;z-index:1;
    background:linear-gradient(180deg,rgba(8,8,15,.12) 0%,rgba(8,8,15,.3) 35%,rgba(8,8,15,.85) 100%);
    pointer-events:none;
}
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
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
@keyframes iconPulse{0%,100%{filter:drop-shadow(0 0 6px rgba(201,162,39,.15))}50%{filter:drop-shadow(0 0 14px rgba(201,162,39,.3))}}
.fade-up{opacity:0;animation:fadeUp .65s cubic-bezier(.23,1,.32,1) forwards}
.s1{animation-delay:.05s}.s2{animation-delay:.1s}.s3{animation-delay:.16s}
.s4{animation-delay:.22s}.s5{animation-delay:.28s}.s6{animation-delay:.34s}
.s7{animation-delay:.4s}.s8{animation-delay:.46s}.s9{animation-delay:.52s}
.s10{animation-delay:.58s}.s11{animation-delay:.64s}.s12{animation-delay:.7s}

/* ═══ GLASS CARD — blur gelap persis kayak trip-card di gambar 2 ═══ */
.glass-card{
    width:100%;max-width:520px;margin:0 auto;
    position:relative;overflow:hidden;
    background:rgba(12,12,24,.6);
    backdrop-filter:blur(28px) saturate(1.3);
    -webkit-backdrop-filter:blur(28px) saturate(1.3);
    border-radius:24px;padding:36px 34px;
    border:1px solid rgba(255,255,255,.05);
    box-shadow:0 20px 60px rgba(0,0,0,.25),inset 0 1px 0 rgba(255,255,255,.04);
    transition:transform .15s ease-out;
}
/* top glow line */
.glass-card::before{
    content:'';position:absolute;top:0;left:8%;right:8%;height:1px;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.06),transparent);
    pointer-events:none;z-index:5;
}
/* bottom gold accent line */
.glass-card::after{
    content:'';position:absolute;bottom:0;left:20%;right:20%;height:2px;border-radius:2px;
    background:linear-gradient(90deg,rgba(201,162,39,.25),rgba(212,176,64,.4),rgba(201,162,39,.25));
    pointer-events:none;z-index:5;
}

.form-field{position:relative}
.form-field::after{content:'';position:absolute;bottom:0;left:50%;width:0;height:2px;background:linear-gradient(90deg,transparent,#c9a227,transparent);transition:all .4s cubic-bezier(.23,1,.32,1);transform:translateX(-50%);border-radius:1px}
.form-field:focus-within::after{width:90%}
.form-input,.form-select,.form-textarea{
    width:100%;padding:13px 16px 13px 46px;
    background:rgba(255,255,255,.035);
    border:1px solid rgba(255,255,255,.055);
    border-radius:13px;color:rgba(255,255,255,.9);
    font-size:14px;font-family:'Inter',sans-serif;font-weight:400;
    outline:none;transition:all .3s;
}
.form-input::placeholder,.form-textarea::placeholder{color:rgba(255,255,255,.12)}
.form-input:focus,.form-select:focus,.form-textarea:focus{background:rgba(255,255,255,.055);border-color:rgba(201,162,39,.18)}
.form-field:focus-within .field-icon{color:#c9a227}
.form-field:focus-within .form-label{color:rgba(201,162,39,.55)}
.field-icon{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.12);transition:all .3s;pointer-events:none}
.form-field:focus-within .field-icon{transform:translateY(-50%) scale(1.1)}

.form-input.no-icon,.form-select.no-icon,.form-textarea.no-icon{padding-left:16px}
.form-select.no-icon{
    background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='rgba(255,255,255,0.2)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat;background-position:right 15px center;padding-right:38px;
}
.form-select{appearance:none;cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='rgba(255,255,255,0.2)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 15px center;padding-right:38px}
.form-select option{background:#0c0c18;color:#f0f0f5}
.form-textarea{resize:vertical;min-height:80px}
.form-label{display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.24);margin-bottom:7px;transition:color .3s}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form-error{font-size:11.5px;color:rgba(252,165,165,.8);margin-top:5px;margin-left:2px;display:flex;align-items:center;gap:4px}

input[type="date"]::-webkit-calendar-picker-indicator{filter:invert(.6);cursor:pointer;opacity:.5;transition:opacity .3s}
input[type="date"]:focus::-webkit-calendar-picker-indicator{opacity:.8}
input[type="date"]{color-scheme:dark}

.char-count{text-align:right;font-size:11px;color:rgba(255,255,255,.12);margin-top:4px;transition:color .3s}
.char-count.near{color:rgba(234,179,8,.5)}
.char-count.full{color:rgba(252,165,165,.7)}

.btn-gold{width:100%;padding:14px;border:none;border-radius:14px;font-family:'Inter',sans-serif;font-size:15px;font-weight:700;cursor:pointer;position:relative;overflow:hidden;background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);background-size:200% 200%;color:#0c0c18;box-shadow:0 2px 20px rgba(201,162,39,.12),inset 0 1px 0 rgba(255,255,255,.2);transition:all .35s cubic-bezier(.23,1,.32,1);display:flex;align-items:center;justify-content:center;gap:8px}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 35px rgba(201,162,39,.25),inset 0 1px 0 rgba(255,255,255,.25);background-position:100% 100%}
.btn-gold:active{transform:translateY(0) scale(.98)}
.btn-gold::after{content:'';position:absolute;inset:0;background:linear-gradient(105deg,transparent 40%,rgba(255,255,255,.22) 50%,transparent 60%);transform:translateX(-100%)}
.btn-gold:hover::after{animation:shimmerBtn 1.8s ease-in-out infinite}
.btn-gold .ripple-fx{position:absolute;border-radius:50%;background:rgba(255,255,255,.35);transform:scale(0);animation:rippleAnim .6s ease-out forwards;pointer-events:none}

.btn-ghost{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:14px;border:1px solid rgba(255,255,255,.07);border-radius:14px;background:rgba(255,255,255,.02);color:rgba(255,255,255,.4);font-family:'Inter',sans-serif;font-size:14px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .3s}
.btn-ghost:hover{background:rgba(255,255,255,.05);border-color:rgba(255,255,255,.1);color:rgba(255,255,255,.6)}

.link-back{color:rgba(255,255,255,.14);text-decoration:none;font-weight:500;transition:all .3s;display:inline-flex;align-items:center;gap:5px;font-size:12.5px}
.link-back:hover{color:rgba(255,255,255,.4)}
.link-back svg{transition:transform .3s}
.link-back:hover svg{transform:translateX(-3px)}

.section-divider{display:flex;align-items:center;gap:14px;margin:20px 0}
.section-divider::before,.section-divider::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.04)}
.section-divider span{font-size:9px;text-transform:uppercase;letter-spacing:.2em;font-weight:700;color:rgba(201,162,39,.3)}

.content-layer{position:relative;z-index:2;min-height:100vh;display:flex;flex-direction:column;justify-content:center;padding:40px 16px}

.icon-hero{
    width:60px;height:60px;margin:0 auto;border-radius:18px;
    background:linear-gradient(145deg,rgba(201,162,39,.14),rgba(201,162,39,.04));
    border:1px solid rgba(201,162,39,.12);
    display:flex;align-items:center;justify-content:center;
    position:relative;overflow:hidden;
    animation:float 5s ease-in-out infinite,iconPulse 4s ease-in-out infinite;
}
.icon-hero::before{
    content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;
    background:conic-gradient(from 0deg,transparent,rgba(201,162,39,.08),transparent,rgba(201,162,39,.04),transparent);
    animation:spinSlow 8s linear infinite;
}
@keyframes spinSlow{to{transform:rotate(360deg)}}
.icon-hero svg{position:relative;z-index:1}

@media(max-width:1023px){
    .overlay-left,.overlay-right{display:none}
    .overlay-mobile{display:block}
    .content-layer{justify-content:flex-end;padding:0}
    .glass-card{
        max-width:100%;border-radius:24px 24px 0 0;padding:28px 22px 36px;
        box-shadow:0 -8px 60px rgba(0,0,0,.4),inset 0 1px 0 rgba(255,255,255,.04);
        background:rgba(12,12,24,.65);
        backdrop-filter:blur(28px) saturate(1.3);
        -webkit-backdrop-filter:blur(28px) saturate(1.3);
        max-height:90dvh;overflow-y:auto;
    }
    .glass-card::-webkit-scrollbar{display:none}
    .glass-card{scrollbar-width:none}
    .mobile-top{display:flex!important}
}
@media(min-width:1024px){.mobile-top{display:none!important}}
@media(max-width:480px){
    .form-row{grid-template-columns:1fr}
    .form-input,.form-select,.form-textarea{padding-left:40px;font-size:13px}
    .field-icon{left:13px}
    .glass-card{padding:24px 18px 32px}
    .btn-row{flex-direction:column}
}
@media(prefers-reduced-motion:reduce){
    .fade-up{animation:none!important;opacity:1!important}
    *{animation-duration:0s!important;transition-duration:0s!important}
}
::-webkit-scrollbar{display:none}html{scrollbar-width:none}
@keyframes spin{to{transform:rotate(360deg)}}
@keyframes rippleAnim{to{transform:scale(4);opacity:0}}
</style>
@endpush

@section('content')
<svg width="0" height="0" style="position:absolute">
    <defs>
        <linearGradient id="gGold" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#e0c05a"/>
            <stop offset="50%" stop-color="#c9a227"/>
            <stop offset="100%" stop-color="#a88520"/>
        </linearGradient>
        <linearGradient id="gGoldSoft" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="rgba(224,192,90,.6)"/>
            <stop offset="100%" stop-color="rgba(168,133,32,.6)"/>
        </linearGradient>
        <linearGradient id="gGoldFill" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" stop-color="rgba(201,162,39,.12)"/>
            <stop offset="100%" stop-color="rgba(201,162,39,.03)"/>
        </linearGradient>
    </defs>
</svg>

<div class="bg-fallback"></div>
<div class="bg-photo" id="bgPhoto"></div>
<div class="overlay-left"></div>
<div class="overlay-right"></div>
<div class="overlay-mobile"></div>
<div class="overlay-grid"></div>

<div class="content-layer">
    <div class="glass-card" id="glassCard">

        <div class="mobile-top" style="display:none;margin-bottom:16px">
            <a href="{{ route('trips.index') }}" class="link-back">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <div class="fade-up s1" style="text-align:center;margin-bottom:8px">
            <div class="icon-hero">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L9.5 9.5L2 12L9.5 14.5L12 22L14.5 14.5L22 12L14.5 9.5L12 2Z" fill="url(#gGoldFill)" stroke="url(#gGold)" stroke-width="1.2" stroke-linejoin="round"/>
                    <path d="M12 6L10.5 10.5L6 12L10.5 13.5L12 18L13.5 13.5L18 12L13.5 10.5L12 6Z" fill="url(#gGoldSoft)" stroke="none" opacity=".5"/>
                    <circle cx="12" cy="12" r="1.5" fill="#c9a227" opacity=".8"/>
                </svg>
            </div>
        </div>

        <div class="fade-up s2" style="text-align:center;margin-bottom:4px">
            <h1 style="font-size:24px;font-weight:800;color:#f0f0f5;letter-spacing:-.02em">Posting Perjalanan</h1>
        </div>
        <div class="fade-up s3" style="text-align:center;margin-bottom:24px">
            <p style="font-size:13.5px;color:rgba(255,255,255,.24);font-weight:300">Bagikan rute kamu dan dapatkan cuan dari titipan</p>
        </div>

        <form action="{{ route('trips.store') }}" method="POST" id="tripForm">
            @csrf

            <div class="form-row fade-up s4" style="margin-bottom:16px">
                <div>
                    <label class="form-label">Kota Asal *</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="currentColor" stroke-width="1.5" fill="url(#gGoldFill)" opacity=".6"/>
                            <circle cx="12" cy="9" r="2.5" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            <path d="M9.5 9l1.5 1.5L14.5 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity=".5"/>
                        </svg>
                        <input type="text" name="origin_city" value="{{ old('origin_city') }}" required placeholder="Jakarta" class="form-input">
                    </div>
                    @error('origin_city')
                        <p class="form-error">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Kota Tujuan *</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M3.5 9.5L12 3l8.5 6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity=".4"/>
                            <path d="M12 3v18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3"/>
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" fill="url(#gGoldFill)" opacity=".5"/>
                            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.3" fill="none"/>
                            <circle cx="12" cy="12" r="1" fill="currentColor" opacity=".6"/>
                            <path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width=".8" stroke-linecap="round" opacity=".3"/>
                        </svg>
                        <input type="text" name="destination_city" value="{{ old('destination_city') }}" required placeholder="Surabaya" class="form-input">
                    </div>
                    @error('destination_city')
                        <p class="form-error">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="fade-up s4" style="display:flex;align-items:center;justify-content:center;margin:-8px 0 16px">
                <div style="display:flex;align-items:center;gap:10px;padding:5px 16px;border-radius:20px;background:rgba(201,162,39,.04);border:1px solid rgba(201,162,39,.06)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h14" stroke="rgba(201,162,39,.35)" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M15 8l4 4-4 4" stroke="rgba(201,162,39,.35)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span style="font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(201,162,39,.3)">Rute</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M19 12H5" stroke="rgba(201,162,39,.35)" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M9 8l-4 4 4 4" stroke="rgba(201,162,39,.35)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="form-row fade-up s5" style="margin-bottom:16px">
                <div>
                    <label class="form-label">Tanggal Berangkat *</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.4" fill="url(#gGoldFill)" opacity=".5"/>
                            <path d="M3 9h18" stroke="currentColor" stroke-width="1.4"/>
                            <path d="M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                            <rect x="7" y="12" width="3" height="3" rx=".5" fill="currentColor" opacity=".4"/>
                            <rect x="14" y="12" width="3" height="3" rx=".5" fill="url(#gGoldSoft)" opacity=".7"/>
                        </svg>
                        <input type="date" name="departure_date" value="{{ old('departure_date') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-input">
                    </div>
                    @error('departure_date')
                        <p class="form-error">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <label class="form-label" style="color:rgba(255,255,255,.16)">Tanggal Tiba</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.4" fill="none" opacity=".6"/>
                            <path d="M3 9h18" stroke="currentColor" stroke-width="1.4" opacity=".5"/>
                            <path d="M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity=".5"/>
                            <path d="M8 15l2 2 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" opacity=".3"/>
                        </svg>
                        <input type="date" name="arrival_date" value="{{ old('arrival_date') }}" class="form-input">
                    </div>
                </div>
            </div>

            <div class="section-divider fade-up s5">
                <span>Detail</span>
            </div>

            <div class="form-row fade-up s6" style="margin-bottom:16px">
                <div>
                    <label class="form-label">Maks. Titipan *</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="3" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.4" fill="url(#gGoldFill)" opacity=".6"/>
                            <rect x="14" y="3" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.4" fill="none" opacity=".4"/>
                            <rect x="3" y="14" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.4" fill="none" opacity=".4"/>
                            <rect x="14" y="14" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.4" fill="url(#gGoldFill)" opacity=".5"/>
                            <circle cx="6.5" cy="6.5" r="1" fill="currentColor" opacity=".5"/>
                            <circle cx="17.5" cy="17.5" r="1" fill="currentColor" opacity=".4"/>
                        </svg>
                        <input type="number" name="max_requests" value="{{ old('max_requests', 3) }}" required min="1" max="10" class="form-input">
                    </div>
                    @error('max_requests')
                        <p class="form-error">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <label class="form-label" style="color:rgba(255,255,255,.16)">Transportasi</label>
                    <select name="transport_mode" class="form-select no-icon" style="margin-top:0">
                        <option value="">Pilih transportasi...</option>
                        <option value="pesawat" {{ old('transport_mode') === 'pesawat' ? 'selected' : '' }}>✈️  Pesawat</option>
                        <option value="kereta" {{ old('transport_mode') === 'kereta' ? 'selected' : '' }}>🚂  Kereta</option>
                        <option value="mobil" {{ old('transport_mode') === 'mobil' ? 'selected' : '' }}>🚗  Mobil</option>
                        <option value="bus" {{ old('transport_mode') === 'bus' ? 'selected' : '' }}>🚌  Bus</option>
                    </select>
                </div>
            </div>

            <div class="fade-up s7" style="margin-bottom:16px">
                <label class="form-label" style="color:rgba(255,255,255,.16)">Kapasitas Bagasi</label>
                <select name="baggage_capacity" class="form-select no-icon">
                    <option value="">Pilih kapasitas...</option>
                    <option value="kecil" {{ old('baggage_capacity') === 'kecil' ? 'selected' : '' }}>🎒  Kecil — Tas ransel</option>
                    <option value="sedang" {{ old('baggage_capacity') === 'sedang' ? 'selected' : '' }}>🧳  Sedang — Koper kecil</option>
                    <option value="besar" {{ old('baggage_capacity') === 'besar' ? 'selected' : '' }}>📦  Besar — Koper besar</option>
                </select>
            </div>

            <div class="fade-up s8" style="margin-bottom:24px" x-data="{ remaining: 500 }">
                <label class="form-label" style="color:rgba(255,255,255,.16)">Catatan Tambahan</label>
                <textarea name="notes" rows="3" maxlength="500"
                    placeholder="Jenis barang yang bisa dititip, batasan, info lainnya..."
                    class="form-textarea no-icon"
                    @input="remaining = 500 - $el.value.length">{{ old('notes') }}</textarea>
                <div class="char-count" :class="remaining <= 0 ? 'full' : (remaining <= 50 ? 'near' : '')" x-text="remaining + ' karakter tersisa'"></div>
            </div>

            <div class="btn-row fade-up s9" style="display:flex;gap:12px;margin-bottom:20px">
                <a href="{{ route('trips.index') }}" class="btn-ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="btn-gold" id="submitBtn">
                    <span id="btnDefault" style="display:flex;align-items:center;justify-content:center;gap:8px;position:relative;z-index:1">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="url(#gGoldFill)" opacity=".3"/>
                        </svg>
                        <span>Posting Perjalanan</span>
                        <svg id="btnArrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="transition:transform .3s"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                    <span id="btnLoading" style="display:none;align-items:center;justify-content:center;gap:8px;position:relative;z-index:1">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" style="animation:spin .8s linear infinite"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span>Memposting...</span>
                    </span>
                </button>
            </div>

            <div class="fade-up s10" style="text-align:center;display:none" id="desktopBack">
                <a href="{{ route('trips.index') }}" class="link-back">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Perjalanan
                </a>
            </div>

            <div class="fade-up s11" style="display:flex;justify-content:center;gap:5px;margin-top:16px">
                <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.15);animation:dotBreath 3s ease-in-out infinite"></div>
                <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.15);animation:dotBreath 3s ease-in-out infinite;animation-delay:.5s"></div>
                <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.15);animation:dotBreath 3s ease-in-out infinite;animation-delay:1s"></div>
            </div>
        </form>
    </div>
</div>

<style>@media(min-width:1024px){#desktopBack{display:block!important}}</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const isDesktop = window.innerWidth >= 1024;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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

    if (isDesktop) {
        const card = document.getElementById('glassCard');
        if (card) {
            card.addEventListener('mouseenter', () => {
                card.classList.add('hover-lift');
            });
            card.addEventListener('mouseleave', () => {
                card.classList.remove('hover-lift');
            });
        }
    }
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.addEventListener('click', function(e) {
            const rect = btn.getBoundingClientRect();
            const cx = e.clientX ?? rect.left + rect.width / 2;
            const cy = e.clientY ?? rect.top + rect.height / 2;
            const span = document.createElement('span');
            span.className = 'ripple-fx';
            const sz = Math.max(rect.width, rect.height) * 1.2;
            span.style.cssText = `width:${sz}px;height:${sz}px;left:${cx - rect.left - sz/2}px;top:${cy - rect.top - sz/2}px`;
            btn.appendChild(span);
            setTimeout(() => span.remove(), 600);
        });
        if (isDesktop) {
            const arrow = document.getElementById('btnArrow');
            if (arrow) {
                btn.addEventListener('mouseenter', () => arrow.style.transform = 'translateX(3px)');
                btn.addEventListener('mouseleave', () => arrow.style.transform = 'translateX(0)');
            }
        }
    }

    const form = document.getElementById('tripForm');
    const btnDefault = document.getElementById('btnDefault');
    const btnLoading = document.getElementById('btnLoading');
    if (form) {
        form.addEventListener('submit', () => {
            btnDefault.style.display = 'none';
            btnLoading.style.display = 'flex';
            btn.disabled = true;
            btn.style.opacity = '.7';
            btn.style.cursor = 'wait';
            btn.style.transform = 'none';
        });
    }

    document.querySelectorAll('.form-input, .form-textarea, .form-select').forEach(input => {
        let timer;
        input.addEventListener('input', () => {
            const f = input.closest('.form-field');
            if (f) f.style.borderColor = 'rgba(201,162,39,.1)';
            clearTimeout(timer);
            timer = setTimeout(() => {
                if (document.activeElement !== input && f) f.style.borderColor = 'rgba(255,255,255,.055)';
            }, 800);
        });
    });
});
</script>
@endsection