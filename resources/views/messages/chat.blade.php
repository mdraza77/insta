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
                            class="max-w-xs md:max-w-md px-1 py-1 text-sm leading-relaxed break-words rounded-2xl shadow-sm
                {{ $msg->sender_id == auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-zinc-800 text-white rounded-bl-none' }}">

                            {{-- Agar Message mein Post ya Reel share ki gayi hai --}}
                            @if ($msg->post_id && $msg->post)
                                <div class="mb-1 overflow-hidden rounded-xl bg-zinc-900 border border-white/10">
                                    {{-- Post ka preview --}}
                                    <a href="#" class="block">
                                        {{-- Image ya Video Thumbnail --}}
                                        <div class="relative aspect-square w-full min-w-[200px]">
                                            {{-- <img src="{{ asset('storage/' . $msg->post->media->first()->file_path) }}"
                                                class="w-full h-full object-cover opacity-80 hover:opacity-100 transition"> --}}

                                            @php
                                                $media = $msg->post?->media?->first();
                                            @endphp

                                            @if ($media)
                                                <img src="{{ asset('storage/' . $media->media_url) }}"
                                                    class="w-full h-64 object-cover rounded-lg">
                                            @else
                                                <div
                                                    class="w-full h-64 bg-zinc-800 flex items-center justify-center text-gray-500">
                                                    No media
                                                </div>
                                            @endif

                                            {{-- Agar Reel hai toh play icon dikhao --}}
                                            @if ($msg->post->is_reel)
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <i class="fa-solid fa-play text-white text-2xl opacity-70"></i>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Post Owner details --}}
                                        <div class="p-2 flex items-center space-x-2 bg-zinc-900">
                                            <img src="{{ $msg->post->user->profile_picture
                                                ? asset('storage/' . $msg->post->user->profile_picture)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($msg->post->user->name) }}"
                                                class="w-5 h-5 rounded-full object-cover">
                                            <span
                                                class="text-[12px] font-semibold truncate">{{ $msg->post->user->username }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            {{-- <div
                                class="relative max-w-xs px-3 py-2 rounded-2xl {{ $msg->sender_id == auth()->id() ? 'bg-blue-600' : 'bg-zinc-800' }}">
                                <p class="text-sm pb-1">{{ $msg->body }}</p>

                                <span class="text-[10px] text-white/60 block text-right leading-none">
                                    {{ $msg->created_at->format('h:i A') }}
                                </span>
                            </div> --}}

                            {{-- Message Bubble ke andar change karein --}}
                            <div
                                class="relative max-w-xs px-1 py-1 rounded-2xl {{ $msg->sender_id == auth()->id() ? 'bg-blue-600' : 'bg-zinc-800' }}">

                                {{-- Display Image --}}
                                @if ($msg->type === 'image')
                                    <div class="mb-1">
                                        <img src="{{ asset('storage/' . $msg->media_path) }}"
                                            class="rounded-xl max-w-full h-auto object-cover cursor-pointer hover:opacity-90 transition"
                                            onclick="window.open(this.src)">
                                    </div>
                                @endif

                                {{-- Display Video --}}
                                @if ($msg->type === 'video')
                                    <div class="mb-1">
                                        <video controls class="rounded-xl max-w-full">
                                            <source src="{{ asset('storage/' . $msg->media_path) }}" type="video/mp4">
                                        </video>
                                    </div>
                                @endif

                                {{-- Text Body (sirf tab dikhayein jab body null na ho) --}}
                                @if ($msg->body)
                                    <p class="text-sm px-2 py-1">{{ $msg->body }}</p>
                                @endif

                                <span class="text-[10px] text-white/60 block text-right px-2 pb-1 leading-none">
                                    {{ $msg->created_at->format('h:i A') }}
                                </span>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-gray-500 italic text-sm">
                        No messages yet. Say hi!
                    </div>
                @endforelse
            </div>

            <div class="p-4 bg-black border-t border-gray-800">
                {{-- <form id="msg-form"
                    class="flex items-center bg-zinc-900 border border-gray-700 rounded-full px-4 py-2 focus-within:border-gray-500 transition">
                    <input type="text" placeholder="Message..."
                        class="flex-1 bg-transparent border-none text-white focus:ring-0 text-sm">
                    <button type="submit" class="ml-2 text-blue-500 font-bold text-sm hover:text-white transition"><i
                            class="fa-regular fa-paper-plane text-xl"></i></button>
                </form> --}}

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

    {{-- <script>
        // Scroll to bottom
        const chatWindow = document.getElementById('chat-window');
        if (chatWindow) chatWindow.scrollTop = chatWindow.scrollHeight;

        // Form Submit Logic
        document.getElementById('msg-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const input = e.target.querySelector('input');
            const body = input.value.trim();
            if (!body) return;

            try {
                const res = await axios.post("{{ route('messages.send', $conversation->id) }}", {
                    body
                });
                if (res.data.success) {
                    window.location.reload();
                }
            } catch (err) {
                console.error(err);
            }
        });
    </script>

    <script>
        document.getElementById('msg-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const input = e.target.querySelector('input[type="text"]');
    const fileInput = document.getElementById('media-upload');
    const body = input.value.trim();
    
    // Agar na text hai na file, toh return karein
    if (!body && !fileInput.files[0]) return;

    const formData = new FormData();
    formData.append('body', body);
    if (fileInput.files[0]) {
        formData.append('media', fileInput.files[0]);
    }

    try {
        const res = await axios.post("{{ route('messages.send', $conversation->id) }}", formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        
        if (res.data.success) {
            window.location.reload();
        }
    } catch (err) {
        console.error(err);
        alert('File upload failed!');
    }
});
    </script> --}}

    {{-- <script>
    // 1. Page load par scroll neeche karein
    const chatWindow = document.getElementById('chat-window');
    if (chatWindow) chatWindow.scrollTop = chatWindow.scrollHeight;

    // 2. Single Submit Logic (Merged & Simple)
    document.getElementById('msg-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const textInput = e.target.querySelector('input[type="text"]');
        const fileInput = document.getElementById('media-upload');
        
        // Agar kuch bhi nahi hai toh return
        if (!textInput.value.trim() && !fileInput.files[0]) return;

        // FormData banayein (Files bhejni hai isliye zaroori hai)
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
</script> --}}

    <script>
        // --- 1. Puraane variables ---
        const chatWindow = document.getElementById('chat-window');
        const msgForm = document.getElementById('msg-form');
        const fileInput = document.getElementById('media-upload');
        const textInput = msgForm.querySelector('input[type="text"]');

        // --- 2. Preview ke naye variables ---
        const previewContainer = document.getElementById('media-preview-container');
        const previewContent = document.getElementById('preview-content');
        const removeMediaBtn = document.getElementById('remove-media');

        // --- 3. Page load par scroll neeche karein ---
        if (chatWindow) chatWindow.scrollTop = chatWindow.scrollHeight;

        // --- 4. NEW: Preview Generate karne ka Logic ---
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                previewContent.innerHTML = ''; // Purana preview saaf karein

                reader.onload = function(e) {
                    let html = '';
                    // Check karein file image hai ya video
                    if (file.type.includes('image')) {
                        html = `<img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">`;
                    } else if (file.type.includes('video')) {
                        html =
                            `<video src="${e.target.result}" class="w-full h-32 object-cover rounded-lg" muted></video>`;
                    } else {
                        // Audio ya anya files ke liye sirf naam dikhayein
                        html = `<div class="p-3 text-xs text-white bg-zinc-800 rounded-lg">${file.name}</div>`;
                    }

                    previewContent.innerHTML = html;
                    previewContainer.classList.remove('hidden'); // Show preview container
                }
                reader.readAsDataURL(file); // File read karna shuru karein
            }
        });

        // --- 5. NEW: Preview Remove karne ka Logic (X button) ---
        removeMediaBtn.addEventListener('click', () => {
            fileInput.value = ''; // Input saaf karein
            previewContainer.classList.add('hidden'); // Hide preview container
            previewContent.innerHTML = '';
        });

        // --- 6. Form Submit Logic (Simple) ---
        msgForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Agar kuch bhi nahi hai toh return
            if (!textInput.value.trim() && !fileInput.files[0]) return;

            // FormData banayein
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
@endsection
