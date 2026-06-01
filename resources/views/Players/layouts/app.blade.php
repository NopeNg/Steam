<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Steam Key Marketplace')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Cấu hình màu sắc nguyên bản của Steam */
        body {
            background-color: #1b2838; /* Màu xanh đen nền của Steam */
            color: #c7d5e0; /* Màu chữ xám xanh không bị lóa mắt */
            font-family: "Motiva Sans", Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between">

    <header class="bg-[#171a21] text-[#c5c3c0] text-sm uppercase tracking-wider font-semibold shadow-md">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
            
            <div class="flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-white text-2xl font-black tracking-tight flex items-center space-x-2 normal-case">
                    <i class="fa-brands fa-steam text-3xl text-sky-400"></i>
                    <span>STEAM<span class="text-sky-400 font-light text-lg">KEY</span></span>
                </a>
                
                <nav class="hidden md:flex space-x-6 text-xs pt-1">
                    <a href="{{ route('home') }}" class="hover:text-white transition {{ request()->routeIs('home') ? 'text-sky-400' : '' }}">Cửa Hàng</a>
                    <a href="{{ route('games.index') }}" class="hover:text-white transition {{ request()->routeIs('games.*') ? 'text-sky-400' : '' }}">Trò Chơi</a>
                    <a href="#" class="hover:text-white transition">Cộng Đồng</a>
                    <a href="#" class="hover:text-white transition">Hỗ Trợ</a>
                </nav>
            </div>

            <div class="flex items-center space-x-4 text-xs normal-case">
                 <a href="{{ route('cart.index') }}" class="bg-[#5c7e10] hover:bg-[#75b022] text-white px-3 py-1.5 rounded-sm flex items-center space-x-2 transition font-medium">
    <i class="fa-solid fa-cart-shopping text-sm"></i>
    <span>Giỏ hàng</span>
    <span class="bg-[#171a21] text-[#beee11] px-1.5 py-0.5 text-[10px] font-bold rounded-sm">
        {{ $cartCount }}
    </span>
</a>
        

                <div class="flex items-center space-x-3 border-l border-gray-700 pl-4">
                    <a href="{{ route('library.index') }}" class="text-[#c7d5e0] hover:text-white transition font-medium">Thư viện</a>
                    <span class="text-gray-600">|</span>
                   <div class="flex items-center space-x-4 text-xs normal-case">
 

    <div class="border-l border-gray-700 pl-4 flex items-center space-x-3">
        @auth('player')
         
            <div class="flex items-center space-x-2">
                <span class="text-sky-400 font-semibold">{{ Auth::guard('player')->user()->username }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-white transition text-[10px]">(Đăng xuất)</button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="text-[#c7d5e0] hover:text-white transition font-medium">Đăng nhập</a>
            <span class="text-gray-600">|</span>
            <a href="{{ route('register') }}" class="text-[#c7d5e0] hover:text-white transition font-medium">Đăng ký</a>
        @endauth
    </div>
</div>
                </div>
            </div>

        </div>
    </header>

    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-6">
        @yield('content')
    </main>

    <footer class="bg-[#171a21] text-[#8f98a0] text-xs py-8 border-t border-[#2a475e]/3xl">
        <div class="max-w-6xl mx-auto px-4">
            <div class="border-b border-gray-800 pb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <p>© 2026 SteamKey Marketplace. Được phát triển dựa trên mô hình API JIT Dropshipping.</p>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-white transition">Chính sách bảo mật</a>
                    <a href="#" class="hover:text-white transition">Điều khoản dịch vụ</a>
                    <a href="#" class="hover:text-white transition">Liên hệ hoàn tiền</a>
                </div>
            </div>
            <p class="pt-4 text-[11px] text-gray-600 leading-relaxed">
                Tất cả các nhãn hiệu, tên trò chơi và hình ảnh liên quan đều thuộc quyền sở hữu của các nhà phát hành gốc trên Steam, Epic Games hoặc Origin. Hệ thống vận hành tự động kết nối API đối soát mã giao dịch nhà cung cấp.
            </p>
        </div>
    </footer>

</body>
</html>