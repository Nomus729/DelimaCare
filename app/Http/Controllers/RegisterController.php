<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman form register.
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Memproses data pendaftaran.
     *
     * Keamanan yang diterapkan:
     * 1. CSRF Protection (otomatis via middleware Laravel)
     * 2. Validasi input ketat (username unik, email unik, password strength)
     * 3. Password hashing (bcrypt via Hash::make)
     * 4. Password confirmation (harus cocok)
     * 5. Role default 'pasien' — mencegah privilege escalation
     * 6. Session regeneration setelah login otomatis
     * 7. XSS Prevention — input di-sanitize oleh Blade secara default
     */
    public function store(Request $request)
    {
        // 1. Validasi input ketat
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers(),
            ],
        ], [
            'username.required'  => 'Username wajib diisi.',
            'username.min'       => 'Username minimal 3 karakter.',
            'username.max'       => 'Username maksimal 50 karakter.',
            'username.unique'    => 'Username sudah digunakan.',
            'username.regex'     => 'Username hanya boleh huruf, angka, dan underscore.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
        ]);

        // 2. Buat user baru dengan password di-hash
        $user = User::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'pasien', // Default role — tidak bisa dimanipulasi dari form
        ]);

        // 3. Login otomatis setelah registrasi
        Auth::login($user);

        // 4. Regenerate session — mencegah session fixation
        $request->session()->regenerate();

        // 5. Redirect ke portal pasien
        return redirect()->route('portal')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di DelimaCare.');
    }
}
