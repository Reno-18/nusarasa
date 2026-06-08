<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Pahlawan <span class="text-[#A16207]">Dapur</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Kenali para chef hebat di balik resep-resep lezat</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($chefs as $index => $chef)
                <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] p-8 flex flex-col items-center text-center shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-2 hover:shadow-[12px_16px_0px_0px_rgba(0,0,0,1)] transition-all relative overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    
                    @if($chef->role === 'admin')
                        <div class="absolute top-6 right-6">
                            <span class="px-4 py-2 bg-nusarasa-yellow text-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-[10px] uppercase tracking-widest shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">Kepala Chef</span>
                        </div>
                    @endif

                    <!-- Chef Avatar -->
                    @if($chef->avatar_url)
                        <img src="{{ $chef->avatar_url }}" alt="{{ $chef->name }}" class="w-32 h-32 border-4 border-nusarasa-dark rounded-full object-cover shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mb-6 mt-4">
                    @else
                        <div class="w-32 h-32 bg-{{ $chef->role === 'admin' ? 'nusarasa-pink' : 'nusarasa-purple' }} border-4 border-nusarasa-dark rounded-full flex items-center justify-center font-black text-5xl font-display text-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mb-6 mt-4">
                            {{ substr($chef->name, 0, 1) }}
                        </div>
                    @endif

                    <!-- Chef Name -->
                    <h3 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark mb-2">{{ $chef->name }}</h3>
                    <p class="text-xs font-bold opacity-50 uppercase tracking-widest mb-6">Bergabung {{ $chef->created_at->format('M Y') }}</p>

                    <!-- Stats -->
                    <div class="w-full grid grid-cols-2 gap-4 pt-6 border-t-4 border-nusarasa-dark/10 mt-auto">
                        <div>
                            <div class="text-3xl font-black text-nusarasa-dark">{{ $chef->total_recipes }}</div>
                            <div class="text-[10px] font-black uppercase tracking-widest opacity-40">Resep Terbit</div>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-nusarasa-dark flex justify-center items-center gap-1">
                                {{ number_format($chef->average_rating, 1) }} <span class="text-nusarasa-yellow text-xl">★</span>
                            </div>
                            <div class="text-[10px] font-black uppercase tracking-widest opacity-40">{{ $chef->total_votes }} Ulasan</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
