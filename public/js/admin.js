// public/js/admin.js

document.addEventListener("alpine:init", () => {
    Alpine.data("adminPanel", () => ({
        activeMenu: new URLSearchParams(window.location.search).get('tab') || "dashboard",
        darkMode: document.documentElement.classList.contains('dark'),

        // Menu labels for header title
        menuLabels: {
            dashboard: 'Dashboard',
            konten: 'Kelola Konten',
            inventori: 'Inventori Obat',
            keuangan: 'Keuangan',
            laporan: 'Laporan Pengunjung',
            reservasi: 'Reservasi',
            rekam_medis: 'Rekam Medis',
        },

        get menuLabel() {
            return this.menuLabels[this.activeMenu] || 'Dashboard';
        },

        // Notification state
        notification: {
            show: false,
            message: '',
            type: 'success'
        },

        init() {
            // Check for initial notification from PHP session
            const initialMsg = document.getElementById('initial-success-message');
            if (initialMsg && initialMsg.value) {
                this.showNotify(initialMsg.value);
            }
        },

        showNotify(message, type = 'success') {
            this.notification.message = message;
            this.notification.type = type;
            this.notification.show = true;

            setTimeout(() => {
                this.notification.show = false;
            }, 4000);
        },

        switchMenu(menuName) {
            this.activeMenu = menuName;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Add tab to URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', menuName);
            window.history.pushState({}, '', url);
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
