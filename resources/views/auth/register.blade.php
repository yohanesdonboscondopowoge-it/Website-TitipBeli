<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - TitipBeli</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:#08080f;color:#f0f0f5;overflow-x:hidden}

        input:-webkit-autofill,input:-webkit-autofill:hover,input:-webkit-autofill:focus{-webkit-text-fill-color:rgba(255,255,255,.9)!important;-webkit-box-shadow:0 0 0 1000px rgba(10,10,20,.95) inset!important;transition:background-color 5000s ease-in-out 0s}

        /* ═══ VIDEO BACKGROUND ═══ */
        .video-bg{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);min-width:100%;min-height:100%;width:auto;height:auto;z-index:0;pointer-events:none;overflow:hidden}
        .video-bg iframe{position:absolute;top:50%;left:50%;width:100vw;height:56.25vw;min-height:100vh;min-width:177.78vh;transform:translate(-50%,-50%);border:0;pointer-events:none}
        .bg-fallback{position:fixed;inset:0;z-index:-1;background:#08080f}

        /* ═══ OVERLAYS ═══ */
        .overlay-left{position:fixed;left:0;top:0;width:50%;height:100%;z-index:1;background:linear-gradient(155deg,rgba(8,8,15,.93) 0%,rgba(8,8,15,.76) 55%,rgba(8,8,15,.58) 100%);pointer-events:none}
        .overlay-right{position:fixed;right:0;top:0;width:50%;height:100%;z-index:1;background:rgba(8,8,15,.18);pointer-events:none}
        .overlay-mobile{display:none;position:fixed;inset:0;z-index:1;background:linear-gradient(180deg,rgba(8,8,15,.15) 0%,rgba(8,8,15,.32) 30%,rgba(8,8,15,.88) 100%);pointer-events:none}

        /* ═══ CONTENT ═══ */
        .content-layer{position:relative;z-index:2;display:flex;min-height:100vh;min-height:100dvh}
        .content-left{flex:0 0 50%;display:flex;flex-direction:column;justify-content:space-between;padding:48px}
        .content-right{flex:0 0 50%;display:flex;align-items:center;justify-content:center;padding:40px}

        /* ═══ GLASS CARD ═══ */
        .glass-card{
            width:100%;max-width:420px;
            background:rgba(10,10,22,.7);
            backdrop-filter:blur(34px) saturate(1.3);
            -webkit-backdrop-filter:blur(34px) saturate(1.3);
            border:1px solid rgba(255,255,255,.07);
            border-radius:24px;padding:36px 38px;
            box-shadow:0 8px 60px rgba(0,0,0,.35),inset 0 1px 0 rgba(255,255,255,.04);
            transition:transform .15s ease-out;
            max-height:92vh;overflow-y:auto;
        }
        .glass-card::-webkit-scrollbar{display:none}
        .glass-card{scrollbar-width:none}

        /* ═══ ANIMATIONS ═══ */
        @keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        @keyframes shimmerBtn{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
        @keyframes spin{to{transform:rotate(360deg)}}
        @keyframes rippleAnim{to{transform:scale(4);opacity:0}}
        @keyframes dotBreath{0%,100%{opacity:.12}50%{opacity:.4}}
        @keyframes barGrow{from{width:0}to{width:var(--w)}}
        .fade-up{opacity:0;animation:fadeUp .6s cubic-bezier(.23,1,.32,1) forwards}
        .s1{animation-delay:.05s}.s2{animation-delay:.1s}.s3{animation-delay:.16s}
        .s4{animation-delay:.22s}.s5{animation-delay:.28s}.s6{animation-delay:.34s}
        .s7{animation-delay:.40s}.s8{animation-delay:.46s}.s9{animation-delay:.52s}
        .s10{animation-delay:.58s}.s11{animation-delay:.64s}.s12{animation-delay:.70s}

        /* ═══ FEATURE CARDS ═══ */
        .feature-card{display:flex;align-items:flex-start;gap:14px;padding:15px;border-radius:14px;background:rgba(0,0,0,.32);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.06);transition:all .4s cubic-bezier(.23,1,.32,1);cursor:default}
        .feature-card:hover{background:rgba(0,0,0,.42);border-color:rgba(201,162,39,.12);transform:translateX(6px)}
        .feat-icon{width:40px;height:40px;border-radius:10px;background:rgba(201,162,39,.08);border:1px solid rgba(201,162,39,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .4s}
        .feature-card:hover .feat-icon{background:rgba(201,162,39,.14);border-color:rgba(201,162,39,.2)}
        .feature-card:hover .feat-icon svg{transform:scale(1.1)}
        .feat-icon svg{transition:transform .4s}

        /* ═══ FORM ═══ */
        .form-field{position:relative}
        .form-field::after{content:'';position:absolute;bottom:0;left:50%;width:0;height:2px;background:linear-gradient(90deg,transparent,#c9a227,transparent);transition:all .4s cubic-bezier(.23,1,.32,1);transform:translateX(-50%);border-radius:1px}
        .form-field:focus-within::after{width:90%}
        .form-input{width:100%;padding:12px 16px 12px 44px;background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.055);border-radius:12px;color:rgba(255,255,255,.9);font-size:14px;font-family:'Inter',sans-serif;font-weight:400;outline:none;transition:all .3s}
        .form-input::placeholder{color:rgba(255,255,255,.13)}
        .form-input:focus{background:rgba(255,255,255,.055);border-color:rgba(201,162,39,.16)}
        .form-field:focus-within .field-icon{color:#c9a227}
        .form-field:focus-within .field-label{color:rgba(201,162,39,.55)}
        .field-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.12);transition:all .3s;pointer-events:none}
        .form-field:focus-within .field-icon{transform:translateY(-50%) scale(1.08)}

        /* ═══ ROW 2-COL ═══ */
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}

        /* ═══ PASSWORD STRENGTH ═══ */
        .pw-strength{display:flex;gap:4px;margin-top:6px}
        .pw-bar{height:3px;border-radius:2px;background:rgba(255,255,255,.06);flex:1;transition:all .4s ease;overflow:hidden}
        .pw-bar.active{animation:barGrow .4s ease forwards}
        .pw-text{font-size:11px;margin-top:4px;font-weight:500;transition:color .3s;min-height:16px}

        /* ═══ BUTTON ═══ */
        .btn-login{width:100%;padding:13px;border:none;border-radius:13px;font-family:'Inter',sans-serif;font-size:15px;font-weight:700;cursor:pointer;position:relative;overflow:hidden;background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);background-size:200% 200%;color:#0c0c18;box-shadow:0 2px 20px rgba(201,162,39,.12),inset 0 1px 0 rgba(255,255,255,.2);transition:all .35s cubic-bezier(.23,1,.32,1)}
        .btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 35px rgba(201,162,39,.25),inset 0 1px 0 rgba(255,255,255,.25);background-position:100% 100%}
        .btn-login:active{transform:translateY(0) scale(.98)}
        .btn-login::after{content:'';position:absolute;inset:0;background:linear-gradient(105deg,transparent 40%,rgba(255,255,255,.22) 50%,transparent 60%);transform:translateX(-100%)}
        .btn-login:hover::after{animation:shimmerBtn 1.8s ease-in-out infinite}
        .btn-login .ripple-fx{position:absolute;border-radius:50%;background:rgba(255,255,255,.35);transform:scale(0);animation:rippleAnim .6s ease-out forwards;pointer-events:none}

        /* ═══ LINKS ═══ */
        .link-gold{color:rgba(201,162,39,.6);text-decoration:none;font-weight:600;transition:all .3s}
        .link-gold:hover{color:rgba(201,162,39,1);text-decoration:underline;text-underline-offset:3px}
        .link-subtle{color:rgba(255,255,255,.22);text-decoration:none;font-weight:500;transition:color .3s}
        .link-subtle:hover{color:rgba(255,255,255,.45)}
        .link-back{color:rgba(255,255,255,.14);text-decoration:none;font-weight:500;transition:all .3s;display:inline-flex;align-items:center;gap:5px}
        .link-back:hover{color:rgba(255,255,255,.4)}
        .link-back svg{transition:transform .3s}
        .link-back:hover svg{transform:translateX(-3px)}

        /* ═══ MOBILE ═══ */
        .mobile-header{display:none!important}
        .mobile-stats{display:none!important;gap:1px;background:rgba(255,255,255,.03);border-radius:12px;overflow:hidden;margin-bottom:20px}
        .mobile-stat{flex:1;text-align:center;padding:11px 6px;background:rgba(255,255,255,.01)}
        .mobile-stat-num{font-size:16px;font-weight:700;color:#c9a227;line-height:1;margin-bottom:2px}
        .mobile-stat-label{font-size:9px;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.18);font-weight:500}

        @media(max-width:1023px){
            .overlay-left,.overlay-right{display:none}
            .overlay-mobile{display:block}
            .content-layer{flex-direction:column}
            .content-left{display:none}
            .content-right{flex:1;display:flex;flex-direction:column;justify-content:flex-end;padding:0}
            .glass-card{max-width:100%;border-radius:24px 24px 0 0;padding:28px 20px 34px;box-shadow:0 -8px 60px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.04);background:rgba(10,10,22,.8);backdrop-filter:blur(36px) saturate(1.3);-webkit-backdrop-filter:blur(36px) saturate(1.3);max-height:86dvh}
            .mobile-header{display:flex!important;flex-direction:column;align-items:center;margin-bottom:16px}
            .mobile-stats{display:flex!important}
            .form-row{grid-template-columns:1fr}
        }
        @media(min-width:1024px){.mobile-stats{display:none!important}}
        @media(max-width:380px){.form-input{padding-left:38px;font-size:13px}.field-icon{left:12px}.glass-card{padding:24px 16px 30px}}

        ::-webkit-scrollbar{display:none}html{scrollbar-width:none}
    </style>
