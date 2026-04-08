<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
        {{-- Profile Header Section --}}
        <div class="flex flex-col md:flex-row items-center md:items-start gap-8 mb-12 border-b border-gray-800 pb-10">
            {{-- Avatar --}}
            <div class="relative w-32 h-32 md:w-40 md:h-40">

                <!-- IMAGE -->
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                    class="w-full h-full object-cover rounded-full border-2 border-purple-600 p-1">

                @if (auth()->id() === $user->id)
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
                @endif

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
                    @endif
                </div>

                {{-- Stats --}}
                <div class="flex justify-center md:justify-start gap-8 mb-6">
                    <div class="text-center md:text-left">
                        <span class="font-bold text-white block md:inline">{{ $user->posts_count }}</span>
                        <span class="text-gray-500 text-sm">Posts</span>
                    </div>
                    <div class="text-center md:text-left cursor-pointer hover:opacity-70">
                        <span class="font-bold text-white block md:inline">{{ $user->followers_count }}</span>
                        <span class="text-gray-500 text-sm">Followers</span>
                    </div>
                    <div class="text-center md:text-left cursor-pointer hover:opacity-70">
                        <span class="font-bold text-white block md:inline">{{ $user->following_count }}</span>
                        <span class="text-gray-500 text-sm">Following</span>
                    </div>
                </div>

                {{-- Bio --}}
                <div class="space-y-1">
                    <h3 class="font-bold text-white">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-300">{{ $user->bio ?? 'No bio yet. ✨' }}</p>
                </div>
            </div>
        </div>

        {{-- Posts Grid Section --}}
        <div class="grid grid-cols-3 gap-1 md:gap-4">
            @forelse($posts as $post)
                <a href="#"
                    class="relative aspect-square group overflow-hidden bg-gray-900 rounded-sm hover:opacity-90">
                    @if ($post->media->first()->media_type === 'video')
                        <video src="{{ asset('storage/' . $post->media->first()->media_url) }}"
                            class="w-full h-full object-cover"></video>
                        <div class="absolute top-2 right-2 text-white"><i class="fa-solid fa-video text-xs"></i></div>
                    @else
                        <img src="{{ asset('storage/' . $post->media->first()->media_url) }}"
                            class="w-full h-full object-cover">
                    @endif

                    {{-- Hover Overlay --}}
                    <div
                        class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                        <div class="flex gap-6 text-white font-bold">
                            <span><i class="fa-solid fa-heart mr-1"></i> {{ $post->likes_count }}</span>
                            <span><i class="fa-solid fa-comment mr-1"></i> {{ $post->comments_count }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-20 text-gray-500">
                    <i class="fa-solid fa-camera text-4xl mb-4 block"></i>
                    <p class="uppercase tracking-widest text-sm font-bold">No Posts Yet</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
