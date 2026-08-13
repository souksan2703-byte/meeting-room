<nav class="fixed top-0 inset-x-0 h-16 z-50 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">

        {{-- Logo + Nav links --}}
        <div class="flex items-center gap-10">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-900">
                RoomReserve
            </a>

            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('dashboard') }}"
                   class="pb-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-900 text-indigo-900' : 'border-transparent text-gray-500 hover:text-indigo-900' }}">
                    Dashboard
                </a>
                <a href="{{ route('rooms.index') }}"
                   class="pb-1 border-b-2 {{ request()->routeIs('rooms.*') ? 'border-indigo-900 text-indigo-900' : 'border-transparent text-gray-500 hover:text-indigo-900' }}">
                    Rooms
                </a>
                <a href="{{ route('bookings.index') }}"
                   class="pb-1 border-b-2 {{ request()->routeIs('bookings.*') ? 'border-indigo-900 text-indigo-900' : 'border-transparent text-gray-500 hover:text-indigo-900' }}">
                    My Bookings
                </a>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.bookings.index') }}"
                           class="pb-1 border-b-2 {{ request()->routeIs('admin.*') ? 'border-indigo-900 text-indigo-900' : 'border-transparent text-gray-500 hover:text-indigo-900' }}">
                            Approvals
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Right side: profile + logout --}}
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-indigo-900">
                    {{ auth()->user()->name }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm border rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-50">
                        Logout
                    </button>
                </form>

                <div class="w-9 h-9 rounded-full bg-indigo-900 text-white flex items-center justify-center text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm text-indigo-900 font-medium">Login</a>
            @endauth
        </div>

    </div>
</nav>