@extends('layouts.app')

@section('title', 'Buat Permintaan Titip')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="preload" as="image" href="https://i.pinimg.com/736x/a8/52/22/a8522254f76c12589682d95ca041796c.jpg">
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
textarea:-webkit-autofill{-webkit-text-fill-color:rgba(255,255,255,.9)!important;-webkit-box-shadow:0 0 0 1000px rgba(10,10,20,.95) inset!important;transition:background-color 5000s ease-in-out 0s}

.bg-fallback{position:fixed;inset:0;z-index:-1;background:#08080f}
.bg-photo{position:fixed;inset:0;z-index:0;background:url('https://i.pinimg.com/736x/a8/52/22/a8522254f76c12589682d95ca041796c.jpg') center/cover no-repeat;opacity:0;transition:opacity 1.8s ease;transform:scale(1.03)}
.bg-photo.loaded{opacity:1}
.overlay-left{position:fixed;left:0;top:0;width:50%;height:100%;z-index:1;background:linear-gradient(155deg,rgba(8,8,15,.93) 0%,rgba(8,8,15,.78) 55%,rgba(8,8,15,.65) 100%);pointer-events:none}
.overlay-right{position:fixed;right:0;top:0;width:50%;height:100%;z-index:1;background:rgba(8,8,15,.22);pointer-events:none}
.overlay-mobile{display:none;position:fixed;inset:0;z-index:1;background:linear-gradient(180deg,rgba(8,8,15,.18) 0%,rgba(8,8,15,.4) 35%,rgba(8,8,15,.9) 100%);pointer-events:none}
.overlay-grid{position:fixed;inset:0;z-index:1;pointer-events:none;background-image:linear-gradient(rgba(201,162,39,.012) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.012) 1px,transparent 1px);background-size:60px 60px;mask-image:radial-gradient(ellipse at 50% 30%,black 5%,transparent 50%);-webkit-mask-image:radial-gradient(ellipse at 50% 30%,black 5%,transparent 50%)}

