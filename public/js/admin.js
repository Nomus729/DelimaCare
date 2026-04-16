document.addEventListener("alpine:init", () => {
    Alpine.data("adminPanel", () => ({
        // Set tab default saat halaman dimuat
        activeMenu: "dashboard",

        switchMenu(menuName) {
            this.activeMenu = menuName;
            window.scrollTo({ top: 0, behavior: "smooth" });
        },
    }));
});
