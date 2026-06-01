{{-- File: resources/views/Players/library/_game_card.blade.php --}}

@php
    // Chúng ta định nghĩa biến cục bộ từ đối tượng $item được truyền vào
    $gameKey   = $item->gameKey;
    $orderItem = $gameKey ? $gameKey->orderItem : null;
    $version   = $orderItem ? $orderItem->version : null;
    $game      = $version ? $version->game : null;
@endphp

@if($game)
    <div class="bg-[#171a21] border border-gray-800 p-3 rounded-sm text-xs space-y-3 flex flex-col justify-between">
        <div>
            <img src="{{ $game->cover_image ?? 'https://via.placeholder.com/300x150' }}" 
                 class="w-full h-28 object-cover rounded-xs" 
                 alt="{{ $game->name }}">
            
            <h3 class="text-white font-bold text-sm mt-2">
                {{ $game->name }} 
                @if($version)
                    <span class="text-[10px] text-sky-400 font-normal block">({{ $version->version_name }})</span>
                @endif
            </h3>
        </div>

        <div class="bg-[#101822] p-2 rounded-xs flex justify-between items-center mt-2">
            <span class="text-gray-500">Mã kích hoạt:</span>
            
            @if($gameKey && $gameKey->status !== 'Activated')
                <button onclick="alert('Mã kích hoạt của bạn là: {{ $gameKey->key_code }}')" 
                        class="bg-sky-600 hover:bg-sky-500 text-white px-2 py-1 font-bold rounded-xs uppercase tracking-wider transition-colors">
                    XEM KEY
                </button>
            @else
                <span class="text-green-500 font-bold text-[11px]">Đã kích hoạt</span>
            @endif
        </div>
    </div>
@endif