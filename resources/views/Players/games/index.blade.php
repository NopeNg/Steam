@extends('Players.layouts.app')

@section('title', 'Cửa Hàng - Danh Sách Trò Chơi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-[#171a21] p-6 rounded-lg shadow-xl border border-[#2a475e]/20">
                <h3 class="text-white text-xl font-bold tracking-wide mb-5">Bộ lọc tìm kiếm</h3>
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

                    <div>
                        <label class="text-xs text-[#8f98a0] block mb-1">Trạng thái game</label>
                        <select name="status" class="w-full bg-[#101822] text-[#8f98a0] text-sm px-3 py-2 rounded-sm border border-[#2a475e]/50 focus:outline-none focus:border-sky-400 transition-all">
                            <option value="Active" {{ $currentStatus == 'Active' ? 'selected' : '' }}>⚡ Sẵn sàng cấp Key</option>
                            <option value="Inactive" {{ $currentStatus == 'Inactive' ? 'selected' : '' }}>❌ Tạm hết hàng</option>
                            <option value="ComingSoon" {{ $currentStatus == 'ComingSoon' ? 'selected' : '' }}>⏳ Sắp ra mắt</option>
                            <option value="Archived" {{ $currentStatus == 'Archived' ? 'selected' : '' }}>🚫 Ngừng kinh doanh</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold py-2 rounded-sm shadow-md transition-all">
                        Áp Dụng Bộ Lọc
                    </button>
                    
                    @if(request('search') || request('category') || (request('status') && request('status') !== 'Active'))
                        <a href="{{ url('/games') }}" class="block text-center text-xs text-rose-400 hover:underline mt-2">
                            Xóa tất cả bộ lọc
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="lg:col-span-3 space-y-6">
            {{-- ====== BANNER GAME SẮP RA MẮT ====== --}}
            @if(isset($upcomingGames) && $upcomingGames->count() > 0)
                <div class="bg-gradient-to-r from-[#1a2236] via-[#1d2942] to-[#1a2236] border border-[#2a475e]/40 rounded-lg p-5 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-white text-lg font-bold flex items-center gap-2">
                            <span class="text-2xl">⏳</span> 
                            <span>Game Sắp Ra Mắt</span>
                        </h2>
                        <a href="{{ url('/games?status=ComingSoon') }}" class="text-xs text-sky-400 hover:text-sky-300 font-semibold uppercase tracking-wide">
                            Xem tất cả →
                        </a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($upcomingGames as $up)
                            <a href="{{ url('/games/' . $up->id) }}" class="group bg-[#0e1621] hover:bg-[#1a2638] rounded-md overflow-hidden border border-[#2a475e]/30 hover:border-sky-400/50 transition-all duration-300 block">
                                <div class="h-28 overflow-hidden relative">
                                    <img src="{{ asset($up->cover_image) }}" alt="{{ $up->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-500 opacity-80 group-hover:opacity-100">
                                    <div class="absolute top-1.5 right-1.5 bg-amber-500 text-black text-[9px] font-bold px-1.5 py-0.5 rounded">
                                        SOON
                                    </div>
                                </div>
                                <div class="p-2">
                                    <h3 class="text-white text-xs font-semibold truncate group-hover:text-sky-400">{{ $up->name }}</h3>
                                    <p class="text-[10px] text-amber-400 mt-0.5">
                                        <i class="far fa-calendar-alt"></i> 
                                        @if($up->release_date)
                                            {{ \Carbon\Carbon::parse($up->release_date)->format('d/m/Y') }}
                                        @else
                                            Sắp công bố
                                        @endif
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div id="games-list-container">
                <h2 class="text-white text-lg font-bold border-b border-[#2a475e]/30 pb-2 flex justify-between items-center">
                    <span>Kết Quả Tìm Kiếm</span>
                    <span class="text-xs text-[#8f98a0] font-normal">Tìm thấy {{ $games->total() }} trò chơi</span>
                </h2>

            @if($games->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($games as $game)
                        @php 
                            $lowestPriceVersion = $game->versions->sortBy('price')->first(); 
                        @endphp
                        <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" 
                             class="bg-[#171a21] hover:bg-[#1f3044] rounded-sm overflow-hidden border border-[#2a475e]/20 hover:border-[#3b6484]/50 shadow-lg cursor-pointer transition-all duration-300 group">
                            
                            <div class="h-40 overflow-hidden relative">
                                <img src="{{ asset($game->cover_image) }}" alt="{{ $game->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                            </div>

                            <div class="p-4 space-y-2 flex flex-col justify-between h-32">
                                <div>
                                    <h3 class="text-white text-sm font-semibold truncate group-hover:text-sky-400 transition-colors">{{ $game->name }}</h3>
                                    <p class="text-[11px] text-[#8f98a0]">Phát hành: {{ \Carbon\Carbon::parse($game->release_date)->format('d/m/Y') }}</p>
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-[#2a475e]/20">
                                    @if($lowestPriceVersion && $lowestPriceVersion->price > 0)
                                        @if($lowestPriceVersion->discount_price)
                                            @php $percent = round((($lowestPriceVersion->price - $lowestPriceVersion->discount_price) / $lowestPriceVersion->price) * 100); @endphp
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

                <div class="bootstrap-pagination mt-5 pt-4 border-top border-secondary">
                    @if ($games->hasPages())
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center m-0">
                                {{-- Nút Quay lại --}}
                                <li class="page-item {{ $games->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $games->previousPageUrl() }}">&laquo;</a>
                                </li>
                                
                                {{-- Các số trang --}}
                                @foreach ($games->getUrlRange(1, $games->lastPage()) as $page => $url)
                                    <li class="page-item {{ ($page == $games->currentPage()) ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                                
                                {{-- Nút Tiếp theo --}}
                                <li class="page-item {{ !$games->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $games->nextPageUrl() }}">&raquo;</a>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            @else
                <div class="text-center py-12 bg-[#171a21] rounded-sm border border-dashed border-[#2a475e]/40">
                    <p class="text-[#8f98a0] text-sm">Không tìm thấy trò chơi nào phù hợp với bộ lọc hiện tại.</p>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tự động tải ngầm dữ liệu danh sách game kèm theo các tham số tìm kiếm, bộ lọc
        setInterval(async () => {
            const currentQuery = window.location.search || '';
            try {
                const response = await fetch('/api/games-list-updates' + currentQuery);
                if (response.ok) {
                    const data = await response.json();
                    if (data.games_grid_html) {
                        document.getElementById('games-list-container').innerHTML = data.games_grid_html;
                    }
                }
            } catch (error) {
                console.error('Lỗi tải dữ liệu danh sách:', error);
            }
        }, 10000); // Cập nhật ngầm mỗi 10 giây
    });
</script>
@endsection

<style>
    /* Ép buộc phân trang hiển thị ngang và căn giữa */
    .bootstrap-pagination .pagination {
        display: flex !important;
        flex-direction: row !important;
        justify-content: center !important;
        list-style: none !important;
        padding-left: 0 !important;
    }
    
    /* Tùy chỉnh màu sắc nút */
    .bootstrap-pagination .page-link {
        background-color: #171a21 !important;
        border: 1px solid #2a475e !important;
        color: #8f98a0 !important;
        padding: 0.5rem 0.75rem !important;
        text-decoration: none !important;
    }
    
    .bootstrap-pagination .page-item.active .page-link {
        background-color: #0ea5e9 !important;
        border-color: #0ea5e9 !important;
        color: #ffffff !important;
        font-weight: bold !important;
    }

    .bootstrap-pagination .page-item.disabled .page-link {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
    }
</style>