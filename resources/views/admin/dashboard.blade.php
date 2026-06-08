<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-12" data-aos="fade-down">
            <div>
                <h1 class="text-5xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Dashboard<br>Kepala Chef</h1>
            </div>
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <div class="text-left md:text-right">
                    <p class="text-xs font-black uppercase opacity-40 tracking-widest">Status Sistem</p>
                    <p class="font-bold text-green-500 uppercase tracking-widest text-sm">Aktif & Berjalan</p>
                </div>
                <a href="{{ route('chef.recipes.create') }}" 
                   class="px-8 py-4 bg-nusarasa-dark text-white rounded-full font-black text-xs uppercase tracking-widest hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] whitespace-nowrap">
                    + Tambah Resep
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div class="bg-white border-2 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up" data-aos-delay="0">
                <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-4">Total Pengguna</div>
                <div class="text-5xl font-black font-display text-nusarasa-dark tracking-tighter">{{ $totalUsers }}</div>
            </div>
            <div class="bg-nusarasa-purple border-2 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up" data-aos-delay="100">
                <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-4 text-nusarasa-dark">Total Resep</div>
                <div class="text-5xl font-black font-display text-nusarasa-dark tracking-tighter">{{ $totalRecipes }}</div>
            </div>
            <div class="bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up" data-aos-delay="200">
                <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-4 text-nusarasa-dark">Perlu Approval</div>
                <div class="text-5xl font-black font-display text-nusarasa-dark tracking-tighter">{{ $pendingRecipes }}</div>
            </div>
            <div class="bg-nusarasa-pink border-2 border-nusarasa-dark rounded-4xl p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up" data-aos-delay="300">
                <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-4 text-nusarasa-dark">Total Chef</div>
                <div class="text-5xl font-black font-display text-nusarasa-dark tracking-tighter">{{ $totalChefs }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Charts Section -->
            <div class="lg:col-span-8">
                <div class="bg-white border-2 border-nusarasa-dark rounded-4xl p-8" data-aos="fade-up">
                    <h3 class="text-2xl font-black font-display uppercase tracking-tight mb-8">Statistik Platform</h3>
                    <div class="h-80">
                        <canvas id="adminStatsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="lg:col-span-4 space-y-8">
                <a href="{{ route('admin.recipes') }}" 
                   class="group block bg-white border-2 border-nusarasa-dark rounded-4xl p-8 hover:bg-nusarasa-dark hover:text-white transition-all duration-300" data-aos="fade-left">
                    <div class="w-12 h-12 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-full flex items-center justify-center mb-6 group-hover:bg-white group-hover:text-nusarasa-dark transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black font-display uppercase tracking-tight mb-2">Persetujuan Resep</h3>
                    <p class="font-bold text-sm opacity-60">Review dan setujui karya chef lainnya.</p>
                </a>

                <a href="{{ route('admin.users') }}" 
                   class="group block bg-white border-2 border-nusarasa-dark rounded-4xl p-8 hover:bg-nusarasa-dark hover:text-white transition-all duration-300" data-aos="fade-left" data-aos-delay="100">
                    <div class="w-12 h-12 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full flex items-center justify-center mb-6 group-hover:bg-white group-hover:text-nusarasa-dark transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black font-display uppercase tracking-tight mb-2">Manajemen User</h3>
                    <p class="font-bold text-sm opacity-60">Kelola hak akses dan status anggota.</p>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const ctx = document.getElementById('adminStatsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pengguna', 'Resep', 'Pending', 'Chef'],
                datasets: [{
                    label: 'Statistik',
                    data: [{{ $totalUsers }}, {{ $totalRecipes }}, {{ $pendingRecipes }}, {{ $totalChefs }}],
                    backgroundColor: ['#e5dcf4', '#fef2cb', '#fcdada', '#d1fae5'],
                    borderColor: '#1a1a1a',
                    borderWidth: 3,
                    borderRadius: 10
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
                        grid: { display: false },
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
