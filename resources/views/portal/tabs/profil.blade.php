{{-- Modal Edit Profil (Pakai Native JS biar gak bentrok) --}}
<div id="modalEditProfil" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">

    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 max-w-md w-full shadow-2xl relative dark:bg-[#1E293B] border border-gray-100 dark:border-gray-700 animate-fade-in">

        {{-- Close Button --}}
        <button type="button" onclick="document.getElementById('modalEditProfil').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 rounded-3xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-teal-500/30"
                 style="background: var(--gradient-main); background-image: url('{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : '' }}'); background-size: cover; background-position: center;">
                @if(!Auth::user()->foto)
                    {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                @endif
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">Profil Pasien</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Update informasi akun Anda di sini</p>
        </div>

        <form action="{{ route('portal.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Upload Foto --}}
            <div class="group">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Foto Profil</label>
                <div class="relative">
                    <input type="file" name="foto" accept="image/*"
                           class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-teal-900/30 dark:file:text-teal-400 outline-none cursor-pointer">
                </div>
            </div>

            {{-- Input Username --}}
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                <input type="text" name="username" value="{{ Auth::user()->username }}"
                       class="w-full px-5 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none dark:bg-[#0F172A] dark:border-gray-700 dark:text-white transition-all font-medium">
            </div>

            {{-- Input Email --}}
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Alamat Email</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}"
                       class="w-full px-5 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none dark:bg-[#0F172A] dark:border-gray-700 dark:text-white transition-all font-medium">
            </div>

            <button type="submit" class="w-full py-4 rounded-2xl text-white font-bold text-sm transition-all duration-300 hover:-translate-y-1 mt-4 shadow-xl shadow-teal-500/20"
                    style="background: var(--gradient-main);">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
