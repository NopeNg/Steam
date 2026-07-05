@php
    // Hỗ trợ cả Library model (có gameKey relationship) và GameKey model trực tiếp
    $gameKey   = $item->gameKey ?? (isset($item->status) ? $item : null);
    $orderItem = $gameKey ? $gameKey->orderItem : null;
    $version   = $orderItem ? $orderItem->version : null;
    $game      = $version ? $version->game : null;
    $order     = $orderItem ? $orderItem->order : null;
    $type      = $order ? $order->order_type : 'Giveaway';

    // Fallback: nếu Library có game/version riêng (cho Giveaway)
    if (!$game && $item->game) {
        $game = $item->game;
    }
    if (!$version && $item->gameVersion) {
        $version = $item->gameVersion;
    }
    // Fallback từ gameVersionId
    if (!$game && $gameKey && $gameKey->game_version_id) {
        $v = \App\Models\GameVersion::with('game')->find($gameKey->game_version_id);
        $version = $v;
        $game = $v ? $v->game : null;
    }

    $gameId    = $game ? $game->id : null;
    $isOwned   = ($gameId && isset($activatedGameIds) && in_array($gameId, $activatedGameIds));
    $isActivated = ($gameKey && $gameKey->status === 'Activated');
    $isPending = ($gameKey && $gameKey->status === 'Pending');
    $isGiveaway = ($gameKey && $gameKey->status === 'Giveaway');
    $isGifted  = ($gameKey && \App\Models\Gift::where('game_key_id', $gameKey->id)->where('sender_id', auth('player')->id())->whereIn('status', ['Sent', 'Accepted'])->exists());
    $isGiftBadge = $isGifted || $type === 'Gift';
    $badgeClass = $isGiftBadge ? 'bg-blue-600/80 text-white border-blue-600' : 
        ($type === 'Giveaway' ? 'bg-purple-600/80 text-white border-purple-600' :
        ($type === 'Other' ? 'bg-amber-600/80 text-white border-amber-600' : 
        'bg-emerald-600/80 text-white border-emerald-600'));
    $badgeText = $isGiftBadge ? 'GIFT' : ($type === 'Giveaway' ? 'SỰ KIỆN' : strtoupper($type));
    $versionName = $version ? $version->version_name : ($item->version_id ? 'Version #'.$item->version_id : 'N/A');
    $purchasedAt = $item->purchased_at ? \Carbon\Carbon::parse($item->purchased_at)->format('d/m/Y H:i') : ($order ? $order->created_at->format('d/m/Y H:i') : 'N/A');
@endphp

@if($game)
<div class="bg-[#171a21] border border-gray-800 rounded-sm overflow-hidden transition-all hover:border-gray-600 group {{ ($isGifted || $type === 'Gift') ? '' : 'cursor-pointer' }}" 
     onclick="{{ ($isGifted || $type === 'Gift') ? '' : ($gameKey->status === 'Revoked' ? 'openRevokeReasonModal(\''.$gameKey->id.'\')' : 'openKeyModal(\''.$gameKey->key_code.'\',\''.$gameKey->id.'\')') }}">
    {{-- Cover ảnh trên --}}
    <div class="h-36 overflow-hidden relative">
        <img src="{{ $game->cover_image ?? 'https://via.placeholder.com/300x150' }}" 
             class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500" alt="{{ $game->name }}">
    {{-- Badge góc trên phải --}}
    <span class="absolute top-2 right-2 text-[9px] px-1.5 py-0.5 rounded border font-bold {{ $badgeClass }}">
        {{ $badgeText }}
    </span>
    </div>

    {{-- Thông tin bên dưới --}}
    <div class="p-3 text-xs space-y-1.5">
        <h3 class="text-white font-bold text-sm truncate group-hover:text-sky-400 transition-colors">
            {{ $game->name }}
        </h3>
        <p class="text-[10px] text-sky-400">{{ $versionName }}</p>
        <p class="text-[10px] text-gray-500">Ngày mua: {{ $purchasedAt }}</p>
        
        <div class="border-t border-gray-800 pt-2 flex items-center justify-between">
            {{-- Trạng thái --}}
            @if($isActivated)
                <span class="text-green-500 font-bold text-[11px]">✓ ĐÃ KÍCH HOẠT</span>
            @elseif($isPending || $isGiveaway)
                <span class="text-amber-400 font-bold text-[11px]">⏳ CHỜ KÍCH HOẠT</span>
            @elseif($gameKey->status === 'Revoked')
                <span class="text-red-500 font-bold text-[11px]">✕ ĐÃ THU HỒI</span>
            @else
                <span class="text-gray-400 text-[11px]">{{ $gameKey->status ?? 'Unknown' }}</span>
            @endif

            {{-- Chỉ hiển thị nút KÍCH HOẠT khi cần - ẩn nếu là Gift --}}
            @if(($isPending || $isGiveaway) && !$isOwned && !$isGifted && $type !== 'Gift')
                <button onclick="event.stopPropagation(); openActivateModal('{{ $gameKey->key_code }}', '{{ $game->name }}')" 
                        class="bg-amber-600 hover:bg-amber-500 text-white px-2 py-1 font-bold rounded-xs uppercase text-[10px] transition-colors">
                    KÍCH HOẠT
                </button>
            @elseif($isOwned)
                <span class="text-[9px] text-orange-500 font-bold uppercase italic">Đã sở hữu</span>
            @endif
        </div>
    </div>
</div>

{{-- Modal Lý do thu hồi --}}
<div id="revoke-reason-modal-{{ $gameKey->id }}" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black/70" onclick="closeRevokeReasonModal('{{ $gameKey->id }}')"></div>
    <div class="bg-[#1b2838] border border-red-500 p-6 rounded-sm shadow-2xl max-w-md w-full mx-4 relative z-10">
        <div class="text-center">
            <div class="mb-4 flex justify-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
            </div>
            <h2 class="text-red-500 text-lg font-bold mb-3">Key Đã Bị Thu Hồi</h2>
            <div class="bg-[#101822] border border-gray-700 p-4 rounded mb-4 text-left">
                <p class="text-gray-400 text-[10px] mb-1">Game:</p>
                <p class="text-white font-bold text-xs mb-3">{{ $game->name }}</p>
                <p class="text-gray-400 text-[10px] mb-1">Lý do thu hồi:</p>
                @php
                    $revokeReason = $gameKey->supplier_transaction_id && str_starts_with($gameKey->supplier_transaction_id, 'REVOKE: ') 
                        ? substr($gameKey->supplier_transaction_id, 8) 
                        : 'Không có thông tin';
                @endphp
                <p class="text-red-400 text-sm font-bold break-words">{{ $revokeReason }}</p>
            </div>
            <button onclick="closeRevokeReasonModal('{{ $gameKey->id }}')" 
                    class="bg-red-600 hover:bg-red-500 text-white px-6 py-2 rounded text-xs font-bold transition-colors">
                Đã hiểu
            </button>
        </div>
    </div>
</div>

{{-- Modal Xem Key + Kích Hoạt (gộp chung) --}}
<div id="key-modal-{{ $gameKey->id }}" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black/70" onclick="closeKeyModal('{{ $gameKey->id }}')"></div>
    <div class="bg-[#1b2838] border border-sky-500 p-6 rounded-sm shadow-2xl max-w-sm w-full mx-4 relative z-10">
        <div class="text-center">
            <h3 class="text-sky-400 font-bold text-sm mb-1">Mã Kích Hoạt</h3>
            <p class="text-white text-xs mb-3">{{ $game->name }} - {{ $versionName }}</p>
            <div class="bg-black/50 border border-gray-700 p-3 rounded mb-3">
                <code class="text-white font-mono text-xs block bg-gray-900 p-2 rounded cursor-text select-all break-all">
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

