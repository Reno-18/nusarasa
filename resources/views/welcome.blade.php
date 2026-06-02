<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        <!-- Hero Section -->
        <div class="relative py-20 mb-20 flex flex-col items-center justify-center min-h-[600px] overflow-visible">
            
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
                    <div class="flex items-center gap-4 text-[5rem] md:text-[8rem] lg:text-[10rem] font-black font-display leading-none tracking-tighter text-nusarasa-dark uppercase">
                        <span>F</span>
                        <div class="w-24 md:w-56 lg:w-80 h-16 md:h-28 lg:h-36 border-[12px] md:border-[18px] lg:border-[24px] border-nusarasa-dark rounded-full"></div>
                        <span>D</span>
                    </div>
                    <!-- MOOD Row -->
                    <div class="flex items-center gap-4 text-[5rem] md:text-[8rem] lg:text-[10rem] font-black font-display leading-none tracking-tighter text-nusarasa-dark uppercase">
                        <span>M</span>
                        <div class="w-24 md:w-56 lg:w-80 h-16 md:h-28 lg:h-36 border-[12px] md:border-[18px] lg:border-[24px] border-nusarasa-dark rounded-full"></div>
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

            <div class="mt-20 flex flex-wrap justify-center gap-4" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('recipes.index') }}" class="bg-nusarasa-yellow px-10 py-5 border-2 border-nusarasa-dark rounded-pill flex items-center gap-4 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                    <span class="font-black text-sm uppercase tracking-widest text-nusarasa-dark">Mulai Eksplorasi Resep</span>
                    <svg class="w-6 h-6 text-nusarasa-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <!-- Info / Features Section -->
        <div class="py-24 border-t-4 border-nusarasa-dark/10">
            <div class="text-center mb-24" data-aos="fade-up">
                <h2 class="text-5xl md:text-7xl font-black font-display uppercase tracking-tighter text-nusarasa-dark mb-6">
                    Apa Itu <span class="text-nusarasa-pink">NusaRasa?</span>
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
