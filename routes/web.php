<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\ConsultationController; // Tambahan buat Live Chat
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ForgotPasswordController; // 🔥 Tambahan buat Lupa Password
use Illuminate\Support\Facades\Auth;

// ==========================================
// 1. HALAMAN PUBLIK (Semua orang bisa akses)
// ==========================================
Route::get('/', function () {
    $articles = \App\Models\Article::latest()->take(3)->get();
    return view('landing', compact('articles'));
})->name('home');

// --- Artikel / Konten (publik) ---
Route::get('/artikel', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\Article::query();

    // Filter by Category
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // Search by Keyword
    if ($request->filled('search')) {
        $searchTerm = '%' . $request->search . '%';
        $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', $searchTerm)
              ->orWhere('content', 'like', $searchTerm);
        });
    }

    $articles = $query->latest()->paginate(9)->withQueryString();

    $currentCategory = $request->query('category', '');
    $searchQuery = $request->query('search', '');

    return view('articles.index', compact('articles', 'currentCategory', 'searchQuery'));
})->name('articles.index');

Route::get('/artikel/{slug}', function ($slug) {
    $article = \App\Models\Article::where('slug', $slug)->firstOrFail();
    $related = \App\Models\Article::where('id', '!=', $article->id)->latest()->take(2)->get();
    return view('articles.show', compact('article', 'related'));
})->name('articles.show');


// ==========================================
// 2. AUTHENTICATION (hanya untuk tamu/guest)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'index'])->name('login');
    Route::post('/login',   [LoginController::class, 'authenticate']);
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

   // 🔥 FITUR BARU: Lupa Password & Reset Password (Sistem OTP) 🔥
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    // Hapus /{token} dari sini, jadi tinggal /reset-password
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Logout (hanya user yang sudah login)
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


// ==========================================
// 3. PORTAL PASIEN (Hanya pasien terdaftar)
// ==========================================
Route::middleware(['auth', 'patient'])->group(function () {
    Route::get('/portal', function () {
        // Hanya jadwal milik pasien yang lagi login
        $jadwalPasien = \App\Models\Reservasi::where('user_id', Auth::id())->latest()->get();
        $doctors = \App\Models\Doctor::whereNotIn('status', ['Libur', 'Istirahat'])->get();

        // Ambil rekam medis via user_id (reliable FK) dengan fallback ke nama_pasien
        $rekamMedis = \App\Models\RekamMedis::with('resepMedis.items.medicine')
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('nama_pasien', Auth::user()->username);
            })
            ->latest()
            ->get();

        return view('portal', compact('jadwalPasien', 'doctors', 'rekamMedis'));
    })->name('portal');

    // Rute Profil Pasien
    Route::put('/portal/profil/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('portal.profil.update');

    // Rute Reservasi Pasien
    Route::post('/portal/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/portal/jadwal', [ReservasiController::class, 'indexPasien'])->name('portal.jadwal');
    Route::delete('/portal/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');

    // 🔥 FITUR BARU: Live Chat Konsultasi Pasien 🔥
    Route::get('/portal/chat/load', [ConsultationController::class, 'loadMessages'])->name('chat.load');
    Route::post('/portal/chat/send', [ConsultationController::class, 'sendMessage'])->name('chat.send');
});


// ==========================================
// 4. ADMIN PANEL (Hanya admin & dokter)
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // ── Partial Endpoints (AJAX Lazy Loading) ──────────────────────────────
    // PENTING: Harus di atas Route::resource agar tidak di-capture sebagai {id}
    Route::get('/dashboard/partial',   [AdminController::class, 'getDashboardPartial'])->name('dashboard.partial');
    Route::get('/konten/partial',      [AdminController::class, 'getKontenPartial'])->name('konten.partial');
    Route::get('/inventori/partial',   [AdminController::class, 'getInventoriPartial'])->name('inventori.partial');
    Route::get('/keuangan/partial',    [AdminController::class, 'getKeuanganPartial'])->name('keuangan.partial');
    Route::get('/laporan/partial',     [AdminController::class, 'getLaporanPartial'])->name('laporan.partial');
    Route::get('/doctors/partial',     [AdminController::class, 'getDoctorsPartial'])->name('doctors.partial');
    Route::get('/reservasi/partial',   [AdminController::class, 'getReservasiPartial'])->name('reservasi.partial');
    Route::get('/rekam-medis/partial', [AdminController::class, 'getRekamMedisPartial'])->name('rekam-medis.partial');
    Route::get('/konsultasi/partial',  [AdminController::class, 'getKonsultasiPartial'])->name('konsultasi.partial');
    Route::get('/stats/polling',       [AdminController::class, 'getPollingStats'])->name('stats.polling');

    // Kelola Konten
    Route::resource('konten', \App\Http\Controllers\ArticleController::class)->except(['index', 'show']);

    // Inventori Obat & Dokter
    Route::resource('medicines', \App\Http\Controllers\Admin\MedicineController::class);
    Route::resource('doctors', \App\Http\Controllers\Admin\DoctorController::class)->except(['show']);

    // Rekam Medis
    Route::resource('rekam-medis', \App\Http\Controllers\Admin\RekamMedisController::class)
        ->parameters(['rekam-medis' => 'rekamMedis'])
        ->except(['show']);

    // Resep Medis
    Route::post('/resep-medis', [\App\Http\Controllers\Admin\ResepMedisController::class, 'store'])->name('resep-medis.store');
    Route::patch('/resep-medis/{resepMedis}/status', [\App\Http\Controllers\Admin\ResepMedisController::class, 'updateStatus'])->name('resep-medis.status');
    Route::delete('/resep-medis/{resepMedis}', [\App\Http\Controllers\Admin\ResepMedisController::class, 'destroy'])->name('resep-medis.destroy');
    Route::get('/api/medicines/search', [\App\Http\Controllers\Admin\ResepMedisController::class, 'searchMedicine'])->name('api.medicines.search');

    // Fitur Reservasi Admin
    Route::post('/reservasi/store', [ReservasiController::class, 'storeAdmin'])->name('reservasi.store_admin');
    Route::patch('/reservasi/{id}/konfirmasi', [ReservasiController::class, 'konfirmasiAdmin'])->name('reservasi.konfirmasi');
    Route::patch('/reservasi/{id}/status', [ReservasiController::class, 'updateStatus'])->name('reservasi.status');
    Route::delete('/reservasi/{id}/batal', [ReservasiController::class, 'batalAdmin'])->name('reservasi.batal');

    // 🔥 FITUR BARU: Live Chat Konsultasi Admin 🔥
    Route::get('/chat/users', [ConsultationController::class, 'getChatUsers']);
    Route::get('/chat/{userId}', [ConsultationController::class, 'getAdminMessages']);
    Route::post('/chat/send', [ConsultationController::class, 'sendAdminMessage']);

    // Keuangan (Pengeluaran)
    Route::post('/pengeluaran', [\App\Http\Controllers\Admin\PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::delete('/pengeluaran/{id}', [\App\Http\Controllers\Admin\PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

    // 🔥 FITUR BARU: Laporan & Analitik 🔥
    Route::get('/report/stats', [ReportController::class, 'getStats'])->name('report.stats');
});
