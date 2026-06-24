@extends('Players.Layouts.dashboard')

@section('title', 'Thư viện game')

@section('dashboard_content')
<div class="space-y-6">
    {{-- Header & Tabs --}}
    <div class="flex items-center justify-between border-b border-gray-800 pb-2">
        <h2 class="text-white text-sm font-bold uppercase tracking-wider">Kho game của bạn</h2>
        <div class="flex space-x-1 bg-[#101822] p-1 rounded-sm">
            <button onclick="showTab('inactive')" id="btn-inactive" class="px-4 py-1.5 text-xs font-bold bg-sky-600 text-white rounded-sm">Chưa kích hoạt ({{ $inactiveGames->count() }})</button>
            <button onclick="showTab('active')" id="btn-active" class="px-4 py-1.5 text-xs font-bold text-gray-400 hover:text-white">Đã kích hoạt ({{ $activeGames->count() }})</button>
            <button onclick="showTab('revoked')" id="btn-revoked" class="px-4 py-1.5 text-xs font-bold text-gray-400 hover:text-white">Đã thu hồi ({{ $revokedGames->count() }})</button>
        </div>
    </div>

    {{-- Tab Chưa kích hoạt --}}
    <div id="tab-inactive" class="space-y-4">
        @forelse($inactiveGames->chunk(12) as $page => $chunk)
            <div class="page-inactive grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 {{ $page > 0 ? 'hidden' : '' }}" data-page="{{ $page }}">
                @foreach($chunk as $item) @include('Players.library._game_card', ['item' => $item]) @endforeach
            </div>
        @empty <p class="text-gray-500 text-sm">Chưa có game nào trong trạng thái chờ.</p> @endforelse
        @if($inactiveGames->count() > 12)
            <div class="flex justify-center gap-2 mt-4">
                <button onclick="prevPage('inactive')" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded text-xs">← Trước</button>
                <span class="px-3 py-1 bg-gray-800 text-gray-300 rounded text-xs">Trang <span id="page-inactive">1</span>/<span id="total-inactive">{{ ceil($inactiveGames->count() / 12) }}</span></span>
                <button onclick="nextPage('inactive')" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded text-xs">Sau →</button>
            </div>
        @endif
    </div>

    {{-- Tab Đã kích hoạt --}}
    <div id="tab-active" class="space-y-4 hidden">
        @forelse($activeGames->chunk(12) as $page => $chunk)
            <div class="page-active grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 {{ $page > 0 ? 'hidden' : '' }}" data-page="{{ $page }}">
                @foreach($chunk as $item) @include('Players.library._game_card', ['item' => $item]) @endforeach
            </div>
        @empty <p class="text-gray-500 text-sm">Bạn chưa kích hoạt game nào.</p> @endforelse
        @if($activeGames->count() > 12)
            <div class="flex justify-center gap-2 mt-4">
                <button onclick="prevPage('active')" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded text-xs">← Trước</button>
                <span class="px-3 py-1 bg-gray-800 text-gray-300 rounded text-xs">Trang <span id="page-active">1</span>/<span id="total-active">{{ ceil($activeGames->count() / 12) }}</span></span>
                <button onclick="nextPage('active')" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded text-xs">Sau →</button>
            </div>
        @endif
    </div>
{{-- Tab Đã thu hồi --}}
<div id="tab-revoked" class="space-y-4 hidden">
    @forelse($revokedGames as $key) {{-- Đổi $item thành $key cho rõ nghĩa --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#101822] p-4 rounded border-l-4 border-red-500 opacity-60">
                {{-- Vì $key là đối tượng GameKey, truy cập thẳng vào orderItem --}}
                <p class="text-white font-bold text-sm">
                    {{ optional(optional($key->orderItem)->version)->game->name ?? 'Game không xác định' }}
                </p>
                <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-widest">
                    Đã bị thu hồi bởi hệ thống
                </p>
            </div>
        </div>
    @empty 
        <p class="text-gray-500 text-sm italic">Không có trò chơi nào bị thu hồi.</p> 
    @endforelse
</div>

<script>
function showTab(tab) {
    // Ẩn tất cả nội dung tab
    ['tab-inactive', 'tab-active', 'tab-revoked'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });
    // Hiện tab được chọn
    document.getElementById('tab-' + tab).classList.remove('hidden');
    
    // Reset style tất cả các nút
    ['btn-inactive', 'btn-active', 'btn-revoked'].forEach(id => {
        document.getElementById(id).className = "px-4 py-1.5 text-xs font-bold text-gray-400 hover:text-white";
    });
    
    // Style cho nút được chọn
    document.getElementById('btn-' + tab).className = "px-4 py-1.5 text-xs font-bold bg-sky-600 text-white rounded-sm";
}

function nextPage(tab) {
    const pages = document.querySelectorAll(`.page-${tab}`);
    const currentPage = Array.from(pages).findIndex(p => !p.classList.contains('hidden'));
    if (currentPage < pages.length - 1) {
        pages[currentPage].classList.add('hidden');
        pages[currentPage + 1].classList.remove('hidden');
        const pageEl = document.getElementById(`page-${tab}`);
        if (pageEl) pageEl.textContent = currentPage + 2;
    }
}

function prevPage(tab) {
    const pages = document.querySelectorAll(`.page-${tab}`);
    const currentPage = Array.from(pages).findIndex(p => !p.classList.contains('hidden'));
    if (currentPage > 0) {
        pages[currentPage].classList.add('hidden');
        pages[currentPage - 1].classList.remove('hidden');
        const pageEl = document.getElementById(`page-${tab}`);
        if (pageEl) pageEl.textContent = currentPage;
    }
}
</script>
@endsection