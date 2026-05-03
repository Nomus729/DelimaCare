// public/js/admin.js

// ── Tab color themes (acc1 = primary, acc2 = secondary) ──────────────────────
const TAB_THEMES = {
    dashboard: { acc1: '#0D9488', acc2: '#06B6D4', blob1: 'rgba(13,148,136,0.35)', blob2: 'rgba(6,182,212,0.25)' },
    konten: { acc1: '#7C3AED', acc2: '#A78BFA', blob1: 'rgba(124,58,237,0.28)', blob2: 'rgba(167,139,250,0.18)' },
    inventori: { acc1: '#0369A1', acc2: '#38BDF8', blob1: 'rgba(3,105,161,0.28)', blob2: 'rgba(56,189,248,0.20)' },
    keuangan: { acc1: '#059669', acc2: '#34D399', blob1: 'rgba(5,150,105,0.30)', blob2: 'rgba(52,211,153,0.20)' },
    laporan: { acc1: '#D97706', acc2: '#FCD34D', blob1: 'rgba(217,119,6,0.25)', blob2: 'rgba(252,211,77,0.18)' },
    doctors: { acc1: '#0891B2', acc2: '#67E8F9', blob1: 'rgba(8,145,178,0.28)', blob2: 'rgba(103,232,249,0.18)' },
    reservasi: { acc1: '#0D9488', acc2: '#34D399', blob1: 'rgba(13,148,136,0.30)', blob2: 'rgba(52,211,153,0.18)' },
    rekam_medis: { acc1: '#BE185D', acc2: '#F472B6', blob1: 'rgba(190,24,93,0.22)', blob2: 'rgba(244,114,182,0.15)' },
};

function applyTabTheme(tab) {
    const t = TAB_THEMES[tab] || TAB_THEMES.dashboard;
    const root = document.documentElement;
    root.style.setProperty('--acc1', t.acc1);
    root.style.setProperty('--acc2', t.acc2);
    root.style.setProperty('--blob1', t.blob1);
    root.style.setProperty('--blob2', t.blob2);
}

document.addEventListener("alpine:init", () => {
    Alpine.data("adminPanel", () => ({
        activeMenu: new URLSearchParams(window.location.search).get('tab') || "dashboard",
        darkMode: document.documentElement.classList.contains('dark'),

        menuLabels: {
            dashboard: 'Dashboard',
            konten: 'Kelola Konten',
            inventori: 'Inventori Obat',
            keuangan: 'Keuangan',
            laporan: 'Laporan Pengunjung',
            doctors: 'Jadwal Dokter',
            reservasi: 'Antrean Pasien',
            rekam_medis: 'Rekam Medis',
        },

        get menuLabel() {
            return this.menuLabels[this.activeMenu] || 'Dashboard';
        },

        notification: { show: false, message: '', type: 'success' },
        pendingCount: 0,
        lowStockCount: 0,
        showPopout: false,
        recentNotifications: [],

        init() {
            // Apply initial tab theme
            applyTabTheme(this.activeMenu);

            // Show session notification
            const initialMsg = document.getElementById('initial-success-message');
            if (initialMsg && initialMsg.value) {
                this.showNotify(initialMsg.value);
            }

            // Global stats polling every 10s
            this.pollStats(true);
            setInterval(() => this.pollStats(), 10000);
        },

        async pollStats(isInitial = false) {
            try {
                const res = await fetch('/admin/stats/polling');
                const data = await res.json();

                // If pending count increases
                if (!isInitial && data.pendingReservasiCount > this.pendingCount) {
                    // Trigger popout near bell
                    this.showPopout = true;
                    setTimeout(() => { this.showPopout = false; }, 8000);

                    // Add to recent list
                    this.recentNotifications.unshift({
                        title: 'Antrean Pasien Baru',
                        time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB',
                        type: 'reservasi'
                    });

                    // Keep only last 5
                    if (this.recentNotifications.length > 5) this.recentNotifications.pop();

                    // Tell the reservation tab to refresh immediately
                    window.dispatchEvent(new CustomEvent('refresh-reservasi'));
                }

                this.pendingCount = data.pendingReservasiCount;
                this.lowStockCount = data.lowStockCount;
            } catch (e) { console.error('Stats poll error:', e); }
        },

        showNotify(message, type = 'success') {
            this.notification.message = message;
            this.notification.type = type;
            this.notification.show = true;
            setTimeout(() => { this.notification.show = false; }, 4000);
        },

        switchMenu(menuName) {
            this.activeMenu = menuName;
            applyTabTheme(menuName);
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Create a clean URL with ONLY the tab parameter
            const newUrl = window.location.origin + window.location.pathname + '?tab=' + menuName;
            window.history.pushState({}, '', newUrl);
        },

        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('delimacare-dark', this.darkMode);
            this.darkMode
                ? document.documentElement.classList.add('dark')
                : document.documentElement.classList.remove('dark');
        },
    }));
});
