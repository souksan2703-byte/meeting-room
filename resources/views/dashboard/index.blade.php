@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <p class="text-gray-500">Room Availability Calendar</p>
    </div>
    <a href="{{ route('rooms.index') }}" class="bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + New Booking
    </a>
</div>

{{-- View switcher: Day / Week / Month --}}
<div class="flex items-center justify-between mb-4">
    <div class="relative flex gap-1 bg-gray-100 p-1 rounded-xl w-fit">
        @php $tabs = ['day' => 'Day', 'week' => 'Week', 'month' => 'Month']; $tabKeys = array_keys($tabs); $activeIndex = array_search($view, $tabKeys); @endphp
        {{-- แถบพื้นหลังสีแดงที่เลื่อนไปมาตาม tab ที่เลือก --}}
        <div class="absolute top-1 bottom-1 left-1 rounded-lg bg-red-700 shadow-sm transition-transform duration-300 ease-out"
             style="width: calc((100% - 0.5rem) / 3); transform: translateX(calc({{ $activeIndex }} * 100%));"></div>

        @foreach ($tabs as $key => $label)
            <a href="{{ route('dashboard', ['view' => $key, 'date' => $selectedDate]) }}"
               class="relative z-10 px-4 py-2 rounded-lg text-sm font-medium w-24 text-center transition-colors duration-200
                      {{ $view === $key ? 'text-white' : 'text-gray-600 hover:text-red-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="flex items-center gap-4 text-sm text-gray-500">
        <span><span class="inline-block w-2 h-2 rounded-full bg-gray-300 mr-1"></span>Available</span>
        <span><span class="inline-block w-2 h-2 rounded-full bg-red-700 mr-1"></span>Confirmed</span>
        <span><span class="inline-block w-2 h-2 rounded-full bg-red-400 mr-1 animate-pulse"></span>Pending</span>
    </div>
</div>

{{-- ============== DAY VIEW ============== --}}
@if ($view === 'day')
    <div class="animate-fade-in">
    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3 mb-4">
        <input type="hidden" name="view" value="day">
        <a href="{{ route('dashboard', ['view' => 'day', 'date' => \Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d')]) }}"
           class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-red-700">&lt;</a>
        <input type="date" name="date" value="{{ $selectedDate }}"
               onchange="this.form.submit()"
               class="border rounded-lg px-3 py-1.5 text-sm font-medium cursor-pointer">
        <a href="{{ route('dashboard', ['view' => 'day', 'date' => \Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d')]) }}"
           class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-red-700">&gt;</a>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <div class="min-w-[900px]">
            <div class="grid border-b" style="grid-template-columns: 220px repeat({{ count($hours) - 1 }}, 1fr);">
                <div class="p-3 text-xs font-semibold text-gray-500">ROOMS</div>
                @foreach ($hours as $hour)
                    @if (!$loop->last)
                        <div class="p-3 text-xs text-gray-500 border-l">{{ sprintf('%02d:00', $hour) }}</div>
                    @endif
                @endforeach
            </div>

            @foreach ($rooms as $room)
                <div class="grid border-b relative" style="grid-template-columns: 220px 1fr; min-height: 90px;">
                    <div class="p-3 border-r">
                        <a href="{{ route('rooms.show', $room) }}" class="font-semibold hover:underline">{{ $room->name }}</a>
                        <p class="text-xs text-gray-500 mt-1">{{ $room->capacity }} คน</p>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 grid" style="grid-template-columns: repeat({{ count($hours) - 1 }}, 1fr);">
                            @for ($i = 0; $i < count($hours) - 1; $i++)
                                <div class="border-l h-full"></div>
                            @endfor
                        </div>

                        @if ($now->format('Y-m-d') === $selectedDate && $now->hour >= $startHour && $now->hour < $endHour)
                            @php
                                $nowLeft = (($now->hour * 60 + $now->minute - $startHour * 60) / (($endHour - $startHour) * 60)) * 100;
                            @endphp
                            <div class="absolute top-0 bottom-0 w-px bg-red-500 z-10" style="left: {{ $nowLeft }}%"></div>
                        @endif

                        @foreach ($room->todayBookings as $booking)
                            <button type="button"
                               onclick="openBookingModal(this)"
                               data-title="{{ $booking->title }}"
                               data-room="{{ $room->name }}"
                               data-user="{{ $booking->user->name }}"
                               data-status="{{ $booking->status }}"
                               data-date="{{ $booking->start_time->format('D, M j, Y') }}"
                               data-time="{{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}"
                               data-attendees="{{ $booking->attendeesCount() }}"
                               data-description="{{ $booking->description }}"
                               class="absolute top-2 bottom-2 rounded-md px-3 py-2 text-white text-xs text-left cursor-pointer transition-all duration-150 hover:scale-[1.03] hover:shadow-md hover:z-20
                                      {{ $booking->status === 'pending' ? 'bg-red-50 text-red-600 border border-red-200 animate-pulse' : 'bg-red-700' }}"
                               style="left: {{ $booking->position['left'] }}%; width: {{ $booking->position['width'] }}%;">
                                <p class="font-semibold uppercase tracking-wide truncate">
                                    @if ($booking->status === 'pending') &#9200; PENDING @else {{ $booking->title }} @endif
                                </p>
                                <p class="truncate">{{ $booking->status === 'pending' ? $booking->title : $booking->user->name }}</p>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>
@endif

{{-- ============== WEEK VIEW ============== --}}
@if ($view === 'week')
    <div class="animate-fade-in">
    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3 mb-4">
        <input type="hidden" name="view" value="week">
        <a href="{{ route('dashboard', ['view' => 'week', 'date' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}"
           class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-red-700">&lt;</a>
        <input type="date" name="date" value="{{ $selectedDate }}"
               onchange="this.form.submit()"
               class="border rounded-lg px-3 py-1.5 text-sm font-medium cursor-pointer">
        <span class="text-sm text-gray-500">{{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}</span>
        <a href="{{ route('dashboard', ['view' => 'week', 'date' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}"
           class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-red-700">&gt;</a>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <div class="min-w-[1000px]">
            <div class="grid border-b" style="grid-template-columns: 180px repeat(7, 1fr);">
                <div class="p-3 text-xs font-semibold text-gray-500">ROOMS</div>
                @foreach ($weekDays as $day)
                    <div class="p-3 text-xs text-gray-500 border-l text-center {{ $day->isToday() ? 'bg-red-50 text-red-700 font-semibold' : '' }}">
                        {{ $day->format('D') }}<br>
                        <span class="text-sm">{{ $day->format('j') }}</span>
                    </div>
                @endforeach
            </div>

            @foreach ($rooms as $room)
                <div class="grid border-b" style="grid-template-columns: 180px repeat(7, 1fr); min-height: 70px;">
                    <div class="p-3 border-r">
                        <a href="{{ route('rooms.show', $room) }}" class="font-semibold text-sm hover:underline">{{ $room->name }}</a>
                    </div>

                    @foreach ($weekDays as $day)
                        @php
                            $cellBookings = $weekBookings->get($room->id . '_' . $day->format('Y-m-d'), collect());
                        @endphp
                        <div class="border-l p-1.5 space-y-1">
                            @foreach ($cellBookings as $booking)
                                <button type="button"
                                   onclick="openBookingModal(this)"
                                   data-title="{{ $booking->title }}"
                                   data-room="{{ $room->name }}"
                                   data-user="{{ $booking->user->name }}"
                                   data-status="{{ $booking->status }}"
                                   data-date="{{ $booking->start_time->format('D, M j, Y') }}"
                                   data-time="{{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}"
                                   data-attendees="{{ $booking->attendeesCount() }}"
                                   data-description="{{ $booking->description }}"
                                   class="block w-full text-left rounded px-1.5 py-1 text-[11px] leading-tight truncate cursor-pointer
                                          {{ $booking->status === 'pending' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-red-700 text-white' }}">
                                    {{ $booking->start_time->format('H:i') }} {{ $booking->title }}
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    </div>
@endif

{{-- ============== MONTH VIEW ============== --}}
@if ($view === 'month')
    <div class="animate-fade-in">
    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3 mb-4">
        <input type="hidden" name="view" value="month">
        <a href="{{ route('dashboard', ['view' => 'month', 'date' => $monthCursor->copy()->subMonth()->format('Y-m-d')]) }}"
           class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-red-700">&lt;</a>
        <input type="month" name="date" value="{{ $monthCursor->format('Y-m') }}"
               onchange="this.form.submit()"
               class="border rounded-lg px-3 py-1.5 text-sm font-medium cursor-pointer">
        <span class="text-sm text-gray-500">{{ $monthCursor->format('F Y') }}</span>
        <a href="{{ route('dashboard', ['view' => 'month', 'date' => $monthCursor->copy()->addMonth()->format('Y-m-d')]) }}"
           class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-red-700">&gt;</a>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="grid grid-cols-7 border-b bg-gray-50">
            @foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
                <div class="p-2 text-xs font-semibold text-gray-500 text-center">{{ $d }}</div>
            @endforeach
        </div>

        @foreach ($monthWeeks as $week)
            <div class="grid grid-cols-7 border-b">
                @foreach ($week as $day)
                    @php
                        $dayBookings = $monthBookings->get($day->format('Y-m-d'), collect());
                        $isCurrentMonth = $day->month === $monthCursor->month;
                        $dayUrl = route('dashboard', ['view' => 'day', 'date' => $day->format('Y-m-d')]);
                    @endphp
                    <div onclick="window.location.href='{{ $dayUrl }}'"
                         class="border-l p-2 min-h-[100px] cursor-pointer hover:bg-gray-50 {{ !$isCurrentMonth ? 'bg-gray-50 text-gray-300' : '' }} {{ $day->isToday() ? 'bg-red-50' : '' }}">
                        <p class="text-xs font-medium mb-1 {{ $day->isToday() ? 'text-red-700 font-bold' : '' }}">{{ $day->format('j') }}</p>
                        @foreach ($dayBookings->take(3) as $booking)
                            <button type="button"
                               onclick="event.stopPropagation(); openBookingModal(this)"
                               data-title="{{ $booking->title }}"
                               data-room="{{ $booking->room->name }}"
                               data-user="{{ $booking->user->name }}"
                               data-status="{{ $booking->status }}"
                               data-date="{{ $booking->start_time->format('D, M j, Y') }}"
                               data-time="{{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}"
                               data-attendees="{{ $booking->attendeesCount() }}"
                               data-description="{{ $booking->description }}"
                               class="block w-full text-left text-[10px] truncate rounded px-1 py-0.5 mb-0.5 cursor-pointer
                                      {{ $booking->status === 'pending' ? 'bg-red-50 text-red-600' : 'bg-red-100 text-red-600' }}">
                                {{ $booking->start_time->format('H:i') }} {{ $booking->title }}
                            </button>
                        @endforeach
                        @if ($dayBookings->count() > 3)
                            <p class="text-[10px] text-gray-400">+{{ $dayBookings->count() - 3 }} more</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    </div>
@endif

{{-- ============== BOOKING DETAIL MODAL ============== --}}
<div id="booking-modal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" onclick="if(event.target===this) closeBookingModal()">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6 relative max-h-[85vh] overflow-y-auto">
        <button type="button" onclick="closeBookingModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-lg leading-none">✕</button>

        <span id="modal-status" class="text-xs px-2 py-1 rounded-full font-medium inline-block mb-3"></span>

        <h3 id="modal-title" class="text-lg font-bold mb-1"></h3>
        <p id="modal-room" class="text-sm text-gray-500 mb-4"></p>

        <div class="bg-gray-50 rounded-lg p-3 text-sm space-y-1.5 mb-4">
            <p>📅 <span id="modal-date"></span></p>
            <p>🕐 <span id="modal-time"></span></p>
            <p>👤 <span id="modal-user"></span></p>
            <p>👥 <span id="modal-attendees"></span> ผู้เข้าร่วม</p>
        </div>

        <div id="modal-description-wrap" class="mb-4">
            <p class="text-xs font-medium text-gray-500 mb-1">รายละเอียด</p>
            <p id="modal-description" class="text-sm text-gray-700"></p>
        </div>

        <a href="{{ route('bookings.index') }}" class="block text-center bg-red-700 text-white rounded-lg py-2 text-sm font-medium">
            ไปที่ My Bookings
        </a>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.25s ease-out;
    }

    @keyframes modalPop {
        from { opacity: 0; transform: scale(0.95) translateY(8px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    #booking-modal.show-modal > div {
        animation: modalPop 0.2s ease-out;
    }
</style>

<script>
    function openBookingModal(btn) {
        const d = btn.dataset;

        document.getElementById('modal-title').textContent = d.title;
        document.getElementById('modal-room').textContent = d.room;
        document.getElementById('modal-date').textContent = d.date;
        document.getElementById('modal-time').textContent = d.time;
        document.getElementById('modal-user').textContent = d.user;
        document.getElementById('modal-attendees').textContent = d.attendees;

        const statusMap = {
            confirmed: ['Confirmed', 'bg-green-50 text-green-700'],
            pending: ['Pending', 'bg-yellow-50 text-yellow-700'],
            cancelled: ['Cancelled', 'bg-red-50 text-red-600'],
        };
        const [label, cls] = statusMap[d.status] || ['Unknown', 'bg-gray-100 text-gray-600'];
        const statusEl = document.getElementById('modal-status');
        statusEl.textContent = label;
        statusEl.className = 'text-xs px-2 py-1 rounded-full font-medium inline-block mb-3 ' + cls;

        const descWrap = document.getElementById('modal-description-wrap');
        if (d.description && d.description.trim() !== '') {
            document.getElementById('modal-description').textContent = d.description;
            descWrap.classList.remove('hidden');
        } else {
            descWrap.classList.add('hidden');
        }

        const modal = document.getElementById('booking-modal');
        modal.classList.remove('hidden');
        requestAnimationFrame(() => modal.classList.add('show-modal'));
    }

    function closeBookingModal() {
        const modal = document.getElementById('booking-modal');
        modal.classList.remove('show-modal');
        modal.classList.add('hidden');
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeBookingModal();
    });
</script>
@endsection