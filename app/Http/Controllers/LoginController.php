<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan ini tetap ada

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // 1. Validasi input form
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Kredensial "Dummy" (Hardcoded)
        $dummyEmail = 'admin@delimacare.id';
        $dummyPassword = 'password123';

        // 3. Cek apakah input cocok dengan data dummy kita
        if ($request->email === $dummyEmail && $request->password === $dummyPassword) {

            // 4. "Memaksa" Laravel untuk menganggap user ini login
            // Karena kita tidak punya database, kita buat object User kosong secara instan di memori (tidak disimpan ke DB)
            $dummyUser = new User();
            $dummyUser->id = 1;
            $dummyUser->name = 'Admin Dummy';
            $dummyUser->email = $dummyEmail;

            Auth::login($dummyUser); // Login-kan user dummy tersebut

            $request->session()->regenerate(); // Regenerate session biar aman

            // 5. Arahkan ke Dashboard
            return redirect()->intended('/admin/dashboard');
        }

        // Jika salah email/password
        return back()->withErrors([
            'email' => 'Email atau password tidak cocok dengan data dummy.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
