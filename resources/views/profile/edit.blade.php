<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Pengaturan <span class="text-nusarasa-yellow">Profil</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest">Kelola identitas dan keamanan akunmu</p>
        </div>

        <div class="grid grid-cols-1 gap-12 max-w-4xl mx-auto">
            <!-- Information Section -->
            <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-12 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-none transition-all" data-aos="fade-up">
                <div class="flex items-center gap-4 mb-10 pb-6 border-b-2 border-nusarasa-dark/10">
                    <div class="w-12 h-12 bg-nusarasa-pink border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        👤
                    </div>
                    <div>
                        <h2 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Informasi Profil</h2>
                        <p class="text-sm font-bold opacity-40 uppercase tracking-widest">Data diri dan alamat email</p>
                    </div>
                </div>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Section -->
            <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-12 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-none transition-all" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center gap-4 mb-10 pb-6 border-b-2 border-nusarasa-dark/10">
                    <div class="w-12 h-12 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-white">
                        🔐
                    </div>
                    <div>
                        <h2 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Keamanan Akun</h2>
                        <p class="text-sm font-bold opacity-40 uppercase tracking-widest">Ubah kata sandi berkala</p>
                    </div>
                </div>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Gamification Stats Section -->
            @php
                $userPoints = auth()->user()->points;
                $userBadges = auth()->user()->badges()->withPivot(['unlocked_at'])->orderByPivot('unlocked_at', 'desc')->get();
                $userStreak = auth()->user()->mealPlanStreak;
            @endphp
            <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-12 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-none transition-all" data-aos="fade-up" data-aos-delay="150">
                <div class="flex items-center gap-4 mb-10 pb-6 border-b-2 border-nusarasa-dark/10">
                    <div class="w-12 h-12 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">⭐</div>
                    <div>
                        <h2 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Pencapaian & Poin</h2>
                        <p class="text-sm font-bold opacity-40 uppercase tracking-widest">Level, poin, streak & lencana yang sudah diraih</p>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                    <div class="bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-2xl p-4 text-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <div class="text-2xl mb-1">⭐</div>
                        <p class="text-3xl font-black text-nusarasa-dark">{{ number_format($userPoints?->points ?? 0) }}</p>
                        <p class="text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/60 mt-1">Total Poin</p>
                    </div>
                    <div class="bg-nusarasa-purple border-2 border-nusarasa-dark rounded-2xl p-4 text-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <div class="text-2xl mb-1">🏆</div>
                        <p class="text-xl font-black text-white">{{ $userPoints?->level ?? 'Pemula' }}</p>
                        <p class="text-[10px] font-black uppercase tracking-widest text-white/60 mt-1">Level</p>
                    </div>
                    <div class="bg-nusarasa-pink border-2 border-nusarasa-dark rounded-2xl p-4 text-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <div class="text-2xl mb-1">🔥</div>
                        <p class="text-3xl font-black text-nusarasa-dark">{{ $userStreak?->current_streak ?? 0 }}</p>
                        <p class="text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/60 mt-1">Streak Minggu</p>
                    </div>
                    <div class="bg-green-100 border-2 border-nusarasa-dark rounded-2xl p-4 text-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <div class="text-2xl mb-1">🏅</div>
                        <p class="text-3xl font-black text-nusarasa-dark">{{ $userBadges->count() }}</p>
                        <p class="text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/60 mt-1">Lencana</p>
                    </div>
                </div>

                <!-- Badges Grid -->
                @if($userBadges->isEmpty())
                    <div class="text-center py-10 bg-nusarasa-cream/40 border-2 border-dashed border-nusarasa-dark/20 rounded-2xl">
                        <div class="text-4xl mb-2">🎯</div>
                        <p class="font-bold opacity-50 text-sm">Belum ada lencana. Terus beraktivitas di NusaRasa!</p>
                    </div>
                @else
                    <h3 class="font-black text-sm uppercase tracking-widest text-nusarasa-dark/60 mb-4">Lencana Diraih</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($userBadges as $badge)
                            <div class="flex flex-col items-center gap-2 p-4 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-center hover:-translate-y-0.5 transition-transform">
                                <span class="text-4xl">{{ $badge->icon ?? '🏅' }}</span>
                                <p class="font-black text-xs text-nusarasa-dark uppercase tracking-wide leading-tight">{{ $badge->name }}</p>
                                <p class="font-bold text-[10px] text-nusarasa-dark/40">{{ \Carbon\Carbon::parse($badge->pivot->unlocked_at)->format('d M Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Leaderboard Toggle -->
                <div class="mt-8 pt-8 border-t-2 border-nusarasa-dark/10 flex items-center justify-between">
                    <div>
                        <p class="font-black text-sm text-nusarasa-dark uppercase tracking-wide">Tampil di Leaderboard</p>
                        <p class="text-xs font-bold text-nusarasa-dark/50">Biarkan pengguna lain melihat rankingmu</p>
                    </div>
                    <form method="POST" action="{{ route('profile.leaderboard-toggle') }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-6 py-2 border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:translate-y-0.5 hover:shadow-none transition-all {{ auth()->user()->show_on_leaderboard ? 'bg-nusarasa-dark text-white' : 'bg-white text-nusarasa-dark hover:bg-nusarasa-cream' }}">
                            {{ auth()->user()->show_on_leaderboard ? '✅ Aktif' : '⬜ Nonaktif' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Danger Zone Section -->
            <div class="bg-red-50 border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-12 shadow-[12px_12px_0px_0px_rgba(239,68,68,1)] hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-none transition-all" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center gap-4 mb-10 pb-6 border-b-2 border-red-500/20">
                    <div class="w-12 h-12 bg-red-500 border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-white">
                        ⚠️
                    </div>
                    <div>
                        <h2 class="text-2xl font-black font-display uppercase tracking-tighter text-red-600">Hapus Akun</h2>
                        <p class="text-sm font-bold opacity-40 uppercase tracking-widest text-red-400">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
