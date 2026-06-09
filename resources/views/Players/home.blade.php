@extends('Players.layouts.app') 

@section('title', 'Cửa Hàng Steam Key - Trang Chủ')

@section('content')
<div class="space-y-12">
    @if($activeGames->count() > 0 || $comingSoonGames->count() > 0)
        @php 
            $featuredGame = $activeGames->first() ?? $comingSoonGames->first(); 
            $featuredVersion = $featuredGame->versions->sortBy('price')->first();
        @endphp
        <section>
            <h2 class="text-[#67c1f5] text-sm font-bold uppercase tracking-widest mb-4 border-l-4 border-[#67c1f5] pl-3">Tâm điểm chú ý</h2>
            <div class="bg-[#1b2838] grid grid-cols-1 md:grid-cols-3 shadow-[0_10px_30px_rgba(0,0,0,0.7)] border border-[#3b6484]/50 rounded-sm overflow-hidden transition-all duration-300">
                <div class="md:col-span-2 relative h-64 md:h-96 group overflow-hidden">
                    <img src="{{ asset($featuredGame->cover_image) }}" class="w-full h-full object-cover transform group-hover:scale-102 transition duration-700" alt="{{ $featuredGame->name }}">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-[#1b2838] via-[#1b2838]/70 to-transparent p-6 pt-24">
                        <span class="bg-gradient-to-r from-sky-600 to-sky-500 text-white text-[10px] uppercase font-extrabold px-2 py-0.5 rounded-xs tracking-wider shadow-sm">Featured</span>
                        <h3 class="text-white text-3xl font-extrabold mt-2 tracking-tight style-shadow" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">{{ $featuredGame->name }}</h3>
                    </div>
                </div>
                <div class="p-6 flex flex-col justify-between bg-[#10161d] border-l border-[#2a475e]/30">
                    <div>
                        <p class="text-xs text-[#8f98a0] mb-5 leading-relaxed line-clamp-5">{{ $featuredGame->description }}</p>
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#2a475e]/20">
                            @foreach($featuredGame->images->take(2) as $img)
                                <div class="h-16 rounded-xs overflow-hidden border border-[#2a475e] bg-black">
                                    <img src="{{ asset($img->image_path) }}" class="w-full h-full object-cover opacity-70 hover:opacity-100 transition duration-300" alt="screenshot">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if($featuredVersion)
                    <div class="flex items-center justify-between bg-[#171a21] p-2.5 rounded-sm mt-6 border border-[#2a475e]/40 shadow-inner">
                        <div class="text-white font-bold text-sm flex items-center">
                            @if($featuredVersion->discount_price)
                                @php $percent = round((($featuredVersion->price - $featuredVersion->discount_price) / $featuredVersion->price) * 100); @endphp
                                <span class="bg-[#4c6b22] text-[#beee11] font-extrabold text-xs px-2 py-1 rounded-xs mr-2 shadow-sm">-{{ $percent }}%</span>
                                <div class="text-right flex flex-col justify-center mr-1">
                                    <span class="text-[10px] text-[#556772] line-through leading-none mb-0.5">{{ number_format($featuredVersion->price, 0, ',', '.') }}đ</span>
                                    <span class="text-[#beee11] text-sm font-extrabold leading-none">{{ number_format($featuredVersion->discount_price, 0, ',', '.') }}đ</span>
                                </div>
                            @else
                                <span class="text-[#67c1f5] font-extrabold">{{ number_format($featuredVersion->price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        <a href="{{ url('/games/' . $featuredGame->id) }}" class="bg-[#6fa127] hover:bg-[#82bd2d] text-[#e5f4d2] text-xs font-bold px-4 py-2 border border-black rounded-[2px] shadow-[inset_0_1px_0_rgba(255,255,255,0.2)] transition-all">Xem chi tiết</a>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <section class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-3">
            <h2 class="text-white text-sm font-semibold uppercase mb-4 pb-2 border-b border-[#2a475e] tracking-wider">Sản Phẩm Sắp Ra Mắt</h2>
            <div class="space-y-2" id="coming-soon-container">
                @foreach($comingSoonGames as $game)
                    <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" class="bg-[#101a24] hover:bg-[#1c324a] p-2.5 flex items-center justify-between rounded-sm cursor-pointer border border-[#2a475e]/30 group">
                        <div class="flex items-center space-x-4">
                            <div class="w-24 h-12 overflow-hidden rounded-xs border border-[#2a475e]/40">
                                <img src="{{ asset($game->cover_image) }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="text-[#c7d5e0] group-hover:text-white text-sm font-bold">{{ $game->name }}</h3>
                                <span class="text-[9px] bg-yellow-600/20 text-yellow-500 px-1 rounded uppercase font-bold">Coming Soon</span>
                            </div>
                        </div>
                        <div class="text-[10px] text-[#7d8b99] uppercase">Dự kiến: {{ \Carbon\Carbon::parse($game->release_date)->format('d/m/Y') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-1">
            <h3 class="text-white text-sm font-semibold uppercase mb-4 pb-2 border-b border-[#2a475e] tracking-wider">Top 10 Bán Chạy</h3>
            <div class="bg-[#101a24] rounded-sm border border-[#233c51]/70 p-2" id="top-selling-container">
                @foreach($topSellingGames as $index => $game)
                    <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" class="flex items-center p-2 hover:bg-[#1c324a] cursor-pointer rounded-xs border-b border-[#2a475e]/30 last:border-0">
                        <span class="text-[#556772] font-bold text-sm w-8">{{ $index + 1 }}</span>
                        <img src="{{ asset($game->cover_image) }}" class="w-12 h-8 object-cover rounded-xs mr-3">
                        <h4 class="text-[#c7d5e0] text-xs font-bold truncate">{{ $game->name }}</h4>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-3">
            <h2 class="text-white text-sm font-semibold uppercase mb-4 pb-2 border-b border-[#2a475e] tracking-wider">Sản Phẩm Mới</h2>
            <div class="space-y-2" id="active-games-container">
                @foreach($activeGames as $game)
                    @php $defaultVersion = $game->versions->sortBy('price')->first(); @endphp
                    <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" class="bg-[#101a24] hover:bg-[#1c324a] transition-all duration-200 p-2.5 flex items-center justify-between rounded-sm cursor-pointer border border-[#233c51]/70 hover:border-[#67c1f5]/60 shadow-md hover:shadow-xl group">
                        <div class="flex items-center space-x-4">
                            <div class="w-24 h-12 flex-shrink-0 overflow-hidden rounded-xs border border-[#2a475e]/40 group-hover:border-[#67c1f5]/40 transition-all">
                                <img src="{{ asset($game->cover_image) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-300" alt="{{ $game->name }}">
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
        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.sendAjaxRequest = async function(url) {
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Network response was not ok');
                return await response.json();
            } catch (error) {
                console.error('Lỗi tải dữ liệu ngầm:', error);
            }
        };

        // Cứ mỗi 10 giây (10000ms) sẽ tự động gọi API ngầm dưới nền
        setInterval(async () => {
            const response = await window.sendAjaxRequest('/api/home-updates');
            if (response) {
                if (response.coming_soon_html) {
                    document.getElementById('coming-soon-container').innerHTML = response.coming_soon_html;
                }
                if (response.active_games_html) {
                    document.getElementById('active-games-container').innerHTML = response.active_games_html;
                }
                if (response.top_selling_html) {
                    document.getElementById('top-selling-container').innerHTML = response.top_selling_html;
                }
                console.log('Cập nhật giao diện trang chủ thành công ngầm dưới nền!');
            }
        }, 10000);
    });
</script>
@endsection