@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes shimmerBtn{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
@keyframes dotBreath{0%,100%{opacity:.12}50%{opacity:.4}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
@keyframes iconPulse{0%,100%{filter:drop-shadow(0 0 6px rgba(201,162,39,.15))}50%{filter:drop-shadow(0 0 14px rgba(201,162,39,.3))}}
@keyframes spinSlow{to{transform:rotate(360deg)}}
.fade-up{opacity:0;animation:fadeUp .65s cubic-bezier(.23,1,.32,1) forwards}
.s1{animation-delay:.05s}.s2{animation-delay:.1s}.s3{animation-delay:.16s}
.s4{animation-delay:.22s}.s5{animation-delay:.28s}.s6{animation-delay:.34s}
.s7{animation-delay:.4s}.s8{animation-delay:.46s}.s9{animation-delay:.52s}
.s10{animation-delay:.58s}.s11{animation-delay:.64s}.s12{animation-delay:.7s}
.s13{animation-delay:.76s}.s14{animation-delay:.82s}.s15{animation-delay:.88s}

.glass-card{width:100%;
    max-width:540px;
    margin:0 auto;
    background:rgba(10,10,22,.88);
    backdrop-filter:blur(40px) saturate(1.4);
    -webkit-backdrop-filter:blur(40px) saturate(1.4);
    border:1px solid rgba(255,255,255,.06);
    border-radius:24px;padding:36px 34px;
    box-shadow:0 8px 60px rgba(0,0,0,.3),inset 0 1px 0 rgba(255,255,255,.04);
    transition:box-shadow .3s ease-out, border-color .3s ease-out;
}
.glass-card.hover-lift{
    box-shadow:0 20px 80px rgba(0,0,0,.5),0 0 0 1px rgba(201,162,39,.04),inset 0 1px 0 rgba(255,255,255,.05);
    border-color:rgba(201,162,39,.1);
}

.form-field{position:relative}
.form-field::after{content:'';position:absolute;bottom:0;left:50%;width:0;height:2px;background:linear-gradient(90deg,transparent,#c9a227,transparent);transition:all .4s cubic-bezier(.23,1,.32,1);transform:translateX(-50%);border-radius:1px}
.form-field:focus-within::after{width:90%}
.form-input,.form-textarea{width:100%;padding:13px 16px 13px 46px;background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.055);border-radius:13px;color:rgba(255,255,255,.9);font-size:14px;font-family:'Inter',sans-serif;font-weight:400;outline:none;transition:all .3s}
.form-input::placeholder,.form-textarea::placeholder{color:rgba(255,255,255,.12)}
.form-input:focus,.form-textarea:focus{background:rgba(255,255,255,.055);border-color:rgba(201,162,39,.18)}
.form-field:focus-within .field-icon{color:#c9a227}
.form-field:focus-within .form-label{color:rgba(201,162,39,.55)}
.field-icon{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.12);transition:all .3s;pointer-events:none}
.form-field:focus-within .field-icon{transform:translateY(-50%) scale(1.1)}
.form-input.no-icon,.form-textarea.no-icon{padding-left:16px}
.form-textarea{resize:vertical;min-height:76px}
.form-label{display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.24);margin-bottom:7px;transition:color .3s}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form-error{font-size:11.5px;color:rgba(252,165,165,.8);margin-top:5px;margin-left:2px;display:flex;align-items:center;gap:4px}

input[type="date"]::-webkit-calendar-picker-indicator{filter:invert(.6);cursor:pointer;opacity:.5;transition:opacity .3s}
input[type="date"]:focus::-webkit-calendar-picker-indicator{opacity:.8}
input[type="date"]{color-scheme:dark}

.char-count{text-align:right;font-size:11px;color:rgba(255,255,255,.12);margin-top:4px;transition:color .3s}
.char-count.near{color:rgba(234,179,8,.5)}
.char-count.full{color:rgba(252,165,165,.7)}

/* ═══ CUSTOM DROPDOWN — no overflow clipping ═══ */
.dd-wrap{position:relative}
.dd-trigger{width:100%;padding:13px 44px 13px 46px;background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.055);border-radius:13px;color:rgba(255,255,255,.9);font-size:14px;font-family:'Inter',sans-serif;font-weight:400;cursor:pointer;outline:none;transition:all .3s;display:flex;align-items:center;gap:10px;-webkit-user-select:none;user-select:none;position:relative;z-index:1}
.dd-trigger::placeholder{color:rgba(255,255,255,.12)}
.dd-trigger.has-val{color:rgba(255,255,255,.85)}
.dd-trigger:focus,.dd-trigger.open{background:rgba(255,255,255,.055);border-color:rgba(201,162,39,.18)}
.dd-trigger.open{border-color:rgba(201,162,39,.22)}
.dd-trigger .dd-chevron{position:absolute;right:14px;top:50%;transform:translateY(-50%);transition:transform .3s cubic-bezier(.23,1,.32,1);pointer-events:none}
.dd-trigger.open .dd-chevron{transform:translateY(-50%) rotate(180deg)}
.dd-trigger .dd-field-icon{position:absolute;left:15px;top:50%;transform:translateY(-50%);pointer-events:none;transition:all .3s}
.dd-trigger:focus .dd-field-icon,.dd-trigger.open .dd-field-icon{color:#c9a227;transform:translateY(-50%) scale(1.1)}

/* Panel — positioned outside overflow context */
./* Panel — positioned absolute relatif terhadap card */
.dd-panel{
    position:absolute;
    z-index:9999;
    background:rgba(14,14,28,.96);
    backdrop-filter:blur(28px) saturate(1.4);
    -webkit-backdrop-filter:blur(28px) saturate(1.4);
    border:1px solid rgba(255,255,255,.08);
    border-radius:16px;
    box-shadow:0 20px 60px rgba(0,0,0,.5),0 0 0 1px rgba(201,162,39,.05);
    padding:6px;
    max-height:280px;
    overflow-y:auto;
}
.dd-panel::-webkit-scrollbar{width:3px}
.dd-panel::-webkit-scrollbar-track{background:transparent}
.dd-panel::-webkit-scrollbar-thumb{background:rgba(201,162,39,.15);border-radius:2px}
@keyframes ddFadeIn{from{opacity:0;transform:translateY(-6px) scale(.98)}to{opacity:1;transform:translateY(0) scale(1)}}

.dd-option{display:flex;align-items:center;gap:12px;padding:11px 14px;border-radius:11px;cursor:pointer;transition:all .2s;border:1px solid transparent}
.dd-option:hover{background:rgba(201,162,39,.06);border-color:rgba(201,162,39,.08)}
.dd-option.selected{background:rgba(201,162,39,.08);border-color:rgba(201,162,39,.12)}
.dd-option .dd-opt-icon{width:36px;height:36px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);transition:all .25s}
.dd-option:hover .dd-opt-icon{background:rgba(201,162,39,.06);border-color:rgba(201,162,39,.1)}
.dd-option.selected .dd-opt-icon{background:rgba(201,162,39,.1);border-color:rgba(201,162,39,.15)}
.dd-option .dd-opt-label{font-size:13.5px;font-weight:600;color:rgba(255,255,255,.75);transition:color .2s}
.dd-option:hover .dd-opt-label{color:rgba(255,255,255,.9)}
.dd-option.selected .dd-opt-label{color:#c9a227}
.dd-option .dd-opt-desc{font-size:11px;color:rgba(255,255,255,.2);margin-top:1px}
.dd-option .dd-opt-check{width:18px;height:18px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:rgba(201,162,39,.08);border:1px solid rgba(201,162,39,.15);opacity:0;transform:scale(.5);transition:all .25s cubic-bezier(.34,1.56,.64,1)}
.dd-option.selected .dd-opt-check{opacity:1;transform:scale(1)}

/* File upload */
.file-zone{position:relative;border:1.5px dashed rgba(255,255,255,.08);border-radius:14px;padding:24px 16px;text-align:center;cursor:pointer;transition:all .3s;background:rgba(255,255,255,.01)}
.file-zone:hover{border-color:rgba(201,162,39,.18);background:rgba(201,162,39,.02)}
.file-zone.has-file{border-color:rgba(201,162,39,.15);border-style:solid;background:rgba(201,162,39,.03)}
.file-zone input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer}
.file-zone-icon{margin:0 auto 10px;width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);display:flex;align-items:center;justify-content:center;transition:all .3s}
.file-zone:hover .file-zone-icon{border-color:rgba(201,162,39,.12);background:rgba(201,162,39,.05)}
.file-zone.has-file .file-zone-icon{border-color:rgba(201,162,39,.15);background:rgba(201,162,39,.08)}
.file-zone-text{font-size:13px;color:rgba(255,255,255,.25);font-weight:400}
.file-zone-hint{font-size:11px;color:rgba(255,255,255,.12);margin-top:4px}
.file-zone-name{font-size:12.5px;color:rgba(201,162,39,.7);font-weight:600;margin-top:8px;display:none;word-break:break-all}
.file-zone.has-file .file-zone-name{display:block}
.file-zone.has-file .file-zone-text{display:none}

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

