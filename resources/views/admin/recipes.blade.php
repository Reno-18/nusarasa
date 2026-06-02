<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-12" data-aos="fade-down">
            <div>
                <h1 class="text-5xl md:text-7xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark">Persetujuan<br><span class="text-[#6D28D9]">Karya Chef</span></h1>
                <p class="font-bold opacity-60 text-sm uppercase tracking-widest mt-4 ml-2">Kurasi resep-resep terbaik untuk platform</p>
            </div>
            
            <div class="w-full md:w-80 relative group">
                <input type="text" id="recipeSearch" placeholder="Cari resep..." 
                       class="w-full pl-6 pr-14 py-4 bg-white border-4 border-nusarasa-dark rounded-pill font-bold text-sm focus:ring-0 focus:border-nusarasa-purple transition-all shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] group-hover:translate-x-[2px] group-hover:translate-y-[2px] group-hover:shadow-none transition-all">
                <div class="absolute right-10 top-1/2 -translate-y-1/2 text-xl pointer-events-none">🔍</div>
            </div>
        </div>

        <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] overflow-hidden shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y-4 divide-nusarasa-dark">
                    <thead class="bg-nusarasa-dark text-white">
                        <tr>
                            <th class="px-8 py-6 text-left text-[10px] font-black uppercase tracking-widest">Resep</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black uppercase tracking-widest">Kontributor</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-6 text-right text-[10px] font-black uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="recipeTableBody" class="divide-y-2 divide-nusarasa-dark/10">
                        @foreach($recipes as $recipe)
                            <tr class="recipe-row hover:bg-nusarasa-cream/30 transition" data-id="{{ $recipe->id }}">
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-6">
                                        <div class="w-20 h-20 rounded-3xl overflow-hidden border-2 border-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-nusarasa-cream flex-shrink-0">
                                            <img src="{{ $recipe->image_url ?? 'https://via.placeholder.com/100' }}" 
                                                 alt="{{ $recipe->title }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="font-black text-xl font-display text-nusarasa-dark uppercase tracking-tighter">{{ $recipe->title }}</div>
                                            <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mt-1">{{ $recipe->category }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full flex items-center justify-center font-black text-[10px]">
                                            {{ substr($recipe->user->name, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-nusarasa-dark">{{ $recipe->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center status-cell">
                                    @if($recipe->is_approved)
                                        <span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill bg-green-100 text-green-700 border-2 border-green-500 shadow-[2px_2px_0px_0px_rgba(34,197,94,1)]">Terbit</span>
                                    @else
                                        <span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill bg-yellow-100 text-yellow-700 border-2 border-yellow-500 shadow-[2px_2px_0px_0px_rgba(234,179,8,1)]">Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-8 py-8 text-right space-x-6">
                                    <a href="{{ route('recipes.show', $recipe->id) }}" class="text-[10px] font-black uppercase tracking-widest text-nusarasa-dark hover:text-nusarasa-purple transition">Pratinjau</a>
                                    
                                    @if(!$recipe->is_approved)
                                        <button class="approve-btn text-[10px] font-black uppercase tracking-widest text-green-600 hover:opacity-70 transition" data-id="{{ $recipe->id }}">Approve</button>
                                    @endif
                                    
                                    <button class="delete-btn text-[10px] font-black uppercase tracking-widest text-red-600 hover:opacity-70 transition" data-id="{{ $recipe->id }}">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Search Functionality
            $('#recipeSearch').on('keyup', function() {
                const search = $(this).val();
                $.ajax({
                    url: "{{ route('admin.recipes') }}",
                    data: { search: search },
                    success: function(data) {
                        let html = '';
                        if (data.length === 0) {
                            html = '<tr><td colspan="4" class="px-8 py-20 text-center text-xl font-black uppercase opacity-20 italic">Tidak ada resep ditemukan</td></tr>';
                        } else {
                            data.forEach(recipe => {
                                const statusHtml = recipe.is_approved 
                                    ? '<span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill bg-green-100 text-green-700 border-2 border-green-500 shadow-[2px_2px_0px_0px_rgba(34,197,94,1)]">Terbit</span>'
                                    : '<span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill bg-yellow-100 text-yellow-700 border-2 border-yellow-500 shadow-[2px_2px_0px_0px_rgba(234,179,8,1)]">Menunggu</span>';
                                
                                const approveBtn = !recipe.is_approved 
                                    ? `<button class="approve-btn text-[10px] font-black uppercase tracking-widest text-green-600 hover:opacity-70 transition" data-id="${recipe.id}">Approve</button>`
                                    : '';

                                html += `
                                    <tr class="recipe-row hover:bg-nusarasa-cream/30 transition" data-id="${recipe.id}">
                                        <td class="px-8 py-8">
                                            <div class="flex items-center gap-6">
                                                <div class="w-20 h-20 rounded-3xl overflow-hidden border-2 border-nusarasa-dark shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-nusarasa-cream flex-shrink-0">
                                                    <img src="${recipe.image_url || 'https://via.placeholder.com/100'}" class="w-full h-full object-cover">
                                                </div>
                                                <div>
                                                    <div class="font-black text-xl font-display text-nusarasa-dark uppercase tracking-tighter">${recipe.title}</div>
                                                    <div class="text-[10px] font-black uppercase tracking-widest opacity-40 mt-1">${recipe.category || 'N/A'}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-8">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-nusarasa-yellow border-2 border-nusarasa-dark rounded-full flex items-center justify-center font-black text-[10px]">
                                                    ${recipe.user.name.charAt(0)}
                                                </div>
                                                <span class="font-bold text-nusarasa-dark">${recipe.user.name}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-8 text-center status-cell">${statusHtml}</td>
                                        <td class="px-8 py-8 text-right space-x-6">
                                            <a href="/recipes/${recipe.id}" class="text-[10px] font-black uppercase tracking-widest text-nusarasa-dark hover:text-nusarasa-purple transition">Pratinjau</a>
                                            ${approveBtn}
                                            <button class="delete-btn text-[10px] font-black uppercase tracking-widest text-red-600 hover:opacity-70 transition" data-id="${recipe.id}">Hapus</button>
                                        </td>
                                    </tr>
                                `;
                            });
                        }
                        $('#recipeTableBody').html(html);
                    }
                });
            });

            // Approve Logic
            $(document).on('click', '.approve-btn', function() {
                const id = $(this).data('id');
                const btn = $(this);
                const row = btn.closest('.recipe-row');

                $.ajax({
                    url: `/admin/recipes/${id}/approve`,
                    type: 'PATCH',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        row.find('.status-cell').html('<span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill bg-green-100 text-green-700 border-2 border-green-500 shadow-[2px_2px_0px_0px_rgba(34,197,94,1)]">Terbit</span>');
                        btn.fadeOut();
                        showToast(response.message, 'success');
                    }
                });
            });

            // Delete Logic
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const row = $(this).closest('.recipe-row');

                if (confirm('Hapus resep ini secara permanen?')) {
                    $.ajax({
                        url: `/admin/recipes/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            row.fadeOut(400, function() { $(this).remove(); });
                            showToast(response.message, 'success');
                        }
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
