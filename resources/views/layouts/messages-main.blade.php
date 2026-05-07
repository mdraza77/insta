<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Zing'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Hide scrollbar for reels */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Marquee animation for audio text */
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            animation: marquee 10s linear infinite;
        }

        /* Snap scroll behavior */
        .snap-y-mandatory {
            scroll-snap-type: y mandatory;
        }

        .snap-start {
            scroll-snap-align: start;
        }

        /* Play/pause overlay animation */
        .play-pause-overlay {
            transition: all 0.3s ease;
        }

        /* Heart animation */
        @keyframes heartBeat {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 0;
            }
        }

        .animate-heart {
            animation: heartBeat 0.8s ease-in-out forwards;
        }

        /* Spinning audio disc */
        @keyframes spin-slow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-black text-white antialiased font-sans">
    <div class="flex h-screen">
        {{-- Left Navigation --}}
        @include('layouts.navigation')

        {{-- Main Content Area --}}
        <main class="flex-1 md:ml-64 h-screen overflow-hidden bg-black">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
