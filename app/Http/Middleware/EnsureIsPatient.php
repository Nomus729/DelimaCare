<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureIsPatient Middleware
 * 
 * Melindungi route Portal Pasien.
 * Memastikan user yang mengakses portal adalah 'pasien' yang sudah login.
 * Admin tidak bisa mengakses portal pasien.
 */
class EnsureIsPatient
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses Portal Pasien.');
        }

        $user = Auth::user();

        // Admin yang tidak sengaja buka portal pasien → arahkan ke dashboard admin
        if (in_array($user->role, ['admin', 'dokter'])) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
