<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LabseAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() && !session('login')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
