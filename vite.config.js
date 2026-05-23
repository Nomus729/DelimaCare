import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',     // Custom CSS admin panel
                'resources/js/app.js',
                'resources/js/admin-init.js',  // Pusher/Echo init (production-safe)
            ],
            refresh: true,
        }),
    ],
});
