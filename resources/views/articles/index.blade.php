<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Berita, artikel kesehatan, dan acara terbaru dari DelimaCare — Klinik Kesehatan Ibu & Anak.">
    <title>Berita & Konten - DelimaCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script>if(localStorage.getItem('delimacare-dark')==='true'||(localStorage.getItem('delimacare-dark')===null&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark');</script>
</head>
<body class="antialiased bg-[#FAFFFE]" x-data="articlesPage()">

    {{-- Navbar --}}
    <header class="glass shadow-md fixed w-full top-0 z-50 px-6 md:px-16 py-3 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background: var(--gradient-main);">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">DelimaCare</span>
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold border border-gray-300 text-gray-700 hover:border-teal-500 hover:text-teal-600 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Beranda
            </a>
            <button @click="toggleDark()" class="dark-toggle text-gray-600" aria-label="Toggle dark mode">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>
            <a href="{{ route('login') }}" class="btn-dark text-sm !py-2.5 !px-6">Login</a>
        </div>
    </header>

    <div class="h-20"></div>

    {{-- Header --}}
    <section class="px-6 md:px-16 pt-12 pb-8 max-w-7xl mx-auto">
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-teal-600 transition">Beranda</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-900 font-medium">Berita & Konten</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3">Berita, Acara & Konten</h1>
            <p class="text-gray-500 max-w-xl">Temukan informasi terkini seputar kesehatan ibu & anak, acara klinik, dan artikel edukatif.</p>
        </div>

        {{-- Search + Filter + Sort --}}
        <div class="flex flex-col md:flex-row md:items-center gap-4 mb-8">
            {{-- Search --}}
            <div class="relative flex-1">
                <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="searchQuery" placeholder="Cari berdasarkan judul..." class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none text-sm transition">
            </div>
            {{-- Sort --}}
            <select x-model="sortBy" class="sort-select">
                <option value="terbaru">Terbaru</option>
                <option value="terlama">Terlama</option>
                <option value="terpopuler">Terpopuler</option>
            </select>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex flex-wrap gap-2 mb-10">
            <button @click="activeFilter = 'semua'" :class="activeFilter === 'semua' ? 'active' : ''" class="filter-tab">Semua</button>
            <button @click="activeFilter = 'berita'" :class="activeFilter === 'berita' ? 'active' : ''" class="filter-tab">Berita</button>
            <button @click="activeFilter = 'artikel'" :class="activeFilter === 'artikel' ? 'active' : ''" class="filter-tab">Artikel</button>
            <button @click="activeFilter = 'acara'" :class="activeFilter === 'acara' ? 'active' : ''" class="filter-tab">Acara</button>
        </div>
    </section>

    {{-- Articles Grid --}}
    <section class="px-6 md:px-16 pb-24 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="article in filteredArticles" :key="article.slug">
                <a :href="'/artikel/' + article.slug" class="article-card">
                    <div class="article-card-img">
                        <img :src="article.image" :alt="article.title">
                        <div class="article-badge">
                            <span class="cat-badge" :class="'cat-' + article.category" x-text="article.categoryLabel"></span>
                        </div>
                    </div>
                    <div class="article-card-body">
                        <h3 x-text="article.title"></h3>
                        <p x-text="article.excerpt"></p>
                        <div class="article-card-meta">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span x-text="article.date"></span>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span x-text="article.views + ' views'"></span>
                            </span>
                        </div>
                    </div>
                </a>
            </template>
        </div>

        {{-- Empty state --}}
        <div x-show="filteredArticles.length === 0" class="text-center py-20">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Tidak ada konten ditemukan</h3>
            <p class="text-gray-500 text-sm">Coba ubah filter atau kata kunci pencarian Anda.</p>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="footer-section py-12 px-6 md:px-16">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: var(--gradient-main);">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-sm text-gray-500">© {{ date('Y') }} DelimaCare. Semua hak dilindungi.</span>
            </div>
            <div class="flex gap-6 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-teal-400 transition">Beranda</a>
                <a href="{{ route('portal') }}" class="text-gray-500 hover:text-teal-400 transition">Portal Pasien</a>
            </div>
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
    function articlesPage() {
        return {
            searchQuery: '',
            activeFilter: 'semua',
            sortBy: 'terbaru',
            darkMode: document.documentElement.classList.contains('dark'),
            toggleDark() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('delimacare-dark', this.darkMode);
                document.documentElement.classList.add('dark-transition');
                setTimeout(() => document.documentElement.classList.remove('dark-transition'), 350);
                this.darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
            },
            articles: [
                {
                    slug: 'tips-menjaga-kesehatan-ibu-hamil',
                    title: 'Tips Menjaga Kesehatan Ibu Hamil di Trimester Pertama',
                    excerpt: 'Trimester pertama kehamilan adalah periode krusial dalam perkembangan janin. Berikut tips penting yang perlu diperhatikan untuk menjaga kesehatan ibu dan bayi.',
                    image: 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600&q=80',
                    category: 'artikel',
                    categoryLabel: 'Artikel',
                    date: '25 Apr 2026',
                    dateSort: 20260425,
                    views: 1245,
                    readTime: '5 menit'
                },
                {
                    slug: 'jadwal-vaksinasi-anak-2026',
                    title: 'Jadwal Vaksinasi Anak Tahun 2026 Telah Dibuka',
                    excerpt: 'Pendaftaran vaksinasi untuk anak usia 0-5 tahun bulan ini telah dibuka. Segera daftarkan buah hati Anda melalui portal pasien.',
                    image: 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=600&q=80',
                    category: 'berita',
                    categoryLabel: 'Berita',
                    date: '22 Apr 2026',
                    dateSort: 20260422,
                    views: 892,
                    readTime: '3 menit'
                },
                {
                    slug: 'seminar-kesehatan-keluarga',
                    title: 'Seminar Kesehatan Keluarga: Nutrisi Ibu dan Tumbuh Kembang Anak',
                    excerpt: 'Ikuti seminar gratis bersama pakar nutrisi dan dokter spesialis anak pada tanggal 10 Mei 2026 di aula utama klinik.',
                    image: 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=600&q=80',
                    category: 'acara',
                    categoryLabel: 'Acara',
                    date: '18 Apr 2026',
                    dateSort: 20260418,
                    views: 654,
                    readTime: '4 menit'
                },
                {
                    slug: 'pentingnya-asi-eksklusif',
                    title: 'Pentingnya ASI Eksklusif untuk 6 Bulan Pertama Kehidupan Bayi',
                    excerpt: 'ASI eksklusif memberikan nutrisi sempurna yang dibutuhkan bayi. Pelajari manfaat dan tips sukses menyusui dari para ahli.',
                    image: 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=600&q=80',
                    category: 'artikel',
                    categoryLabel: 'Artikel',
                    date: '15 Apr 2026',
                    dateSort: 20260415,
                    views: 1567,
                    readTime: '6 menit'
                },
                {
                    slug: 'layanan-kb-modern',
                    title: 'Mengenal Layanan Keluarga Berencana Modern di DelimaCare',
                    excerpt: 'Berbagai pilihan kontrasepsi modern tersedia di klinik kami. Konsultasikan dengan dokter untuk menemukan metode yang tepat.',
                    image: 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=600&q=80',
                    category: 'berita',
                    categoryLabel: 'Berita',
                    date: '10 Apr 2026',
                    dateSort: 20260410,
                    views: 723,
                    readTime: '4 menit'
                },
                {
                    slug: 'workshop-senam-hamil',
                    title: 'Workshop Senam Hamil Gratis — Daftar Sekarang!',
                    excerpt: 'Workshop senam hamil rutin setiap hari Sabtu pukul 08.00. Dipandu oleh instruktur bersertifikat untuk kehamilan yang sehat.',
                    image: 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&q=80',
                    category: 'acara',
                    categoryLabel: 'Acara',
                    date: '5 Apr 2026',
                    dateSort: 20260405,
                    views: 432,
                    readTime: '3 menit'
                }
            ],
            get filteredArticles() {
                let result = this.articles;

                // Filter by category
                if (this.activeFilter !== 'semua') {
                    result = result.filter(a => a.category === this.activeFilter);
                }

                // Filter by search
                if (this.searchQuery.trim()) {
                    const q = this.searchQuery.toLowerCase();
                    result = result.filter(a => a.title.toLowerCase().includes(q) || a.excerpt.toLowerCase().includes(q));
                }

                // Sort
                if (this.sortBy === 'terbaru') {
                    result = [...result].sort((a, b) => b.dateSort - a.dateSort);
                } else if (this.sortBy === 'terlama') {
                    result = [...result].sort((a, b) => a.dateSort - b.dateSort);
                } else if (this.sortBy === 'terpopuler') {
                    result = [...result].sort((a, b) => b.views - a.views);
                }

                return result;
            }
        };
    }
    </script>
</body>
</html>
