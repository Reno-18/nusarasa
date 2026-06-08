<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-16" data-aos="fade-down">
            <div>
                <h1 class="text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark">Dapur<br><span class="text-[#6D28D9]">Kreatif</span></h1>
                <p class="font-bold opacity-60 text-sm uppercase tracking-widest mt-4 ml-2">Kelola resep dan pantau performa kulinermu</p>
            </div>
            <a href="{{ route('chef.recipes.create') }}" 
               class="px-12 py-6 bg-nusarasa-dark text-white rounded-pill font-black text-xs uppercase tracking-widest hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                + Tambah Resep Baru
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-16">
            <div class="bg-white border-4 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all" data-aos="fade-up" data-aos-delay="0">
                <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-4">Total Karya</div>
                <div class="text-6xl font-black font-display text-nusarasa-dark tracking-tighter">{{ $totalRecipes }}</div>
            </div>
            <div class="bg-nusarasa-purple border-4 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all" data-aos="fade-up" data-aos-delay="100">
                <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-4">Sudah Tayang</div>
                <div class="text-6xl font-black font-display text-nusarasa-dark tracking-tighter">{{ $approvedRecipes }}</div>
            </div>
            <div class="bg-nusarasa-yellow border-4 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all" data-aos="fade-up" data-aos-delay="200">
                <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-4">Dalam Antrean</div>
                <div class="text-6xl font-black font-display text-nusarasa-dark tracking-tighter">{{ $pendingRecipes }}</div>
            </div>
            <div class="bg-nusarasa-pink border-4 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all" data-aos="fade-up" data-aos-delay="300">
                <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-4">Total Dilihat</div>
                <div class="text-6xl font-black font-display text-nusarasa-dark tracking-tighter">{{ number_format($totalViews) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Views Chart -->
            <div class="lg:col-span-12">
                <div class="bg-white border-4 border-nusarasa-dark rounded-4xl p-8 md:p-12 mb-16 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up">
                    <h3 class="text-3xl font-black font-display uppercase tracking-tighter mb-12">Grafik Penayangan</h3>
                    <div class="h-80">
                        <canvas id="chefViewsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recipes Table -->
            <div class="lg:col-span-12">
                <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] overflow-hidden shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up">
                    <div class="px-10 py-8 border-b-4 border-nusarasa-dark bg-nusarasa-cream/50">
                        <h3 class="text-2xl font-black font-display uppercase tracking-tighter">Daftar Resep Saya</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-4 divide-nusarasa-dark">
                            <thead class="bg-nusarasa-dark">
                                <tr>
                                    <th class="px-10 py-6 text-left text-[10px] font-black text-white uppercase tracking-widest">Resep</th>
                                    <th class="px-10 py-6 text-left text-[10px] font-black text-white uppercase tracking-widest">Kategori</th>
                                    <th class="px-10 py-6 text-left text-[10px] font-black text-white uppercase tracking-widest text-center">Status</th>
                                    <th class="px-10 py-6 text-center text-[10px] font-black text-white uppercase tracking-widest">Views</th>
                                    <th class="px-10 py-6 text-right text-[10px] font-black text-white uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-nusarasa-dark/10">
                                @forelse($recipes as $recipe)
                                    <tr class="hover:bg-nusarasa-cream/30 transition">
                                        <td class="px-10 py-8">
                                            <div class="flex items-center gap-6">
                                                <div class="w-20 h-20 rounded-3xl overflow-hidden border-2 border-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-nusarasa-cream flex-shrink-0">
                                                    <img src="{{ $recipe->image_url ?? 'https://via.placeholder.com/100' }}" 
                                                         alt="{{ $recipe->title }}" 
                                                         class="w-full h-full object-cover">
                                                </div>
                                                <div class="font-black text-xl font-display text-nusarasa-dark uppercase tracking-tighter">{{ $recipe->title }}</div>
                                            </div>
                                        </td>
                                        <td class="px-10 py-8">
                                            <span class="text-xs font-black uppercase tracking-widest opacity-60">{{ $recipe->category }}</span>
                                        </td>
                                        <td class="px-10 py-8 text-center">
                                            @if($recipe->is_approved)
                                                <span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill bg-green-100 text-green-700 border-2 border-green-500 shadow-[2px_2px_0px_0px_rgba(34,197,94,1)]">Terbit</span>
                                            @else
                                                <span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill bg-yellow-100 text-yellow-700 border-2 border-yellow-500 shadow-[2px_2px_0px_0px_rgba(234,179,8,1)]">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-10 py-8 text-center font-black text-2xl font-display text-nusarasa-dark">{{ number_format($recipe->view_count) }}</td>
                                        <td class="px-10 py-8 text-right space-x-6">
                                            <a href="{{ route('chef.recipes.edit', $recipe->id) }}" class="text-[10px] font-black uppercase tracking-widest text-nusarasa-dark hover:text-nusarasa-pink transition">Ubah</a>
                                            <form action="{{ route('chef.recipes.destroy', $recipe->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus resep ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-red-600 hover:opacity-70 transition">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-10 py-20 text-center">
                                            <div class="text-4xl mb-4">🍳</div>
                                            <p class="text-xl font-black uppercase tracking-widest opacity-20 italic">Belum ada karya yang dibagikan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const ctx = document.getElementById('chefViewsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($recipes->pluck('title')->take(7)) !!},
                datasets: [{
                    label: 'Penayangan',
                    data: {!! json_encode($recipes->pluck('view_count')->take(7)) !!},
                    backgroundColor: 'rgba(229, 220, 244, 0.4)',
                    borderColor: '#1a1a1a',
                    borderWidth: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#1a1a1a',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' } }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
