<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-7xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Masak dari <span class="text-[#6D28D9]">Bahan</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Masukkan bahan yang kamu miliki, kami temukan resep terbaik untukmu</p>
        </div>

        <!-- Search Form Card -->
        <div class="max-w-3xl mx-auto mb-16" data-aos="fade-up" x-data="{ 
            ingredients: '{{ $ingredientsInput }}',
            quickAdd(item) {
                let current = this.ingredients.split(',').map(i => i.trim()).filter(i => i.length > 0);
                if (!current.includes(item)) {
                    current.push(item);
                    this.ingredients = current.join(', ');
                }
            }
        }">
            <div class="bg-white border-4 border-nusarasa-dark rounded-[2.5rem] p-8 md:p-12 shadow-[12px_12px_0px_0px_rgba(26,26,26,1)]">
                <form action="{{ route('recipes.by-ingredients') }}" method="GET" class="space-y-6">
                    <div>
                        <label for="ingredients" class="block text-sm font-black uppercase tracking-widest text-nusarasa-dark mb-3">Bahan-bahan yang kamu punya (pisahkan dengan koma)</label>
                        <input type="text" id="ingredients" name="ingredients" x-model="ingredients"
                               placeholder="Contoh: telur, nasi, bawang putih, cabai..." 
                               class="w-full px-6 py-4 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl font-bold focus:ring-0 focus:border-nusarasa-dark text-lg shadow-inner">
                    </div>

                    <!-- Quick Suggestions -->
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-nusarasa-dark/40 mb-2">Bahan populer:</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="quickAdd('telur')" class="px-4 py-2 bg-nusarasa-yellow text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-wider hover:-translate-y-0.5 shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] hover:shadow-none transition-all">🥚 Telur</button>
                            <button type="button" @click="quickAdd('nasi')" class="px-4 py-2 bg-nusarasa-pink text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-wider hover:-translate-y-0.5 shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] hover:shadow-none transition-all">🍚 Nasi</button>
                            <button type="button" @click="quickAdd('cabai')" class="px-4 py-2 bg-nusarasa-purple text-white border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-wider hover:-translate-y-0.5 shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] hover:shadow-none transition-all">🌶️ Cabai</button>
                            <button type="button" @click="quickAdd('bawang putih')" class="px-4 py-2 bg-green-200 text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-wider hover:-translate-y-0.5 shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] hover:shadow-none transition-all">🧄 Bawang Putih</button>
                            <button type="button" @click="quickAdd('daging ayam')" class="px-4 py-2 bg-orange-200 text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-wider hover:-translate-y-0.5 shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] hover:shadow-none transition-all">🍗 Daging Ayam</button>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 bg-nusarasa-dark text-white border-4 border-nusarasa-dark rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-white hover:text-nusarasa-dark transition-all shadow-[6px_6px_0px_0px_rgba(26,26,26,0.3)] hover:translate-y-1 hover:shadow-none flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Temukan Rencana Rasa
                    </button>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        @if(!empty($ingredients))
            <div class="mt-16">
                <h2 class="text-3xl font-black font-display uppercase tracking-tight text-nusarasa-dark mb-8" data-aos="fade-right">
                    Hasil untuk: <span class="opacity-50 font-sans font-bold text-2xl lowercase">"{{ implode(', ', $ingredients) }}"</span>
                </h2>

                @if(count($results) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($results as $item)
                            @php
                                $recipe = $item['recipe'];
                                $isLocal = $item['source'] === 'local';
                                $title = $isLocal ? $recipe->title : $recipe->title;
                                $imageUrl = $isLocal ? ($recipe->image_url ?? asset('images/default-recipe.jpg')) : $recipe->image_url;
                                $category = $isLocal ? $recipe->category : $recipe->category;
                                $url = $isLocal ? route('recipes.show', $recipe->id) : route('recipes.show-api', $recipe->id);
                            @endphp

                            <div class="bg-white border-4 border-nusarasa-dark rounded-[2rem] overflow-hidden shadow-[8px_8px_0px_0px_rgba(26,26,26,1)] hover:-translate-y-1 transition-transform flex flex-col h-full" data-aos="fade-up">
                                <!-- Image & Match Badge -->
                                <div class="relative h-56 border-b-4 border-nusarasa-dark">
                                    <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full h-full object-cover">
                                    
                                    <!-- Match Percentage Badge -->
                                    <div class="absolute top-4 left-4 bg-nusarasa-yellow border-2 border-nusarasa-dark px-4 py-2 rounded-xl font-black text-xs uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                                        🎯 Cocok {{ $item['match_percent'] }}%
                                    </div>

                                    <!-- Source Badge -->
                                    <div class="absolute top-4 right-4 {{ $isLocal ? 'bg-nusarasa-pink' : 'bg-nusarasa-purple text-white' }} border-2 border-nusarasa-dark px-4 py-2 rounded-xl font-black text-xs uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                                        {{ $isLocal ? 'Lokal' : 'Global' }}
                                    </div>
                                </div>

                                <!-- Body -->
                                <div class="p-6 flex flex-col flex-grow justify-between">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-nusarasa-pink mb-2">{{ $category ?? 'Lain-lain' }}</p>
                                        <h3 class="text-2xl font-black text-nusarasa-dark tracking-tight line-clamp-2 leading-tight mb-4">{{ $title }}</h3>
                                    </div>

                                    <div>
                                        <div class="border-t-2 border-dashed border-nusarasa-dark/10 pt-4 mb-4">
                                            <p class="text-xs font-bold text-nusarasa-dark/50 uppercase">Bahan yang cocok: <span class="font-black text-nusarasa-dark">{{ $item['matched_count'] }}</span> dari <span class="font-black text-nusarasa-dark">{{ count($ingredients) }}</span></p>
                                        </div>

                                        <a href="{{ $url }}" class="block text-center py-3 bg-nusarasa-yellow text-nusarasa-dark border-2 border-nusarasa-dark rounded-xl font-black text-xs uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(26,26,26,1)] hover:translate-y-0.5 hover:shadow-none transition-all">
                                            Lihat Resep
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white border-4 border-nusarasa-dark rounded-[2rem] p-12 text-center shadow-[8px_8px_0px_0px_rgba(26,26,26,1)]" data-aos="zoom-in">
                        <div class="text-6xl mb-4">🍳</div>
                        <h3 class="text-2xl font-black text-nusarasa-dark uppercase tracking-tight mb-2">Resep Tidak Ditemukan</h3>
                        <p class="text-sm font-bold opacity-60 max-w-md mx-auto">Kami tidak dapat menemukan resep lokal maupun global yang cocok dengan minimal 50% dari bahan-bahan kamu. Coba kurangi atau cari bahan lain!</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
