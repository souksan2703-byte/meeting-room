<nav class="navbar navbar-expand-lg navbar-dark" style="background:#C62828;">
    <div class="container-fluid">

        <a
            class="navbar-brand fw-bold"
            href="{{ route('dashboard') }}"
        >
            <i class="bi bi-building"></i>
            Office Meeting Room
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="navbarNav"
        >

            <ul class="navbar-nav ms-auto">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('dashboard') }}"
                    >
                        <i class="bi bi-house-door"></i>
                        Dashboard
                    </a>
                </li>

                {{-- Rooms --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('rooms.*') ? 'active fw-bold' : '' }}"
                        href="{{ route('rooms.index') }}"
                    >
                        <i class="bi bi-door-open"></i>
                        ຫ້ອງປະຊຸມ
                    </a>
                </li>

                {{-- Bookings --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('bookings.*') ? 'active fw-bold' : '' }}"
                        href="{{ route('bookings.index') }}"
                    >
                        <i class="bi bi-calendar-check"></i>
                        ຈອງຫ້ອງ
                    </a>
                </li>

                {{-- Calendar --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('calendar') ? 'active fw-bold' : '' }}"
                        href="{{ route('calendar') }}"
                    >
                        <i class="bi bi-calendar3"></i>
                        Calendar
                    </a>
                </li>

                {{-- Reports --}}
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="#"
                    >
                        <i class="bi bi-bar-chart"></i>
                        ລາຍງານ
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>