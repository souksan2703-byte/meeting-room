<!DOCTYPE html>
<html class="h-full" lang="en-GB">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'RoomReserve')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="h-full bg-gray-50 text-gray-900 flex flex-col" style="font-family: 'Lato', 'Noto Sans Lao', sans-serif;">
    @include('partials.navbar')

    <div class="flex flex-1 pt-16 min-h-screen w-full max-w-7xl mx-auto">
        @yield('sidebar')

        <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>