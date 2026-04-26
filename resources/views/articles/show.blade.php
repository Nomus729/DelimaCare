<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel - DelimaCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script>if(localStorage.getItem('delimacare-dark')==='true'||(localStorage.getItem('delimacare-dark')===null&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark');</script>
</head>
<body class="antialiased bg-white" x-data="articleShow()">

    <div class="reading-progress" :style="'width:' + readProgress + '%'"></div>

    <header class="glass shadow-md fixed w-full top-0 z-50 px-6 md:px-16 py-3 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background:var(--gradient-main);">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">DelimaCare</span>
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('articles.index') }}" class="hidden sm:inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold border border-gray-300 text-gray-700 hover:border-teal-500 hover:text-teal-600 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Semua Artikel
            </a>
            <button @click="toggleDark()" class="dark-toggle text-gray-600" aria-label="Toggle dark mode">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>
        </div>
    </header>

    <div class="h-20"></div>

    <article class="max-w-3xl mx-auto px-6 pt-10 pb-24">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-teal-600 transition">Beranda</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('articles.index') }}" class="hover:text-teal-600 transition">Konten</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-medium truncate max-w-[200px]" x-text="article.title"></span>
        </nav>

        <span class="cat-badge mb-4 inline-flex" :class="'cat-' + article.category" x-text="article.categoryLabel"></span>

        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-6" x-text="article.title"></h1>

        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-8 pb-6 border-b border-gray-100">
            <span class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background:var(--gradient-main);" x-text="article.authorInitials"></div>
                <span class="font-medium text-gray-700" x-text="article.author"></span>
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span x-text="article.date"></span>
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/></svg>
                <span x-text="article.readTime"></span>
            </span>
        </div>

        <div class="rounded-2xl overflow-hidden mb-10 border border-gray-100">
            <img :src="article.image" :alt="article.title" class="w-full h-64 md:h-80 object-cover">
        </div>

        <div class="prose-content" x-html="article.content"></div>

        {{-- Share --}}
        <div class="mt-12 pt-8 border-t border-gray-100">
            <p class="text-sm font-bold text-gray-900 mb-3">Bagikan Artikel Ini</p>
            <div class="flex gap-2">
                <button class="footer-social-icon !bg-gray-100 !text-gray-600 hover:!bg-teal-500 hover:!text-white"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></button>
                <button class="footer-social-icon !bg-gray-100 !text-gray-600 hover:!bg-teal-500 hover:!text-white"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg></button>
            </div>
        </div>
    </article>

    {{-- Related Articles --}}
    <section class="bg-gray-50 py-16 px-6 md:px-16">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Artikel Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <template x-for="item in relatedArticles" :key="item.slug">
                    <a :href="'/artikel/' + item.slug" class="article-card">
                        <div class="article-card-img" style="height:160px;">
                            <img :src="item.image" :alt="item.title">
                            <div class="article-badge"><span class="cat-badge" :class="'cat-' + item.category" x-text="item.categoryLabel"></span></div>
                        </div>
                        <div class="article-card-body">
                            <h3 x-text="item.title" style="font-size:1rem;"></h3>
                            <div class="article-card-meta">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span x-text="item.date"></span>
                                </span>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </section>

    <footer class="footer-section py-10 px-6 md:px-16">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="text-sm text-gray-500">© {{ date('Y') }} DelimaCare.</span>
            <div class="flex gap-6 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-teal-400 transition">Beranda</a>
                <a href="{{ route('articles.index') }}" class="text-gray-500 hover:text-teal-400 transition">Konten</a>
            </div>
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/article-show.js') }}"></script>
</body>
</html>
