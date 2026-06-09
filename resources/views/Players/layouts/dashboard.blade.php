@extends('Players.Layouts.app')

@section('content')
<div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-6 py-6">
    
    <aside class="bg-[#171a21] rounded-sm border border-[#2a475e]/30 h-fit">
        <div class="p-4 border-b border-[#2a475e]/30 flex items-center space-x-3">
            <div class="w-10 h-10 bg-[#101822] border border-sky-400 flex items-center justify-center rounded-xs">
                <i class="fa-solid fa-user text-sky-400"></i>
            </div>
            <div class="overflow-hidden">
                <h3 class="text-white font-bold text-sm truncate">
                    {{ Auth::guard('player')->user()->username ?? 'Người chơi' }}
                </h3>
                <span class="text-[10px] text-emerald-400 uppercase tracking-wider font-semibold">Trực tuyến</span>
            </div>
        </div>
        <nav class="mt-2 py-2 space-y-0.5 text-xs font-medium">
            @php
                $links = [
                    ['route' => 'library.index', 'icon' => 'fa-gamepad', 'label' => 'Thư Viện Trò Chơi'],
                    ['route' => 'social.index', 'icon' => 'fa-users', 'label' => 'Bạn Bè & Quà Tặng'],
                    ['route' => 'orders.history', 'icon' => 'fa-receipt', 'label' => 'Lịch Sử Giao Dịch'],
                    ['route' => 'library.redeem', 'icon' => 'fa-plus', 'label' => 'Kích hoạt mã (Redeem)'],
                ];
            @endphp

            @foreach($links as $link)
                <a href="{{ route($link['route']) }}" 
                   class="flex items-center space-x-3 px-4 py-3 transition {{ request()->routeIs($link['route']) ? 'bg-[#2a475e]/50 text-white border-l-2 border-sky-400' : 'text-[#c7d5e0] hover:bg-[#2a475e]/30 hover:text-white' }}">
                    <i class="fa-solid {{ $link['icon'] }} w-4 text-center"></i> 
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </aside>

    <main class="lg:col-span-3 bg-[#1b2838] border border-[#2a475e]/20 p-6 rounded-sm">
        @yield('dashboard_content')
    </main>
</div>
@endsection
@if(Auth::guard('player')->check())
    <script>
        setInterval(function() {
            fetch('/api/check-status')
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.status === 'Banned') {
                        window.location.reload();
                    }
                })
                .catch(error => console.log('Kiểm tra trạng thái thất bại:', error));
        }, 10000);
    </script>
@endif