@extends('Players.Layouts.dashboard')

@section('title', 'Chi tiết đơn hàng')

@section('dashboard_content')
<div class="bg-[#171a21] p-6 rounded-sm border border-gray-800 space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-4">
        <h2 class="text-white font-bold text-lg">Chi tiết đơn hàng #ORD-{{ $order->id }}</h2>
        <span class="text-xs text-gray-400">Ngày mua: {{ $order->created_at->format('d/m/Y H:i') }}</span>
    </div>

    <div class="space-y-4">
        @foreach($order->items as $item)
            <div class="flex items-center gap-4 bg-[#101822] p-4 rounded-sm">
                <img src="{{ $item->version->game->cover_image }}" class="w-20 h-12 object-cover rounded-sm">
                <div class="flex-1">
                    <h3 class="text-white font-bold">{{ $item->version->game->name }}</h3>
                    <p class="text-sky-400 text-xs">{{ $item->version->version_name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white font-bold">{{ number_format($item->price_at_purchase, 0, ',', '.') }}đ</p>
                    <p class="text-gray-500 text-xs">x{{ $item->quantity }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="border-t border-gray-800 pt-4 flex justify-between font-bold text-white">
        <span>Tổng cộng:</span>
        <span class="text-emerald-400 text-xl">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
    </div>

    <a href="{{ route('orders.history') }}" class="inline-block text-sky-400 hover:text-white text-sm">← Quay lại lịch sử</a>
</div>
@endsection