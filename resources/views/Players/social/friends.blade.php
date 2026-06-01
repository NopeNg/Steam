@extends('Players.Layouts.dashboard')
@section('title', 'Bạn bè')

@section('dashboard_content')
<div class="space-y-8">
    <div class="bg-[#171a21] p-4 border border-gray-700">
        <h3 class="text-white text-xs font-bold mb-2 uppercase">Tìm kiếm người chơi</h3>
        <form action="{{ route('friends.search') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="search" placeholder="Nhập username..." class="flex-1 bg-black text-white p-2 border border-gray-600 focus:border-sky-500 outline-none">
            <button class="bg-sky-700 px-6 text-white font-bold text-xs uppercase hover:bg-sky-600">Tìm kiếm</button>
        </form>
    </div>

    @if(isset($searchResults))
    <div class="mb-6">
        <h2 class="text-white font-bold border-b border-gray-700 pb-2 mb-4">🔍 KẾT QUẢ TÌM KIẾM</h2>
        @forelse($searchResults as $user)
            <div class="bg-[#171a21] p-3 flex justify-between items-center border border-gray-800">
                <span class="text-white">{{ $user->username }}</span>
                
                @if(in_array($user->id, $acceptedFriends))
                    <span class="text-emerald-500 text-xs">Đã là bạn (Accepted)</span>
                @elseif(in_array($user->id, $sentRequests))
                    <span class="text-gray-400 text-xs">Đã gửi lời mời</span>
                @else
                    <form action="{{ route('friends.request', $user->id) }}" method="POST">
                        @csrf
                        <button class="text-xs text-sky-400 hover:underline">Gửi lời mời kết bạn</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-xs">Không tìm thấy người chơi nào.</p>
        @endforelse
    </div>
@endif
    <div>
        <h2 class="text-white font-bold uppercase border-b border-gray-800 pb-2 mb-4">👥 BẠN BÈ CỦA BẠN</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($friends as $friendship)
                @php $friend = ($friendship->sender_id == $myId) ? $friendship->receiver : $friendship->sender; @endphp
                <div class="bg-[#171a21] p-3 flex justify-between items-center border border-gray-800">
                    <span class="text-white font-bold">{{ $friend->username }}</span>
                    <button class="text-xs text-emerald-400 underline hover:text-emerald-300">Gửi quà</button>
                </div>
            @empty
                <p class="text-gray-500 text-xs">Bạn chưa có bạn bè nào.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection