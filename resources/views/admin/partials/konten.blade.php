{{--
    ┌─────────────────────────────────────────────────────────────────┐
    │  Kelola Konten  — Optimized for Performance                     │
    │  • AJAX Powered — No full page refresh                          │
    │  • Modern Medical Loader — Pulse animation                      │
    │  • paginate(9)  — small DOM, fast scroll                        │
    └─────────────────────────────────────────────────────────────────┘
--}}

{{-- ─── LOCAL STYLES ─────────────────────────────────────────────── --}}
<style>
    /* ── Grid layout ───────────────────────────────── */
    .kc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.25rem;
    }

    /* ── Card ──────────────────────────────────────── */
    .kc-card {
        contain: layout paint style;
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        transition: box-shadow .22s ease, transform .22s ease;
        will-change: transform;
        display: flex;
        flex-direction: column;
    }
    .dark .kc-card {
        background: #1E293B;
        border-color: #1e3a5f;
    }
    .kc-card:hover {
        box-shadow: 0 8px 28px rgba(13,148,136,0.13);
        transform: translateY(-3px);
    }

    /* ── Image wrapper ─────────────────────────────── */
    .kc-img-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: #f1f5f9;
        flex-shrink: 0;
    }
    .dark .kc-img-wrap { background: #0f172a; }

    .kc-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
        will-change: transform;
    }
    .kc-card:hover .kc-img-wrap img { transform: scale(1.04); }

    /* ── Placeholder (no image) ────────────────────── */
    .kc-no-img {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    /* ── Category badge ────────────────────────────── */
    .kc-badge {
        position: absolute;
        top: .6rem;
        left: .6rem;
        padding: .2rem .65rem;
        border-radius: 9999px;
        font-size: .65rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(6px);
        box-shadow: 0 1px 4px rgba(0,0,0,0.10);
    }
    .dark .kc-badge { background: rgba(15,23,42,0.88); }
    .kc-badge.artikel  { color: #0d9488; }
    .kc-badge.berita   { color: #2563eb; }
    .kc-badge.acara    { color: #d97706; }

    /* ── Card body ─────────────────────────────────── */
    .kc-body {
        padding: 1rem 1.1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }
    .kc-title {
        font-size: .95rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .dark .kc-title { color: #f1f5f9; }
    .kc-excerpt {
        font-size: .8rem;
        color: #64748b;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    .dark .kc-excerpt { color: #94a3b8; }
    .kc-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .6rem;
        font-size: .72rem;
        color: #94a3b8;
        font-weight: 500;
    }
    .kc-meta span { display: flex; align-items: center; gap: .3rem; }
    .kc-meta svg  { width: .9rem; height: .9rem; color: #0d9488; flex-shrink: 0; }

    /* ── Card footer ───────────────────────────────── */
    .kc-footer {
        padding: .65rem 1.1rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        gap: .5rem;
    }
    .dark .kc-footer { border-top-color: #1e3a5f; }

    .kc-btn {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .4rem .9rem;
        border-radius: .5rem;
        font-size: .75rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .18s, color .18s, box-shadow .18s, transform .18s;
        border: 1.5px solid transparent;
    }
    .kc-btn svg { width: .85rem; height: .85rem; flex-shrink: 0; }
    .kc-btn:active { transform: scale(.97); }

    .kc-btn-edit {
        background: #fff;
        border-color: #e2e8f0;
        color: #374151;
    }
    .dark .kc-btn-edit {
        background: #0f172a;
        border-color: #334155;
        color: #cbd5e1;
    }
    .kc-btn-edit:hover { background: #f8fafc; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }

    .kc-btn-del {
        background: #fff;
        border-color: #fecaca;
        color: #dc2626;
    }
    .dark .kc-btn-del {
        background: #0f172a;
        border-color: rgba(239,68,68,.35);
        color: #f87171;
    }
    .kc-btn-del:hover { background: #fef2f2; box-shadow: 0 2px 8px rgba(239,68,68,.12); }

    /* ── Pagination ────────────────────────────────── */
    .kc-page { display: flex; align-items: center; justify-content: center; gap: .45rem; flex-wrap: wrap; }
    .kc-page button, .kc-page span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2.1rem; height: 2.1rem; padding: 0 .65rem;
        border-radius: .6rem; font-size: .8rem; font-weight: 700;
        transition: background .18s, color .18s, box-shadow .18s;
        border: 1.5px solid transparent;
    }
    .kc-page button {
        color: #374151; background: #fff; border-color: #e2e8f0;
    }
    .dark .kc-page button {
        color: #cbd5e1; background: #1E293B; border-color: #334155;
    }
    .kc-page button:hover {
        background: #f0fdfa; border-color: #0d9488; color: #0d9488;
        box-shadow: 0 2px 8px rgba(13,148,136,.15);
    }
    .dark .kc-page button:hover { background: rgba(13,148,136,.15); color: #2dd4bf; border-color: #0d9488; }
    .kc-page span.active {
        background: linear-gradient(135deg,#0d9488,#06b6d4);
        color: #fff; border-color: transparent;
        box-shadow: 0 3px 10px rgba(13,148,136,.35);
    }
    .kc-page span.disabled { color: #cbd5e1; background: transparent; border-color: transparent; cursor: not-allowed; }
    .dark .kc-page span.disabled { color: #475569; }

    /* ── Filters ───────────────────────────────────── */
    .kc-filters { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.4rem; }
    .kc-filter-pill {
        display: inline-flex; align-items: center; gap: .4rem; padding: .4rem 1rem;
        border-radius: 9999px; font-size: .78rem; font-weight: 700;
        cursor: pointer; border: 1.5px solid transparent;
        transition: all .2s ease; white-space: nowrap;
    }
    .kc-filter-pill:active { transform: scale(.96); }

    .kc-filter-pill.pill-default { background: #fff; border-color: #e2e8f0; color: #64748b; }
    .dark .kc-filter-pill.pill-default { background: #1E293B; border-color: #334155; color: #94a3b8; }
    .kc-filter-pill.pill-default:hover { border-color: #0d9488; color: #0d9488; box-shadow: 0 2px 8px rgba(13,148,136,.12); }

    .pill-active-all { background: linear-gradient(135deg,#0d9488,#06b6d4); color: #fff; box-shadow: 0 3px 12px rgba(13,148,136,.35); }
    .pill-active-artikel { background: linear-gradient(135deg,#0f766e,#0d9488); color: #fff; box-shadow: 0 3px 10px rgba(13,148,136,.3); }
    .pill-active-berita  { background: linear-gradient(135deg,#1d4ed8,#2563eb); color: #fff; box-shadow: 0 3px 10px rgba(37,99,235,.3); }
    .pill-active-acara   { background: linear-gradient(135deg,#b45309,#d97706); color: #fff; box-shadow: 0 3px 10px rgba(217,119,6,.3); }

    .kc-pill-count {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 1.2rem; height: 1.2rem; padding: 0 .3rem;
        border-radius: 9999px; font-size: .65rem; font-weight: 900;
        background: rgba(0,0,0,0.05); color: currentColor;
    }
    [class*="pill-active-"] .kc-pill-count { background: rgba(255,255,255,.25); color: #fff; }

    .kc-pill-dot { width: .45rem; height: .45rem; border-radius: 50%; flex-shrink: 0; }

    /* ── Modern Medical Loader ────────────────────── */
    .medical-loader-container {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 5rem 2rem; width: 100%; min-height: 400px;
    }
    .medical-pulse {
        position: relative; width: 80px; height: 80px;
        display: flex; align-items: center; justify-content: center;
    }
    .pulse-ring {
        position: absolute; width: 100%; height: 100%;
        border-radius: 50%; border: 4px solid #0d9488;
        animation: medical-pulse-ring 1.5s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
    }
    .pulse-dot {
        width: 32px; height: 32px; background: #0d9488; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        animation: medical-pulse-dot 1.5s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        box-shadow: 0 0 20px rgba(13,148,136,0.4);
    }
    .pulse-dot svg { width: 20px; height: 20px; color: white; }

    @keyframes medical-pulse-ring {
        0% { transform: scale(0.3); opacity: 0.8; }
        80%, 100% { transform: scale(1.5); opacity: 0; }
    }
    @keyframes medical-pulse-dot {
        0% { transform: scale(0.8); }
        50% { transform: scale(1); }
        100% { transform: scale(0.8); }
    }

    .loading-text {
        margin-top: 1.5rem; font-size: 0.875rem; font-weight: 700;
        color: #0d9488; text-transform: uppercase; letter-spacing: 0.1em;
        animation: loading-fade 1.5s ease-in-out infinite;
    }
    @keyframes loading-fade { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }

    /* ── Empty State ──────────────────────────────── */
    .kc-empty {
        text-align: center; padding: 4rem 2rem; background: #fff;
        border-radius: 1.5rem; border: 2px dashed #e2e8f0;
    }
    .dark .kc-empty { background: #1E293B; border-color: #334155; }

    /* ── Modal ────────────────────────────────────── */
    .kc-modal-wrap {
        position: fixed; inset: 0; z-index: 110;
        display: flex; align-items: center; justify-content: center; padding: 1rem;
    }
    .kc-modal-backdrop {
        position: absolute; inset: 0; background: rgba(15,23,42,.6); backdrop-filter: blur(4px);
    }
    .kc-modal-box {
        position: relative; background: #fff; border-radius: 1.5rem;
        width: 100%; max-width: 26rem; overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,.2);
        animation: kcModalIn .25s cubic-bezier(.16,1,.3,1) both;
    }
    .dark .kc-modal-box { background: #1E293B; }
    @keyframes kcModalIn {
        from { opacity: 0; transform: scale(.94) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>

<div x-data="kontenManager()" x-init="init()" class="relative">

    {{-- ── Header ──────────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
        <div class="flex-1">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-1">Manajemen Konten</h2>
            <p id="konten-header-stats" class="text-sm text-gray-500 dark:text-gray-400">
                Kelola artikel, berita, dan acara untuk website
                @if($articles->total() > 0)
                    &nbsp;·&nbsp;
                    <span class="font-bold text-teal-600 dark:text-teal-400">
                        {{ $articles->total() }} {{ $activeCategory ? strtolower($activeCategory) : 'konten' }} ditemukan
                    </span>
                @endif
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 lg:min-w-[500px]">
            {{-- AJAX Search Bar --}}
            <div class="flex-1 relative group">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400 group-focus-within:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           x-model="search"
                           @input.debounce.500ms="fetchKonten()"
                           placeholder="Cari judul konten..."
                           class="w-full pl-10 pr-10 py-2.5 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800
                                  rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500
                                  dark:text-white transition-all shadow-sm placeholder-gray-400">
                    <button x-show="search" @click="search = ''; fetchKonten()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- AJAX Sort --}}
            <div class="relative flex-shrink-0">
                <select x-model="sort"
                        @change="fetchKonten()"
                        class="appearance-none pl-4 pr-10 py-2.5 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800
                               rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2
                               focus:ring-teal-500/20 focus:border-teal-500 transition-all shadow-sm cursor-pointer">
                    <option value="latest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="title_asc">Judul (A-Z)</option>
                    <option value="title_desc">Judul (Z-A)</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            <a href="{{ route('admin.konten.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white
                      bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600
                      shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 hover:-translate-y-0.5
                      transition-all duration-200 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah
            </a>
        </div>
    </div>

    {{-- ── AJAX Filter Pills ─────────────────────────── --}}
    @php
        $totalAll = array_sum($categoryCounts->toArray());
        $filters  = [
            ''        => ['label' => 'Semua',   'dot' => 'bg-gradient-to-r from-teal-500 to-cyan-400', 'active_class' => 'pill-active-all'],
            'Artikel' => ['label' => 'Artikel', 'dot' => 'bg-teal-500',  'active_class' => 'pill-active-artikel'],
            'Berita'  => ['label' => 'Berita',  'dot' => 'bg-blue-500',  'active_class' => 'pill-active-berita'],
            'Acara'   => ['label' => 'Acara',   'dot' => 'bg-amber-500', 'active_class' => 'pill-active-acara'],
        ];
    @endphp

    <div id="konten-filter-container" class="kc-filters" role="tablist">
        @foreach($filters as $catKey => $meta)
            <button @click="category = '{{ $catKey }}'; fetchKonten()"
                    class="kc-filter-pill"
                    :class="category === '{{ $catKey }}' ? '{{ $meta['active_class'] }}' : 'pill-default'"
                    role="tab">
                <span class="kc-pill-dot {{ $meta['dot'] }}"></span>
                {{ $meta['label'] }}
                <span class="kc-pill-count">
                    {{ $catKey === '' ? $totalAll : ($categoryCounts[$catKey] ?? 0) }}
                </span>
            </button>
        @endforeach
    </div>

    {{-- ── AJAX Content Wrapper ──────────────────────── --}}
    <div id="konten-ajax-wrapper" class="relative min-h-[400px]">

        {{-- Loading Animation --}}
        <template x-if="loading">
            <div class="medical-loader-container anim-up">
                <div class="medical-pulse">
                    <div class="pulse-ring"></div>
                    <div class="pulse-dot">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <p class="loading-text">Menyiapkan Data Kesehatan...</p>
            </div>
        </template>

        {{-- Content Grid --}}
        <div x-show="!loading" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            @if($articles->count() > 0)
                <div class="kc-grid mb-8">
                    @foreach($articles as $article)
                    <article class="kc-card">
                        <div class="kc-img-wrap">
                            @if($article->image_path)
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" loading="lazy">
                            @else
                                <div class="kc-no-img">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-10 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <span class="kc-badge {{ strtolower($article->category) }}">{{ $article->category }}</span>
                        </div>
                        <div class="kc-body">
                            <h4 class="kc-title">{{ $article->title }}</h4>
                            <p class="kc-excerpt">{{ strip_tags($article->content) }}</p>
                            <div class="kc-meta">
                                <span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>{{ $article->author ? $article->author->username : 'Admin' }}</span>
                                <span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>{{ $article->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="kc-footer">
                            <a href="{{ route('admin.konten.edit', $article->id) }}" class="kc-btn kc-btn-edit">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>Edit
                            </a>
                            <button @click="confirmDelete({{ $article->id }}, '{{ addslashes($article->title) }}')" class="kc-btn kc-btn-del">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Hapus
                            </button>
                        </div>
                    </article>
                    @endforeach
                </div>

                {{-- AJAX Pagination --}}
                @if($articles->hasPages())
                <nav class="kc-page mb-2">
                    @if(!$articles->onFirstPage())
                        <button @click="gotoPage({{ $articles->currentPage() - 1 }})"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg></button>
                    @endif

                    @foreach($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                        @if($page == $articles->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <button @click="gotoPage({{ $page }})">{{ $page }}</button>
                        @endif
                    @endforeach

                    @if($articles->hasMorePages())
                        <button @click="gotoPage({{ $articles->currentPage() + 1 }})"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></button>
                    @endif
                </nav>
                <p class="text-center text-xs text-gray-400 dark:text-gray-600 mb-6">
                    Menampilkan {{ $articles->firstItem() }}–{{ $articles->lastItem() }} dari {{ $articles->total() }} konten
                </p>
                @endif
            @else
                <div class="kc-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-12 h-12 mx-auto mb-4 text-gray-300">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Konten tidak ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Coba kata kunci atau kategori lain.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" x-cloak class="kc-modal-wrap" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="kc-modal-backdrop" @click="showDeleteModal = false"></div>
        <div class="kc-modal-box">
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Hapus Konten?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Hapus <span class="font-bold" x-text="contentToDelete.title"></span>? Tindakan ini permanen.</p>
                <div class="flex gap-3">
                    <button @click="showDeleteModal = false" class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-800 font-bold rounded-xl text-sm">Batal</button>
                    <button @click="executeDelete()" class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 text-sm">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function kontenManager() {
    return {
        loading: false,
        search: '{{ $searchKonten }}',
        category: '{{ $activeCategory }}',
        sort: '{{ $sortKonten }}',
        page: 1,
        showDeleteModal: false,
        contentToDelete: { id: '', title: '' },

        init() {
            window.addEventListener('refresh-konten', () => this.fetchKonten());
        },

        async fetchKonten() {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    tab: 'konten',
                    category: this.category,
                    search_konten: this.search,
                    sort_konten: this.sort,
                    page: this.page
                });

                const response = await fetch(`{{ route('admin.konten.partial') }}?${params.toString()}`);
                const html = await response.text();

                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update Wrapper Content
                const newWrapper = doc.getElementById('konten-ajax-wrapper');
                if (newWrapper) {
                    document.getElementById('konten-ajax-wrapper').innerHTML = newWrapper.innerHTML;
                }

                // Update Filters inner content - USE SPECIFIC ID
                const newFilters = doc.getElementById('konten-filter-container');
                if (newFilters) {
                    document.getElementById('konten-filter-container').innerHTML = newFilters.innerHTML;
                }

                // Update Header Text - USE SPECIFIC ID
                const newHeader = doc.getElementById('konten-header-stats');
                if (newHeader) {
                    document.getElementById('konten-header-stats').innerHTML = newHeader.innerHTML;
                }

                // Update URL
                const newUrl = window.location.origin + window.location.pathname + '?' + params.toString();
                window.history.pushState({ path: newUrl }, '', newUrl);

            } catch (error) {
                console.error('AJAX Error:', error);
            } finally {
                this.loading = false;
            }
        },

        gotoPage(p) {
            this.page = p;
            this.fetchKonten();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        confirmDelete(id, title) {
            this.contentToDelete = { id, title };
            this.showDeleteModal = true;
        },

        executeDelete() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('admin/konten') }}/${this.contentToDelete.id}`;
            const csrf = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
            const method = document.createElement('input');
            method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
            form.appendChild(csrf); form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    }
}
</script>
