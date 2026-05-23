/**
 * resources/js/admin-init.js
 *
 * Inisialisasi Laravel Echo / Pusher untuk Admin Panel DelimaCare.
 *
 * Cara kerja (aman untuk production):
 *  - Pusher key & cluster dibaca dari data-attribute <body>, bukan dari env()
 *  - data-attribute diisi oleh Blade menggunakan config(), bukan env()
 *  - Sehingga php artisan config:cache tidak merusak nilainya
 *  - Username admin TIDAK di-expose ke window global; dibaca sekali saat init
 *
 * Data attributes yang dibutuhkan di <body>:
 *   data-pusher-key       = {{ config('broadcasting.connections.pusher.key') }}
 *   data-pusher-cluster   = {{ config('broadcasting.connections.pusher.options.host') }}
 *   data-admin-user       = {{ Auth::user()->username }}  (hanya jika @auth)
 */

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;

    // Baca konfigurasi dari data-attributes (aman, tidak ada window global)
    const pusherKey     = body.dataset.pusherKey     || '';
    const pusherCluster = body.dataset.pusherCluster || 'ap1';
    const adminUser     = body.dataset.adminUser     || null;

    // Guard: jangan init jika Pusher library belum dimuat atau key tidak ada
    if (typeof Pusher === 'undefined' || !pusherKey) {
        console.warn('[DelimaCare] Pusher tidak tersedia atau PUSHER_APP_KEY kosong.');
        return;
    }

    // Inisialisasi Laravel Echo
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key:         pusherKey,
        cluster:     pusherCluster,
        forceTLS:    true,
    });

    // Expose admin user hanya ke scope ini (tidak ke window global)
    // Gunakan adminUser lokal ini jika perlu subscribe ke private channel
    if (adminUser && window.Echo) {
        // Contoh: subscribe ke private channel admin (uncomment jika diperlukan)
        // window.Echo.private(`admin.${adminUser}`)
        //     .listen('NewReservation', (e) => {
        //         window.dispatchEvent(new CustomEvent('refresh-reservasi', { detail: e }));
        //     });
    }
});
