<nav class="fixed top-0 inset-x-0 h-16 z-50 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto h-full px-4 md:px-6 flex items-center justify-between gap-6">

        {{-- Logo + Nav links --}}
        <div class="flex items-center gap-4 md:gap-10 shrink-0">
            <a href="{{ route('dashboard') }}" class="text-lg md:text-xl font-bold text-red-700 whitespace-nowrap">
                RoomReserve
            </a>

            <div class="hidden md:flex items-center gap-6 text-sm font-medium ">
                <a href="{{ route('dashboard') }}"
                   class="pb-1 border-b-2 transition-transform hover:scale-110 {{ request()->routeIs('dashboard') ? 'border-red-700 text-red-700' : 'border-transparent text-gray-500 hover:text-red-700' }}">
                    Dashboard
                </a>
                <a href="{{ route('rooms.index') }}"
                   class="pb-1 border-b-2 transition-transform hover:scale-110 {{ request()->routeIs('rooms.*') ? 'border-red-700 text-red-700' : 'border-transparent text-gray-500 hover:text-red-700' }}">
                    Rooms
                </a>
                <a href="{{ route('bookings.index') }}"
                   class="pb-1 border-b-2 transition-transform hover:scale-110 {{ request()->routeIs('bookings.*') ? 'border-red-700 text-red-700' : 'border-transparent text-gray-500 hover:text-red-700' }}">
                    My Bookings
                </a>
                @auth
                    <a href="{{ route('profile.edit') }}"
                       class="pb-1 border-b-2 transition-transform hover:scale-110 {{ request()->routeIs('profile.*') ? 'border-red-700 text-red-700' : 'border-transparent text-gray-500 hover:text-red-700' }}">
                        Profile
                    </a>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.bookings.index') }}"
                           class="pb-1 border-b-2 transition-transform hover:scale-110 {{ request()->routeIs('admin.*') ? 'border-red-700 text-red-700' : 'border-transparent text-gray-500 hover:text-red-700' }}">
                            Approvals
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Right side: icons + profile + logout --}}
        <div class="flex items-center gap-2 md:gap-4 shrink-0">
            <a href="{{ route('notifications.index') }}" title="Notifications" class="relative text-gray-400 hover:text-red-700">
                🔔
                @if (auth()->check() && auth()->user()->unreadNotificationsCount() > 0)
                    <span class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">
                        {{ auth()->user()->unreadNotificationsCount() > 9 ? '9+' : auth()->user()->unreadNotificationsCount() }}
                    </span>
                @endif
            </a>

            <button type="button" title="Help" class="hidden sm:flex text-gray-400 hover:text-red-700 border rounded-full w-6 h-6 items-center justify-center text-xs">?</button>

            @auth
                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button type="submit" class="text-sm border rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-50">
                        Logout
                    </button>
                </form>

                <div class="w-9 h-9 rounded-full bg-red-700 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                {{-- Hamburger — เฉพาะจอมือถือ --}}
                <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                        class="md:hidden text-gray-500 text-2xl leading-none px-1">
                    ☰
                </button>
            @else
                <a href="{{ route('login') }}" class="text-sm text-red-700 font-medium">Login</a>
            @endauth
        </div>

    </div>

    {{-- Mobile dropdown menu --}}
    @auth
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-200 px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block py-2 text-sm {{ request()->routeIs('dashboard') ? 'text-red-700 font-semibold' : 'text-gray-600' }}">Dashboard</a>
            <a href="{{ route('rooms.index') }}" class="block py-2 text-sm {{ request()->routeIs('rooms.*') ? 'text-red-700 font-semibold' : 'text-gray-600' }}">Rooms</a>
            <a href="{{ route('bookings.index') }}" class="block py-2 text-sm {{ request()->routeIs('bookings.*') ? 'text-red-700 font-semibold' : 'text-gray-600' }}">My Bookings</a>
            <a href="{{ route('profile.edit') }}" class="block py-2 text-sm {{ request()->routeIs('profile.*') ? 'text-red-700 font-semibold' : 'text-gray-600' }}">Profile</a>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.bookings.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.*') ? 'text-red-700 font-semibold' : 'text-gray-600' }}">Approvals</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t">
                @csrf
                <button type="submit" class="w-full text-left py-2 text-sm text-gray-600">Logout</button>
            </form>
        </div>
    @endauth
</nav>