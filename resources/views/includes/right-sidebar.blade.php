<div class="fixed w-[320px] hidden lg:block">
    {{-- <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4 cursor-pointer">
            <div class="w-12 h-12 rounded-full overflow-hidden border border-spheria-border bg-gray-900">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                    alt="my-avatar" class="w-full h-full object-cover">
            </div>
            <div>
                <h4 class="font-bold text-sm leading-none text-white">
                    {{ auth()->user()->username ?? auth()->user()->name }}
                </h4>
                <span class="text-xs text-gray-500 capitalize">{{ auth()->user()->name }}</span>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="text-xs font-bold text-purple-500 hover:text-purple-400">Switch</a>
    </div> --}}

    <div class="flex items-center justify-between mb-4">
        <span class="text-sm font-bold text-gray-400 uppercase tracking-wider text-[11px]">Suggested for you</span>
        <button class="text-xs font-bold text-white hover:text-gray-400">See All</button>
    </div>

    <div class="space-y-4">
        @foreach ($suggestions as $user)
            <div class="flex items-center justify-between" x-data="{
                isFollowing: false,
                toggleFollow() {
                    // Check: Kya route ka naam 'user.follow' hi hai?
                    fetch('{{ route('user.follow', $user->id) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.isFollowing = (data.status === 'following');
                        })
                        .catch(err => console.error('Error:', err));
                }
            }">

                <a href="{{ route('profile.show', $user->username) }}">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full overflow-hidden border border-spheria-border bg-gray-900">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-white leading-none">{{ $user->username ?? $user->name }}
                            </h5>
                            <p class="text-[10px] text-gray-500 mt-0.5">Suggested for you</p>
                        </div>
                    </div>
                </a>

                <button @click="toggleFollow"
                    :class="isFollowing ? 'text-gray-400' : 'text-purple-500 hover:text-white'"
                    class="text-xs font-bold transition-colors focus:outline-none"
                    x-text="isFollowing ? 'Following' : 'Follow'">
                </button>
            </div>
        @endforeach
    </div>

    <div class="mt-10 opacity-30">
        <nav class="flex flex-wrap gap-2 text-[10px] text-gray-400 uppercase tracking-widest leading-loose">
            <a href="#" class="hover:underline">About</a>
            <a href="#" class="hover:underline">Help</a>
            <a href="#" class="hover:underline">Privacy</a>
            <a href="#" class="hover:underline">Terms</a>
        </nav>
        <p class="text-[10px] text-gray-500 mt-4 font-bold tracking-widest italic uppercase text-white">
            © 2026 SPHERIA BY RAZA
        </p>
    </div>
</div>
