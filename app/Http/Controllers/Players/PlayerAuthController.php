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