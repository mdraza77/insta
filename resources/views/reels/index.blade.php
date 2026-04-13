{{-- @extends('layouts.app') --}}
<x-app-layout>

    @section('title', 'Reels')

    @section('content')
        <div class="min-h-screen bg-black flex items-center justify-center">
            {{-- Reels Container --}}
            <div class="relative w-full max-w-sm md:max-w-md h-[calc(100vh-4rem)] md:h-[calc(100vh-5rem)] snap-y snap-mandatory overflow-y-scroll scrollbar-hide"
                x-data="reelsPlayer()" x-init="init()">

                @if ($reels->count() > 0)
                    @foreach ($reels as $index => $reel)
                        <div class="snap-start w-full h-full flex-shrink-0 relative bg-black"
                            data-reel-index="{{ $index }}">

                            {{-- Video Container --}}
                            <div class="relative w-full h-full flex items-center justify-center">
                                {{-- Video Element --}}
                                <video x-ref="video{{ $index }}"
                                    src="{{ asset('storage/' . $reel->media->first()->media_url) }}"
                                    class="w-full h-full object-cover" loop playsinline
                                    @click="togglePlayPause({{ $index }})"
                                    @loadedmetadata="onVideoLoaded({{ $index }})">
                                </video>

                                {{-- Play/Pause Overlay --}}
                                <div x-show="showOverlay[{{ $index }}]"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-100 scale-100"
                                    x-transition:enter-end="opacity-0 scale-150"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-0 scale-150"
                                    x-transition:leave-end="opacity-100 scale-100"
                                    class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                                    <div class="w-20 h-20 rounded-full bg-black/40 flex items-center justify-center">
                                        <template x-if="!isPlaying[{{ $index }}]">
                                            <i class="fa-solid fa-play text-4xl text-white"></i>
                                        </template>
                                    </div>
                                </div>

                                {{-- Double Tap Heart Animation --}}
                                <div x-show="showHeart[{{ $index }}]"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-50"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-150"
                                    class="absolute inset-0 flex items-center justify-center pointer-events-none z-20">
                                    <i class="fa-solid fa-heart text-8xl text-white drop-shadow-lg"></i>
                                </div>

                                {{-- Loading Spinner --}}
                                <div x-show="isLoading[{{ $index }}]"
                                    class="absolute inset-0 flex items-center justify-center bg-black/20">
                                    <i class="fa-solid fa-spinner fa-spin text-4xl text-white"></i>
                                </div>

                                {{-- Gradient Overlay for Right Side Controls --}}
                                <div
                                    class="absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-black/60 to-transparent pointer-events-none">
                                </div>

                                {{-- Gradient Overlay for Bottom Info --}}
                                <div
                                    class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/80 via-black/40 to-transparent pointer-events-none">
                                </div>

                                {{-- Right Side Controls --}}
                                <div
                                    class="absolute right-3 bottom-24 md:bottom-32 flex flex-col items-center space-y-6 z-30">
                                    {{-- Like Button --}}
                                    <div class="flex flex-col items-center" x-data="{
                                        liked: {{ $reel->isLikedBy(auth()->user()) ? 'true' : 'false' }},
                                        count: {{ $reel->likes_count }},
                                        toggleLike() {
                                            this.liked = !this.liked;
                                            this.liked ? this.count++ : this.count--;
                                            fetch('{{ route('posts.like', $reel) }}', {
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
                                        <button @click="toggleLike()"
                                            class="focus:outline-none transition transform active:scale-125">
                                            <template x-if="liked">
                                                <i class="fa-solid fa-heart text-3xl text-red-500"></i>
                                            </template>
                                            <template x-if="!liked">
                                                <i class="fa-regular fa-heart text-3xl text-white"></i>
                                            </template>
                                        </button>
                                        <span class="text-white text-xs font-semibold mt-1" x-text="count"></span>
                                    </div>

                                    {{-- Comment Button --}}
                                    <div class="flex flex-col items-center">
                                        <button @click="openComments({{ $reel->id }})" class="focus:outline-none">
                                            <i class="fa-regular fa-comment text-3xl text-white"></i>
                                        </button>
                                        <span
                                            class="text-white text-xs font-semibold mt-1">{{ $reel->comments_count }}</span>
                                    </div>

                                    {{-- Share Button --}}
                                    <button class="focus:outline-none">
                                        <i class="fa-regular fa-paper-plane text-3xl text-white"></i>
                                    </button>

                                    {{-- Save Button --}}
                                    <div class="flex flex-col items-center" x-data="{
                                        saved: {{ $reel->isSavedBy(auth()->user()) ? 'true' : 'false' }},
                                        loading: false,
                                        async toggleSave() {
                                            if (this.loading) return;
                                            this.loading = true;
                                            try {
                                                const res = await fetch('{{ route('posts.save', $reel) }}', {
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
                                        <button @click="toggleSave()"
                                            class="focus:outline-none transition transform active:scale-125">
                                            <template x-if="saved">
                                                <i class="fa-solid fa-bookmark text-3xl text-white"></i>
                                            </template>
                                            <template x-if="!saved">
                                                <i class="fa-regular fa-bookmark text-3xl text-white"></i>
                                            </template>
                                        </button>
                                    </div>

                                    {{-- More Options --}}
                                    <button class="focus:outline-none">
                                        <i class="fa-solid fa-ellipsis text-2xl text-white"></i>
                                    </button>

                                    {{-- Audio Icon (Spinning) --}}
                                    <div class="w-8 h-8 rounded-md border-2 border-gray-300 overflow-hidden animate-spin"
                                        style="animation-duration: 3s;">
                                        <img src="{{ $reel->user->profile_picture ? asset('storage/' . $reel->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($reel->user->name) }}"
                                            class="w-full h-full object-cover" alt="Audio">
                                    </div>
                                </div>

                                {{-- Bottom Info --}}
                                <div class="absolute left-4 bottom-24 md:bottom-32 right-20 z-30">
                                    {{-- User Info --}}
                                    <div class="flex items-center space-x-3 mb-3">
                                        <a href="{{ route('profile.show', $reel->user->username) }}">
                                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white">
                                                <img src="{{ $reel->user->profile_picture ? asset('storage/' . $reel->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($reel->user->name) }}"
                                                    class="w-full h-full object-cover" alt="{{ $reel->user->name }}">
                                            </div>
                                        </a>
                                        <a href="{{ route('profile.show', $reel->user->username) }}"
                                            class="font-bold text-white text-sm hover:underline">
                                            {{ $reel->user->username ?? $reel->user->name }}
                                        </a>
                                        @if (!$reel->user->isFollowing(auth()->user()) && $reel->user->id !== auth()->id())
                                            <button onclick="followUser({{ $reel->user->id }})"
                                                class="text-white text-xs font-semibold border border-white px-3 py-1 rounded hover:bg-white/10 transition">
                                                Follow
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Caption --}}
                                    @if ($reel->caption)
                                        <p class="text-white text-sm mb-2 line-clamp-2">
                                            {{ $reel->caption }}
                                        </p>
                                    @endif

                                    {{-- Audio Info --}}
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-music text-xs"></i>
                                        <div class="overflow-hidden w-48">
                                            <p class="text-xs whitespace-nowrap animate-marquee">
                                                Original Audio - {{ $reel->user->username ?? $reel->user->name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Progress Bar --}}
                                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-800 z-40">
                                    <div x-ref="progress{{ $index }}"
                                        class="h-full bg-white transition-all duration-100" style="width: 0%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Empty State --}}
                    <div class="w-full h-full flex flex-col items-center justify-center text-center px-4">
                        <i class="fa-solid fa-clapperboard text-6xl text-gray-600 mb-4"></i>
                        <h3 class="text-white text-xl font-bold mb-2">No Reels Yet</h3>
                        <p class="text-gray-500 text-sm">Create your first reel to get started!</p>
                    </div>
                @endif
            </div>

            {{-- Comments Modal --}}
            <div x-show="showCommentsModal" x-cloak class="fixed inset-0 z-50 flex items-end md:items-center justify-center"
                @click.self="showCommentsModal = false">

                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-black/70"></div>

                {{-- Modal Content --}}
                <div class="relative bg-gray-900 w-full md:max-w-lg max-h-[70vh] rounded-t-2xl md:rounded-2xl overflow-hidden"
                    x-show="showCommentsModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-y-full md:translate-y-10 md:opacity-0"
                    x-transition:enter-end="translate-y-0 md:translate-y-0 md:opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
                    x-transition:leave-end="translate-y-full md:translate-y-10 md:opacity-0">

                    {{-- Header --}}
                    <div class="flex items-center justify-between p-4 border-b border-gray-800">
                        <h3 class="text-white font-bold text-lg">Comments</h3>
                        <button @click="showCommentsModal = false" class="text-gray-400 hover:text-white">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    {{-- Comments List --}}
                    <div class="overflow-y-auto p-4 space-y-4" style="max-height: 50vh;">
                        <template x-for="comment in comments" :key="comment.id">
                            <div class="flex space-x-3">
                                <a href="#">
                                    <img
                                        :src="comment.user.profile_picture ?
                                            '/storage/' + comment.user.profile_picture :
                                            'https://ui-avatars.com/api/?name=' + encodeURIComponent(comment.user.name)"
                                 class="w-8 h-8 rounded-full object-cover border border-gray-700">
                        </a>
                        <div class="flex-1">
                            <div class="bg-gray-800 rounded-lg px-3 py-2">
                                <p class="text-white text-sm">
                                    <span class="font-bold" x-text="comment.user.username || comment.user.name"></span>
                                    <span x-text="comment.body"></span>
                                </p>
                            </div>
                            <div class="flex items-center space-x-4 mt-1 px-2">
                                <span class="text-xs text-gray-500" x-text="comment.created_at_formatted"></span>
                                <button class="text-xs text-gray-500 font-semibold">Reply</button>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="comments.length === 0" class="text-center py-8 text-gray-500">
                    No comments yet
                </div>
            </div>

            {{-- Comment Input --}}
            <div class="p-4 border-t border-gray-800">
                <form @submit.prevent="submitComment" class="flex items-center space-x-2">
                    <input type="text" 
                           x-model="newComment" 
                           placeholder="Add a comment..."
                           class="flex-1 bg-transparent text-white text-sm placeholder-gray-500 focus:outline-none">
                    <button type="submit" 
                            class="text-blue-500 text-sm font-semibold hover:text-blue-400 transition"
                            :disabled="!newComment.trim()"
                            :class="{ 'opacity -
                                            50 cursor - not - allowed ': !newComment.trim() }">
                                    Post
                                    </button>
                                    </form>
                            </div>
                    </div>
                </div>
            </div>
            {{-- @endsection --}}
    </x-app-layout>

    @push('styles')
        <style>
            /* Hide scrollbar */
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            /* Marquee animation for audio text */
            @keyframes marquee {
                0% {
                    transform: translateX(0);
                }

                100% {
                    transform: translateX(-50%);
                }
            }

            .animate-marquee {
                animation: marquee 10s linear infinite;
            }

            /* Snap scroll behavior */
            .snap-y {
                scroll-snap-type: y mandatory;
            }

            .snap-start {
                scroll-snap-align: start;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function reelsPlayer() {
                return {
                    currentReel: 0,
                    isPlaying: [],
                    showOverlay: [],
                    showHeart: [],
                    isLoading: [],
                    showCommentsModal: false,
                    comments: [],
                    newComment: '',
                    currentPostId: null,
                    observer: null,

                    init() {
                        @foreach ($reels as $index => $reel)
                            this.isPlaying[{{ $index }}] = false;
                            this.showOverlay[{{ $index }}] = false;
                            this.showHeart[{{ $index }}] = false;
                            this.isLoading[{{ $index }}] = true;
                        @endforeach

                        // Intersection Observer for autoplay
                        this.$nextTick(() => {
                            const options = {
                                root: null,
                                rootMargin: '0px',
                                threshold: 0.7
                            };

                            this.observer = new IntersectionObserver((entries) => {
                                entries.forEach(entry => {
                                    const index = parseInt(entry.target.dataset.reelIndex);
                                    if (entry.isIntersecting) {
                                        this.currentReel = index;
                                        this.playVideo(index);
                                    } else {
                                        this.pauseVideo(index);
                                    }
                                });
                            }, options);

                            document.querySelectorAll('[data-reel-index]').forEach(el => {
                                this.observer.observe(el);
                            });
                        });
                    },

                    playVideo(index) {
                        const video = this.$refs['video' + index];
                        if (video) {
                            video.play().then(() => {
                                this.isPlaying[index] = true;
                                this.isLoading[index] = false;
                                this.updateProgress(index);
                            }).catch(() => {
                                this.isLoading[index] = false;
                            });
                        }
                    },

                    pauseVideo(index) {
                        const video = this.$refs['video' + index];
                        if (video) {
                            video.pause();
                            this.isPlaying[index] = false;
                        }
                    },

                    togglePlayPause(index) {
                        const video = this.$refs['video' + index];
                        if (video.paused) {
                            video.play();
                            this.isPlaying[index] = true;
                            this.showOverlay[index] = true;
                            setTimeout(() => this.showOverlay[index] = false, 500);
                        } else {
                            video.pause();
                            this.isPlaying[index] = false;
                            this.showOverlay[index] = true;
                            setTimeout(() => this.showOverlay[index] = false, 500);
                        }
                    },

                    updateProgress(index) {
                        const video = this.$refs['video' + index];
                        const progressBar = this.$refs['progress' + index];

                        if (video && progressBar) {
                            video.addEventListener('timeupdate', () => {
                                const progress = (video.currentTime / video.duration) * 100;
                                progressBar.style.width = progress + '%';
                            });
                        }
                    },

                    onVideoLoaded(index) {
                        this.isLoading[index] = false;
                    },

                    async openComments(postId) {
                        this.currentPostId = postId;
                        this.showCommentsModal = true;

                        try {
                            const res = await fetch(`/posts/${postId}/comments`);
                            const data = await res.json();
                            if (data.success) {
                                this.comments = data.comments;
                            }
                        } catch (error) {
                            console.error('Error loading comments:', error);
                        }
                    },

                    async submitComment() {
                        if (!this.newComment.trim()) return;

                        try {
                            const res = await fetch(`/posts/${this.currentPostId}/comments`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    body: this.newComment
                                })
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.comments.unshift(data.comment);
                                this.newComment = '';
                            }
                        } catch (error) {
                            console.error('Error submitting comment:', error);
                        }
                    }
                }
            }

            function followUser(userId) {
                fetch(`/user/${userId}/follow`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(res => res.json())
                    .then(data => {
                        console.log('Follow toggled:', data);
                    });
            }
        </script>
    @endpush
