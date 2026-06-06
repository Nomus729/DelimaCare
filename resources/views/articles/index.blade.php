<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kumpulan Konten - DelimaCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {} }
        }
    </script>
    <script>if(localStorage.getItem('delimacare-dark')==='true'||(localStorage.getItem('delimacare-dark')===null&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark');</script>
</head>

<body class="bg-[#FAFFFE] dark:bg-[#0B1120] antialiased">

    {{-- Minimal Header --}}
    <header class="fixed w-full top-0 z-50 bg-white/80 dark:bg-[#0B1120]/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 md:px-16 h-20 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-300 group-hover:scale-110 shadow-md" style="background: var(--gradient-main);">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <span class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">DelimaCare</span>
            </a>
            <div class="flex items-center gap-4">
                <button id="darkModeToggle" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-500 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" aria-label="Toggle dark mode">
                    <svg id="moonIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg id="sunIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
                <a href="{{ route('home') }}#konten" class="text-sm font-semibold text-gray-500 hover:text-teal-600 dark:text-gray-400 dark:hover:text-teal-400 transition-colors">Kembali ke Beranda</a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="pt-32 pb-24 max-w-7xl mx-auto px-6 md:px-16">
        
        <div class="text-center mb-12 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4">Pusat Informasi Kesehatan</h1>
            <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">Kumpulan artikel kesehatan, berita klinik, dan informasi acara terbaru dari DelimaCare.</p>
        </div>

        {{-- Search and Filter --}}
        <div class="mb-12 flex flex-col md:flex-row gap-6 items-center justify-between animate-fade-in-up" style="animation-delay: 100ms">
            {{-- Category Filter Pills --}}
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('articles.index', ['search' => $searchQuery]) }}" class="px-5 py-2 rounded-full text-sm font-bold transition-all {{ $currentCategory === '' ? 'bg-teal-600 text-white shadow-md shadow-teal-500/30' : 'bg-white dark:bg-[#1E293B] text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400' }}">Semua</a>
                <a href="{{ route('articles.index', ['category' => 'Berita', 'search' => $searchQuery]) }}" class="px-5 py-2 rounded-full text-sm font-bold transition-all {{ $currentCategory === 'Berita' ? 'bg-teal-600 text-white shadow-md shadow-teal-500/30' : 'bg-white dark:bg-[#1E293B] text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400' }}">Berita</a>
                <a href="{{ route('articles.index', ['category' => 'Artikel', 'search' => $searchQuery]) }}" class="px-5 py-2 rounded-full text-sm font-bold transition-all {{ $currentCategory === 'Artikel' ? 'bg-teal-600 text-white shadow-md shadow-teal-500/30' : 'bg-white dark:bg-[#1E293B] text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400' }}">Artikel</a>
                <a href="{{ route('articles.index', ['category' => 'Acara', 'search' => $searchQuery]) }}" class="px-5 py-2 rounded-full text-sm font-bold transition-all {{ $currentCategory === 'Acara' ? 'bg-teal-600 text-white shadow-md shadow-teal-500/30' : 'bg-white dark:bg-[#1E293B] text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400' }}">Acara</a>
            </div>

            {{-- Search Bar --}}
            <form action="{{ route('articles.index') }}" method="GET" class="w-full md:w-auto flex-1 max-w-sm">
                @if($currentCategory)
                    <input type="hidden" name="category" value="{{ $currentCategory }}">
                @endif
                <div class="relative group">
                    <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Cari konten..." class="w-full pl-12 pr-4 py-3 bg-white dark:bg-[#1E293B] border border-gray-200 dark:border-gray-700 rounded-full focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none text-gray-900 dark:text-white text-sm font-medium transition-all shadow-sm group-hover:shadow-md">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-teal-600 hover:bg-teal-700 text-white p-1.5 rounded-full transition-all hover:scale-105 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>

        @if($articles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($articles as $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="group flex flex-col rounded-2xl overflow-hidden bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl transition-all duration-300 animate-fade-in-up" style="animation-delay: {{ $loop->index * 100 }}ms">
                <div class="aspect-video w-full bg-gray-100 dark:bg-gray-800 overflow-hidden relative">
                    @if($article->image_path)
                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @endif
                    <span class="absolute top-4 left-4 px-3 py-1 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-[11px] font-extrabold uppercase rounded shadow-sm text-teal-600 dark:text-teal-400">
                        {{ $article->category }}
                    </span>
                </div>
                <div class="p-8 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 leading-snug group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-2">{{ $article->title }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed mb-6 line-clamp-3 flex-1">{{ strip_tags($article->content) }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-800 text-xs text-gray-400 font-medium">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-teal-50 dark:bg-teal-900/50 flex items-center justify-center text-teal-700 dark:text-teal-400 font-bold">
                                {{ substr($article->author->username ?? 'A', 0, 1) }}
                            </div>
                            <span>{{ $article->author->username ?? 'Admin' }}</span>
                        </div>
                        <span>{{ $article->created_at->locale('id')->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-16 flex justify-center">
            {{ $articles->links() }}
        </div>
        @else
        <div class="text-center py-24 bg-white dark:bg-[#1E293B] rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                {{ ($searchQuery || $currentCategory) ? 'Konten Tidak Ditemukan' : 'Belum ada konten' }}
            </h3>
            <p class="text-gray-500 dark:text-gray-400">
                {{ ($searchQuery || $currentCategory) ? 'Maaf, tidak ada artikel yang sesuai dengan pencarian Anda. Silakan coba kata kunci lain.' : 'Silakan periksa kembali nanti untuk informasi terbaru.' }}
            </p>
        </div>
        @endif

    </main>

    {{-- Minimal Footer --}}
    <footer class="py-8 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800">
        © {{ date('Y') }} DelimaCare. Semua hak dilindungi.
    </footer>

    <script>
        const toggleBtn = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const moonIcon = document.getElementById('moonIcon');
        const sunIcon = document.getElementById('sunIcon');

        if (html.classList.contains('dark')) {
            moonIcon.classList.add('hidden');
            sunIcon.classList.remove('hidden');
        }

        toggleBtn.addEventListener('click', () => {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('delimacare-dark', isDark);
            
            if (isDark) {
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
            } else {
                moonIcon.classList.remove('hidden');
                sunIcon.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
