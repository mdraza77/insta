<div class="bg-black border border-spheria-border rounded-xl mb-8 overflow-hidden">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border border-spheria-border bg-gray-900">
                {{-- User Profile Picture --}}
                <img src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                    alt="{{ $post->user->name }}" class="w-full h-full object-cover">
            </div>
            <div>
                {{-- User Name --}}
                <h4 class="font-bold text-sm leading-none text-white">
                    {{ $post->user->username ?? $post->user->name }}
                </h4>
                {{-- Location --}}
                @if ($post->location)
                    <span class="text-[10px] text-gray-500 uppercase tracking-tighter">{{ $post->location }}</span>
                @endif
            </div>
        </div>
        <button class="text-gray-400"><i class="fa-solid fa-ellipsis"></i></button>
    </div>

    <div id="carousel-{{ $post->id }}" class="relative w-full" data-carousel="static">
        <div class="relative aspect-square overflow-hidden border-y border-spheria-border">
            @if ($post->media && $post->media->isNotEmpty())
                @foreach ($post->media as $index => $item)
                    <div class="hidden duration-700 ease-in-out" data-carousel-item="{{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $item->media_url) }}"
                            class="absolute block w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                            alt="Post Media">
                    </div>
                @endforeach
            @endif
        </div>

        @if ($post->media->count() > 1)
            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-2">
                @foreach ($post->media as $index => $item)
                    <button type="button" class="w-2 h-2 rounded-full bg-white/50"
                        aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"
                        data-carousel-slide-to="{{ $index }}"></button>
                @endforeach
            </div>

            <button type="button"
                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
                <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-black/30 group-hover:bg-black/50">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7" />
                    </svg>
                </span>
            </button>
            <button type="button"
                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
                <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-black/30 group-hover:bg-black/50">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                    </svg>
                </span>
            </button>
        @endif
    </div>

    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-5">
                <i class="fa-regular fa-heart text-2xl cursor-pointer hover:text-red-500 transition text-white"></i>
                <i
                    class="fa-regular fa-comment text-2xl cursor-pointer hover:text-purple-500 transition text-white"></i>
                <i
                    class="fa-regular fa-paper-plane text-2xl cursor-pointer hover:text-blue-500 transition text-white"></i>
            </div>
            <i class="fa-regular fa-bookmark text-2xl cursor-pointer hover:text-yellow-500 transition text-white"></i>
        </div>

        <div class="space-y-1">
            {{-- Likes Count --}}
            <p class="text-sm font-bold text-white">{{ number_format($post->likes_count) }} Likes</p>

            {{-- Caption --}}
            @if ($post->caption)
                <p class="text-sm text-white">
                    <span class="font-bold mr-2">{{ $post->user->username ?? $post->user->name }}</span>
                    {{ $post->caption }}
                </p>
            @endif

            {{-- Comments Count --}}
            @if ($post->comments_count > 0)
                <button class="text-gray-500 text-xs py-1">View all {{ $post->comments_count }} comments</button>
            @endif

            {{-- Post Time --}}
            <p class="text-[10px] text-gray-600 uppercase mt-1">{{ $post->created_at->diffForHumans() }}</p>
        </div>
    </div>
</div>
