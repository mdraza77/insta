<div class="space-y-3">
    @foreach ($comments as $comment)
        <div class="text-sm">
            <div class="flex items-start space-x-2">
                {{-- Comment User Avatar --}}
                <a href="{{ route('profile.view', $comment->user->id ?? 'profile') }}" class="flex-shrink-0">
                    <img src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                        alt="{{ $comment->user->name }}" class="w-6 h-6 rounded-full object-cover border border-gray-700">
                </a>

                {{-- Comment Content --}}
                <div class="flex-1">
                    <div class="bg-gray-900 rounded-lg px-3 py-2">
                        <p class="text-white">
                            <a href="{{ route('profile.view', $comment->user->id ?? 'profile') }}"
                                class="font-bold text-sm text-white hover:underline">
                                {{ $comment->user->username ?? $comment->user->name }}
                            </a>
                            <span class="text-sm">{{ $comment->body }}</span>
                        </p>
                    </div>

                    {{-- Comment Actions --}}
                    <div class="flex items-center space-x-3 mt-1 px-2">
                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>

                        @if ($showReply)
                            <button @click="showReplyForm = {{ $comment->id }}"
                                class="text-xs text-gray-500 hover:text-gray-300 font-semibold">
                                Reply
                            </button>
                        @endif

                        @if (auth()->id() === $comment->user_id)
                            <button @click="deleteComment({{ $comment->id }})"
                                class="text-xs text-red-500 hover:text-red-400">
                                Delete
                            </button>
                        @endif
                    </div>

                    {{-- Reply Form --}}
                    @if ($showReply)
                        <div x-show="showReplyForm === {{ $comment->id }}" x-cloak class="mt-2 ml-2">
                            <form @submit.prevent="submitReply({{ $comment->id }})"
                                class="flex items-center space-x-2">
                                <input type="text" x-model="replyText" placeholder="Add a reply..."
                                    class="flex-1 bg-transparent text-sm text-white placeholder-gray-500 focus:outline-none">
                                <button type="submit" class="text-blue-500 text-sm font-semibold hover:text-blue-400">
                                    Post
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Replies --}}
                    @if ($comment->replies && $comment->replies->count() > 0)
                        <div class="mt-2 ml-8 space-y-2">
                            @foreach ($comment->replies as $reply)
                                <div class="text-sm">
                                    <p class="text-white">
                                        <a href="{{ route('profile.view', $reply->user->id ?? 'profile') }}"
                                            class="font-bold text-sm text-white hover:underline">
                                            {{ $reply->user->username ?? $reply->user->name }}
                                        </a>
                                        <span class="text-sm">{{ $reply->body }}</span>
                                    </p>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span
                                            class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                        @if (auth()->id() === $reply->user_id)
                                            <button @click="deleteComment({{ $reply->id }})"
                                                class="text-xs text-red-500 hover:text-red-400">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
