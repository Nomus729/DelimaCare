<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;

// ==========================================
// 1. HALAMAN DEPAN & PORTAL (Bisa diakses siapa saja)
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/portal', function () {
    return view('portal');
})->name('portal');


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
// 3. ADMIN PANEL (Hanya bisa diakses kalau sudah Login)
// ==========================================
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {

    // Halaman Dashboard Admin (Akan memanggil resources/views/admin/index.blade.php)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

});
