@extends('layouts.app')

@section('title', $titipRequest->item_name)

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="preload" as="image" href="https://i1-c.pinimg.com/1200x/5e/bf/71/5ebf7124e981ac4e8a9bf791223fee48.jpg">
<style>
*,*::before,*::after{box-sizing:border-box}
body{font-family:'Inter',sans-serif!important;background:#08080f!important;color:#f0f0f5!important;overflow-x:hidden}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:#08080f}
::-webkit-scrollbar-thumb{background:rgba(201,162,39,.2);border-radius:3px}
::-webkit-scrollbar-thumb:hover{background:rgba(201,162,39,.35)}
html{scrollbar-width:thin;scrollbar-color:rgba(201,162,39,.2) #08080f}

nav,header{background:rgba(8,8,15,.85)!important;backdrop-filter:blur(20px)!important;-webkit-backdrop-filter:blur(20px)!important;border-bottom:1px solid rgba(255,255,255,.04)!important}

.bg-fallback{position:fixed;inset:0;z-index:-1;background:#08080f}
.bg-photo{position:fixed;inset:0;z-index:0;background:url('https://i1-c.pinimg.com/1200x/5e/bf/71/5ebf7124e981ac4e8a9bf791223fee48.jpg') center/cover no-repeat;opacity:0;transition:opacity 1.8s ease;transform:scale(1.03)}
.bg-photo.loaded{opacity:1}
.overlay-left{position:fixed;left:0;top:0;width:50%;height:100%;z-index:1;background:linear-gradient(155deg,rgba(8,8,15,.93) 0%,rgba(8,8,15,.78) 55%,rgba(8,8,15,.65) 100%);pointer-events:none}
.overlay-right{position:fixed;right:0;top:0;width:50%;height:100%;z-index:1;background:rgba(8,8,15,.22);pointer-events:none}
.overlay-mobile{display:none;position:fixed;inset:0;z-index:1;background:linear-gradient(180deg,rgba(8,8,15,.2) 0%,rgba(8,8,15,.4) 35%,rgba(8,8,15,.88) 100%);pointer-events:none}
.overlay-grid{position:fixed;inset:0;z-index:1;pointer-events:none;background-image:linear-gradient(rgba(201,162,39,.012) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.012) 1px,transparent 1px);background-size:60px 60px;mask-image:radial-gradient(ellipse at 50% 30%,black 5%,transparent 50%);-webkit-mask-image:radial-gradient(ellipse at 50% 30%,black 5%,transparent 50%)}

@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes dotBreath{0%,100%{opacity:.12}50%{opacity:.4}}
.fade-up{opacity:0;animation:fadeUp .65s cubic-bezier(.23,1,.32,1) forwards}
.s1{animation-delay:.05s}.s2{animation-delay:.1s}.s3{animation-delay:.16s}
.s4{animation-delay:.22s}.s5{animation-delay:.28s}.s6{animation-delay:.34s}
.s7{animation-delay:.4s}.s8{animation-delay:.46s}

.glass{background:rgba(10,10,22,.88);backdrop-filter:blur(40px) saturate(1.4);-webkit-backdrop-filter:blur(40px) saturate(1.4);border:1px solid rgba(255,255,255,.06);border-radius:20px;box-shadow:0 4px 40px rgba(0,0,0,.2),inset 0 1px 0 rgba(255,255,255,.04);transition:all .4s cubic-bezier(.23,1,.32,1)}

.sec-label{display:flex;align-items:center;gap:10px;margin-bottom:20px}
.sec-label .dot{width:6px;height:6px;border-radius:50%;background:rgba(201,162,39,.4);flex-shrink:0}
.sec-label .line{flex:1;height:1px;background:linear-gradient(90deg,rgba(201,162,39,.08),transparent)}
.sec-label span{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.18em;color:rgba(201,162,39,.45);white-space:nowrap}

.route-box{display:flex;align-items:center;justify-content:center;gap:20px;padding:28px 16px;background:rgba(201,162,39,.02);border:1px solid rgba(201,162,39,.05);border-radius:16px}
.route-point{text-align:center;flex:1}
.route-point .label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.2);margin-bottom:6px}
.route-point .city{font-size:22px;font-weight:800;color:#f0f0f5;letter-spacing:-.02em}
.route-arrow{display:flex;flex-direction:column;align-items:center;gap:4px;color:rgba(201,162,39,.4)}

.detail-row{display:flex;justify-content:space-between;align-items:center;padding:12px 0}
.detail-row+.detail-row{border-top:1px solid rgba(255,255,255,.03)}
.detail-label{font-size:13.5px;color:rgba(255,255,255,.3);font-weight:400;display:flex;align-items:center;gap:8px}
.detail-label svg{opacity:.5;flex-shrink:0}
.detail-value{font-size:14px;font-weight:600;color:rgba(255,255,255,.8);text-align:right}
.detail-value.gold{color:rgba(201,162,39,.9)}

.profile-card{position:relative;overflow:hidden;border-radius:16px;padding:24px;text-align:center;background:rgba(168,85,247,.1);border:1px solid rgba(168,85,247,.14);transition:all .4s cubic-bezier(.23,1,.32,1)}
.profile-card::before{content:'';position:absolute;inset:0;pointer-events:none;background:radial-gradient(ellipse at 50% 0%,rgba(168,85,247,.08),transparent 60%)}
.profile-avatar{width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;margin:0 auto 14px;background:linear-gradient(135deg,rgba(168,85,247,.22),rgba(168,85,247,.08));border:1px solid rgba(168,85,247,.2);color:rgba(196,181,253,.9);transition:transform .4s}
.profile-name{font-size:16px;font-weight:700;color:#f0f0f5;margin-bottom:6px}
.profile-stats{display:flex;align-items:center;justify-content:center;gap:12px;font-size:13px;color:rgba(255,255,255,.3)}
.profile-stats svg{color:#c9a227;flex-shrink:0}
.profile-stats .tp{color:rgba(147,197,253,.6);font-weight:600}

.img-display{position:relative;border-radius:14px;overflow:hidden;border:1px solid rgba(255,255,255,.05);transition:all .4s}
.img-display:hover{border-color:rgba(201,162,39,.12);transform:scale(1.01)}
.img-display img{width:100%;max-width:100%;display:block;border-radius:13px;object-fit:cover}
.img-display::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,transparent 60%,rgba(8,8,15,.3));pointer-events:none;border-radius:13px}

.status-badge{display:inline-flex;align-items:center;gap:7px;padding:8px 20px;border-radius:20px;font-size:12.5px;font-weight:700;letter-spacing:.02em;backdrop-filter:blur(8px);border:1px solid}
.status-badge svg{flex-shrink:0}

.btn-ghost{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px;border:1px solid rgba(255,255,255,.07);border-radius:14px;background:rgba(255,255,255,.02);color:rgba(255,255,255,.5);font-family:'Inter',sans-serif;font-size:14px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .3s}
.btn-ghost:hover{background:rgba(255,255,255,.05);border-color:rgba(255,255,255,.1);color:rgba(255,255,255,.8)}

.btn-outline-red{width:100%;padding:13px;border:1px solid rgba(239,68,68,.12);border-radius:14px;font-family:'Inter',sans-serif;font-size:13.5px;font-weight:600;cursor:pointer;background:rgba(239,68,68,.03);color:rgba(252,165,165,.7);transition:all .3s;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none}
.btn-outline-red:hover{background:rgba(239,68,68,.06);border-color:rgba(239,68,68,.2);color:rgba(252,165,165,.9)}

.link-back{color:rgba(255,255,255,.18);text-decoration:none;font-weight:500;transition:all .3s;display:inline-flex;align-items:center;gap:6px;font-size:13px}
.link-back:hover{color:rgba(255,255,255,.4)}
.link-back svg{transition:transform .3s}
.link-back:hover svg{transform:translateX(-3px)}

@media(max-width:1023px){
    .overlay-left,.overlay-right{display:none}
    .overlay-mobile{display:block}
    .detail-grid{grid-template-columns:1fr!important}
}
@media(max-width:640px){
    .route-box{flex-direction:column;gap:12px;padding:20px 16px}
    .route-arrow{transform:rotate(90deg)}
}
@media(prefers-reduced-motion:reduce){
    .fade-up{animation:none!important;opacity:1!important}
    *{animation-duration:0s!important;transition-duration:0s!important}
}
::-webkit-scrollbar{display:none}html{scrollbar-width:none}
</style>
@endpush

@section('content')
<div class="bg-fallback"></div>
<div class="bg-photo" id="bgPhoto"></div>
<div class="overlay-left"></div>
<div class="overlay-right"></div>
<div class="overlay-mobile"></div>
<div class="overlay-grid"></div>

<div style="max-width:1000px;margin:0 auto;padding:24px 16px 60px;position:relative;z-index:2">

    <!-- Back Link -->
    <div class="fade-up s1" style="margin-bottom:24px">
        <a href="{{ route('requests.index') }}" class="link-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Permintaan
        </a>
    </div>

    @php
        $statusConfig = [
            'open'    => ['bg'=>'rgba(34,197,94,.06)','border'=>'rgba(34,197,94,.12)','text'=>'rgba(134,239,172,.85)', 'label'=>'Open'],
            'selesai' => ['bg'=>'rgba(255,255,255,.04)','border'=>'rgba(255,255,255,.08)','text'=>'rgba(255,255,255,.5)', 'label'=>'Selesai'],
        ];
        $cfg = $statusConfig[$titipRequest->status] ?? $statusConfig['open'];
        
        $weightLabels = [
            'ringan' => 'Ringan (< 1 kg)',
            'sedang' => 'Sedang (1 - 5 kg)',
            'berat'  => 'Berat (> 5 kg)'
        ];
    @endphp

    <!-- HEADER CARD -->
    <div class="glass fade-up s2" style="padding:28px 32px;margin-bottom:24px">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px">
            <div style="display:flex;align-items:center;gap:16px;flex:1;min-width:0">
                <div style="width:50px;height:50px;border-radius:14px;background:linear-gradient(145deg,rgba(201,162,39,.14),rgba(201,162,39,.04));border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="24" height="24" fill="none" stroke="#c9a227" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div style="min-width:0">
                    <h1 style="font-size:22px;font-weight:800;color:#f0f0f5;letter-spacing:-.02em;word-break:break-word">{{ $titipRequest->item_name }}</h1>
                    <div style="display:flex;align-items:center;gap:10px;margin-top:6px;flex-wrap:wrap">
                        @if($titipRequest->category)
                            <span style="font-size:11px;padding:4px 12px;border-radius:20px;background:rgba(201,162,39,.06);border:1px solid rgba(201,162,39,.1);color:rgba(201,162,39,.8);font-weight:600;text-transform:capitalize">{{ $titipRequest->category }}</span>
                        @endif
                        <span style="color:rgba(255,255,255,.15)">•</span>
                        <span style="font-size:13px;color:rgba(255,255,255,.3)">oleh <strong style="color:rgba(255,255,255,.6)">{{ $titipRequest->user->name }}</strong></span>
                    </div>
                </div>
            </div>
            <span class="status-badge" style="background:{{ $cfg['bg'] }};border-color:{{ $cfg['border'] }};color:{{ $cfg['text'] }};flex-shrink:0">
                @if($titipRequest->status === 'open')
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                @else
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
                {{ $cfg['label'] }}
            </span>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="detail-grid" style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start">

        <!-- LEFT COLUMN -->
        <div style="display:flex;flex-direction:column;gap:24px">

            <!-- Route Card -->
            <div class="glass fade-up s3" style="padding:28px">
                <div class="sec-label">
                    <div class="dot"></div>
                    <span>Rute Pengiriman</span>
                    <div class="line"></div>
                </div>
                <div class="route-box">
                    <div class="route-point">
                        <div class="label">Dari</div>
                        <div class="city">{{ $titipRequest->origin_city }}</div>
                    </div>
                    <div class="route-arrow">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        <div style="width:40px;height:1px;background:rgba(201,162,39,.15)"></div>
                    </div>
                    <div class="route-point">
                        <div class="label">Ke</div>
                        <div class="city">{{ $titipRequest->destination_city }}</div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($titipRequest->description)
            <div class="glass fade-up s4" style="padding:28px">
                <div class="sec-label">
                    <div class="dot"></div>
                    <span>Deskripsi Barang</span>
                    <div class="line"></div>
                </div>
                <p style="font-size:14.5px;color:rgba(255,255,255,.6);line-height:1.75;font-weight:400">{{ $titipRequest->description }}</p>
            </div>
            @endif

            <!-- Image -->
            @if($titipRequest->image)
            <div class="glass fade-up s5" style="padding:28px">
                <div class="sec-label">
                    <div class="dot"></div>
                    <span>Foto Barang</span>
                    <div class="line"></div>
                </div>
                <div class="img-display">
                    <img src="{{ Storage::url($titipRequest->image) }}" alt="{{ $titipRequest->item_name }}">
                </div>
            </div>
            @endif
        </div>

        <!-- RIGHT SIDEBAR -->
        <div style="display:flex;flex-direction:column;gap:24px;position:sticky;top:24px">

            <!-- Info Card -->
            <div class="glass fade-up s6" style="padding:24px">
                <div class="sec-label" style="margin-bottom:16px">
                    <div class="dot"></div>
                    <span>Detail Info</span>
                    <div class="line"></div>
                </div>
                <div>
                    @if($titipRequest->budget_min && $titipRequest->budget_max)
                    <div class="detail-row">
                        <span class="detail-label">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M15 9l-5 3.5L8 11" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Budget
                        </span>
                        <span class="detail-value gold">Rp {{ number_format($titipRequest->budget_min, 0, ',', '.') }} <span style="color:rgba(255,255,255,.2);font-weight:400"> - </span> Rp {{ number_format($titipRequest->budget_max, 0, ',', '.') }}</span>
                    </div>
                    @elseif($titipRequest->budget_max)
                    <div class="detail-row">
                        <span class="detail-label">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M9 15l5-3.5L16 13" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Budget Max
                        </span>
                        <span class="detail-value gold">Rp {{ number_format($titipRequest->budget_max, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    @if($titipRequest->deadline)
                    <div class="detail-row">
                        <span class="detail-label">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M3 9h18M8 2v4M16 2v4" stroke-linecap="round"/></svg>
                            Deadline
                        </span>
                        <span class="detail-value">{{ $titipRequest->deadline->format('d M Y') }}</span>
                    </div>
                    @endif

                    @if($titipRequest->weight_estimate)
                    <div class="detail-row">
                        <span class="detail-label">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M12 3v7M12 10l-5 8h10l-5-8z" stroke-linejoin="round"/><path d="M9 18h6" stroke-linecap="round"/></svg>
                            Est. Berat
                        </span>
                        <span class="detail-value">{{ $weightLabels[$titipRequest->weight_estimate] ?? $titipRequest->weight_estimate }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Requester Profile -->
            <div class="fade-up s7">
                <div class="profile-card">
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(196,181,253,.4);margin-bottom:16px;display:flex;align-items:center;justify-content:center;gap:6px">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Peminta
                    </div>
                    <div class="profile-avatar">
                        {{ substr($titipRequest->user->name, 0, 1) }}
                    </div>
                    <div class="profile-name">{{ $titipRequest->user->name }}</div>
                    <div class="profile-stats">
                        <span style="display:flex;align-items:center;gap:4px">
                            <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            {{ number_format($titipRequest->user->rating_avg, 1) }}
                        </span>
                        <span style="color:rgba(255,255,255,.1)">|</span>
                        <span class="tp">{{ $titipRequest->user->trust_score }} TP</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @auth
                @if($titipRequest->user_id === Auth::id())
                <div class="glass fade-up s8" style="padding:24px">
                    <div class="sec-label" style="margin-bottom:16px">
                        <div class="dot"></div>
                        <span>Kelola</span>
                        <div class="line"></div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:12px">
                        <a href="{{ route('requests.edit', $titipRequest) }}" class="btn-ghost">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Permintaan
                        </a>
                        <form action="{{ route('requests.destroy', $titipRequest) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permintaan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-outline-red">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus Permintaan
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endauth

            <!-- Decorative Dots -->
            <div class="fade-up s8" style="display:flex;justify-content:center;gap:5px;padding-top:8px">
                <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.15);animation:dotBreath 3s ease-in-out infinite"></div>
                <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.15);animation:dotBreath 3s ease-in-out infinite;animation-delay:.5s"></div>
                <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.15);animation:dotBreath 3s ease-in-out infinite;animation-delay:1s"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isDesktop = window.innerWidth >= 1024;

    const bgPhoto = document.getElementById('bgPhoto');
    if (bgPhoto) {
        const img = new Image();
        img.onload = () => bgPhoto.classList.add('loaded');
        img.onerror = () => {};
        img.src = 'https://i.pinimg.com/736x/a8/52/22/a8522254f76c12589682d95ca041796c.jpg';
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
});
</script>
@endsection