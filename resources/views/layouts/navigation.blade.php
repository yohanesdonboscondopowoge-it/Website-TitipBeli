<nav x-data="{ open: false, scrolled: false, userMenu: false }" 
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 30)"
     :class="scrolled ? 'nav-scrolled' : ''"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">

    <style>
        nav {
            background: rgba(8,8,15,.82);
            border-bottom: 1px solid transparent;
            transition: all .4s cubic-bezier(.23,1,.32,1);
        }
        nav.nav-scrolled {
            background: rgba(8,8,15,.82);
            backdrop-filter: blur(24px) saturate(1.3);
            -webkit-backdrop-filter: blur(24px) saturate(1.3);
            border-bottom-color: rgba(255,255,255,.04);
        }
        /* Override any Tailwind nav conflicts */
        nav * { font-family: 'Inter', system-ui, sans-serif !important; }
    </style>

    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="display:flex;justify-content:space-between;align-items:center;height:68px">

            <!-- Logo -->
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none">
                <div style="width:38px;height:38px;border-radius:11px;background:linear-gradient(145deg,#c9a227,#a88520);display:flex;align-items:center;justify-content:center;font-size:17px;box-shadow:0 2px 14px rgba(201,162,39,.18),0 0 0 1px rgba(201,162,39,.1);position:relative;overflow:hidden;transition:transform .3s,box-shadow .3s"
                     onmouseover="this.style.transform='scale(1.06)';this.style.boxShadow='0 4px 20px rgba(201,162,39,.28),0 0 0 1px rgba(201,162,39,.15)'"
                     onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 2px 14px rgba(201,162,39,.18),0 0 0 1px rgba(201,162,39,.1)'">
                    <span style="position:relative;z-index:1;filter:drop-shadow(0 1px 1px rgba(0,0,0,.3))">🧳</span>
                    <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.22),transparent 50%)"></div>
                </div>
                <span style="font-size:19px;font-weight:700;background:linear-gradient(135deg,#c9a227,#d4b040);-webkit-background-clip:text;background-clip:text;color:transparent;letter-spacing:-.01em" class="hidden sm:inline">TitipBeli</span>
            </a>

            <!-- Desktop Menu -->
            <div style="display:flex;align-items:center;gap:2px" class="hidden md:flex">

                <a href="{{ route('home') }}" class="nav-link" style="display:flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;color:rgba(255,255,255,.5);text-decoration:none;font-size:13.5px;font-weight:500;transition:all .25s"
                   onmouseover="this.style.color='rgba(255,255,255,.9)';this.style.background='rgba(255,255,255,.04)'"
                   onmouseout="this.style.color='rgba(255,255,255,.5)';this.style.background='transparent'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                    Beranda
                </a>

                <a href="{{ route('trips.index') }}" class="nav-link" style="display:flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;color:rgba(255,255,255,.5);text-decoration:none;font-size:13.5px;font-weight:500;transition:all .25s"
                   onmouseover="this.style.color='rgba(255,255,255,.9)';this.style.background='rgba(255,255,255,.04)'"
                   onmouseout="this.style.color='rgba(255,255,255,.5)';this.style.background='transparent'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Perjalanan
                </a>

                <a href="{{ route('requests.index') }}" class="nav-link" style="display:flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;color:rgba(255,255,255,.5);text-decoration:none;font-size:13.5px;font-weight:500;transition:all .25s"
                   onmouseover="this.style.color='rgba(255,255,255,.9)';this.style.background='rgba(255,255,255,.04)'"
                   onmouseout="this.style.color='rgba(255,255,255,.5)';this.style.background='transparent'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Permintaan
                </a>

                @auth
                    <a href="{{ route('orders.index') }}" class="nav-link" style="display:flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;color:rgba(255,255,255,.5);text-decoration:none;font-size:13.5px;font-weight:500;transition:all .25s"
                       onmouseover="this.style.color='rgba(255,255,255,.9)';this.style.background='rgba(255,255,255,.04)'"
                       onmouseout="this.style.color='rgba(255,255,255,.5)';this.style.background='transparent'">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Status Order
                    </a>

                    <!-- User Dropdown -->
                    <div style="position:relative;margin-left:10px" @click.away="userMenu = false">
                        <button @click="userMenu = !userMenu"
                                style="display:flex;align-items:center;gap:8px;padding:6px 12px 6px 6px;border-radius:12px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);cursor:pointer;transition:all .25s;font-family:inherit"
                                onmouseover="this.style.background='rgba(255,255,255,.07)';this.style.borderColor='rgba(255,255,255,.1)'"
                                onmouseout="if(!userMenu){this.style.background='rgba(255,255,255,.04)';this.style.borderColor='rgba(255,255,255,.06)'}">

                            <div style="width:30px;height:30px;border-radius:9px;background:linear-gradient(135deg,#c9a227,#a88520);display:flex;align-items:center;justify-content:center;color:#0c0c18;font-size:12px;font-weight:700;flex-shrink:0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span style="font-size:13px;font-weight:500;color:rgba(255,255,255,.7)" class="hidden lg:inline">{{ Auth::user()->username }}</span>
                            <svg width="14" height="14" fill="none" stroke="rgba(255,255,255,.35)" viewBox="0 0 24 24" stroke-width="2" style="transition:transform .25s" :style="userMenu ? 'transform:rotate(180deg)' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="userMenu"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             style="position:absolute;right:0;top:calc(100% + 8px);width:240px;border-radius:16px;background:rgba(14,14,30,.92);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.06);box-shadow:0 16px 48px rgba(0,0,0,.4);padding:6px;z-index:100">

                            <!-- User info -->
                            <div style="padding:12px 14px;border-bottom:1px solid rgba(255,255,255,.04);margin-bottom:4px">
                                <div style="font-size:13.5px;font-weight:600;color:rgba(255,255,255,.85)">{{ Auth::user()->name }}</div>
                                <div style="font-size:11.5px;color:rgba(255,255,255,.25);margin-top:1px">{{ Auth::user()->email }}</div>
                            </div>

                            <a href="{{ route('dashboard') }}" class="dd-item" style="display:flex;align-items:center;gap:10px;padding:9px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:13px;font-weight:450;transition:all .2s"
                               onmouseover="this.style.color='rgba(255,255,255,.9)';this.style.background='rgba(255,255,255,.04)'"
                               onmouseout="this.style.color='rgba(255,255,255,.55)';this.style.background='transparent'">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                Dashboard
                            </a>

                            <a href="{{ route('trips.my') }}" class="dd-item" style="display:flex;align-items:center;gap:10px;padding:9px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:13px;font-weight:450;transition:all .2s"
                               onmouseover="this.style.color='rgba(255,255,255,.9)';this.style.background='rgba(255,255,255,.04)'"
                               onmouseout="this.style.color='rgba(255,255,255,.55)';this.style.background='transparent'">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Perjalanan Saya
                            </a>

                            <a href="{{ route('requests.my') }}" class="dd-item" style="display:flex;align-items:center;gap:10px;padding:9px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:13px;font-weight:450;transition:all .2s"
                               onmouseover="this.style.color='rgba(255,255,255,.9)';this.style.background='rgba(255,255,255,.04)'"
                               onmouseout="this.style.color='rgba(255,255,255,.55)';this.style.background='transparent'">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                Permintaan Saya
                            </a>

                            <a href="{{ route('orders.index') }}" class="dd-item" style="display:flex;align-items:center;gap:10px;padding:9px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:13px;font-weight:450;transition:all .2s"
                               onmouseover="this.style.color='rgba(255,255,255,.9)';this.style.background='rgba(255,255,255,.04)'"
                               onmouseout="this.style.color='rgba(255,255,255,.55)';this.style.background='transparent'">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                Order Saya
                            </a>

                            <a href="{{ route('profile.edit') }}" class="dd-item" style="display:flex;align-items:center;gap:10px;padding:9px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:13px;font-weight:450;transition:all .2s"
                               onmouseover="this.style.color='rgba(255,255,255,.9)';this.style.background='rgba(255,255,255,.04)'"
                               onmouseout="this.style.color='rgba(255,255,255,.55)';this.style.background='transparent'">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Pengaturan
                            </a>

                            @if(Auth::user()->is_admin)
                                <div style="height:1px;background:rgba(255,255,255,.04);margin:4px 10px"></div>
                                <a href="{{ route('admin.dashboard') }}" style="display:flex;align-items:center;gap:10px;padding:9px 14px;border-radius:10px;color:rgba(201,162,39,.7);text-decoration:none;font-size:13px;font-weight:550;transition:all .2s"
                                   onmouseover="this.style.color='rgba(201,162,39,1)';this.style.background='rgba(201,162,39,.06)'"
                                   onmouseout="this.style.color='rgba(201,162,39,.7)';this.style.background='transparent'">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Admin Panel
                                </a>
                            @endif

                            <div style="height:1px;background:rgba(255,255,255,.04);margin:4px 10px"></div>

                            <form method="POST" action="{{ route('logout') }}" style="display:block">
                                @csrf
                                <button type="submit" style="display:flex;align-items:center;gap:10px;padding:9px 14px;border-radius:10px;color:rgba(252,165,165,.7);background:none;border:none;cursor:pointer;font-size:13px;font-weight:450;width:100%;transition:all .2s;font-family:inherit"
                                        onmouseover="this.style.color='rgba(252,165,165,1)';this.style.background='rgba(252,165,165,.06)'"
                                        onmouseout="this.style.color='rgba(252,165,165,.7)';this.style.background='transparent'">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest buttons -->
                    <div style="display:flex;align-items:center;gap:8px;margin-left:12px">
                        <a href="{{ route('login') }}" style="padding:8px 18px;color:rgba(255,255,255,.5);text-decoration:none;font-size:13.5px;font-weight:500;border-radius:10px;transition:all .25s"
                           onmouseover="this.style.color='rgba(255,255,255,.9)'"
                           onmouseout="this.style.color='rgba(255,255,255,.5)'">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" style="padding:8px 20px;background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);background-size:200% 200%;color:#0c0c18;text-decoration:none;font-size:13.5px;font-weight:650;border-radius:10px;transition:all .3s;box-shadow:0 2px 14px rgba(201,162,39,.12),inset 0 1px 0 rgba(255,255,255,.18)"
                           onmouseover="this.style.backgroundPosition='100% 100%';this.style.boxShadow='0 4px 20px rgba(201,162,39,.22),inset 0 1px 0 rgba(255,255,255,.22)';this.style.transform='translateY(-1px)'"
                           onmouseout="this.style.backgroundPosition='0% 0%';this.style.boxShadow='0 2px 14px rgba(201,162,39,.12),inset 0 1px 0 rgba(255,255,255,.18)';this.style.transform='translateY(0)'">
                            Daftar Gratis
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile hamburger -->
            <button @click="open = !open" class="md:hidden" style="padding:8px;background:none;border:none;cursor:pointer;color:rgba(255,255,255,.6);transition:color .2s"
                    onmouseover="this.style.color='rgba(255,255,255,.9)'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2 scale-[.98]"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-2 scale-[.98]"
             class="md:hidden"
             style="background:rgba(14,14,30,.92);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.06);border-radius:18px;margin-top:10px;padding:8px;box-shadow:0 16px 48px rgba(0,0,0,.4)">

            <a href="{{ route('home') }}" class="mob-link" style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;color:rgba(255,255,255,.6);text-decoration:none;font-size:14px;font-weight:450;transition:all .2s"
               onmouseover="this.style.color='rgba(255,255,255,.95)';this.style.background='rgba(255,255,255,.04)'"
               onmouseout="this.style.color='rgba(255,255,255,.6)';this.style.background='transparent'"
               @click="open=false">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                Beranda
            </a>

            <a href="{{ route('trips.index') }}" class="mob-link" style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;color:rgba(255,255,255,.6);text-decoration:none;font-size:14px;font-weight:450;transition:all .2s"
               onmouseover="this.style.color='rgba(255,255,255,.95)';this.style.background='rgba(255,255,255,.04)'"
               onmouseout="this.style.color='rgba(255,255,255,.6)';this.style.background='transparent'"
               @click="open=false">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Perjalanan
            </a>

            <a href="{{ route('requests.index') }}" class="mob-link" style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;color:rgba(255,255,255,.6);text-decoration:none;font-size:14px;font-weight:450;transition:all .2s"
               onmouseover="this.style.color='rgba(255,255,255,.95)';this.style.background='rgba(255,255,255,.04)'"
               onmouseout="this.style.color='rgba(255,255,255,.6)';this.style.background='transparent'"
               @click="open=false">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Permintaan
            </a>

            @auth
                <a href="{{ route('orders.index') }}" style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;color:rgba(255,255,255,.6);text-decoration:none;font-size:14px;font-weight:450;transition:all .2s"
                   onmouseover="this.style.color='rgba(255,255,255,.95)';this.style.background='rgba(255,255,255,.04)'"
                   onmouseout="this.style.color='rgba(255,255,255,.6)';this.style.background='transparent'"
                   @click="open=false">
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Order
                </a>
                <a href="{{ route('dashboard') }}" style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;color:rgba(255,255,255,.6);text-decoration:none;font-size:14px;font-weight:450;transition:all .2s"
                   onmouseover="this.style.color='rgba(255,255,255,.95)';this.style.background='rgba(255,255,255,.04)'"
                   onmouseout="this.style.color='rgba(255,255,255,.6)';this.style.background='transparent'"
                   @click="open=false">
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    Dashboard
                </a>
                @if(Auth::user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;color:rgba(201,162,39,.7);text-decoration:none;font-size:14px;font-weight:550;transition:all .2s"
                       onmouseover="this.style.color='rgba(201,162,39,1)';this.style.background='rgba(201,162,39,.06)'"
                       onmouseout="this.style.color='rgba(201,162,39,.7)';this.style.background='transparent'"
                       @click="open=false">
                        <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Admin Panel
                    </a>
                @endif
                <div style="height:1px;background:rgba(255,255,255,.04);margin:4px 8px"></div>
                <form method="POST" action="{{ route('logout') }}" style="display:block">
                    @csrf
                    <button type="submit" style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;color:rgba(252,165,165,.7);background:none;border:none;cursor:pointer;font-size:14px;font-weight:450;width:100%;transition:all .2s;font-family:inherit"
                            onmouseover="this.style.color='rgba(252,165,165,1)';this.style.background='rgba(252,165,165,.06)'"
                            onmouseout="this.style.color='rgba(252,165,165,.7)';this.style.background='transparent'">
                        <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            @else
                <div style="height:1px;background:rgba(255,255,255,.04);margin:4px 8px"></div>
                <a href="{{ route('login') }}" style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;color:rgba(255,255,255,.6);text-decoration:none;font-size:14px;font-weight:450;transition:all .2s"
                   onmouseover="this.style.color='rgba(255,255,255,.95)';this.style.background='rgba(255,255,255,.04)'"
                   onmouseout="this.style.color='rgba(255,255,255,.6)';this.style.background='transparent'"
                   @click="open=false">
                    Masuk
                </a>
                <a href="{{ route('register') }}" style="display:block;padding:12px 14px;border-radius:12px;background:linear-gradient(135deg,#c9a227,#b08a22,#d4b040,#c9a227);background-size:200% 200%;color:#0c0c18;text-decoration:none;font-size:14px;font-weight:650;text-align:center;transition:all .3s;box-shadow:0 2px 14px rgba(201,162,39,.12),inset 0 1px 0 rgba(255,255,255,.18)"
                   onmouseover="this.style.backgroundPosition='100% 100%'"
                   onmouseout="this.style.backgroundPosition='0% 0%'"
                   @click="open=false">
                    Daftar Gratis
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- Spacer -->
<div style="height:68px"></div>