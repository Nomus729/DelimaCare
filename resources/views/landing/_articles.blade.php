{{-- Berita & Konten Terbaru --}}
<section id="konten" class="relative py-24 px-6 md:px-16 overflow-hidden" style="background: linear-gradient(180deg, #FAFFFE 0%, #F0FDFA 30%, #CCFBF1 60%, #F0FDFA 85%, #F0FDF4 100%);">

    {{-- Subtle decorative elements --}}
    <div class="absolute top-20 right-0 w-72 h-72 rounded-full opacity-20" style="background: radial-gradient(circle, rgba(13,148,136,0.15), transparent 70%); filter: blur(40px);"></div>
    <div class="absolute bottom-10 left-0 w-56 h-56 rounded-full opacity-15" style="background: radial-gradient(circle, rgba(6,182,212,0.15), transparent 70%); filter: blur(40px);"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-14 scroll-reveal">
            <div>
                <span class="section-badge mb-4 inline-flex">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Berita & Konten
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-4">Informasi Terkini</h2>
                <p class="text-gray-500 mt-3 max-w-lg">Berita, artikel kesehatan, dan acara terbaru dari DelimaCare untuk keluarga Indonesia.</p>
            </div>
            <a href="{{ route('articles.index') }}" class="mt-6 md:mt-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold border-2 border-teal-600/20 text-teal-700 hover:bg-teal-600 hover:text-white hover:border-teal-600 transition-all duration-300 hover:-translate-y-0.5">
                Lihat Semua Konten
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        @if($articles->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Featured Large Card (First Article) --}}
            @php $featured = $articles->first(); @endphp
            <a href="{{ route('articles.show', $featured->slug) }}" class="group relative rounded-2xl overflow-hidden h-full min-h-[380px] flex scroll-reveal delay-100" style="box-shadow: 0 8px 32px rgba(13,148,136,0.1);">
                @if($featured->image_path)
                    <img src="{{ $featured->image_url }}" alt="{{ $featured->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                @else
                    <div class="absolute inset-0 w-full h-full bg-teal-800"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent"></div>
                <div class="relative z-10 mt-auto p-8 w-full">
                    <span class="cat-badge cat-{{ strtolower($featured->category) }} mb-3 inline-flex">{{ $featured->category }}</span>
                    <h3 class="text-2xl md:text-3xl font-extrabold text-white mb-3 leading-tight group-hover:text-teal-200 transition-colors">{{ $featured->title }}</h3>
                    <p class="text-gray-300 text-sm leading-relaxed mb-4 max-w-lg line-clamp-2">{{ strip_tags($featured->content) }}</p>
                    <div class="flex items-center gap-4 text-xs text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $featured->author->username ?? 'Admin' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $featured->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </a>

            {{-- Right Column: Stacked cards (Next 2 Articles) --}}
            <div class="flex flex-col gap-6">
                @foreach($articles->skip(1) as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="group flex flex-col sm:flex-row bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:border-teal-500/30 transition-all duration-300 flex-1 scroll-reveal delay-200">
                    <div class="w-full sm:w-48 h-48 sm:h-auto relative overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700">
                        @if($article->image_path)
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @endif
                    </div>
                    <div class="p-5 sm:p-6 flex flex-col justify-center flex-1">
                        <span class="cat-badge cat-{{ strtolower($article->category) }} mb-2 inline-flex self-start">{{ $article->category }}</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 leading-snug group-hover:text-teal-600 transition-colors">{{ $article->title }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed mb-3 line-clamp-2">{{ strip_tags($article->content) }}</p>
                        <div class="flex items-center gap-3 text-xs text-gray-400 mt-auto">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $article->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-16">
            <p class="text-gray-500">Belum ada konten yang dipublikasikan.</p>
        </div>
        @endif
    </div>
</section>
