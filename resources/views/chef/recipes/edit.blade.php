<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Sempurnakan <span class="text-nusarasa-purple">Rasa</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Perbarui resep spesialmu</p>
        </div>

        <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-16 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up">
            <form action="{{ route('chef.recipes.update', $recipe->id) }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                @csrf
                @method('PUT')

                <!-- Section 1: Dasar -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            📝
                        </div>
                        <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Informasi Dasar</h2>
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="title" :value="__('Judul Resep')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                        <x-text-input id="title" name="title" type="text" class="block w-full" :value="old('title', $recipe->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <x-input-label for="category" :value="__('Kategori')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                            <x-text-input id="category" name="category" type="text" class="block w-full" :value="old('category', $recipe->category)" />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>
                        <div class="space-y-2">
                            <x-input-label for="origin" :value="__('Asal Daerah')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                            <x-text-input id="origin" name="origin" type="text" class="block w-full" :value="old('origin', $recipe->origin)" />
                            <x-input-error :messages="$errors->get('origin')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Konten -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-white">
                            🍳
                        </div>
                        <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Bahan & Cara</h2>
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="ingredients" :value="__('Bahan-bahan')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                        <textarea id="ingredients" name="ingredients" rows="6" class="block w-full bg-nusarasa-cream border-2 border-nusarasa-dark focus:ring-0 focus:border-nusarasa-dark rounded-[2rem] px-8 py-6 font-bold placeholder-gray-400" required>{{ old('ingredients', $recipe->ingredients) }}</textarea>
                        <x-input-error :messages="$errors->get('ingredients')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="instructions" :value="__('Langkah Memasak')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                        <textarea id="instructions" name="instructions" rows="8" class="block w-full bg-nusarasa-cream border-2 border-nusarasa-dark focus:ring-0 focus:border-nusarasa-dark rounded-[2rem] px-8 py-6 font-bold placeholder-gray-400" required>{{ old('instructions', $recipe->instructions) }}</textarea>
                        <x-input-error :messages="$errors->get('instructions')" class="mt-2" />
                    </div>
                </div>

                <!-- Section 3: Media -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-nusarasa-pink border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            📸
                        </div>
                        <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Visual Resep</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        @if($recipe->image_url)
                            <div class="space-y-4">
                                <p class="text-xs font-black uppercase tracking-widest opacity-40 ml-4">Gambar Saat Ini</p>
                                <div class="bg-nusarasa-cream border-4 border-nusarasa-dark rounded-[2rem] overflow-hidden p-2">
                                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full aspect-video object-cover rounded-3xl">
                                </div>
                            </div>
                        @endif

                        <div class="space-y-4">
                            <p class="text-xs font-black uppercase tracking-widest opacity-40 ml-4">Ganti Gambar</p>
                            <div class="relative group border-4 border-dashed border-nusarasa-dark/10 rounded-[2rem] p-10 text-center hover:border-nusarasa-dark/40 transition-all h-full flex flex-col justify-center">
                                <input type="file" name="image" id="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                                <div class="space-y-4">
                                    <div class="w-16 h-16 bg-nusarasa-cream rounded-full flex items-center justify-center mx-auto text-3xl">
                                        🖼️
                                    </div>
                                    <p class="text-sm font-black uppercase tracking-tighter text-nusarasa-dark opacity-60">Klik untuk unggah baru</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div class="flex flex-col md:flex-row gap-6 pt-8">
                    <x-primary-button class="w-full md:w-auto py-6 px-16 text-lg">
                        Update Resep
                    </x-primary-button>
                    <a href="{{ route('chef.dashboard') }}" class="w-full md:w-auto py-6 px-16 border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest flex items-center justify-center hover:bg-nusarasa-cream transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
