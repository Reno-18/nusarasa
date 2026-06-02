<footer class="bg-nusarasa-dark text-white border-t-8 border-nusarasa-dark mt-20 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <!-- Brand Column -->
            <div class="col-span-1 md:col-span-2 space-y-6">
                <a href="/" class="inline-block">
                    <span class="text-4xl font-black font-display tracking-tighter text-white">
                        NUSA<span class="text-nusarasa-yellow">RASA</span>
                    </span>
                </a>
                <p class="text-sm font-bold opacity-70 leading-relaxed max-w-sm">
                    Platform berbagi resep masakan Nusantara terbaik. Temukan inspirasi masakan harianmu, bagikan kreasi rahasia dapurmu, dan jelajahi rasa dari seluruh pelosok Indonesia.
                </p>
                <div class="flex items-center gap-4 pt-2">
                    <a href="#" class="w-12 h-12 bg-white text-nusarasa-dark rounded-full flex items-center justify-center text-xl border-2 border-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(255,255,255,0.3)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                        <i class="fab fa-instagram">IG</i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-white text-nusarasa-dark rounded-full flex items-center justify-center text-xl border-2 border-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(255,255,255,0.3)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                        <i class="fab fa-twitter">TW</i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-white text-nusarasa-dark rounded-full flex items-center justify-center text-xl border-2 border-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(255,255,255,0.3)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                        <i class="fab fa-facebook-f">FB</i>
                    </a>
                </div>
            </div>

            <!-- Links Column -->
            <div>
                <h4 class="text-lg font-black font-display uppercase tracking-widest text-nusarasa-pink mb-6">Jelajahi</h4>
                <ul class="space-y-4">
                    <li><a href="/" class="text-sm font-bold opacity-80 hover:opacity-100 hover:text-nusarasa-yellow transition">Beranda</a></li>
                    <li><a href="{{ route('recipes.index') }}" class="text-sm font-bold opacity-80 hover:opacity-100 hover:text-nusarasa-yellow transition">Semua Resep</a></li>
                    <li><a href="{{ route('chefs.index') }}" class="text-sm font-bold opacity-80 hover:opacity-100 hover:text-nusarasa-yellow transition">Daftar Chef</a></li>
                    @auth
                        <li><a href="{{ route('meal-plan.index') }}" class="text-sm font-bold opacity-80 hover:opacity-100 hover:text-nusarasa-yellow transition">Rencana Makan</a></li>
                        <li><a href="{{ route('saved.index') }}" class="text-sm font-bold opacity-80 hover:opacity-100 hover:text-nusarasa-yellow transition">Favorit Saya</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Help Column -->
            <div>
                <h4 class="text-lg font-black font-display uppercase tracking-widest text-nusarasa-purple mb-6">Bantuan</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-sm font-bold opacity-80 hover:opacity-100 hover:text-nusarasa-yellow transition">Tentang Kami</a></li>
                    <li><a href="#" class="text-sm font-bold opacity-80 hover:opacity-100 hover:text-nusarasa-yellow transition">Hubungi Kami</a></li>
                    <li><a href="#" class="text-sm font-bold opacity-80 hover:opacity-100 hover:text-nusarasa-yellow transition">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-sm font-bold opacity-80 hover:opacity-100 hover:text-nusarasa-yellow transition">Kebijakan Privasi</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t-2 border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs font-black uppercase tracking-widest opacity-50 text-center md:text-left">
                &copy; {{ date('Y') }} NusaRasa. Seluruh hak cipta dilindungi.
            </p>
            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-widest opacity-50">
                <span>Dibuat dengan</span>
                <span class="text-nusarasa-pink text-sm">❤️</span>
                <span>di Indonesia</span>
            </div>
        </div>
    </div>
</footer>
