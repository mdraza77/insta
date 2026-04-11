<a href="#" class="relative aspect-square group overflow-hidden bg-gray-900 rounded-sm">
    @php $firstMedia = $post->media->first(); @endphp

    @if ($firstMedia->media_type === 'video')
        <video src="{{ asset('storage/' . $firstMedia->media_url) }}" class="w-full h-full object-cover"></video>
        <div class="absolute top-2 right-2 text-white drop-shadow-lg">
            <i class="fa-solid fa-clapperboard text-sm"></i>
        </div>
    @else
        <img src="{{ asset('storage/' . $firstMedia->media_url) }}" class="w-full h-full object-cover">
    @endif

    {{-- Overlay on Hover --}}
    <div
        class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
        <div class="flex gap-6 text-white font-bold">
            <span><i class="fa-solid fa-heart"></i> {{ $post->likes_count ?? 0 }}</span>
            <span><i class="fa-solid fa-comment"></i> {{ $post->comments_count ?? 0 }}</span>
        </div>
    </div>
</a>
