@extends('Players.Layouts.dashboard')

@section('title', 'Thư viện game')

@section('dashboard_content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-gray-800 pb-2">
        <h2 class="text-white text-sm font-bold uppercase tracking-wider">Kho game của bạn</h2>
        <div class="flex space-x-1 bg-[#101822] p-1 rounded-sm">
            <button onclick="showTab('inactive')" id="btn-inactive" class="px-4 py-1.5 text-xs font-bold bg-sky-600 text-white rounded-sm">Chưa kích hoạt ({{ $inactiveGames->count() }})</button>
            <button onclick="showTab('active')" id="btn-active" class="px-4 py-1.5 text-xs font-bold text-gray-400 hover:text-white">Đã kích hoạt ({{ $activeGames->count() }})</button>
        </div>
    </div>

    {{-- Tab Chưa kích hoạt --}}
    <div id="tab-inactive" class="space-y-4">
        @forelse($inactiveGames->chunk(10) as $page => $chunk)
            <div class="page-inactive grid grid-cols-1 md:grid-cols-3 gap-4 {{ $page > 0 ? 'hidden' : '' }}" data-page="{{ $page }}">
                @foreach($chunk as $item) @include('Players.library._game_card', ['item' => $item]) @endforeach
            </div>
        @empty <p class="text-gray-500 text-sm">Chưa có game nào.</p> @endforelse

        @if($inactiveGames->count() > 10)
            <div class="flex justify-center space-x-2 mt-4">
                @foreach($inactiveGames->chunk(10) as $page => $chunk)
                    <button onclick="changePage('inactive', {{ $page }})" class="px-3 py-1 bg-gray-800 text-white text-xs rounded">{{ $page + 1 }}</button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Tab Đã kích hoạt --}}
    <div id="tab-active" class="space-y-4">
        @forelse($activeGames->chunk(10) as $page => $chunk)
            <div class="page-active grid grid-cols-1 md:grid-cols-3 gap-4 {{ $page > 0 ? 'hidden' : '' }}" data-page="{{ $page }}">
                @foreach($chunk as $item) @include('Players.library._game_card', ['item' => $item]) @endforeach
            </div>
        @empty <p class="text-gray-500 text-sm">Chưa có game nào.</p> @endforelse

        @if($activeGames->count() > 10)
            <div class="flex justify-center space-x-2 mt-4">
                @foreach($activeGames->chunk(10) as $page => $chunk)
                    <button onclick="changePage('active', {{ $page }})" class="px-3 py-1 bg-gray-800 text-white text-xs rounded">{{ $page + 1 }}</button>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
function showTab(tab) {
    document.getElementById('tab-inactive').classList.toggle('hidden', tab !== 'inactive');
    document.getElementById('tab-active').classList.toggle('hidden', tab !== 'active');
    document.getElementById('btn-inactive').className = tab === 'inactive' ? "px-4 py-1.5 text-xs font-bold bg-sky-600 text-white rounded-sm" : "px-4 py-1.5 text-xs font-bold text-gray-400 hover:text-white";
    document.getElementById('btn-active').className = tab === 'active' ? "px-4 py-1.5 text-xs font-bold bg-sky-600 text-white rounded-sm" : "px-4 py-1.5 text-xs font-bold text-gray-400 hover:text-white";
}
function changePage(tab, pageIndex) {
    document.querySelectorAll('.page-' + tab).forEach(el => el.classList.add('hidden'));
    document.querySelector('.page-' + tab + '[data-page="' + pageIndex + '"]').classList.remove('hidden');
}
</script>
@endsection