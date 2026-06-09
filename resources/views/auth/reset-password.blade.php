<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password — NusaRasa</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-nusarasa-cream flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-10">
            <a href="{{ route('home') }}"
               style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 900; font-size: 28px; color: #1C1208; letter-spacing: -0.02em;"
               class="uppercase hover:opacity-70 transition-opacity">
                NUSARASA
            </a>
            <h1 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark mt-4 mb-2">Buat Password Baru</h1>
            <p class="text-sm font-bold text-nusarasa-dark/50 uppercase tracking-wider">Masukkan password baru Anda di bawah ini.</p>
        </div>

        <div class="bg-white border-4 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/50 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
                           required autofocus autocomplete="username"
                           class="w-full px-5 py-4 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl font-bold text-nusarasa-dark focus:outline-none focus:ring-0 focus:border-nusarasa-dark placeholder-nusarasa-dark/30"
                           placeholder="nama@gmail.com">
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-red-600 uppercase tracking-wider">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5 relative" x-data="{ show: false }">
                    <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/50 mb-2">Password Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                               required autocomplete="new-password"
                               class="w-full px-5 py-4 pr-14 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl font-bold text-nusarasa-dark focus:outline-none focus:ring-0 focus:border-nusarasa-dark placeholder-nusarasa-dark/30"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-nusarasa-dark/40 hover:text-nusarasa-dark transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs font-bold text-red-600 uppercase tracking-wider">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6 relative" x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/50 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                               required autocomplete="new-password"
                               class="w-full px-5 py-4 pr-14 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl font-bold text-nusarasa-dark focus:outline-none focus:ring-0 focus:border-nusarasa-dark placeholder-nusarasa-dark/30"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-nusarasa-dark/40 hover:text-nusarasa-dark transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-xs font-bold text-red-600 uppercase tracking-wider">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-4 bg-nusarasa-dark text-white font-black text-xs uppercase tracking-widest rounded-pill border-2 border-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all">
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
