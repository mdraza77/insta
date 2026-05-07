@extends('layouts.main')

@section('title', config('app.name') . ' • Messages')

@section('content')
    <div class="max-w-6xl mx-auto h-[calc(100vh-30px)] flex border border-gray-800 rounded-lg overflow-hidden bg-black mt-4">

        @include('messages.sidebar')

        <div class="hidden md:flex flex-1 flex-col items-center justify-center bg-black">
            <div class="text-center p-10">
                <div class="w-24 h-24 border-2 border-white rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-regular fa-paper-plane text-4xl text-white"></i>
                </div>
                <h2 class="text-white text-2xl font-light">Your Messages</h2>
                <p class="text-gray-500 mt-2">Send private photos and messages to a friend.</p>
                {{-- <button class="mt-6 bg-blue-500 text-white px-5 py-2 rounded-lg font-bold hover:bg-blue-600 transition">
                    Send Message
                </button> --}}
            </div>
        </div>
    </div>
@endsection
