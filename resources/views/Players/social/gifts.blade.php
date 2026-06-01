@extends('Players.Layouts.dashboard')
@section('title', 'Hộp quà của bạn')

@section('dashboard_content')
<div class="space-y-6">
    <h2 class="text-white font-bold uppercase border-b border-gray-800 pb-2">📦 QUÀ TẶNG CỦA BẠN</h2>
    
    <div class="space-y-3">
        @forelse($gifts as $gift)
            <div class="bg-[#171a21] p-4 flex justify-between items-center border border-sky-900/50">
                <div>
                    <p class="text-white font-bold">{{ $gift->sender->username }} đã tặng bạn game!</p>
                    <p class="text-xs text-gray-400 italic mt-1">"{{ $gift->message }}"</p>
                </div>
                <form action="{{ route('gifts.accept', $gift->id) }}" method="POST">
                    @csrf
                    <button class="bg-[#5c7e10] hover:bg-[#75b022] px-4 py-2 text-xs text-white uppercase font-bold transition">Nhận quà</button>
                </form>
            </div>
        @empty
            <div class="text-gray-500 italic p-4 border border-dashed border-gray-800">Không có quà tặng nào mới.</div>
        @endforelse
    </div>
</div>
@endsection