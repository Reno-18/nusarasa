<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20" x-data="{ 
        difficulty: 'easy',
        imagePreview: null,
        handleImageFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.imagePreview = URL.createObjectURL(file);
        },
        suggestTags() {
            let ing = document.getElementById('ingredients').value;
            let inst = document.getElementById('instructions').value;
            if (!ing || !inst) {
                alert('Silakan isi bahan-bahan dan langkah memasak terlebih dahulu!');
                return;
            }
            fetch('/api/recipes/auto-tags', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ingredients: ing, instructions: inst })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success && res.data) {
                    document.querySelectorAll('.tag-checkbox').forEach(el => el.checked = false);
                    res.data.forEach(tag => {
                        let el = document.getElementById('tag_' + tag);
                        if (el) el.checked = true;
                    });
                }
            });
        }
    }">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Bagikan <span class="text-nusarasa-pink">Resep</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Tuliskan rahasia dapurmu — akan ditinjau oleh admin sebelum ditayangkan</p>
        </div>

        <!-- Info Banner -->
        <div class="mb-8 bg-nusarasa-yellow/50 border-2 border-nusarasa-dark rounded-2xl px-6 py-4 flex items-center gap-3 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <span class="text-2xl">📋</span>
            <p class="text-sm font-bold text-nusarasa-dark">Resep yang kamu bagikan akan melalui proses tinjauan admin sebelum ditampilkan ke publik. Harap isi semua informasi dengan lengkap dan benar.</p>
        </div>

        <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-16 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up">
            <form action="{{ route('user.recipes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                @csrf

                <!-- Section 1: Dasar -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                            📝
                        </div>
                        <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Informasi Dasar</h2>
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="title" :value="__('Judul Resep')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                        <x-text-input id="title" name="title" type="text" class="block w-full" :value="old('title')" required placeholder="Contoh: Rendang Daging Autentik" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <x-input-label for="category" :value="__('Kategori')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                            <x-text-input id="category" name="category" type="text" class="block w-full" :value="old('category')" placeholder="Contoh: Makan Siang" />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>
                        <div class="space-y-2">
                            <x-input-label for="origin" :value="__('Asal Daerah')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                            <x-text-input id="origin" name="origin" type="text" class="block w-full" :value="old('origin')" placeholder="Contoh: Sumatra Barat" />
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
                        <textarea id="ingredients" name="ingredients" rows="6" class="block w-full bg-nusarasa-cream border-2 border-nusarasa-dark focus:ring-0 focus:border-nusarasa-dark rounded-[2rem] px-8 py-6 font-bold placeholder-gray-400" required placeholder="Sebutkan satu per satu di setiap baris...">{{ old('ingredients') }}</textarea>
                        <x-input-error :messages="$errors->get('ingredients')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="instructions" :value="__('Langkah Memasak')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                        <textarea id="instructions" name="instructions" rows="8" class="block w-full bg-nusarasa-cream border-2 border-nusarasa-dark focus:ring-0 focus:border-nusarasa-dark rounded-[2rem] px-8 py-6 font-bold placeholder-gray-400" required placeholder="Jelaskan langkah-langkahnya secara detail...">{{ old('instructions') }}</textarea>
                        <x-input-error :messages="$errors->get('instructions')" class="mt-2" />
                    </div>
                </div>

                <!-- Section 3: Klasifikasi -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-nusarasa-pink border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                            🏷️
                        </div>
                        <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Kesulitan & Tag</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <x-input-label for="difficulty" :value="__('Tingkat Kesulitan')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                            <select id="difficulty" name="difficulty" x-model="difficulty"
                                    class="w-full px-6 py-4 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl font-bold focus:ring-0 focus:border-nusarasa-dark">
                                <option value="easy">Mudah (Easy)</option>
                                <option value="medium">Sedang (Medium)</option>
                                <option value="hard">Sulit (Hard)</option>
                            </select>
                            <div class="p-4 border-2 border-nusarasa-dark rounded-2xl flex items-center justify-between shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-colors"
                                 :class="difficulty === 'easy' ? 'bg-green-100' : (difficulty === 'medium' ? 'bg-nusarasa-yellow' : 'bg-nusarasa-pink')">
                                <span class="font-black text-xs uppercase tracking-widest text-nusarasa-dark">Tingkat Kesulitan:</span>
                                <span class="px-3 py-1 bg-white border-2 border-nusarasa-dark rounded-lg font-black text-xs uppercase text-nusarasa-dark" x-text="difficulty"></span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <x-input-label :value="__('Tags / Label')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                                <button type="button" @click="suggestTags()" class="px-3 py-1 bg-nusarasa-purple text-white border-2 border-nusarasa-dark rounded-xl font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-0.5 hover:shadow-none transition-all">
                                    💡 Rekomendasikan Tag
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-3 p-4 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl">
                                @foreach($allTags as $tag)
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="tags[]" value="{{ $tag }}" id="tag_{{ $tag }}"
                                               class="tag-checkbox w-5 h-5 rounded border-2 border-nusarasa-dark text-nusarasa-pink focus:ring-0">
                                        <span class="font-bold text-xs uppercase tracking-wider text-nusarasa-dark">{{ $tag }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Visual -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-orange-200 border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                            📸
                        </div>
                        <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Visual Resep</h2>
                    </div>

                    <div class="relative border-4 border-dashed border-nusarasa-dark/20 rounded-[2rem] overflow-hidden transition-all hover:border-nusarasa-dark/50"
                         :class="imagePreview ? 'border-nusarasa-dark' : ''">
                        <input type="file" name="image" id="image"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               accept="image/*"
                               @change="handleImageFile($event)">

                        <template x-if="imagePreview">
                            <div class="relative">
                                <img :src="imagePreview" alt="Preview" class="w-full h-72 object-cover">
                                <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <span class="text-white font-black text-xl uppercase tracking-tight">🔄 Klik untuk ganti gambar</span>
                                </div>
                                <div class="absolute top-4 right-4 bg-green-500 text-white text-xs font-black px-3 py-1 rounded-full border-2 border-white shadow">
                                    ✓ Gambar dipilih
                                </div>
                            </div>
                        </template>

                        <template x-if="!imagePreview">
                            <div class="p-12 text-center space-y-4">
                                <div class="w-20 h-20 bg-nusarasa-cream rounded-full flex items-center justify-center mx-auto text-4xl">
                                    🖼️
                                </div>
                                <p class="text-xl font-black uppercase tracking-tighter text-nusarasa-dark opacity-60">Klik atau seret gambar ke sini</p>
                                <p class="text-xs font-bold opacity-40 uppercase tracking-widest">Maksimal 2MB (JPG, PNG)</p>
                            </div>
                        </template>
                    </div>
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div class="flex flex-col md:flex-row gap-6 pt-8">
                    <x-primary-button class="w-full md:w-auto py-6 px-16 text-lg">
                        🚀 Kirim Resep
                    </x-primary-button>
                    <a href="{{ route('home') }}" class="w-full md:w-auto py-6 px-16 border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest flex items-center justify-center hover:bg-nusarasa-cream transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
