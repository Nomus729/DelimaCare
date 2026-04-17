<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // BYPASS TOTAL:
        // Tanpa validasi email/password, tanpa cek database.
        // Asal tombol login diklik, langsung lempar ke Dashboard Admin!
        return redirect('/admin/dashboard');
    }

    public function logout(Request $request)
    {
        // Bypass logout: Langsung kembalikan ke halaman depan
        return redirect('/');
    }
}
