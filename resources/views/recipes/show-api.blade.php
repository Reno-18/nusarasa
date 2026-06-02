<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('recipes.index') }}" class="inline-flex items-center gap-2 font-bold uppercase tracking-widest text-sm hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Side: Image & Meta -->
            <div class="lg:col-span-7">
                <div class="relative group rounded-4xl overflow-hidden border-2 border-nusarasa-dark shadow-xl mb-8">
                    <img src="{{ $recipe['image_url'] ?? 'https://placehold.co/800x600?text=No+Image' }}" 
                         alt="{{ $recipe['title'] }}" 
                         class="w-full h-[500px] object-cover group-hover:scale-105 transition duration-700">
                    
                    <div class="absolute bottom-6 left-6 flex gap-3">
                        <span class="px-6 py-2 bg-white border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase">
                            {{ $recipe['category'] ?? 'Uncategorized' }}
                        </span>
                        <span class="px-6 py-2 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase">
                            {{ $recipe['origin'] ?? 'Global' }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-blue-500 rounded-full border-2 border-nusarasa-dark flex items-center justify-center font-black text-xl text-white">
                            A
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase opacity-60">Sumber Konten</p>
                            <p class="font-bold text-lg leading-tight">TheMealDB API</p>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-white border-2 border-nusarasa-dark rounded-4xl p-8 mb-8">
                    <h2 class="text-3xl font-black font-display uppercase tracking-tight mb-6">Cara Memasak</h2>
                    <div class="prose max-w-none text-lg text-nusarasa-dark/80 leading-relaxed">
                        <pre class="whitespace-pre-wrap font-sans">{{ $recipe['instructions'] }}</pre>
                    </div>
                </div>
            </div>

            <!-- Right Side: Info & Actions -->
            <div class="lg:col-span-5">
                <h1 class="text-5xl md:text-6xl font-black font-display uppercase tracking-tighter leading-[0.9] mb-8 text-nusarasa-dark">
                    {{ $recipe['title'] }}
                </h1>

                <!-- Actions -->
                <div class="flex flex-col gap-4 mb-12">
                    @auth
                        @if($isSaved)
                            <button class="w-full py-5 bg-nusarasa-dark text-white rounded-pill font-black uppercase tracking-widest text-sm flex items-center justify-center gap-3 opacity-80 cursor-default" disabled>
                                <span class="text-xl">✓</span> Tersimpan di Favorit
                            </button>
                        @else
                            <button id="save-recipe-btn" class="w-full py-5 bg-nusarasa-dark text-white rounded-pill font-black uppercase tracking-widest text-sm hover:opacity-80 transition flex items-center justify-center gap-3" 
                                    data-meal-api-id="{{ $recipe['id'] }}">
                                <span class="text-xl">♥</span> Simpan Rasa Ini
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full py-5 bg-nusarasa-dark text-white rounded-pill font-black uppercase tracking-widest text-sm text-center">
                            Masuk untuk simpan
                        </a>
                    @endauth
                </div>

                <!-- Ingredients -->
                <div class="bg-nusarasa-pink border-2 border-nusarasa-dark rounded-4xl p-8 mb-8">
                    <h2 class="text-2xl font-black font-display uppercase tracking-tight mb-6">Bahan-bahan</h2>
                    <div class="space-y-3 font-bold text-lg">
                        <pre class="whitespace-pre-wrap font-sans">{{ $recipe['ingredients'] }}</pre>
                    </div>
                </div>

                <!-- Meal Plan Form -->
                @auth
                    <div class="bg-nusarasa-purple border-2 border-nusarasa-dark rounded-4xl p-8 mb-8">
                        <h3 class="text-xl font-black font-display uppercase mb-6">Rencanakan Menu</h3>
                        <form id="meal-plan-form" class="space-y-4">
                            <select name="day_of_week" class="w-full px-6 py-4 bg-white border-2 border-nusarasa-dark rounded-pill font-bold focus:ring-0" required>
                                <option value="">Pilih Hari</option>
                                <option value="monday">Senin</option>
                                <option value="tuesday">Selasa</option>
                                <option value="wednesday">Rabu</option>
                                <option value="thursday">Kamis</option>
                                <option value="friday">Jumat</option>
                                <option value="saturday">Sabtu</option>
                                <option value="sunday">Minggu</option>
                            </select>
                            <select name="meal_type" class="w-full px-6 py-4 bg-white border-2 border-nusarasa-dark rounded-pill font-bold focus:ring-0" required>
                                <option value="">Pilih Waktu</option>
                                <option value="breakfast">Sarapan</option>
                                <option value="lunch">Makan Siang</option>
                                <option value="dinner">Makan Malam</option>
                            </select>
                            <input type="hidden" name="meal_api_id" value="{{ $recipe['id'] }}">
                            <input type="hidden" name="meal_api_title" value="{{ $recipe['title'] }}">
                            <input type="hidden" name="meal_api_image" value="{{ $recipe['image_url'] }}">
                            <input type="hidden" name="week_start" value="{{ now()->startOfWeek()->format('Y-m-d') }}">
                            <button type="submit" class="w-full py-4 bg-nusarasa-dark text-white rounded-pill font-black uppercase tracking-widest text-xs hover:opacity-80 transition">
                                Tambahkan ke Rencana
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>

        <div class="mt-12 p-8 bg-white/50 border-2 border-dashed border-nusarasa-dark rounded-4xl text-center">
            <p class="text-lg font-bold opacity-60 italic">
                Resep ini berasal dari TheMealDB API. Ulasan dan rating hanya tersedia untuk resep lokal dari komunitas Chef kami.
            </p>
        </div>
    </div>

    @push('scripts')
    <script>
        // Save recipe
        $('#save-recipe-btn').on('click', function() {
            const mealApiId = $(this).data('meal-api-id');
            $.ajax({
                url: '/api/saved-recipes',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { 
                    meal_api_id: mealApiId,
                    meal_api_title: "{{ $recipe['title'] }}",
                    meal_api_image: "{{ $recipe['image_url'] }}"
                },
                success: function(response) {
                    showToast('Resep berhasil disimpan! Mengalihkan...', 'success');
                    $('#save-recipe-btn').html('<span class="text-xl">✓</span> Mengalihkan...').prop('disabled', true);
                    setTimeout(() => window.location.href = "{{ route('saved.index') }}", 1000);
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Gagal menyimpan resep';
                    showToast(message, 'error');
                }
            });
        });

        // Meal Plan
        $('#meal-plan-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/meal-plans',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(this).serialize(),
                success: function(response) {
                    showToast('Berhasil ditambahkan! Mengalihkan ke Rencana Makan...', 'success');
                    setTimeout(() => window.location.href = "{{ route('meal-plan.index') }}", 1000);
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Gagal menambahkan ke rencana makan';
                    showToast(message, 'error');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
