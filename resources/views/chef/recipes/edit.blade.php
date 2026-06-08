<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20" x-data="{ 
        difficulty: '{{ old('difficulty', $recipe->difficulty ?? 'easy') }}',
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
                Sempurnakan <span class="text-nusarasa-purple">Resep</span>
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
                        <div class="w-12 h-12 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
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

                <!-- Section 3: Klasifikasi (Tags & Difficulty) -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-nusarasa-pink border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                            🏷️
                        </div>
                        <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Kesulitan & Tag</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Difficulty -->
                        <div class="space-y-4">
                            <x-input-label for="difficulty" :value="__('Tingkat Kesulitan')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                            <select id="difficulty" name="difficulty" x-model="difficulty" 
                                    class="w-full px-6 py-4 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-2xl font-bold focus:ring-0 focus:border-nusarasa-dark">
                                <option value="easy">Mudah (Easy)</option>
                                <option value="medium">Sedang (Medium)</option>
                                <option value="hard">Sulit (Hard)</option>
                            </select>
                            
                            <!-- Live Preview Box -->
                            <div class="p-4 border-2 border-nusarasa-dark rounded-2xl flex items-center justify-between shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-colors"
                                 :class="difficulty === 'easy' ? 'bg-green-100' : (difficulty === 'medium' ? 'bg-nusarasa-yellow' : 'bg-nusarasa-pink')">
                                <span class="font-black text-xs uppercase tracking-widest text-nusarasa-dark">Tingkat Kesulitan:</span>
                                <span class="px-3 py-1 bg-white border-2 border-nusarasa-dark rounded-lg font-black text-xs uppercase text-nusarasa-dark" x-text="difficulty"></span>
                            </div>
                        </div>

                        <!-- Tags -->
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
                                               {{ (is_array($recipe->tags) && in_array($tag, $recipe->tags)) ? 'checked' : '' }}
                                               class="tag-checkbox w-5 h-5 rounded border-2 border-nusarasa-dark text-nusarasa-pink focus:ring-0">
                                        <span class="font-bold text-xs uppercase tracking-wider text-nusarasa-dark">{{ $tag }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Nutrisi -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-green-200 border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                            🥗
                        </div>
                        <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Kandungan Nutrisi (Per Porsi)</h2>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="space-y-2">
                            <x-input-label for="calories" :value="__('Kalori (kkal)')" class="text-xs font-black uppercase tracking-widest opacity-60 text-center" />
                            <x-text-input id="calories" name="nutrition[calories]" type="number" step="0.1" class="block w-full text-center" :value="old('nutrition.calories', $recipe->nutrition['calories'] ?? '')" placeholder="0" />
                        </div>
                        <div class="space-y-2">
                            <x-input-label for="protein" :value="__('Protein (g)')" class="text-xs font-black uppercase tracking-widest opacity-60 text-center" />
                            <x-text-input id="protein" name="nutrition[protein]" type="number" step="0.1" class="block w-full text-center" :value="old('nutrition.protein', $recipe->nutrition['protein'] ?? '')" placeholder="0" />
                        </div>
                        <div class="space-y-2">
                            <x-input-label for="carbs" :value="__('Karbo (g)')" class="text-xs font-black uppercase tracking-widest opacity-60 text-center" />
                            <x-text-input id="carbs" name="nutrition[carbs]" type="number" step="0.1" class="block w-full text-center" :value="old('nutrition.carbs', $recipe->nutrition['carbs'] ?? '')" placeholder="0" />
                        </div>
                        <div class="space-y-2">
                            <x-input-label for="fat" :value="__('Lemak (g)')" class="text-xs font-black uppercase tracking-widest opacity-60 text-center" />
                            <x-text-input id="fat" name="nutrition[fat]" type="number" step="0.1" class="block w-full text-center" :value="old('nutrition.fat', $recipe->nutrition['fat'] ?? '')" placeholder="0" />
                        </div>
                        <div class="space-y-2 col-span-2 md:col-span-1">
                            <x-input-label for="fiber" :value="__('Serat (g)')" class="text-xs font-black uppercase tracking-widest opacity-60 text-center" />
                            <x-text-input id="fiber" name="nutrition[fiber]" type="number" step="0.1" class="block w-full text-center" :value="old('nutrition.fiber', $recipe->nutrition['fiber'] ?? '')" placeholder="0" />
                        </div>
                    </div>
                </div>

                <!-- Section 5: Media -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-orange-200 border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                            📸
                        </div>
                        <h2 class="text-3xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Visual Resep</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <!-- Current image -->
                        @if($recipe->image_url)
                            <div class="space-y-4">
                                <p class="text-xs font-black uppercase tracking-widest opacity-40 ml-4">Gambar Saat Ini</p>
                                <div class="bg-nusarasa-cream border-4 border-nusarasa-dark rounded-[2rem] overflow-hidden p-2">
                                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full aspect-video object-cover rounded-3xl">
                                </div>
                            </div>
                        @endif

                        <!-- New image upload with preview -->
                        <div class="space-y-4">
                            <p class="text-xs font-black uppercase tracking-widest opacity-40 ml-4">Ganti Gambar</p>
                            <div class="relative border-4 border-dashed border-nusarasa-dark/20 rounded-[2rem] overflow-hidden transition-all hover:border-nusarasa-dark/50"
                                 :class="imagePreview ? 'border-nusarasa-dark' : ''">

                                <input type="file" name="image" id="image"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       accept="image/*"
                                       @change="handleImageFile($event)">

                                <!-- Preview of new image -->
                                <template x-if="imagePreview">
                                    <div class="relative">
                                        <img :src="imagePreview" alt="Preview" class="w-full h-52 object-cover">
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                            <span class="text-white font-black uppercase tracking-tight">🔄 Klik ganti lagi</span>
                                        </div>
                                        <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-black px-3 py-1 rounded-full border-2 border-white shadow">
                                            ✓ Gambar baru dipilih
                                        </div>
                                    </div>
                                </template>

                                <!-- Empty state -->
                                <template x-if="!imagePreview">
                                    <div class="p-10 text-center space-y-4">
                                        <div class="w-16 h-16 bg-nusarasa-cream rounded-full flex items-center justify-center mx-auto text-3xl">
                                            🖼️
                                        </div>
                                        <p class="text-sm font-black uppercase tracking-tighter text-nusarasa-dark opacity-60">Klik untuk unggah baru</p>
                                    </div>
                                </template>
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
                    <a href="{{ route('chef.dashboard') }}" class="w-full md:w-auto py-6 px-16 border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest flex items-center justify-center hover:bg-nusarasa-cream transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
