{{--
    ┌─────────────────────────────────────────────────────────────────┐
    │  Kelola Konten  — Optimized for Performance                     │
    │  • paginate(6)  — small DOM, fast scroll                        │
    │  • CSS contain  — isolate paint/layout per card                 │
    │  • loading="lazy" — defer off-screen images                     │
    │  • will-change: transform  — GPU-composited hover               │
    │  • Minimal Alpine — only for delete modal                       │
    └─────────────────────────────────────────────────────────────────┘
--}}

{{-- ─── LOCAL STYLES (scoped, loaded once) ─────────────────────────── --}}
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
    .kc-page a, .kc-page span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2.1rem; height: 2.1rem; padding: 0 .65rem;
        border-radius: .6rem; font-size: .8rem; font-weight: 700;
        transition: background .18s, color .18s, box-shadow .18s;
        border: 1.5px solid transparent;
    }
    .kc-page a {
        color: #374151; background: #fff; border-color: #e2e8f0;
    }
    .dark .kc-page a {
        color: #cbd5e1; background: #1E293B; border-color: #334155;
    }
    .kc-page a:hover {
        background: #f0fdfa; border-color: #0d9488; color: #0d9488;
        box-shadow: 0 2px 8px rgba(13,148,136,.15);
    }
    .dark .kc-page a:hover { background: rgba(13,148,136,.15); color: #2dd4bf; border-color: #0d9488; }
    .kc-page span.active {
        background: linear-gradient(135deg,#0d9488,#06b6d4);
        color: #fff; border-color: transparent;
        box-shadow: 0 3px 10px rgba(13,148,136,.35);
    }
    .kc-page span.disabled { color: #cbd5e1; background: transparent; border-color: transparent; cursor: not-allowed; }
    .dark .kc-page span.disabled { color: #475569; }

    /* ── Delete modal backdrop ─────────────────────── */
    .kc-modal-wrap {
        position: fixed; inset: 0; z-index: 110;
        display: flex; align-items: center; justify-content: center; padding: 1rem;
    }
    .kc-modal-backdrop {
        position: absolute; inset: 0;
        background: rgba(15,23,42,.6);
        backdrop-filter: blur(4px);
    }
    .kc-modal-box {
        position: relative;
        background: #fff; border-radius: 1.5rem;
        width: 100%; max-width: 26rem;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,.2);
        animation: kcModalIn .25s cubic-bezier(.16,1,.3,1) both;
    }
    .dark .kc-modal-box { background: #1E293B; }
    @keyframes kcModalIn {
        from { opacity: 0; transform: scale(.94) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* ── Empty state ───────────────────────────────── */
    .kc-empty {
        text-align: center;
        padding: 3.5rem 1.5rem;
        background: #fff;
        border-radius: 1rem;
        border: 1.5px dashed #cbd5e1;
    }
    .dark .kc-empty { background: #1E293B; border-color: #334155; }
    .kc-empty svg { color: #cbd5e1; width: 3rem; height: 3rem; margin: 0 auto 1rem; display: block; }
    .dark .kc-empty svg { color: #475569; }

    /* ── Category filter pills ─────────────────────── */
    .kc-filters {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: 1.4rem;
    }
    .kc-filter-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem 1rem;
        border-radius: 9999px;
        font-size: .78rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        border: 1.5px solid transparent;
        transition: background .18s, color .18s, border-color .18s, box-shadow .18s, transform .18s;
        white-space: nowrap;
    }
    .kc-filter-pill:active { transform: scale(.96); }

    /* Default (inactive) */
    .kc-filter-pill.pill-default {
        background: #fff;
        border-color: #e2e8f0;
        color: #64748b;
    }
    .dark .kc-filter-pill.pill-default {
        background: #1E293B;
        border-color: #334155;
        color: #94a3b8;
    }
    .kc-filter-pill.pill-default:hover {
        border-color: #0d9488;
        color: #0d9488;
        box-shadow: 0 2px 8px rgba(13,148,136,.12);
    }
    .dark .kc-filter-pill.pill-default:hover {
        border-color: #14b8a6;
        color: #2dd4bf;
    }

    /* Active pill — Semua */
    .kc-filter-pill.pill-active-all {
        background: linear-gradient(135deg,#0d9488,#06b6d4);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 3px 12px rgba(13,148,136,.35);
    }

    /* Active pill — Artikel (teal) */
    .kc-filter-pill.pill-active-artikel {
        background: linear-gradient(135deg,#0f766e,#0d9488);
        color: #fff; border-color: transparent;
        box-shadow: 0 3px 10px rgba(13,148,136,.3);
    }
    /* Active pill — Berita (blue) */
    .kc-filter-pill.pill-active-berita {
        background: linear-gradient(135deg,#1d4ed8,#2563eb);
        color: #fff; border-color: transparent;
        box-shadow: 0 3px 10px rgba(37,99,235,.3);
    }
    /* Active pill — Acara (amber) */
    .kc-filter-pill.pill-active-acara {
        background: linear-gradient(135deg,#b45309,#d97706);
        color: #fff; border-color: transparent;
        box-shadow: 0 3px 10px rgba(217,119,6,.3);
    }

    /* Badge count inside pill */
    .kc-pill-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.2rem;
        height: 1.2rem;
        padding: 0 .3rem;
        border-radius: 9999px;
        font-size: .65rem;
        font-weight: 900;
        line-height: 1;
    }
    .pill-active-all    .kc-pill-count,
    .pill-active-artikel .kc-pill-count,
    .pill-active-berita  .kc-pill-count,
    .pill-active-acara   .kc-pill-count { background: rgba(255,255,255,.25); color: #fff; }
    .pill-default .kc-pill-count { background: #f1f5f9; color: #64748b; }
    .dark .pill-default .kc-pill-count { background: #0f172a; color: #94a3b8; }

    /* Dot icon inside filter pill */
    .kc-pill-dot {
        width: .45rem; height: .45rem;
        border-radius: 50%;
        flex-shrink: 0;
    }
</style>

{{-- ─── ALPINE SCOPE (delete modal only) ───────────────────────────── --}}
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
        csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
        const method = document.createElement('input');
        method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
        form.appendChild(csrf); form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}">

    {{-- ── Header ──────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-0.5">Manajemen Konten</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola artikel, berita, dan acara untuk website
                @if($articles->total() > 0)
                    &nbsp;·&nbsp;
                    <span class="font-semibold text-teal-600 dark:text-teal-400">
                        {{ $articles->total() }} {{ $activeCategory ? strtolower($activeCategory) : 'konten' }} ditemukan
                    </span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.konten.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white
                  bg-gradient-to-r from-teal-600 to-cyan-500
                  hover:from-teal-700 hover:to-cyan-600
                  shadow-lg shadow-teal-500/30
                  hover:shadow-teal-500/50 hover:-translate-y-0.5
                  transition-all duration-200 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Konten Baru
        </a>
    </div>

    {{-- ── Category Filter Pills ─────────────────────── --}}
    @php
        $totalAll = array_sum($categoryCounts->toArray());
        $filters  = [
            ''        => ['label' => 'Semua',   'dot' => 'bg-gradient-to-r from-teal-500 to-cyan-400', 'active_class' => 'pill-active-all'],
            'Artikel' => ['label' => 'Artikel', 'dot' => 'bg-teal-500',  'active_class' => 'pill-active-artikel'],
            'Berita'  => ['label' => 'Berita',  'dot' => 'bg-blue-500',  'active_class' => 'pill-active-berita'],
            'Acara'   => ['label' => 'Acara',   'dot' => 'bg-amber-500', 'active_class' => 'pill-active-acara'],
        ];
    @endphp

    <div class="kc-filters" role="tablist" aria-label="Filter kategori konten">
        @foreach($filters as $catKey => $meta)
            @php
                $isActive = ($activeCategory === $catKey);
                $count    = $catKey === '' ? $totalAll : ($categoryCounts[$catKey] ?? 0);
                $pillClass = $isActive ? 'kc-filter-pill ' . $meta['active_class'] : 'kc-filter-pill pill-default';
                // Build URL: keep ?tab=konten, set/remove ?category=, reset page=1
                $filterUrl = route('admin.dashboard') . '?tab=konten' . ($catKey !== '' ? '&category=' . $catKey : '');
            @endphp
            <a href="{{ $filterUrl }}"
               class="{{ $pillClass }}"
               role="tab"
               aria-selected="{{ $isActive ? 'true' : 'false' }}"
               aria-label="Filter {{ $meta['label'] }}">
                <span class="kc-pill-dot {{ $meta['dot'] }}"></span>
                {{ $meta['label'] }}
                <span class="kc-pill-count">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    {{-- ── Content grid ──────────────────────────────── --}}
    @if($articles->count() > 0)
    <div class="kc-grid mb-8">
        @foreach($articles as $article)
        <article class="kc-card">
            {{-- Image --}}
            <div class="kc-img-wrap">
                @if($article->image_path)
                    <img
                        src="{{ $article->image_url }}"
                        alt="{{ $article->title }}"
                        loading="lazy"
                        decoding="async"
                        width="480" height="270"
                    >
                @else
                    <div class="kc-no-img">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:2.5rem;height:2.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                <span class="kc-badge {{ strtolower($article->category) }}">
                    {{ $article->category }}
                </span>
            </div>

            {{-- Body --}}
            <div class="kc-body">
                <h4 class="kc-title">{{ $article->title }}</h4>
                <p class="kc-excerpt">{{ strip_tags($article->content) }}</p>
                <div class="kc-meta">
                    <span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $article->author ? $article->author->username : 'Admin' }}
                    </span>
                    <span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $article->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>

            {{-- Footer actions --}}
            <div class="kc-footer">
                <a href="{{ route('admin.konten.edit', $article->id) }}" class="kc-btn kc-btn-edit">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <button @click="confirmDelete({{ $article->id }}, '{{ addslashes($article->title) }}')"
                        class="kc-btn kc-btn-del">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </div>
        </article>
        @endforeach
    </div>

    {{-- ── Pagination ─────────────────────────────── --}}
    @if($articles->hasPages())
    <nav class="kc-page mb-2" aria-label="Navigasi halaman konten">
        {{-- Prev --}}
        @if($articles->onFirstPage())
            <span class="disabled" aria-disabled="true">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $articles->previousPageUrl() }}&tab=konten" rel="prev" aria-label="Halaman sebelumnya">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
            @if($page == $articles->currentPage())
                <span class="active" aria-current="page">{{ $page }}</span>
            @else
                <a href="{{ $url }}&tab=konten">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if($articles->hasMorePages())
            <a href="{{ $articles->nextPageUrl() }}&tab=konten" rel="next" aria-label="Halaman berikutnya">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="disabled" aria-disabled="true">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </nav>

    <p class="text-center text-xs text-gray-400 dark:text-gray-600 mb-6">
        Menampilkan {{ $articles->firstItem() }}–{{ $articles->lastItem() }} dari {{ $articles->total() }} konten
    </p>
    @endif

    @else
    {{-- ── Empty state ─────────────────────────── --}}
    <div class="kc-empty">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum ada konten</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-5">
            Mulai tulis artikel, berita, atau informasi acara sekarang.
        </p>
        <a href="{{ route('admin.konten.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white
                  bg-gradient-to-r from-teal-600 to-cyan-500 shadow-lg shadow-teal-500/30
                  hover:from-teal-700 hover:to-cyan-600 transition-all duration-200">
            Buat Konten Pertama
        </a>
    </div>
    @endif

    {{-- ── Delete Confirmation Modal ──────────────────── --}}
    <div x-show="showDeleteModal" x-cloak class="kc-modal-wrap"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="kc-modal-backdrop" @click="showDeleteModal = false"></div>
        <div class="kc-modal-box">
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Hapus Konten?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
                    Apakah Anda yakin ingin menghapus
                    <span class="font-bold text-gray-900 dark:text-white" x-text="contentToDelete.title"></span>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button @click="showDeleteModal = false"
                            class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                                   font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all text-sm">
                        Batal
                    </button>
                    <button @click="executeDelete()"
                            class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-bold rounded-xl
                                   hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all text-sm">
                        Hapus Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
