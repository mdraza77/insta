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
</head>

<body class="bg-black text-white antialiased font-sans">
    <div class="flex min-h-screen">
        @include('layouts.navigation')

        <main class="flex-1 md:ml-64 pb-20 md:pb-0">
            <div class="max-w-[600px] mx-auto py-8 px-4 md:px-0">
                {{ $slot }}
            </div>
        </main>

        <aside class="hidden lg:block w-[350px] pr-8 pt-8">
            @include('includes.right-sidebar')
        </aside>
    </div>
</body>

</html>
