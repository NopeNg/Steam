@extends('Players.Layouts.app')

@section('title', 'Đang xử lý thanh toán')

@section('content')
<div class="max-w-2xl mx-auto my-12">
    @if($order->status === 'API_Error')
        {{-- Hiển thị thông báo lỗi từ nhà cung cấp --}}
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

        <div class="bg-[#171a21] border border-[#2a475e]/30 p-6 rounded-sm shadow-2xl">
            <h3 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">📋 Tóm tắt đơn hàng</h3>
            <div class="space-y-3 mb-4">
                @foreach($order->orderItems as $item)
                <div class="flex justify-between items-center text-xs bg-[#101822] p-3 rounded border border-[#2a475e]/20">
                    <span class="text-[#c7d5e0]">
                        {{ $item->version->game->name ?? 'Game' }} 
                        <span class="text-sky-400">({{ $item->version->version_name ?? 'N/A' }})</span> x{{ $item->quantity }}
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
        {{-- Hiển thị chờ thanh toán QR Code --}}
        <div class="max-w-md mx-auto bg-[#171a21] border border-[#2a475e]/30 p-8 rounded-sm text-center shadow-2xl">
            <div class="flex justify-center mb-6">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-sky-400"></div>
            </div>
            
            <h2 class="text-white text-lg font-bold mb-2">Đang chờ thanh toán...</h2>
            <p class="text-xs text-[#8f98a0] mb-6">
                Vui lòng quét mã QR dưới đây để hoàn tất giao dịch.<br>
                Hết hạn thanh toán sau: <span id="countdown-30s" class="text-amber-400 font-bold text-sm">30</span> giây.
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
                            {{ $item->version->game->name ?? 'Game' }} 
                            <span class="text-sky-400">({{ $item->version->version_name ?? 'N/A' }})</span> x{{ $item->quantity }}
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
        @if($order->status !== 'API_Error' && $order->status !== 'Completed')
            let timeLeft = 30;
            const countdownEl = document.getElementById('countdown-30s');
            
            // 1. Đếm ngược 30s giao diện
            const countdownTimer = setInterval(() => {
                timeLeft--;
                if (countdownEl) countdownEl.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(countdownTimer);
                    // Hết 30s -> Tự động gọi AJAX kích hoạt hàm lấy Key từ nhà cung cấp
                    executeOrderSupplier();
                }
            }, 1000);

            // 2. Gọi AJAX xử lý cấp key ngầm
            function executeOrderSupplier() {
                const executeUrl = "{{ route('orders.execute', ['order_id' => $order->id]) }}";

                fetch(executeUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Lỗi kết nối server');
                    return response.json();
                })
                .then(data => {
                    // Chuyển hướng sau khi xử lý xong (Thành công -> Thư viện, Lỗi -> Giỏ hàng)
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                })
                .catch(error => console.error('Lỗi kết nối hệ thống cấp key:', error));
            }
        @endif
    });
</script>
@endsection