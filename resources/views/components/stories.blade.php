<div x-data="{
    open: false,
    activeUserStories: [],
    currentIndex: 0,
    timer: null,

    openStory(stories) {
        if (!stories || stories.length === 0) return;
        this.activeUserStories = stories;
        this.currentIndex = 0;
        this.open = true;
        this.startTimer();
    },

    next() {
        if (this.currentIndex < this.activeUserStories.length - 1) {
            this.currentIndex++;
            this.startTimer();
        } else {
            this.closeStory();
        }
    },

    prev() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.startTimer();
        }
    },

    startTimer() {
        clearTimeout(this.timer);
        // Har story 5 second tak rukegi
        this.timer = setTimeout(() => {
            this.next();
        }, 5000);
    },

    closeStory() {
        this.open = false;
        clearTimeout(this.timer);
        this.activeUserStories = [];
    }
}" @keydown.escape.window="closeStory()">

    <div class="flex items-center space-x-4 overflow-x-auto py-4 no-scrollbar border-b border-gray-800 px-2">

        <div class="flex flex-col items-center space-y-1 flex-shrink-0 cursor-pointer pl-2" x-data="{
            uploading: false,
            progress: 0,
            async uploadStory(e) {
                let file = e.target.files[0];
                if (!file) return;
                this.uploading = true;
                let formData = new FormData();
                formData.append('media', file);
                try {
                    const res = await axios.post('{{ route('stories.store') }}', formData, {
                        onUploadProgress: (p) => this.progress = Math.round((p.loaded * 100) / p.total)
                    });
                    if (res.data.success) window.location.reload();
                } catch (e) { alert('Upload failed. Try smaller size.'); } finally { this.uploading = false; }
            }
        }">
            <input type="file" x-ref="storyInput" class="hidden" @change="uploadStory" accept="image/*,video/*">
            <div class="relative p-[2px] rounded-full bg-gradient-to-tr from-yellow-400 to-purple-600"
                @click="$refs.storyInput.click()">
                <div class="p-1 bg-black rounded-full relative">
                    <template x-if="uploading">
                        <div class="absolute inset-0 z-10 flex items-center justify-center bg-black/60 rounded-full text-white font-bold text-[10px]"
                            x-text="progress + '%'"></div>
                    </template>
                    <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . auth()->user()->name }}"
                        class="w-16 h-16 rounded-full object-cover" :class="uploading ? 'opacity-30' : ''">
                </div>
                <div class="absolute bottom-0 right-0 bg-blue-500 rounded-full border-2 border-black p-[2px]">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </div>
            <span class="text-[11px] text-gray-400">Your Story</span>
        </div>

        @isset($users_with_stories)
            @foreach ($users_with_stories as $user)
                <div class="flex flex-col items-center space-y-1 flex-shrink-0 cursor-pointer group"
                    @click="openStory({{ $user->stories->toJson() }})">
                    <div
                        class="p-[2px] rounded-full bg-gradient-to-tr from-yellow-400 to-purple-600 group-active:scale-95 transition duration-150">
                        <div class="p-[2px] bg-black rounded-full">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . $user->name }}"
                                class="w-16 h-16 rounded-full object-cover border border-gray-900">
                        </div>
                    </div>
                    <span class="text-[11px] text-gray-400 truncate w-16 text-center">{{ $user->name }}</span>
                </div>
            @endforeach
        @endisset
    </div>

    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[100] bg-black flex items-center justify-center select-none"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            <button @click="closeStory()" class="absolute top-6 right-6 text-white z-[110] hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="absolute top-4 left-0 right-0 flex px-4 space-x-1.5 z-[110]">
                <template x-for="(story, index) in activeUserStories" :key="index">
                    <div class="h-[2px] flex-1 bg-gray-600 rounded-full overflow-hidden">
                        <div class="h-full bg-white transition-all duration-100"
                            :style="index < currentIndex ? 'width: 100%' : (index === currentIndex ? '' : 'width: 0%')"
                            :class="index === currentIndex ? 'animate-story-progress' : ''">
                        </div>
                    </div>
                </template>
            </div>

            <div class="relative w-full max-w-lg h-[90vh] flex items-center justify-center">

                <div @click="prev()" class="absolute left-0 top-0 w-1/3 h-full z-20 cursor-pointer"></div>
                <div @click="next()" class="absolute right-0 top-0 w-1/3 h-full z-20 cursor-pointer"></div>

                <template x-for="(story, index) in activeUserStories" :key="index">
                    <div x-show="currentIndex === index" class="absolute inset-0 flex items-center justify-center">

                        <template x-for="media in story.media" :key="media.id">
                            <div class="w-full h-full flex items-center justify-center">

                                <template x-if="media.media_type === 'image'">
                                    <img :src="'{{ asset('storage') }}/' + media.media_url"
                                        class="max-h-full max-w-full object-contain shadow-2xl">
                                </template>

                                <template x-if="media.media_type === 'video'">
                                    <video :src="'{{ asset('storage') }}/' + media.media_url" autoplay muted playsinline
                                        loop class="max-h-full max-w-full object-contain shadow-2xl">
                                    </video>
                                </template>
                            </div>
                        </template>

                    </div>
                </template>
            </div>
        </div>
    </template>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    @keyframes storyProgress {
        from {
            width: 0%;
        }

        to {
            width: 100%;
        }
    }

    .animate-story-progress {
        animation: storyProgress 5s linear forwards;
    }
</style>
