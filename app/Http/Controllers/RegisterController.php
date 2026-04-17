<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    // Menampilkan halaman form register
    public function index()
    {
        return view('auth.register');
    }

    // Memproses data pendaftaran (BYPASS TOTAL)
    public function store(Request $request)
    {
        // Tanpa validasi apapun, tanpa simpan ke database.
        // Asal tombol daftar diklik, langsung terbang ke Dashboard Admin!
        return redirect('/admin/dashboard');
    }
}
