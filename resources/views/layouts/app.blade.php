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

        /* For hiding the default scrollbar */
        html,
        body {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
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

    {{-- Upload Modal --}}
    <x-modal name="create-post" focusable>
        <div class="bg-[#121212] border border-zinc-800 rounded-2xl overflow-hidden shadow-2xl" x-data="{
            isReel: false,
            previews: [],
            handleFiles(e) {
                this.previews = [];
                const files = e.target.files;
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        this.previews.push({ url: event.target.result, type: files[i].type });
                    }
                    reader.readAsDataURL(files[i]);
                }
            }
        }">

            {{-- Header --}}
            <div class="p-4 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
                <h2 class="text-md font-bold text-white tracking-tight">Create new post</h2>
                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="flex flex-col md:flex-row h-full max-h-[80vh]">

                    {{-- Left Side: Media Preview --}}
                    <div
                        class="w-full md:w-[400px] bg-black flex items-center justify-center min-h-[300px] border-r border-zinc-800 relative">
                        {{-- Empty State --}}
                        <template x-if="previews.length === 0">
                            <div class="text-center p-10 pointer-events-none">
                                <i class="fa-regular fa-images text-5xl text-zinc-700 mb-4 block"></i>
                                <p class="text-zinc-500 text-sm">Photos and videos will appear here</p>
                            </div>
                        </template>

                        {{-- Image/Video Preview --}}
                        <div class="w-full h-full overflow-hidden flex items-center justify-center bg-black">
                            <template x-if="previews.length > 0">
                                <div class="relative w-full h-full">
                                    <template x-if="previews[0].type.includes('image')">
                                        <img :src="previews[0].url" class="w-full h-full object-contain">
                                    </template>
                                    <template x-if="previews[0].type.includes('video')">
                                        <video :src="previews[0].url" class="w-full h-full object-contain"
                                            controls></video>
                                    </template>
                                    <div x-show="previews.length > 1"
                                        class="absolute bottom-4 right-4 bg-black/60 text-white text-[10px] px-2 py-1 rounded-full">
                                        + <span x-text="previews.length - 1"></span> more
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Right Side: Details --}}
                    <div class="flex-1 p-5 space-y-5 overflow-y-auto no-scrollbar">

                        {{-- User Profile Header --}}
                        <div class="flex items-center space-x-3">
                            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . auth()->user()->name }}"
                                class="w-8 h-8 rounded-full object-cover border border-zinc-700">
                            <span class="text-white text-sm font-semibold">{{ auth()->user()->username }}</span>
                        </div>

                        {{-- Caption --}}
                        <div>
                            <textarea name="caption" rows="4"
                                class="w-full bg-transparent border-none text-white placeholder-zinc-600 focus:ring-0 p-0 text-sm outline-none resize-none overflow-y-auto no-scrollbar"
                                placeholder="Write a caption..."></textarea>
                        </div>

                        <hr class="border-zinc-800">

                        {{-- Inputs --}}
                        <div class="space-y-4">
                            {{-- Media Input --}}
                            <div>
                                <label
                                    class="cursor-pointer bg-zinc-800 hover:bg-zinc-700 text-white text-[12px] px-3 py-2 rounded-lg transition inline-block">
                                    <i class="fa-solid fa-plus mr-1"></i> Add from computer
                                    <input id="mediaInput" type="file" name="media[]" multiple required
                                        class="hidden" @change="handleFiles">
                                </label>
                            </div>

                            {{-- Toggle --}}
                            <div class="flex items-center justify-between">
                                <label class="text-sm text-zinc-300">Is this a Reel?</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_reel" class="sr-only peer" x-model="isReel">
                                    <div
                                        class="w-9 h-5 bg-zinc-700 rounded-full peer peer-checked:bg-purple-600 transition after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition peer-checked:after:translate-x-4">
                                    </div>
                                </label>
                            </div>

                            {{-- Tags & Location --}}
                            <div class="space-y-3">
                                <div class="flex items-center border-b border-zinc-800 pb-2">
                                    <i class="fa-solid fa-tag text-zinc-500 mr-2 text-xs"></i>
                                    <input type="text" name="tags"
                                        class="bg-transparent border-none text-sm text-white focus:ring-0 p-0 w-full placeholder-zinc-600"
                                        placeholder="Add tags...">
                                </div>
                                <div class="flex items-center border-b border-zinc-800 pb-2">
                                    <i class="fa-solid fa-location-dot text-zinc-500 mr-2 text-xs"></i>
                                    <input type="text" name="location"
                                        class="bg-transparent border-none text-sm text-white focus:ring-0 p-0 w-full placeholder-zinc-600"
                                        placeholder="Add location...">
                                </div>
                            </div>
                        </div>

                        {{-- Footer/Publish --}}
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-xl text-sm transition shadow-lg shadow-purple-900/20">
                                Share Post
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </x-modal>
</body>

</html>
