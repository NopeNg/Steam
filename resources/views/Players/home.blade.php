@extends('Players.layouts.app') 

@section('title', 'Cửa Hàng Steam Key - Trang Chủ')

@section('content')
<div class="space-y-12">

    @if($games->count() > 0)
        @php 
            $featuredGame = $games->first(); 
            $featuredVersion = $featuredGame->versions->sortBy('price')->first();
        @endphp
        <section>
            <h2 class="text-[#c7d5e0] text-sm font-semibold uppercase tracking-widest mb-4 border-l-4 border-sky-500 pl-3">Tâm điểm chú ý</h2>
            <div class="bg-[#171a21] grid grid-cols-1 md:grid-cols-3 shadow-lg border border-[#2a475e]/30 rounded-sm overflow-hidden">
                <div class="md:col-span-2 relative h-64 md:h-96">
                    <img src="{{ $featuredGame->cover_image }}" class="w-full h-full object-cover" alt="{{ $featuredGame->name }}">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-[#171a21] to-transparent p-6 pt-24">
                        <span class="bg-sky-500 text-white text-[10px] uppercase font-bold px-2 py-0.5 rounded-sm">Featured</span>
                        <h3 class="text-white text-3xl font-bold mt-2">{{ $featuredGame->name }}</h3>
                    </div>
                </div>
                <div class="p-6 flex flex-col justify-between bg-[#171a21]">
                    <div>
                        <p class="text-xs text-[#8f98a0] mb-4 leading-relaxed line-clamp-4">{{ $featuredGame->description }}</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($featuredGame->images->take(2) as $img)
                                <img src="{{ $img->image_path }}" class="w-full h-16 object-cover border border-[#2a475e] opacity-80 hover:opacity-100 transition rounded-xs" alt="screenshot">
                            @endforeach
                        </div>
                    </div>

                    @if($featuredVersion)
                    <div class="flex items-center justify-between bg-[#101822] p-3 rounded-sm mt-4">
                        <div class="text-white font-semibold">
                            @if($featuredVersion->discount_price)
                                <span class="text-[#4c6b22] text-xs px-1.5 py-0.5 rounded-sm border border-[#4c6b22] mr-2">-{{ round((($featuredVersion->price - $featuredVersion->discount_price) / $featuredVersion->price) * 100) }}%</span>
                                <span class="text-[#8f98a0] line-through text-xs mr-2">{{ number_format($featuredVersion->price, 0, ',', '.') }}đ</span>
                                {{ number_format($featuredVersion->discount_price, 0, ',', '.') }}đ
                            @else
                                {{ number_format($featuredVersion->price, 0, ',', '.') }}đ
                            @endif
                        </div>
                        <a href="{{ url('/games/' . $featuredGame->id) }}" class="bg-[#5c7e10] hover:bg-[#75b022] text-white text-xs px-4 py-1.5 rounded-sm transition">Xem chi tiết</a>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <h2 class="text-white text-sm font-semibold uppercase mb-4 pb-2 border-b border-[#2a475e]">Sản Phẩm Mới</h2>
            <div class="space-y-2">
                @foreach($games as $game)
                    @php $defaultVersion = $game->versions->sortBy('price')->first(); @endphp
                    <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" class="bg-[#1b2838] hover:bg-[#2a475e]/30 transition p-2 flex items-center justify-between rounded-sm cursor-pointer border border-transparent hover:border-[#3b6484]/50">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $game->cover_image }}" class="w-20 h-10 object-cover rounded-xs" alt="{{ $game->name }}">
                            <div>
                                <h3 class="text-white text-sm font-medium">{{ $game->name }}</h3>
                                <p class="text-[10px] text-[#4c97c5]">{{ $game->categories->pluck('category_name')->implode(', ') }}</p>
                            </div>
                        </div>
                        @if($defaultVersion)
                            <div class="text-white text-xs font-medium">
                                {{ number_format($defaultVersion->discount_price ?? $defaultVersion->price, 0, ',', '.') }}đ
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h3 class="text-white text-sm font-semibold uppercase mb-4 pb-2 border-b border-[#2a475e]">Duyệt theo thể loại</h3>
            <div class="grid grid-cols-1 gap-2">
                @foreach($categories as $category)
                    <a href="{{ url('/games?category=' . $category->id) }}" class="bg-[#171a21] hover:bg-[#20252e] p-3 text-sm text-[#c7d5e0] hover:text-white rounded-sm border border-[#2a475e]/30 flex justify-between items-center transition">
                        <span>{{ $category->category_name }}</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-gray-500"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

</div>
@endsection