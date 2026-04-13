<x-app-layout>
    <div class="py-4">
        <div class="mb-8">
            @include('components.stories')
        </div>

        <div class="flex flex-col">
            @foreach ($posts as $post)
                @include('components.post-card', ['post' => $post])
            @endforeach
        </div>
    </div>
</x-app-layout>

{{-- <x-modal name="create-post" focusable>
    <div class="bg-black border border-gray-800 p-6 rounded-xl">
        <h2 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2">Nayi Post Daalo</h2>

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Photos Select Karo</label>
                <input type="file" name="media[]" multiple required
                    class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer">
                <p class="text-[10px] text-gray-500 mt-1">Tip: Tum ek saath 2-3 photos select karke carousel bana sakte
                    ho.</p>
            </div>

            <div>
                <textarea name="caption" rows="3"
                    class="w-full bg-spheria-gray border-gray-800 rounded-lg text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Kuch desi caption likho..."></textarea>
            </div>

            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                    <i class="fa-solid fa-location-dot"></i>
                </span>
                <input type="text" name="location"
                    class="w-full bg-spheria-gray border-gray-800 rounded-lg pl-10 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Location (e.g. Kolkata, India)">
            </div>

            <div class="flex justify-end pt-2">
                <x-secondary-button x-on:click="$dispatch('close')" class="mr-2">Cancel</x-secondary-button>
                <x-primary-button class="bg-purple-600 hover:bg-purple-700">Post</x-primary-button>
            </div>
        </form>
    </div>
</x-modal> --}}

<x-modal name="create-post" focusable>
    <div class="bg-black border border-gray-800 p-6 rounded-xl">

        <h2 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2">
            Create New Post
        </h2>

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- TOGGLE -->
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white text-sm font-medium">Post Type</p>
                    <p class="text-xs text-gray-500">Switch to Reel to upload video only</p>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input id="reelToggle" type="checkbox" name="is_reel" class="sr-only peer">

                    <div class="w-11 h-6 bg-gray-700 rounded-full peer peer-checked:bg-purple-600 transition"></div>

                    <div
                        class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition 
            peer-checked:translate-x-5">
                    </div>
                </label>
            </div>

            <!-- MEDIA -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">
                    Select Media
                </label>

                <input id="mediaInput" type="file" name="media[]" multiple required
                    class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer">

                <p id="mediaHint" class="text-xs text-gray-500 mt-1">
                    You can upload multiple images or videos to create a carousel post.
                </p>
            </div>

            <!-- CAPTION -->
            <div>
                <textarea name="caption" rows="3"
                    class="w-full bg-[#1e1e2f] border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 outline-none"
                    placeholder="Write a caption..."></textarea>
            </div>

            <!-- TAGS -->
            <div>
                <input type="text" name="tags"
                    class="w-full bg-[#1e1e2f] border border-gray-700 rounded-lg text-white placeholder-gray-500 px-4 py-2 focus:ring-2 focus:ring-purple-500 outline-none"
                    placeholder="Add tags (e.g. travel, food, coding)">

                <p class="text-xs text-gray-500 mt-1">
                    Separate tags using commas.
                </p>
            </div>

            <!-- LOCATION -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                    <i class="fa-solid fa-location-dot"></i>
                </span>

                <input type="text" name="location"
                    class="w-full bg-[#1e1e2f] border border-gray-700 rounded-lg pl-10 text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 outline-none"
                    placeholder="Add location">
            </div>

            <!-- ACTIONS -->
            <div class="flex justify-end pt-2">
                <x-secondary-button x-on:click="$dispatch('close')" class="mr-2">
                    Cancel
                </x-secondary-button>

                <x-primary-button class="bg-purple-600 hover:bg-purple-700">
                    Publish
                </x-primary-button>
            </div>

        </form>
    </div>
</x-modal>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const toggle = document.getElementById('reelToggle');
        const input = document.getElementById('mediaInput');
        const hint = document.getElementById('mediaHint');

        toggle.addEventListener('change', function() {

            if (this.checked) {
                // Reel Mode
                input.removeAttribute('multiple');
                input.setAttribute('accept', 'video/*');

                hint.innerText = "Only one video can be uploaded for reels.";
            } else {
                // Normal Post
                input.setAttribute('multiple', true);
                input.setAttribute('accept', 'image/*,video/*');

                hint.innerText = "You can upload multiple images or videos to create a carousel post.";
            }

            // reset selected files
            input.value = '';
        });

    });
</script>
