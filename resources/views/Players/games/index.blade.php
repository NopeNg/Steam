@extends('Players.layouts.app')

@section('title', 'Cửa Hàng - Danh Sách Trò Chơi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    
    <div class="space-y-4">
        <div class="bg-[#171a21] p-4 rounded-sm border border-[#2a475e]/20 shadow-xl">
            <h3 class="text-white text-sm uppercase font-semibold tracking-wider mb-4">Bộ lọc tìm kiếm</h3>
            
            <form action="{{ url('/games') }}" method="GET" class="space-y-4">
                <div>
                    <label class="text-xs text-[#8f98a0] block mb-1">Từ khóa</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập tên game..." 
                           class="w-full bg-[#101822] text-white text-sm px-3 py-2 rounded-sm border border-[#2a475e]/50 focus:outline-none focus:border-sky-400 transition-all">
                </div>

                <div>
                    <label class="text-xs text-[#8f98a0] block mb-1">Thể loại</label>
                    <select name="category" class="w-full bg-[#101822] text-[#8f98a0] text-sm px-3 py-2 rounded-sm border border-[#2a475e]/50 focus:outline-none focus:border-sky-400 transition-all">
                        <option value="">-- Tất cả thể loại --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold py-2 rounded-sm shadow-md transition-all">
                    Áp Dụng Bộ Lọc
                </button>
                
                @if(request('search') || request('category'))
                    <a href="{{ url('/games') }}" class="block text-center text-xs text-rose-400 hover:underline mt-2">
                        Xóa tất cả bộ lọc
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="lg:col-span-3 space-y-6">
        <h2 class="text-white text-lg font-bold border-b border-[#2a475e]/30 pb-2 flex justify-between items-center">
            <span>Kết Quả Tìm Kiếm</span>
            <span class="text-xs text-[#8f98a0] font-normal">Tìm thấy {{ $games->total() }} trò chơi</span>
        </h2>

        @if($games->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($games as $game)
                    @php 
                        // Lấy ra gói phiên bản có giá rẻ nhất để hiển thị làm giá đại diện
                        $lowestPriceVersion = $game->versions->sortBy('price')->first(); 
                    @endphp
                    <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" 
                         class="bg-[#171a21] hover:bg-[#1f3044] rounded-sm overflow-hidden border border-[#2a475e]/20 hover:border-[#3b6484]/50 shadow-lg cursor-pointer transition-all duration-300 group">
                        
                        <div class="h-40 overflow-hidden relative">
                            <img src="{{ $game->cover_image }}" alt="{{ $game->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                        </div>

                        <div class="p-4 space-y-2 flex flex-col justify-between h-32">
                            <div>
                                <h3 class="text-white text-sm font-semibold truncate group-hover:text-sky-400 transition-colors">{{ $game->name }}</h3>
                                <p class="text-[11px] text-[#8f98a0]">Phát hành: {{ \Carbon\Carbon::parse($game->release_date)->format('d/m/Y') }}</p>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-[#2a475e]/20">
                                @if($lowestPriceVersion)
                                    @if($lowestPriceVersion->discount_price)
                                        @php 
                                            $percent = round((($lowestPriceVersion->price - $lowestPriceVersion->discount_price) / $lowestPriceVersion->price) * 100);
                                        @endphp
                                        <span class="bg-[#4c6b22] text-[#beee11] font-bold text-[10px] px-1.5 py-0.5 rounded-xs">-{{ $percent }}%</span>
                                        <div class="text-right">
                                            <p class="text-[10px] text-[#556772] line-through leading-none mb-1">{{ number_format($lowestPriceVersion->price, 0, ',', '.') }}đ</p>
                                            <p class="text-white text-xs font-semibold leading-none">{{ number_format($lowestPriceVersion->discount_price, 0, ',', '.') }}đ</p>
                                        </div>
                                    @else
                                        <span></span>
                                        <p class="text-white text-xs font-semibold">{{ number_format($lowestPriceVersion->price, 0, ',', '.') }}đ</p>
                                    @endif
                                @else
                                    <span></span>
                                    <p class="text-amber-400 text-xs font-medium">Chưa có giá</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-6">
                {{ $games->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-[#171a21] rounded-sm border border-dashed border-[#2a475e]/40">
                <p class="text-[#8f98a0] text-sm">Không tìm thấy trò chơi nào phù hợp với bộ lọc hiện tại.</p>
            </div>
        @endif
    </div>
</div>
@endsection