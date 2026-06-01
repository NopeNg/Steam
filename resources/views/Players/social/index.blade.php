@extends('Players.Layouts.dashboard')
@section('title', 'Social Hub')

@section('dashboard_content')
<div class="space-y-6">
    <h1 class="text-white text-xl font-bold uppercase tracking-widest border-b border-gray-800 pb-4">Social Hub</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('gifts.index') }}" class="block bg-[#171a21] p-6 border border-gray-700 hover:border-sky-500 transition-all group">
            <h3 class="text-white font-bold text-lg group-hover:text-sky-400">🎁 Quà tặng</h3>
            <p class="text-gray-400 text-sm mt-2">Xem các món quà đang chờ nhận và lịch sử quà tặng.</p>
        </a>

        <a href="{{ route('friends.index') }}" class="block bg-[#171a21] p-6 border border-gray-700 hover:border-emerald-500 transition-all group">
            <h3 class="text-white font-bold text-lg group-hover:text-emerald-400">👥 Danh sách bạn bè</h3>
            <p class="text-gray-400 text-sm mt-2">Tìm kiếm người chơi mới và quản lý bạn bè hiện tại.</p>
        </a>
    </div>
</div>
@endsection