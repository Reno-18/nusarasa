<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NusaRasa') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- jQuery & Chart.js -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        [data-aos] { pointer-events: none; }
        .aos-animate { pointer-events: auto; }
    </style>
</head>
<body class="font-sans antialiased bg-nusarasa-cream text-nusarasa-dark">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 w-full print:hidden"
             style="background-color: #F0EBE0; border-bottom: 1px solid rgba(28,18,8,0.08);"
             x-data="{ mobileOpen: false, dropdownOpen: false }">

            <!-- Top Bar -->
            <div class="flex flex-row items-center justify-between w-full box-border"
                 style="height: 64px; padding: 0 1.25rem;">

                @php
                    $allNavLinks = [
                        ['route' => 'recipes.index',         'label' => 'Jelajahi'],
                        ['route' => 'chefs.index',           'label' => 'Daftar Chef'],
                        ['route' => 'leaderboard.chefs',     'label' => 'Leaderboard'],
                        ['route' => 'recipes.by-ingredients','label' => 'Masak dari Bahan'],
                    ];
                    $authNavLinks = [
                        ['route' => 'saved.index',           'label' => 'Favorit'],
                        ['route' => 'meal-plan.index',       'label' => 'Rencana Makan'],
                    ];
                @endphp
                @auth
                    @php $allNavLinks = array_merge($allNavLinks, $authNavLinks); @endphp
                @endauth

                {{-- Logo --}}
                <a href="/"
                   style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif; font-weight: 900; font-size: 22px; color: #1C1208; letter-spacing: -0.02em; flex-shrink: 0;"
                   class="select-none hover:opacity-80 transition-opacity uppercase">
                    NUSARASA
                </a>

                {{-- Nav Links — Desktop only --}}
                <div class="hidden lg:flex flex-1 items-center justify-center gap-1 overflow-x-auto h-full" style="scrollbar-width: none;">
                    @foreach($allNavLinks as $link)
                        @php $active = request()->routeIs($link['route'] . '*'); @endphp
                        <a href="{{ route($link['route']) }}"
                           class="transition-all duration-200 whitespace-nowrap flex items-center h-16 px-4 hover:text-[#1C1208] hover:bg-[rgba(28,18,8,0.06)]"
                           style="font-family: 'DM Sans', sans-serif; font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em;
                                  {{ $active
                                     ? 'color: #1C1208; font-weight: 700; border-bottom: 2px solid #1C1208;'
                                     : 'color: #6B5B47; font-weight: 600; border-bottom: 2px solid transparent;' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>

                {{-- Right Controls — Desktop --}}
                <div class="hidden lg:flex items-center gap-3 shrink-0">
                    @auth
                        @php $navUserPoints = auth()->user()->points; @endphp
                        @if($navUserPoints)
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center transition-transform hover:scale-105"
                           style="background: #F5DFA0; border: 1.5px solid #D4A830; color: #7A5A08; font-family: 'DM Sans', sans-serif; font-weight: 700; border-radius: 20px; padding: 5px 12px; font-size: 12px;">
                            <span class="mr-1">★</span> {{ number_format($navUserPoints->points) }}
                        </a>
                        @endif

                        <div class="relative" x-data="{ dropdownOpen: false }">
                            <button @click="dropdownOpen = !dropdownOpen"
                                    @click.away="dropdownOpen = false"
                                    class="flex items-center gap-1.5 transition-colors hover:bg-white/50"
                                    style="border: 1.5px solid #C4B8A4; border-radius: 24px; padding: 6px 14px;">
                                <span style="font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 12.5px; color: #1C1208; text-transform: uppercase;" class="max-w-[150px] truncate">
                                    {{ Auth::user()->name }}
                                </span>
                                <span style="color: #6B5B47; font-size: 12px;" :class="{'rotate-180': dropdownOpen}" class="transition-transform duration-200">▾</span>
                            </button>

                            <div x-show="dropdownOpen"
                                 x-transition
                                 style="display:none;"
                                 class="absolute right-0 mt-2 w-56 bg-white border-2 border-nusarasa-dark rounded-2xl shadow-[5px_5px_0px_0px_rgba(26,26,26,1)] overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-nusarasa-dark/10">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-nusarasa-dark/40 mb-0.5">Akun Saya</p>
                                    <p class="text-xs font-black text-nusarasa-dark truncate">{{ Auth::user()->name }}</p>
                                </div>
                                <div class="flex flex-col">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-nusarasa-dark hover:bg-nusarasa-cream uppercase tracking-wider transition-colors whitespace-nowrap border-b border-nusarasa-dark/10">
                                        <span class="text-base">👤</span> Profil Saya
                                    </a>

                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-white bg-red-500 hover:bg-red-600 uppercase tracking-wider transition-colors whitespace-nowrap border-b border-nusarasa-dark/10">
                                            <span class="text-base">👮</span> Kepala Chef (Admin)
                                        </a>
                                    @endif

                                    @if(auth()->user()->isChef() || auth()->user()->isAdmin())
                                        <a href="{{ route('chef.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-white bg-nusarasa-dark hover:bg-nusarasa-dark/80 uppercase tracking-wider transition-colors whitespace-nowrap border-b border-nusarasa-dark/10">
                                            <span class="text-base">👨‍🍳</span> Dapur Kreatif
                                        </a>
                                    @else
                                        <a href="{{ route('user.recipes.create') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-nusarasa-dark hover:bg-nusarasa-yellow uppercase tracking-wider transition-colors whitespace-nowrap border-b border-nusarasa-dark/10">
                                            <span class="text-base">📝</span> Bagikan Resep
                                        </a>
                                    @endif

                                    <div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-3 text-xs font-bold text-red-600 hover:bg-red-50 uppercase tracking-wider transition-colors whitespace-nowrap">
                                                <span class="text-base">🚪</span> Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest text-nusarasa-dark/50 hover:text-nusarasa-dark transition-colors" style="font-family: 'DM Sans', sans-serif;">Masuk</a>
                        <a href="{{ route('register') }}"
                           class="transition-all hover:scale-105"
                           style="background: #1C1208; color: #FFFFFF; font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 12.5px; text-transform: uppercase; border-radius: 24px; padding: 6px 16px;">
                            Daftar
                        </a>
                    @endauth
                </div>

                {{-- Mobile Right: Points badge + Hamburger --}}
                <div class="flex lg:hidden items-center gap-2">
                    @auth
                        @php $navUserPoints = auth()->user()->points; @endphp
                        @if($navUserPoints)
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center"
                           style="background: #F5DFA0; border: 1.5px solid #D4A830; color: #7A5A08; font-family: 'DM Sans', sans-serif; font-weight: 700; border-radius: 20px; padding: 4px 10px; font-size: 11px;">
                            <span class="mr-1">★</span> {{ number_format($navUserPoints->points) }}
                        </a>
                        @endif
                    @endauth

                    <button @click="mobileOpen = !mobileOpen"
                            class="flex items-center justify-center w-10 h-10 rounded-xl border-2 border-nusarasa-dark/20 hover:border-nusarasa-dark hover:bg-white/60 transition-all"
                            aria-label="Toggle Menu">
                        <svg x-show="!mobileOpen" class="w-5 h-5 text-nusarasa-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileOpen" class="w-5 h-5 text-nusarasa-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Slide-down Menu --}}
            <div x-show="mobileOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 style="display:none;"
                 class="lg:hidden border-t-2 border-nusarasa-dark/10 bg-[#F0EBE0] pb-2">

                {{-- Nav links --}}
                <div class="px-4 pt-3 pb-2 space-y-1">
                    @foreach($allNavLinks as $link)
                        @php $active = request()->routeIs($link['route'] . '*'); @endphp
                        <a href="{{ route($link['route']) }}"
                           @click="mobileOpen = false"
                           class="flex items-center px-4 py-3 rounded-2xl font-bold text-sm uppercase tracking-wider transition-all {{ $active ? 'bg-nusarasa-dark text-white' : 'text-nusarasa-dark hover:bg-white/70' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>

                {{-- Auth section --}}
                <div class="px-4 pb-4 border-t border-nusarasa-dark/10 pt-3">
                    @auth
                        <div class="flex items-center gap-3 px-4 py-3 mb-2 bg-white rounded-2xl border border-nusarasa-dark/10">
                            <div class="w-9 h-9 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-full flex items-center justify-center font-black text-base flex-shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/40">Akun Saya</p>
                                <p class="text-sm font-black text-nusarasa-dark truncate">{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <a href="{{ route('profile.edit') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-nusarasa-dark hover:bg-white/70 uppercase tracking-wider transition-all">
                                <span>👤</span> Profil Saya
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 uppercase tracking-wider transition-all">
                                    <span>👮</span> Kepala Chef (Admin)
                                </a>
                            @endif
                            @if(auth()->user()->isChef() || auth()->user()->isAdmin())
                                <a href="{{ route('chef.dashboard') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-white bg-nusarasa-dark hover:bg-nusarasa-dark/80 uppercase tracking-wider transition-all">
                                    <span>👨‍🍳</span> Dapur Kreatif
                                </a>
                            @else
                                <a href="{{ route('user.recipes.create') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-nusarasa-dark hover:bg-nusarasa-yellow uppercase tracking-wider transition-all">
                                    <span>📝</span> Bagikan Resep
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl text-sm font-bold text-red-600 hover:bg-red-50 uppercase tracking-wider transition-all">
                                    <span>🚪</span> Keluar
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex gap-3">
                            <a href="{{ route('login') }}" class="flex-1 text-center py-3 border-2 border-nusarasa-dark rounded-2xl font-black text-sm uppercase tracking-wider hover:bg-white/70 transition-all">Masuk</a>
                            <a href="{{ route('register') }}" class="flex-1 text-center py-3 bg-nusarasa-dark text-white rounded-2xl font-black text-sm uppercase tracking-wider hover:opacity-80 transition-all">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>


        <!-- Flash Messages -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" data-aos="fade-down">
                <div class="bg-green-100 border-2 border-green-500 text-green-700 px-6 py-4 rounded-2xl font-bold" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" data-aos="fade-down">
                <div class="bg-red-100 border-2 border-red-500 text-red-700 px-6 py-4 rounded-2xl font-bold" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Gamification Toast Notifications -->
        @if(session('gamification_points') || session('gamification_badges'))
            <div id="gamification-toast" class="fixed bottom-8 right-4 sm:right-8 z-[999] flex flex-col gap-4 max-w-sm w-full px-4 sm:px-0" data-aos="fade-left">
                @if(session('gamification_points'))
                    <div class="bg-nusarasa-yellow border-4 border-nusarasa-dark rounded-[1.5rem] p-5 shadow-[8px_8px_0px_0px_rgba(26,26,26,1)] flex items-center gap-4 gamification-toast-item transform hover:scale-[1.02] transition-transform duration-200">
                        <div class="w-12 h-12 bg-white border-2 border-nusarasa-dark rounded-xl flex items-center justify-center text-3xl shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] animate-bounce">
                            ⭐
                        </div>
                        <div class="flex-1">
                            <p class="font-black text-nusarasa-dark text-xs uppercase tracking-wider">Perolehan Poin!</p>
                            <p class="font-black text-nusarasa-dark text-xl uppercase tracking-tighter">+{{ session('gamification_points') }} POIN</p>
                            <p class="font-bold text-nusarasa-dark/60 text-[10px] uppercase tracking-wide">Level up menantimu!</p>
                        </div>
                        <button onclick="this.closest('.gamification-toast-item').remove()" class="text-nusarasa-dark/45 hover:text-nusarasa-dark font-black text-sm p-1 select-none">✕</button>
                    </div>
                @endif
                @if(session('gamification_badges'))
                    @foreach(session('gamification_badges') as $badgeName)
                        <div class="bg-nusarasa-purple border-4 border-nusarasa-dark rounded-[1.5rem] p-5 shadow-[8px_8px_0px_0px_rgba(26,26,26,1)] flex items-center gap-4 gamification-toast-item transform hover:scale-[1.02] transition-transform duration-200">
                            <div class="w-12 h-12 bg-white border-2 border-nusarasa-dark rounded-xl flex items-center justify-center text-3xl shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] animate-pulse">
                                🏅
                            </div>
                            <div class="flex-1">
                                <p class="font-black text-nusarasa-dark text-xs uppercase tracking-wider text-purple-800">LENCANA DIKUNCI!</p>
                                <p class="font-black text-nusarasa-dark text-lg uppercase tracking-tight leading-tight">{{ $badgeName }}</p>
                                <p class="font-bold text-nusarasa-dark/60 text-[10px] uppercase tracking-wide">Pencapaian baru diraih!</p>
                            </div>
                            <button onclick="this.closest('.gamification-toast-item').remove()" class="text-nusarasa-dark/45 hover:text-nusarasa-dark font-black text-sm p-1 select-none">✕</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <script>
                setTimeout(function() {
                    document.querySelectorAll('.gamification-toast-item').forEach(el => {
                        el.style.transition = 'all 0.5s ease-in-out';
                        el.style.opacity = '0';
                        el.style.transform = 'translateX(50px)';
                        setTimeout(() => el.remove(), 500);
                    });
                }, 5000);
            </script>
        @endif

        <!-- Page Content -->
        <main class="py-8">
            {{ $slot }}
        </main>
        
        <!-- Footer -->
        <x-footer />
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        function toggleDropdown() {
            document.getElementById('dropdown').classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('button')) {
                var dropdown = document.getElementById("dropdown");
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            }
        }
    </script>

    <script>
        // Global AJAX setup — send CSRF token and session cookies with every request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            xhrFields: {
                withCredentials: true
            }
        });
    </script>

    <script src="{{ asset('js/nusarasa.js') }}"></script>
    @stack('scripts')
</body>
</html>
