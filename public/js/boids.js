/**
 * DelimaCare — Elegant Health Bubbles (Refined Intensity)
 * Minimalist, calming background particles for a healthcare theme.
 * Optimized for mobile responsiveness and smooth performance.
 */

class Bubble {
    constructor(width, height) {
        this.reset(width, height, true);
    }

    reset(width, height, initial = false) {
        this.width = width;
        this.height = height;
        
        const isMobile = width < 768;
        
        this.radius = Math.random() * (isMobile ? 5 : 7) + 1.5;
        this.x = Math.random() * width;
        this.y = initial ? Math.random() * height : height + this.radius * 2;
        
        this.speed = Math.random() * 0.2 + 0.1;
        this.wiggle = Math.random() * 0.01;
        this.wiggleOffset = Math.random() * Math.PI * 2;
        
        // Increased opacity slightly for better visibility
        this.opacity = Math.random() * 0.18 + 0.07;
        this.vy = -this.speed;
    }

    update(mouse) {
        let drift = Math.sin(Date.now() * this.wiggle + this.wiggleOffset) * 0.2;
        
        let forceX = 0;
        let forceY = 0;

        if (this.width > 768 && mouse.x > -1000) {
            let dx = this.x - mouse.x;
            let dy = this.y - mouse.y;
            let distanceSq = dx * dx + dy * dy;
            if (distanceSq < 40000) {
                let distance = Math.sqrt(distanceSq);
                let strength = (200 - distance) / 200;
                forceX = (dx / distance) * strength * 0.6;
                forceY = (dy / distance) * strength * 0.6;
            }
        }

        this.x += drift + forceX;
        this.y += this.vy + forceY;

        if (this.y < -this.radius * 2) {
            this.reset(this.width, this.height);
        }
    }

    draw(ctx, isDark) {
        // Use more visible white/teal
        const baseColor = isDark ? '167, 243, 208' : '255, 255, 255';
        
        if (this.width < 768) {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${baseColor}, ${this.opacity})`;
            ctx.fill();
            return;
        }

        ctx.save();
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        
        let gradient = ctx.createRadialGradient(
            this.x, this.y, 0,
            this.x, this.y, this.radius
        );
        gradient.addColorStop(0, `rgba(${baseColor}, ${this.opacity + 0.1})`);
        gradient.addColorStop(1, `rgba(${baseColor}, 0)`);
        
        ctx.fillStyle = gradient;
        ctx.fill();
        
        if (this.radius > 3) {
            ctx.beginPath();
            ctx.arc(this.x, this.y, 1, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${baseColor}, ${this.opacity + 0.2})`;
            ctx.fill();
        }
        
        ctx.restore();
    }
}

class BubbleSystem {
    constructor(canvas) {
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.bubbles = [];
        this.mouse = { x: -2000, y: -2000 };
        this.isDark = document.documentElement.classList.contains('dark');
        
        this.init();
        this.animate();

        window.addEventListener('resize', () => this.resize(), { passive: true });
        if (window.innerWidth > 768) {
            window.addEventListener('mousemove', (e) => {
                const rect = this.canvas.getBoundingClientRect();
                this.mouse.x = e.clientX - rect.left;
                this.mouse.y = e.clientY - rect.top;
            }, { passive: true });
        }
        
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    this.isDark = document.documentElement.classList.contains('dark');
                }
            });
        });
        observer.observe(document.documentElement, { attributes: true });
    }

    init() {
        this.resize();
        const isMobile = this.canvas.width < 768;
        // Increased density for better visibility (lower number = more bubbles)
        let density = isMobile ? 20000 : 8000;
        let bubbleCount = Math.floor((this.canvas.width * this.canvas.height) / density);
        bubbleCount = Math.min(Math.max(bubbleCount, isMobile ? 25 : 40), isMobile ? 50 : 150);
        
        this.bubbles = [];
        for (let i = 0; i < bubbleCount; i++) {
            this.bubbles.push(new Bubble(this.canvas.width, this.canvas.height));
        }
    }

    resize() {
        const parent = this.canvas.parentElement;
        if (!parent) return;
        
        this.canvas.width = parent.clientWidth;
        this.canvas.height = parent.clientHeight;
        
        this.bubbles.forEach(b => {
            b.width = this.canvas.width;
            b.height = this.canvas.height;
        });
    }

    animate() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        for (let i = 0; i < this.bubbles.length; i++) {
            this.bubbles[i].update(this.mouse);
            this.bubbles[i].draw(this.ctx, this.isDark);
        }
        
        requestAnimationFrame(() => this.animate());
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('boids-canvas');
    if (canvas) {
        setTimeout(() => {
            new BubbleSystem(canvas);
        }, 100);
    }
});
