@php
    $gameKey   = $item->gameKey;
    $orderItem = $gameKey ? $gameKey->orderItem : null;
    $version   = $orderItem ? $orderItem->version : null;
    $game      = $version ? $version->game : null;
    $order     = $orderItem ? $orderItem->order : null;
    $type      = $order ? $order->order_type : 'Personal';
    
    // Logic mới: Kiểm tra game đã từng được Active trong tài khoản chưa
    $gameId    = $version ? $version->game_id : null;
    $isOwned   = ($gameId && isset($activatedGameIds) && in_array($gameId, $activatedGameIds));
    $isActivated = ($gameKey && $gameKey->status === 'Activated');
@endphp

@if($game)
    <div class="bg-[#171a21] border border-gray-800 p-3 rounded-sm text-xs flex flex-col justify-between transition-all hover:border-gray-600">
        <div>
            <img src="{{ $game->cover_image ?? 'https://via.placeholder.com/300x150' }}" 
                 class="w-full h-28 object-cover rounded-xs" alt="{{ $game->name }}">
            
            <div class="mt-2 flex items-center justify-between">
                <h3 class="text-white font-bold text-sm truncate">{{ $game->name }}</h3>
                <span class="text-[9px] px-1.5 py-0.5 rounded border 
                    {{ $type === 'Gift' ? 'bg-blue-600/20 text-blue-400 border-blue-600/30' : 
                       ($type === 'Other' ? 'bg-amber-600/20 text-amber-400 border-amber-600/30' : 
                       'bg-emerald-600/20 text-emerald-400 border-emerald-600/30') }}">
                    {{ strtoupper($type) }}
                </span>
            </div>
            @if($version)
                <p class="text-[10px] text-sky-400">{{ $version->version_name }}</p>
            @endif
        </div>

        <div class="bg-[#101822] p-2 rounded-xs flex justify-between items-center mt-3">
            <span class="text-gray-500">Trạng thái:</span>
            
            @if($isActivated)
                <span class="text-green-500 font-bold text-[11px]">ĐÃ KÍCH HOẠT</span>
            @else
                <div class="flex items-center space-x-2">
                    {{-- CHỈ HIỆN NÚT ACTIVE NẾU CHƯA TỪNG SỞ HỮU BẢN QUYỀN GAME ĐÓ --}}
                    @if(!$isOwned)
                        <form action="{{ route('library.activate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="key_code" value="{{ $gameKey->key_code }}">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-2 py-1 font-bold rounded-xs uppercase text-[10px] transition-colors">
                                ACTIVE
                            </button>
                        </form>
                    @else
                        <span class="text-[9px] text-orange-500 font-bold uppercase italic">Đã sở hữu</span>
                    @endif

                    {{-- Nút XEM KEY luôn hiện để người dùng quản lý mã --}}
                    <div class="relative">
                        <button onclick="toggleKey(this)" class="bg-sky-600 hover:bg-sky-500 text-white px-2 py-1 font-bold rounded-xs uppercase text-[10px] transition-colors">
                            XEM KEY
                        </button>
                        <div class="key-box absolute bottom-full right-0 mb-2 w-48 bg-[#1b2838] border border-sky-500 p-2 rounded shadow-xl z-50 hidden">
                            <p class="text-[10px] text-gray-400 mb-1">Mã kích hoạt:</p>
                            <code class="text-white font-mono text-[11px] block bg-black/50 p-1 cursor-text select-all">
                                {{ $gameKey->key_code }}
                            </code>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif

<script>
// Đảm bảo hàm toggle không bị định nghĩa lại nhiều lần
if (typeof toggleKey !== 'function') {
    function toggleKey(btn) {
        const box = btn.nextElementSibling;
        box.classList.toggle('hidden');
    }
}
</script>