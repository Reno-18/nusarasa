<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <style>
            /* Custom scrollbar styling for Neobrutalist board */
            #meal-plan-board::-webkit-scrollbar {
                height: 14px;
            }
            #meal-plan-board::-webkit-scrollbar-track {
                background: transparent;
                border: 4px solid #1a1a1a;
                border-radius: 100px;
            }
            #meal-plan-board::-webkit-scrollbar-thumb {
                background: #1a1a1a;
                border-radius: 100px;
                border: 2px solid #ffffff;
            }
            #meal-plan-board::-webkit-scrollbar-thumb:hover {
                background: #e5dcf4; /* nusarasa-purple */
            }
        </style>

        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Rencana <span class="text-nusarasa-purple">Makan</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest">Atur suasana kulinermu minggu ini</p>
        </div>

        <div class="flex overflow-x-auto gap-8 pb-8 snap-x snap-mandatory scroll-smooth px-2 py-4" id="meal-plan-board" data-aos="fade-up">
            @foreach($days as $index => $day)
                @php
                    $dayNames = [
                        'monday' => 'Senin',
                        'tuesday' => 'Selasa',
                        'wednesday' => 'Rabu',
                        'thursday' => 'Kamis',
                        'friday' => 'Jumat',
                        'saturday' => 'Sabtu',
                        'sunday' => 'Minggu'
                    ];
                    $dayColors = [
                        'monday' => 'bg-nusarasa-pink',
                        'tuesday' => 'bg-nusarasa-purple',
                        'wednesday' => 'bg-nusarasa-yellow',
                        'thursday' => 'bg-emerald-100',
                        'friday' => 'bg-orange-100',
                        'saturday' => 'bg-blue-100',
                        'sunday' => 'bg-red-100'
                    ];
                    $dayColor = $dayColors[$day] ?? 'bg-white';
                @endphp
                
                <div class="flex-shrink-0 w-[300px] sm:w-[350px] snap-start flex flex-col bg-white border-4 border-nusarasa-dark rounded-3xl shadow-[8px_8px_0px_0px_rgba(26,26,26,1)] overflow-hidden transition-all hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[6px_6px_0px_0px_rgba(26,26,26,1)]">
                    <!-- Day Header -->
                    <div class="px-6 py-5 border-b-4 border-nusarasa-dark {{ $dayColor }} flex items-center justify-between">
                        <h2 class="text-xl font-black uppercase tracking-tight text-nusarasa-dark">
                            {{ $dayNames[$day] ?? $day }}
                        </h2>
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-nusarasa-dark/65 bg-white/70 px-2 py-0.5 border-2 border-nusarasa-dark rounded-pill">
                            HARI {{ $index + 1 }}
                        </span>
                    </div>
                    
                    <!-- Slots (Breakfast, Lunch, Dinner) -->
                    <div class="p-5 flex-1 flex flex-col gap-5 bg-nusarasa-cream/10">
                        @foreach($mealTypes as $mealType)
                            @php
                                $typeNames = [
                                    'breakfast' => 'Sarapan',
                                    'lunch' => 'Makan Siang',
                                    'dinner' => 'Makan Malam'
                                ];
                                $typeEmojis = [
                                    'breakfast' => '🍳',
                                    'lunch' => '🍱',
                                    'dinner' => '🍲'
                                ];
                                $item = $mealPlan?->items->where('day_of_week', $day)->where('meal_type', $mealType)->first();
                            @endphp

                            @if($item)
                                <div class="group relative flex flex-row bg-white border-2 border-nusarasa-dark rounded-2xl overflow-hidden hover:-translate-y-0.5 hover:shadow-[4px_4px_0px_0px_rgba(26,26,26,1)] transition-all duration-300 h-28 flex-shrink-0">
                                    <!-- Image Container with Delete Button -->
                                    <div class="relative w-24 h-full bg-gray-100 overflow-hidden border-r-2 border-nusarasa-dark flex-shrink-0">
                                        <img src="{{ $item->recipe_id ? ($item->recipe->image_url ?? 'https://placehold.co/400x300?text=No+Image') : ($item->meal_api_image ?? 'https://placehold.co/400x300?text=No+Image') }}" 
                                             alt="{{ $item->recipe_id ? ($item->recipe->title ?? 'Resep') : ($item->meal_api_title ?? 'Resep') }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                             onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=No+Image';">
                                        
                                        <!-- Delete Button Inside Image Container to avoid content overlaps -->
                                        <button class="delete-meal-item absolute top-1.5 left-1.5 w-6 h-6 bg-white border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition duration-200 shadow-[1px_1px_0px_0px_rgba(26,26,26,1)] active:translate-x-[0.5px] active:translate-y-[0.5px] active:shadow-none cursor-pointer z-10" 
                                                data-id="{{ $item->id }}"
                                                title="Hapus dari Rencana Makan"
                                                style="font-size: 9px; line-height: 1;">
                                            ✕
                                        </button>
                                    </div>

                                    <!-- Content Container -->
                                    <div class="p-3 flex flex-col justify-center flex-1 min-w-0">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <!-- Meal Type Overlay Badge -->
                                                <span class="px-2 py-0.5 bg-nusarasa-cream border-2 border-nusarasa-dark text-nusarasa-dark flex items-center gap-1 whitespace-nowrap rounded-md shadow-[1px_1px_0px_0px_rgba(26,26,26,1)] font-black uppercase tracking-wider" style="font-size: 8px; line-height: 1;">
                                                    <span>{{ $typeEmojis[$mealType] ?? '🍽️' }}</span>
                                                    <span>{{ $typeNames[$mealType] ?? $mealType }}</span>
                                                </span>

                                                <!-- Source Tag -->
                                                <span class="px-2 py-0.5 font-black uppercase tracking-wider rounded-md border-2 border-nusarasa-dark whitespace-nowrap shadow-[1px_1px_0px_0px_rgba(26,26,26,1)] {{ $item->recipe_id ? 'bg-[#D4E2D4]' : 'bg-[#D0E8FF]' }}" style="font-size: 8px; line-height: 1;">
                                                    {{ $item->recipe_id ? 'Lokal' : 'API' }}
                                                </span>
                                            </div>

                                            @if($item->recipe_id)
                                                <a href="{{ route('recipes.show', $item->recipe_id) }}" 
                                                   class="text-nusarasa-dark hover:text-nusarasa-pink font-black uppercase tracking-tight leading-tight line-clamp-2 block mt-1 hover:underline decoration-2" style="font-size: 11px; line-height: 1.3;">
                                                    {{ $item->recipe->title ?? 'Resep Lokal' }}
                                                </a>
                                            @elseif($item->meal_api_id)
                                                <a href="{{ route('recipes.show-api', $item->meal_api_id) }}" 
                                                   class="text-nusarasa-dark hover:text-blue-600 font-black uppercase tracking-tight leading-tight line-clamp-2 block mt-1 hover:underline decoration-2" style="font-size: 11px; line-height: 1.3;">
                                                    {{ $item->meal_api_title ?? 'Resep API' }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('recipes.index') }}" 
                                   class="group flex flex-row items-center p-3 h-20 bg-white hover:bg-nusarasa-cream/35 border-2 border-dashed border-nusarasa-dark/25 hover:border-nusarasa-dark hover:shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] rounded-2xl transition duration-300 text-left gap-3.5 flex-shrink-0">
                                    <div class="w-10 h-10 bg-nusarasa-cream border-2 border-nusarasa-dark/15 group-hover:border-nusarasa-dark group-hover:bg-white rounded-xl flex flex-shrink-0 items-center justify-center text-lg font-black text-nusarasa-dark/45 group-hover:text-nusarasa-dark transition duration-300">
                                        <span>{{ $typeEmojis[$mealType] ?? '🍽️' }}</span>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-black uppercase tracking-wider text-nusarasa-dark/50 group-hover:text-nusarasa-dark" style="font-size: 9px; line-height: 1.2;">
                                            Tambah {{ $typeNames[$mealType] ?? $mealType }}
                                        </span>
                                        <span class="font-bold text-nusarasa-dark/30 group-hover:text-nusarasa-dark/50 whitespace-nowrap" style="font-size: 8px; line-height: 1.2;">
                                            Klik untuk cari resep masakan
                                        </span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 text-center" data-aos="fade-up">
            <p class="text-lg font-bold opacity-40 italic">
                Tips: Tambahkan resep favoritmu ke rencana makan langsung dari halaman detail resep.
            </p>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).on('click', '.delete-meal-item', function() {
            const btn = $(this);
            const id = btn.data('id');
            const item = btn.closest('.group');

            if (confirm('Hapus menu ini dari rencana makan?')) {
                $.ajax({
                    url: `/api/meal-plans/items/${id}`,
                    type: 'DELETE',
                    success: function() {
                        item.fadeOut(300, function() {
                            $(this).remove();
                            // If no items left in this cell, show the + sign
                            const cell = item.parent();
                            if (cell.find('.group').length === 0) {
                                location.reload(); // Simplest way to restore the + layout
                            }
                        });
                        showToast('Menu berhasil dihapus!', 'success');
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message || 'Gagal menghapus menu';
                        showToast(msg, 'error');
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
