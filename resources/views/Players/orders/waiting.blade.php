@extends('Players.Layouts.app')

@section('title', 'Đang xử lý thanh toán')

@section('content')
<div class="max-w-2xl mx-auto my-12">
    @if($order->status === 'API_Error' && request()->has('checked'))
        {{-- Hiển thị thông báo lỗi (sau khi check status) --}}
        <div class="bg-[#171a21] border-l-4 border-red-500 p-8 rounded-sm shadow-2xl mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-3xl"></i>
                </div>
                <div class="ml-4 flex-grow">
                    <h2 class="text-red-500 text-lg font-bold mb-2">⚠️ Lỗi Xử Lý Đơn Hàng</h2>
                    <p class="text-[#c7d5e0] text-sm mb-4">
                        Rất tiếc, hệ thống gặp sự cố khi xử lý đơn hàng của bạn. Chúng tôi đang nỗ lực khắc phục sớm nhất có thể.
                    </p>
                    <div class="bg-[#101822] p-4 rounded-sm mb-4 border border-[#2a475e]/20">
                        <p class="text-white text-xs font-bold mb-2">Mã đơn hàng: <span class="text-sky-400">#ORD-{{ $order->id }}</span></p>
                        <p class="text-white text-xs font-bold">Tổng tiền: <span class="text-emerald-400">{{ number_format($order->total_amount) }}đ</span></p>
                    </div>
                    <p class="text-[#8f98a0] text-xs mb-4">
                        ✓ Khoản tiền của bạn đã được bảo vệ<br>
                        ✓ Chúng tôi sẽ xử lý sớm nhất trong vòng 24 giờ
                    </p>
                    <div class="space-y-2">
                        <h3 class="text-white font-bold text-xs uppercase tracking-wider mb-2">📞 Liên Hệ Admin:</h3>
                        <div class="text-[#c7d5e0] text-xs space-y-1">
                            <p><i class="fas fa-envelope me-2 text-sky-400"></i><strong>Email:</strong> <a href="mailto:admin@gamekey.com" class="text-sky-400 hover:underline">admin@gamekey.com</a></p>
                            <p><i class="fas fa-phone me-2 text-sky-400"></i><strong>Điện thoại:</strong> <a href="tel:+84123456789" class="text-sky-400 hover:underline">+84 (123) 456-789</a></p>
                            <p><i class="fas fa-comments me-2 text-sky-400"></i><strong>Chat:</strong> <a href="https://www.facebook.com/gamekey" target="_blank" class="text-sky-400 hover:underline">Facebook Messenger</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tóm tắt đơn hàng --}}
        <div class="bg-[#171a21] border border-[#2a475e]/30 p-6 rounded-sm shadow-2xl">
            <h3 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">📋 Tóm tắt đơn hàng</h3>
            <div class="space-y-3 mb-4">
                @foreach($order->orderItems as $item)
                <div class="flex justify-between items-center text-xs bg-[#101822] p-3 rounded border border-[#2a475e]/20">
                    <span class="text-[#c7d5e0]">
                        {{ $item->gameVersion->game->name ?? 'Game' }} 
                        <span class="text-sky-400">({{ $item->gameVersion->version_name ?? 'N/A' }})</span> x{{ $item->quantity }}
                    </span>
                    <span class="text-white font-medium">{{ number_format($item->price_at_purchase * $item->quantity) }}đ</span>
                </div>
                @endforeach
                <div class="border-t border-[#2a475e]/30 pt-3 flex justify-between">
                    <span class="text-white font-bold text-sm">Tổng thanh toán:</span>
                    <span class="text-emerald-400 font-bold text-sm">{{ number_format($order->total_amount) }}đ</span>
                </div>
            </div>
        </div>
    @else
        {{-- Hiển thị khi đơn hàng thành công --}}
        <div class="max-w-md mx-auto bg-[#171a21] border border-[#2a475e]/30 p-8 rounded-sm text-center shadow-2xl">
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
                @foreach($order->orderItems as $item)
                <div class="flex justify-between items-center text-xs">
                    <span class="text-[#c7d5e0]">
                        {{ $item->gameVersion->game->name ?? 'Game' }} 
                        <span class="text-sky-400">({{ $item->gameVersion->version_name ?? 'N/A' }})</span> x{{ $item->quantity }}
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
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const isChecked = urlParams.has('checked');
        const statusElement = document.querySelector('[data-order-status]');
        const orderStatus = statusElement ? statusElement.dataset.orderStatus : null;
        
        if (!isChecked) {
            // Lần đầu: countdown 5 giây rồi redirect để check status
            let seconds = 5;
            const countdownEl = document.getElementById('countdown');

            const timer = setInterval(() => {
                seconds--;
                if (countdownEl) countdownEl.textContent = seconds;

                if (seconds <= 0) {
                    clearInterval(timer);
                    window.location.href = "{{ route('orders.waiting', ['order_id' => $order->id]) }}?checked=1";
                }
            }, 1000);
        } else {
            // Lần thứ 2: đã check status, nếu thành công thì redirect ngay
            if (orderStatus && orderStatus === 'Completed') {
                // Redirect thẳng đến library (không cần popup)
                window.location.href = "{{ route('library.index') }}";
            }
        }
    });
</script>

<!-- Hidden element to pass data to script -->
<div data-order-status="{{ $order->status }}" style="display: none;"></div>
@endsection