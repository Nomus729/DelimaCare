document.addEventListener("alpine:init", () => {
    Alpine.data("layout", () => ({
        scrolled: false,
        init() {
            this.scrolled = window.pageYOffset > 20;
            window.addEventListener("scroll", () => {
                this.scrolled = window.pageYOffset > 20;
            });
        },
    }));
});