.content-layer{position:relative;z-index:2;min-height:100vh;display:flex;flex-direction:column;justify-content:center;padding:40px 16px 60px}

.icon-hero{width:60px;height:60px;margin:0 auto;border-radius:18px;background:linear-gradient(145deg,rgba(201,162,39,.14),rgba(201,162,39,.04));border:1px solid rgba(201,162,39,.12);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;animation:float 5s ease-in-out infinite,iconPulse 4s ease-in-out infinite}
.icon-hero::before{content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:conic-gradient(from 0deg,transparent,rgba(201,162,39,.08),transparent,rgba(201,162,39,.04),transparent);animation:spinSlow 8s linear infinite}
.icon-hero svg{position:relative;z-index:1}

/* ═══ MOBILE — NO overflow constraint on card ═══ */
@media(max-width:1023px){
    .overlay-left,.overlay-right{display:none}
    .overlay-mobile{display:block}
    .content-layer{justify-content:flex-end;padding:0 0 env(safe-area-inset-bottom,0)}
    .glass-card{
        max-width:100%;
        border-radius:24px 24px 0 0;
        padding:28px 22px 40px;
        box-shadow:0 -8px 60px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.04);
        background:rgba(10,10,22,.92);  /* ← Naikkan opacity dari .82 ke .92 */
backdrop-filter:blur(45px) saturate(1.5);  /* ← Blur lebih pekat untuk mobile */
-webkit-backdrop-filter:blur(45px) saturate(1.5);
    }
    .mobile-top{display:flex!important}
}
@media(min-width:1024px){.mobile-top{display:none!important}}
@media(max-width:480px){
    .form-row{grid-template-columns:1fr}
    .form-input,.form-textarea{padding-left:40px;font-size:13px}
    .field-icon{left:13px}
    .dd-trigger{padding-left:40px;font-size:13px}
    .dd-trigger .dd-field-icon{left:13px}
    .glass-card{padding:24px 18px 36px}
    .btn-row{flex-direction:column}
    .dd-panel{max-width:calc(100vw - 40px)}
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
        <linearGradient id="gG" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#e0c05a"/><stop offset="50%" stop-color="#c9a227"/><stop offset="100%" stop-color="#a88520"/></linearGradient>
        <linearGradient id="gGs" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="rgba(224,192,90,.7)"/><stop offset="100%" stop-color="rgba(168,133,32,.7)"/></linearGradient>
        <linearGradient id="gGf" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" stop-color="rgba(201,162,39,.12)"/><stop offset="100%" stop-color="rgba(201,162,39,.03)"/></linearGradient>
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
            <a href="{{ route('requests.index') }}" class="link-back">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <div class="fade-up s1" style="text-align:center;margin-bottom:8px">
            <div class="icon-hero">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4H6z" fill="url(#gGf)" stroke="url(#gG)" stroke-width="1.2" stroke-linejoin="round"/>
                    <path d="M3 6h18" stroke="url(#gG)" stroke-width="1.3"/>
                    <path d="M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2" stroke="url(#gG)" stroke-width="1.2" fill="none"/>
                    <path d="M10 11h4M12 9v4" stroke="url(#gGs)" stroke-width="1.5" stroke-linecap="round" opacity=".5"/>
                </svg>
            </div>
        </div>

        <div class="fade-up s2" style="text-align:center;margin-bottom:4px">
            <h1 style="font-size:24px;font-weight:800;color:#f0f0f5;letter-spacing:-.02em">Buat Permintaan Titip</h1>
        </div>
        <div class="fade-up s3" style="text-align:center;margin-bottom:24px">
            <p style="font-size:13.5px;color:rgba(255,255,255,.24);font-weight:300">Deskripsikan barang yang ingin kamu titip beli</p>
        </div>

        <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data" id="reqForm">
            @csrf

            <!-- Item Name -->
            <div class="fade-up s4" style="margin-bottom:16px">
                <label class="form-label">Nama Barang *</label>
                <div class="form-field">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke="currentColor" stroke-width="1.4" opacity=".5"/>
                        <path d="M14 10h.01" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" opacity=".6"/>
                    </svg>
                    <input type="text" name="item_name" value="{{ old('item_name') }}" required placeholder="Bolen Meranti, Baju Batik, Laptop..." class="form-input">
                </div>
                @error('item_name')
                    <p class="form-error"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="fade-up s5" style="margin-bottom:16px" x-data="{ remaining: 1000 }">
                <label class="form-label" style="color:rgba(255,255,255,.16)">Deskripsi</label>
                <textarea name="description" rows="3" maxlength="1000" placeholder="Merek, ukuran, warna, link referensi, toko tempat beli..." class="form-textarea no-icon" @input="remaining = 1000 - $el.value.length">{{ old('description') }}</textarea>
                <div class="char-count" :class="remaining <= 0 ? 'full' : (remaining <= 100 ? 'near' : '')" x-text="remaining + ' karakter tersisa'"></div>
            </div>

            <div class="section-divider fade-up s5"><span>Detail</span></div>

            <!-- Category Dropdown -->
            <div class="fade-up s6" style="margin-bottom:16px"
                 x-data="{
                     open: false,
                     value: '{{ old('category') }}',
                     panelStyle: '',
                     options: [
                         { v: 'makanan',    l: 'Makanan',    d: 'Oleh-oleh, camilan, frozen' },
                         { v: 'elektronik', l: 'Elektronik', d: 'Gadget, aksesoris, komponen' },
                         { v: 'fashion',    l: 'Fashion',    d: 'Pakaian, sepatu, tas' },
                         { v: 'dokumen',   l: 'Dokumen',   d: 'Surat, sertifikat, dokumen' },
                         { v: 'lainnya',   l: 'Lainnya',   d: 'Kebutuhan khusus lainnya' }
                     ],
                     get selected() { return this.options.find(o => o.v === this.value) },
                     get display() { return this.selected ? this.selected.l : '' },
                     pick(o) { this.value = o.v; this.open = false },
                   positionPanel() {
    const trigger = this.$el.querySelector('.dd-trigger');
    const panel = this.$el.querySelector('.dd-panel');
    if (!trigger || !panel) return;
    const card = document.getElementById('glassCard');
    if (!card) return;
    const cardRect = card.getBoundingClientRect();
    const r = trigger.getBoundingClientRect();
    panel.style.top = (r.bottom - cardRect.top + 6) + 'px';
    panel.style.left = (r.left - cardRect.left) + 'px';
    panel.style.width = r.width + 'px';
}
                 }"
                 x-init="$watch('open', v => { if (v) $nextTick(() => positionPanel()) })"
                 @click.away="open = false">
                <label class="form-label" style="color:rgba(255,255,255,.16)">Kategori</label>
                <input type="hidden" name="category" :value="value">
                <div class="dd-wrap">
                    <div class="dd-trigger" :class="display ? 'has-val' : ''" @click="open = !open; $nextTick(() => positionPanel())" @keydown.escape="open = false" @keydown.enter="open = !open; $nextTick(() => positionPanel())">
                        <svg class="dd-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" style="color:rgba(255,255,255,.12)">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3" fill="url(#gGf)" opacity=".5"/>
                            <rect x="14" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3" opacity=".3"/>
                            <rect x="3" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3" opacity=".3"/>
                            <rect x="14" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3" fill="url(#gGf)" opacity=".4"/>
                        </svg>
                        <span x-show="!display" style="color:rgba(255,255,255,.12);pointer-events:none">Pilih kategori...</span>
                        <span x-show="display" x-text="display"></span>
                        <svg class="dd-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.2)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                    </div>
                    <div class="dd-panel" x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.stop>
                        <template x-for="o in options" :key="o.v">
                            <div class="dd-option" :class="value === o.v ? 'selected' : ''" @click="pick(o)">
                                <div class="dd-opt-icon">
                                    <svg x-show="o.v === 'makanan'" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 14h18v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4z" stroke="url(#gGs)" stroke-width="1.4" fill="url(#gGf)" opacity=".6"/><path d="M7 14V8a5 5 0 0110 0v6" stroke="url(#gGs)" stroke-width="1.2" opacity=".4"/><path d="M9 4h6" stroke="url(#gGs)" stroke-width="1" stroke-linecap="round" opacity=".3"/></svg>
                                    <svg x-show="o.v === 'elektronik'" width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="5" y="2" width="14" height="20" rx="3" stroke="url(#gGs)" stroke-width="1.4" fill="url(#gGf)" opacity=".6"/><path d="M5 18h14" stroke="url(#gGs)" stroke-width="1" opacity=".35"/><circle cx="12" cy="20" r=".8" fill="url(#gGs)" opacity=".5"/><rect x="8" y="5" width="8" height="10" rx="1" fill="none" stroke="url(#gGs)" stroke-width="1" opacity=".4"/></svg>
                                    <svg x-show="o.v === 'fashion'" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M6 2v6l6 4 6-4V2" stroke="url(#gGs)" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" fill="url(#gGf)" opacity=".5"/><path d="M4 8h16v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8z" stroke="url(#gGs)" stroke-width="1.4" fill="url(#gGf)" opacity=".4"/><path d="M4 13h16" stroke="url(#gGs)" stroke-width="1" opacity=".3"/><path d="M10 2v3M14 2v3" stroke="url(#gGs)" stroke-width="1.2" stroke-linecap="round" opacity=".3"/></svg>
                                    <svg x-show="o.v === 'dokumen'" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="url(#gGs)" stroke-width="1.4" fill="url(#gGf)" opacity=".5"/><path d="M14 2v6h6" stroke="url(#gGs)" stroke-width="1.2" opacity=".4"/><path d="M8 13h8M8 17h5" stroke="url(#gGs)" stroke-width="1.2" stroke-linecap="round" opacity=".35"/></svg>
                                    <svg x-show="o.v === 'lainnya'" width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="2" y="6" width="20" height="14" rx="2" stroke="url(#gGs)" stroke-width="1.4" fill="url(#gGf)" opacity=".5"/><path d="M9 6V4.5A2.5 2.5 0 0111.5 2h1A2.5 2.5 0 0115 4.5V6" stroke="url(#gGs)" stroke-width="1.3" opacity=".5"/><path d="M2 12h20" stroke="url(#gGs)" stroke-width="1" opacity=".3"/></svg>
                                </div>
                                <div style="flex:1"><div class="dd-opt-label" x-text="o.l"></div><div class="dd-opt-desc" x-text="o.d"></div></div>
                                <div class="dd-opt-check"><svg width="10" height="10" fill="none" stroke="#c9a227" viewBox="0 0 24 24" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Cities -->
            <div class="form-row fade-up s7" style="margin-bottom:16px">
                <div>
                    <label class="form-label">Kota Asal *</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="currentColor" stroke-width="1.5" fill="url(#gGf)" opacity=".6"/><circle cx="12" cy="9" r="2.5" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M9.5 9l1.5 1.5L14.5 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity=".5"/></svg>
                        <input type="text" name="origin_city" value="{{ old('origin_city') }}" required placeholder="Jakarta" class="form-input">
                    </div>
                    @error('origin_city')<p class="form-error"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Kota Tujuan *</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" fill="url(#gGf)" opacity=".5"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.3" fill="none"/><circle cx="12" cy="12" r="1" fill="currentColor" opacity=".6"/><path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width=".8" stroke-linecap="round" opacity=".3"/></svg>
                        <input type="text" name="destination_city" value="{{ old('destination_city') }}" required placeholder="Surabaya" class="form-input">
                    </div>
                    @error('destination_city')<p class="form-error"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Route -->
            <div class="fade-up s7" style="display:flex;align-items:center;justify-content:center;margin:-8px 0 16px">
                <div style="display:flex;align-items:center;gap:10px;padding:5px 16px;border-radius:20px;background:rgba(201,162,39,.04);border:1px solid rgba(201,162,39,.06)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14" stroke="rgba(201,162,39,.35)" stroke-width="1.5" stroke-linecap="round"/><path d="M15 8l4 4-4 4" stroke="rgba(201,162,39,.35)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span style="font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(201,162,39,.3)">Rute</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M19 12H5" stroke="rgba(201,162,39,.35)" stroke-width="1.5" stroke-linecap="round"/><path d="M9 8l-4 4 4 4" stroke="rgba(201,162,39,.35)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            </div>

            <div class="section-divider fade-up s8"><span>Budget & Waktu</span></div>

            <!-- Budget -->
            <div class="form-row fade-up s9" style="margin-bottom:16px">
                <div>
                    <label class="form-label" style="color:rgba(255,255,255,.16)">Budget Min</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.4" fill="url(#gGf)" opacity=".5"/><path d="M15 9l-5 3.5L8 11" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" opacity=".5"/><path d="M9 9h6" stroke="currentColor" stroke-width="1" stroke-linecap="round" opacity=".3"/></svg>
                        <input type="number" name="budget_min" value="{{ old('budget_min') }}" min="0" placeholder="50000" class="form-input">
                    </div>
                </div>
                <div>
                    <label class="form-label" style="color:rgba(255,255,255,.16)">Budget Max</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.4" fill="url(#gGf)" opacity=".5"/><path d="M9 15l5-3.5L16 13" stroke="url(#gGs)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" opacity=".6"/><path d="M9 15h6" stroke="url(#gGs)" stroke-width="1" stroke-linecap="round" opacity=".4"/></svg>
                        <input type="number" name="budget_max" value="{{ old('budget_max') }}" min="0" placeholder="100000" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Deadline & Weight -->
            <div class="form-row fade-up s10" style="margin-bottom:16px">
                <div>
                    <label class="form-label" style="color:rgba(255,255,255,.16)">Deadline</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.4" fill="url(#gGf)" opacity=".5"/><path d="M3 9h18" stroke="currentColor" stroke-width="1.4"/><path d="M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><rect x="14" y="12" width="3" height="3" rx=".5" fill="url(#gGs)" opacity=".7"/></svg>
                        <input type="date" name="deadline" value="{{ old('deadline') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-input">
                    </div>
                </div>
                <div>
                    <label class="form-label" style="color:rgba(255,255,255,.16)">Estimasi Berat</label>
                    <div x-data="{
                        open: false, value: '{{ old('weight_estimate') }}',
                        options: [
                            { v: 'ringan', l: 'Ringan', d: '< 1 kg' },
                            { v: 'sedang', l: 'Sedang', d: '1 – 5 kg' },
                            { v: 'berat',  l: 'Berat',  d: '> 5 kg' }
                        ],
                        get selected() { return this.options.find(o => o.v === this.value) },
                        get display() { return this.selected ? this.selected.l : '' },
                        pick(o) { this.value = o.v; this.open = false },
                                               positionPanel() {
                            const trigger = this.$el.querySelector('.dd-trigger');
                            const panel = this.$el.querySelector('.dd-panel');
                            if (!trigger || !panel) return;
                            const card = document.getElementById('glassCard');
                            if (!card) return;
                            const cardRect = card.getBoundingClientRect();
                            const r = trigger.getBoundingClientRect();
                            panel.style.top = (r.bottom - cardRect.top + 6) + 'px';
                            panel.style.left = (r.left - cardRect.left) + 'px';
                            panel.style.width = r.width + 'px';
                        }
                    }"
                    x-init="$watch('open', v => { if (v) $nextTick(() => positionPanel()) })"
                    @click.away="open = false">
                        <input type="hidden" name="weight_estimate" :value="value">
                        <div class="dd-wrap">
                            <div class="dd-trigger" :class="display ? 'has-val' : ''" @click="open = !open; $nextTick(() => positionPanel())" @keydown.escape="open = false" @keydown.enter="open = !open; $nextTick(() => positionPanel())">
                                <svg class="dd-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" style="color:rgba(255,255,255,.12)"><path d="M12 3v7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity=".4"/><path d="M12 10l-5 8h10l-5-8z" stroke="currentColor" stroke-width="1.4" fill="url(#gGf)" opacity=".5" stroke-linejoin="round"/><path d="M9 18h6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity=".35"/><circle cx="12" cy="14" r="1" fill="currentColor" opacity=".4"/></svg>
                                <span x-show="!display" style="color:rgba(255,255,255,.12);pointer-events:none">Pilih...</span>
                                <span x-show="display" x-text="display"></span>
                                <svg class="dd-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.2)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                            </div>
                            <div class="dd-panel" x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.stop>
                                <template x-for="o in options" :key="o.v">
                                    <div class="dd-option" :class="value === o.v ? 'selected' : ''" @click="pick(o)">
                                        <div class="dd-opt-icon">
                                            <svg x-show="o.v === 'ringan'" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 3C10.34 3 9 4.34 9 6c0 3.5 3 8 3 8s3-4.5 3-8c0-1.66-1.34-3-3-3z" stroke="url(#gGs)" stroke-width="1.4" fill="url(#gGf)" opacity=".6"/><path d="M10 6.5a2 2 0 004 0" stroke="url(#gGs)" stroke-width="1" opacity=".4"/></svg>
                                            <svg x-show="o.v === 'sedang'" width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="4" y="8" width="16" height="12" rx="2" stroke="url(#gGs)" stroke-width="1.4" fill="url(#gGf)" opacity=".6"/><path d="M8 8V6a4 4 0 018 0v2" stroke="url(#gGs)" stroke-width="1.3" opacity=".5"/><path d="M4 14h16" stroke="url(#gGs)" stroke-width="1" opacity=".3"/></svg>
                                            <svg x-show="o.v === 'berat'" width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="14" rx="2" stroke="url(#gGs)" stroke-width="1.4" fill="url(#gGf)" opacity=".6"/><path d="M8 6V4.5A2.5 2.5 0 0110.5 2h3A2.5 2.5 0 0116 4.5V6" stroke="url(#gGs)" stroke-width="1.3" opacity=".5"/><path d="M3 12h18" stroke="url(#gGs)" stroke-width="1" opacity=".3"/></svg>
                                        </div>
                                        <div style="flex:1"><div class="dd-opt-label" x-text="o.l"></div><div class="dd-opt-desc" x-text="o.d"></div></div>
                                        <div class="dd-opt-check"><svg width="10" height="10" fill="none" stroke="#c9a227" viewBox="0 0 24 24" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider fade-up s11"><span>Foto</span></div>

            <!-- File Upload -->
            <div class="fade-up s12" style="margin-bottom:24px" x-data="{ fileName: '' }">
                <div class="file-zone" :class="fileName ? 'has-file' : ''">
                    <input type="file" name="image" accept="image/*" @change="fileName = $el.files[0]?.name || ''">
                    <div class="file-zone-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.4" fill="none" opacity=".5"/><circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.2" fill="url(#gGf)" opacity=".5"/><path d="M21 15l-5-5L5 21" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" opacity=".4"/><path d="M12 12h.01" stroke="url(#gGs)" stroke-width="2.5" stroke-linecap="round" opacity=".5"/><path d="M8 12h8" stroke="url(#gGs)" stroke-width="1.5" stroke-linecap="round" opacity=".3"/></svg>
                    </div>
                    <div class="file-zone-text" x-show="!fileName">Klik atau seret foto barang di sini</div>
                    <div class="file-zone-name" x-show="fileName" x-text="fileName"></div>
                    <div class="file-zone-hint">JPG, PNG, max 5MB — opsional</div>
                    @error('image')<p class="form-error" style="justify-content:center;margin-top:8px"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="btn-row fade-up s13" style="display:flex;gap:12px;margin-bottom:20px">
                <a href="{{ route('requests.index') }}" class="btn-ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    Batal
                </a>
                <button type="submit" class="btn-gold" id="submitBtn">
                    <span id="btnDefault" style="display:flex;align-items:center;justify-content:center;gap:8px;position:relative;z-index:1">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4H6z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="url(#gGf)" opacity=".25"/><path d="M10 11h4M12 9v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Buat Permintaan</span>
                        <svg id="btnArrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="transition:transform .3s"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                    <span id="btnLoading" style="display:none;align-items:center;justify-content:center;gap:8px;position:relative;z-index:1">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" style="animation:spin .8s linear infinite"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span>Mengirim...</span>
                    </span>
                </button>
            </div>

            <div class="fade-up s14" style="text-align:center;display:none" id="desktopBack">
                <a href="{{ route('requests.index') }}" class="link-back"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>Kembali ke Daftar Permintaan</a>
            </div>

            <div class="fade-up s15" style="display:flex;justify-content:center;gap:5px;margin-top:16px">
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

    /* Reposition all fixed dropdowns on scroll/resize */
    function repositionDropdowns() {
    const card = document.getElementById('glassCard');
    if (!card) return;
    const cardRect = card.getBoundingClientRect();
    document.querySelectorAll('.dd-panel').forEach(panel => {
        if (panel.offsetParent === null) return; // skip jika dropdown sedang ditutup
        const wrap = panel.closest('.dd-wrap');
        if (!wrap) return;
        const trigger = wrap.querySelector('.dd-trigger');
        if (!trigger) return;
        const r = trigger.getBoundingClientRect();
        panel.style.top = (r.bottom - cardRect.top + 6) + 'px';
        panel.style.left = (r.left - cardRect.left) + 'px';
        panel.style.width = r.width + 'px';
    });
}
    window.addEventListener('scroll', repositionDropdowns, { passive: true });
    window.addEventListener('resize', repositionDropdowns);

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

    const form = document.getElementById('reqForm');
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

    document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
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