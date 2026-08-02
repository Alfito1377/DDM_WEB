<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/');
        }

        // Ambil data role_name dari relasi user (pastikan relasi 'role' ada di model User)
        $userRole = Auth::user()->role->role_name ?? '';

        // Jika role sesuai dengan salah satu argumen, izinkan lewat
        $roles = array_map('strtolower', $roles);
        if (in_array(strtolower($userRole), $roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}