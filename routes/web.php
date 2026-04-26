<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;

// ==========================================
// 1. HALAMAN DEPAN & PORTAL (Bisa diakses siapa saja)
// ==========================================
Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/portal', function () {
    return view('portal');
})->name('portal');

// --- Artikel / Konten ---
Route::get('/artikel', function () {
    return view('articles.index');
})->name('articles.index');

Route::get('/artikel/{slug}', function ($slug) {
    return view('articles.show', ['slug' => $slug]);
})->name('articles.show');


// ==========================================
// 2. AUTHENTICATION (Login, Register, Logout)
// ==========================================

// --- Login ---
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- Register ---
Route::get('/register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']);


// ==========================================
// 3. ADMIN PANEL (DIBYPASS SEMENTARA TANPA DATABASE)
// ==========================================
// Middleware 'auth' sudah saya hapus di bawah ini biar nggak ngecek database lagi
Route::group(['prefix' => 'admin'], function () {

    // Halaman Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

});
