<div class="space-y-2">
    @foreach($comingSoonGames as $game)
        <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" class="bg-[#101a24] hover:bg-[#1c324a] p-2.5 flex items-center justify-between rounded-sm cursor-pointer border border-[#2a475e]/30 group">
            <div class="flex items-center space-x-4">
                <div class="w-24 h-12 overflow-hidden rounded-xs border border-[#2a475e]/40"><img src="{{ $game->cover_image }}" class="w-full h-full object-cover"></div>
                <div>
                    <h3 class="text-[#c7d5e0] group-hover:text-white text-sm font-bold">{{ $game->name }}</h3>
                    <span class="text-[9px] bg-yellow-600/20 text-yellow-500 px-1 rounded uppercase font-bold">Coming Soon</span>
                </div>
            </div>
            <div class="text-[10px] text-[#7d8b99] uppercase">Dự kiến: {{ \Carbon\Carbon::parse($game->release_date)->format('d/m/Y') }}</div>
        </div>
    @endforeach
</div>