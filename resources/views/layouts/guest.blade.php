<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RoomReserve')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-red-50 via-white to-red-50 flex flex-col items-center justify-center px-4" style="font-family: 'Lato', 'Noto Sans Lao', sans-serif;">

    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-red-700">RoomReserve</h1>
        <p class="text-gray-400 text-sm mt-1">ລະບົບຈອງຫ້ອງປະຊຸມພາຍໃນບໍລິສັດລາວໂມບາຍມັນນີ</p>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-red-100 p-8">
        @yield('content')
    </div>

    <p class="text-xs text-gray-400 mt-8">&copy; {{ date('Y') }} RoomReserve. All rights reserved.</p>

</body>
</html>