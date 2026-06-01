@extends('Players.Layouts.app')

@section('title', 'Đang xử lý thanh toán')

@section('content')
<div class="max-w-md mx-auto my-12 bg-[#171a21] border border-[#2a475e]/30 p-8 rounded-sm text-center shadow-2xl">
    <div class="flex justify-center mb-6">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-sky-400"></div>
    </div>
    
    <h2 class="text-white text-lg font-bold mb-2">Đang kết nối ngân hàng...</h2>
    <p class="text-xs text-[#8f98a0] mb-6">
        Vui lòng quét mã QR dưới đây để hoàn tất thanh toán. 
        Hệ thống sẽ tự động xác nhận sau <span id="countdown" class="text-amber-400 font-bold text-sm">5</span> giây.
    </p>

    <div class="bg-white p-2 rounded-sm inline-block mb-6">
        <img src="https://qr.sepay.vn/img?acc=153058888&bank=VIB&amount={{ $order->total_amount }}&des=ORDER{{ $order->id }}" 
             alt="QR Thanh toán" 
             class="w-56 h-56 mx-auto">
    </div>
    <div class="bg-[#101822] p-4 rounded-sm mb-6 text-left border border-[#2a475e]/20">
    <h3 class="text-white font-bold text-sm mb-3 uppercase tracking-wider">Tóm tắt đơn hàng</h3>
    
    <div class="space-y-3">
        @foreach($order->items as $item)
        <div class="flex justify-between items-center text-xs">
            <span class="text-[#c7d5e0]">
                {{ $item->version->game->name }} 
                <span class="text-sky-400">({{ $item->version->name }})</span> x{{ $item->quantity }}
            </span>
            <span class="text-white font-medium">{{ number_format($item->price_at_purchase * $item->quantity) }}đ</span>
        </div>
        @endforeach
        
        <div class="border-t border-[#2a475e]/30 pt-3 mt-3 flex justify-between">
            <span class="text-white font-bold">Tổng thanh toán:</span>
            <span class="text-emerald-400 font-bold">{{ number_format($order->total_amount) }}đ</span>
        </div>
    </div>
</div>

    <div class="text-[#c7d5e0] text-xs space-y-1">
        <p>Số tiền: <span class="text-white font-bold">{{ number_format($order->total_amount) }}đ</span></p>
        <p>Nội dung: <span class="text-sky-400 font-bold">ORDER{{ $order->id }}</span></p>
    </div>
</div>

<script>
    let seconds = 5;
    const countdownEl = document.getElementById('countdown');

    const timer = setInterval(() => {
        seconds--;
        if (countdownEl) countdownEl.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(timer);
            // Chuyển hướng về trang chủ kèm thông báo thành công
            window.location.href = "{{ route('home') }}?payment=success";
        }
    }, 1000);
</script>
@endsection