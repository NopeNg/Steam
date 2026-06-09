<div class="space-y-2">
    @foreach($activeGames as $game)
        @php $defaultVersion = $game->versions->sortBy('price')->first(); @endphp
        <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" class="bg-[#101a24] hover:bg-[#1c324a] transition-all duration-200 p-2.5 flex items-center justify-between rounded-sm cursor-pointer border border-[#233c51]/70 hover:border-[#67c1f5]/60 shadow-md hover:shadow-xl group">
            <div class="flex items-center space-x-4">
                <div class="w-24 h-12 flex-shrink-0 overflow-hidden rounded-xs border border-[#2a475e]/40 group-hover:border-[#67c1f5]/40 transition-all">
                    <img src="{{ $game->cover_image }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-300" alt="{{ $game->name }}">
                </div>
                <div>
                    <h3 class="text-[#c7d5e0] group-hover:text-white text-sm font-bold transition-colors tracking-tight">{{ $game->name }}</h3>
                    <p class="text-[10px] text-[#4c97c5] mt-0.5 font-medium">{{ $game->categories->pluck('category_name')->implode(', ') }}</p>
                </div>
            </div>
            @if($defaultVersion)
                <div class="flex items-center space-x-2 bg-[#171a21]/90 px-2 py-1 rounded-xs border border-[#2a475e]/30 group-hover:border-[#3b6484]/60 transition-all min-w-[90px] justify-end">
                    @if($defaultVersion->discount_price)
                        @php $percent = round((($defaultVersion->price - $defaultVersion->discount_price) / $defaultVersion->price) * 100); @endphp
                        <span class="bg-[#4c6b22] text-[#beee11] font-extrabold text-[10px] px-1 py-0.5 rounded-xs mr-1">-{{ $percent }}%</span>
                        <div class="text-right flex flex-col justify-center">
                            <span class="text-[9px] text-[#556772] line-through leading-none mb-0.5">{{ number_format($defaultVersion->price, 0, ',', '.') }}đ</span>
                            <span class="text-[#beee11] text-xs font-bold leading-none">{{ number_format($defaultVersion->discount_price, 0, ',', '.') }}đ</span>
                        </div>
                    @else
                        <span class="text-[#67c1f5] text-xs font-bold">{{ number_format($defaultVersion->price, 0, ',', '.') }}đ</span>
                    @endif
                </div>
            @endif
        </div>
    @endforeach
</div>