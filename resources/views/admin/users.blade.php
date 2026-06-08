<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-12" data-aos="fade-down">
            <div>
                <h1 class="text-5xl md:text-7xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark">Manajemen<br><span class="text-[#A16207]">Anggota</span></h1>
                <p class="font-bold opacity-60 text-sm uppercase tracking-widest mt-4 ml-2">Kelola peran dan hak akses komunitas NusaRasa</p>
            </div>
            
            <div class="w-full md:w-80 relative group">
                <input type="text" id="userSearch" placeholder="Cari anggota..." 
                       class="w-full pl-6 pr-14 py-4 bg-white border-4 border-nusarasa-dark rounded-pill font-bold text-sm focus:ring-0 focus:border-nusarasa-yellow transition-all shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] group-hover:translate-x-[2px] group-hover:translate-y-[2px] group-hover:shadow-none transition-all">
                <div class="absolute right-10 top-1/2 -translate-y-1/2 text-xl pointer-events-none">🔍</div>
            </div>
        </div>

        <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] overflow-hidden shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y-4 divide-nusarasa-dark">
                    <thead class="bg-nusarasa-dark text-white">
                        <tr>
                            <th class="px-8 py-6 text-left text-[10px] font-black uppercase tracking-widest">Nama Lengkap</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black uppercase tracking-widest">Kontak</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black uppercase tracking-widest text-center">Peran Saat Ini</th>
                            <th class="px-8 py-6 text-right text-[10px] font-black uppercase tracking-widest">Ubah Akses</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody" class="divide-y-2 divide-nusarasa-dark/10">
                        @foreach($users as $user)
                            <tr class="user-row hover:bg-nusarasa-cream/30 transition" data-id="{{ $user->id }}">
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-4">
                                        @if($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 object-cover border-2 border-nusarasa-dark rounded-full shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                                        @else
                                            <div class="w-12 h-12 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-full flex items-center justify-center font-black text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-white">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="font-black text-xl font-display text-nusarasa-dark uppercase tracking-tighter">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="font-bold text-nusarasa-dark opacity-60 italic">{{ $user->email }}</div>
                                    <div class="text-[10px] font-black uppercase tracking-widest opacity-30 mt-1">Bergabung: {{ $user->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-8 py-8 text-center role-cell">
                                    @php
                                        $roleLabel = $user->role == 'admin' ? 'Kepala Chef' : ($user->role == 'chef' ? 'Chef' : 'Anggota');
                                        $roleClass = $user->role == 'admin' ? 'bg-orange-100 text-orange-700 border-orange-500 shadow-[2px_2px_0px_0px_rgba(249,115,22,1)]' : 
                                                    ($user->role == 'chef' ? 'bg-blue-100 text-blue-700 border-blue-500 shadow-[2px_2px_0px_0px_rgba(59,130,246,1)]' : 
                                                    'bg-gray-100 text-gray-700 border-gray-500 shadow-[2px_2px_0px_0px_rgba(107,114,128,1)]');
                                    @endphp
                                    <span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill border-2 {{ $roleClass }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td class="px-8 py-8 text-right">
                                    @if($user->id != auth()->id())
                                        <div class="inline-flex items-center gap-4">
                                            <div x-data="{ 
                                                open: false, 
                                                role: '{{ $user->role }}',
                                                get roleLabel() {
                                                    if (this.role === 'admin') return 'Kepala Chef';
                                                    if (this.role === 'chef') return 'Chef';
                                                    return 'Anggota';
                                                },
                                                updateRole(newRole) {
                                                    if(this.role === newRole) return;
                                                    this.role = newRole;
                                                    updateUserRole({{ $user->id }}, newRole, this.$el);
                                                }
                                            }" class="relative inline-block text-left" :class="open ? 'z-50' : 'z-10'">
                                                <button @click="open = !open" @click.away="open = false" class="px-6 py-3 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-pill font-black text-[10px] uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-between min-w-[150px] hover:translate-y-[2px] hover:translate-x-[2px] hover:shadow-none transition-all">
                                                    <span x-text="roleLabel"></span>
                                                    <span>▾</span>
                                                </button>
                                                <div x-show="open" x-transition style="display:none;" class="absolute right-0 mt-2 w-full min-w-[150px] bg-white border-2 border-nusarasa-dark rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden z-[60]">
                                                    <button @click="updateRole('user'); open = false" class="w-full text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-nusarasa-cream transition-colors" :class="role === 'user' ? 'bg-nusarasa-yellow' : ''">Anggota</button>
                                                    <button @click="updateRole('chef'); open = false" class="w-full text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest border-t border-nusarasa-dark/10 hover:bg-nusarasa-cream transition-colors" :class="role === 'chef' ? 'bg-nusarasa-yellow' : ''">Chef</button>
                                                    <button @click="updateRole('admin'); open = false" class="w-full text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest border-t border-nusarasa-dark/10 hover:bg-nusarasa-cream transition-colors" :class="role === 'admin' ? 'bg-nusarasa-yellow' : ''">Kepala Chef</button>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-[10px] font-black uppercase tracking-widest opacity-30 italic mr-6">Akun Anda</span>
                                    @endif
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
            $('#userSearch').on('keyup', function() {
                const search = $(this).val();
                $.ajax({
                    url: "{{ route('admin.users') }}",
                    data: { search: search },
                    success: function(data) {
                        let html = '';
                        if (data.length === 0) {
                            html = '<tr><td colspan="4" class="px-8 py-20 text-center text-xl font-black uppercase opacity-20 italic">Tidak ada pengguna ditemukan</td></tr>';
                        } else {
                            data.forEach(user => {
                                const isSelf = user.id == {{ auth()->id() }};
                                const roleLabel = user.role == 'admin' ? 'Kepala Chef' : (user.role == 'chef' ? 'Chef' : 'Anggota');
                                const roleClass = user.role == 'admin' ? 'bg-orange-100 text-orange-700 border-orange-500 shadow-[2px_2px_0px_0px_rgba(249,115,22,1)]' : 
                                                (user.role == 'chef' ? 'bg-blue-100 text-blue-700 border-blue-500 shadow-[2px_2px_0px_0px_rgba(59,130,246,1)]' : 
                                                'bg-gray-100 text-gray-700 border-gray-500 shadow-[2px_2px_0px_0px_rgba(107,114,128,1)]');
                                
                                const avatarHtml = user.avatar_url 
                                    ? `<img src="${user.avatar_url}" alt="${user.name}" class="w-12 h-12 object-cover border-2 border-nusarasa-dark rounded-full shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">`
                                    : `<div class="w-12 h-12 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-full flex items-center justify-center font-black text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-white">${user.name.charAt(0)}</div>`;
                                
                                const actionHtml = isSelf 
                                    ? '<span class="text-[10px] font-black uppercase tracking-widest opacity-30 italic mr-6">Akun Anda</span>'
                                    : `<div x-data="{ 
                                            open: false, 
                                            role: '${user.role}',
                                            get roleLabel() {
                                                if (this.role === 'admin') return 'Kepala Chef';
                                                if (this.role === 'chef') return 'Chef';
                                                return 'Anggota';
                                            },
                                            updateRole(newRole) {
                                                if(this.role === newRole) return;
                                                this.role = newRole;
                                                updateUserRole(${user.id}, newRole, this.$el);
                                            }
                                        }" class="relative inline-block text-left" :class="open ? 'z-50' : 'z-10'">
                                            <button @click="open = !open" @click.away="open = false" class="px-6 py-3 bg-nusarasa-cream border-2 border-nusarasa-dark rounded-pill font-black text-[10px] uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-between min-w-[150px] hover:translate-y-[2px] hover:translate-x-[2px] hover:shadow-none transition-all">
                                                <span x-text="roleLabel"></span>
                                                <span>▾</span>
                                            </button>
                                            <div x-show="open" x-transition style="display:none;" class="absolute right-0 mt-2 w-full min-w-[150px] bg-white border-2 border-nusarasa-dark rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden z-[60]">
                                                <button @click="updateRole('user'); open = false" class="w-full text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-nusarasa-cream transition-colors" :class="role === 'user' ? 'bg-nusarasa-yellow' : ''">Anggota</button>
                                                <button @click="updateRole('chef'); open = false" class="w-full text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest border-t border-nusarasa-dark/10 hover:bg-nusarasa-cream transition-colors" :class="role === 'chef' ? 'bg-nusarasa-yellow' : ''">Chef</button>
                                                <button @click="updateRole('admin'); open = false" class="w-full text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest border-t border-nusarasa-dark/10 hover:bg-nusarasa-cream transition-colors" :class="role === 'admin' ? 'bg-nusarasa-yellow' : ''">Kepala Chef</button>
                                            </div>
                                        </div>`;

                                html += `
                                    <tr class="user-row hover:bg-nusarasa-cream/30 transition" data-id="${user.id}">
                                        <td class="px-8 py-8">
                                            <div class="flex items-center gap-4">
                                                ${avatarHtml}
                                                <div class="font-black text-xl font-display text-nusarasa-dark uppercase tracking-tighter">${user.name}</div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-8">
                                            <div class="font-bold text-nusarasa-dark opacity-60 italic">${user.email}</div>
                                        </td>
                                        <td class="px-8 py-8 text-center role-cell">
                                            <span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill border-2 ${roleClass}">
                                                ${roleLabel}
                                            </span>
                                        </td>
                                        <td class="px-8 py-8 text-right">${actionHtml}</td>
                                    </tr>
                                `;
                            });
                        }
                        $('#userTableBody').html(html);
                    }
                });
            });

            // Role Update Logic Function
            window.updateUserRole = function(id, role, el) {
                const row = $(el).closest('.user-row');

                $.ajax({
                    url: `/admin/users/${id}/role`,
                    type: 'PATCH',
                    data: { 
                        role: role,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        const roleLabel = role == 'admin' ? 'Kepala Chef' : (role == 'chef' ? 'Chef' : 'Anggota');
                        const roleClass = role == 'admin' ? 'bg-orange-100 text-orange-700 border-orange-500 shadow-[2px_2px_0px_0px_rgba(249,115,22,1)]' : 
                                        (role == 'chef' ? 'bg-blue-100 text-blue-700 border-blue-500 shadow-[2px_2px_0px_0px_rgba(59,130,246,1)]' : 
                                        'bg-gray-100 text-gray-700 border-gray-500 shadow-[2px_2px_0px_0px_rgba(107,114,128,1)]');
                        
                        row.find('.role-cell').html(`<span class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-pill border-2 ${roleClass}">${roleLabel}</span>`);
                        showToast(response.message, 'success');
                    }
                });
            };
        });
    </script>
    @endpush
</x-app-layout>
