<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <style>
            #meal-plan-board::-webkit-scrollbar { height: 14px; }
            #meal-plan-board::-webkit-scrollbar-track { background: transparent; border: 4px solid #1a1a1a; border-radius: 100px; }
            #meal-plan-board::-webkit-scrollbar-thumb { background: #1a1a1a; border-radius: 100px; border: 2px solid #ffffff; }
            #meal-plan-board::-webkit-scrollbar-thumb:hover { background: #e5dcf4; }
        </style>

        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-4xl sm:text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Rencana <span class="text-[#6D28D9]">Makan</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest">Atur suasana kulinermu minggu ini</p>
        </div>

        <!-- Dashboard Overview Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-14" data-aos="fade-up">
            <!-- Left Panel (col-span-5) -->
            <div class="lg:col-span-5 flex flex-col gap-6">
                <!-- Streak Card: Retro High Score Console -->
                <div class="bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-3xl p-6 shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] hover:shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] hover:translate-x-[3px] hover:translate-y-[3px] transition-all duration-300 relative overflow-hidden group">
                    <!-- Diagonal stripes background decoration -->
                    <div class="absolute inset-0 opacity-[0.04] bg-[linear-gradient(45deg,#1a1a1a_25%,transparent_25%,transparent_50%,#1a1a1a_50%,#1a1a1a_75%,transparent_75%,transparent)] bg-[size:24px_24px] pointer-events-none"></div>
                    
                    <div class="flex items-center gap-5 relative z-10">
                        <div class="w-16 h-16 bg-white border-2 border-nusarasa-dark rounded-2xl flex items-center justify-center text-4xl shadow-[4px_4px_0px_0px_rgba(26,26,26,1)] flex-shrink-0 transform -rotate-3 group-hover:rotate-3 transition-transform duration-300">
                            🔥
                        </div>
                        <div>
                            <span class="inline-block px-2 py-0.5 bg-nusarasa-dark text-white rounded-md font-black text-[8px] uppercase tracking-widest mb-1.5 shadow-[1px_1px_0px_0px_rgba(26,26,26,1)]">
                                STREAK MINGGUAN
                            </span>
                            <p class="text-4xl font-black text-nusarasa-dark tracking-tighter leading-none">
                                {{ $streak->current_streak }} <span class="text-sm font-bold uppercase tracking-wider text-nusarasa-dark/60">Minggu</span>
                            </p>
                            <div class="mt-2.5 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-orange-600 animate-ping"></span>
                                <span class="text-[9px] font-black uppercase tracking-wider text-nusarasa-dark/50">Lari Terlama: {{ $streak->longest_streak }} mgg</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shopping List Card: Retro Lined Notepad/Receipt -->
                <a href="{{ route('meal-plan.shopping-list') }}" 
                   class="bg-nusarasa-pink border-2 border-nusarasa-dark rounded-3xl p-6 shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] hover:shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] hover:translate-x-[3px] hover:translate-y-[3px] transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
                    <!-- Ruled notebook line background simulated -->
                    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(rgba(26,26,26,0.1)_1px,transparent_1px)] bg-[size:100%_16px] pointer-events-none"></div>
                    
                    <div class="flex items-center gap-5 relative z-10 mb-2">
                        <div class="w-16 h-16 bg-white border-2 border-nusarasa-dark rounded-2xl flex items-center justify-center text-4xl shadow-[4px_4px_0px_0px_rgba(26,26,26,1)] flex-shrink-0 transform rotate-3 group-hover:-rotate-3 transition-transform duration-300">
                            🛒
                        </div>
                        <div>
                            <span class="inline-block px-2 py-0.5 bg-nusarasa-dark text-white rounded-md font-black text-[8px] uppercase tracking-widest mb-1.5 shadow-[1px_1px_0px_0px_rgba(26,26,26,1)]">
                                DAFTAR BELANJA
                            </span>
                            <p class="text-2xl font-black text-nusarasa-dark tracking-tight leading-none">
                                Bahan Masak
                            </p>
                        </div>
                    </div>
                    
                    <div class="relative z-10 pt-2 border-t-2 border-dashed border-nusarasa-dark/20 flex items-center justify-between">
                        <span class="text-[9px] font-black uppercase tracking-wider text-nusarasa-dark/55">PILIH HARI & CETAK</span>
                        <span class="px-2.5 py-1 bg-white border-2 border-nusarasa-dark rounded-lg font-black text-[9px] uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] group-hover:bg-nusarasa-dark group-hover:text-white transition-all">
                            BUKA 📝
                        </span>
                    </div>
                </a>

                <!-- Weekly Nutrition Card: Retro Health Console -->
                @php
                    $maxCal = 14000; $maxProt = 500; $maxCarb = 1800; $maxFat = 500;
                    $calPct = min(100, round(($nutritionSummary['calories'] / $maxCal) * 100));
                    $protPct = min(100, round(($nutritionSummary['protein'] / $maxProt) * 100));
                    $carbPct = min(100, round(($nutritionSummary['carbs'] / $maxCarb) * 100));
                    $fatPct = min(100, round(($nutritionSummary['fat'] / $maxFat) * 100));
                @endphp
                <div class="bg-emerald-100 border-2 border-nusarasa-dark rounded-3xl p-6 shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2 py-0.5 bg-nusarasa-dark text-white rounded-md font-black text-[8px] uppercase tracking-widest shadow-[1px_1px_0px_0px_rgba(26,26,26,1)]">
                            ESTIMASI NUTRISI
                        </span>
                        <span class="text-[9px] font-black uppercase text-nusarasa-dark/45">Rata-rata 7 Hari</span>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-[9px] font-black text-nusarasa-dark/85 uppercase mb-1">
                                <span>🔥 Energi Total</span>
                                <span>{{ number_format($nutritionSummary['calories']) }} / {{ number_format($maxCal) }} kkal</span>
                            </div>
                            <div class="h-3.5 bg-white border-2 border-nusarasa-dark rounded-lg overflow-hidden shadow-[2px_2px_0px_0px_rgba(26,26,26,1)]">
                                <div class="h-full bg-nusarasa-dark rounded-r-md transition-all duration-500" style="width: {{ $calPct }}%"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2.5 pt-1">
                            <div>
                                <div class="flex justify-between text-[7px] font-black text-nusarasa-dark/60 uppercase">
                                    <span>PRO</span>
                                    <span>{{ $protPct }}%</span>
                                </div>
                                <div class="h-2 bg-white border-2 border-nusarasa-dark rounded-md overflow-hidden mt-0.5">
                                    <div class="h-full bg-indigo-500 transition-all duration-500" style="width: {{ $protPct }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-[7px] font-black text-nusarasa-dark/60 uppercase">
                                    <span>KAR</span>
                                    <span>{{ $carbPct }}%</span>
                                </div>
                                <div class="h-2 bg-white border-2 border-nusarasa-dark rounded-md overflow-hidden mt-0.5">
                                    <div class="h-full bg-amber-400 transition-all duration-500" style="width: {{ $carbPct }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-[7px] font-black text-nusarasa-dark/60 uppercase">
                                    <span>LEM</span>
                                    <span>{{ $fatPct }}%</span>
                                </div>
                                <div class="h-2 bg-white border-2 border-nusarasa-dark rounded-md overflow-hidden mt-0.5">
                                    <div class="h-full bg-rose-400 transition-all duration-500" style="width: {{ $fatPct }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel (col-span-7) -->
            <div class="lg:col-span-7 flex">
                <div class="w-full bg-white border-2 border-nusarasa-dark rounded-3xl p-6 md:p-8 shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] relative overflow-hidden flex flex-col justify-between">
                    <!-- Dot overlay grid decoration -->
                    <div class="absolute inset-0 bg-[radial-gradient(#1a1a1a_1px,transparent_1px)] [background-size:20px_20px] opacity-[0.04] pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="mb-6">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-nusarasa-pink border-2 border-nusarasa-dark rounded-xl text-[10px] font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(26,26,26,1)]">
                                📊 Makronutrisi Terencana
                            </span>
                            <h2 class="text-3xl font-black font-display uppercase tracking-tight text-nusarasa-dark leading-none mt-3">
                                Distribusi Nutrisi
                            </h2>
                        </div>

                        @if($mealPlan && $mealPlan->items->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center flex-1">
                                <!-- Left: Doughnut Chart -->
                                <div class="md:col-span-5 flex justify-center">
                                    <div class="relative w-48 h-48 p-2 bg-nusarasa-cream/25 border-2 border-nusarasa-dark rounded-2xl shadow-[4px_4px_0px_0px_rgba(26,26,26,1)]">
                                        <canvas id="nutritionChart" class="w-full h-full"></canvas>
                                    </div>
                                </div>
                                
                                <!-- Right: Detail Stickers -->
                                <div class="md:col-span-7 grid grid-cols-2 gap-3">
                                    <!-- Calories Sticker -->
                                    <div class="p-3 bg-white border-2 border-nusarasa-dark rounded-2xl flex items-center gap-3 shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] transform -rotate-1 hover:rotate-0 transition-transform duration-200">
                                        <span class="w-4 h-4 bg-nusarasa-dark border border-black rounded-md flex-shrink-0 shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]"></span>
                                        <div class="min-w-0">
                                            <p class="text-[8px] font-black uppercase tracking-wider text-nusarasa-dark/40">Kalori</p>
                                            <p class="text-sm font-black text-nusarasa-dark leading-none mt-0.5 truncate">{{ number_format($nutritionSummary['calories']) }} kkal</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Protein Sticker -->
                                    <div class="p-3 bg-white border-2 border-nusarasa-dark rounded-2xl flex items-center gap-3 shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] transform rotate-1 hover:rotate-0 transition-transform duration-200">
                                        <span class="w-4 h-4 bg-indigo-500 border border-black rounded-md flex-shrink-0 shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]"></span>
                                        <div class="min-w-0">
                                            <p class="text-[8px] font-black uppercase tracking-wider text-nusarasa-dark/40">Protein</p>
                                            <p class="text-sm font-black text-nusarasa-dark leading-none mt-0.5 truncate">{{ $nutritionSummary['protein'] }}g</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Carbs Sticker -->
                                    <div class="p-3 bg-white border-2 border-nusarasa-dark rounded-2xl flex items-center gap-3 shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] transform rotate-1 hover:rotate-0 transition-transform duration-200">
                                        <span class="w-4 h-4 bg-amber-400 border border-black rounded-md flex-shrink-0 shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]"></span>
                                        <div class="min-w-0">
                                            <p class="text-[8px] font-black uppercase tracking-wider text-nusarasa-dark/40">Karbohidrat</p>
                                            <p class="text-sm font-black text-nusarasa-dark leading-none mt-0.5 truncate">{{ $nutritionSummary['carbs'] }}g</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Fat Sticker -->
                                    <div class="p-3 bg-white border-2 border-nusarasa-dark rounded-2xl flex items-center gap-3 shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] transform -rotate-1 hover:rotate-0 transition-transform duration-200">
                                        <span class="w-4 h-4 bg-rose-400 border border-black rounded-md flex-shrink-0 shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]"></span>
                                        <div class="min-w-0">
                                            <p class="text-[8px] font-black uppercase tracking-wider text-nusarasa-dark/40">Lemak</p>
                                            <p class="text-sm font-black text-nusarasa-dark leading-none mt-0.5 truncate">{{ $nutritionSummary['fat'] }}g</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Fiber Sticker -->
                                    <div class="p-3 bg-white border-2 border-nusarasa-dark rounded-2xl flex items-center gap-3 shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] sm:col-span-2 transform rotate-0.5 hover:rotate-0 transition-transform duration-200">
                                        <span class="w-4 h-4 bg-emerald-400 border border-black rounded-md flex-shrink-0 shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]"></span>
                                        <div class="min-w-0">
                                            <p class="text-[8px] font-black uppercase tracking-wider text-nusarasa-dark/40">Serat Makanan</p>
                                            <p class="text-sm font-black text-nusarasa-dark leading-none mt-0.5 truncate">{{ $nutritionSummary['fiber'] }}g</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-12 px-4 h-full flex-grow">
                                <span class="text-5xl mb-3">🥗</span>
                                <h3 class="text-lg font-black uppercase tracking-tight text-nusarasa-dark">Belum Ada Rencana Makan</h3>
                                <p class="text-xs font-bold text-nusarasa-dark/50 mt-1 max-w-sm">Tambahkan beberapa menu di bawah untuk melihat diagram distribusi dan rincian nutrisi mingguan Anda!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Meal Plan Board -->
        <div class="flex overflow-x-auto gap-8 pb-8 snap-x snap-mandatory scroll-smooth px-2 py-4" id="meal-plan-board" data-aos="fade-up">
            @foreach($days as $index => $day)
                @php
                    $dayNames = ['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'];
                    $dayColors = ['monday' => 'bg-nusarasa-pink', 'tuesday' => 'bg-nusarasa-purple', 'wednesday' => 'bg-nusarasa-yellow', 'thursday' => 'bg-emerald-100', 'friday' => 'bg-orange-100', 'saturday' => 'bg-blue-100', 'sunday' => 'bg-red-100'];
                    $dayColor = $dayColors[$day] ?? 'bg-white';
                @endphp
                
                <div class="flex-shrink-0 w-[300px] sm:w-[350px] snap-start flex flex-col bg-white border-2 border-nusarasa-dark rounded-3xl shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] overflow-hidden transition-all hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[4px_4px_0px_0px_rgba(26,26,26,1)]">
                    <div class="px-6 py-5 border-b-2 border-nusarasa-dark {{ $dayColor }} flex items-center justify-between">
                        <h2 class="text-xl font-black uppercase tracking-tight text-nusarasa-dark">{{ $dayNames[$day] ?? $day }}</h2>
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-nusarasa-dark/65 bg-white/70 px-2 py-0.5 border border-nusarasa-dark rounded-pill">HARI {{ $index + 1 }}</span>
                    </div>
                    
                    <div class="p-5 flex-1 flex flex-col gap-5 bg-nusarasa-cream/10">
                        @foreach($mealTypes as $mealType)
                            @php
                                $typeNames = ['breakfast' => 'Sarapan', 'lunch' => 'Makan Siang', 'dinner' => 'Makan Malam', 'snacks' => 'Cemilan'];
                                $typeEmojis = ['breakfast' => '🍳', 'lunch' => '🍱', 'dinner' => '🍲', 'snacks' => '🍿'];
                                $item = $mealPlan?->items->where('day_of_week', $day)->where('meal_type', $mealType)->first();
                            @endphp

                            @if($item)
                                <div class="meal-card-wrap group relative flex flex-col bg-white border-2 border-nusarasa-dark rounded-2xl overflow-hidden transition-all duration-300 flex-shrink-0">
                                    <button class="delete-meal-item absolute top-2 right-2 w-6 h-6 bg-white hover:bg-red-500 text-nusarasa-dark hover:text-white border border-nusarasa-dark rounded-full flex items-center justify-center shadow-[1px_1px_0px_0px_rgba(26,26,26,1)] hover:shadow-none hover:translate-x-[0.5px] hover:translate-y-[0.5px] transition-all z-20 cursor-pointer text-xs font-black"
                                            data-id="{{ $item->id }}" 
                                            title="Hapus Menu">
                                        ✕
                                    </button>
                                    <div class="flex flex-row h-28">
                                        <div class="relative w-24 bg-gray-100 overflow-hidden border-r-2 border-nusarasa-dark flex-shrink-0">
                                            <img src="{{ $item->recipe_id ? ($item->recipe->image_url ?? 'https://placehold.co/400x300?text=No+Image') : ($item->meal_api_image ?? 'https://placehold.co/400x300?text=No+Image') }}"
                                                 alt="recipe" class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                                 onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=No+Image';">
                                        </div>
                                        <div class="p-3 flex flex-col justify-center flex-1 min-w-0 pr-8">
                                            <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                                <span class="px-2 py-0.5 bg-nusarasa-cream border border-nusarasa-dark text-nusarasa-dark flex items-center gap-1 whitespace-nowrap rounded-md shadow-[1px_1px_0px_0px_rgba(26,26,26,1)] font-black uppercase tracking-wider" style="font-size: 8px; line-height: 1;">
                                                    <span>{{ $typeEmojis[$mealType] ?? '🍽️' }}</span>
                                                    <span>{{ $typeNames[$mealType] ?? $mealType }}</span>
                                                </span>
                                                <span class="px-2 py-0.5 font-black uppercase tracking-wider rounded-md border border-nusarasa-dark whitespace-nowrap shadow-[1px_1px_0px_0px_rgba(26,26,26,1)] {{ $item->recipe_id ? 'bg-[#D4E2D4]' : 'bg-[#D0E8FF]' }}" style="font-size: 8px; line-height: 1;">
                                                    {{ $item->recipe_id ? 'Lokal' : 'API' }}
                                                </span>
                                            </div>
                                            @if($item->recipe_id)
                                                <a href="{{ route('recipes.show', $item->recipe_id) }}" class="text-nusarasa-dark hover:text-nusarasa-pink font-black uppercase tracking-tight leading-tight line-clamp-2 block hover:underline decoration-2" style="font-size: 11px; line-height: 1.3;">
                                                    {{ $item->recipe->title ?? 'Resep Lokal' }}
                                                </a>
                                            @elseif($item->meal_api_id)
                                                <a href="{{ route('recipes.show-api', $item->meal_api_id) }}" class="text-nusarasa-dark hover:text-blue-600 font-black uppercase tracking-tight leading-tight line-clamp-2 block hover:underline decoration-2" style="font-size: 11px; line-height: 1.3;">
                                                    {{ $item->meal_api_title ?? 'Resep API' }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('recipes.index') }}" class="group flex flex-row items-center p-3 h-20 bg-white hover:bg-nusarasa-cream/35 border-2 border-dashed border-nusarasa-dark/25 hover:border-nusarasa-dark hover:shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] rounded-2xl transition duration-300 text-left gap-3.5 flex-shrink-0">
                                    <div class="w-10 h-10 bg-nusarasa-cream border-2 border-nusarasa-dark/15 group-hover:border-nusarasa-dark group-hover:bg-white rounded-xl flex flex-shrink-0 items-center justify-center text-lg font-black text-nusarasa-dark/45 group-hover:text-nusarasa-dark transition duration-300">
                                        <span>{{ $typeEmojis[$mealType] ?? '🍽️' }}</span>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-black uppercase tracking-wider text-nusarasa-dark/50 group-hover:text-nusarasa-dark" style="font-size: 9px; line-height: 1.2;">Tambah {{ $typeNames[$mealType] ?? $mealType }}</span>
                                        <span class="font-bold text-nusarasa-dark/30 group-hover:text-nusarasa-dark/50 whitespace-nowrap" style="font-size: 8px; line-height: 1.2;">Klik untuk cari resep masakan</span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Templates Dashboard Widget -->
        @if($templates->isNotEmpty())
            <div class="mt-16" data-aos="fade-up">
                <div class="bg-nusarasa-cream/40 border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-10 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
                    
                    <!-- Widget Header -->
                    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white border-4 border-nusarasa-dark rounded-2xl flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(26,26,26,1)] rotate-[-3deg]">
                                📋
                            </div>
                            <div>
                                <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark leading-none">Templates Mingguan</h2>
                                <p class="text-xs font-bold text-nusarasa-dark/60 uppercase tracking-widest mt-1">Siap pakai dari para Chef</p>
                            </div>
                        </div>
                        <a href="{{ route('meal-plan.templates') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-nusarasa-purple text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(26,26,26,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                            Lihat Semua →
                        </a>
                    </div>

                    @php
                        $badgeColors = [
                            'bg-nusarasa-pink', 
                            'bg-nusarasa-yellow', 
                            'bg-emerald-300', 
                            'bg-orange-300', 
                            'bg-blue-300'
                        ];
                    @endphp

                    <!-- Horizontal Scroll Area -->
                    <div class="flex overflow-x-auto gap-6 pb-6 pt-2 snap-x snap-mandatory hide-scrollbar">
                        @foreach($templates->take(8) as $index => $template)
                            @php
                                $badgeColor = $badgeColors[$index % count($badgeColors)];
                            @endphp

                            <div class="min-w-[280px] w-[280px] snap-center bg-white border-2 border-nusarasa-dark rounded-3xl overflow-hidden shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] hover:shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] hover:translate-x-[3px] hover:translate-y-[3px] transition-all flex flex-col flex-shrink-0 relative group">
                                
                                <div class="p-6 flex flex-col flex-grow">
                                    <!-- Badges -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @if($template->goal)
                                            <span class="px-2.5 py-1 {{ $badgeColor }} border-2 border-nusarasa-dark rounded-pill font-black text-[9px] uppercase tracking-widest text-nusarasa-dark">
                                                🎯 {{ $template->goal }}
                                            </span>
                                        @endif
                                        <span class="px-2.5 py-1 bg-nusarasa-dark text-white border-2 border-nusarasa-dark rounded-pill font-black text-[9px] uppercase tracking-widest">
                                            {{ $template->items->count() }} Menu
                                        </span>
                                    </div>

                                    <!-- Title & Description -->
                                    <h3 class="text-xl font-black text-nusarasa-dark tracking-tight leading-tight line-clamp-2 mb-2 group-hover:text-nusarasa-purple transition-colors">{{ $template->name }}</h3>
                                    
                                    @if($template->description)
                                        <p class="text-xs font-bold text-nusarasa-dark/60 line-clamp-2 mb-5 leading-relaxed">{{ $template->description }}</p>
                                    @else
                                        <p class="text-xs font-bold text-nusarasa-dark/40 italic mb-5">Rencana makan mingguan siap pakai.</p>
                                    @endif

                                    <!-- Chef Info -->
                                    <div class="mt-auto">
                                        @if($template->chef)
                                            <div class="flex items-center gap-3 mb-5 border-t-2 border-dashed border-nusarasa-dark/10 pt-4">
                                                <div class="w-8 h-8 bg-nusarasa-cream text-nusarasa-dark border-2 border-nusarasa-dark rounded-full flex items-center justify-center font-black text-xs shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] flex-shrink-0">
                                                    {{ strtoupper(substr($template->chef->name, 0, 1)) }}
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="text-[8px] font-black uppercase tracking-widest text-nusarasa-dark/50">Dibuat oleh</p>
                                                    <p class="text-xs font-black text-nusarasa-dark uppercase tracking-wider truncate">{{ $template->chef->name }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        <button class="apply-template-btn w-full py-3 bg-nusarasa-yellow text-nusarasa-dark border-2 border-nusarasa-dark rounded-xl font-black text-[11px] uppercase tracking-widest shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] hover:bg-nusarasa-dark hover:text-white hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all flex justify-center items-center gap-2"
                                                data-template-id="{{ $template->id }}">
                                            <span>🚀</span> Terapkan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-12 text-center" data-aos="fade-up">
            <p class="text-lg font-bold opacity-40 italic">Tips: Tambahkan resep favoritmu ke rencana makan langsung dari halaman detail resep.</p>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).on('click', '.delete-meal-item', function() {
            const btn = $(this);
            const id = btn.data('id');
            const item = btn.closest('.meal-card-wrap');
            if (confirm('Hapus menu ini dari rencana makan?')) {
                $.ajax({
                    url: `/meal-plan/items/${id}`,
                    type: 'DELETE',
                    success: function() {
                        item.fadeOut(300, function() {
                            $(this).remove();
                            // Reload to refresh the empty slot placeholder
                            setTimeout(() => location.reload(), 400);
                        });
                        showToast('Menu berhasil dihapus!', 'success');
                    },
                    error: function(xhr) {
                        showToast(xhr.responseJSON?.message || 'Gagal menghapus menu', 'error');
                    }
                });
            }
        });

        // Apply template
        $(document).on('click', '.apply-template-btn', function() {
            const btn = $(this);
            const templateId = btn.data('template-id');
            if (!confirm('Ini akan menimpa rencana makan minggu ini. Lanjutkan?')) return;

            btn.prop('disabled', true).text('Menerapkan...');
            $.ajax({
                url: `/api/meal-plan-templates/${templateId}/apply`,
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(res) {
                    showToast('Templat berhasil diterapkan! Memuat ulang...', 'success');
                    setTimeout(() => location.reload(), 1200);
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Gagal menerapkan templat', 'error');
                    btn.prop('disabled', false).text('Terapkan ke Minggu Ini');
                }
            });
        });

        // Nutrition Chart
        @if(isset($nutritionSummary) && ($nutritionSummary['calories'] > 0 || $nutritionSummary['protein'] > 0))
        const ctx = document.getElementById('nutritionChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Kalori', 'Protein', 'Karbo', 'Lemak', 'Serat'],
                    datasets: [{
                        data: [
                            {{ $nutritionSummary['calories'] }},
                            {{ $nutritionSummary['protein'] }},
                            {{ $nutritionSummary['carbs'] }},
                            {{ $nutritionSummary['fat'] }},
                            {{ $nutritionSummary['fiber'] }}
                        ],
                        backgroundColor: ['#1a1a1a', '#6366f1', '#fbbf24', '#fb7185', '#34d399'],
                        borderColor: '#1a1a1a',
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
        @endif
    </script>
    @endpush
</x-app-layout>
