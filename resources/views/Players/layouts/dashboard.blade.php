@extends('Players.Layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <aside class="bg-[#171a21] p-2 rounded-sm border border-[#2a475e]/20 h-fit">
        <div class="p-4 border-b border-[#2a475e]/20 flex items-center space-x-3">
            <img src="https://avatars.githubusercontent.com/u/9919?v=4" class="w-10 h-10 border border-sky-400 rounded-xs">
            <div>
                <h3 class="text-white font-bold text-sm">Gamer_A</h3>
                <span class="text-xs text-emerald-400">Trực tuyến</span>
            </div>
        </div>
        <nav class="mt-2 space-y-1 text-xs font-medium">
            <a href="{{ route('library.index') }}" class="flex items-center space-x-3 px-4 py-3 text-[#c7d5e0] hover:bg-[#2a475e]/30 hover:text-white transition rounded-sm {{ request()->routeIs('library.*') ? 'bg-[#2a475e]/50 text-white border-l-2 border-sky-400' : '' }}">
                <i class="fa-solid fa-gamepad w-4 text-center"></i> <span>Thư Viện Trò Chơi</span>
            </a>
            <a href="{{ route('social.index') }}" class="flex items-center space-x-3 px-4 py-3 text-[#c7d5e0] hover:bg-[#2a475e]/30 hover:text-white transition rounded-sm {{ request()->routeIs('social.*') ? 'bg-[#2a475e]/50 text-white border-l-2 border-sky-400' : '' }}">
                <i class="fa-solid fa-users w-4 text-center"></i> <span>Bạn Bè & Quà Tặng</span>
            </a>
            <a href="{{ route('orders.history') }}" class="flex items-center space-x-3 px-4 py-3 text-[#c7d5e0] hover:bg-[#2a475e]/30 hover:text-white transition rounded-sm {{ request()->routeIs('orders.history') ? 'bg-[#2a475e]/50 text-white border-l-2 border-sky-400' : '' }}">
                <i class="fa-solid fa-receipt w-4 text-center"></i> <span>Lịch Sử Giao Dịch</span>
            </a>
            <a href="{{ route('library.redeem') }}" class="flex items-center space-x-3 px-4 py-3 text-[#c7d5e0] hover:text-white transition">
    <i class="fa-solid fa-plus w-4 text-center"></i> <span>Kích hoạt mã (Redeem)</span>
</a>
        </nav>
    </aside>

    <div class="lg:col-span-3">
        @yield('dashboard_content')
    </div>
</div>
@endsection