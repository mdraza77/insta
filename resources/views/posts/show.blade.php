@extends('layouts.main')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-zinc-900 rounded-xl overflow-hidden flex flex-col md:flex-row border border-gray-800">
            <div class="w-full md:w-2/3 bg-black">
                @if ($post->is_reel)
                    <video src="{{ asset('storage/' . $post->media->first()->media_url) }}" controls class="w-full"></video>
                @else
                    <img src="{{ asset('storage/' . $post->media->first()->media_url) }}" class="w-full object-contain">
                @endif
            </div>
            <div class="w-full md:w-1/3 p-4 flex flex-col">
                <div class="flex items-center gap-2 mb-4">
                    <img src="..." class="w-8 h-8 rounded-full">
                    <span class="font-bold">{{ $post->user->username }}</span>
                </div>
                <div class="flex-1 overflow-y-auto"></div>
            </div>
        </div>
    </div>
@endsection
