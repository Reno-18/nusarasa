<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email — NusaRasa</title>
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
        </div>

        <div class="bg-white border-4 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] text-center">

            {{-- Icon --}}
            <div class="w-20 h-20 bg-nusarasa-yellow border-4 border-nusarasa-dark rounded-full flex items-center justify-center mx-auto mb-6 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <svg class="w-10 h-10 text-nusarasa-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark mb-3">Verifikasi Email</h1>
            <p class="text-sm font-bold text-nusarasa-dark/60 mb-2 leading-relaxed">
                Terima kasih sudah mendaftar! Kami telah mengirimkan link verifikasi ke email Anda.
            </p>
            <p class="text-xs font-bold text-nusarasa-dark/40 uppercase tracking-wider mb-6">
                Cek folder inbox atau spam Anda.
            </p>

            {{-- Status: Email sent confirmation --}}
            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-3 bg-green-50 border-2 border-green-400 rounded-2xl text-xs font-bold text-green-700 uppercase tracking-wider">
                    ✅ Link verifikasi baru telah dikirim ke email Anda!
                </div>
            @endif

            {{-- Resend form --}}
            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit"
                        class="w-full py-4 bg-nusarasa-dark text-white font-black text-xs uppercase tracking-widest rounded-pill border-2 border-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all">
                    📧 Kirim Ulang Email Verifikasi
                </button>
            </form>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-xs font-black uppercase tracking-widest text-nusarasa-dark/40 hover:text-nusarasa-dark transition-colors">
                    Logout
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <p class="text-xs font-bold text-nusarasa-dark/40 uppercase tracking-wider">
                Email dari <span class="text-nusarasa-dark">nusarasauniba@gmail.com</span>
            </p>
        </div>
    </div>
</body>
</html>
