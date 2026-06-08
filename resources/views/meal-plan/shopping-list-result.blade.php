<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-7xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Daftar <span class="text-nusarasa-yellow">Belanja</span>
            </h1>
            @php
                $dayLabels = ['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'];
                $selectedLabels = collect($selectedDays)->map(fn($d) => $dayLabels[$d] ?? $d)->join(', ');
            @endphp
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">
                {{ $selectedLabels }} · {{ count($shoppingList['flat_list']) }} bahan unik
            </p>
        </div>

        <!-- Actions bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-10 print:hidden" data-aos="fade-up">
            <a href="{{ route('meal-plan.shopping-list') }}"
               class="inline-flex items-center gap-2 font-bold uppercase tracking-widest text-sm hover:text-nusarasa-pink transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ubah Pilihan Hari
            </a>
            <button onclick="window.print()"
                    class="inline-flex items-center gap-3 px-8 py-3 bg-nusarasa-dark text-white border-2 border-nusarasa-dark
                           rounded-pill font-black text-xs uppercase tracking-widest
                           shadow-[4px_4px_0px_0px_rgba(255,209,0,1)] hover:translate-y-0.5 hover:shadow-none transition-all">
                🖨️ Cetak Daftar
            </button>
        </div>

        <!-- Selected days chips -->
        <div class="flex flex-wrap gap-2 mb-8 print:hidden" data-aos="fade-up">
            @foreach($selectedDays as $day)
                <span class="px-4 py-1.5 bg-nusarasa-purple text-white border-2 border-nusarasa-dark rounded-full font-black text-[10px] uppercase tracking-widest">
                    {{ $dayLabels[$day] ?? $day }}
                </span>
            @endforeach
        </div>

        <!-- Consolidated checklist -->
        <div class="bg-white border-2 border-nusarasa-dark rounded-3xl p-8 mb-10 shadow-[6px_6px_0px_0px_rgba(26,26,26,1)]" data-aos="fade-up">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                <h2 class="text-2xl font-black font-display uppercase tracking-tight text-nusarasa-dark flex items-center gap-3">
                    <span class="text-3xl">🛒</span> Semua Bahan
                </h2>
                <div class="flex gap-3 print:hidden">
                    <button onclick="setAll(true)"
                            class="text-xs font-black uppercase tracking-widest px-4 py-1.5 border-2 border-nusarasa-dark rounded-full hover:bg-nusarasa-dark hover:text-white transition-all">
                        ✅ Semua
                    </button>
                    <button onclick="setAll(false)"
                            class="text-xs font-black uppercase tracking-widest px-4 py-1.5 border-2 border-nusarasa-dark rounded-full hover:bg-nusarasa-dark hover:text-white transition-all">
                        ↩ Reset
                    </button>
                </div>
            </div>

            @if(empty($shoppingList['flat_list']))
                <p class="text-center font-bold opacity-50 py-8">Tidak ada bahan yang ditemukan untuk hari yang dipilih.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="shopping-checklist">
                    @foreach($shoppingList['flat_list'] as $index => $item)
                        <label class="flex items-center gap-3 p-3.5 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl
                                      cursor-pointer hover:bg-nusarasa-yellow/30 transition-colors checklist-item shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px]"
                               x-data="{ checked: false }"
                               :class="{ 'opacity-50': checked }">
                            <input type="checkbox" class="check-input w-4 h-4 rounded border-2 border-nusarasa-dark text-nusarasa-pink focus:ring-0 flex-shrink-0"
                                   x-model="checked">
                            <span class="font-bold text-sm text-nusarasa-dark flex-1" :class="{ 'line-through': checked }">
                                {{ $item['original'] }}
                            </span>
                            @if($item['count'] > 1)
                                <span class="px-2 py-0.5 bg-nusarasa-pink border border-nusarasa-dark rounded-md font-black text-[10px] uppercase flex-shrink-0">
                                    ×{{ $item['count'] }}
                                </span>
                            @endif
                        </label>
                    @endforeach
                </div>

                <!-- Progress bar -->
                <div class="mt-6 print:hidden" x-data="checklistProgress()">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-black uppercase tracking-widest opacity-60">Sudah disiapkan</span>
                        <span class="text-xs font-black" x-text="`${done} / ${total}`"></span>
                    </div>
                    <div class="h-3 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-full overflow-hidden">
                        <div class="h-full bg-nusarasa-purple transition-all duration-300 rounded-full"
                             :style="`width: ${percent}%`"></div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Per-recipe breakdown -->
        @if(!empty($shoppingList['recipes']))
            <div class="space-y-5" data-aos="fade-up">
                <h2 class="text-2xl font-black font-display uppercase tracking-tight text-nusarasa-dark flex items-center gap-3">
                    <span class="text-3xl">📋</span> Detail Bahan per Resep
                </h2>
                @foreach($shoppingList['recipes'] as $recipeItem)
                    <div class="bg-white border-2 border-nusarasa-dark rounded-3xl overflow-hidden shadow-[5px_5px_0px_0px_rgba(26,26,26,1)]">
                        <div class="px-6 py-4 bg-nusarasa-purple border-b-2 border-nusarasa-dark flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <h3 class="text-base font-black text-white uppercase tracking-tight leading-tight">
                                    {{ $recipeItem['title'] }}
                                </h3>
                                <span class="text-white/70 font-bold text-xs">
                                    {{ $recipeItem['day'] }} · {{ $recipeItem['meal_type'] }}
                                </span>
                            </div>
                            <span class="px-3 py-1 bg-white/20 border border-white/30 rounded-lg text-[10px] font-black text-white uppercase tracking-widest">
                                {{ count($recipeItem['ingredients']) }} bahan
                            </span>
                        </div>
                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 bg-nusarasa-cream/10">
                            @foreach($recipeItem['ingredients'] as $ingredient)
                                <div class="flex items-start gap-2 p-2 rounded-xl hover:bg-nusarasa-cream/40 transition-colors">
                                    <span class="mt-1.5 w-2 h-2 rounded-full bg-nusarasa-purple flex-shrink-0"></span>
                                    <span class="text-sm font-bold text-nusarasa-dark">{{ $ingredient }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Print styles --}}
    <style>
        @media print {
            body { background: white !important; }
            .print\:hidden { display: none !important; }
            nav, footer { display: none !important; }
        }
    </style>

    <script>
        function setAll(state) {
            document.querySelectorAll('.check-input').forEach(cb => {
                if (cb.checked !== state) {
                    cb.checked = state;
                    cb.dispatchEvent(new Event('change'));
                    const el = cb.closest('[x-data]');
                    if (el && el._x_dataStack) {
                        el._x_dataStack[0].checked = state;
                    }
                }
            });
        }

        function checklistProgress() {
            return {
                total: 0, done: 0,
                get percent() { return this.total > 0 ? Math.round((this.done / this.total) * 100) : 0; },
                init() {
                    const update = () => {
                        this.total = document.querySelectorAll('.check-input').length;
                        this.done  = document.querySelectorAll('.check-input:checked').length;
                    };
                    update();
                    document.querySelectorAll('.check-input').forEach(cb => cb.addEventListener('change', update));
                }
            };
        }
    </script>
</x-app-layout>
