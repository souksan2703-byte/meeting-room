<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RoomReserve')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-red-50 via-white to-red-50 flex flex-col items-center justify-center px-4">

    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-red-700">RoomReserve</h1>
        <p class="text-gray-400 text-sm mt-1">ລະບົບການຈອງຫ້ອງປະຊຸມອອນໄລນ໌</p>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-red-100 p-8">
        @yield('content')
    </div>

    <p class="text-xs text-gray-400 mt-8">&copy; {{ date('Y') }} RoomReserve. All rights reserved.</p>

</body>
</html>