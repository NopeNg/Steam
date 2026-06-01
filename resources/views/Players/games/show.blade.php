@extends('Players.layouts.app')

@section('title', $game->name . ' - Chi Tiết Trò Chơi')

@section('content')
<div class="space-y-6">
    <h1 class="text-white text-2xl font-bold tracking-tight">{{ $game->name }}</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 bg-[#171a21] p-4 rounded-sm border border-[#2a475e]/20 shadow-2xl">
        <div class="lg:col-span-2 h-64 md:h-96 rounded-sm overflow-hidden shadow-inner">
            <img src="{{ $game->cover_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
        </div>

        <div class="flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <p class="text-xs text-[#8f98a0] leading-relaxed line-clamp-6">
                    {{ $game->description }}
                </p>
                <div class="text-xs space-y-1.5 pt-2 border-t border-[#2a475e]/20">
                    <p class="text-[#556772]">Ngày phát hành: <span class="text-[#8f98a0]">{{ \Carbon\Carbon::parse($game->release_date)->format('d/m/Y') }}</span></p>
                    <p class="text-[#556772]">Nhà phát triển: <span class="text-[#8f98a0]">{{ $game->developer ?? 'Đang cập nhật' }}</span></p>
                    <p class="text-[#556772]">Trạng thái: <span class="text-emerald-400 font-medium">Sẵn sàng cấp Key</span></p>
                </div>
            </div>

            <div>
                <p class="text-[11px] text-[#556772] uppercase font-bold tracking-wider mb-2">Hình ảnh Screenshot</p>
                <div class="grid grid-cols-2 gap-2">
                    @forelse($game->images as $img)
                        <div class="h-16 rounded-xs overflow-hidden border border-[#2a475e]/30 hover:border-sky-400 transition-all cursor-zoom-in">
                            <img src="{{ $img->image_path }}" alt="screenshot" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity">
                        </div>
                    @empty
                        <p class="text-xs text-[#556772] col-span-2 italic">Không có hình ảnh đính kèm</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h2 class="text-white text-sm uppercase tracking-widest font-bold">Chọn phiên bản mua sản phẩm</h2>
        
        <div class="space-y-3">
            @forelse($game->versions as $version)
                <div class="bg-gradient-to-r from-[#1b2838] to-[#16222f] border border-[#2a475e]/40 rounded-sm p-4 flex flex-col md:flex-row justify-between items-center shadow-md hover:border-[#3b6484]/60 transition-all">
                    
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-white text-base font-bold">{{ $version->version_name }}</h3>
                        <p class="text-xs text-sky-400/80 mt-0.5">⚡ Nhận code kích hoạt kích hoạt trực tiếp</p>
                    </div>

                    <div class="flex items-center space-x-4 w-full md:w-auto justify-end">
                        <div class="bg-[#101822] px-3 py-1.5 rounded-sm flex items-center">
                            @if($version->discount_price)
                                @php 
                                    $percent = round((($version->price - $version->discount_price) / $version->price) * 100);
                                @endphp
                                <span class="bg-[#4c6b22] text-[#beee11] font-bold text-xs px-2 py-1 rounded-xs mr-3">-{{ $percent }}%</span>
                                <div class="text-right">
                                    <p class="text-[10px] text-[#556772] line-through leading-none mb-1">{{ number_format($version->price, 0, ',', '.') }}đ</p>
                                    <p class="text-[#beee11] text-sm font-bold leading-none">{{ number_format($version->discount_price, 0, ',', '.') }}đ</p>
                                </div>
                            @else
                                <span class="text-white text-sm font-semibold px-2">{{ number_format($version->price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>

                  <form action="{{ url('/cart/add/' . $version->id) }}" method="POST">
    @csrf
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="bg-gradient-to-r from-[#75b022] to-[#588a1b] hover:from-[#8ed629] hover:to-[#6aa720] text-white text-xs font-semibold px-5 py-2.5 rounded-xs shadow-md transition-all uppercase tracking-wider">
        Thêm Vào Giỏ
    </button>
</form>
                    </div>
                </div>
            @empty
                <div class="p-4 bg-[#171a21] text-center text-[#8f98a0] text-xs border border-dashed border-[#2a475e]/40 rounded-sm">
                    Trò chơi này hiện đang tạm hết hàng các gói phiên bản kích hoạt. Vui lòng quay lại sau!
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection