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
                    @include('messages.single-message', ['msg' => $msg])
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
        document.addEventListener('DOMContentLoaded', function() {
            const chatWindow = document.getElementById('chat-window');
            const msgForm = document.getElementById('msg-form');
            const fileInput = document.getElementById('media-upload');
            const textInput = msgForm.querySelector('input[type="text"]');
            const previewContainer = document.getElementById('media-preview-container');
            const previewContent = document.getElementById('preview-content');
            const removeMediaBtn = document.getElementById('remove-media');

            // Last ID for Polling
            let lastMessageId = {{ $messages->last()?->id ?? 0 }};

            const scrollChat = () => {
                if (chatWindow) chatWindow.scrollTop = chatWindow.scrollHeight;
            };

            // --- Polling Logic ---
            // async function fetchNewMessages() {
            //     try {
            //         const response = await axios.get(
            //             `{{ route('messages.fetch', $conversation->id) }}?last_id=${lastMessageId}`);

            //         if (response.data.html !== '') {
            //             chatWindow.insertAdjacentHTML('beforeend', response.data.html);
            //             lastMessageId = response.data.new_last_id;
            //             scrollChat();
            //         }
            //     } catch (error) {
            //         console.error('Polling error:', error);
            //     }
            // }
            async function fetchNewMessages() {
                try {
                    const response = await axios.get(
                        `{{ route('messages.fetch', $conversation->id) }}?last_id=${lastMessageId}`);

                    if (response.data.html !== '') {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = response.data.html;

                        const incomingMessages = tempDiv.querySelectorAll('[id^="msg-"]');

                        incomingMessages.forEach(msgEl => {
                            if (!document.getElementById(msgEl.id)) {
                                chatWindow.insertAdjacentElement('beforeend', msgEl);
                            }
                        });

                        lastMessageId = response.data.new_last_id;
                        scrollChat();
                    }
                } catch (error) {
                    console.error('Polling error:', error);
                }
            }

            // Check for new messages every 3 seconds
            setInterval(fetchNewMessages, 3000);

            // --- Media Preview Logic ---
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    previewContent.innerHTML = '';
                    reader.onload = function(e) {
                        let html = file.type.includes('image') ?
                            `<img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">` :
                            file.type.includes('video') ?
                            `<video src="${e.target.result}" class="w-full h-32 object-cover rounded-lg" muted autoplay></video>` :
                            `<div class="p-3 text-xs text-white bg-zinc-800 rounded-lg"><i class="fa-solid fa-file"></i> ${file.name}</div>`;

                        previewContent.innerHTML = html;
                        previewContainer.classList.remove('hidden');
                        scrollChat();
                    }
                    reader.readAsDataURL(file);
                }
            });

            removeMediaBtn.addEventListener('click', () => {
                fileInput.value = '';
                previewContainer.classList.add('hidden');
                previewContent.innerHTML = '';
            });

            // --- Submit Logic (Simplified to prevent duplicates) ---
            msgForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const messageBody = textInput.value.trim();
                if (!messageBody && !fileInput.files[0]) return;

                const formData = new FormData();
                formData.append('body', messageBody);
                if (fileInput.files[0]) formData.append('media', fileInput.files[0]);

                const submitBtn = e.target.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                try {
                    const res = await axios.post("{{ route('messages.send', $conversation->id) }}",
                        formData);

                    if (res.data.success) {
                        // 1. Reset the form and preview
                        msgForm.reset();
                        previewContainer.classList.add('hidden');
                        previewContent.innerHTML = '';

                        // 2. CRITICAL: Don't manually inject HTML here.
                        // Instead, trigger Polling immediately so the message appears once.
                        fetchNewMessages();
                    }
                } catch (err) {
                    console.error("Submission Error:", err);
                    alert('Error: Could not send message.');
                } finally {
                    submitBtn.disabled = false;
                }
            });

            // Initial scroll
            scrollChat();
        });
    </script>

    <style>
        .media-card {
            transition: transform 0.2s;
        }

        .media-card:hover {
            transform: scale(1.01);
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