</head>
<body>

<!-- Dark fallback (visible before video loads) -->
<div class="bg-fallback"></div>

<!-- YouTube Video Background -->
<div class="video-bg" id="videoBg">
    <iframe
        id="ytPlayer"
        src="https://www.youtube.com/embed/KjePbhd7nto?autoplay=1&mute=1&loop=1&playlist=KjePbhd7nto&controls=0&showinfo=0&modestbranding=1&rel=0&playsinline=1&enablejsapi=1&iv_load_policy=3&disablekb=1&fs=0&cc_load_policy=0&origin={{ url('/') }}"
        allow="autoplay; encrypted-media"
        allowfullscreen
        title="Background Video">
    </iframe>
</div>

<!-- Overlays -->
<div class="overlay-left"></div>
<div class="overlay-right"></div>
<div class="overlay-mobile"></div>

<!-- ═══ CONTENT ═══ -->
<div class="content-layer">

    <!-- ═══ LEFT: Branding ═══ -->
    <div class="content-left" id="contentLeft">
        <div>
            <a href="{{ route('home') }}" style="text-decoration:none;display:inline-flex;align-items:center;gap:12px">
                <div style="width:46px;height:46px;border-radius:13px;background:linear-gradient(145deg,#c9a227,#a88520);display:flex;align-items:center;justify-content:center;font-size:21px;box-shadow:0 4px 20px rgba(201,162,39,.2),0 0 0 1px rgba(201,162,39,.12);position:relative;overflow:hidden">
                    <span style="filter:drop-shadow(0 1px 2px rgba(0,0,0,.3));position:relative;z-index:1">🧳</span>
                    <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.22),transparent 50%)"></div>
                </div>
                <span style="font-size:21px;font-weight:700;color:#f0f0f5;letter-spacing:-.02em;text-shadow:0 2px 16px rgba(0,0,0,.7)">TitipBeli</span>
            </a>
        </div>

        <div style="max-width:400px">
            <div style="display:inline-block;padding:5px 13px;border-radius:20px;background:rgba(201,162,39,.08);border:1px solid rgba(201,162,39,.1);margin-bottom:20px;backdrop-filter:blur(8px)">
                <span style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:.12em;color:rgba(201,162,39,.7)">Gratis untuk bergabung</span>
            </div>
            <h2 style="font-size:36px;font-weight:800;line-height:1.12;color:#fff;letter-spacing:-.03em;margin-bottom:14px;text-shadow:0 2px 24px rgba(0,0,0,.6)">
                Mulai perjalanan<br>
                <span style="background:linear-gradient(135deg,#c9a227,#e0c05a,#c9a227);-webkit-background-clip:text;background-clip:text;color:transparent">belanjamu</span><br>
                di sini.
            </h2>
            <p style="font-size:14.5px;color:rgba(255,255,255,.5);line-height:1.65;font-weight:300;text-shadow:0 1px 10px rgba(0,0,0,.6)">
                Bergabung dengan ribuan pengguna yang sudah mempercayai TitipBeli untuk belanja produk luar negeri.
            </p>
        </div>

        <div style="display:flex;flex-direction:column;gap:10px" id="featList">
            <div class="feature-card" id="feat1">
                <div class="feat-icon">
                    <svg width="18" height="18" style="color:rgba(201,162,39,.7)" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div style="font-size:13.5px;font-weight:600;color:rgba(255,255,255,.8);margin-bottom:2px">Daftar Gratis</div>
                    <div style="font-size:12.5px;color:rgba(255,255,255,.35);line-height:1.4">Tanpa biaya pendaftaran apapun</div>
                </div>
            </div>
            <div class="feature-card" id="feat2">
                <div class="feat-icon">
                    <svg width="18" height="18" style="color:rgba(201,162,39,.7)" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <div style="font-size:13.5px;font-weight:600;color:rgba(255,255,255,.8);margin-bottom:2px">Verifikasi Kilat</div>
                    <div style="font-size:12.5px;color:rgba(255,255,255,.35);line-height:1.4">Akun langsung aktif setelah daftar</div>
                </div>
            </div>
            <div class="feature-card" id="feat3">
                <div class="feat-icon">
                    <svg width="18" height="18" style="color:rgba(201,162,39,.7)" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <div style="font-size:13.5px;font-weight:600;color:rgba(255,255,255,.8);margin-bottom:2px">Data Terproteksi</div>
                    <div style="font-size:12.5px;color:rgba(255,255,255,.35);line-height:1.4">Privasi dan keamanan data terjamin</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ RIGHT: Glass Form ═══ -->
    <div class="content-right" id="contentRight">
        <div class="glass-card" id="glassCard">

            <!-- Mobile logo -->
            <div class="mobile-header">
                <a href="{{ route('home') }}" style="text-decoration:none;display:inline-flex;align-items:center;gap:10px">
                    <div style="width:40px;height:40px;border-radius:11px;background:linear-gradient(145deg,#c9a227,#a88520);display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 2px 12px rgba(201,162,39,.15);position:relative;overflow:hidden">
                        <span style="position:relative;z-index:1">🧳</span>
                        <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.2),transparent 50%)"></div>
                    </div>
                    <span style="font-size:18px;font-weight:700;color:#f0f0f5">TitipBeli</span>
                </a>
            </div>

            <!-- Mobile stats -->
            <div class="mobile-stats fade-up s1">
                <div class="mobile-stat">
                    <div class="mobile-stat-num" id="statUsers">0</div>
                    <div class="mobile-stat-label">Pengguna</div>
                </div>
                <div class="mobile-stat">
                    <div class="mobile-stat-num" id="statOrders">0</div>
                    <div class="mobile-stat-label">Pesanan</div>
                </div>
                <div class="mobile-stat">
                    <div class="mobile-stat-num" id="statRating">0</div>
                    <div class="mobile-stat-label">Rating</div>
                </div>
            </div>

            <!-- Heading -->
            <div class="fade-up s1" style="margin-bottom:4px">
                <h1 style="font-size:25px;font-weight:700;color:#f0f0f5;letter-spacing:-.02em">Daftar Akun</h1>
            </div>
            <div class="fade-up s2" style="margin-bottom:24px">
                <p style="font-size:13.5px;color:rgba(255,255,255,.26);font-weight:300">Buat akun baru untuk mulai belanja.</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name + Username -->
                <div class="form-row fade-up s3" style="margin-bottom:14px">
                    <div>
                        <label class="field-label" style="display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.24);margin-bottom:6px;transition:color .3s">Nama Lengkap</label>
                        <div class="form-field">
                            <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" class="form-input">
                        </div>
                        @error('name')
                            <p style="font-size:11.5px;color:rgba(252,165,165,.8);margin-top:4px;margin-left:2px;display:flex;align-items:center;gap:4px">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label" style="display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.24);margin-bottom:6px;transition:color .3s">Username</label>
                        <div class="form-field">
                            <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm-3.375 6.375h3.375"/></svg>
                            <input type="text" name="username" value="{{ old('username') }}" required placeholder="johndoe" class="form-input">
                        </div>
                        @error('username')
                            <p style="font-size:11.5px;color:rgba(252,165,165,.8);margin-top:4px;margin-left:2px;display:flex;align-items:center;gap:4px">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="fade-up s4" style="margin-bottom:14px">
                    <label class="field-label" style="display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.24);margin-bottom:6px;transition:color .3s">Email</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="kamu@email.com" class="form-input">
                    </div>
                    @error('email')
                        <p style="font-size:11.5px;color:rgba(252,165,165,.8);margin-top:4px;margin-left:2px;display:flex;align-items:center;gap:4px">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="fade-up s5" style="margin-bottom:4px"
                     x-data="{ show: false, strength: 0, label: '', color: '' }">
                    <label class="field-label" style="display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.24);margin-bottom:6px;transition:color .3s">Password</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter"
                               class="form-input" style="padding-right:44px"
                               @input="let v=$el.value;let s=0;if(v.length>=8)s++;if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;strength=s;label=s===0?'':s===1?'Lemah':s===2?'Cukup':s===3?'Kuat':'Sangat Kuat';color=s===0?'':s===1?'rgba(239,68,68,.7)':s===2?'rgba(245,158,11,.7)':s===3?'rgba(34,197,94,.7)':'rgba(16,185,129,.7)'">
                        <button type="button" @click="show=!show" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:3px;display:flex;transition:color .2s" :style="'color:'+(show?'rgba(201,162,39,.5)':'rgba(255,255,255,.12)')">
                            <svg x-show="!show" x-transition width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-transition width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <div class="pw-strength">
                        <div class="pw-bar" :style="strength>=1?'background:'+color+';--w:100%':''" :class="strength>=1?'active':''"></div>
                        <div class="pw-bar" :style="strength>=2?'background:'+color+';--w:100%':''" :class="strength>=2?'active':''"></div>
                        <div class="pw-bar" :style="strength>=3?'background:'+color+';--w:100%':''" :class="strength>=3?'active':''"></div>
                        <div class="pw-bar" :style="strength>=4?'background:'+color+';--w:100%':''" :class="strength>=4?'active':''"></div>
                    </div>
                    <div class="pw-text" :style="'color:'+color" x-text="label"></div>
                    @error('password')
                        <p style="font-size:11.5px;color:rgba(252,165,165,.8);margin-top:2px;margin-left:2px;display:flex;align-items:center;gap:4px">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="fade-up s6" style="margin-bottom:14px" x-data="{ show: false }">
                    <label class="field-label" style="display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.24);margin-bottom:6px;transition:color .3s">Konfirmasi Password</label>
                    <div class="form-field">
                        <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" class="form-input" style="padding-right:44px">
                        <button type="button" @click="show=!show" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:3px;display:flex;transition:color .2s" :style="'color:'+(show?'rgba(201,162,39,.5)':'rgba(255,255,255,.12)')">
                            <svg x-show="!show" x-transition width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-transition width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p style="font-size:11.5px;color:rgba(252,165,165,.8);margin-top:4px;margin-left:2px;display:flex;align-items:center;gap:4px">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Phone + City -->
                <div class="form-row fade-up s7" style="margin-bottom:22px">
                    <div>
                        <label class="field-label" style="display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.24);margin-bottom:6px;transition:color .3s">Nomor HP</label>
                        <div class="form-field">
                            <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0812xxxxxxxx" class="form-input">
                        </div>
                        @error('phone')
                            <p style="font-size:11.5px;color:rgba(252,165,165,.8);margin-top:4px;margin-left:2px;display:flex;align-items:center;gap:4px">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label" style="display:block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.15em;color:rgba(255,255,255,.24);margin-bottom:6px;transition:color .3s">Kota</label>
                        <div class="form-field">
                            <svg class="field-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="Jakarta" class="form-input">
                        </div>
                        @error('city')
                            <p style="font-size:11.5px;color:rgba(252,165,165,.8);margin-top:4px;margin-left:2px;display:flex;align-items:center;gap:4px">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Submit -->
                <div class="fade-up s8" style="margin-bottom:18px">
                    <button type="submit" class="btn-login" id="submitBtn">
                        <span id="btnDefault" style="display:flex;align-items:center;justify-content:center;gap:8px;position:relative;z-index:1">
                            <span>Buat Akun</span>
                            <svg id="btnArrow" width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" style="transition:transform .3s"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                        <span id="btnLoading" style="display:none;align-items:center;justify-content:center;gap:8px;position:relative;z-index:1">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" style="animation:spin .8s linear infinite"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <span>Memproses...</span>
                        </span>
                    </button>
                </div>

                <!-- Divider -->
                <div class="fade-up s9" style="display:flex;align-items:center;gap:16px;margin-bottom:16px">
                    <div style="flex:1;height:1px;background:rgba(255,255,255,.05)"></div>
                    <span style="font-size:10px;text-transform:uppercase;letter-spacing:.2em;font-weight:600;color:rgba(255,255,255,.1)">atau</span>
                    <div style="flex:1;height:1px;background:rgba(255,255,255,.05)"></div>
                </div>

                <!-- Login link -->
                <p class="fade-up s10" style="text-align:center;font-size:14px;color:rgba(255,255,255,.2)">
                    Sudah punya akun? <a href="{{ route('login') }}" class="link-gold">Masuk di sini</a>
                </p>

                <!-- Back -->
                <div class="fade-up s11" style="text-align:center;margin-top:14px">
                    <a href="{{ route('home') }}" class="link-back" style="font-size:12.5px">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Dots -->
                <div class="fade-up s12" style="display:flex;justify-content:center;gap:5px;margin-top:18px">
                    <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.2);animation:dotBreath 3s ease-in-out infinite"></div>
                    <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.2);animation:dotBreath 3s ease-in-out infinite;animation-delay:.5s"></div>
                    <div style="width:4px;height:4px;border-radius:50%;background:rgba(201,162,39,.2);animation:dotBreath 3s ease-in-out infinite;animation-delay:1s"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const isDesktop = window.innerWidth >= 1024;
    const isMobile = !isDesktop;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ═══ 1. YOUTUBE VIDEO BG — Pause when tab not visible (save bandwidth) ═══ */
    const ytFrame = document.getElementById('ytPlayer');
    let ytReady = false;

    // Load YouTube IFrame API to control playback
    const tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    document.head.appendChild(tag);

    let player;
    window.onYouTubeIframeAPIReady = function() {
        player = new YT.Player('ytPlayer', {
            events: {
                onReady: function(e) {
                    ytReady = true;
                    e.target.mute();
                    // Ensure it's playing
                    e.target.playVideo();
                },
                onStateChange: function(e) {
                    // If video ends, restart (backup loop)
                    if (e.data === YT.PlayerState.ENDED) {
                        e.target.seekTo(0);
                        e.target.playVideo();
                    }
                }
            }
        });
    };

    // Pause when tab hidden, resume when visible
    document.addEventListener('visibilitychange', () => {
        if (!ytReady || !player) return;
        if (document.hidden) {
            player.pauseVideo();
        } else {
            player.playVideo();
        }
    });

    /* ═══ 2. GLASS CARD TILT (desktop) ═══ */
    if (isDesktop && !reducedMotion) {
        const card = document.getElementById('glassCard');
        const right = document.getElementById('contentRight');
        if (card && right) {
            right.addEventListener('mousemove', e => {
                const r = right.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - 0.5;
                const y = (e.clientY - r.top) / r.height - 0.5;
                card.style.transform = `perspective(900px) rotateY(${x * 2}deg) rotateX(${y * -2}deg)`;
                card.style.transition = 'transform 0.1s ease-out';
            });
            right.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(900px) rotateY(0) rotateX(0)';
                card.style.transition = 'transform 0.5s ease-out';
            });
        }
    }

    /* ═══ 3. FEATURE CARDS STAGGER (desktop) ═══ */
    if (isDesktop && !reducedMotion) {
        ['feat1','feat2','feat3'].forEach((id, i) => {
            const el = document.getElementById(id);
            if (el) {
                el.style.opacity = '0';
                el.style.transform = 'translateX(-14px)';
                el.style.transition = 'all .6s cubic-bezier(.23,1,.32,1)';
                setTimeout(() => { el.style.opacity = '1'; el.style.transform = 'translateX(0)'; }, 800 + i * 130);
            }
        });
    }

    /* ═══ 4. BUTTON RIPPLE ═══ */
    const btn = document.getElementById('submitBtn');
    if (btn) {
        function ripple(e) {
            const rect = btn.getBoundingClientRect();
            const cx = e.clientX ?? (e.touches?.[0]?.clientX) ?? rect.left + rect.width / 2;
            const cy = e.clientY ?? (e.touches?.[0]?.clientY) ?? rect.top + rect.height / 2;
            const span = document.createElement('span');
            span.className = 'ripple-fx';
            const sz = Math.max(rect.width, rect.height) * 1.2;
            span.style.cssText = `width:${sz}px;height:${sz}px;left:${cx - rect.left - sz/2}px;top:${cy - rect.top - sz/2}px`;
            btn.appendChild(span);
            setTimeout(() => span.remove(), 600);
        }
        btn.addEventListener('click', ripple);
        btn.addEventListener('touchstart', ripple, { passive: true });
    }

    /* ═══ 5. ARROW NUDGE ═══ */
    if (btn && isDesktop) {
        const arrow = document.getElementById('btnArrow');
        if (arrow) {
            btn.addEventListener('mouseenter', () => arrow.style.transform = 'translateX(3px)');
            btn.addEventListener('mouseleave', () => arrow.style.transform = 'translateX(0)');
        }
    }

    /* ═══ 6. FORM SUBMIT LOADING ═══ */
    const form = document.getElementById('registerForm');
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

    /* ═══ 7. INPUT TYPING FEEDBACK ═══ */
    document.querySelectorAll('.form-input').forEach(input => {
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

    /* ═══ 8. MOBILE STATS COUNTER ═══ */
    function animateNum(el, target, suffix, dur) {
        if (!el) return;
        const t0 = performance.now();
        (function tick(now) {
            const p = Math.min((now - t0) / dur, 1);
            const e = 1 - Math.pow(1 - p, 3);
            el.textContent = suffix === '/5' ? (target * e).toFixed(1) + suffix : Math.round(target * e).toLocaleString('id-ID') + suffix;
            if (p < 1) requestAnimationFrame(tick);
        })(t0);
    }
    if (isMobile && !reducedMotion) {
        setTimeout(() => {
            animateNum(document.getElementById('statUsers'), 12500, '+', 1500);
            animateNum(document.getElementById('statOrders'), 48000, '+', 1800);
            animateNum(document.getElementById('statRating'), 4.9, '/5', 1200);
        }, 500);
    }

    /* ═══ 9. REDUCED MOTION ═══ */
    if (reducedMotion) {
        document.querySelectorAll('.fade-up').forEach(el => { el.style.animation = 'none'; el.style.opacity = '1'; });
    }
});
</script>
</body>
</html>