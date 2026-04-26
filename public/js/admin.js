// public/js/admin.js

document.addEventListener("alpine:init", () => {
    Alpine.data("adminPanel", () => ({
        activeMenu: "dashboard",

        // Dark mode state
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

        switchMenu(menuName) {
            this.activeMenu = menuName;
            window.scrollTo({ top: 0, behavior: 'smooth' });
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
