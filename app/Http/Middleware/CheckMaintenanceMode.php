<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isMaintenance = Setting::get('maintenance_mode', '0');

        if ($isMaintenance === '1') {
            // Allow access to dashboard, login, and api routes (if needed)
            if ($request->is('dashboard*') || $request->is('login') || $request->is('logout') || $request->is('api*')) {
                return $next($request);
            }

            // Allow authenticated users (admins) to browse the site
            if (Auth::check()) {
                return $next($request);
            }

            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
