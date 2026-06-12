<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-7xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Daftar <span class="text-[#6D28D9]">Belanja</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">
                Pilih hari mana saja yang ingin kamu belanjakan
            </p>
        </div>

        <!-- Back -->
        <div class="mb-8" data-aos="fade-up">
            <a href="{{ route('meal-plan.index') }}"
               class="inline-flex items-center gap-2 font-bold uppercase tracking-widest text-sm hover:text-nusarasa-pink transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Rencana Makan
            </a>
        </div>

        @if(session('error'))
            <div class="mb-6 px-6 py-4 bg-red-100 border-2 border-red-400 rounded-2xl font-bold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($dayPlans))
            <!-- Empty state -->
            <div class="bg-white border-2 border-nusarasa-dark rounded-3xl p-12 text-center shadow-[6px_6px_0px_0px_rgba(26,26,26,1)]" data-aos="fade-up">
                <p class="text-6xl mb-4">🛒</p>
                <h2 class="text-2xl font-black font-display uppercase text-nusarasa-dark mb-2">Rencana Makanmu Kosong</h2>
                <p class="font-bold opacity-60 text-nusarasa-dark mb-6">Tambahkan resep ke rencana makan mingguanmu terlebih dahulu.</p>
                <a href="{{ route('meal-plan.index') }}"
                   class="inline-flex items-center gap-2 px-8 py-3 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full font-black text-xs uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,0.3)] hover:translate-y-0.5 hover:shadow-none transition-all">
                    ➕ Atur Rencana Makan
                </a>
            </div>
        @else
            <form action="{{ route('meal-plan.shopping-list.generate') }}" method="POST" data-aos="fade-up">
                @csrf

                <!-- Info banner -->
                <div class="mb-6 px-6 py-4 bg-nusarasa-yellow/40 border-2 border-nusarasa-dark rounded-2xl flex items-start gap-3">
                    <span class="text-2xl">💡</span>
                    <p class="font-bold text-sm text-nusarasa-dark">
                        Centang hari-hari yang ingin kamu sertakan, lalu klik <strong>Buat Daftar Belanja</strong>.
                        Semua bahan dari resep pada hari yang dipilih akan digabungkan menjadi satu daftar belanja.
                    </p>
                </div>

                <!-- Select / Deselect All -->
                <div class="flex items-center gap-4 mb-5">
                    <button type="button" onclick="toggleAll(true)"
                            class="text-xs font-black uppercase tracking-widest px-4 py-2 border-2 border-nusarasa-dark rounded-full hover:bg-nusarasa-dark hover:text-white transition-all">
                        ✅ Pilih Semua
                    </button>
                    <button type="button" onclick="toggleAll(false)"
                            class="text-xs font-black uppercase tracking-widest px-4 py-2 border-2 border-nusarasa-dark rounded-full hover:bg-nusarasa-dark hover:text-white transition-all">
                        ❌ Batal Semua
                    </button>
                    <span id="selected-count" class="text-sm font-bold opacity-60"></span>
                </div>

                <!-- Day cards grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8" id="day-grid">
                    @foreach($dayPlans as $dayKey => $dayData)
                        <div class="bg-white border-2 border-nusarasa-dark rounded-3xl overflow-hidden shadow-[5px_5px_0px_0px_rgba(26,26,26,1)] day-card transition-all"
                             x-data="{ checked: true }" :class="{ 'border-nusarasa-pink shadow-[5px_5px_0px_0px_rgba(244,114,182,0.45)]': checked }">

                            <!-- Day header — clicking the whole header toggles the checkbox -->
                            <label class="flex items-center gap-3 px-5 py-4 cursor-pointer {{ $dayData['color'] }} border-b-2 border-nusarasa-dark select-none">
                                <input type="checkbox" name="days[]" value="{{ $dayKey }}"
                                       class="day-checkbox w-5 h-5 rounded border-2 border-nusarasa-dark flex-shrink-0 text-nusarasa-pink focus:ring-0"
                                       x-model="checked" checked>

                                <div class="flex-1 min-w-0">
                                    <p class="font-black text-base uppercase tracking-tight text-nusarasa-dark leading-none">
                                        {{ $dayData['label'] }}
                                    </p>
                                    <p class="text-[10px] font-bold text-nusarasa-dark/60 uppercase tracking-widest mt-0.5">
                                        {{ count($dayData['items']) }} menu terjadwal
                                    </p>
                                </div>
                                <div class="text-xl flex-shrink-0" x-show="checked" x-transition>✅</div>
                            </label>

                            <!-- Meal rows for this day -->
                            <div class="divide-y divide-nusarasa-dark/10" :class="{ 'opacity-50': !checked }">
                                @foreach($dayData['items'] as $meal)
                                    <div class="flex items-center gap-3 px-4 py-3">
                                        @if($meal['image_url'])
                                            <img src="{{ $meal['image_url'] }}" alt="{{ $meal['title'] }}"
                                                 class="w-12 h-12 object-cover rounded-xl border border-nusarasa-dark flex-shrink-0"
                                                 onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=🍽';">
                                        @else
                                            <div class="w-12 h-12 bg-nusarasa-cream rounded-xl border border-nusarasa-dark flex-shrink-0 flex items-center justify-center text-2xl">
                                                {{ $meal['meal_emoji'] }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <span class="inline-block px-1.5 py-px bg-nusarasa-cream border border-nusarasa-dark/20 rounded font-black text-[9px] uppercase tracking-widest text-nusarasa-dark/70 mb-0.5">
                                                {{ $meal['meal_emoji'] }} {{ $meal['meal_type'] }}
                                            </span>
                                            <p class="font-black text-xs text-nusarasa-dark leading-tight line-clamp-1">
                                                {{ $meal['title'] }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit -->
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <button type="submit"
                            class="w-full sm:w-auto flex items-center justify-center gap-3 px-10 py-4
                                   bg-nusarasa-dark text-white border-2 border-nusarasa-dark rounded-pill
                                   font-black text-sm uppercase tracking-widest
                                   shadow-[6px_6px_0px_0px_rgba(255,209,0,1)]
                                   hover:translate-y-1 hover:shadow-[2px_2px_0px_0px_rgba(255,209,0,1)]
                                   transition-all">
                        🛒 Buat Daftar Belanja
                    </button>
                    <span id="submit-hint" class="text-xs font-bold opacity-50 uppercase tracking-widest"></span>
                </div>
            </form>
        @endif
    </div>

    <script>
        function toggleAll(state) {
            document.querySelectorAll('.day-checkbox').forEach(cb => {
                cb.checked = state;
                cb.dispatchEvent(new Event('change'));
                const el = cb.closest('[x-data]');
                if (el && el._x_dataStack) {
                    el._x_dataStack[0].checked = state;
                }
            });
            updateCount();
        }

        function updateCount() {
            const total   = document.querySelectorAll('.day-checkbox').length;
            const checked = document.querySelectorAll('.day-checkbox:checked').length;
            const countEl = document.getElementById('selected-count');
            const hintEl  = document.getElementById('submit-hint');
            if (countEl) countEl.textContent = `${checked} dari ${total} hari terpilih`;
            if (hintEl)  hintEl.textContent  = checked === 0 ? 'Pilih minimal 1 hari' : '';
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.day-checkbox').forEach(cb => {
                cb.addEventListener('change', updateCount);
            });
            updateCount();
        });
    </script>
</x-app-layout>
