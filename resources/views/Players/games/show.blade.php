@extends('Players.layouts.app')

@section('title', $game->name . ' - Chi Tiết Trò Chơi')

@section('content')
    <style>
        #steam-thumb-container::-webkit-scrollbar {
            display: none;
            /* Khóa scrollbar trên Chrome, Safari, Edge */
        }

        #steam-thumb-container {
            -ms-overflow-style: none;
            /* Khóa scrollbar trên IE/Edge cũ */
            scrollbar-width: none;
            /* Khóa scrollbar trên Firefox */
        }
    </style>

    <div class="space-y-6">
        <h1 class="text-white text-2xl font-bold tracking-tight">{{ $game->name }}</h1>

        <div
            class="grid grid-cols-1 lg:grid-cols-3 gap-6 bg-[#171a21] p-4 rounded-sm border border-[#2a475e]/20 shadow-2xl">

            {{-- BÊN TRÁI: BỘ SƯU TẬP ẢNH SLIDER --}}
            <div class="lg:col-span-2 space-y-3">

                {{-- Khung ảnh chính --}}
                <div class="h-64 md:h-96 rounded-sm overflow-hidden shadow-inner relative group select-none bg-black">
                    <img id="main-game-image" src="{{ $game->cover_image }}" alt="{{ $game->name }}"
                        class="w-full h-full object-cover transition-all duration-500 ease-in-out">

                    <button onclick="prevImage()"
                        class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-[#2a475e] text-white p-2 rounded-sm border border-[#2a475e]/40 transition-all opacity-0 group-hover:opacity-100 flex items-center justify-center w-10 h-12 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>

                    <button onclick="nextImage()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-[#2a475e] text-white p-2 rounded-sm border border-[#2a475e]/40 transition-all opacity-0 group-hover:opacity-100 flex items-center justify-center w-10 h-12 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>

                {{-- Gom mảng ảnh từ Laravel để truyền cho file JS chạy bằng Vite xử lý --}}
                @php
                    $allImages = array_merge([$game->cover_image], $game->images->pluck('image_path')->toArray());
                @endphp

                {{-- Vùng Thumbnails & Thanh cuộn mượt --}}
                <div class="bg-black/30 p-1.5 rounded-sm select-none" id="game-gallery-wrapper"
                    data-images="{{ json_encode($allImages) }}">

                    <div class="flex items-center space-x-2 overflow-x-auto py-1" id="steam-thumb-container">
                        <img src="{{ $game->cover_image }}" alt="main cover"
                            class="screenshot-thumbnail flex-shrink-0 w-24 h-14 object-cover border-2 border-white opacity-100 transition-all cursor-pointer rounded-xs shadow-md"
                            onclick="jumpToImage(0)">

                        @foreach($game->images as $img)
                            <img src="{{ $img->image_path }}" alt="screenshot"
                                class="screenshot-thumbnail flex-shrink-0 w-24 h-14 object-cover border-2 border-transparent opacity-70 hover:opacity-100 transition-all cursor-pointer rounded-xs"
                                onclick="jumpToImage({{ $loop->iteration }})">
                        @endforeach
                    </div>

                    <div
                        class="mt-2 flex items-center justify-between bg-[#233c51]/10 h-5 rounded-xs border border-[#233c51]/20 px-2 text-[10px] text-[#67c1f5]">
                        <button onclick="scrollThumbsHandled('left')"
                            class="hover:text-white transition p-1 select-none">◀</button>

                        <div id="steam-slider-track"
                            class="flex-1 h-2 bg-[#101822] mx-4 rounded-xs relative cursor-pointer">
                            <div id="steam-slider-handle"
                                class="absolute left-0 top-0 bottom-0 w-16 bg-[#233c51] hover:bg-[#315371] active:bg-[#416e94] rounded-xs cursor-grab active:cursor-grabbing transition-shadow duration-150">
                            </div>
                        </div>

                        <button onclick="scrollThumbsHandled('right')"
                            class="hover:text-white transition p-1 select-none">▶</button>
                    </div>
                </div>

            </div>

            {{-- BÊN PHẢI: THÔNG TIN TỔNG QUAN GAME --}}
            <div class="flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <p class="text-xs text-[#8f98a0] leading-relaxed line-clamp-6">
                        {{ $game->description }}
                    </p>

                    <div class="text-xs space-y-1.5 pt-3 border-t border-[#2a475e]/20">
                        <p class="text-[#556772]">Ngày phát hành: <span
                                class="text-[#8f98a0]">{{ \Carbon\Carbon::parse($game->release_date)->format('d/m/Y') }}</span>
                        </p>
                        <p class="text-[#556772]">Nhà phát triển: <span
                                class="text-[#8f98a0]">{{ $game->developer ?? 'Đang cập nhật' }}</span></p>
                        <p class="text-[#556772]">Nhà phát hành: <span
                                class="text-[#8f98a0]">{{ $game->publisher ?? 'Đang cập nhật' }}</span></p>
                        <p class="text-[#556772]">Trạng thái Game:
                            @if($game->status == 'Active')
                                <span class="text-emerald-400 font-semibold">⚡ Sẵn sàng cấp Key</span>
                            @elseif($game->status == 'Inactive')
                                <span class="text-rose-400 font-semibold">❌ Tạm hết hàng</span>
                            @elseif($game->status == 'ComingSoon')
                                <span class="text-sky-400 font-semibold">⏳ Sắp ra mắt</span>
                            @elseif($game->status == 'Archived')
                                <span class="text-gray-400 font-semibold">🚫 Ngừng kinh doanh</span>
                            @else
                                <span class="text-amber-400 font-semibold">⚙️ Đang cập nhật</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="text-[11px] text-[#4c97c5] bg-[#101822]/40 p-2 rounded-xs border border-[#2a475e]/10">
                    🌐 Sản phẩm là Key kích hoạt kỹ thuật số toàn cầu.
                </div>
            </div>
        </div>

        {{-- KHU VỰC CHỌN PHIÊN BẢN MUA SẢN PHẨM --}}
        <div class="space-y-4">
            <h2 class="text-white text-sm uppercase tracking-widest font-bold">Chọn phiên bản mua sản phẩm</h2>

            <div class="space-y-3">
                @forelse($game->versions as $version)
                            <div
                                class="bg-gradient-to-r from-[#1b2838] to-[#16222f] border border-[#2a475e]/40 rounded-sm p-4 flex flex-col md:flex-row justify-between items-center shadow-md hover:border-[#3b6484]/60 transition-all">

                                <div class="mb-4 md:mb-0 space-y-1.5">
                                    <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                        <h3 class="text-white text-base font-bold">{{ $version->version_name }}</h3>
                                    </div>

                                    <p class="text-xs text-sky-400/80 mt-0.5">
                                        @if($game->status == 'Active')
                                            ⚡ Nhận code kích hoạt trực tiếp
                                        @else
                                            🔒 Phiên bản này hiện chưa thể mua
                                        @endif
                                    </p>
                                </div>

                    <div class="flex items-center space-x-4 w-full md:w-auto justify-end">
                      <div class="bg-[#101822] px-3 py-1.5 rounded-sm flex items-center">
    <?php 
        // 1. Tính toán phần trăm giảm giá an toàn (tránh chia cho 0)
        $hasDiscount = !empty($version->discount_price);
        $percent = ($hasDiscount && $version->price > 0) 
            ? round((($version->price - $version->discount_price) / $version->price) * 100) 
            : 0;
    ?>

    <?php if ($hasDiscount): ?>
        <span class="bg-[#4c6b22] text-[#beee11] font-bold text-xs px-2 py-1 rounded-xs mr-3">-<?php echo $percent; ?>%</span>
        <div class="text-right">
            <p class="text-[10px] text-[#556772] line-through leading-none mb-1"><?php echo number_format($version->price, 0, ',', '.'); ?>đ</p>
            <p class="text-[#beee11] text-sm font-bold leading-none"><?php echo number_format($version->discount_price, 0, ',', '.'); ?>đ</p>
        </div>
    <?php elseif($version->price == 0): ?>
        <span class="bg-green-500/20 text-green-400 text-xs font-bold px-3 py-1.5 rounded border border-green-500/30">MIỄN PHÍ</span>
    <?php else: ?>
        <span class="text-white text-sm font-semibold px-2"><?php echo number_format($version->price, 0, ',', '.'); ?>đ</span>
    <?php endif; ?>
</div>

                                    @if($game->status == 'Active')
                                        <form action="{{ url('/cart/add/' . $version->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                class="bg-[#6fa127] hover:bg-[#82bd2d] text-[#e5f4d2] text-xs font-bold px-5 py-2 border border-black rounded-[2px] shadow-[inset_0_1px_0_rgba(255,255,255,0.2)] transition-all duration-200"
                                                style="text-shadow: 1px 1px 0px rgba(0,0,0,0.6);">
                                                Thêm Vào Giỏ
                                            </button>
                                        </form>
                                    @else
                                        <button disabled
                                            class="bg-[#2a3f54]/40 text-[#556772] text-xs font-bold px-5 py-2 border border-[#2a475e]/30 rounded-[2px] cursor-not-allowed select-none min-w-[115px] text-center">
                                            @if($game->status == 'Inactive') 🚫 Hết Hàng
                                            @elseif($game->status == 'ComingSoon') ⏳ Sắp Ra Mắt
                                            @elseif($game->status == 'Archived') 📦 Ngừng Bán
                                            @else ⚙️ Tạm Khóa
                                            @endif
                                        </button>
                                    @endif
                                </div>
                            </div>
                @empty
                    <div
                        class="p-4 bg-[#171a21] text-center text-[#8f98a0] text-xs border border-dashed border-[#2a475e]/40 rounded-sm">
                        Trò chơi này hiện đang tạm hết hàng các gói phiên bản kích hoạt. Vui lòng quay lại sau!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- KHU VỰC YÊU CẦU HỆ THỐNG --}}
        <div class="space-y-3 pt-2">
            <h2 class="text-white text-sm uppercase tracking-widest font-bold border-b border-[#2a475e]/30 pb-2">Yêu cầu hệ
                thống</h2>
            <div
                class="bg-[#171a21]/60 border border-[#2a475e]/20 rounded-sm p-4 text-xs text-[#8f98a0] leading-relaxed tracking-wide font-mono">
                @if($game->requirements)
                    {!! nl2br(e($game->requirements)) !!}
                @else
                    <p class="italic text-[#556772]">Nhà phát triển chưa cập nhật cấu hình chi tiết cho trò chơi này.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- NHÚNG FILE JAVASCRIPT RIÊNG THÔNG QUA VITE QUẢN LÝ --}}
    @vite(['resources/js/game-detail.js'])
@endsection