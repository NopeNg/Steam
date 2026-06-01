@extends('Players.Layouts.dashboard')
@section('title', 'Bạn bè')

@section('dashboard_content')
<div class="space-y-8">
    <div class="bg-[#171a21] p-4 border border-gray-700">
        <h3 class="text-white text-xs font-bold mb-2 uppercase">Tìm kiếm người chơi</h3>
        <form action="{{ route('friends.search') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập username..." class="flex-1 bg-black text-white p-2 border border-gray-600 focus:border-sky-500 outline-none">
            <button type="submit" class="bg-sky-700 px-6 text-white font-bold text-xs uppercase hover:bg-sky-600">Tìm kiếm</button>
        </form>
    </div>

    @if(isset($searchResults))
    <div class="mb-6">
        <h2 class="text-white font-bold border-b border-gray-700 pb-2 mb-4">🔍 KẾT QUẢ TÌM KIẾM</h2>
        @forelse($searchResults as $user)
            @php
                $status = \App\Models\Friendship::where(function($q) use ($user, $myId) {
                    $q->where('sender_id', $myId)->where('receiver_id', $user->id);
                })->orWhere(function($q) use ($user, $myId) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $myId);
                })->first();
            @endphp
            <div class="bg-[#171a21] p-3 flex justify-between items-center border border-gray-800">
                <span class="text-white">{{ $user->username }}</span>
                @if(!$status)
                    <form action="{{ route('friends.request', $user->id) }}" method="POST">
                        @csrf
                        <button class="text-xs text-sky-400 hover:underline">Gửi lời mời</button>
                    </form>
                @elseif($status->status == 'Pending')
                    <span class="text-gray-400 text-xs italic">Đang chờ...</span>
                @else
                    <span class="text-emerald-500 text-xs">Đã là bạn</span>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-xs">Không tìm thấy người dùng nào.</p>
        @endforelse
    </div>
    @endif

    @if(isset($incomingRequests) && $incomingRequests->count() > 0)
    <div class="mb-8">
        <h2 class="text-yellow-500 font-bold uppercase border-b border-yellow-800 pb-2 mb-4">📩 LỜI MỜI KẾT BẠN</h2>
        <div class="space-y-2">
            @foreach($incomingRequests as $request)
                <div class="bg-[#171a21] p-3 flex justify-between items-center border border-yellow-900">
                    <span class="text-white font-semibold">{{ $request->sender->username }} muốn kết bạn</span>
                    <div class="flex gap-2">
                        <form action="{{ route('friends.accept', $request->sender_id) }}" method="POST">
                            @csrf
                            <button class="text-xs bg-emerald-700 px-3 py-1 text-white hover:bg-emerald-600">Chấp nhận</button>
                        </form>
                        <form action="{{ route('friends.remove', $request->sender_id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-xs bg-red-800 px-3 py-1 text-white hover:bg-red-700">Từ chối</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div>
        <h2 class="text-white font-bold uppercase border-b border-gray-800 pb-2 mb-4">👥 BẠN BÈ CỦA BẠN</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($friends as $friendship)
                @php $friend = ($friendship->sender_id == $myId) ? $friendship->receiver : $friendship->sender; @endphp
          <div class="bg-[#171a21] p-3 flex justify-between items-center border border-gray-800">
    <span class="text-white font-bold">{{ $friend->username }}</span>
    <div class="flex gap-2">
        <a href="{{ route('gifts.showSendForm', $friend->id) }}" 
           class="text-xs text-emerald-400 underline hover:text-emerald-300">
           Gửi quà
        </a>
        
        <form action="{{ route('friends.remove', $friend->id) }}" method="POST">
            @csrf @method('DELETE')
            <button class="text-xs text-red-500 hover:text-red-300">Xóa</button>
        </form>
    </div>
</div>
            @empty
                <p class="text-gray-500 text-xs">Bạn chưa có bạn bè nào.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection