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
        $userRole = strtolower(Auth::user()->role->role_name ?? '');
        $roles = array_map('strtolower', $roles);

        // Jika role sesuai, izinkan lewat. Jika tidak, kembalikan ke halaman sebelumnya.
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}