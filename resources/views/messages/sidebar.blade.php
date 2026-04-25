<div class="flex-shrink-0 w-70 border-r border-gray-800 flex flex-col h-full bg-black">
    <div class="p-5 border-b border-gray-800 flex justify-between items-center bg-black">
        <h1 class="text-white font-bold text-xl"><a
                href="{{ route('profile.show', auth()->user()->username) }}">{{ auth()->user()->username }}</a></h1>
        <button class="text-white hover:text-gray-400" title="Work In Progress">
            <i class="fa-solid fa-pen-to-square text-xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar">
        @foreach ($conversations as $conv)
            @php
                $chatPartner = $conv->getReceiver();
            @endphp

            <a href="{{ route('messages.chat', $chatPartner->username) }}"
                class="flex items-center px-5 py-4 transition border-l-2 
       {{ isset($activeReceiver) && $activeReceiver->id === $chatPartner->id ? 'bg-zinc-900 border-white' : 'border-transparent hover:bg-zinc-900' }}">

                <div class="relative flex-shrink-0">
                    <img src="{{ $chatPartner->profile_picture ? asset('storage/' . $chatPartner->profile_picture) : 'https://ui-avatars.com/api/?name=' . $chatPartner->name }}"
                        class="w-12 h-12 rounded-full object-cover">
                </div>


                <div class="ml-3 flex-1 min-w-0">

                    <!-- Top row: Name + Time -->
                    <div class="flex justify-between items-center">
                        <p class="text-white text-sm font-semibold truncate" title="{{ $chatPartner->username }}">
                            {{ $chatPartner->name }}
                        </p>

                        <span class="text-[10px] text-gray-500 whitespace-nowrap ml-2">
                            {{ $conv->lastMessage?->created_at?->diffForHumans() }}
                        </span>
                    </div>

                    <!-- Message preview -->
                    <p class="text-gray-400 text-xs truncate">
                        {{ \Illuminate\Support\Str::words($conv->lastMessage?->body ?? 'No messages yet', 3, '...') }}
                    </p>

                </div>
            </a>
        @endforeach
    </div>
</div>
