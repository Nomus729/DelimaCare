// public/js/admin.js
// ─────────────────────────────────────────────────────────────────────────────
//  DelimaCare Admin Panel — Alpine.js + HTMX
//  Tab switching  : Alpine.js (state + DOM cache)
//  HTTP requests  : Fetch API untuk initial load, HTMX untuk pagination/forms
//  Re-init        : htmx:afterSwap → Alpine.initTree + enhanceTabContent
// ─────────────────────────────────────────────────────────────────────────────

// ── Tab color themes ──────────────────────────────────────────────────────────
const TAB_THEMES = {
    dashboard:   { acc1: '#0D9488', acc2: '#06B6D4', blob1: 'rgba(13,148,136,0.35)',  blob2: 'rgba(6,182,212,0.25)'   },
    konten:      { acc1: '#7C3AED', acc2: '#A78BFA', blob1: 'rgba(124,58,237,0.28)', blob2: 'rgba(167,139,250,0.18)' },
    inventori:   { acc1: '#0369A1', acc2: '#38BDF8', blob1: 'rgba(3,105,161,0.28)',  blob2: 'rgba(56,189,248,0.20)'  },
    keuangan:    { acc1: '#059669', acc2: '#34D399', blob1: 'rgba(5,150,105,0.30)',  blob2: 'rgba(52,211,153,0.20)'  },
    laporan:     { acc1: '#D97706', acc2: '#FCD34D', blob1: 'rgba(217,119,6,0.25)',  blob2: 'rgba(252,211,77,0.18)'  },
    doctors:     { acc1: '#0891B2', acc2: '#67E8F9', blob1: 'rgba(8,145,178,0.28)',  blob2: 'rgba(103,232,249,0.18)' },
    reservasi:   { acc1: '#0D9488', acc2: '#34D399', blob1: 'rgba(13,148,136,0.30)', blob2: 'rgba(52,211,153,0.18)'  },
    rekam_medis: { acc1: '#BE185D', acc2: '#F472B6', blob1: 'rgba(190,24,93,0.22)',  blob2: 'rgba(244,114,182,0.15)' },
    konsultasi:  { acc1: '#4F46E5', acc2: '#818CF8', blob1: 'rgba(79,70,229,0.25)',  blob2: 'rgba(129,140,248,0.18)' },
};

function applyTabTheme(tab) {
    const t    = TAB_THEMES[tab] || TAB_THEMES.dashboard;
    const root = document.documentElement;
    root.style.setProperty('--acc1',  t.acc1);
    root.style.setProperty('--acc2',  t.acc2);
    root.style.setProperty('--blob1', t.blob1);
    root.style.setProperty('--blob2', t.blob2);
}

// ── Route map: tabName → partial URL (exposed globally untuk HTMX handlers) ──
window.__adminTabRoutes = {
    dashboard:   '/admin/dashboard/partial',
    konten:      '/admin/konten/partial',
    inventori:   '/admin/inventori/partial',
    keuangan:    '/admin/keuangan/partial',
    laporan:     '/admin/laporan/partial',
    doctors:     '/admin/doctors/partial',
    reservasi:   '/admin/reservasi/partial',
    rekam_medis: '/admin/rekam-medis/partial',
    konsultasi:  '/admin/konsultasi/partial',
};