{{-- Modal Kích Hoạt (hướng dẫn) --}}
@if($isPending || $isGiveaway)
<div id="activate-modal-{{ $gameKey->id }}" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black/70" onclick="closeActivateModal('{{ $gameKey->id }}')"></div>
    <div class="bg-[#1b2838] border border-amber-500 p-6 rounded-sm shadow-2xl max-w-md w-full mx-4 relative z-10">
        <div class="text-center">
            <div class="mb-4 flex justify-center">
                <i class="fas fa-key text-amber-500 text-4xl"></i>
            </div>
            <h2 class="text-amber-500 text-lg font-bold mb-2">Kích Hoạt Game Key</h2>
            <p class="text-[#c7d5e0] text-xs mb-1">Game: <span class="text-white font-bold">{{ $game->name }}</span></p>
            <p class="text-[#c7d5e0] text-xs mb-4">Phiên bản: <span class="text-sky-400">{{ $versionName }}</span></p>
            
            <div class="bg-black/50 border border-gray-700 p-3 rounded mb-4">
                <p class="text-gray-400 text-[10px] mb-1">Mã kích hoạt của bạn:</p>
                <code class="text-white font-mono text-sm block bg-gray-900 p-2 rounded cursor-text select-all break-all">
                    {{ $gameKey->key_code }}
                </code>
                <button onclick="copyKeyCode('{{ $gameKey->key_code }}', '{{ $gameKey->id }}')" 
                        class="mt-2 bg-sky-600 hover:bg-sky-500 text-white px-3 py-1 rounded text-[10px] font-bold transition-colors">
                    <i class="fas fa-copy me-1"></i>Sao chép Key
                </button>
                <span id="copy-msg-{{ $gameKey->id }}" class="text-green-400 text-[10px] ml-2 hidden">Đã sao chép!</span>
            </div>

            <div class="bg-[#101822] border border-gray-700 p-3 rounded mb-4 text-left">
                <p class="text-amber-400 font-bold text-xs mb-2"><i class="fas fa-info-circle me-1"></i>Hướng dẫn kích hoạt:</p>
                <ol class="text-[#c7d5e0] text-[10px] space-y-1 list-decimal list-inside">
                    <li>Sao chép mã key ở trên</li>
                    <li>Mở game <strong class="text-white">{{ $game->name }}</strong> trên máy tính</li>
                    <li>Vào phần <strong class="text-white">Redeem Code</strong> trong game</li>
                    <li>Dán key vào và xác nhận</li>
                    <li>Sau khi kích hoạt thành công, bấm nút bên dưới</li>
                </ol>
            </div>

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
@else
{{-- Fallback nếu không có game --}}
<div class="bg-[#171a21] border border-gray-800 rounded-sm overflow-hidden">
    <div class="h-36 bg-[#101822] flex items-center justify-center">
        <i class="fas fa-key text-sky-400 text-3xl opacity-50"></i>
    </div>
    <div class="p-3 text-center">
        <p class="text-gray-400 text-xs mb-1">Game Key</p>
        <code class="text-white font-mono text-[10px] block bg-gray-900 p-2 rounded break-all select-all">{{ $gameKey->key_code ?? 'N/A' }}</code>
        <span class="inline-block mt-2 px-2 py-0.5 rounded text-[10px] font-bold 
            {{ $gameKey->status === 'Activated' ? 'bg-green-600/20 text-green-400' : 'bg-yellow-600/20 text-yellow-400' }}">
            {{ $gameKey->status ?? 'Unknown' }}
        </span>
    </div>
</div>
@endif

<script>
if (typeof openActivateModal !== 'function') {
    function openActivateModal(keyCode, gameName) {
        document.querySelectorAll('[id^="activate-modal-"]').forEach(m => {
            if (m.querySelector('code') && m.querySelector('code').textContent.trim() === keyCode.trim())
                m.classList.remove('hidden');
        });
    }
}
if (typeof closeActivateModal !== 'function') {
    function closeActivateModal(id) {
        const m = document.getElementById('activate-modal-' + id);
        if (m) m.classList.add('hidden');
    }
}
if (typeof copyKeyCode !== 'function') {
    function copyKeyCode(code, id) {
        navigator.clipboard.writeText(code).then(() => {
            const msg = document.getElementById('copy-msg-' + id);
            if (msg) { msg.classList.remove('hidden'); setTimeout(() => msg.classList.add('hidden'), 2000); }
        });
    }
}
if (typeof openKeyModal !== 'function') {
    function openKeyModal(code, id) {
        const m = document.getElementById('key-modal-' + id);
        if (m) m.classList.remove('hidden');
    }
}
if (typeof closeKeyModal !== 'function') {
    function closeKeyModal(id) {
        const m = document.getElementById('key-modal-' + id);
        if (m) m.classList.add('hidden');
    }
}
if (typeof copyKeyFromModal !== 'function') {
    function copyKeyFromModal(code, id) {
        navigator.clipboard.writeText(code).then(() => {
            const msg = document.getElementById('modal-copy-msg-' + id);
            if (msg) { msg.classList.remove('hidden'); setTimeout(() => msg.classList.add('hidden'), 2000); }
        });
    }
}
if (typeof openRevokeReasonModal !== 'function') {
    function openRevokeReasonModal(id) {
        const m = document.getElementById('revoke-reason-modal-' + id);
        if (m) m.classList.remove('hidden');
    }
}
if (typeof closeRevokeReasonModal !== 'function') {
    function closeRevokeReasonModal(id) {
        const m = document.getElementById('revoke-reason-modal-' + id);
        if (m) m.classList.add('hidden');
    }
}
</script>
