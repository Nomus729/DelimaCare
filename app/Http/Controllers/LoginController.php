<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi user.
     *
     * Keamanan yang diterapkan:
     * 1. CSRF Protection (otomatis via middleware Laravel)
     * 2. Validasi input (email format, password min length)
     * 3. Rate Limiting (max 5 percobaan per menit per IP+email)
     * 4. Password hashing (via Auth::attempt, cek bcrypt otomatis)
     * 5. Session regeneration (mencegah session fixation)
     * 6. Sanitized error messages (tidak bocorkan info user)
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        // 2. Rate limiting — max 5 percobaan per menit
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            Log::warning('Percobaan login diblokir oleh Rate Limiter (Terlalu banyak percobaan).', [
                'email'               => $request->input('email'),
                'ip'                  => $request->ip(),
                'retry_after_seconds' => $seconds,
            ]);

            return back()
                ->withInput($request->only('email'))
                ->with('throttle', "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.");
        }

        // 3. Attempt authentication (password otomatis di-hash check oleh Laravel)
        if (Auth::attempt($credentials)) {
            // Reset rate limiter on success
            RateLimiter::clear($throttleKey);

            // 4. Session regeneration — mencegah session fixation attack
            $request->session()->regenerate();

            // 5. Redirect berdasarkan role user
            $user = Auth::user();

            if ($user->role === 'admin') {
                Log::info('Login berhasil sebagai Admin.', [
                    'user_id'  => $user->id,
                    'email'    => $user->email,
                    'role'     => $user->role,
                    'redirect' => '/admin/dashboard',
                ]);

                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'dokter') {
                Log::info('Login berhasil sebagai Dokter.', [
                    'user_id'  => $user->id,
                    'email'    => $user->email,
                    'role'     => $user->role,
                    'redirect' => '/admin/dashboard',
                ]);

                return redirect()->intended('/admin/dashboard'); // Bisa diganti ke dashboard dokter nanti
            } else {
                // Bersihkan URL intended jika mengarah ke admin panel agar pasien tidak terkena error izin
                if (session()->has('url.intended') && str_contains(session()->get('url.intended'), '/admin')) {
                    Log::notice('Membersihkan session url.intended admin untuk user role pasien.', [
                        'user_id'              => $user->id,
                        'email'                => $user->email,
                        'cleared_intended_url' => session()->get('url.intended'),
                    ]);

                    session()->forget('url.intended');
                }

                Log::info('Login berhasil sebagai Pasien.', [
                    'user_id'      => $user->id,
                    'email'        => $user->email,
                    'role'         => $user->role,
                    'intended_url' => session()->get('url.intended', '/portal'),
                ]);

                return redirect()->intended('/portal');
            }
        }

        // Increment rate limiter on failure
        RateLimiter::hit($throttleKey, 60); // 60 detik decay

        Log::warning('Percobaan login gagal: Email atau kata sandi salah.', [
            'email'    => $request->input('email'),
            'ip'       => $request->ip(),
            'attempts' => RateLimiter::attempts($throttleKey),
        ]);

        // 6. Pesan error generik — tidak bocorkan apakah email ada atau tidak
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau kata sandi yang Anda masukkan salah.');
    }

    /**
     * Logout user.
     *
     * Keamanan:
     * - Invalidate session
     * - Regenerate CSRF token
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