// ── Skeleton Loader ───────────────────────────────────────────────────────────
function buildSkeleton() {
    return `
    <div class="space-y-6 animate-pulse p-2" aria-label="Memuat konten...">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            ${Array(4).fill(0).map(() => `
            <div class="rounded-2xl bg-white dark:bg-[#0D1826] border border-gray-100 dark:border-gray-800 p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-full w-24"></div>
                    <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-700"></div>
                </div>
                <div class="h-7 bg-gray-200 dark:bg-gray-700 rounded-lg w-20"></div>
                <div class="h-2.5 bg-gray-100 dark:bg-gray-800 rounded-full w-32"></div>
            </div>`).join('')}
        </div>
        <div class="rounded-2xl bg-white dark:bg-[#0D1826] border border-gray-100 dark:border-gray-800 p-6 space-y-4">
            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded-full w-40"></div>
            ${Array(5).fill(0).map(() => `
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-700 flex-shrink-0"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-full w-full"></div>
                    <div class="h-2.5 bg-gray-100 dark:bg-gray-800 rounded-full w-3/4"></div>
                </div>
                <div class="h-6 bg-gray-100 dark:bg-gray-800 rounded-lg w-20 flex-shrink-0"></div>
            </div>`).join('')}
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl bg-white dark:bg-[#0D1826] border border-gray-100 dark:border-gray-800 h-48 p-5 flex items-center justify-center">
                <div class="w-32 h-32 rounded-full bg-gray-200 dark:bg-gray-700"></div>
            </div>
            <div class="rounded-2xl bg-white dark:bg-[#0D1826] border border-gray-100 dark:border-gray-800 h-48 p-5 space-y-3">
                ${Array(4).fill(0).map(() => `<div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-full w-full"></div>`).join('')}
            </div>
        </div>
    </div>`;
}

// ─────────────────────────────────────────────────────────────────────────────
//  HTMX Enhancement — Pagination & Form Filter
//  Dipanggil setelah setiap tab selesai diisi (dari _fetchTab dan htmx:afterSwap)
//  Menggantikan vanilla JS Pagination Interceptor yang sebelumnya rapuh.
// ─────────────────────────────────────────────────────────────────────────────
function enhanceTabContent(container, tabName) {
    const partialRoute = window.__adminTabRoutes[tabName];
    if (!partialRoute || typeof htmx === 'undefined') return;

    const containerId = container.id;

    // ── 1. Enhance filter & pagination <a> links ──────────────────────────────
    // Selector targets pagination links AND filter tabs/pills containing "tab=" in their href
    container.querySelectorAll(
        'nav[role="navigation"] a[href], [data-pagination] a[href], .pagination a[href], a[href*="tab="]'
    ).forEach(link => {
        if (link.getAttribute('hx-get')) return; // sudah diproses, skip

        try {
            const origUrl   = new URL(link.href);
            const targetUrl = new URL(partialRoute, location.origin);

            // Salin semua query params (page, search, filter, dll) kecuali 'tab'
            origUrl.searchParams.forEach((v, k) => {
                if (k !== 'tab') targetUrl.searchParams.set(k, v);
            });

            link.setAttribute('hx-get',       targetUrl.pathname + targetUrl.search);
            link.setAttribute('hx-target',     '#' + containerId);
            link.setAttribute('hx-swap',       'innerHTML');
            link.setAttribute('hx-indicator',  '#htmx-loading-bar');
            link.setAttribute('hx-push-url',   'false');
            htmx.process(link); // aktivasi HTMX pada elemen ini
        } catch (e) { /* URL tidak valid, lewati */ }
    });

    // ── 2. Enhance GET search/filter forms ───────────────────────────────────
    container.querySelectorAll('form[method="get"], form:not([method])').forEach(form => {
        // Lewati form yang sudah di-enhance atau dikelola Alpine (AJAX form)
        if (form.getAttribute('hx-get'))          return;
        if (form.getAttribute('hx-post'))         return;
        if (form.hasAttribute('data-no-htmx'))    return;

        // Deteksi form Alpine AJAX: biasanya punya @submit.prevent di atribut string
        const xDataParent = form.closest('[x-data]');
        if (xDataParent) {
            const xDataStr = xDataParent.getAttribute('x-data') || '';
            if (xDataStr.includes('submitForm') || xDataStr.includes('fetch(')) return;
        }

        form.setAttribute('hx-get',      partialRoute);
        form.setAttribute('hx-target',   '#' + containerId);
        form.setAttribute('hx-swap',     'innerHTML');
        form.setAttribute('hx-indicator','#htmx-loading-bar');
        htmx.process(form);
    });
}

// ─────────────────────────────────────────────────────────────────────────────
//  HTMX Global Event Listeners
// ─────────────────────────────────────────────────────────────────────────────

/**
 * htmx:afterSwap — dipanggil setelah HTMX mengganti konten DOM
 * Tugasnya: re-init Alpine + enhance pagination/forms dalam konten baru + update browser URL
 */
document.addEventListener('htmx:afterSwap', (e) => {
    const container = e.detail.target;
    if (!container || !container.id || !container.id.startsWith('tab-')) return;

    const tabName = container.id.replace('tab-', '');

    // 1. Re-init Alpine components yang ada dalam konten baru
    try {
        if (window.Alpine) Alpine.initTree(container);
    } catch (err) {
        console.warn('[HTMX] Alpine.initTree error:', err);
    }

    // 2. Enhance pagination links & search forms agar HTMX-aware
    enhanceTabContent(container, tabName);

    // 3. Update browser's URL to reflect the new parameters safely
    if (e.detail.requestConfig) {
        const reqPath = e.detail.requestConfig.path; // e.g. /admin/rekam-medis/partial?rm_kategori=Kehamilan
        try {
            const reqUrl = new URL(reqPath, window.location.origin);
            const browserUrl = new URL(window.location.pathname, window.location.origin);
            browserUrl.searchParams.set('tab', tabName);
            
            // Salin semua parameter filter/pencarian dari request AJAX ke URL browser
            reqUrl.searchParams.forEach((v, k) => {
                if (k !== 'tab') browserUrl.searchParams.set(k, v);
            });
            
            window.history.pushState({ tab: tabName }, '', browserUrl.pathname + browserUrl.search);
        } catch (err) {
            // URL parses can fail for malformed paths, fallback
        }
    }

    // 4. Fade in
    container.style.transition = 'opacity 0.2s ease';
    container.style.opacity    = '1';
});

/**
 * htmx:beforeSwap — fade out sebelum konten diganti
 */
document.addEventListener('htmx:beforeSwap', (e) => {
    const container = e.detail.target;
    if (!container?.id?.startsWith('tab-')) return;
    container.style.opacity    = '0.4';
    container.style.transition = 'opacity 0.15s ease';
});

/**
 * htmx:afterSettle — setelah DOM stabil, re-exec script yang butuh re-init
 * (ApexCharts yang diinisialisasi via inline <script> di dalam partial)
 */
document.addEventListener('htmx:afterSettle', (e) => {
    const container = e.detail.target;
    if (!container?.id?.startsWith('tab-')) return;

    // Re-execute script tags yang memiliki atribut data-reinit
    // (tambahkan data-reinit pada script ApexCharts di partial jika perlu)
    container.querySelectorAll('script[data-reinit]').forEach(oldScript => {
        const newScript = document.createElement('script');
        Array.from(oldScript.attributes).forEach(attr => {
            if (attr.name !== 'data-reinit') newScript.setAttribute(attr.name, attr.value);
        });
        newScript.textContent = oldScript.textContent;
        oldScript.replaceWith(newScript);
    });
});

/**
 * htmx:responseError — tampilkan error state jika request gagal
 */
document.addEventListener('htmx:responseError', (e) => {
    const container = e.detail.target;
    if (!container?.id?.startsWith('tab-')) return;

    const tabName = container.id.replace('tab-', '');
    container.innerHTML = `
    <div class="flex flex-col items-center justify-center py-24 text-center space-y-4">
        <div class="w-16 h-16 rounded-2xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-900 dark:text-white">Gagal memuat konten</p>
            <p class="text-xs text-gray-400 mt-1">Status: ${e.detail.xhr?.status || 'Unknown'}</p>
        </div>
        <button onclick="(function(){const p=window.Alpine&&window.Alpine.$data(document.body);if(p){delete p.loadedTabs['${tabName}'];p.switchMenu('${tabName}');}})()"
                class="px-4 py-2 rounded-xl bg-teal-600 text-white text-xs font-bold hover:bg-teal-700 transition-all">
            Coba Lagi
        </button>
    </div>`;
    container.style.opacity = '1';
});

// ─────────────────────────────────────────────────────────────────────────────
//  Alpine Component — adminPanel()
// ─────────────────────────────────────────────────────────────────────────────
document.addEventListener('alpine:init', () => {
    Alpine.data('adminPanel', () => ({

        // ── State ─────────────────────────────────────────────────────────
        activeMenu: new URLSearchParams(window.location.search).get('tab') || 'dashboard',
        darkMode:   document.documentElement.classList.contains('dark'),
        isLoading:  false,

        // Cache: { tabName: true } — tab yang sudah pernah dimuat, tidak di-fetch ulang
        loadedTabs: { dashboard: true },

        // Route map (same as global, tersedia di Alpine context)
        tabRoutes: window.__adminTabRoutes,

        // ── Labels ────────────────────────────────────────────────────────
        menuLabels: {
            dashboard:   'Dashboard',
            konten:      'Kelola Konten',
            inventori:   'Inventori Obat',
            keuangan:    'Keuangan',
            laporan:     'Laporan Pengunjung',
            doctors:     'Jadwal Dokter',
            reservasi:   'Antrean Pasien',
            rekam_medis: 'Rekam Medis',
            konsultasi:  'Konsultasi Live',
        },

        get menuLabel() {
            return this.menuLabels[this.activeMenu] || 'Dashboard';
        },

        // ── Notification / Polling state ──────────────────────────────────
        notification:        { show: false, message: '', type: 'success' },
        pendingCount:        0,
        lowStockCount:       0,
        showPopout:          false,
        recentNotifications: [],

        // ── Lifecycle ─────────────────────────────────────────────────────
        init() {
            applyTabTheme(this.activeMenu);

            // Session flash notification
            const initialMsg = document.getElementById('initial-success-message');
            if (initialMsg?.value) this.showNotify(initialMsg.value);

            // Jika URL punya tab bukan dashboard, auto-load
            if (this.activeMenu !== 'dashboard') {
                this.$nextTick(() => this._fetchTab(this.activeMenu));
            }

            // Polling notifikasi setiap 10 detik
            this.pollStats(true);
            setInterval(() => this.pollStats(), 10000);

            // Refresh tab reservasi saat ada antrean baru
            window.addEventListener('refresh-reservasi', () => {
                if (this.activeMenu === 'reservasi' && this.loadedTabs['reservasi']) {
                    this._refetchTab('reservasi');
                }
            });
        },

        // ── Tab Switching ─────────────────────────────────────────────────
        async switchMenu(menuName) {
            if (menuName === this.activeMenu && this.loadedTabs[menuName]) return;

            this.activeMenu = menuName;
            applyTabTheme(menuName);
            this._updateUrl(menuName);
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Cache hit → konten sudah ada di DOM, tampil langsung
            if (this.loadedTabs[menuName]) return;

            // Cache miss → fetch dari server
            await this._fetchTab(menuName);
        },

        // ── Internal: Fetch & Inject Partial via Fetch API ────────────────
        // (HTMX digunakan untuk pagination/forms setelah konten dimuat)
        async _fetchTab(menuName) {
            const container = document.getElementById('tab-' + menuName);
            if (!container) return;

            this.isLoading = true;
            container.innerHTML = buildSkeleton();
            container.style.opacity    = '0';
            container.style.transition = 'opacity 0.2s ease';

            try {
                let url = this.tabRoutes[menuName];
                
                // Teruskan query parameters dari URL browser ke partial request
                const currentParams = new URLSearchParams(window.location.search);
                if (currentParams.toString()) {
                    const partialUrl = new URL(url, window.location.origin);
                    currentParams.forEach((v, k) => {
                        // Teruskan semua query parameters (seperti search, filter, dll) kecuali 'tab'
                        if (k !== 'tab') partialUrl.searchParams.set(k, v);
                    });
                    url = partialUrl.pathname + partialUrl.search;
                }

                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'HX-Request':       'true', // agar backend tahu ini HTMX/AJAX request
                        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]')?.content || '',
                    }
                });

                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const html = await res.text();
                container.innerHTML = html;
                this.loadedTabs[menuName] = true;

                // Re-execute inline <script> tags (ApexCharts, dll.)
                container.querySelectorAll('script').forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr =>
                        newScript.setAttribute(attr.name, attr.value)
                    );
                    newScript.textContent = oldScript.textContent;
                    oldScript.replaceWith(newScript);
                });

                // Re-init Alpine components dalam konten baru
                if (window.Alpine) Alpine.initTree(container);

                // Enhance pagination links & forms dengan HTMX (menggantikan interceptor lama)
                enhanceTabContent(container, menuName);

                // Fade in
                requestAnimationFrame(() => { container.style.opacity = '1'; });

            } catch (err) {
                console.error('[AdminPanel] Gagal memuat tab:', menuName, err);
                container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-24 text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">Gagal memuat konten</p>
                        <p class="text-xs text-gray-400 mt-1">Periksa koneksi internet Anda</p>
                    </div>
                    <button onclick="(function(){const p=window.Alpine&&window.Alpine.$data(document.body);if(p){delete p.loadedTabs['${menuName}'];p.switchMenu('${menuName}');}})()"
                            class="px-4 py-2 rounded-xl bg-teal-600 text-white text-xs font-bold hover:bg-teal-700 transition-all">
                        Coba Lagi
                    </button>
                </div>`;
                container.style.opacity = '1';
            } finally {
                this.isLoading = false;
            }
        },

        // ── Internal: Force re-fetch (reset cache) ────────────────────────
        async _refetchTab(menuName) {
            delete this.loadedTabs[menuName];
            await this._fetchTab(menuName);
        },

        // ── Internal: Update URL bar ──────────────────────────────────────
        _updateUrl(menuName) {
            const newUrl = `${location.origin}${location.pathname}?tab=${menuName}`;
            window.history.pushState({ tab: menuName }, '', newUrl);
        },

        // ── Stats Polling ─────────────────────────────────────────────────
        async pollStats(isInitial = false) {
            try {
                const res  = await fetch('/admin/stats/polling');
                const data = await res.json();

                if (!isInitial && data.pendingReservasiCount > this.pendingCount) {
                    this.showPopout = true;
                    setTimeout(() => { this.showPopout = false; }, 8000);

                    this.recentNotifications.unshift({
                        title: 'Antrean Pasien Baru',
                        time:  new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB',
                        type:  'reservasi'
                    });
                    if (this.recentNotifications.length > 5) this.recentNotifications.pop();

                    window.dispatchEvent(new CustomEvent('refresh-reservasi'));
                }

                this.pendingCount  = data.pendingReservasiCount;
                this.lowStockCount = data.lowStockCount;
            } catch (e) {
                console.error('[AdminPanel] Stats poll error:', e);
            }
        },

        // ── Toast Notification ────────────────────────────────────────────
        showNotify(message, type = 'success') {
            this.notification.message = message;
            this.notification.type    = type;
            this.notification.show    = true;
            setTimeout(() => { this.notification.show = false; }, 4000);
        },

        // ── Dark Mode ─────────────────────────────────────────────────────
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('delimacare-dark', this.darkMode);
            this.darkMode
                ? document.documentElement.classList.add('dark')
                : document.documentElement.classList.remove('dark');
        },
    }));
});
