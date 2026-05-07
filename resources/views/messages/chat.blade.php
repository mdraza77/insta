@extends('layouts.main')

@section('title', config('app.name') . ' • Messages')

@section('content')
    <div class="max-w-6xl mx-auto h-[calc(100vh-30px)] flex border border-gray-800 rounded-lg overflow-hidden bg-black mt-4">

        @include('messages.sidebar', ['activeReceiver' => $receiver])

        <div class="flex-1 flex flex-col bg-black overflow-hidden">

            <div class="p-4 border-b border-gray-800 flex items-center justify-between bg-black">
                <div class="flex items-center">
                    <a title="{{ $receiver->username }}" href="{{ route('profile.show', $receiver->username) }}">
                        <img src="{{ $receiver->profile_picture ? asset('storage/' . $receiver->profile_picture) : 'https://ui-avatars.com/api/?name=' . $receiver->name }}"
                            class="w-10 h-10 rounded-full object-cover">
                    </a>
                    <div class="ml-3">
                        <p class="text-white font-bold text-sm leading-tight">{{ $receiver->name }}</p>

                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            <p class="text-gray-500 text-[10px] leading-none">Active now</p>
                        </div>
                    </div>

                </div>
            </div>

            <div id="chat-window" class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar bg-black scroll-smooth">
                @forelse ($messages as $msg)
                    <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">

                        <div title="{{ $msg->created_at->format('M d, Y h:i A') }}"
                            class="max-w-xs md:max-w-md overflow-hidden 
        @if ($msg->type === 'text') px-3 py-2 rounded-2xl shadow-sm {{ $msg->sender_id == auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-zinc-800 text-white rounded-bl-none' }}
        @else
            rounded-2xl border border-gray-800 bg-zinc-900/30 @endif">

                            {{-- 1. MEDIA SECTION (No Bubble, Full Width) --}}
                            @if ($msg->type === 'image')
                                <img src="{{ asset('storage/' . $msg->media_path) }}"
                                    class="w-full h-auto object-cover cursor-pointer" onclick="window.open(this.src)">
                            @elseif($msg->type === 'video')
                                <video controls class="w-full h-auto">
                                    <source src="{{ asset('storage/' . $msg->media_path) }}" type="video/mp4">
                                </video>
                            @elseif($msg->type === 'audio')
                                <div class="p-3 bg-zinc-800"><audio controls class="w-full h-8 invert">
                                        <source src="{{ asset('storage/' . $msg->media_path) }}" type="audio/mpeg">
                                    </audio></div>
                            @elseif($msg->type === 'file')
                                <a href="{{ asset('storage/' . $msg->media_path) }}" target="_blank"
                                    class="flex items-center gap-3 p-4 hover:bg-zinc-800 transition">
                                    <i class="fa-solid fa-file-pdf text-red-500 text-2xl"></i>
                                    <span class="text-xs text-white truncate">View Document</span>
                                </a>

                                {{-- Post / Reel Sharing Card --}}
                            @elseif ($msg->post_id && $msg->post)
                                <div class="mb-1 overflow-hidden rounded-xl bg-zinc-900 border border-white/10 group/card">
                                    {{-- <a href="{{ route('posts.show', $msg->post_id) }}" class="block"> --}}
                                    <a href="#" class="block">

                                        {{-- Header: Post Owner Details --}}
                                        <div class="p-2 flex items-center space-x-2 bg-white/5 border-b border-white/5">
                                            <img src="{{ $msg->post->user->profile_picture ? asset('storage/' . $msg->post->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($msg->post->user->name) }}"
                                                class="w-5 h-5 rounded-full object-cover">
                                            <span
                                                class="text-[11px] font-bold text-white/90 truncate">{{ $msg->post->user->username }}</span>
                                        </div>

                                        {{-- Media Section: Dynamic Image/Video --}}
                                        <div class="relative aspect-square w-full min-w-[220px] bg-black">
                                            @php
                                                $media = $msg->post?->media?->first();
                                                $isPostVideo =
                                                    $media &&
                                                    (str_contains($media->media_type, 'video') ||
                                                        str_ends_with($media->media_url, '.mp4'));
                                            @endphp

                                            @if ($media)
                                                @if ($isPostVideo)
                                                    {{-- Shared Post Video Preview --}}
                                                    <video
                                                        class="w-full h-full object-cover opacity-90 group-hover/card:opacity-100 transition"
                                                        loop onmouseover="this.play()" onmouseout="this.pause()">
                                                        <source src="{{ asset('storage/' . $media->media_url) }}"
                                                            type="video/mp4">
                                                    </video>
                                                    {{-- Video Indicator --}}
                                                    <div class="absolute top-2 right-2 bg-black/50 p-1 rounded-md">
                                                        <i class="fa-solid fa-video text-[10px] text-white"></i>
                                                    </div>
                                                @else
                                                    {{-- Shared Post Image --}}
                                                    <img src="{{ asset('storage/' . $media->media_url) }}"
                                                        class="w-full h-full object-cover opacity-90 group-hover/card:opacity-100 transition">
                                                @endif
                                            @else
                                                <div
                                                    class="w-full h-full bg-zinc-800 flex items-center justify-center text-gray-500 text-xs">
                                                    No media available</div>
                                            @endif

                                            {{-- REEL INDICATOR (Special Play Icon for Reels) --}}
                                            @if ($msg->post->is_reel)
                                                <div class="absolute inset-0 flex items-center justify-center bg-black/10">
                                                    <div
                                                        class="w-10 h-10 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md border border-white/30">
                                                        <i class="fa-solid fa-play text-white text-sm"></i>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Footer: Caption Preview --}}
                                        @if ($msg->post->caption)
                                            <div class="p-2 bg-zinc-900 border-t border-white/5">
                                                <p class="text-[10px] text-gray-400 line-clamp-1 italic">
                                                    {{ $msg->post->caption }}</p>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            @endif

                            {{-- 2. TEXT SECTION (Media ke niche wala bubble ya caption) --}}
                            @if ($msg->body)
                                <div class="px-3 py-2 {{ $msg->type !== 'text' ? 'bg-zinc-800/50' : '' }}">
                                    <p class="text-sm leading-relaxed">{{ $msg->body }}</p>

                                    {{-- Time (Inside caption for media) --}}
                                    <span class="text-[10px] text-white/50 block text-right mt-1 leading-none">
                                        {{ $msg->created_at->format('h:i A') }}
                                    </span>
                                </div>
                            @endif

                            {{-- 3. TIME (Sirf tab jab body na ho, image ke upar chota sa overlay ya niche) --}}
                            @if (!$msg->body && $msg->type !== 'text')
                                <div class="px-2 py-1 text-right">
                                    <span
                                        class="text-[9px] text-white/40 italic">{{ $msg->created_at->format('h:i A') }}</span>
                                </div>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-gray-500 italic text-sm">
                        No messages yet. Say hi!
                    </div>
                @endforelse
            </div>

            <div class="p-4 bg-black border-t border-gray-800">

                <div id="media-preview-container" class="hidden mb-2 relative inline-block">
                    <div id="preview-content" class="rounded-lg overflow-hidden border border-gray-700 max-w-[200px]"></div>
                    <button type="button" id="remove-media"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center shadow-lg">×</button>
                </div>
                <form id="msg-form"
                    class="flex items-center bg-zinc-900 border border-gray-700 rounded-full px-4 py-2 focus-within:border-gray-500 transition">

                    <label for="media-upload" class="mr-2 cursor-pointer text-gray-400 hover:text-white transition">
                        <i class="fa-regular fa-image text-xl"></i>
                    </label>
                    <input type="file" id="media-upload" class="hidden" accept="image/*,video/*,audio/*,.pdf,.doc,.docx">

                    <input type="text" placeholder="Message..."
                        class="flex-1 bg-transparent border-none text-white focus:ring-0 text-sm">

                    <button type="submit" class="ml-2 text-blue-500 font-bold text-sm hover:text-white transition">
                        <i class="fa-regular fa-paper-plane text-xl"></i>
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script>
        // --- 1. Old variables ---
        const chatWindow = document.getElementById('chat-window');
        const msgForm = document.getElementById('msg-form');
        const fileInput = document.getElementById('media-upload');
        const textInput = msgForm.querySelector('input[type="text"]');

        // --- 2. New variables for Preview ---
        const previewContainer = document.getElementById('media-preview-container');
        const previewContent = document.getElementById('preview-content');
        const removeMediaBtn = document.getElementById('remove-media');

        // --- 3. Page load scroll ---
        if (chatWindow) chatWindow.scrollTop = chatWindow.scrollHeight;

        // --- 4. NEW: Preview Generate Logic ---
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                previewContent.innerHTML = '';

                reader.onload = function(e) {
                    let html = '';
                    if (file.type.includes('image')) {
                        html = `<img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">`;
                    } else if (file.type.includes('video')) {
                        html =
                            `<video src="${e.target.result}" class="w-full h-32 object-cover rounded-lg" muted></video>`;
                    } else {
                        html = `<div class="p-3 text-xs text-white bg-zinc-800 rounded-lg">${file.name}</div>`;
                    }

                    previewContent.innerHTML = html;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // --- 5. NEW: Preview Remove Logic (X button) ---
        removeMediaBtn.addEventListener('click', () => {
            fileInput.value = '';
            previewContainer.classList.add('hidden'); // Hide preview container
            previewContent.innerHTML = '';
        });

        // --- 6. Form Submit Logic ---
        msgForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!textInput.value.trim() && !fileInput.files[0]) return;

            const formData = new FormData();
            formData.append('body', textInput.value);

            if (fileInput.files[0]) {
                formData.append('media', fileInput.files[0]);
            }

            try {
                const res = await axios.post("{{ route('messages.send', $conversation->id) }}", formData);

                if (res.data.success) {
                    window.location.reload(); // Page refresh to show new message
                }
            } catch (err) {
                console.error(err);
                alert('Sending failed!');
            }
        });
    </script>

    <style>
        .media-card {
            transition: transform 0.2s;
        }

        .media-card:hover {
            transform: scale(1.01);
        }
    </style>
@endsection
