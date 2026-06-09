<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        <!-- Hero Section -->
        <div class="relative py-10 md:py-20 mb-10 md:mb-20 flex flex-col items-center justify-center min-h-[50vh] md:min-h-[600px] overflow-visible">
            
            <!-- Busy Floating Food Elements -->
            <!-- Top Row -->
            <div class="absolute top-0 left-1/4 animate-bounce hidden lg:block" style="animation-duration: 3s;" data-aos="zoom-in">
                <div class="w-16 h-16 bg-nusarasa-pink border-2 border-nusarasa-dark rounded-3xl flex items-center justify-center text-3xl shadow-lg">🍕</div>
            </div>
            <div class="absolute top-10 right-1/3 animate-bounce hidden lg:block" style="animation-duration: 4s;" data-aos="zoom-in" data-aos-delay="200">
                <div class="w-14 h-14 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-3xl shadow-lg">🍔</div>
            </div>
            <div class="absolute top-20 right-10 animate-pulse hidden lg:block" data-aos="zoom-in" data-aos-delay="400">
                <div class="w-12 h-12 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-lg">🍣</div>
            </div>

            <!-- Bottom Row -->
            <div class="absolute bottom-10 left-10 animate-pulse hidden lg:block" data-aos="zoom-in" data-aos-delay="600">
                <div class="w-20 h-20 bg-green-100 border-2 border-nusarasa-dark rounded-4xl flex items-center justify-center text-4xl shadow-lg text-white">🥗</div>
            </div>
            <div class="absolute bottom-0 right-1/4 animate-bounce hidden lg:block" style="animation-duration: 5s;" data-aos="zoom-in" data-aos-delay="800">
                <div class="w-16 h-16 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-3xl flex items-center justify-center text-3xl shadow-lg">🍜</div>
            </div>
            <div class="absolute bottom-20 right-0 animate-bounce hidden lg:block" style="animation-duration: 3.5s;" data-aos="zoom-in" data-aos-delay="1000">
                <div class="w-14 h-14 bg-nusarasa-pink border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-3xl shadow-lg">🍩</div>
            </div>

            <!-- Hero Main Content (Emoticons + Logo) -->
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-16 w-full max-w-screen-2xl">
                
                <!-- Sad Emoticon -->
                <div class="hidden md:flex flex-col items-center justify-center w-28 lg:w-40 h-56 lg:h-80 border-2 border-nusarasa-dark rounded-full bg-white shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] rotate-[-4deg]" data-aos="fade-right">
                    <div class="flex flex-col items-center gap-4">
                        <div class="flex gap-6">
                            <div class="w-3 h-3 bg-nusarasa-dark rounded-full"></div>
                            <div class="w-3 h-3 bg-nusarasa-dark rounded-full"></div>
                        </div>
                        <div class="w-12 h-4 border-t-2 border-nusarasa-dark rounded-[100%] mt-2"></div>
                    </div>
                </div>

                <!-- Custom FOOD MOOD Logo -->
                <div class="flex flex-col items-center gap-2 select-none pointer-events-none" data-aos="zoom-in">
                    <!-- FOOD Row -->
                    <div class="flex items-center gap-2 md:gap-4 text-[3.5rem] sm:text-[5rem] md:text-[8rem] lg:text-[10rem] font-black font-display leading-none tracking-tighter text-nusarasa-dark uppercase">
                        <span>F</span>
                        <div class="w-12 sm:w-24 md:w-56 lg:w-80 h-9 sm:h-16 md:h-28 lg:h-36 border-[7px] sm:border-[12px] md:border-[18px] lg:border-[24px] border-nusarasa-dark rounded-full"></div>
                        <span>D</span>
                    </div>
                    <!-- MOOD Row -->
                    <div class="flex items-center gap-2 md:gap-4 text-[3.5rem] sm:text-[5rem] md:text-[8rem] lg:text-[10rem] font-black font-display leading-none tracking-tighter text-nusarasa-dark uppercase">
                        <span>M</span>
                        <div class="w-12 sm:w-24 md:w-56 lg:w-80 h-9 sm:h-16 md:h-28 lg:h-36 border-[7px] sm:border-[12px] md:border-[18px] lg:border-[24px] border-nusarasa-dark rounded-full"></div>
                        <span>D</span>
                    </div>
                </div>

                <!-- Happy Emoticon -->
                <div class="hidden md:flex flex-col items-center justify-center w-28 lg:w-40 h-56 lg:h-80 border-2 border-nusarasa-dark rounded-full bg-white shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] rotate-[4deg]" data-aos="fade-left">
                    <div class="flex flex-col items-center gap-4">
                        <div class="flex gap-6">
                            <div class="w-3 h-3 bg-nusarasa-dark rounded-full"></div>
                            <div class="w-3 h-3 bg-nusarasa-dark rounded-full"></div>
                        </div>
                        <div class="w-12 h-6 border-b-2 border-nusarasa-dark rounded-[100%]"></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 md:mt-20 flex flex-wrap justify-center gap-4 px-4" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('recipes.index') }}" class="bg-nusarasa-yellow px-10 py-5 border-2 border-nusarasa-dark rounded-pill flex items-center gap-4 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                    <span class="font-black text-sm uppercase tracking-widest text-nusarasa-dark">Mulai Eksplorasi Resep</span>
                    <svg class="w-6 h-6 text-nusarasa-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        @auth
            @if(isset($recommended) && $recommended->isNotEmpty())
                <!-- Recommended for You Section -->
                <div class="py-24 border-t-4 border-nusarasa-dark/10 relative overflow-hidden" data-aos="fade-up">
                    <!-- Retro background dot pattern -->
                    <div class="absolute inset-0 bg-[radial-gradient(#1a1a1a_1.5px,transparent_1.5px)] [background-size:24px_24px] opacity-10 pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-xl text-[10px] font-black uppercase tracking-wider mb-4 shadow-[2px_2px_0px_0px_rgba(26,26,26,1)]">
                                💡 Kreasi Cerdas
                            </div>
                            <h2 class="text-5xl md:text-7xl font-black font-display uppercase tracking-tighter text-nusarasa-dark leading-none">
                                Rekomendasi <span class="bg-nusarasa-yellow px-4 py-1 border-4 border-nusarasa-dark inline-block transform -rotate-2 shadow-[4px_4px_0px_0px_rgba(26,26,26,1)]">Untukmu</span>
                            </h2>
                            <p class="text-base font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark mt-4">Menu pilihan yang disesuaikan dengan selera dan riwayat memasakmu</p>
                        </div>
                        <a href="{{ route('recipes.index') }}" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-white border-4 border-nusarasa-dark rounded-2xl font-black text-xs uppercase tracking-widest shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_rgba(26,26,26,1)] transition-all">
                            Lihat Semua Resep
                            <svg class="w-4 h-4 text-nusarasa-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>

                    <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @php
                            $colors = ['bg-nusarasa-pink', 'bg-nusarasa-purple', 'bg-nusarasa-yellow', 'bg-blue-100', 'bg-green-100'];
                        @endphp
                        @foreach($recommended as $index => $recipe)
                            @php
                                $bgColor = $colors[$index % count($colors)];
                            @endphp
                            <div class="group bg-white border-2 border-nusarasa-dark rounded-4xl p-6 hover:-translate-y-2 transition duration-300 flex flex-col h-full relative" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                                <!-- Image container -->
                                <div class="w-full h-72 rounded-3xl overflow-hidden mb-6 {{ $bgColor }} flex items-center justify-center relative">
                                    <img src="{{ $recipe->image_url ?? asset('images/default-recipe.jpg') }}" alt="{{ $recipe->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                         onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=Resep+NusaRasa';">
                                    
                                    <!-- Star Score sticker (vintage stamp look) -->
                                    <div class="absolute top-4 right-4 bg-nusarasa-yellow border-2 border-nusarasa-dark px-3 py-1.5 rounded-xl font-black text-xs uppercase shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] flex items-center gap-1 transform rotate-6 hover:rotate-0 transition-transform duration-200">
                                        <span class="text-amber-500 animate-pulse">⭐</span> 
                                        <span class="text-nusarasa-dark">{{ number_format($recipe->ratings_avg_score ?? 0, 1) }}</span>
                                    </div>

                                    @if($recipe->difficulty)
                                        <div class="absolute bottom-4 left-4 bg-white border-2 border-nusarasa-dark px-3 py-1 rounded-lg font-black text-[9px] uppercase shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] text-nusarasa-dark">
                                            ⚡ {{ $recipe->difficulty }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-black uppercase tracking-tighter text-nusarasa-dark opacity-60">
                                        {{ $recipe->category ?? 'Lain-lain' }} • {{ $recipe->origin ?? 'Global' }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Lokal</span>
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    </div>
                                </div>

                                <h3 class="text-2xl font-black font-display text-nusarasa-dark mb-6 leading-tight flex-1 uppercase tracking-tighter">
                                    <a href="{{ route('recipes.show', $recipe->id) }}">{{ $recipe->title }}</a>
                                </h3>
                                
                                <div class="flex items-center justify-between mt-auto pt-4 border-t-2 border-nusarasa-dark/5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-yellow-400 font-bold">★</span>
                                        <span class="font-black text-sm">{{ number_format($recipe->ratings_avg_score ?? 0, 1) }}</span>
                                    </div>
                                    <a href="{{ route('recipes.show', $recipe->id) }}" class="px-6 py-3 bg-nusarasa-dark text-white rounded-pill font-black text-[10px] uppercase tracking-widest hover:bg-opacity-80 transition flex items-center gap-2">
                                        Detail
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endauth

        <!-- Info / Features Section -->
        <div class="py-24 border-t-4 border-nusarasa-dark/10">
            <div class="text-center mb-24" data-aos="fade-up">
                <h2 class="text-5xl md:text-7xl font-black font-display uppercase tracking-tighter text-nusarasa-dark mb-6">
                    Apa Itu <span class="text-[#6D28D9]">NusaRasa?</span>
                </h2>
                <p class="text-xl font-bold opacity-60 uppercase tracking-widest max-w-3xl mx-auto leading-relaxed">Satu wadah untuk jutaan rasa. Mengubah cara Anda merencanakan dan menyajikan makanan setiap hari.</p>
            </div>

            <div class="flex flex-col gap-16">
                <!-- Feature 1 -->
                <div class="flex flex-col md:flex-row items-center gap-12" data-aos="fade-up">
                    <div class="w-full md:w-1/2 bg-nusarasa-purple border-4 border-nusarasa-dark rounded-[3rem] p-16 flex items-center justify-center shadow-[16px_16px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-2 hover:-translate-x-2 transition-transform">
                        <div class="text-[8rem] leading-none drop-shadow-[4px_4px_0px_rgba(0,0,0,0.5)]">🌍</div>
                    </div>
                    <div class="w-full md:w-1/2 space-y-6">
                        <div class="inline-block px-6 py-2 bg-nusarasa-purple text-white border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">Global</div>
                        <h3 class="text-4xl md:text-5xl font-black font-display uppercase tracking-tighter text-nusarasa-dark leading-tight">Ribuan Resep<br>Dari Seluruh Dunia</h3>
                        <p class="text-lg font-bold opacity-70 leading-relaxed text-nusarasa-dark">
                            Terintegrasi dengan database masakan internasional. Temukan inspirasi menu baru, lihat bahan, dan ikuti panduan memasak dari berbagai negara dalam satu tempat.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="flex flex-col md:flex-row-reverse items-center gap-12" data-aos="fade-up">
                    <div class="w-full md:w-1/2 bg-nusarasa-yellow border-4 border-nusarasa-dark rounded-[3rem] p-16 flex items-center justify-center shadow-[16px_16px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-2 hover:-translate-x-2 transition-transform">
                        <div class="text-[8rem] leading-none drop-shadow-[4px_4px_0px_rgba(0,0,0,0.5)]">📅</div>
                    </div>
                    <div class="w-full md:w-1/2 space-y-6">
                        <div class="inline-block px-6 py-2 bg-nusarasa-yellow text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">Produktivitas</div>
                        <h3 class="text-4xl md:text-5xl font-black font-display uppercase tracking-tighter text-nusarasa-dark leading-tight">Rencana Makan<br>Lebih Cerdas</h3>
                        <p class="text-lg font-bold opacity-70 leading-relaxed text-nusarasa-dark">
                            Ucapkan selamat tinggal pada kebingungan memasak harian. Simpan resep favorit Anda dan susun jadwal menu mingguan secara praktis langsung dari dashboard pribadi.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="flex flex-col md:flex-row items-center gap-12" data-aos="fade-up">
                    <div class="w-full md:w-1/2 bg-nusarasa-pink border-4 border-nusarasa-dark rounded-[3rem] p-16 flex items-center justify-center shadow-[16px_16px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-2 hover:-translate-x-2 transition-transform">
                        <div class="text-[8rem] leading-none drop-shadow-[4px_4px_0px_rgba(0,0,0,0.5)]">👨‍🍳</div>
                    </div>
                    <div class="w-full md:w-1/2 space-y-6">
                        <div class="inline-block px-6 py-2 bg-nusarasa-pink text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">Komunitas</div>
                        <h3 class="text-4xl md:text-5xl font-black font-display uppercase tracking-tighter text-nusarasa-dark leading-tight">Berbagi Kreasi<br>Pahlawan Dapur</h3>
                        <p class="text-lg font-bold opacity-70 leading-relaxed text-nusarasa-dark">
                            Jangan simpan resep rahasia Anda sendirian. Bergabunglah dengan komunitas, dapatkan ulasan bintang lima, dan raih posisi puncak sebagai Chef Terpopuler di NusaRasa!
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
