<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LabseAdminRoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = Auth::user()?->role ?? session('role');
        if ($role !== 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('pesan', 'Akses ditolak! Hanya admin yang dapat mengakses halaman ini.')
                ->with('tipe_pesan', 'error');
        }

        return $next($request);
    }
}
