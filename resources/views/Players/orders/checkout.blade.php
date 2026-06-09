@extends('Players.Layouts.app')

@section('title', 'Xác nhận thanh toán đơn hàng')

@section('content')
<div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <form action="{{ route('orders.process') }}" method="POST" class="md:col-span-2 bg-[#171a21] p-6 rounded-sm border border-[#2a475e]/20 text-xs space-y-6 shadow-2xl">
        @csrf
        <h2 class="text-white text-base font-bold uppercase tracking-wider border-b border-[#2a475e]/30 pb-3 flex items-center justify-between">
            <span>Xác nhận đơn hàng</span>
            <span class="text-[11px] text-[#8f98a0] font-normal lowercase">Cổng thanh toán tự động</span>
        </h2>
        
        <div class="space-y-3">
            <label class="text-white font-bold block text-sm">1. Chọn hình thức mua hàng:</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-center space-x-3 p-3 bg-[#101822] border border-[#2a475e]/30 rounded-sm cursor-pointer hover:border-sky-500/50 transition-all group">
                    <input type="radio" name="order_type" value="Personal" checked class="w-4 h-4 accent-sky-500"> 
                    <div>
                        <span class="text-white font-semibold block">Mua cho bản thân</span>
                        <span class="text-[10px] text-[#556772]">Game sẽ tự động thêm trực tiếp vào thư viện của bạn.</span>
                    </div>
                </label>
                
                <label class="flex items-center space-x-3 p-3 bg-[#101822] border border-[#2a475e]/30 rounded-sm cursor-pointer hover:border-sky-500/50 transition-all group">
                    <input type="radio" name="order_type" value="Gift" class="w-4 h-4 accent-sky-500"> 
                    <div>
                        <span class="text-white font-semibold block">Mua làm quà tặng</span>
                        <span class="text-[10px] text-[#556772]">Tạo gift-code gửi tặng cho bạn bè kích hoạt.</span>
                    </div>
                </label>
                <!-- <label class="flex items-center space-x-3 p-3 bg-[#101822] border border-[#2a475e]/30 rounded-sm cursor-pointer hover:border-sky-500/50 transition-all group">
        <input type="radio" name="order_type" value="Other" class="w-4 h-4 accent-sky-500"> 
        <div>
            <span class="text-white font-semibold block text-xs">Khác</span>
            <span class="text-[9px] text-[#556772]">Mục đích khác</span>
        </div>
    </label> -->
            </div>
        </div>

        <div class="space-y-3">
            <label class="text-white font-bold block text-sm">2. Phương thức thanh toán:</label>
            <div class="relative">
                <select name="payment_method" class="w-full bg-[#101822] border border-[#2a475e]/60 text-white p-3 rounded-sm appearance-none focus:outline-none focus:border-sky-400 font-medium transition-all">
                    <option value="VNPAY" selected>Cổng thanh toán điện tử VNPAY (QR Code ngân hàng / ATM)</option>
                    <!-- <option value="MoMo">Ví điện tử MoMo (Thanh toán tự động)</option>
                    <option value="Bank_Transfer">Chuyển khoản liên ngân hàng 24/7</option> -->
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#8f98a0]">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-[#1b2838] border-l-2 border-amber-500 p-3 text-[#8f98a0] leading-relaxed text-[11px]">
            ⚡ <strong>Lưu ý:</strong> Sau khi click nút thanh toán, hệ thống sẽ thực hiện kết nối xử lý bảo mật. Bạn vui lòng đợi trong giây lát và tuyệt đối không tải lại trang khi tiến trình đang diễn ra.
        </div>

    <button type="submit" 
        class="w-full bg-[#6fa127] hover:bg-[#82bd2d] text-[#e5f4d2] py-3 rounded-[2px] text-sm font-bold border border-black shadow-[inset_0_1px_0_rgba(255,255,255,0.2)] transition-all duration-200 tracking-wider"
        style="text-shadow: 1px 1px 0px rgba(0,0,0,0.6);">
    Xác nhận & Thanh toán đơn hàng
</button>
    </form>

    <div class="space-y-4">
        <div class="bg-[#171a21] p-4 rounded-sm border border-[#2a475e]/20 shadow-xl space-y-4">
            <h3 class="text-white text-xs uppercase font-bold tracking-wider border-b border-[#2a475e]/20 pb-2">Tóm tắt đơn hàng</h3>
            
            <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                @php $totalAmount = 0; @endphp
                @foreach($cartItems as $item)
                    @php 
                        $price = $item->version->discount_price ?? $item->version->price;
                        $subTotal = $price * $item->quantity;
                        $totalAmount += $subTotal;
                    @endphp
                    <div class="flex space-x-3 items-center border-b border-gray-800/40 pb-3">
                        <img src="{{ $item->version->game->cover_image ?? 'https://via.placeholder.com/120x60' }}" class="w-16 h-10 object-cover rounded-xs flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-white font-semibold text-[11px] truncate">{{ $item->version->game->name }}</h4>
                            <p class="text-[10px] text-sky-400">{{ $item->version->version_name }} <span class="text-[#8f98a0]">x{{ $item->quantity }}</span></p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-white font-medium text-[11px]">{{ number_format($price, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="space-y-1.5 pt-2 text-xs">
                <div class="flex justify-between text-[#8f98a0]">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($totalAmount, 0, ',', '.') }}đ</span>
                </div>
                <div class="flex justify-between text-[#8f98a0]">
                    <span>Phí dịch vụ cổng:</span>
                    <span class="text-emerald-400">Miễn phí</span>
                </div>
                <hr class="border-[#2a475e]/20 my-2">
                <div class="flex justify-between items-center">
                    <span class="text-white font-bold text-[13px]">Tổng thanh toán:</span>
                    <span class="text-[#beee11] font-bold text-base tracking-tight">{{ number_format($totalAmount, 0, ',', '.') }}đ</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection