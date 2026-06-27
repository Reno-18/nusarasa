<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-4xl sm:text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Templates <span class="text-[#6D28D9]">Mingguan</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Rencana makan siap pakai dari para Chef terbaik</p>
        </div>

        <!-- Back + Count Row -->
        <div class="flex items-center justify-between mb-12" data-aos="fade-up">
            <a href="{{ route('meal-plan.index') }}" class="inline-flex items-center gap-2 font-bold uppercase tracking-widest text-sm hover:text-nusarasa-purple transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Rencana Makan
            </a>
            <p class="text-sm font-black uppercase tracking-widest text-nusarasa-dark/50">
                {{ $templates->total() }} templates tersedia
            </p>
        </div>

        @if($templates->isEmpty())
            <div class="text-center py-24 bg-white border-4 border-dashed border-nusarasa-dark/20 rounded-[3rem]" data-aos="zoom-in">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-2xl font-black uppercase tracking-tight text-nusarasa-dark mb-2">Belum Ada Templates</h3>
                <p class="text-sm font-bold opacity-60 max-w-md mx-auto">Para chef belum membuat templates rencana makan. Cek lagi nanti!</p>
            </div>
        @else
            @php
                $badgeColors = [
                    'bg-nusarasa-pink', 
                    'bg-nusarasa-yellow', 
                    'bg-emerald-300', 
                    'bg-orange-300', 
                    'bg-blue-300'
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-aos="fade-up">
                @foreach($templates as $index => $template)
                    @php
                        $badgeColor = $badgeColors[$index % count($badgeColors)];
                    @endphp

                    <div class="bg-white border-2 border-nusarasa-dark rounded-3xl overflow-hidden shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] hover:shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] hover:translate-x-[3px] hover:translate-y-[3px] transition-all flex flex-col group">
                        
                        <!-- Card Body -->
                        <div class="p-8 flex flex-col flex-grow">
                            <!-- Badges -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($template->goal)
                                    <span class="px-3 py-1.5 {{ $badgeColor }} border-2 border-nusarasa-dark rounded-pill font-black text-[10px] uppercase tracking-widest text-nusarasa-dark">
                                        🎯 {{ $template->goal }}
                                    </span>
                                @endif
                                <span class="px-3 py-1.5 bg-nusarasa-dark text-white border-2 border-nusarasa-dark rounded-pill font-black text-[10px] uppercase tracking-widest">
                                    {{ $template->items->count() }} Menu
                                </span>
                            </div>

                            <!-- Title & Description -->
                            <h3 class="text-2xl font-black text-nusarasa-dark tracking-tight leading-tight line-clamp-2 mb-3 group-hover:text-nusarasa-purple transition-colors">{{ $template->name }}</h3>
                            
                            @if($template->description)
                                <p class="text-sm font-bold text-nusarasa-dark/60 line-clamp-3 mb-6 leading-relaxed">{{ $template->description }}</p>
                            @else
                                <p class="text-sm font-bold text-nusarasa-dark/40 italic mb-6">Rencana makan mingguan siap pakai.</p>
                            @endif

                            <!-- Chef Info -->
                            <div class="mt-auto">
                                @if($template->chef)
                                    <div class="flex items-center gap-3 mb-6 border-t-2 border-dashed border-nusarasa-dark/10 pt-5">
                                        <div class="w-10 h-10 bg-nusarasa-cream text-nusarasa-dark border-2 border-nusarasa-dark rounded-full flex items-center justify-center font-black text-sm shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] flex-shrink-0">
                                            {{ strtoupper(substr($template->chef->name, 0, 1)) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-[9px] font-black uppercase tracking-widest text-nusarasa-dark/50">Dibuat oleh</p>
                                            <p class="text-sm font-black text-nusarasa-dark uppercase tracking-wider truncate">{{ $template->chef->name }}</p>
                                        </div>
                                    </div>
                                @endif

                                <button class="apply-template-btn w-full py-4 bg-nusarasa-yellow text-nusarasa-dark border-2 border-nusarasa-dark rounded-xl font-black text-xs uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(26,26,26,1)] hover:bg-nusarasa-dark hover:text-white hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all flex justify-center items-center gap-2"
                                        data-template-id="{{ $template->id }}">
                                    <span>🚀</span> Terapkan Ke Minggu Ini
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $templates->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
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
                    showToast('Templates berhasil diterapkan! Mengarahkan...', 'success');
                    setTimeout(() => window.location.href = '{{ route('meal-plan.index') }}', 1500);
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Gagal menerapkan templates', 'error');
                    btn.prop('disabled', false).text('🚀 Terapkan ke Minggu Ini');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
