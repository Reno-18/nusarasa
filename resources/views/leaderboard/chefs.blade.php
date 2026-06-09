<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            @if($role === 'chef')
            <h1 class="text-4xl sm:text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Chef <span class="text-[#6D28D9]">Terbaik</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Para pahlawan dapur dengan poin tertinggi di NusaRasa</p>
            @else
            <h1 class="text-4xl sm:text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Pengguna <span class="text-nusarasa-pink">Teraktif</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Komunitas pecinta kuliner dengan kontribusi terbanyak</p>
            @endif
        </div>

        <!-- Role Filter -->
        <div class="flex flex-wrap justify-center gap-3 mb-6" data-aos="fade-up">
            <a href="{{ route('leaderboard.chefs', ['role' => 'chef', 'period' => $period]) }}"
               class="px-8 py-3 border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest transition shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-0.5 hover:shadow-none {{ $role === 'chef' ? 'bg-nusarasa-yellow text-nusarasa-dark' : 'bg-white text-nusarasa-dark hover:bg-nusarasa-cream' }}">
                👨‍🍳 Chef
            </a>
            <a href="{{ route('leaderboard.chefs', ['role' => 'user', 'period' => $period]) }}"
               class="px-8 py-3 border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest transition shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-0.5 hover:shadow-none {{ $role === 'user' ? 'bg-nusarasa-pink text-white' : 'bg-white text-nusarasa-dark hover:bg-nusarasa-cream' }}">
                🧑‍🍳 Pengguna
            </a>
        </div>

        <!-- Period Filter -->
        <div class="flex flex-wrap justify-center gap-3 mb-12" data-aos="fade-up">
            <a href="{{ route('leaderboard.chefs', ['period' => 'all', 'role' => $role]) }}"
               class="px-8 py-3 border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest transition shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-0.5 hover:shadow-none {{ $period === 'all' ? 'bg-nusarasa-dark text-white' : 'bg-white text-nusarasa-dark hover:bg-nusarasa-cream' }}">
                🏆 Sepanjang Masa
            </a>
            <a href="{{ route('leaderboard.chefs', ['period' => 'month', 'role' => $role]) }}"
               class="px-8 py-3 border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest transition shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-0.5 hover:shadow-none {{ $period === 'month' ? 'bg-nusarasa-dark text-white' : 'bg-white text-nusarasa-dark hover:bg-nusarasa-cream' }}">
                📅 Bulan Ini
            </a>
        </div>

        <!-- Podium Top 3 -->
        @if($users->count() >= 1)
            <div class="mb-16" data-aos="fade-up">
                <div class="flex flex-col md:flex-row items-center md:items-end justify-center gap-12 md:gap-4 relative pt-12">
                    <!-- 2nd Place -->
                    @if($users->count() >= 2)
                        <div class="flex flex-col items-center order-1 md:order-1 relative z-10 mt-8 md:mt-0">
                            <div class="relative z-10 -mb-6 group">
                                <div class="w-24 h-24 rounded-full border-4 border-nusarasa-dark bg-gray-200 flex items-center justify-center font-black text-4xl text-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden transition-transform group-hover:scale-105">
                                    @if($users[1]->avatar_url)
                                        <img src="{{ $users[1]->avatar_url }}" class="w-full h-full object-cover">
                                    @else
                                        {{ mb_substr($users[1]->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-white border-2 border-nusarasa-dark text-nusarasa-dark rounded-full px-3 py-1 text-xs font-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] whitespace-nowrap">🥈 #2</div>
                            </div>
                            <div class="bg-gray-100 border-4 border-nusarasa-dark rounded-3xl px-6 pt-10 pb-5 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] text-center w-48 md:w-56 flex flex-col justify-center transform hover:-translate-y-2 transition-transform">
                                <p class="font-black text-base text-nusarasa-dark line-clamp-1 mb-1">
                                    {{ $users[1]->name }}
                                    @if($users[1]->activeBadge) <span title="{{ $users[1]->activeBadge->name }}">{{ $users[1]->activeBadge->icon }}</span> @endif
                                </p>
                                <p class="text-xs font-black text-nusarasa-dark/80 bg-white px-3 py-1 rounded-pill mx-auto mb-1 border-2 border-nusarasa-dark/10">{{ number_format($users[1]->score_display) }} PTS</p>
                            </div>
                        </div>
                    @endif

                    <!-- 1st Place -->
                    <div class="flex flex-col items-center order-0 md:order-2 relative z-20">
                        <div class="relative z-10 -mb-8 group">
                            <div class="text-5xl absolute -top-8 left-1/2 -translate-x-1/2 drop-shadow-md animate-bounce">👑</div>
                            <div class="w-32 h-32 rounded-full border-4 border-nusarasa-dark bg-nusarasa-yellow flex items-center justify-center font-black text-5xl text-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden transition-transform group-hover:scale-105">
                                @if($users[0]->avatar_url)
                                    <img src="{{ $users[0]->avatar_url }}" class="w-full h-full object-cover">
                                @else
                                    {{ mb_substr($users[0]->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-white border-2 border-nusarasa-dark text-nusarasa-dark rounded-full px-4 py-1 text-xs font-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] whitespace-nowrap">🥇 #1</div>
                        </div>
                        <div class="bg-nusarasa-yellow border-4 border-nusarasa-dark rounded-3xl px-8 pt-12 pb-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] text-center w-56 md:w-64 flex flex-col justify-center transform hover:-translate-y-2 transition-transform">
                            <p class="font-black text-xl text-nusarasa-dark line-clamp-1 mb-2">
                                {{ $users[0]->name }}
                                @if($users[0]->activeBadge) <span title="{{ $users[0]->activeBadge->name }}">{{ $users[0]->activeBadge->icon }}</span> @endif
                            </p>
                            <p class="text-sm font-black text-nusarasa-dark/90 bg-white px-4 py-1.5 rounded-pill mx-auto mb-3 border-2 border-nusarasa-dark/10">{{ number_format($users[0]->score_display) }} PTS</p>
                            @if(isset($users[0]->points) && $users[0]->points)
                                <span class="text-[10px] font-black uppercase bg-nusarasa-dark text-white px-3 py-1.5 rounded-full inline-block mx-auto">{{ $users[0]->points->level }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- 3rd Place -->
                    @if($users->count() >= 3)
                        <div class="flex flex-col items-center order-2 md:order-3 relative z-10 mt-8 md:mt-0">
                            <div class="relative z-10 -mb-6 group">
                                <div class="w-24 h-24 rounded-full border-4 border-nusarasa-dark bg-orange-200 flex items-center justify-center font-black text-4xl text-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden transition-transform group-hover:scale-105">
                                    @if($users[2]->avatar_url)
                                        <img src="{{ $users[2]->avatar_url }}" class="w-full h-full object-cover">
                                    @else
                                        {{ mb_substr($users[2]->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-white border-2 border-nusarasa-dark text-nusarasa-dark rounded-full px-3 py-1 text-xs font-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] whitespace-nowrap">🥉 #3</div>
                            </div>
                            <div class="bg-orange-100 border-4 border-nusarasa-dark rounded-3xl px-6 pt-10 pb-5 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] text-center w-48 md:w-56 flex flex-col justify-center transform hover:-translate-y-2 transition-transform">
                                <p class="font-black text-base text-nusarasa-dark line-clamp-1 mb-1">
                                    {{ $users[2]->name }}
                                    @if($users[2]->activeBadge) <span title="{{ $users[2]->activeBadge->name }}">{{ $users[2]->activeBadge->icon }}</span> @endif
                                </p>
                                <p class="text-xs font-black text-nusarasa-dark/80 bg-white px-3 py-1 rounded-pill mx-auto mb-1 border-2 border-nusarasa-dark/10">{{ number_format($users[2]->score_display) }} PTS</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Full Rankings Table -->
        <div class="bg-white border-4 border-nusarasa-dark rounded-[2.5rem] overflow-hidden shadow-[8px_8px_0px_0px_rgba(26,26,26,1)]" data-aos="fade-up">
            <div class="px-8 py-6 border-b-4 border-nusarasa-dark bg-nusarasa-dark flex items-center justify-between">
                <h2 class="text-xl font-black text-white uppercase tracking-tight">Peringkat Lengkap</h2>
                <span class="text-xs font-black text-white/60 uppercase">{{ $users->count() }} {{ $role === 'chef' ? 'Chef' : 'Pengguna' }}</span>
            </div>

            @if($users->isEmpty())
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">{{ $role === 'chef' ? '👨‍🍳' : '🧑‍🍳' }}</div>
                    <p class="font-black uppercase tracking-tight text-nusarasa-dark opacity-50">Belum ada {{ $role === 'chef' ? 'chef' : 'pengguna' }} di papan peringkat</p>
                </div>
            @else
                <div class="divide-y-4 divide-nusarasa-dark/10">
                    @foreach($users as $rank => $user)
                        <div class="flex items-center gap-3 sm:gap-6 px-4 sm:px-8 py-4 sm:py-5 hover:bg-nusarasa-cream/50 transition-colors {{ $rank === 0 ? 'bg-nusarasa-yellow/20' : '' }}">
                            <!-- Rank Badge -->
                            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                                @if($rank === 0)
                                    <span class="text-2xl">🥇</span>
                                @elseif($rank === 1)
                                    <span class="text-2xl">🥈</span>
                                @elseif($rank === 2)
                                    <span class="text-2xl">🥉</span>
                                @else
                                    <span class="text-lg font-black text-nusarasa-dark/40">#{{ $rank + 1 }}</span>
                                @endif
                            </div>

                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-full border-2 border-nusarasa-dark flex items-center justify-center font-black text-lg flex-shrink-0
                                {{ $rank === 0 ? 'bg-nusarasa-yellow' : ($rank === 1 ? 'bg-gray-200' : ($rank === 2 ? 'bg-orange-200' : 'bg-nusarasa-cream')) }} overflow-hidden">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover">
                                @else
                                    {{ mb_substr($user->name, 0, 1) }}
                                @endif
                            </div>

                            <!-- Name + Level -->
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-nusarasa-dark text-lg leading-tight line-clamp-1">
                                    {{ $user->name }}
                                    @if($user->activeBadge) <span title="{{ $user->activeBadge->name }}">{{ $user->activeBadge->icon }}</span> @endif
                                </p>
                                @if(isset($user->points) && $user->points)
                                    <span class="text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/50">{{ $user->points->level }}</span>
                                @endif
                            </div>

                            <!-- Score -->
                            <div class="text-right flex-shrink-0">
                                <p class="text-2xl font-black text-nusarasa-dark">{{ number_format($user->score_display) }}</p>
                                <p class="text-[10px] font-black uppercase tracking-widest text-nusarasa-dark/40">{{ $period === 'month' ? 'poin bulan ini' : 'total poin' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Your Rank (if auth) -->
        @auth
            <div class="mt-8 text-center" data-aos="fade-up">
                <a href="{{ route('recipes.by-ingredients') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-nusarasa-purple text-white border-2 border-nusarasa-dark rounded-pill font-black text-sm uppercase tracking-widest shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:translate-y-0.5 hover:shadow-none transition-all">
                    🚀 Kumpulkan lebih banyak poin sekarang!
                </a>
            </div>
        @endauth
    </div>
</x-app-layout>
