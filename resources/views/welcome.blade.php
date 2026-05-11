<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TitipBeli - Titip Beli Aman, Cuan Jalan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:#08080f;color:#f0f0f5;overflow-x:hidden}

        input:-webkit-autofill,input:-webkit-autofill:hover,input:-webkit-autofill:focus{-webkit-text-fill-color:rgba(255,255,255,.9)!important;-webkit-box-shadow:0 0 0 1000px rgba(10,10,20,.95) inset!important;transition:background-color 5000s ease-in-out 0s}

        /* ═══ SCROLLBAR ═══ */
        ::-webkit-scrollbar{width:6px}
        ::-webkit-scrollbar-track{background:#08080f}
        ::-webkit-scrollbar-thumb{background:rgba(201,162,39,.25);border-radius:3px}
        ::-webkit-scrollbar-thumb:hover{background:rgba(201,162,39,.4)}
        html{scrollbar-width:thin;scrollbar-color:rgba(201,162,39,.25) #08080f}

        /* ═══ NAVIGATION OVERRIDE ═══ */
        nav{background:rgba(8,8,15,.85)!important;backdrop-filter:blur(20px)!important;-webkit-backdrop-filter:blur(20px)!important;border-bottom:1px solid rgba(255,255,255,.04)!important}
        nav *{color:rgba(255,255,255,.7)!important}
        nav a:hover{color:rgba(201,162,39,.8)!important}

        /* ═══ HERO ═══ */
        .hero-section{position:relative;min-height:100vh;display:flex;align-items:center;overflow:hidden}
        .hero-video{position:absolute;inset:0;z-index:0}
        .hero-video iframe{position:absolute;top:50%;left:50%;width:100vw;height:56.25vw;min-height:100vh;min-width:177.78vh;transform:translate(-50%,-50%);border:0;pointer-events:none}
        .hero-overlay{position:absolute;inset:0;z-index:1;background:linear-gradient(180deg,rgba(8,8,15,.55) 0%,rgba(8,8,15,.45) 40%,rgba(8,8,15,.8) 100%)}
        .hero-grid{position:absolute;inset:0;z-index:1;background-image:linear-gradient(rgba(201,162,39,.015) 1px,transparent 1px),linear-gradient(90deg,rgba(201,162,39,.015) 1px,transparent 1px);background-size:80px 80px;mask-image:radial-gradient(ellipse at 50% 50%,black 10%,transparent 60%);-webkit-mask-image:radial-gradient(ellipse at 50% 50%,black 10%,transparent 60%);pointer-events:none}

        /* ═══ BUTTONS ═══ */
        .btn-gold{display:inline-flex;align-items:center;gap:8px;padding:14px 32px;background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);background-size:200% 200%;color:#0c0c18;font-family:'Inter',sans-serif;font-size:15px;font-weight:700;border:none;border-radius:14px;text-decoration:none;cursor:pointer;position:relative;overflow:hidden;box-shadow:0 4px 24px rgba(201,162,39,.15),inset 0 1px 0 rgba(255,255,255,.2);transition:all .35s cubic-bezier(.23,1,.32,1)}
        .btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 40px rgba(201,162,39,.28),inset 0 1px 0 rgba(255,255,255,.25);background-position:100% 100%}
        .btn-gold:active{transform:translateY(0) scale(.98)}
        .btn-gold::after{content:'';position:absolute;inset:0;background:linear-gradient(105deg,transparent 40%,rgba(255,255,255,.22) 50%,transparent 60%);transform:translateX(-100%)}
        .btn-gold:hover::after{animation:shimmerBtn 1.8s ease-in-out infinite}
        .btn-glass{display:inline-flex;align-items:center;gap:8px;padding:14px 32px;background:rgba(255,255,255,.04);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.8);font-family:'Inter',sans-serif;font-size:15px;font-weight:600;border-radius:14px;text-decoration:none;cursor:pointer;transition:all .3s}
        .btn-glass:hover{background:rgba(255,255,255,.08);border-color:rgba(201,162,39,.15);color:#f0f0f5}

        /* ═══ ANIMATIONS ═══ */
        @keyframes shimmerBtn{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
        @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
        @keyframes scrollBounce{0%,100%{transform:translateY(0)}50%{transform:translateY(6px)}}
        @keyframes ringPulse{0%,100%{opacity:.06;transform:scale(1)}50%{opacity:.12;transform:scale(1.05)}}
        @keyframes lineGrow{from{transform:scaleX(0)}to{transform:scaleX(1)}}
        @keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}

        /* ═══ REVEAL ON SCROLL ═══ */
        .reveal{opacity:0;transform:translateY(30px);transition:all .7s cubic-bezier(.23,1,.32,1)}
        .reveal.visible{opacity:1;transform:translateY(0)}
        .reveal-delay-1{transition-delay:.1s}
        .reveal-delay-2{transition-delay:.2s}
        .reveal-delay-3{transition-delay:.3s}
        .reveal-delay-4{transition-delay:.4s}

        /* ═══ GLASS CARD ═══ */
        .glass-card{background:rgba(255,255,255,.025);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.05);border-radius:20px;transition:all .4s cubic-bezier(.23,1,.32,1)}
        .glass-card:hover{background:rgba(255,255,255,.04);border-color:rgba(201,162,39,.1);transform:translateY(-4px);box-shadow:0 12px 40px rgba(0,0,0,.2)}

        /* ═══ STEP CARD ═══ */
        .step-num{width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;flex-shrink:0;transition:all .4s}
        .glass-card:hover .step-num{transform:scale(1.1) rotate(-3deg)}

        /* ═══ STATS ═══ */
        .stat-num{font-size:clamp(36px,6vw,56px);font-weight:900;line-height:1;letter-spacing:-.03em}

        /* ═══ SECTION DIVIDER ═══ */
        .section-line{width:60px;height:2px;background:linear-gradient(90deg,transparent,#c9a227,transparent);margin:0 auto 16px;border-radius:1px}

        /* ═══ TRUST ITEM ═══ */
        .trust-item{display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.3);font-weight:500}
        .trust-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0}

        /* ═══ HERO FLOATING CARDS ═══ */
        .hero-float{position:absolute;border-radius:16px;padding:16px 20px;background:rgba(10,10,22,.6);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.06);pointer-events:none}

        /* ═══ CTA ═══ */
        .cta-card{position:relative;overflow:hidden;border-radius:24px;background:rgba(255,255,255,.02);border:1px solid rgba(201,162,39,.08)}
        .cta-card::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 50% 0%,rgba(201,162,39,.06),transparent 60%);pointer-events:none}

        /* ═══ FOOTER ═══ */
        .site-footer{border-top:1px solid rgba(255,255,255,.04);background:rgba(8,8,15,.6)}

        /* ═══ LINKS ═══ */
        .link-gold{color:rgba(201,162,39,.6);text-decoration:none;font-weight:600;transition:color .3s}
        .link-gold:hover{color:rgba(201,162,39,1)}

        /* ═══ MOBILE ═══ */
        @media(max-width:1023px){
            .hero-float{display:none}
        }
        @media(max-width:640px){
            .stat-num{font-size:32px}
        }

        /* ═══ REDUCED MOTION ═══ */
        @media(prefers-reduced-motion:reduce){
            .reveal{transition:none;opacity:1;transform:none}
            *{animation:none!important;transition-duration:0s!important}
        }
    </style>
