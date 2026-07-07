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
                        @elseif($lowestPriceVersion && $lowestPriceVersion->price == 0)
                            <span class="bg-green-500/20 text-green-400 text-[10px] font-bold px-1.5 py-0.5 rounded border border-green-500/30">MIỄN PHÍ</span>
                            <p class="text-green-400 text-xs font-semibold">Miễn phí</p>
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