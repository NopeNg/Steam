<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlayerIsAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem Guard 'player' có đang đăng nhập không
        if (!Auth::guard('player')->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        return $next($request);
    }
}