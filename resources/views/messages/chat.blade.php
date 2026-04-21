<x-app-layout>
    <div
        class="max-w-6xl mx-auto h-[calc(100vh-80px)] flex border border-gray-800 rounded-lg overflow-hidden bg-black mt-4">

        @include('messages.sidebar', ['activeReceiver' => $receiver])

        <div class="flex-1 flex flex-col bg-black overflow-hidden">

            <div class="p-4 border-b border-gray-800 flex items-center justify-between bg-black">
                <div class="flex items-center">
                    <a href="{{ route('profile.show', $receiver->username) }}">
                        <img src="{{ $receiver->profile_picture ? asset('storage/' . $receiver->profile_picture) : 'https://ui-avatars.com/api/?name=' . $receiver->name }}"
                            class="w-10 h-10 rounded-full object-cover">
                    </a>
                    <div class="ml-3">
                        <p class="text-white font-bold text-sm leading-tight">{{ $receiver->name }}</p>
                        <p class="text-gray-500 text-[10px]">Active now</p>
                    </div>
                </div>
            </div>

            <div id="chat-window" class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar bg-black">
                @forelse ($messages as $msg)
                    <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-[70%] px-4 py-2 rounded-2xl text-sm 
                            {{ $msg->sender_id == auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-zinc-800 text-white rounded-bl-none' }}">
                            {{ $msg->body }}
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-gray-500 italic text-sm">
                        No messages yet. Say hi! 👋
                    </div>
                @endforelse
            </div>

            <div class="p-4 bg-black border-t border-gray-800">
                <form id="msg-form"
                    class="flex items-center bg-zinc-900 border border-gray-700 rounded-full px-4 py-2 focus-within:border-gray-500 transition">
                    <input type="text" placeholder="Message..."
                        class="flex-1 bg-transparent border-none text-white focus:ring-0 text-sm">
                    <button type="submit"
                        class="ml-2 text-blue-500 font-bold text-sm hover:text-white transition">Send</button>
                </form>
            </div>
        </div>

    </div>

    <script>
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
</x-app-layout>
