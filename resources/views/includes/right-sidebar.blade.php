<div class="fixed w-[320px] hidden lg:block">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4 cursor-pointer">
            <div class="w-12 h-12 rounded-full overflow-hidden border border-spheria-border">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . auth()->user()->name }}"
                    alt="my-avatar" class="w-full h-full object-cover">
            </div>
            <div>
                <h4 class="font-bold text-sm leading-none">{{ auth()->user()->username ?? auth()->user()->name }}</h4>
                <span class="text-xs text-gray-500 capitalize">{{ auth()->user()->name }}</span>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="text-xs font-bold text-purple-500 hover:text-purple-400">Switch</a>
    </div>

    <div class="flex items-center justify-between mb-4">
        <span class="text-sm font-bold text-gray-400 uppercase tracking-wider text-[11px]">Suggested for you</span>
        <button class="text-xs font-bold text-white hover:text-gray-400">See All</button>
    </div>

    <div class="space-y-4">
        @foreach (range(1, 5) as $i)
            {{-- Dummy Loop --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 cursor-pointer">
                    <div class="w-8 h-8 rounded-full overflow-hidden border border-spheria-border">
                        <img src="https://i.pravatar.cc/150?u=user{{ $i }}" alt="suggested-avatar"
                            class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h5 class="text-xs font-bold leading-none">indian_developer_{{ $i }}</h5>
                        <span class="text-[10px] text-gray-500">Suggested for you</span>
                    </div>
                </div>
                <button class="text-xs font-bold text-purple-500 hover:text-white transition">Follow</button>
            </div>
        @endforeach
    </div>

    <div class="mt-10 opacity-30">
        <nav class="flex flex-wrap gap-2 text-[10px] text-gray-400 uppercase tracking-widest leading-loose">
            <a href="#" class="hover:underline">About</a>
            <a href="#" class="hover:underline">Help</a>
            <a href="#" class="hover:underline">Press</a>
            <a href="#" class="hover:underline">API</a>
            <a href="#" class="hover:underline">Privacy</a>
            <a href="#" class="hover:underline">Terms</a>
        </nav>
        <p class="text-[10px] text-gray-500 mt-4 font-bold tracking-widest italic">© 2026 SPHERIA FROM INDIANS</p>
    </div>
</div>
