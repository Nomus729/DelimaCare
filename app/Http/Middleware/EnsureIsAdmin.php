<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureIsAdmin Middleware
 * 
 * Melindungi semua route Admin Panel.
 * Memastikan user:
 *   1. Sudah login (authenticated)
 *   2. Memiliki role 'admin' atau 'dokter'
 * 
 * Jika tidak, user akan di-redirect ke halaman yang sesuai
 * dengan pesan error yang jelas (tanpa bocorkan detail sistem).
 */
class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login sama sekali
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        // Sudah login tapi bukan admin
        if (!in_array($user->role, ['admin', 'dokter'])) {
            // Pasien diarahkan ke portal mereka sendiri
            return redirect()->route('portal')
                ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman admin.');
        }

        return $next($request);
    }
}
