<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PlayerAuthController extends Controller 
{

    // Hiển thị form đăng ký
    public function showRegister() 
    { 
        return view('Players.auth.register'); 
    }

    // Xử lý đăng ký
   public function register(Request $request) 
{
    $request->validate([
        'username' => 'required|string|max:100|unique:players',
        'email'    => 'required|string|email|max:150|unique:players',
        'fullname' => 'nullable|string|max:150',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $player = Player::create([
        'username' => $request->username,
        'email'    => $request->email,
        'fullname' => $request->fullname, // Lưu thêm trường này
        'password' => Hash::make($request->password),
        'status'   => 'Active', // Mặc định như DB của bạn
    ]);

    Auth::guard('player')->login($player);
    return redirect()->route('login');
}

    // Hiển thị form đăng nhập
    public function showLogin() 
    { 
        return view('Players.auth.login'); 
    }

    // Xử lý đăng nhập
public function login(Request $request) 
{
    $credentials = $request->only('email', 'password');

    // Sử dụng guard 'player' đã khai báo trong config/auth.php
    if (Auth::guard('player')->attempt($credentials)) {
        
        // 1. Lấy thông tin người chơi vừa đăng nhập thành công
        $player = Auth::guard('player')->user();

        // 2. Kiểm tra nếu trạng thái là Banned thì chặn đứng lại
        if ($player->status === 'Banned') {
            // Thực hiện đăng xuất ngầm ngay lập tức để hủy phiên làm việc vừa tạo
            Auth::guard('player')->logout();
            
            // Xóa sạch session và làm mới token bảo mật
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Trả về trang đăng nhập kèm thông báo lỗi chi tiết
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa bởi Ban Quản Trị! Để yêu cầu hỗ trợ hoặc mở khóa tài khoản, vui lòng liên hệ với chúng tôi qua Email: support@steam.com.');
        }

        // 3. Nếu tài khoản hoạt động bình thường (Active), tạo lại session và cho vào trong
        $request->session()->regenerate();
        
        // Chuyển hướng về trang chủ
        return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
    }

    return back()->withErrors([
        'email' => 'Thông tin đăng nhập không chính xác.',
    ]);
}

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::guard('player')->logout();
        
        // Chỉ regenerate token, không invalidate toàn bộ session
        // để phiên đăng nhập admin trên tab khác không bị ảnh hưởng
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }
}