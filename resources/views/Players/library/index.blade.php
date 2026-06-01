@extends('Players.Layouts.dashboard')

@section('title', 'Thư viện game')

@section('dashboard_content')
<div class="space-y-6">
    <h2 class="text-white text-sm font-bold uppercase tracking-wider border-b border-gray-800 pb-2">Kho game của bạn</h2>

    <div class="flex space-x-4 border-b border-gray-800">
        <button onclick="showTab('inactive')" id="btn-inactive" class="pb-2 text-sky-400 border-b-2 border-sky-400 text-sm font-bold">Chưa kích hoạt ({{ $inactiveGames->count() }})</button>
        <button onclick="showTab('active')" id="btn-active" class="pb-2 text-gray-500 hover:text-white text-sm font-bold">Đã kích hoạt ({{ $activeGames->count() }})</button>
    </div>

    <div id="tab-inactive" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @each('Players.library._game_card', $inactiveGames, 'item')
    </div>

    <div id="tab-active" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
        @each('Players.library._game_card', $activeGames, 'item')
    </div>
</div>

<script>
function showTab(tab) {
    document.getElementById('tab-inactive').classList.toggle('hidden', tab !== 'inactive');
    document.getElementById('tab-active').classList.toggle('hidden', tab !== 'active');
    
    document.getElementById('btn-inactive').className = tab === 'inactive' ? "pb-2 text-sky-400 border-b-2 border-sky-400 text-sm font-bold" : "pb-2 text-gray-500 hover:text-white text-sm font-bold";
    document.getElementById('btn-active').className = tab === 'active' ? "pb-2 text-sky-400 border-b-2 border-sky-400 text-sm font-bold" : "pb-2 text-gray-500 hover:text-white text-sm font-bold";
}
</script>
@endsection