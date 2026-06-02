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
    $isPending = ($gameKey && $gameKey->status === 'Pending');
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
                <span class="text-green-500 font-bold text-[11px]">✓ ĐÃ KÍCH HOẠT</span>
            @elseif($isPending)
                <div class="flex items-center space-x-2">
                    @if(!$isOwned)
                        {{-- Nút KÍCH HOẠT mở modal hướng dẫn --}}
                        <button onclick="openActivateModal('{{ $gameKey->key_code }}', '{{ $game->name }}')" 
                                class="bg-amber-600 hover:bg-amber-500 text-white px-2 py-1 font-bold rounded-xs uppercase text-[10px] transition-colors">
                            KÍCH HOẠT
                        </button>
                    @else
                        <span class="text-[9px] text-orange-500 font-bold uppercase italic">Đã sở hữu</span>
                    @endif

                    {{-- Nút XEM KEY mở modal --}}
                    <button onclick="openKeyModal('{{ $gameKey->key_code }}', '{{ $gameKey->id }}')" class="bg-sky-600 hover:bg-sky-500 text-white px-2 py-1 font-bold rounded-xs uppercase text-[10px] transition-colors">
                        XEM KEY
                    </button>
                </div>
            @else
                <span class="text-gray-400 text-[11px]">{{ $gameKey->status ?? 'Unknown' }}</span>
            @endif
        </div>
    </div>

    {{-- Modal Xem Key --}}
    <div id="key-modal-{{ $gameKey->id }}" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="absolute inset-0 bg-black/70" onclick="closeKeyModal('{{ $gameKey->id }}')"></div>
        <div class="bg-[#1b2838] border border-sky-500 p-6 rounded-sm shadow-2xl max-w-sm w-full mx-4 relative z-10">
            <div class="text-center">
                <h3 class="text-sky-400 font-bold text-sm mb-3">Mã Kích Hoạt</h3>
                <div class="bg-black/50 border border-gray-700 p-3 rounded mb-3">
                    <code id="modal-key-{{ $gameKey->id }}" class="text-white font-mono text-xs block bg-gray-900 p-2 rounded cursor-text select-all break-all">
                        {{ $gameKey->key_code }}
                    </code>
                </div>
                <button onclick="copyKeyFromModal('{{ $gameKey->key_code }}', '{{ $gameKey->id }}')" 
                        class="bg-sky-600 hover:bg-sky-500 text-white px-4 py-1.5 rounded text-xs font-bold transition-colors">
                    <i class="fas fa-copy me-1"></i>Sao chép
                </button>
                <span id="modal-copy-msg-{{ $gameKey->id }}" class="text-green-400 text-xs ml-2 hidden">Đã sao chép!</span>
                <br>
                <button onclick="closeKeyModal('{{ $gameKey->id }}')" class="mt-3 text-gray-400 hover:text-white text-xs transition-colors">Đóng</button>
            </div>
        </div>
    </div>

    {{-- Modal Kích Hoạt --}}
    @if($isPending && !$isOwned)
    <div id="activate-modal-{{ $gameKey->id }}" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="absolute inset-0 bg-black/70" onclick="closeActivateModal('{{ $gameKey->id }}')"></div>
        <div class="bg-[#1b2838] border border-amber-500 p-6 rounded-sm shadow-2xl max-w-md w-full mx-4 relative z-10">
            <div class="text-center">
                <div class="mb-4 flex justify-center">
                    <i class="fas fa-key text-amber-500 text-4xl"></i>
                </div>
                <h2 class="text-amber-500 text-lg font-bold mb-2">Kích Hoạt Game Key</h2>
                <p class="text-[#c7d5e0] text-xs mb-4">
                    Game: <span class="text-white font-bold">{{ $game->name }}</span>
                </p>
                
                {{-- Hiển thị Key --}}
                <div class="bg-black/50 border border-gray-700 p-3 rounded mb-4">
                    <p class="text-gray-400 text-[10px] mb-1">Mã kích hoạt của bạn:</p>
                    <code id="key-code-{{ $gameKey->id }}" class="text-white font-mono text-sm block bg-gray-900 p-2 rounded cursor-text select-all break-all">
                        {{ $gameKey->key_code }}
                    </code>
                    <button onclick="copyKeyCode('{{ $gameKey->key_code }}', '{{ $gameKey->id }}')" 
                            class="mt-2 bg-sky-600 hover:bg-sky-500 text-white px-3 py-1 rounded text-[10px] font-bold transition-colors">
                        <i class="fas fa-copy me-1"></i>Sao chép Key
                    </button>
                    <span id="copy-msg-{{ $gameKey->id }}" class="text-green-400 text-[10px] ml-2 hidden">Đã sao chép!</span>
                </div>

                {{-- Hướng dẫn --}}
                <div class="bg-[#101822] border border-gray-700 p-3 rounded mb-4 text-left">
                    <p class="text-amber-400 font-bold text-xs mb-2"><i class="fas fa-info-circle me-1"></i>Hướng dẫn kích hoạt:</p>
                    <ol class="text-[#c7d5e0] text-[10px] space-y-1 list-decimal list-inside">
                        <li>Sao chép mã key ở trên</li>
                        <li>Mở game <strong class="text-white">{{ $game->name }}</strong> trên máy tính</li>
                        <li>Vào phần <strong class="text-white">Settings/Account</strong> của game</li>
                        <li>Tìm mục <strong class="text-white">"Redeem Code"</strong> hoặc <strong class="text-white">"Activate Key"</strong></li>
                        <li>Dán key vào và xác nhận</li>
                        <li>Sau khi kích hoạt thành công trong game, bấm nút bên dưới</li>
                    </ol>
                </div>

                {{-- Form xác nhận đã kích hoạt --}}
                <form action="{{ route('library.activate') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="hidden" name="key_code" value="{{ $gameKey->key_code }}">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2 px-4 rounded-sm transition text-xs">
                        <i class="fas fa-check me-1"></i>XÁC NHẬN ĐÃ KÍCH HOẠT TRONG GAME
                    </button>
                </form>
                
                <button onclick="closeActivateModal('{{ $gameKey->id }}')" 
                        class="mt-2 text-gray-400 hover:text-white text-[10px] transition-colors">
                    Đóng
                </button>
            </div>
        </div>
    </div>
    @endif
@endif

<script>
// Đảm bảo hàm toggle không bị định nghĩa lại nhiều lần
if (typeof toggleKey !== 'function') {
    function toggleKey(btn) {
        const box = btn.nextElementSibling;
        box.classList.toggle('hidden');
    }
}

if (typeof openActivateModal !== 'function') {
    function openActivateModal(keyCode, gameName) {
        // Tìm modal gần nhất
        const modals = document.querySelectorAll('[id^="activate-modal-"]');
        modals.forEach(m => {
            if (m.querySelector('code') && m.querySelector('code').textContent.trim() === keyCode.trim()) {
                m.classList.remove('hidden');
            }
        });
    }
}

if (typeof closeActivateModal !== 'function') {
    function closeActivateModal(gameKeyId) {
        const modal = document.getElementById('activate-modal-' + gameKeyId);
        if (modal) modal.classList.add('hidden');
    }
}

if (typeof copyKeyCode !== 'function') {
    function copyKeyCode(keyCode, gameKeyId) {
        navigator.clipboard.writeText(keyCode).then(() => {
            const msg = document.getElementById('copy-msg-' + gameKeyId);
            if (msg) {
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            }
        });
    }
}

if (typeof openKeyModal !== 'function') {
    function openKeyModal(keyCode, gameKeyId) {
        const modal = document.getElementById('key-modal-' + gameKeyId);
        if (modal) modal.classList.remove('hidden');
    }
}

if (typeof closeKeyModal !== 'function') {
    function closeKeyModal(gameKeyId) {
        const modal = document.getElementById('key-modal-' + gameKeyId);
        if (modal) modal.classList.add('hidden');
    }
}

if (typeof copyKeyFromModal !== 'function') {
    function copyKeyFromModal(keyCode, gameKeyId) {
        navigator.clipboard.writeText(keyCode).then(() => {
            const msg = document.getElementById('modal-copy-msg-' + gameKeyId);
            if (msg) {
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            }
        });
    }
}
</script>
