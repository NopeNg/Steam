@extends('Players.Layouts.app')

@section('title', 'Đang xử lý thanh toán')

@section('content')
<div class="max-w-md mx-auto my-12 bg-[#171a21] border border-[#2a475e]/30 p-8 rounded-sm text-center space-y-6 shadow-2xl">
    <div class="flex justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-sky-400"></div>
    </div>
    
    <h2 class="text-white text-lg font-bold">Hệ thống đang kết nối ngân hàng...</h2>
    <p class="text-xs text-[#8f98a0] leading-relaxed">
        Vui lòng không tắt hoặc làm mới (F5) trình duyệt. Đơn hàng của bạn sẽ được hoàn tất tự động sau <span id="countdown" class="text-amber-400 font-bold text-sm">15</span> giây.
    </p>
</div>

<script>
    let seconds = 15;
    const countdownEl = document.getElementById('countdown');

    const timer = setInterval(() => {
        seconds--;
        countdownEl.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(timer);
            // Hết 15 giây, gọi về link hoàn thành
            window.location.href = "{{ route('vnpay.return') }}?order_id={{ $orderId }}";
        }
    }, 1000);
</script>
@endsection