@extends('Players.Layouts.dashboard')
@section('title', 'Gửi quà cho ' . $friend->username)

@section('dashboard_content')
<div class="max-w-2xl mx-auto py-6">
    <div class="bg-[#171a21] p-6 border border-gray-700 shadow-xl">
        <h2 class="text-white text-xl font-bold mb-2 uppercase border-b border-gray-700 pb-2">Gửi quà tặng</h2>
        <p class="text-gray-400 text-sm mb-6">Tặng trò chơi trong thư viện của bạn cho người bạn <span class="text-sky-400 font-bold">{{ $friend->username }}</span>.</p>
        
        <form action="{{ route('gifts.send') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="friend_id" value="{{ $friend->id }}">
            
            <div>
                <label class="text-white text-xs block mb-2 font-bold tracking-wider">CHỌN TRÒ CHƠI TRONG THƯ VIỆN:</label>
                <select name="key_id" class="w-full bg-[#1b1e27] text-white p-4 border border-gray-600 focus:border-sky-500 outline-none transition appearance-none cursor-pointer">
                    @forelse($myGames as $game)
                        <option value="{{ $game->key_id }}" class="bg-[#171a21] text-white p-2">
                            {{ $game->game_name }} - Phiên bản: {{ $game->version_name }}
                        </option>
                    @empty
                        <option disabled class="text-gray-500">Bạn hiện không có trò chơi nào có thể gửi tặng.</option>
                    @endforelse
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    </div>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="bg-sky-700 hover:bg-sky-600 px-8 py-3 text-white font-bold text-sm uppercase transition duration-200">
                    Xác nhận gửi quà
                </button>
                <a href="{{ route('friends.index') }}" class="bg-gray-800 hover:bg-gray-700 px-8 py-3 text-gray-300 font-bold text-sm uppercase transition duration-200">
                    Quay lại
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    /* Ép style cho option trong dropdown */
    select option {
        background-color: #171a21 !important;
        color: white !important;
        padding: 12px !important;
    }
</style>
@endsection