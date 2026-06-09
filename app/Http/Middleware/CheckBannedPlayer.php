<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedPlayer
{
    public function handle(Request $request, Closure $next): Response
    {
        // Lấy thông tin player hiện tại (chắc chắn đã đăng nhập mới tới bước này)
        $player = Auth::guard('player')->user();

        // Nếu tài khoản bị Banned, xử lý đăng xuất và hủy session ngay lập tức
        if ($player && $player->status === 'Banned') {
            Auth::guard('player')->logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa bởi Ban Quản Trị!');
        }

        return $next($request);
    }
}