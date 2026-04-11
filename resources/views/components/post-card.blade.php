<div class="bg-black border border-spheria-border rounded-xl mb-8 overflow-hidden" x-data="{
    comments: [],
    newComment: '',
    showComments: false,
    loading: false,
    replyTo: null,
    replyText: '',
    async loadComments() {
        this.loading = true;
        try {
            const res = await fetch('{{ route('comments.index', $post) }}');
            const data = await res.json();
            if (data.success) {
                this.comments = data.comments;
            }
        } catch (error) {
            console.error('Error loading comments:', error);
        } finally {
            this.loading = false;
        }
    },
    async submitComment() {
        if (!this.newComment.trim()) return;

        try {
            const res = await fetch('{{ route('comments.store', $post) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ body: this.newComment })
            });
            const data = await res.json();
            if (data.success) {
                this.comments.unshift(data.comment);
                this.newComment = '';
                // Refresh comment count
                window.dispatchEvent(new CustomEvent('comment-added', { detail: { postId: {{ $post->id }} } }));
            }
        } catch (error) {
            console.error('Error submitting comment:', error);
        }
    },
    async submitReply(commentId) {
        if (!this.replyText.trim()) return;

        try {
            const res = await fetch('{{ route('comments.store', $post) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    body: this.replyText,
                    parent_id: commentId
                })
            });
            const data = await res.json();
            if (data.success) {
                // Reload comments to show new reply
                await this.loadComments();
                this.replyTo = null;
                this.replyText = '';
            }
        } catch (error) {
            console.error('Error submitting reply:', error);
        }
    },
    async deleteComment(commentId) {
        if (!confirm('Are you sure you want to delete this comment?')) return;

        try {
            const res = await fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                this.comments = this.comments.filter(c => c.id !== commentId);
            }
        } catch (error) {
            console.error('Error deleting comment:', error);
        }
    },
    toggleComments() {
        this.showComments = !this.showComments;
        if (this.showComments && this.comments.length === 0) {
            this.loadComments();
        }
    }
}">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('profile.show', $post->user->username) }}">
                <div class="w-10 h-10 rounded-full overflow-hidden border border-spheria-border bg-gray-900">
                    {{-- User Profile Picture --}}
                    <img src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                        alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                </div>
            </a>
            <div>
                <a href="{{ route('profile.show', $post->user->username) }}">
                    {{-- User Name --}}
                    <h4 class="font-bold text-sm leading-none text-white">
                        {{ $post->user->username ?? $post->user->name }}
                    </h4>
                </a>
                {{-- Location --}}
                @if ($post->location)
                    <span class="text-[10px] text-gray-500 uppercase tracking-tighter">{{ $post->location }}</span>
                @endif
            </div>
        </div>
        <button class="text-gray-400"><i class="fa-solid fa-ellipsis"></i></button>
    </div>

    <div id="carousel-{{ $post->id }}" class="relative w-full" data-carousel="static">
        <div class="relative aspect-square overflow-hidden border-y border-spheria-border bg-spheria-gray">
            @if ($post->media && $post->media->isNotEmpty())
                @foreach ($post->media as $index => $item)
                    <div class="hidden duration-700 ease-in-out"
                        data-carousel-item="{{ $index == 0 ? 'active' : '' }}">
                        @if ($item->media_type === 'video')
                            {{-- Video Player --}}
                            <video src="{{ asset('storage/' . $item->media_url) }}"
                                class="absolute block w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                autoplay muted playsinline controls>
                            </video>
                        @else
                            {{-- Image Player --}}
                            <img src="{{ asset('storage/' . $item->media_url) }}"
                                class="absolute block w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                alt="Post Media">
                        @endif
                    </div>
                @endforeach
            @else
                {{-- Placeholder if no media --}}
                <div class="flex items-center justify-center h-full text-gray-700">
                    <i class="fa-regular fa-image text-4xl"></i>
                </div>
            @endif
        </div>

        @if ($post->media->count() > 1)
            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-2">
                @foreach ($post->media as $index => $item)
                    <button type="button" class="w-1.5 h-1.5 rounded-full bg-white/50"
                        aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"
                        data-carousel-slide-to="{{ $index }}"></button>
                @endforeach
            </div>

            <button type="button"
                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
                <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-black/20 group-hover:bg-black/40 transition">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7" />
                    </svg>
                </span>
            </button>
            <button type="button"
                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
                <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-black/20 group-hover:bg-black/40 transition">
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
                            }).then(res => res.json())
                            .then(data => {
                                this.count = data.likes_count;
                            });
                    }
                }">
                    <div class="flex items-center space-x-5 mb-4">
                        {{-- Like Heart Icon --}}
                        <button @click="toggleLike" class="focus:outline-none transition transform active:scale-125">
                            <template x-if="liked">
                                <i class="fa-solid fa-heart text-2xl text-red-500"></i>
                            </template>
                            <template x-if="!liked">
                                <i class="fa-regular fa-heart text-2xl text-white hover:text-red-500"></i>
                            </template>
                        </button>

                        <i class="fa-regular fa-comment text-2xl cursor-pointer hover:text-purple-500 transition text-white"
                            @click="toggleComments"></i>
                        <i
                            class="fa-regular fa-paper-plane text-2xl cursor-pointer hover:text-blue-500 transition text-white"></i>
                    </div>

                    {{-- Likes Count Display --}}
                    <p class="text-sm font-bold text-white">
                        <span x-text="count"></span> Likes
                    </p>
                </div>
            </div>
            {{-- <i class="fa-regular fa-bookmark text-2xl cursor-pointer hover:text-yellow-500 transition text-white"></i> --}}

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
                <button @click="toggleSave" class="focus:outline-none transition transform active:scale-125">
                    <template x-if="saved">
                        <i class="fa-solid fa-bookmark text-2xl text-white"></i>
                    </template>
                    <template x-if="!saved">
                        <i class="fa-regular fa-bookmark text-2xl text-white hover:text-gray-400"></i>
                    </template>
                </button>
            </div>
        </div>

        <div class="space-y-1">
            {{-- Likes Count --}}
            {{-- <p class="text-sm font-bold text-white">{{ number_format($post->likes_count) }} Likes</p> --}}

            {{-- Caption --}}
            @if ($post->caption)
                <p class="text-sm text-white">
                    <span class="font-bold mr-2">{{ $post->user->username ?? $post->user->name }}</span>
                    {{ $post->caption }}
                </p>
            @endif

            {{-- Comments Count --}}
            @if ($post->comments_count > 0)
                <button @click="toggleComments" class="text-gray-500 text-xs py-1 hover:text-gray-300 transition">
                    View all {{ $post->comments_count }} comments
                </button>
            @endif

            {{-- Post Time --}}
            <p class="text-[10px] text-gray-600 uppercase mt-1">{{ $post->created_at->diffForHumans() }}</p>
        </div>

        {{-- Comments Section --}}
        <div x-show="showComments" x-cloak class="mt-4 border-t border-gray-800 pt-4">
            {{-- Loading State --}}
            <div x-show="loading" class="text-center py-4">
                <i class="fa-solid fa-spinner fa-spin text-gray-400 text-2xl"></i>
            </div>

            {{-- Comments List --}}
            <div x-show="!loading" class="space-y-3 max-h-64 overflow-y-auto">
                <template x-for="comment in comments" :key="comment.id">
                    <div class="text-sm">
                        <div class="flex items-start space-x-2">
                            {{-- Comment User Avatar --}}
                            <a href="#" class="flex-shrink-0">
                                <img :src="comment.user.profile_picture ?
                                    '/storage/' + comment.user.profile_picture :
                                    'https://ui-avatars.com/api/?name=' + encodeURIComponent(comment.user.name)"
                                    :alt="comment.user.name" 
                                    class="w-6 h-6 rounded-full object-cover border border-gray-700">
                            </a>
                            
                            {{-- Comment Content --}}
                            <div class="flex-1">
                                <div class="bg-gray-900 rounded-lg px-3 py-2">
                                    <p class="text-white">
                                        <a href="#"
                                           class="font-bold text-sm text-white hover:underline"
                                           x-text="comment.user.username || comment.user.name">
                                        </a>
                                        <span class="text-sm" x-text="comment.body"></span>
                                    </p>
                                </div>
                                
                                {{-- Comment Actions --}}
                                <div class="flex items-center space-x-3 mt-1 px-2">
                                    <span class="text-xs text-gray-500" x-text="comment.created_at_formatted"></span>
                                    
                                    <button @click="replyTo = comment.id; replyText = ' '"
                                    class="text-xs text-gray-500 hover:text-gray-300 font-semibold">
                                Reply
                                </button>

                                <button @click="deleteComment(comment.id)"
                                    class="text-xs text-red-500 hover:text-red-400">
                                    Delete
                                </button>
                        </div>

                        {{-- Reply Input --}}
                        <div x-show="replyTo === comment.id" x-cloak class="mt-2 ml-2">
                            <form @submit.prevent="submitReply(comment.id)" class="flex items-center space-x-2">
                                <input type="text" x-model="replyText" placeholder="Reply to this comment..."
                                    class="flex-1 bg-transparent text-sm text-white placeholder-gray-500 focus:outline-none">
                                <button type="submit"
                                    class="text-blue-500 text-sm font-semibold hover:text-blue-400 transition">
                                    Reply
                                </button>
                                <button type="button" @click="replyTo = null"
                                    class="text-gray-500 text-sm hover:text-gray-300">
                                    Cancel
                                </button>
                            </form>
                        </div>

                        {{-- Replies --}}
                        <template x-if="comment.replies && comment.replies.length > 0">
                            <div class="mt-2 ml-8 space-y-2">
                                <template x-for="reply in comment.replies" :key="reply.id">
                                    <div class="text-sm">
                                        <p class="text-white">
                                            <a href="#" class="font-bold text-sm text-white hover:underline"
                                                x-text="reply.user.username || reply.user.name">
                                            </a>
                                            <span class="text-sm" x-text="reply.body"></span>
                                        </p>
                                        <div class="flex items-center space-x-3 mt-1">
                                            <span class="text-xs text-gray-500"
                                                x-text="reply.created_at_formatted"></span>
                                            <button @click="deleteComment(reply.id)"
                                                class="text-xs text-red-500 hover:text-red-400">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
            </div>
        </div>
        </template>

        {{-- No Comments Message --}}
        <div x-show="comments.length === 0" class="text-center py-4 text-gray-500 text-sm">
            No comments yet. Be the first to comment!
        </div>
    </div>

    {{-- Comment Input --}}
    <div class="mt-4 border-t border-gray-800 pt-4">
        <form @submit.prevent="submitComment" class="flex items-center space-x-2">
            <input type="text" x-model="newComment" placeholder="Add a comment..."
                class="flex-1 bg-transparent text-sm text-white placeholder-gray-500 focus:outline-none">
            <button type="submit" class="text-blue-500 text-sm font-semibold hover:text-blue-400 transition"
                :disabled="!newComment.trim()" :class="{ 'opacity-50 cursor-not-allowed': !newComment.trim() }">
                Post
            </button>
        </form>
    </div>
</div>
</div>
</div>
