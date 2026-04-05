<div class="flex items-center space-x-4 overflow-x-auto py-4 no-scrollbar border-b border-gray-800">

    <div class="flex flex-col items-center space-y-1 flex-shrink-0 cursor-pointer pl-2">
        <div class="relative p-[2px] rounded-full bg-gradient-to-tr from-yellow-400 to-purple-600">
            <div class="p-1 bg-black rounded-full">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . auth()->user()->name }}"
                    class="w-16 h-16 rounded-full object-cover">
            </div>
            <div class="absolute bottom-0 right-0 bg-blue-500 rounded-full border-2 border-black p-[2px]">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
        </div>
        <span class="text-[11px] text-gray-400">Your Story</span>
    </div>

    @foreach (range(1, 10) as $i)
        <div class="flex flex-col items-center space-y-1 flex-shrink-0 cursor-pointer group">
            <div
                class="p-[2px] rounded-full bg-gradient-to-tr from-yellow-400 to-purple-600 group-active:scale-95 transition duration-150">
                <div class="p-[2px] bg-black rounded-full">
                    <img src="https://i.pravatar.cc/150?u={{ $i }}"
                        class="w-16 h-16 rounded-full object-cover border border-gray-900">
                </div>
            </div>
            <span class="text-[11px] text-gray-400 truncate w-16 text-center">user_{{ $i }}</span>
        </div>
    @endforeach

</div>

<style>
    /* Scrollbar hide karne ke liye utility class */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
