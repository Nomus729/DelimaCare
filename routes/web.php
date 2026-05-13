<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\ConsultationController; // Tambahan buat Live Chat
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
        $doctors = \App\Models\Doctor::all();

        // Ambil rekam medis spesifik milik pasien
        $rekamMedis = \App\Models\RekamMedis::with('resepMedis.items.medicine')
            ->where('nama_pasien', Auth::user()->username)
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

    // Kelola Konten
    Route::resource('konten', \App\Http\Controllers\ArticleController::class)->except(['index', 'show']);

    // Inventori Obat & Dokter
    Route::resource('medicines', \App\Http\Controllers\Admin\MedicineController::class);
    Route::resource('doctors', \App\Http\Controllers\Admin\DoctorController::class);

    // Rekam Medis
    Route::resource('rekam-medis', \App\Http\Controllers\Admin\RekamMedisController::class)
        ->parameters(['rekam-medis' => 'rekamMedis']);

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

    // Polling Partial
    Route::get('/reservasi/partial', [\App\Http\Controllers\AdminController::class, 'getReservasiPartial'])->name('reservasi.partial');
    Route::get('/inventori/partial', [\App\Http\Controllers\AdminController::class, 'getInventoriPartial'])->name('inventori.partial');
    Route::get('/stats/polling', [\App\Http\Controllers\AdminController::class, 'getPollingStats'])->name('stats.polling');
});
