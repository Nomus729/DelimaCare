<div x-data="{
    showDeleteModal: false,
    contentToDelete: { id: '', title: '' },
    confirmDelete(id, title) {
        this.contentToDelete = { id, title };
        this.showDeleteModal = true;
    },
    executeDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('admin/konten') }}/${this.contentToDelete.id}`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-1">Manajemen Konten</h2>
            <p class="text-gray-500 dark:text-gray-400">Kelola artikel, berita, dan acara untuk website</p>
        </div>
        <a href="{{ route('admin.konten.create') }}" class="bg-teal-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-teal-700 transition-colors shadow-lg shadow-teal-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Buat Konten Baru
        </a>
    </div>


    <div class="space-y-4 mb-8">
        @forelse($articles ?? [] as $article)
        <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-xl p-4 shadow-sm flex flex-col md:flex-row gap-6 hover:shadow-md transition-shadow">
            <div class="w-full md:w-48 h-32 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-800 relative">
                @if($article->image_path)
                    <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full h-full object-cover" alt="{{ $article->title }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                <span class="absolute top-2 left-2 px-2.5 py-1 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-[10px] font-extrabold uppercase rounded shadow-sm {{ $article->category == 'Artikel' ? 'text-teal-600 dark:text-teal-400' : ($article->category == 'Berita' ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400') }}">
                    {{ $article->category }}
                </span>
            </div>
            <div class="flex-grow flex flex-col justify-between">
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1 hover:text-teal-600 transition-colors">{{ $article->title }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">{{ strip_tags($article->content) }}</p>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400 font-medium">
                        <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> {{ $article->author ? $article->author->username : 'Admin' }}</span>
                        <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $article->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="flex gap-2 mt-4 md:mt-0 pt-3 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('admin.konten.edit', $article->id) }}" class="px-4 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded flex items-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                    </a>
                    <button @click="confirmDelete({{ $article->id }}, '{{ addslashes($article->title) }}')" class="px-4 py-1.5 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 text-xs font-bold rounded flex items-center gap-2 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white dark:bg-[#1E293B] rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum ada konten</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Mulai tulis artikel, berita, atau informasi acara sekarang.</p>
            <a href="{{ route('admin.konten.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 font-semibold text-sm rounded-lg hover:bg-teal-100 dark:hover:bg-teal-900/50 transition-colors">
                Buat Konten Pertama
            </a>
        </div>
        @endforelse
    </div>

    {{-- Custom Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div @click="showDeleteModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md rounded-3xl shadow-2xl overflow-hidden anim-up" x-transition>
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Hapus Konten?</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Apakah Anda yakin ingin menghapus <span class="font-bold text-gray-900 dark:text-white" x-text="contentToDelete.title"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex gap-3">
                    <button @click="showDeleteModal = false" class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">
                        Batal
                    </button>
                    <button @click="executeDelete()" class="flex-1 px-4 py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all">
                        Hapus Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
