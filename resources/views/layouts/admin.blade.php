<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RoomReserve Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen" style="font-family: 'Lato', 'Noto Sans Lao', sans-serif;">

    {{-- Navbar เดิมของเว็บ (โลโก้ + เมนู Dashboard/Rooms/My Bookings/Profile/Approvals) --}}
    @include('partials.navbar')

    {{-- Mobile overlay สำหรับปิด sidebar --}}
    <div id="sidebar-overlay" onclick="toggleSidebar()"
         class="hidden fixed inset-0 bg-black/40 z-40 md:hidden"></div>

    {{-- Sidebar เฉพาะหน้า Admin — อยู่ใต้ navbar (top-16) --}}
    <aside id="admin-sidebar"
           class="w-64 shrink-0 bg-white border-r border-gray-200 flex flex-col justify-between fixed left-0 top-16 bottom-0 z-40
                  transform -translate-x-full md:translate-x-0 transition-transform duration-200">
        <div class="overflow-y-auto">
            <div class="p-4">
                <a href="{{ route('admin.rooms.create') }}"
                   class="block text-center bg-red-700 text-white rounded-lg py-2.5 text-sm font-semibold hover:bg-red-600">
                    + Add New Room
                </a>
            </div>

            <nav class="px-3 space-y-1">
                <a href="{{ route('admin.rooms.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 hover:translate-x-1 {{ request()->routeIs('admin.rooms.*') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50' }}">
                    🏢 Rooms
                </a>
                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 hover:translate-x-1 {{ request()->routeIs('admin.bookings.*') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50' }}">
                    📅 Bookings
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 hover:translate-x-1 {{ request()->routeIs('admin.users.*') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50' }}">
                    👥 Users
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 hover:translate-x-1 {{ request()->routeIs('admin.reports.*') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50' }}">
                    📊 Reports
                </a>
            </nav>
        </div>

        <div class="p-3 border-t border-gray-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-150 hover:translate-x-1">
                ⚙️ Back to Dashboard
            </a>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="pt-16 md:ml-64">
        {{-- แถบเล็กๆ สำหรับเปิด sidebar บนมือถือ --}}
        <div class="md:hidden bg-white border-b border-gray-200 px-4 py-2 sticky top-16 z-30">
            <button type="button" onclick="toggleSidebar()" class="text-gray-500 text-sm flex items-center gap-2">
                ☰ Admin Menu
            </button>
        </div>

        <main class="p-4 md:p-6">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('admin-sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }
    </script>

</body>
</html>