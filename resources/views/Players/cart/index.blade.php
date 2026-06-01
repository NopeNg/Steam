@extends('Players.Layouts.app')
@section('title', 'Giỏ hàng của bạn')
@section('content')
<div class="space-y-6">
    <h1 class="text-white text-xl font-bold uppercase tracking-wider">Giỏ hàng của bạn</h1>
    
    @if($cartItems->isEmpty())
        <div class="bg-[#171a21] p-8 text-center rounded-sm border border-gray-800 text-sm">
            Giỏ hàng trống. <a href="{{ route('home') }}" class="text-sky-400 underline">Quay lại cửa hàng</a> để chọn game.
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-3">
                @foreach($cartItems as $item)
                <div class="bg-[#171a21] p-4 rounded-sm border border-[#2a475e]/20 flex justify-between items-center text-xs">
                    <div class="flex items-center space-x-4">
                        <img src="https://via.placeholder.com/120x60" class="w-20 h-10 object-cover rounded-xs">
                        <div>
                            <h3 class="text-white font-bold text-sm">{{ $item->version->game->name }}</h3>
                            <p class="text-gray-500 mt-0.5">Phiên bản: {{ $item->version->version_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" onchange="this.form.submit()" class="w-12 bg-[#101822] border border-[#2a475e] text-center p-1 text-white rounded-xs">
                        </form>
                        <span class="text-white font-semibold">{{ number_format(($item->version->discount_price ?? $item->version->price) * $item->quantity, 0, ',', '.') }}đ</span>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-[#171a21] p-4 rounded-sm border border-[#2a475e]/20 h-fit space-y-4 text-xs">
                <h3 class="text-white font-bold text-sm border-b border-gray-800 pb-2">Tổng tiền hóa đơn</h3>
                <div class="flex justify-between">
                    <span class="text-gray-400">Tạm tính:</span>
                    <span class="text-white font-semibold">
                        {{ number_format($cartItems->sum(function($BC) { return ($BC->version->discount_price ?? $BC->version->price) * $BC->quantity; }), 0, ',', '.') }}đ
                    </span>
                </div>
                <a href="{{ route('orders.checkout') }}" class="block text-center bg-gradient-to-r from-[#75b022] to-[#588a1b] text-white py-2.5 font-bold rounded-xs uppercase tracking-wide">Thực hiện thanh toán</a>
            </div>
        </div>
    @endif
</div>
@endsection