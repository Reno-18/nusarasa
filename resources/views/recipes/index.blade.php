<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Eksplorasi <span class="text-[#6D28D9]">Rasa</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Temukan inspirasi masakan harianmu dari seluruh penjuru dunia</p>
        </div>

        <div class="mb-12">
            <!-- Search and Filter -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-16 relative z-30" data-aos="fade-up">
                <div class="relative w-full max-w-xl">
                    <input type="text" id="search-input" placeholder="Cari suasana makanmu..." 
                           class="w-full px-8 py-5 bg-white border-2 border-nusarasa-dark rounded-pill font-bold focus:ring-0 focus:border-nusarasa-dark placeholder-gray-400 text-lg shadow-xl"
                           value="{{ request('search') }}">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-nusarasa-dark rounded-full flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                
                <div class="relative w-full md:w-72" id="custom-sort-dropdown">
                    <!-- Dropdown Trigger Button -->
                    <button type="button" id="sort-dropdown-btn" class="w-full flex items-center justify-between px-8 py-5 bg-white border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest focus:outline-none shadow-xl cursor-pointer transition hover:bg-nusarasa-cream">
                        <span id="sort-dropdown-label">Semua Resep</span>
                        <svg class="w-4 h-4 ml-3 transition-transform duration-300 transform" id="sort-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="sort-dropdown-menu" class="hidden absolute right-0 mt-3 w-full bg-white border-2 border-nusarasa-dark rounded-2xl shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] py-2 z-50 overflow-hidden">
                        <button type="button" class="sort-dropdown-item w-full text-left px-6 py-4 text-xs font-black uppercase tracking-widest hover:bg-nusarasa-cream transition text-nusarasa-dark" data-value="all">
                            Semua Resep
                        </button>
                        <button type="button" class="sort-dropdown-item w-full text-left px-6 py-4 text-xs font-black uppercase tracking-widest hover:bg-nusarasa-cream transition text-nusarasa-dark border-t-2 border-nusarasa-dark/5" data-value="local">
                            Resep Lokal
                        </button>
                        <button type="button" class="sort-dropdown-item w-full text-left px-6 py-4 text-xs font-black uppercase tracking-widest hover:bg-nusarasa-cream transition text-nusarasa-dark border-t-2 border-nusarasa-dark/5" data-value="api">
                            Resep dari API
                        </button>
                    </div>
                    <!-- Hidden Input to retain same ID for JS integration -->
                    <input type="hidden" id="sort-filter" value="all">
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-16 relative z-20" data-aos="fade-up">
                <div class="flex items-center gap-4 overflow-x-auto pb-4 w-full md:w-auto">
                    <button class="category-btn px-8 py-3 rounded-pill border-2 border-nusarasa-dark font-black text-xs uppercase tracking-widest transition bg-nusarasa-dark text-white whitespace-nowrap" data-category="">Semua</button>
                    @foreach($categories as $category)
                        <button class="category-btn px-8 py-3 rounded-pill border-2 border-nusarasa-dark font-black text-xs uppercase tracking-widest transition hover:bg-nusarasa-dark hover:text-white whitespace-nowrap" data-category="{{ $category }}">
                            {{ $category }}
                        </button>
                    @endforeach
                    <input type="hidden" id="category-filter" value="{{ request('category') }}">
                </div>
            </div>

            <!-- Recipe Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="recipe-grid">
                <!-- Loading Skeleton / Empty State -->
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-nusarasa-dark opacity-50">
                    <div class="w-16 h-16 border-4 border-nusarasa-dark border-t-nusarasa-pink rounded-full animate-spin mb-4"></div>
                    <p class="font-black uppercase tracking-widest text-lg">Mencampur Resep...</p>
                </div>
            </div>

            <!-- Load More Container -->
            <div id="load-more-container" class="mt-16 flex justify-center hidden">
                <button id="load-more-btn" class="px-10 py-4 bg-nusarasa-dark text-white border-4 border-nusarasa-dark rounded-pill font-black text-sm uppercase tracking-widest hover:bg-white hover:text-nusarasa-dark transition-all shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:translate-y-2 hover:shadow-none flex items-center gap-3">
                    Muat Lebih Banyak
                    <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.savedRecipeIds = @json($savedRecipeIds);
        window.savedMealApiIds = @json($savedMealApiIds);

        $(document).ready(function() {
            const dropdownBtn = $('#sort-dropdown-btn');
            const dropdownMenu = $('#sort-dropdown-menu');
            const dropdownArrow = $('#sort-dropdown-arrow');
            const dropdownLabel = $('#sort-dropdown-label');
            const sortInput = $('#sort-filter');

            dropdownBtn.on('click', function(e) {
                e.stopPropagation();
                dropdownMenu.toggleClass('hidden');
                dropdownArrow.toggleClass('rotate-180');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#custom-sort-dropdown').length) {
                    dropdownMenu.addClass('hidden');
                    dropdownArrow.removeClass('rotate-180');
                }
            });

            $('.sort-dropdown-item').on('click', function() {
                const val = $(this).data('value');
                const label = $(this).text().trim();

                dropdownLabel.text(label);
                sortInput.val(val).trigger('change');

                dropdownMenu.addClass('hidden');
                dropdownArrow.removeClass('rotate-180');
            });
        });
    </script>
    @endpush
</x-app-layout>
