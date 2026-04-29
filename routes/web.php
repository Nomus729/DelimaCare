<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;

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
    
    // Get current parameters for the view
    $currentCategory = $request->query('category', '');
    $searchQuery = $request->query('search', '');

    return view('articles.index', compact('articles', 'currentCategory', 'searchQuery'));
})->name('articles.index');

Route::get('/artikel/{slug}', function ($slug) {
    $article = \App\Models\Article::where('slug', $slug)->firstOrFail();
    
    // Ambil artikel terkait
    $related = \App\Models\Article::where('id', '!=', $article->id)
        ->latest()
        ->take(2)
        ->get();
        
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
Route::get('/portal', function () {
    return view('portal');
})->name('portal')->middleware('patient');


// ==========================================
// 4. ADMIN PANEL (Hanya admin & dokter)
//    Semua route di sini wajib melalui middleware 'admin'
// ==========================================
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Kelola Konten
    Route::resource('konten', \App\Http\Controllers\ArticleController::class)->except(['index', 'show']);

    // Inventori Obat
    Route::resource('medicines', \App\Http\Controllers\Admin\MedicineController::class);

    // Rekam Medis
    Route::resource('rekam-medis', \App\Http\Controllers\Admin\RekamMedisController::class)
        ->except(['index', 'create', 'show', 'edit'])
        ->parameters(['rekam-medis' => 'rekamMedis']);
});
