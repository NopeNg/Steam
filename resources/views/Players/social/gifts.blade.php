@extends('Players.Layouts.dashboard')
@section('title', 'Hộp quà của tôi')

@section('dashboard_content')
<div class="bg-[#171a21] p-8 border border-gray-700 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-white text-2xl font-bold uppercase tracking-widest border-l-4 border-sky-500 pl-4">Hộp quà của tôi</h2>
        <span class="text-gray-400 text-sm">Tổng: {{ $gifts->count() }} món quà</span>
    </div>

    @if($gifts->isEmpty())
        <div class="text-center py-20 border-2 border-dashed border-gray-700 rounded-lg">
            <p class="text-gray-500 text-lg">Hiện tại bạn không có món quà nào.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($gifts as $gift)
                <div class="bg-[#1b2838] p-5 rounded border border-gray-600 hover:border-sky-500 transition-all flex justify-between items-center">
                    <div>
                        <p class="text-white font-bold text-lg">{{ $gift->game_name }}</p>
                        <p class="text-gray-400 text-sm">Gửi bởi: <span class="text-sky-400">{{ $gift->sender_name }}</span></p>
                        <p class="text-gray-500 text-xs mt-1">Ngày: {{ \Carbon\Carbon::parse($gift->created_at)->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="text-right">
                        @if($gift->status == 'Sent')
                            <div class="flex flex-col gap-2">
                                <form action="{{ route('gifts.accept', $gift->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 text-sm rounded transition">Nhận</button>
                                </form>
                                <form action="{{ route('gifts.reject', $gift->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 text-sm rounded transition">Từ chối</button>
                                </form>
                            </div>
                        @else
                            <span class="px-3 py-1 rounded text-xs uppercase font-bold 
                                {{ $gift->status == 'Accepted' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                {{ $gift->status }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection