<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NusaRasa') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
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
        <nav class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-12">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="text-3xl font-extrabold font-display tracking-tight text-nusarasa-dark uppercase">
                                NusaRasa
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-10 sm:flex">
                            <a href="{{ route('recipes.index') }}" class="text-sm font-bold uppercase tracking-widest hover:text-gray-600 transition {{ request()->routeIs('recipes.*') ? 'text-nusarasa-dark border-b-2 border-nusarasa-dark' : 'text-gray-500' }}">
                                Jelajahi
                            </a>
                            <a href="{{ route('chefs.index') }}" class="text-sm font-bold uppercase tracking-widest hover:text-gray-600 transition {{ request()->routeIs('chefs.*') ? 'text-nusarasa-dark border-b-2 border-nusarasa-dark' : 'text-gray-500' }}">
                                Daftar Chef
                            </a>
                            
                            @auth
                                <a href="{{ route('saved.index') }}" class="text-sm font-bold uppercase tracking-widest hover:text-gray-600 transition {{ request()->routeIs('saved.*') ? 'text-nusarasa-dark border-b-2 border-nusarasa-dark' : 'text-gray-500' }}">
                                    Favorit
                                </a>
                                <a href="{{ route('meal-plan.index') }}" class="text-sm font-bold uppercase tracking-widest hover:text-gray-600 transition {{ request()->routeIs('meal-plan.*') ? 'text-nusarasa-dark border-b-2 border-nusarasa-dark' : 'text-gray-500' }}">
                                    Rencana Makan
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="hidden sm:flex sm:items-center">
                        @auth
                            <div class="relative">
                                <button class="px-6 py-2 border-2 border-nusarasa-dark rounded-pill font-bold text-sm uppercase tracking-widest hover:bg-nusarasa-dark hover:text-white transition flex items-center gap-2" onclick="toggleDropdown()">
                                    {{ Auth::user()->name }}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div id="dropdown" class="hidden absolute right-0 mt-3 w-48 bg-white border-2 border-nusarasa-dark rounded-2xl shadow-xl py-2 z-50 overflow-hidden">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-bold text-nusarasa-dark hover:bg-nusarasa-cream uppercase">Profil</a>
                                    
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm font-bold text-nusarasa-dark hover:bg-nusarasa-cream uppercase text-orange-600">Kepala Chef</a>
                                    @endif

                                    @if(auth()->user()->isChef())
                                        <a href="{{ route('chef.dashboard') }}" class="block px-4 py-2 text-sm font-bold text-nusarasa-dark hover:bg-nusarasa-cream uppercase">Chef Dashboard</a>
                                    @endif

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 uppercase">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-6">
                                <a href="{{ route('login') }}" class="text-sm font-bold uppercase tracking-widest text-gray-500 hover:text-nusarasa-dark transition">Masuk</a>
                                <a href="{{ route('register') }}" class="px-8 py-2 bg-nusarasa-dark text-white rounded-pill font-bold text-sm uppercase tracking-widest hover:opacity-80 transition">
                                    Daftar
                                </a>
                            </div>
                        @endauth
                    </div>
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
