<!DOCTYPE html>
<html class="h-full" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'RoomReserve')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="h-full bg-gray-50 text-gray-900 flex flex-col" style="font-family: 'Inter', sans-serif;">
    @include('partials.navbar')

    <div class="flex flex-1 pt-16 min-h-screen w-full max-w-7xl ml-10   ">
        @yield('sidebar')

        <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>