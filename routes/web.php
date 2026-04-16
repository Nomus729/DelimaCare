<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// Form Login
Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');

// Proses Login
Route::post('login', [LoginController::class, 'authenticate']);

// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard (Halaman setelah login)
Route::get('dashboard', function () {
    return view('dashboard');
})->middleware('auth');
Route::get('/', function () {
    return view('welcome');
});

// Route Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route Portal Pasien
Route::get('/portal', function () {
    return view('portal');
})->name('portal');


