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
        // 1. Kiểm tra kỹ xem Guard 'player' có đang đăng nhập không
        if (!Auth::guard('player')->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        // 2. Lấy thông tin player hiện tại sau khi đã xác định họ đã đăng nhập
        $player = Auth::guard('player')->user();

        // 3. Nếu tài khoản bị Banned, đá người dùng ra ngay lập tức
        if ($player->status === 'Banned') {
            // Thực hiện đăng xuất (Hủy session)
            Auth::guard('player')->logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Trả về trang login kèm thông báo
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa bởi Ban Quản Trị!');
        }

        return $next($request);
    }
}