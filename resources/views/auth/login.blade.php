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
        <div class="mb-6" x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block w-full !pr-14"
                                x-bind:type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
                <button type="button" @click="show = !show" class="absolute right-6 top-1/2 -translate-y-1/2 flex items-center text-nusarasa-dark/40 hover:text-nusarasa-dark">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
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
                    Lupa Password?
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
