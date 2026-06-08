<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-7xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Edit <span class="text-nusarasa-yellow">Templat</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest text-nusarasa-dark">Perbarui jadwal mingguan templat ini</p>
        </div>

        <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-12 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up">
            <form action="{{ route('chef.meal-plan-templates.update', $template->id) }}" method="POST" class="space-y-10">
                @csrf
                @method('PUT')

                <!-- Template Info -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">📋</div>
                        <h2 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Informasi Templat</h2>
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Nama Templat')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                        <x-text-input id="name" name="name" type="text" class="block w-full" :value="old('name', $template->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <x-input-label for="goal" :value="__('Tujuan (opsional)')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                            <x-text-input id="goal" name="goal" type="text" class="block w-full" :value="old('goal', $template->goal)" />
                        </div>
                        <div class="space-y-2">
                            <x-input-label for="description" :value="__('Deskripsi (opsional)')" class="text-xs font-black uppercase tracking-widest opacity-60 ml-4" />
                            <x-text-input id="description" name="description" type="text" class="block w-full" :value="old('description', $template->description)" />
                        </div>
                    </div>
                </div>

                <!-- Weekly Grid -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-white">📅</div>
                        <h2 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Jadwal Mingguan</h2>
                    </div>

                    @php
                        $dayNames = ['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'];
                        $mealTimeNames = ['breakfast' => 'Sarapan 🍳', 'lunch' => 'Siang 🍱', 'dinner' => 'Malam 🍲', 'camilan' => 'Camilan 🍪'];
                    @endphp

                    <div class="overflow-x-auto pb-4">
                        <table class="w-full min-w-[700px] border-4 border-nusarasa-dark rounded-2xl overflow-hidden">
                            <thead>
                                <tr class="bg-nusarasa-dark text-white">
                                    <th class="px-4 py-4 text-left font-black text-xs uppercase tracking-widest w-32">Waktu</th>
                                    @foreach($days as $day)
                                        <th class="px-4 py-4 font-black text-xs uppercase tracking-widest text-center">{{ $dayNames[$day] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mealTimes as $mealTime)
                                    <tr class="border-t-4 border-nusarasa-dark/10">
                                        <td class="px-4 py-3 bg-nusarasa-cream border-r-4 border-nusarasa-dark/10">
                                            <span class="font-black text-xs uppercase tracking-widest text-nusarasa-dark/70">{{ $mealTimeNames[$mealTime] }}</span>
                                        </td>
                                        @foreach($days as $day)
                                            <td class="px-3 py-3">
                                                <select name="items[{{ $day }}][{{ $mealTime }}]"
                                                        class="w-full px-2 py-2 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-xl font-bold text-xs focus:ring-0 focus:border-nusarasa-dark">
                                                    <option value="">— Kosong —</option>
                                                    @foreach($recipes as $recipe)
                                                        <option value="{{ $recipe->id }}" {{ (old("items.$day.$mealTime") ?? ($groupedItems[$day][$mealTime] ?? '')) == $recipe->id ? 'selected' : '' }}>
                                                            {{ Str::limit($recipe->title, 30) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex flex-col md:flex-row gap-6 pt-6">
                    <x-primary-button class="w-full md:w-auto py-5 px-14">
                        Update Templat
                    </x-primary-button>
                    <a href="{{ route('chef.meal-plan-templates.index') }}"
                       class="w-full md:w-auto py-5 px-14 border-2 border-nusarasa-dark rounded-pill font-black text-xs uppercase tracking-widest flex items-center justify-center hover:bg-nusarasa-cream transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-nusarasa-dark">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
