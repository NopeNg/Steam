@extends('Players.Layouts.dashboard')
@section('title', 'Gửi quà cho ' . $friend->username)

@section('dashboard_content')
<div class="space-y-6">
    <h2 class="text-white font-bold text-lg border-b border-gray-700 pb-3">
        🎁 Gửi quà tặng đến <span class="text-sky-400">{{ $friend->username }}</span>
    </h2>

    <form action="{{ route('gifts.send') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="friend_id" value="{{ $friend->id }}">

        @if($myGames->count() > 0)
            <p class="text-gray-400 text-xs">Chọn trò chơi trong thư viện để gửi tặng {{ $friend->username }}:</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($myGames as $game)
                    <label class="bg-[#171a21] border border-gray-800 hover:border-sky-500 cursor-pointer p-3 flex items-center space-x-3 transition rounded-sm">
                        <input type="radio" name="key_id" value="{{ $game->key_id }}" class="accent-sky-500">
                        <div class="w-14 h-14 bg-gray-800 flex-shrink-0 overflow-hidden rounded-sm">
                            @if($game->cover_image)
                                <img src="{{ $game->cover_image }}" alt="{{ $game->game_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500 text-xs">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-semibold text-sm truncate">{{ $game->game_name }}</p>
                            <p class="text-gray-400 text-xs">{{ $game->version_name }}</p>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold uppercase text-xs px-8 py-3 tracking-wider rounded-sm transition">
                    <i class="fa-solid fa-gift mr-2"></i> GỬI QUÀ
                </button>
                <a href="{{ route('friends.index') }}" class="ml-3 text-gray-400 hover:text-white text-xs transition">Huỷ</a>
            </div>
        @else
            <div class="bg-[#171a21] p-6 border border-gray-700 text-center">
                <div class="text-4xl text-gray-600 mb-3">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <p class="text-gray-400 text-sm mb-4">Bạn không còn trò chơi nào trong thư viện để gửi tặng.</p>
                <p class="text-gray-500 text-xs">Các trò chơi đã gửi quà trước đó sẽ không xuất hiện ở đây.</p>
                <a href="{{ route('home') }}" class="inline-block mt-4 bg-sky-700 hover:bg-sky-600 text-white font-bold uppercase text-xs px-6 py-3 rounded-sm transition">
                    <i class="fa-solid fa-store mr-2"></i> ĐẾN CỬA HÀNG
                </a>
            </div>
        @endif
    </form>
</div>
@endsection