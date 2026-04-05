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

<x-modal name="create-post" focusable>
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
                <x-primary-button class="bg-purple-600 hover:bg-purple-700">Post Karein</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
