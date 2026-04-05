<div class="bg-black border border-spheria-border rounded-xl mb-8 overflow-hidden">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border border-spheria-border">
                <img src="https://i.pravatar.cc/150?u=srijan" alt="avatar">
            </div>
            <div>
                <h4 class="font-bold text-sm leading-none">Srijan</h4>
                <span class="text-[10px] text-gray-500 uppercase tracking-tighter">Kolkata, India</span>
            </div>
        </div>
        <button class="text-gray-400"><i class="fa-solid fa-ellipsis"></i></button>
    </div>

    <div class="w-full bg-spheria-gray">
        @if ($post->media && $post->media->isNotEmpty())
            {{-- Agar ek se zyada image hai toh carousel (baad mein), abhi pehli dikhao --}}
            <img src="{{ asset('storage/' . $post->media->first()->media_url) }}" class="w-full object-cover"
                alt="Post Media">
        @else
            {{-- Agar koi image nahi mili toh placeholder dikhao taaki error na aaye --}}
            <div class="w-full h-64 bg-gray-900 flex items-center justify-center">
                <i class="fa-regular fa-image text-4xl text-gray-700"></i>
            </div>
        @endif
    </div>

    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-5">
                <i class="fa-regular fa-heart text-2xl cursor-pointer hover:text-red-500 transition"></i>
                <i class="fa-regular fa-comment text-2xl cursor-pointer hover:text-purple-500 transition"></i>
                <i class="fa-regular fa-paper-plane text-2xl cursor-pointer hover:text-blue-500 transition"></i>
            </div>
            <i class="fa-regular fa-bookmark text-2xl cursor-pointer hover:text-yellow-500 transition"></i>
        </div>

        <div class="space-y-1">
            <p class="text-sm font-bold">4.2k Likes</p>
            <p class="text-sm">
                <span class="font-bold mr-2">Srijan</span>
                Ye naya design kaisa lag raha hai? Bilkul clean aur Spheria vibe! ⚡
            </p>
            <button class="text-gray-500 text-xs py-1">View all 128 comments</button>
        </div>
    </div>
</div>
