<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlayerIsAuthenticated
{
public function handle(Request $request, Closure $next)
{
    // Kiểm tra kỹ xem Guard 'player' có đang hoạt động không
    if (!Auth::guard('player')->check()) {
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
    }

    return $next($request);
}
}