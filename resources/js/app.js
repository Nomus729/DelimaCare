// resources/js/app.js
import "./bootstrap";

// Mendaftarkan komponen Alpine.js untuk mengatur state header
document.addEventListener("alpine:init", () => {
    Alpine.data("delimaCareLayout", () => ({
        scrolled: false,

        init() {
            // Cek posisi scroll saat pertama kali dimuat
            this.scrolled = window.pageYOffset > 20;

            // Pantau pergerakan scroll
            window.addEventListener("scroll", () => {
                this.scrolled = window.pageYOffset > 20;
            });
        },
    }));
});
