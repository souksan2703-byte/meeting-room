<!DOCTYPE html>
<html class="h-full" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'RoomReserve')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <!-- ใส่ Tailwind Config ที่นี่ -->
    @stack('styles')
</head>
<body class="h-full bg-background text-on-background flex flex-col font-body-lg text-body-lg" style="font-family: 'Google Sans', 'Phetsarath', sans-serif;">
    @include('partials.navbar')

    <div class="flex flex-1 pt-16 h-full overflow-hidden w-full max-w-container-max mx-auto">
        @yield('sidebar')
        
        <main class="flex-1 overflow-y-auto bg-background p-lg">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>