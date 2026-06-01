@extends('Players.layouts.app')

@section('title', 'Cửa Hàng Steam Key - Trang Chủ')

@section('content')
<div class="space-y-10">

    @if($games->count() > 0)
        @php 
            $featuredGame = $games->first(); 
            // Lấy ra phiên bản có giá thấp nhất để làm giá đại diện hiển thị
            $featuredVersion = $featuredGame->versions->sortBy('price')->first();
        @endphp
        <section>
            <h2 class="text-[#fff] text-sm uppercase tracking-widest mb-3 font-semibold">Tâm điểm chú ý</h2>
            <div class="bg-[#101822] grid grid-cols-1 md:grid-cols-3 shadow-2xl rounded-sm overflow-hidden border border-[#2a475e]/30">
                <div class="md:col-span-2 relative h-64 md:h-96">
                    <img src="{{ $featuredGame->cover_image }}" class="w-full h-full object-cover" alt="{{ $featuredGame->name }}">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-[#101822] to-transparent p-6 pt-20">
                        <span class="bg-sky-500 text-white text-[10px] uppercase font-bold px-2 py-0.5 rounded-sm">Tâm điểm</span>
                        <h3 class="text-white text-2xl font-bold mt-1">{{ $featuredGame->name }}</h3>
                    </div>
                </div>
                <div class="p-6 flex flex-col justify-between bg-[#171a21]">
                    <div>
                        <h4 class="text-white text-lg font-bold mb-2">Đã có sẵn trên hệ thống</h4>
                        <p class="text-xs text-[#8f98a0] mb-4 leading-relaxed line-clamp-4">{{ $featuredGame->description }}</p>
                        
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($featuredGame->images->take(2) as $img)
                                <img src="{{ $img->image_path }}" class="w-full h-16 object-cover opacity-70 hover:opacity-100 transition rounded-xs" alt="screenshot">
                            @endforeach
                        </div>
                    </div>

                    @if($featuredVersion)
                    <div class="flex items-center justify-between bg-[#101822] p-2 rounded-sm mt-4">
                        <div class="flex items-center">
                            @if($featuredVersion->discount_price)
                                @php 
                                    $percent = round((($featuredVersion->price - $featuredVersion->discount_price) / $featuredVersion->price) * 100);
                                @endphp
                                <span class="bg-[#4c6b22] text-[#beee11] font-bold px-2 py-1 text-sm rounded-xs mr-2">-{{ $percent }}%</span>
                                <div class="text-xs">
                                    <p class="text-[#556772] line-through">{{ number_format($featuredVersion->price, 0, ',', '.') }}đ</p>
                                    <p class="text-white font-semibold text-sm">{{ number_format($featuredVersion->discount_price, 0, ',', '.') }}đ</p>
                                </div>
                            @else
                                <div class="text-xs">
                                    <p class="text-white font-semibold text-sm">{{ number_format($featuredVersion->price, 0, ',', '.') }}đ</p>
                                </div>
                            @endif
                        </div>
                        <a href="{{ url('/games/' . $featuredGame->id) }}" class="bg-gradient-to-r from-[#75b022] to-[#588a1b] hover:from-[#8ed629] hover:to-[#6aa720] text-white font-medium px-4 py-1.5 text-xs rounded-xs shadow-md transition-all">
                            Xem chi tiết
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-4">
            <div class="flex space-x-6 border-b border-[#2a475e]/40 pb-2">
                <button class="text-white font-bold text-sm border-b-2 border-sky-400 pb-2">Sản Phẩm Mới</button>
            </div>

            <div class="space-y-2">
                @foreach($games as $game)
                    @php $defaultVersion = $game->versions->sortBy('price')->first(); @endphp
                    <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" class="bg-[#16222f] hover:bg-[#1f3044] transition p-2 flex items-center justify-between rounded-sm border border-transparent hover:border-[#3b6484]/30 cursor-pointer">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $game->cover_image }}" class="w-24 h-12 object-cover rounded-xs" alt="{{ $game->name }}">
                            <div>
                                <h3 class="text-white text-sm font-semibold">{{ $game->name }}</h3>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($game->categories->take(2) as $cat)
                                        <span class="text-[10px] bg-[#2a475e]/60 text-sky-300 px-1.5 py-0.5 rounded-xs">{{ $cat->category_name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        @if($defaultVersion)
                        <div class="flex items-center bg-[#101822] rounded-xs p-1">
                            @if($defaultVersion->discount_price)
                                @php 
                                    $percent = round((($defaultVersion->price - $defaultVersion->discount_price) / $defaultVersion->price) * 100);
                                @endphp
                                <span class="bg-[#4c6b22] text-[#beee11] font-bold text-xs px-1.5 py-0.5 rounded-xs mr-2">-{{ $percent }}%</span>
                                <span class="text-white font-medium text-xs pr-2">{{ number_format($defaultVersion->discount_price, 0, ',', '.') }}đ</span>
                            @else
                                <span class="text-white font-medium text-xs px-3">{{ number_format($defaultVersion->price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="text-white text-sm uppercase tracking-wider font-semibold">Duyệt theo thể loại</h3>
            <div class="grid grid-cols-1 gap-2">
                @foreach($categories as $category)
                    <a href="{{ url('/games?category=' . $category->id) }}" class="bg-gradient-to-r from-[#213547] to-[#1b2838] hover:from-[#314f6a] hover:to-[#213547] p-3 text-sm font-semibold text-white rounded-sm border border-[#2a475e]/20 shadow-md flex justify-between items-center transition-all">
                        <span>🎮 {{ $category->category_name }}</span>
                        <i class="fa-solid fa-chevron-right text-xs text-sky-400"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

</div>
@endsection