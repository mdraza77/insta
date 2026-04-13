{{-- Feed Reel Card - Clickable to open in reels section --}}
<div class="bg-black border border-spheria-border rounded-xl mb-8 overflow-hidden cursor-pointer hover:border-gray-700 transition"
     onclick="window.location.href='{{ route('reels.index', ['reel' => $post->id]) }}'">
    
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('profile.show', $post->user->username) }}" onclick="event.stopPropagation()">
                <div class="w-10 h-10 rounded-full overflow-hidden border border-spheria-border bg-gray-900">
                    <img src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                        alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                </div>
            </a>
            <div>
                <a href="{{ route('profile.show', $post->user->username) }}" onclick="event.stopPropagation()">
                    <h4 class="font-bold text-sm leading-none text-white">
                        {{ $post->user->username ?? $post->user->name }}
                    </h4>
                </a>
                @if ($post->location)
                    <span class="text-[10px] text-gray-500 uppercase tracking-tighter">{{ $post->location }}</span>
                @endif
            </div>
        </div>
        
        {{-- Reel Indicator --}}
        <div class="flex items-center space-x-2">
            <span class="text-xs text-purple-500 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-clapperboard"></i> Reel
            </span>
            <button onclick="event.stopPropagation()" class="text-gray-400 hover:text-white">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>
    </div>

    {{-- Video Preview --}}
    @if ($post->media && $post->media->isNotEmpty())
        @php $firstMedia = $post->media->first(); @endphp
        <div class="relative aspect-[9/16] max-h-[600px] overflow-hidden border-y border-spheria-border bg-black">
            @if ($firstMedia->media_type === 'video')
                <video src="{{ asset('storage/' . $firstMedia->media_url) }}"
                       class="w-full h-full object-cover"
                       muted
                       loop
                       playsinline
                       onmouseover="this.play()"
                       onmouseout="this.pause()">
                </video>
            @else
                <img src="{{ asset('storage/' . $firstMedia->media_url) }}"
                     class="w-full h-full object-cover"
                     alt="Post Media">
            @endif
            
            {{-- Play Icon Overlay --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-16 h-16 rounded-full bg-black/50 flex items-center justify-center">
                    <i class="fa-solid fa-play text-2xl text-white"></i>
                </div>
            </div>
            
            {{-- Duration Badge (if video) --}}
            @if ($firstMedia->media_type === 'video')
                <div class="absolute bottom-3 right-3 bg-black/70 rounded px-2 py-1 flex items-center gap-1">
                    <i class="fa-solid fa-clapperboard text-[10px] text-white"></i>
                </div>
            @endif
        </div>
    @endif

    {{-- Bottom Info --}}
    <div class="p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-5">
                {{-- Like Heart Icon --}}
                <div x-data="{
                    liked: {{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }},
                    count: {{ $post->likes_count }},
                    toggleLike() {
                        this.liked = !this.liked;
                        this.liked ? this.count++ : this.count--;
                        fetch('{{ route('posts.like', $post) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(res => res.json()).then(data => {
                            this.count = data.likes_count;
                        });
                    }
                }">
                    <button @click.stop="toggleLike()" class="focus:outline-none transition transform active:scale-125" onclick="event.stopPropagation()">
                        <template x-if="liked">
                            <i class="fa-solid fa-heart text-2xl text-red-500"></i>
                        </template>
                        <template x-if="!liked">
                            <i class="fa-regular fa-heart text-2xl text-white hover:text-red-500"></i>
                        </template>
                    </button>
                    <span class="text-sm font-bold text-white" x-text="count"></span>
                </div>

                {{-- Comment Icon --}}
                <button class="focus:outline-none" onclick="event.stopPropagation()">
                    <i class="fa-regular fa-comment text-2xl text-white hover:text-purple-500 transition"></i>
                </button>
                <span class="text-sm text-gray-500">{{ $post->comments_count }}</span>
            </div>

            {{-- Save Button --}}
            <div x-data="{
                saved: {{ $post->isSavedBy(auth()->user()) ? 'true' : 'false' }},
                loading: false,
                async toggleSave() {
                    if (this.loading) return;
                    this.loading = true;
                    try {
                        const res = await fetch('{{ route('posts.save', $post) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await res.json();
                        this.saved = (data.status === 'saved');
                    } catch (e) {
                        console.error('Save failed', e);
                    } finally {
                        this.loading = false;
                    }
                }
            }">
                <button @click.stop="toggleSave()" class="focus:outline-none transition transform active:scale-125" onclick="event.stopPropagation()">
                    <template x-if="saved">
                        <i class="fa-solid fa-bookmark text-2xl text-white"></i>
                    </template>
                    <template x-if="!saved">
                        <i class="fa-regular fa-bookmark text-2xl text-white hover:text-gray-400"></i>
                    </template>
                </button>
            </div>
        </div>

        {{-- Caption --}}
        @if ($post->caption)
            <p class="text-sm text-white mb-1">
                <span class="font-bold mr-2">{{ $post->user->username ?? $post->user->name }}</span>
                {{ $post->caption }}
            </p>
        @endif

        {{-- Time --}}
        <p class="text-[10px] text-gray-600 uppercase mt-2">{{ $post->created_at->diffForHumans() }}</p>
    </div>
</div>
