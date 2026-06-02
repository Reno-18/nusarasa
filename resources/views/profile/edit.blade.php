<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <!-- Header -->
        <div class="py-16 text-center" data-aos="zoom-in">
            <h1 class="text-6xl md:text-8xl font-black font-display uppercase tracking-tighter leading-none text-nusarasa-dark mb-4">
                Pengaturan <span class="text-nusarasa-yellow">Profil</span>
            </h1>
            <p class="text-xl font-bold opacity-60 uppercase tracking-widest">Kelola identitas dan keamanan akunmu</p>
        </div>

        <div class="grid grid-cols-1 gap-12 max-w-4xl mx-auto">
            <!-- Information Section -->
            <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-12 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-none transition-all" data-aos="fade-up">
                <div class="flex items-center gap-4 mb-10 pb-6 border-b-2 border-nusarasa-dark/10">
                    <div class="w-12 h-12 bg-nusarasa-pink border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        👤
                    </div>
                    <div>
                        <h2 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Informasi Profil</h2>
                        <p class="text-sm font-bold opacity-40 uppercase tracking-widest">Data diri dan alamat email</p>
                    </div>
                </div>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Section -->
            <div class="bg-white border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-12 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-none transition-all" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center gap-4 mb-10 pb-6 border-b-2 border-nusarasa-dark/10">
                    <div class="w-12 h-12 bg-nusarasa-purple border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-white">
                        🔐
                    </div>
                    <div>
                        <h2 class="text-2xl font-black font-display uppercase tracking-tighter text-nusarasa-dark">Keamanan Akun</h2>
                        <p class="text-sm font-bold opacity-40 uppercase tracking-widest">Ubah kata sandi berkala</p>
                    </div>
                </div>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Danger Zone Section -->
            <div class="bg-red-50 border-4 border-nusarasa-dark rounded-[3rem] p-8 md:p-12 shadow-[12px_12px_0px_0px_rgba(239,68,68,1)] hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-none transition-all" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center gap-4 mb-10 pb-6 border-b-2 border-red-500/20">
                    <div class="w-12 h-12 bg-red-500 border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-white">
                        ⚠️
                    </div>
                    <div>
                        <h2 class="text-2xl font-black font-display uppercase tracking-tighter text-red-600">Hapus Akun</h2>
                        <p class="text-sm font-bold opacity-40 uppercase tracking-widest text-red-400">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
