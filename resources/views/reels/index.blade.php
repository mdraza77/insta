@extends('layouts.reels-main')

@section('title', 'Reels')

@section('content')
    <div class="w-full h-full flex items-center justify-center">
        {{-- Reels Container --}}
        <div class="relative w-full max-w-[420px] h-full overflow-y-scroll snap-y snap-mandatory scrollbar-hide"
            x-data="reelsPlayer()" x-init="init()">

            @if ($reels->count() > 0)
                @foreach ($reels as $index => $reel)
                    {{-- Single Reel Card --}}
                    <div class="snap-start w-full h-full flex-shrink-0 relative flex items-center justify-center"
                        data-reel-index="{{ $index }}">

                        {{-- Reel Video Card with Rounded Corners --}}
                        <div class="relative w-full h-full bg-gray-900 rounded-xl overflow-hidden">

                            {{-- Video Element --}}
                            <video x-ref="video{{ $index }}"
                                src="{{ asset('storage/' . $reel->media->first()->media_url) }}"
                                class="w-full h-full object-cover" loop playsinline preload="metadata">
                            </video>

                            {{-- Transparent Click Layer for Play/Pause --}}
                            <div class="absolute inset-0 z-10" @click="togglePlayPause({{ $index }})">
                            </div>

                            {{-- Play/Pause Icon Overlay --}}
                            <div x-show="showOverlay[{{ $index }}]"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-100 scale-100"
                                x-transition:enter-end="opacity-0 scale-150"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-0 scale-150"
                                x-transition:leave-end="opacity-100 scale-100"
                                class="absolute inset-0 flex items-center justify-center pointer-events-none z-20">
                                <div class="w-20 h-20 rounded-full bg-black/40 flex items-center justify-center">
                                    <template x-if="!isPlaying[{{ $index }}]">
                                        <i class="fa-solid fa-play text-4xl text-white"></i>
                                    </template>
                                </div>
                            </div>

                            {{-- Double Tap Heart Animation --}}
                            <div x-show="showHeart[{{ $index }}]"
                                class="absolute inset-0 flex items-center justify-center pointer-events-none z-30 animate-heart">
                                <i class="fa-solid fa-heart text-8xl text-white drop-shadow-lg"></i>
                            </div>

                            {{-- Loading Spinner --}}
                            <div x-show="isLoading[{{ $index }}]"
                                class="absolute inset-0 flex items-center justify-center bg-black/20 z-40">
                                <i class="fa-solid fa-spinner fa-spin text-4xl text-white"></i>
                            </div>

                            {{-- Bottom Gradient for Better Text Visibility --}}
                            <div
                                class="absolute bottom-0 left-0 right-0 h-64 bg-gradient-to-t from-black/90 via-black/40 to-transparent pointer-events-none">
                            </div>

                            {{-- Mute/Unmute Button (Bottom Right) --}}
                            <button @click.stop="toggleMute({{ $index }})"
                                class="absolute bottom-4 right-4 z-30 w-8 h-8 rounded-lg bg-black/40 backdrop-blur-sm flex items-center justify-center hover:bg-black/60 transition">
                                <i class="fa-solid fa-volume-xmark text-white text-sm"
                                    x-show="isMuted[{{ $index }}]"></i>
                                <i class="fa-solid fa-volume-high text-white text-sm"
                                    x-show="!isMuted[{{ $index }}]"></i>
                            </button>

                            {{-- Right Side Action Buttons --}}
                            <div class="absolute right-3 bottom-28 md:bottom-36 flex flex-col items-center space-y-5 z-30">
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
                                    <button @click.stop="toggleLike()"
                                        class="focus:outline-none transition transform active:scale-125 p-1">
                                        <template x-if="liked">
                                            <i class="fa-solid fa-heart text-[28px] text-red-500 drop-shadow"></i>
                                        </template>
                                        <template x-if="!liked">
                                            <i class="fa-regular fa-heart text-[28px] text-white drop-shadow"></i>
                                        </template>
                                    </button>
                                    <span class="text-white text-[11px] font-semibold mt-0.5" x-text="count"></span>
                                </div>

                                {{-- Comment Button --}}
                                <div class="flex flex-col items-center">
                                    <button @click.stop="openComments({{ $reel->id }})" class="focus:outline-none p-1">
                                        <i class="fa-regular fa-comment text-[28px] text-white drop-shadow"></i>
                                    </button>
                                    <span
                                        class="text-white text-[11px] font-semibold mt-0.5">{{ $reel->comments_count }}</span>
                                </div>

                                {{-- Share Button --}}
                                <button @click.stop class="focus:outline-none p-1">
                                    <i class="fa-regular fa-paper-plane text-[28px] text-white drop-shadow"></i>
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
                                    <button @click.stop="toggleSave()"
                                        class="focus:outline-none transition transform active:scale-125 p-1">
                                        <template x-if="saved">
                                            <i class="fa-solid fa-bookmark text-[28px] text-white drop-shadow"></i>
                                        </template>
                                        <template x-if="!saved">
                                            <i class="fa-regular fa-bookmark text-[28px] text-white drop-shadow"></i>
                                        </template>
                                    </button>
                                </div>

                                {{-- More Options --}}
                                <button @click.stop class="focus:outline-none p-1">
                                    <i class="fa-solid fa-ellipsis text-[22px] text-white drop-shadow"></i>
                                </button>

                                {{-- Spinning Audio Disc --}}
                                <div class="w-7 h-7 rounded-md border-2 border-gray-400 overflow-hidden animate-spin-slow">
                                    <img src="{{ $reel->user->profile_picture ? asset('storage/' . $reel->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($reel->user->name) }}"
                                        class="w-full h-full object-cover" alt="Audio">
                                </div>
                            </div>

                            {{-- Bottom Left Info --}}
                            <div class="absolute left-3 bottom-4 right-16 z-30">
                                {{-- User Info --}}
                                <div class="flex items-center space-x-2.5 mb-3">
                                    <a href="{{ route('profile.show', $reel->user->username) }}">
                                        <div
                                            class="w-8 h-8 rounded-full overflow-hidden border border-white/80 flex-shrink-0">
                                            <img src="{{ $reel->user->profile_picture ? asset('storage/' . $reel->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($reel->user->name) }}"
                                                class="w-full h-full object-cover" alt="{{ $reel->user->name }}">
                                        </div>
                                    </a>
                                    <a href="{{ route('profile.show', $reel->user->username) }}"
                                        class="font-bold text-white text-[13px] hover:underline truncate">
                                        {{ $reel->user->username ?? $reel->user->name }}
                                    </a>
                                    @if (!$reel->user->isFollowing(auth()->user()) && $reel->user->id !== auth()->id())
                                        <button @click.stop="followUser({{ $reel->user->id }})"
                                            class="text-white text-xs font-semibold border border-white/80 px-3 py-1 rounded hover:bg-white/10 transition flex-shrink-0">
                                            Follow
                                        </button>
                                    @endif
                                </div>

                                {{-- Caption --}}
                                @if ($reel->caption)
                                    <p class="text-white text-[13px] mb-2.5 line-clamp-2 leading-snug">
                                        {{ $reel->caption }}
                                    </p>
                                @endif

                                {{-- Audio Info --}}
                                <div class="flex items-center space-x-1.5 text-white overflow-hidden">
                                    <i class="fa-solid fa-music text-[10px] flex-shrink-0"></i>
                                    <div class="overflow-hidden">
                                        <p class="text-[11px] whitespace-nowrap animate-marquee">
                                            Original Audio - {{ $reel->user->username ?? $reel->user->name }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-gray-700/50 z-40">
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

        {{-- Comments Modal - Fixed outside scrollable area --}}
        <div x-show="showCommentsModal" x-cloak class="fixed inset-0 z-[100] flex items-end md:items-center justify-center"
            @click.self="showCommentsModal = false">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

            {{-- Modal Content --}}
            <div class="relative bg-gray-900 w-full md:max-w-lg max-h-[70vh] rounded-t-2xl md:rounded-2xl overflow-hidden border border-gray-800 z-10"
                x-show="showCommentsModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full md:translate-y-10 md:scale-95"
                x-transition:enter-end="translate-y-0 md:translate-y-0 md:scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 md:scale-100"
                x-transition:leave-end="translate-y-full md:translate-y-10 md:scale-95" @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between p-4 border-b border-gray-800">
                    <h3 class="text-white font-bold text-base">Comments</h3>
                    <button @click="showCommentsModal = false" class="text-gray-400 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                {{-- Comments List --}}
                <div class="overflow-y-auto p-4 space-y-4" style="max-height: 50vh;">
                    <template x-for="comment in comments" :key="comment.id">
                        <div class="flex space-x-3">
                            <a href="#" class="flex-shrink-0">
                                <img :src="comment.user.profile_picture ?
                                            '/storage/' + comment.user.profile_picture :
                                            'https://ui-avatars.com/api/?name=' + encodeURIComponent(comment.user.name)"
                                 class="w-8 h-8 rounded-full object-cover border border-gray-700">
                        </a>
                        <div class="flex-1 min-w-0">
                            <div class="bg-gray-800/50 rounded-xl px-3.5 py-2">
                                <p class="text-white text-[13px]">
                                    <span class="font-semibold text-[13px]" x-text="comment.user.username || comment.user.name"></span>
                                    <span class="text-[13px]" x-text="comment.body"></span>
                                </p>
                            </div>
                            <div class="flex items-center space-x-4 mt-1.5 px-1">
                                <span class="text-[11px] text-gray-500" x-text="comment.created_at_formatted"></span>
                                <button class="text-[11px] text-gray-500 font-semibold hover:text-gray-300 transition">Reply</button>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="comments.length === 0" class="text-center py-10 text-gray-500 text-sm">
                    <i class="fa-regular fa-comment text-3xl mb-2 block"></i>
                    <p>No comments yet</p>
                </div>
            </div>

            {{-- Comment Input --}}
            <div class="p-4 border-t border-gray-800">
                <form @submit.prevent="submitComment" class="flex items-center space-x-2">
                    <input type="text" 
                           x-model="newComment" 
                           placeholder="Add a comment..."
                           class="flex-1 bg-gray-800/50 rounded-full text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-gray-600 px-4 py-2">
                    <button type="submit" 
                            class="text-blue-500 text-sm font-semibold hover:text-blue-400 transition disabled:opacity-30 disabled:cursor-not-allowed"
                            :disabled="!newComment.trim()">
                        Post
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function reelsPlayer() {
            return {
                currentReel: 0,
                isPlaying: [],
                isMuted: [],
                showOverlay: [],
                showHeart: [],
                isLoading: [],
                showCommentsModal: false,
                comments: [],
                newComment: '',
                currentPostId: null,
                observer: null,
                lastTap: [],

                init() {
                    @foreach ($reels as $index => $reel)
                        this.isPlaying[{{ $index }}] = false;
                        this.isMuted[{{ $index }}] = true;
                        this.showOverlay[{{ $index }}] = false;
                        this.showHeart[{{ $index }}] = false;
                        this.isLoading[{{ $index }}] = true;
                        this.lastTap[{{ $index }}] = 0;
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
                        // 🔧 PRODUCTION NOTE FOR MUTE:
                        // Abhi muted=true hai testing ke liye.
                        // Future production ke liye:
                        // Option 1: video.muted = false; (direct unmute)
                        // Option 2: localStorage se user preference lo
                        // const savedMute = localStorage.getItem('reelMuted');
                        // video.muted = savedMute === null ? true : savedMute === 'true';
                        // Option 3: Browser autoplay policy handle karo
                        video.muted = true; // ✅ Testing: true | Production: false ya user preference
                        this.isMuted[index] = true;

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
                    const now = Date.now();
                    const timeSinceLastTap = now - this.lastTap[index];

                    // Double tap detection (within 300ms)
                    if (timeSinceLastTap < 300) {
                        this.showHeart[index] = true;
                        setTimeout(() => {
                            this.showHeart[index] = false;
                        }, 800);
                        this.lastTap[index] = 0;
                    } else {
                        this.lastTap[index] = now;

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
                    }
                },

                toggleMute(index) {
                    const video = this.$refs['video' + index];
                    if (video) {
                        video.muted = !video.muted;
                        this.isMuted[index] = video.muted;
                        // 🔧 PRODUCTION: User preference save karo
                        // localStorage.setItem('reelMuted', video.muted.toString());
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
                    // UI update ke liye page reload ya button state change karo
                    // location.reload(); // Simple solution: reload to show updated state
                })
                .catch(err => {
                    console.error('Follow error:', err);
                });
        }
    </script>
@endpush
