<div class="bg-[#101a24] rounded-sm border border-[#233c51]/70 p-2">
    @foreach($topSellingGames as $index => $game)
        <div onclick="window.location.href='{{ url('/games/' . $game->id) }}'" class="flex items-center p-2 hover:bg-[#1c324a] cursor-pointer rounded-xs border-b border-[#2a475e]/30 last:border-0">
            <span class="text-[#556772] font-bold text-sm w-8">{{ $index + 1 }}</span>
            <img src="{{ $game->cover_image }}" class="w-12 h-8 object-cover rounded-xs mr-3">
            <h4 class="text-[#c7d5e0] text-xs font-bold truncate">{{ $game->name }}</h4>
        </div>
    @endforeach
</div>