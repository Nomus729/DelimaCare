<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AutoSync
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only trigger sync if authenticated and accessing admin/dashboard routes
        if ($request->is('admin*') || $request->is('dashboard*')) {
            $lockKey = 'auto_sync_middleware_lock';
            
            // Check if we already synced in the last 60 seconds to avoid connection limit issues
            if (!Cache::has($lockKey)) {
                Cache::put($lockKey, true, 60);
                
                try {
                    // Run sync in the background to avoid slowing down the current request
                    Artisan::queue('sync:remote-to-local');
                } catch (\Exception $e) {
                    // Silently fail if sync fails to avoid breaking the UI
                }
            }
        }

        return $next($request);
    }
}
