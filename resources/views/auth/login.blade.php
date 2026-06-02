<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black font-display uppercase tracking-tighter">Welcome back</h2>
        <p class="font-bold opacity-40 text-sm uppercase tracking-widest mt-2">Enter your credentials</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="chef@nusarasa.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-8">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-full border-2 border-nusarasa-dark text-nusarasa-dark focus:ring-0" name="remember">
                <span class="ms-3 text-xs font-black uppercase tracking-widest opacity-60">{{ __('Stay signed in') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-nusarasa-dark transition" href="{{ route('password.request') }}">
                    {{ __('Lost access?') }}
                </a>
            @endif
        </div>

        <div class="flex flex-col gap-4">
            <x-primary-button class="w-full justify-center">
                {{ __('Authenticate') }}
            </x-primary-button>
            
            <p class="text-center text-xs font-black uppercase tracking-widest opacity-40 mt-4">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-nusarasa-dark hover:underline underline-offset-4">Join now</a>
            </p>
        </div>
    </form>
</x-guest-layout>
