document.addEventListener("alpine:init", () => {
    Alpine.data("layout", () => ({
        scrolled: false,
        darkMode: false,
        init() {
            this.scrolled = window.pageYOffset > 20;
            window.addEventListener("scroll", () => {
                this.scrolled = window.pageYOffset > 20;
            });

            // Dark mode: check localStorage, then system preference
            const stored = localStorage.getItem("delimacare-dark");
            if (stored !== null) {
                this.darkMode = stored === "true";
            } else {
                this.darkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;
            }
            this._applyDark(false);
        },
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem("delimacare-dark", this.darkMode);
            this._applyDark(true);
        },
        _applyDark(animate) {
            const html = document.documentElement;
            if (animate) {
                html.classList.add("dark-transition");
                setTimeout(() => html.classList.remove("dark-transition"), 350);
            }
            if (this.darkMode) {
                html.classList.add("dark");
            } else {
                html.classList.remove("dark");
            }
        }
    }));
});

/* ========================================
   Scroll-Triggered Reveal (Intersection Observer)
   ======================================== */
document.addEventListener("DOMContentLoaded", () => {
    const revealElements = document.querySelectorAll(
        ".scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale"
    );

    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-visible");
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: "0px 0px -40px 0px" }
        );

        revealElements.forEach((el) => observer.observe(el));
    } else {
        // Fallback: make all visible immediately
        revealElements.forEach((el) => el.classList.add("is-visible"));
    }

    /* ========================================
       Counter Animation
       ======================================== */
    const counterElements = document.querySelectorAll("[data-counter]");

    function animateCounter(el) {
        const target = parseInt(el.getAttribute("data-counter"), 10);
        const suffix = el.getAttribute("data-suffix") || "";
        const duration = 2000;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(eased * target);
            el.textContent = current.toLocaleString("id-ID") + suffix;
            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        requestAnimationFrame(update);
    }

    if (counterElements.length > 0 && "IntersectionObserver" in window) {
        const counterObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        counterObserver.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.5 }
        );
        counterElements.forEach((el) => counterObserver.observe(el));
    }

    /* ========================================
       Smooth Parallax on Hero
       ======================================== */
    const heroContent = document.querySelector(".hero-content");
    if (heroContent) {
        window.addEventListener("scroll", () => {
            const scrollY = window.pageYOffset;
            if (scrollY < 800) {
                heroContent.style.transform = `translateY(${scrollY * 0.15}px)`;
                heroContent.style.opacity = 1 - scrollY / 900;
            }
        }, { passive: true });
    }

    /* ========================================
       Navbar active link highlight on scroll
       ======================================== */
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-link");

    if (sections.length > 0 && navLinks.length > 0) {
        window.addEventListener("scroll", () => {
            let current = "";
            sections.forEach((section) => {
                const sectionTop = section.offsetTop - 120;
                if (window.pageYOffset >= sectionTop) {
                    current = section.getAttribute("id");
                }
            });
            navLinks.forEach((link) => {
                link.classList.remove("active-link");
                if (link.getAttribute("href") === "#" + current) {
                    link.classList.add("active-link");
                }
            });
        }, { passive: true });
    }
});
