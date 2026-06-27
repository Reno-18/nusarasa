<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-7xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Template <span class="text-[#6D28D9]">Mingguan</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Buat template rencana makan mingguan untuk pengguna</p>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12" data-aos="fade-up">
            <p class="text-sm font-black uppercase tracking-widest text-nusarasa-dark/60">Total: <span class="text-nusarasa-dark">{{ $templates->total() }} template</span></p>
            <a href="{{ route('chef.meal-plan-templates.create') }}"
               class="inline-flex items-center gap-3 px-10 py-4 bg-nusarasa-yellow text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-sm uppercase tracking-widest shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Buat Template Baru
            </a>
        </div>

        <!-- Success / Error Alerts -->
        @if(session('success'))
            <div class="mb-8 p-6 bg-green-100 border-4 border-nusarasa-dark rounded-2xl font-bold text-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-in">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if($templates->isEmpty())
            <div class="text-center py-24 bg-white border-4 border-dashed border-nusarasa-dark/20 rounded-[3rem]" data-aos="zoom-in">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-2xl font-black uppercase tracking-tight text-nusarasa-dark mb-2">Belum Ada Template</h3>
                <p class="text-sm font-bold opacity-60 max-w-md mx-auto mb-8">Buat templat rencana makan mingguan yang bisa diterapkan pengguna ke jadwal mereka.</p>
                <a href="{{ route('chef.meal-plan-templates.create') }}" class="inline-block px-10 py-4 bg-nusarasa-yellow text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-sm uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-0.5 hover:shadow-none transition-all">
                    Buat Sekarang
                </a>
            </div>
        @else
            @php
                $headerColors = ['bg-nusarasa-purple', 'bg-nusarasa-pink', 'bg-nusarasa-yellow', 'bg-emerald-300', 'bg-orange-300', 'bg-blue-300'];
                $headerTextColors = ['text-white', 'text-nusarasa-dark', 'text-nusarasa-dark', 'text-nusarasa-dark', 'text-nusarasa-dark', 'text-nusarasa-dark'];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($templates as $index => $template)
                    @php
                        $ci = $index % count($headerColors);
                        $headerBg = $headerColors[$ci];
                        $headerText = $headerTextColors[$ci];
                    @endphp
                    <div class="bg-white border-2 border-nusarasa-dark rounded-3xl overflow-hidden shadow-[6px_6px_0px_0px_rgba(26,26,26,1)] hover:shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] hover:translate-x-[3px] hover:translate-y-[3px] transition-all flex flex-col group" data-aos="fade-up">
                        
                        <!-- Card Header -->
                        <div class="px-7 py-6 {{ $headerBg }} border-b-2 border-nusarasa-dark">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <h3 class="text-2xl font-black {{ $headerText }} tracking-tight leading-tight">{{ $template->name }}</h3>
                                <div class="w-10 h-10 bg-white border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-xl shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] flex-shrink-0">📋</div>
                            </div>
                            @if($template->goal)
                                <span class="inline-block px-3 py-1 bg-nusarasa-dark text-white border-2 border-nusarasa-dark rounded-pill font-black text-[10px] uppercase tracking-widest">
                                    🎯 {{ $template->goal }}
                                </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex flex-col flex-grow">
                            @if($template->description)
                                <p class="text-sm font-bold text-nusarasa-dark/70 mb-5 line-clamp-2 leading-relaxed">{{ $template->description }}</p>
                            @else
                                <p class="text-sm font-bold text-nusarasa-dark/40 italic mb-5">Rencana makan mingguan siap pakai.</p>
                            @endif

                            <div class="flex items-center gap-3 mb-6 border-t-2 border-dashed border-nusarasa-dark/10 pt-4">
                                <div class="w-9 h-9 bg-nusarasa-dark text-white border-2 border-nusarasa-dark rounded-full flex items-center justify-center font-black text-sm shadow-[2px_2px_0px_0px_rgba(26,26,26,1)] flex-shrink-0">
                                    {{ $template->items->count() }}
                                </div>
                                <span class="text-xs font-black uppercase tracking-widest text-nusarasa-dark/60">Item dalam template</span>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3 mt-auto">
                                <a href="{{ route('chef.meal-plan-templates.edit', $template->id) }}"
                                   class="flex-1 text-center py-3 bg-nusarasa-yellow text-nusarasa-dark border-2 border-nusarasa-dark rounded-xl font-black text-xs uppercase tracking-widest shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] hover:bg-nusarasa-dark hover:text-white hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('chef.meal-plan-templates.destroy', $template->id) }}" onsubmit="return confirm('Hapus templat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-3 px-5 bg-red-50 text-red-600 border-2 border-nusarasa-dark rounded-xl font-black text-xs uppercase tracking-widest shadow-[3px_3px_0px_0px_rgba(26,26,26,1)] hover:bg-red-600 hover:text-white hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                                        🗑
                                    </button>
                                </form>
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
</x-app-layout>
