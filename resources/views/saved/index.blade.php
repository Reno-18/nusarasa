<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Koleksi <span class="text-[#6D28D9]">Favorit</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest">Suasana rasa yang kamu simpan untuk nanti</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @php
                $colors = ['bg-nusarasa-pink', 'bg-nusarasa-purple', 'bg-nusarasa-yellow', 'bg-blue-100', 'bg-green-100'];
            @endphp
            @forelse($savedRecipes as $index => $saved)
                @php
                    $isApi = !is_null($saved->meal_api_id);
                    $recipe = $isApi ? (object)[
                        'id' => $saved->meal_api_id,
                        'title' => $saved->meal_api_title ?? 'Resep API',
                        'image_url' => $saved->meal_api_image ?? 'https://placehold.co/400x300?text=No+Image',
                        'category' => 'Global',
                        'origin' => 'API',
                        'source' => 'api'
                    ] : $saved->recipe;
                    
                    $bgColor = $colors[$index % count($colors)];
                    $detailUrl = $isApi ? route('recipes.show-api', $recipe->id) : route('recipes.show', $recipe->id);
                @endphp

                <div class="group relative bg-white border-2 border-nusarasa-dark rounded-4xl p-6 hover:-translate-y-2 transition duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                    <div class="w-full h-72 rounded-3xl overflow-hidden mb-6 {{ $bgColor }} flex items-center justify-center relative">
                        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        
                        <form action="{{ route('api.saved-recipes.destroy', $saved->id) }}" method="POST" class="absolute top-4 right-4 delete-favorite-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-12 h-12 bg-white border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-xl text-red-500 hover:bg-red-500 hover:text-white transition shadow-lg">
                                ✕
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black uppercase tracking-tighter text-nusarasa-dark opacity-60">
                            {{ $recipe->category ?? 'Uncategorized' }} • {{ $recipe->origin ?? 'Global' }}
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase tracking-widest opacity-40">{{ $isApi ? 'API' : 'Lokal' }}</span>
                            <span class="w-2 h-2 rounded-full {{ $isApi ? 'bg-blue-500' : 'bg-green-500' }}"></span>
                        </div>
                    </div>

                    <h3 class="text-2xl font-black font-display text-nusarasa-dark mb-6 leading-tight flex-1 uppercase tracking-tighter">{{ $recipe->title }}</h3>
                    
                    <div class="flex items-center justify-between mt-auto pt-4 border-t-2 border-nusarasa-dark/5">
                        <a href="{{ $detailUrl }}" class="w-full py-4 bg-nusarasa-dark text-white rounded-pill font-black text-[10px] uppercase tracking-widest hover:bg-opacity-80 transition flex items-center justify-center gap-2">
                            Lihat Detail
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-32" data-aos="fade-up">
                    <div class="w-32 h-32 bg-white border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-5xl mx-auto mb-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        🍳
                    </div>
                    <h3 class="text-4xl font-black font-display text-nusarasa-dark uppercase mb-4">Koleksimu masih kosong</h3>
                    <p class="text-lg font-bold opacity-40 mb-12">Mulailah mencari inspirasi rasa untuk harimu.</p>
                    <a href="{{ route('recipes.index') }}" class="px-12 py-5 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-pill font-black uppercase tracking-widest shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                        Jelajahi Resep
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).on('submit', '.delete-favorite-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');
            
            if (confirm('Hapus resep ini dari favorit?')) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        form.closest('[data-aos]').fadeOut(300, function() {
                            $(this).remove();
                            if ($('.delete-favorite-form').length === 0) {
                                location.reload();
                            }
                        });
                        showToast('Resep dihapus dari favorit', 'success');
                    },
                    error: function(xhr) {
                        showToast('Gagal menghapus resep', 'error');
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
