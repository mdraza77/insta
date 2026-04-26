<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-black text-white antialiased font-sans">
    <div class="flex min-h-screen">
        @include('layouts.navigation')

        <main class="flex-1 md:ml-64 pb-20 md:pb-0">
            <div class="max-w-[700px] mx-auto py-8 px-4 md:px-0">
                {{ $slot }}
            </div>
        </main>

        <aside class="hidden lg:block w-[350px] pr-8 pt-8">
            @include('includes.right-sidebar', ['suggestions' => $suggestions ?? collect()])
        </aside>
    </div>

    {{-- Upload Modal  --}}
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
</body>

</html>