</head>
<body>

    @include('layouts.navigation')

    <!-- ═══════════════════════════════════════════
         HERO SECTION
         ═══════════════════════════════════════════ -->
    <section class="hero-section">
        <!-- Video background -->
        <div class="hero-video">
            <iframe
                id="heroYt"
                src="https://www.youtube.com/embed/KjePbhd7nto?autoplay=1&mute=1&loop=1&playlist=KjePbhd7nto&controls=0&showinfo=0&modestbranding=1&rel=0&playsinline=1&enablejsapi=1&iv_load_policy=3&disablekb=1&fs=0&cc_load_policy=0&origin={{ url('/') }}"
                allow="autoplay; encrypted-media"
                allowfullscreen
                title="Background Video">
            </iframe>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-grid"></div>

        <!-- Decorative rings -->
        <div style="position:absolute;width:400px;height:400px;top:10%;right:5%;border-radius:50%;border:1px solid rgba(201,162,39,.04);pointer-events:none;z-index:1;animation:ringPulse 6s ease-in-out infinite"></div>
        <div style="position:absolute;width:250px;height:250px;bottom:20%;left:8%;border-radius:50%;border:1px solid rgba(201,162,39,.03);pointer-events:none;z-index:1;animation:ringPulse 5s ease-in-out infinite;animation-delay:-2s"></div>

        <div style="position:relative;z-index:2;max-width:1200px;margin:0 auto;padding:120px 24px 80px;width:100%">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center">

                <!-- Left content -->
                <div>
                    <!-- Badge -->
                    <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 16px;border-radius:20px;background:rgba(201,162,39,.06);border:1px solid rgba(201,162,39,.1);margin-bottom:28px;backdrop-filter:blur(8px);animation:fadeIn 1s ease .2s both">
                        <div style="width:6px;height:6px;border-radius:50%;background:#c9a227;animation:float 2s ease-in-out infinite"></div>
                        <span style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:rgba(201,162,39,.7)">Platform Titip Beli #1</span>
                    </div>

                    <!-- Heading -->
                    <h1 style="font-size:clamp(40px,5.5vw,68px);font-weight:900;line-height:1.08;letter-spacing:-.035em;margin-bottom:20px;animation:fadeUp .8s cubic-bezier(.23,1,.32,1) .1s both">
                        <span style="color:#f0f0f5">Titip </span>
                        <span style="background:linear-gradient(135deg,#c9a227,#e0c05a,#c9a227);-webkit-background-clip:text;background-clip:text;color:transparent">Beli</span>
                        <br>
                        <span style="color:#f0f0f5">Anti Ribet</span>
                    </h1>

                    <!-- Subtitle -->
                    <p style="font-size:clamp(15px,1.8vw,18px);color:rgba(255,255,255,.35);line-height:1.7;font-weight:300;max-width:480px;margin-bottom:36px;animation:fadeUp .8s cubic-bezier(.23,1,.32,1) .25s both">
                        Platform peer-to-peer pertama yang menghubungkan
                        <span style="color:rgba(201,162,39,.7);font-weight:500">traveller</span>
                        dengan
                        <span style="color:rgba(201,162,39,.7);font-weight:500">peminta titipan</span>.
                        Aman, terpercaya, dan menguntungkan.
                    </p>

                    <!-- Buttons -->
                    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:36px;animation:fadeUp .8s cubic-bezier(.23,1,.32,1) .4s both">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-gold">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                                Dashboard Saya
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-gold">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                Mulai Gratis
                            </a>
                            <a href="{{ route('trips.index') }}" class="btn-glass">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Jelajah Perjalanan
                            </a>
                        @endauth
                    </div>

                    <!-- Trust badges -->
                    <div style="display:flex;flex-wrap:wrap;gap:20px;animation:fadeUp .8s cubic-bezier(.23,1,.32,1) .55s both">
                        <div class="trust-item">
                            <div class="trust-dot" style="background:rgba(34,197,94,.5)"></div>
                            Escrow Aman
                        </div>
                        <div class="trust-item">
                            <div class="trust-dot" style="background:rgba(201,162,39,.5)"></div>
                            Rating Terpercaya
                        </div>
                        <div class="trust-item">
                            <div class="trust-dot" style="background:rgba(59,130,246,.5)"></div>
                            Support 24/7
                        </div>
                    </div>
                </div>

                <!-- Right floating cards (desktop only) -->
                <div style="position:relative;height:440px;display:flex;align-items:center;justify-content:center">
                    <!-- Main card -->
                    <div class="glass-card" style="width:340px;padding:32px;text-align:center;animation:fadeUp .8s cubic-bezier(.23,1,.32,1) .5s both">
                        <div style="font-size:72px;margin-bottom:16px;animation:float 5s ease-in-out infinite;filter:drop-shadow(0 8px 16px rgba(0,0,0,.3))">
                            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" style="display:inline-block">
                                <rect x="8" y="20" width="64" height="44" rx="8" stroke="rgba(201,162,39,.3)" stroke-width="2" fill="rgba(201,162,39,.05)"/>
                                <path d="M8 32L40 52L72 32" stroke="rgba(201,162,39,.4)" stroke-width="2" fill="none"/>
                                <circle cx="58" cy="18" r="14" stroke="rgba(201,162,39,.25)" stroke-width="2" fill="rgba(201,162,39,.05)"/>
                                <path d="M54 18L58 22L64 14" stroke="#c9a227" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div style="font-size:20px;font-weight:700;color:#f0f0f5;margin-bottom:4px">Jakarta → Surabaya</div>
                        <div style="font-size:14px;color:rgba(255,255,255,.3);margin-bottom:20px">3 slot tersisa</div>
                        <div style="display:flex;gap:8px;justify-content:center">
                            <div style="width:36px;height:36px;border-radius:10px;background:rgba(201,162,39,.08);border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center">
                                <svg width="16" height="16" fill="none" stroke="rgba(201,162,39,.6)" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div style="width:36px;height:36px;border-radius:10px;background:rgba(201,162,39,.08);border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center">
                                <svg width="16" height="16" fill="none" stroke="rgba(201,162,39,.6)" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div style="width:36px;height:36px;border-radius:10px;background:rgba(201,162,39,.08);border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center">
                                <svg width="16" height="16" fill="none" stroke="rgba(201,162,39,.6)" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Floating: Rating -->
                    <div class="hero-float" style="top:0;right:0;animation:float 6s ease-in-out infinite;animation-delay:-1s">
                        <div style="font-size:11px;color:rgba(255,255,255,.3);margin-bottom:2px">Rating Traveller</div>
                        <div style="font-size:22px;font-weight:800;color:#c9a227">4.9</div>
                    </div>

                    <!-- Floating: Success rate -->
                    <div class="hero-float" style="bottom:20px;left:-10px;animation:float 7s ease-in-out infinite;animation-delay:-3s">
                        <div style="font-size:11px;color:rgba(255,255,255,.3);margin-bottom:2px">Transaksi Aman</div>
                        <div style="font-size:22px;font-weight:800;color:rgba(34,197,94,.8)">99.8%</div>
                    </div>
                </div>
            </div>

            <!-- Mobile: stack -->
            <style>
                @media(max-width:1023px){
                    .hero-section>div:last-child>div{grid-template-columns:1fr!important;gap:40px!important;text-align:center}
                    .hero-section>div:last-child>div>div:last-child{display:none}
                    .hero-section>div:last-child>div>div:first-child>div:nth-child(4){justify-content:center}
                    .hero-section>div:last-child>div>div:first-child>div:nth-child(3){justify-content:center}
                }
            </style>
        </div>

        <!-- Scroll indicator -->
        <div style="position:absolute;bottom:32px;left:50%;transform:translateX(-50%);z-index:2;animation:scrollBounce 2s ease-in-out infinite">
            <a href="#how-it-works" style="display:flex;flex-direction:column;align-items:center;gap:6px;color:rgba(255,255,255,.2);text-decoration:none;font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.15em;transition:color .3s">
                <span>Scroll</span>
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </a>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
         HOW IT WORKS
         ═══════════════════════════════════════════ -->
    <section id="how-it-works" style="padding:100px 24px;position:relative;overflow:hidden">
        <!-- Subtle bg accent -->
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(201,162,39,.025),transparent 60%);pointer-events:none"></div>

        <div style="max-width:1100px;margin:0 auto">
            <!-- Header -->
            <div class="reveal" style="text-align:center;margin-bottom:64px">
                <div class="section-line"></div>
                <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.2em;color:rgba(201,162,39,.5)">Proses</span>
                <h2 style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#f0f0f5;letter-spacing:-.03em;margin-top:12px">Bagaimana Caranya?</h2>
                <p style="font-size:16px;color:rgba(255,255,255,.25);margin-top:12px;max-width:480px;margin-left:auto;margin-right:auto;line-height:1.6">
                    Tiga langkah mudah untuk mulai titip beli dengan aman
                </p>
            </div>

            <!-- Steps -->
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;position:relative">

                <!-- Connection line (desktop) -->
                <div style="position:absolute;top:50%;left:10%;right:10%;height:1px;background:linear-gradient(90deg,rgba(201,162,39,.06),rgba(201,162,39,.12),rgba(201,162,39,.06));transform:translateY(-50%);pointer-events:none"></div>

                <!-- Step 1 -->
                <div class="reveal reveal-delay-1" style="position:relative;z-index:1">
                    <div class="glass-card" style="padding:36px 28px;text-align:center">
                        <div class="step-num" style="margin:0 auto 20px;background:linear-gradient(135deg,rgba(201,162,39,.12),rgba(201,162,39,.04));border:1px solid rgba(201,162,39,.12);color:#c9a227">
                            <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(201,162,39,.4);margin-bottom:8px">Langkah 01</div>
                        <h3 style="font-size:18px;font-weight:700;color:#f0f0f5;margin-bottom:10px">Posting Rute</h3>
                        <p style="font-size:14px;color:rgba(255,255,255,.28);line-height:1.65">Traveller posting rute perjalanan & kuota titipan. Dapat cuan tambahan dari barang yang dititipkan!</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="reveal reveal-delay-2" style="position:relative;z-index:1">
                    <div class="glass-card" style="padding:36px 28px;text-align:center">
                        <div class="step-num" style="margin:0 auto 20px;background:linear-gradient(135deg,rgba(201,162,39,.12),rgba(201,162,39,.04));border:1px solid rgba(201,162,39,.12);color:#c9a227">
                            <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(201,162,39,.4);margin-bottom:8px">Langkah 02</div>
                        <h3 style="font-size:18px;font-weight:700;color:#f0f0f5;margin-bottom:10px">Cari yang Cocok</h3>
                        <p style="font-size:14px;color:rgba(255,255,255,.28);line-height:1.65">Butuh barang dari luar kota? Cari traveller dengan rute yang cocok & ajukan titipanmu!</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="reveal reveal-delay-3" style="position:relative;z-index:1">
                    <div class="glass-card" style="padding:36px 28px;text-align:center">
                        <div class="step-num" style="margin:0 auto 20px;background:linear-gradient(135deg,rgba(201,162,39,.12),rgba(201,162,39,.04));border:1px solid rgba(201,162,39,.12);color:#c9a227">
                            <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(201,162,39,.4);margin-bottom:8px">Langkah 03</div>
                        <h3 style="font-size:18px;font-weight:700;color:#f0f0f5;margin-bottom:10px">Titip & Terima</h3>
                        <p style="font-size:14px;color:rgba(255,255,255,.28);line-height:1.65">Sepakati harga, titip barang, terima dengan aman via escrow. Rating bikin semua terpercaya!</p>
                    </div>
                </div>
            </div>

            <!-- Mobile: stack steps -->
            <style>
                @media(max-width:767px){
                    #how-it-works>div>div:last-child{grid-template-columns:1fr!important}
                    #how-it-works>div>div:last-child>div:first-child{display:none}
                }
            </style>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
         STATS SECTION
         ═══════════════════════════════════════════ -->
    <section style="padding:100px 24px;position:relative;overflow:hidden">
        <!-- Background -->
        <div style="position:absolute;inset:0;background:linear-gradient(165deg,rgba(201,162,39,.04) 0%,rgba(8,8,15,0) 40%,rgba(8,8,15,0) 60%,rgba(201,162,39,.03) 100%);pointer-events:none"></div>
        <div style="position:absolute;top:-100px;right:-100px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(201,162,39,.04),transparent 60%);filter:blur(60px);pointer-events:none"></div>

        <!-- Top/bottom borders -->
        <div style="position:absolute;top:0;left:10%;right:10%;height:1px;background:linear-gradient(90deg,transparent,rgba(201,162,39,.08),transparent)"></div>
        <div style="position:absolute;bottom:0;left:10%;right:10%;height:1px;background:linear-gradient(90deg,transparent,rgba(201,162,39,.08),transparent)"></div>

        <div style="max-width:1100px;margin:0 auto;position:relative;z-index:1">
            <!-- Header -->
            <div class="reveal" style="text-align:center;margin-bottom:64px">
                <div class="section-line"></div>
                <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.2em;color:rgba(201,162,39,.5)">Statistik</span>
                <h2 style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#f0f0f5;letter-spacing:-.03em;margin-top:12px">Dipercaya Ribuan Pengguna</h2>
                <p style="font-size:16px;color:rgba(255,255,255,.25);margin-top:12px">Bergabung dengan komunitas titip beli terbesar di Indonesia</p>
            </div>

            <!-- Stats grid -->
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px">
                <div class="reveal reveal-delay-1" style="text-align:center;padding:32px 16px">
                    <div class="stat-num" style="color:#f0f0f5" data-target="12450" data-suffix="+">0</div>
                    <div style="width:32px;height:2px;background:linear-gradient(90deg,transparent,rgba(201,162,39,.3),transparent);margin:14px auto;border-radius:1px"></div>
                    <div style="font-size:13px;color:rgba(255,255,255,.25);font-weight:500">Transaksi Sukses</div>
                </div>
                <div class="reveal reveal-delay-2" style="text-align:center;padding:32px 16px">
                    <div class="stat-num" style="color:#f0f0f5" data-target="3200" data-suffix="+">0</div>
                    <div style="width:32px;height:2px;background:linear-gradient(90deg,transparent,rgba(201,162,39,.3),transparent);margin:14px auto;border-radius:1px"></div>
                    <div style="font-size:13px;color:rgba(255,255,255,.25);font-weight:500">Traveller Aktif</div>
                </div>
                <div class="reveal reveal-delay-3" style="text-align:center;padding:32px 16px">
                    <div class="stat-num" style="color:#f0f0f5" data-target="150" data-suffix="+">0</div>
                    <div style="width:32px;height:2px;background:linear-gradient(90deg,transparent,rgba(201,162,39,.3),transparent);margin:14px auto;border-radius:1px"></div>
                    <div style="font-size:13px;color:rgba(255,255,255,.25);font-weight:500">Kota Terjangkau</div>
                </div>
                <div class="reveal reveal-delay-4" style="text-align:center;padding:32px 16px">
                    <div class="stat-num" style="color:rgba(201,162,39,.85)" data-target="99.8" data-suffix="%" data-decimal="true">0</div>
                    <div style="width:32px;height:2px;background:linear-gradient(90deg,transparent,rgba(201,162,39,.3),transparent);margin:14px auto;border-radius:1px"></div>
                    <div style="font-size:13px;color:rgba(255,255,255,.25);font-weight:500">Tingkat Keberhasilan</div>
                </div>
            </div>

            <!-- Mobile: 2x2 grid -->
            <style>
                @media(max-width:767px){
                    section>div>div:last-child{grid-template-columns:repeat(2,1fr)!important;gap:16px!important}
                }
                @media(max-width:400px){
                    section>div>div:last-child{grid-template-columns:1fr!important}
                }
            </style>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
         CTA SECTION
         ═══════════════════════════════════════════ -->
    <section style="padding:100px 24px">
        <div style="max-width:720px;margin:0 auto">
            <div class="reveal cta-card" style="padding:56px 40px;text-align:center">
                <!-- Icon -->
                <div style="width:64px;height:64px;margin:0 auto 24px;border-radius:18px;background:linear-gradient(135deg,rgba(201,162,39,.1),rgba(201,162,39,.03));border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center">
                    <svg width="28" height="28" fill="none" stroke="rgba(201,162,39,.7)" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </div>

                <h2 style="font-size:clamp(24px,3.5vw,34px);font-weight:800;color:#f0f0f5;letter-spacing:-.02em;margin-bottom:12px">Siap Mulai Titip Beli?</h2>
                <p style="font-size:16px;color:rgba(255,255,255,.28);line-height:1.6;margin-bottom:32px;max-width:440px;margin-left:auto;margin-right:auto">
                    Daftar gratis sekarang dan mulai dapatkan barang impianmu dari seluruh Indonesia!
                </p>

                @guest
                    <a href="{{ route('register') }}" class="btn-gold" style="padding:16px 40px;font-size:16px">
                        Daftar Gratis Sekarang
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                @else
                    <a href="{{ route('trips.index') }}" class="btn-gold" style="padding:16px 40px;font-size:16px">
                        Jelajah Perjalanan
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </a>
                @endguest
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
         FOOTER
         ═══════════════════════════════════════════ -->
    <footer class="site-footer" style="padding:48px 24px">
        <div style="max-width:1100px;margin:0 auto;text-align:center">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:16px">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(145deg,#c9a227,#a88520);display:flex;align-items:center;justify-content:center;font-size:17px;box-shadow:0 2px 12px rgba(201,162,39,.15);position:relative;overflow:hidden">
                    <span style="position:relative;z-index:1">🧳</span>
                    <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.2),transparent 50%)"></div>
                </div>
                <span style="font-size:18px;font-weight:700;background:linear-gradient(135deg,#c9a227,#d4b040);-webkit-background-clip:text;background-clip:text;color:transparent">TitipBeli</span>
            </a>
            <p style="font-size:13px;color:rgba(255,255,255,.15)">&copy; {{ date('Y') }} TitipBeli. Titip aman, cuan jalan.</p>
        </div>
    </footer>


    <!-- ═══ SCRIPTS ═══ -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ═══ 1. YouTube Hero — Pause when tab hidden ═══ */
        let heroPlayer;
        const heroTag = document.createElement('script');
        heroTag.src = 'https://www.youtube.com/iframe_api';
        document.head.appendChild(heroTag);

        // Avoid conflict with login/register page if they loaded the API first
        const origReady = window.onYouTubeIframeAPIReady;
        window.onYouTubeIframeAPIReady = function() {
            if (origReady) origReady();
            const el = document.getElementById('heroYt');
            if (!el) return;
            heroPlayer = new YT.Player('heroYt', {
                events: {
                    onReady: function(e) {
                        e.target.mute();
                        e.target.playVideo();
                    },
                    onStateChange: function(e) {
                        if (e.data === YT.PlayerState.ENDED) {
                            e.target.seekTo(0);
                            e.target.playVideo();
                        }
                    }
                }
            });
        };

        document.addEventListener('visibilitychange', () => {
            if (!heroPlayer) return;
            if (document.hidden) heroPlayer.pauseVideo();
            else heroPlayer.playVideo();
        });


        /* ═══ 2. Scroll Reveal (IntersectionObserver) ═══ */
        if (!reducedMotion) {
            const reveals = document.querySelectorAll('.reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
            reveals.forEach(el => observer.observe(el));
        } else {
            document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
        }


        /* ═══ 3. Animated Stat Counters ═══ */
        const statNums = document.querySelectorAll('.stat-num[data-target]');
        const statObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = parseFloat(el.dataset.target);
                const suffix = el.dataset.suffix || '';
                const isDecimal = el.dataset.decimal === 'true';
                const duration = 1800;
                const t0 = performance.now();

                function tick(now) {
                    const p = Math.min((now - t0) / duration, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    const val = target * eased;
                    el.textContent = isDecimal ? val.toFixed(1) + suffix : Math.round(val).toLocaleString('id-ID') + suffix;
                    if (p < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
                statObserver.unobserve(el);
            });
        }, { threshold: 0.5 });

        statNums.forEach(el => statObserver.observe(el));


        /* ═══ 4. Smooth scroll for anchor links ═══ */
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'start' });
                }
            });
        });
    });
    </script>
</body>
</html>