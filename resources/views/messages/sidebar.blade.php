<div class="flex-shrink-0 w-80 border-r border-gray-800 flex flex-col h-full bg-black">
    <div class="p-5 border-b border-gray-800 flex justify-between items-center bg-black">
        <h1 class="text-white font-bold text-xl">{{ auth()->user()->username }}</h1>
        <button class="text-white hover:text-gray-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
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

                <div class="ml-3 overflow-hidden flex-1">
                    <p class="text-white text-sm font-semibold truncate">{{ $chatPartner->name }}</p>
                    <p class="text-gray-500 text-xs truncate">
                        {{ $conv->lastMessage?->body ?? 'No messages yet' }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
</div>
