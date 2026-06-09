<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password — NusaRasa</title>
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
            <h1 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark mt-4 mb-2">Lupa Password?</h1>
            <p class="text-sm font-bold text-nusarasa-dark/50 uppercase tracking-wider">Masukkan email Anda dan kami akan mengirimkan link reset password.</p>
        </div>

        {{-- Status --}}
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border-2 border-green-500 rounded-2xl text-sm font-bold text-green-700 uppercase tracking-wider">
                ✅ {{ session('status') }}
            </div>
        @endif

        <div class="bg-white border-4 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-6">
                    <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/50 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           required autofocus
                           class="w-full px-5 py-4 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl font-bold text-nusarasa-dark focus:outline-none focus:ring-0 focus:border-nusarasa-dark placeholder-nusarasa-dark/30"
                           placeholder="nama@gmail.com">
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-red-600 uppercase tracking-wider">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-4 bg-nusarasa-dark text-white font-black text-xs uppercase tracking-widest rounded-pill border-2 border-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all">
                    Kirim Link Reset Password
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-widest text-nusarasa-dark/50 hover:text-nusarasa-dark transition-colors">
                ← Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>
