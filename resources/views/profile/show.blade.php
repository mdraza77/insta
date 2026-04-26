<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4" x-data="{ tab: 'posts' }">

        {{-- Profile Header Section --}}
        <div class="flex flex-col md:flex-row items-center md:items-start gap-8 mb-12 border-b border-gray-800 pb-10">
            {{-- Avatar --}}
            <div class="relative w-32 h-32 md:w-40 md:h-40">

                <!-- IMAGE -->
                @php
                    $displayUser = auth()->id() === $user->id ? auth()->user() : $user;
                @endphp

                <img src="{{ $displayUser->profile_picture ? asset('storage/' . $displayUser->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($displayUser->name) }}"
                    class="w-full h-full object-cover rounded-full border-2 border-purple-600 p-1">

                {{-- @if (auth()->id() === $user->id)
                    <!-- Small Button -->
                    <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data"
                        class="absolute bottom-1 right-1">
                        @csrf

                        <label
                            class="bg-transparent hover:bg-white/10 backdrop-blur-sm text-white p-2 rounded-full cursor-pointer transition border-purple-600">
                            <i class="fa-solid fa-pen text-xs"></i>

                            <input type="file" name="photo" class="hidden" onchange="this.form.submit()">
                        </label>
                    </form>
                @endif --}}

            </div>

            {{-- Info --}}
            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col md:flex-row items-center gap-4 mb-6">
                    <h2 class="text-2xl font-bold text-white">{{ $user->username ?? $user->name }}</h2>

                    @if (auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}"
                            class="px-6 py-1.5 bg-spheria-gray border border-gray-700 rounded-lg text-sm font-bold hover:bg-gray-800 transition">
                            Edit Profile
                        </a>
                    @else
                        {{-- Follow/Unfollow Component humne pehle banaya tha wahi yahan use hoga --}}
                        @include('components.follow-button', ['user' => $user])

                        {{-- Message Button --}}
                        <a href="{{ route('messages.chat', $user->username) }}"
                            class="px-6 py-1.5 bg-spheria-gray border border-gray-700 rounded-lg text-sm font-bold hover:bg-gray-800 transition text-white flex items-center justify-center">
                            Message
                        </a>

                        {{-- Optional: Profile Suggestion Icon --}}
                        <button
                            class="p-2 bg-spheria-gray border border-gray-700 rounded-lg hover:bg-gray-800 transition text-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.5 10.5a.5.5 0 01.5.5V12a.5.5 0 01-.5.5h-1.5a.5.5 0 01-.5-.5v-1.5a.5.5 0 01.5-.5h1.5zM6 4a4 4 0 110 8 4 4 0 010-8zm0 2a2 2 0 100 4 2 2 0 000-4zm10 4a4 4 0 110 8 4 4 0 010-8zm0 2a2 2 0 100 4 2 2 0 000-4zM6 14c-2.21 0-4 1.79-4 4v2h8v-2c0-2.21-1.79-4-4-4zm10 4c0-2.21-1.79-4-4-4h-1.1c.36.53.64 1.13.84 1.79.43.08.81.21 1.16.4l.1.06c.65.41 1 1.03 1 1.75v2h8v-2c0-2.21-1.79-4-4-4z" />
                            </svg>
                        </button>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="flex justify-center md:justify-start gap-8 mb-6" x-data="{ showFollowers: false, showFollowings: false }">
                    <div class="text-center md:text-left">
                        <span class="font-bold text-white block md:inline">{{ $user->posts_count }}</span>
                        <span class="text-gray-500 text-sm">Posts</span>
                    </div>

                    {{-- Followers List Trigger --}}
                    <div class="text-center md:text-left cursor-pointer hover:opacity-70" @click="showFollowers = true">
                        <span class="font-bold text-white block md:inline">{{ $user->followers_count }}</span>
                        <span class="text-gray-500 text-sm">Followers</span>

                        <template x-teleport="body">
                            <div x-show="showFollowers"
                                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" x-cloak>
                                <div class="bg-zinc-900 border border-zinc-800 w-full max-w-sm rounded-xl overflow-hidden"
                                    @click.away="showFollowers = false">
                                    <div class="p-4 border-b border-zinc-800 flex justify-between items-center">
                                        <h3 class="text-white font-semibold">Followers</h3>
                                        <button @click="showFollowers = false"
                                            class="text-zinc-400 text-2xl">&times;</button>
                                    </div>

                                    <div class="max-h-[400px] overflow-y-auto no-scrollbar p-2">
                                        @forelse ($user->followers as $follower)
                                            {{-- Har follower ki apni state --}}
                                            <div x-data="{
                                                isRemoved: false,
                                                removeFollower() {
                                                    // Seedhe fetch call, koi alert/confirm nahi
                                                    fetch('{{ route('follower.remove', $follower->id) }}', {
                                                            method: 'POST',
                                                            headers: {
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                'Content-Type': 'application/json'
                                                            }
                                                        })
                                                        .then(res => res.json())
                                                        .then(data => {
                                                            if (data.status === 'success') {
                                                                this.isRemoved = true;
                                                            }
                                                        });
                                                }
                                            }"
                                                class="flex items-center justify-between p-3 hover:bg-zinc-800/50 rounded-lg group">

                                                <div class="flex items-center space-x-3"
                                                    :class="isRemoved ? 'opacity-40' : ''">
                                                    <img src="{{ $follower->profile_picture ? asset('storage/' . $follower->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($follower->username) }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-white text-sm font-semibold">{{ $follower->username }}</span>
                                                        <span
                                                            class="text-gray-500 text-xs">{{ $follower->name }}</span>
                                                    </div>
                                                </div>

                                                {{-- Remove Button: Click par state change hogi --}}
                                                <button @click="removeFollower()" :disabled="isRemoved"
                                                    :class="isRemoved ? 'bg-transparent text-gray-500 border border-zinc-800' :
                                                        'bg-zinc-800 text-white hover:bg-zinc-700'"
                                                    class="px-4 py-1.5 rounded-lg text-xs font-semibold transition min-w-[80px]"
                                                    x-text="isRemoved ? 'Removed' : 'Remove'">
                                                </button>
                                            </div>
                                        @empty
                                            <div class="p-8 text-center text-gray-500 text-sm">No followers yet.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="text-center md:text-left cursor-pointer hover:opacity-70"
                        @click="showFollowings = true">
                        <span class="font-bold text-white block md:inline">{{ $user->following_count }}</span>
                        <span class="text-gray-500 text-sm">Following</span>

                        <template x-teleport="body">
                            <div x-show="showFollowings"
                                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" x-cloak>
                                <div class="bg-zinc-900 border border-zinc-800 w-full max-w-sm rounded-xl overflow-hidden"
                                    @click.away="showFollowings = false">
                                    <div class="p-4 border-b border-zinc-800 flex justify-between items-center">
                                        <h3 class="text-white font-semibold">Followings</h3>
                                        <button @click="showFollowings = false"
                                            class="text-zinc-400 text-2xl">&times;</button>
                                    </div>

                                    <div class="max-h-[400px] overflow-y-auto no-scrollbar p-2">
                                        @forelse ($user->following as $following)
                                            <div class="flex items-center justify-between p-3 hover:bg-zinc-800/50 rounded-lg group"
                                                x-data="{
                                                    isFollowing: true,
                                                    toggleFollow() {
                                                        fetch('{{ route('user.follow', $following->id) }}', {
                                                                method: 'POST',
                                                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                                                            })
                                                            .then(res => res.json())
                                                            .then(data => { this.isFollowing = (data.status === 'following'); });
                                                    }
                                                }">
                                                <div class="flex items-center space-x-3">
                                                    <a href="{{ route('profile.show', $following->username) }}">
                                                        <img src="{{ $following->profile_picture ? asset('storage/' . $following->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($following->username) }}"
                                                            class="w-10 h-10 rounded-full object-cover">
                                                    </a>
                                                    <div class="flex flex-col">
                                                        <a href="{{ route('profile.show', $following->username) }}"
                                                            class="text-white text-sm font-semibold hover:underline">
                                                            {{ $following->username }}
                                                        </a>
                                                        <span
                                                            class="text-gray-500 text-xs">{{ $following->name }}</span>
                                                    </div>
                                                </div>

                                                {{-- Unfollow Button --}}
                                                <button @click="toggleFollow"
                                                    :class="isFollowing ? 'bg-zinc-800 text-white' : 'bg-blue-600 text-white'"
                                                    class="px-4 py-1.5 rounded-lg text-xs font-semibold transition"
                                                    x-text="isFollowing ? 'Unfollow' : 'Follow'">
                                                </button>
                                            </div>
                                        @empty
                                            <div class="p-8 text-center text-gray-500 text-sm">Not following anyone.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Bio --}}
                <div class="space-y-1">
                    <h3 class="font-bold text-white">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-300">{{ $user->bio ?? 'No bio yet. ✨' }}</p>
                    <p class="text-sm text-gray-300">{{ $user->email ?? 'email yet. ✨' }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-center border-gray-800 gap-12 mb-6">
            <button @click="tab = 'posts'"
                :class="tab === 'posts' ? 'border-t border-white text-white' : 'text-gray-500'"
                class="flex items-center gap-2 pt-4 px-2 uppercase tracking-widest text-[12px] font-bold transition-all">
                <i class="fa-solid fa-table-cells"></i> Posts
            </button>

            <button @click="tab = 'reels'"
                :class="tab === 'reels' ? 'border-t border-white text-white' : 'text-gray-500'"
                class="flex items-center gap-2 pt-4 px-2 uppercase tracking-widest text-[12px] font-bold transition-all">
                <i class="fa-solid fa-clapperboard"></i> Reels
            </button>

            @if (auth()->id() === $user->id)
                <button @click="tab = 'saved'"
                    :class="tab === 'saved' ? 'border-t border-white text-white' : 'text-gray-500'"
                    class="flex items-center gap-2 pt-4 px-2 uppercase tracking-widest text-[12px] font-bold transition-all">
                    <i class="fa-regular fa-bookmark"></i> Saved
                </button>
            @endif
        </div>

        {{-- Posts Tab --}}
        <div x-show="tab === 'posts'" x-cloak class="grid grid-cols-3 gap-1 md:gap-4">
            @forelse($posts as $post)
                @include('profile.partials.grid-item', ['post' => $post])
            @empty
                <div class="col-span-3 text-center py-20 text-gray-600 italic">No posts yet.</div>
            @endforelse
        </div>

        {{-- Reels Tab --}}
        <div x-show="tab === 'reels'" x-cloak class="grid grid-cols-3 gap-1 md:gap-4">
            @forelse($reels as $reel)
                @include('profile.partials.grid-item', ['post' => $reel, 'isReel' => true])
            @empty
                <div class="col-span-3 text-center py-20 text-gray-600 italic">No reels yet.</div>
            @endforelse
        </div>

        {{-- Saved Tab --}}
        @if (auth()->id() === $user->id)
            <div x-show="tab === 'saved'" x-cloak class="grid grid-cols-3 gap-1 md:gap-4">
                @forelse($savedPosts as $saved)
                    @include('profile.partials.grid-item', ['post' => $saved])
                @empty
                    <div class="col-span-3 text-center py-20 text-gray-600 italic">No saved posts.</div>
                @endforelse
            </div>
        @endif

    </div>
</x-app-layout>
