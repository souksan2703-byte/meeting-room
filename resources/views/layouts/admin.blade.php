<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RoomReserve Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 shrink-0 bg-white border-r border-gray-200 flex flex-col justify-between fixed inset-y-0 left-0">
        <div>
            <div class="p-5 flex items-center gap-3 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-red-700 text-white flex items-center justify-center font-semibold shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-red-700 leading-tight truncate">RoomReserve</p>
                    <p class="text-xs text-gray-400">Enterprise Admin</p>
                </div>
            </div>

            <div class="p-4">
                <a href="{{ route('rooms.index') }}"
                   class="block text-center bg-red-700 text-white rounded-lg py-2.5 text-sm font-semibold hover:bg-red-600">
                    + New Booking
                </a>
            </div>

            <nav class="px-3 space-y-1">
                <a href="{{ route('rooms.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('rooms.*') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50' }}">
                    🏢 Rooms
                </a>
                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.bookings.*') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50' }}">
                    📅 Bookings
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50' }}">
                    👥 Users
                </a>
                <span class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 cursor-not-allowed" title="ยังไม่พร้อมใช้งาน">
                    📊 Reports
                </span>
            </nav>
        </div>

        <div class="p-3 border-t border-gray-100 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-50">
                ⚙️ Back to Dashboard
            </a>
            <span class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 cursor-not-allowed" title="ยังไม่พร้อมใช้งาน">
                ❓ Support
            </span>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 ml-64">
        {{-- Top bar --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-end px-6 sticky top-0 z-40">
            <div class="flex items-center gap-4 shrink-0">
                <a href="{{ route('notifications.index') }}" title="Notifications" class="relative text-gray-400 hover:text-red-700">
                    🔔
                    @if (auth()->user()->unreadNotificationsCount() > 0)
                        <span class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">
                            {{ auth()->user()->unreadNotificationsCount() > 9 ? '9+' : auth()->user()->unreadNotificationsCount() }}
                        </span>
                    @endif
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm border rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-50">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>