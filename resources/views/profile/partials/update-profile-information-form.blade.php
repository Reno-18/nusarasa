<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Avatar Upload with Live Preview -->
        <div x-data="{
            previewUrl: '{{ $user->avatar_url ?? '' }}',
            handleFile(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.previewUrl = URL.createObjectURL(file);
            }
        }">
            <x-input-label for="avatar" :value="__('Foto Profil (Avatar)')" />
            <div class="mt-3 flex items-center gap-5">
                <!-- Avatar Preview -->
                <div class="relative flex-shrink-0">
                    <template x-if="previewUrl">
                        <img :src="previewUrl" alt="Preview Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-gray-300 shadow">
                    </template>
                    <template x-if="!previewUrl">
                        <div class="w-20 h-20 rounded-full bg-gray-100 border-2 border-gray-300 flex items-center justify-center text-2xl font-bold text-gray-600 shadow">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                    </template>
                    <!-- Camera icon overlay -->
                    <label for="avatar" class="absolute bottom-0 right-0 w-7 h-7 bg-gray-800 rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </label>
                </div>

                <div class="flex-1">
                    <input type="file" id="avatar" name="avatar" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                        @change="handleFile($event)">
                    <p class="mt-1 text-xs text-gray-400">PNG, JPG, WEBP — Maks. 2MB. Klik ikon kamera atau kotak di atas.</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <!-- Badge Selector with Alpine.js -->
        <div x-data="{ selected: '{{ $user->active_badge_id ?? '' }}' }">
            <x-input-label :value="__('Lencana Aktif (Pilih untuk ditampilkan di profil)')" />

            @if(isset($unlockedBadges) && $unlockedBadges->count() > 0)
                <p class="mt-1 text-xs text-gray-400">Klik lencana untuk memilih mana yang ingin ditampilkan di sebelah nama Anda.</p>
                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <!-- No Badge Option -->
                    <button type="button"
                        @click="selected = ''"
                        :class="selected === '' ? 'border-indigo-500 bg-indigo-50 shadow-md scale-105' : 'border-gray-200 bg-white hover:bg-gray-50'"
                        class="rounded-xl border-2 px-4 py-3 text-center transition-all duration-150 focus:outline-none">
                        <div class="text-2xl mb-1">🚫</div>
                        <div class="text-xs font-bold text-gray-500">Tanpa Lencana</div>
                    </button>

                    @foreach($unlockedBadges as $badge)
                        <button type="button"
                            @click="selected = '{{ $badge->id }}'"
                            :class="selected === '{{ $badge->id }}' ? 'border-indigo-500 bg-indigo-50 shadow-md scale-105' : 'border-gray-200 bg-white hover:bg-gray-50'"
                            class="rounded-xl border-2 px-4 py-3 text-center transition-all duration-150 focus:outline-none">
                            <div class="text-2xl mb-1">{{ $badge->icon }}</div>
                            <div class="text-xs font-bold text-gray-700 line-clamp-1">{{ $badge->name }}</div>
                        </button>
                    @endforeach
                </div>
            @else
                <div class="mt-3 p-4 rounded-xl bg-gray-50 border border-dashed border-gray-200 text-sm text-gray-500 italic">
                    Belum ada lencana. Aktif membuat resep atau rencana makan untuk mendapatkan lencana pertama Anda!
                </div>
            @endif

            <!-- Hidden input to submit selected badge id -->
            <input type="hidden" name="active_badge_id" :value="selected">
            <x-input-error class="mt-2" :messages="$errors->get('active_badge_id')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
