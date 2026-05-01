<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - DelimaCare</title>

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
    
    <style>
        /* Typography for Rich Text Content */
        .article-content {
            color: #334155;
            line-height: 1.8;
            font-size: 1.125rem;
        }
        html.dark .article-content {
            color: #CBD5E1;
        }
        .article-content p { margin-bottom: 1.5em; }
        .article-content h1, .article-content h2, .article-content h3 {
            color: #0F172A;
            font-weight: 800;
            margin-top: 2em;
            margin-bottom: 0.75em;
            line-height: 1.3;
        }
        html.dark .article-content h1, html.dark .article-content h2, html.dark .article-content h3 {
            color: #F8FAFC;
        }
        .article-content h2 { font-size: 1.875rem; }
        .article-content h3 { font-size: 1.5rem; }
        .article-content a { color: #0D9488; text-decoration: underline; text-underline-offset: 4px; }
        html.dark .article-content a { color: #2DD4BF; }
        .article-content a:hover { color: #0F766E; }
        .article-content strong { font-weight: 700; color: #1E293B; }
        html.dark .article-content strong { color: #F1F5F9; }
        .article-content ul { list-style-type: disc; margin-left: 1.5em; margin-bottom: 1.5em; }
        .article-content ol { list-style-type: decimal; margin-left: 1.5em; margin-bottom: 1.5em; }
        .article-content li { margin-bottom: 0.5em; }
        .article-content blockquote {
            border-left: 4px solid #0D9488;
            padding-left: 1.25em;
            font-style: italic;
            color: #475569;
            margin: 2em 0;
            background: #F0FDFA;
            padding: 1.5em;
            border-radius: 0 0.75rem 0.75rem 0;
        }
        html.dark .article-content blockquote {
            border-left-color: #2DD4BF;
            color: #94A3B8;
            background: #134E4A;
        }
        .article-content figure {
            margin: 2.5em auto;
            text-align: center;
        }
        .article-content figure img {
            margin: 0 auto 0.5em auto; /* Mepet ke caption */
        }
        .article-content img {
            border-radius: 1rem;
            margin: 2.5em auto; /* Margin default jika gambar berdiri sendiri tanpa figure */
            max-width: 100%;
            height: auto;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .article-content figcaption {
            font-size: 0.875rem; /* Lebih kecil */
            color: #94A3B8; /* Dikaburkan */
            text-align: center; /* Di tengah */
            font-style: italic;
            margin-top: 0.25rem; /* Jarak sangat dekat dengan gambar */
        }
        html.dark .article-content figcaption {
            color: #64748B;
        }
        
        /* Quill Alignment Classes */
        .article-content .ql-align-center { text-align: center; }
        .article-content .ql-align-right { text-align: right; }
        .article-content .ql-align-justify { text-align: justify; }

        .article-content pre {
            background-color: #1E293B;
            color: #E2E8F0;
            padding: 1.5em;
            border-radius: 0.75rem;
            overflow-x: auto;
            margin-bottom: 1.5em;
        }
        .article-content code {
            background-color: #F1F5F9;
            color: #EF4444;
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
            font-size: 0.875em;
        }
        html.dark .article-content code {
            background-color: #334155;
            color: #F87171;
        }
    </style>
</head>

<body class="bg-[#FAFFFE] dark:bg-[#0B1120] antialiased selection:bg-teal-500 selection:text-white">

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
                <a href="{{ route('articles.index') }}" class="text-sm font-semibold text-gray-500 hover:text-teal-600 dark:text-gray-400 dark:hover:text-teal-400 transition-colors">Kembali</a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="pt-32 pb-24">
        <article class="max-w-3xl mx-auto px-6">
            
            {{-- Article Header --}}
            <header class="mb-12 text-center animate-fade-in-up">
                <span class="inline-block px-3 py-1 mb-6 rounded-full text-xs font-extrabold uppercase tracking-widest border border-teal-200 text-teal-700 bg-teal-50 dark:bg-teal-900/30 dark:border-teal-800 dark:text-teal-400">
                    {{ $article->category }}
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight mb-8">
                    {{ $article->title }}
                </h1>
                
                <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-500 dark:text-gray-400 font-medium">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-900/50 flex items-center justify-center text-teal-700 dark:text-teal-400 font-bold">
                            {{ substr($article->author->username ?? 'A', 0, 1) }}
                        </div>
                        <span>Oleh <strong class="text-gray-900 dark:text-white">{{ $article->author->username ?? 'Admin' }}</strong></span>
                    </div>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-700"></span>
                    <span>{{ $article->created_at->isoFormat('D MMMM YYYY') }}</span>
                </div>
            </header>

            {{-- Feature Image --}}
            @if($article->image_path)
            <div class="mb-14 rounded-3xl overflow-hidden shadow-2xl animate-fade-in-up delay-100 aspect-video relative">
                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
            </div>
            @endif

            {{-- Rich Text Body --}}
            <div class="article-content animate-fade-in-up delay-200">
                {!! $article->content !!}
            </div>

            {{-- Share & Footer --}}
            <footer class="mt-20 pt-8 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Bagikan:</span>
                    <button class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 hover:text-[#1877F2] hover:bg-blue-50 transition-all"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></button>
                    <button class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 hover:text-[#1DA1F2] hover:bg-blue-50 transition-all"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></button>
                    <button class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 hover:text-[#25D366] hover:bg-green-50 transition-all"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></button>
                </div>
            </footer>
        </article>
        
        {{-- Related Articles --}}
        @if($related->count() > 0)
        <div class="max-w-4xl mx-auto px-6 mt-16 pt-16 border-t border-gray-100 dark:border-gray-800">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">Mungkin Anda Juga Suka</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($related as $rel)
                <a href="{{ route('articles.show', $rel->slug) }}" class="group block rounded-2xl overflow-hidden bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="aspect-[16/9] w-full bg-gray-100 dark:bg-gray-800 overflow-hidden relative">
                        @if($rel->image_path)
                            <img src="{{ asset('storage/' . $rel->image_path) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @endif
                        <span class="absolute top-3 left-3 px-2 py-1 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-[10px] font-extrabold uppercase rounded text-teal-600 dark:text-teal-400">
                            {{ $rel->category }}
                        </span>
                    </div>
                    <div class="p-6">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2 leading-snug group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-2">{{ $rel->title }}</h4>
                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-4">
                            <span class="font-semibold">{{ $rel->author->username ?? 'Admin' }}</span>
                            <span>•</span>
                            <span>{{ $rel->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
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
