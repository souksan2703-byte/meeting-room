@extends('layouts.app')

@section('content')
<div class="flex">

    {{-- Sidebar filters --}}
    <aside class="w-64 shrink-0 pr-6">
        <h2 class="text-lg font-bold mb-1">Quick Filters</h2>
        <p class="text-sm text-gray-500 mb-4">Refine room availability</p>

        <form method="GET" action="{{ route('dashboard') }}" class="bg-white rounded-lg p-4 shadow-sm">
            <input type="hidden" name="date" value="{{ $selectedDate }}">
            <input type="hidden" name="view" value="{{ $view }}">
            <p class="font-semibold text-sm mb-2">Floor</p>
            @foreach ([1, 2, 3] as $floor)
                <label class="flex items-center gap-2 mb-2 text-sm">
                    <input type="checkbox" name="floors[]" value="{{ $floor }}"
                           onchange="this.form.submit()"
                           {{ in_array($floor, $selectedFloors) ? 'checked' : '' }}>
                    Floor {{ $floor }}
                </label>
            @endforeach
        </form>

        <a href="{{ route('dashboard') }}" class="block text-center mt-4 border rounded-lg py-2 text-sm text-gray-600 hover:bg-gray-50">
            Reset Filters
        </a>
    </aside>

    <div class="flex-1">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <p class="text-gray-500">Room Availability Calendar</p>
            </div>
            <a href="{{ route('rooms.index') }}" class="bg-indigo-900 text-white px-4 py-2 rounded-lg text-sm font-medium">
                + New Booking
            </a>
        </div>

        {{-- View switcher: Day / Week / Month --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex gap-2">
                @foreach (['day' => 'Day', 'week' => 'Week', 'month' => 'Month'] as $key => $label)
                    <a href="{{ route('dashboard', ['view' => $key, 'date' => $selectedDate, 'floors' => $selectedFloors]) }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ $view === $key ? 'bg-indigo-900 text-white' : 'bg-white border text-gray-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="flex items-center gap-4 text-sm text-gray-500">
                <span><span class="inline-block w-2 h-2 rounded-full bg-gray-300 mr-1"></span>Available</span>
                <span><span class="inline-block w-2 h-2 rounded-full bg-indigo-900 mr-1"></span>Confirmed</span>
                <span><span class="inline-block w-2 h-2 rounded-full bg-red-400 mr-1"></span>Pending</span>
            </div>
        </div>

        {{-- ============== DAY VIEW ============== --}}
        @if ($view === 'day')
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3 mb-4">
                <input type="hidden" name="view" value="day">
                @foreach ($selectedFloors as $f) <input type="hidden" name="floors[]" value="{{ $f }}"> @endforeach
                <a href="{{ route('dashboard', ['view' => 'day', 'date' => \Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d'), 'floors' => $selectedFloors]) }}"
                   class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-indigo-900">&lt;</a>
                <input type="date" name="date" value="{{ $selectedDate }}"
                       onchange="this.form.submit()"
                       class="border rounded-lg px-3 py-1.5 text-sm font-medium cursor-pointer">
                <a href="{{ route('dashboard', ['view' => 'day', 'date' => \Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d'), 'floors' => $selectedFloors]) }}"
                   class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-indigo-900">&gt;</a>
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
                                <p class="text-xs text-gray-500 mt-1">{{ $room->capacity }} &bull; Fl {{ $room->floor }}</p>
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
                                    <div class="absolute top-0 bottom-0 w-px bg-indigo-500 z-10" style="left: {{ $nowLeft }}%"></div>
                                @endif

                                @foreach ($room->todayBookings as $booking)
                                    <a href="{{ route('bookings.index') }}"
                                       title="{{ $booking->title }} — {{ $booking->user->name }} ({{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }})"
                                       class="absolute top-2 bottom-2 rounded-md px-3 py-2 text-white text-xs
                                              {{ $booking->status === 'pending' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-indigo-900' }}"
                                       style="left: {{ $booking->position['left'] }}%; width: {{ $booking->position['width'] }}%;">
                                        <p class="font-semibold uppercase tracking-wide truncate">
                                            @if ($booking->status === 'pending') &#9200; PENDING @else {{ $booking->title }} @endif
                                        </p>
                                        <p class="truncate">{{ $booking->status === 'pending' ? $booking->title : $booking->user->name }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ============== WEEK VIEW ============== --}}
        @if ($view === 'week')
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3 mb-4">
                <input type="hidden" name="view" value="week">
                @foreach ($selectedFloors as $f) <input type="hidden" name="floors[]" value="{{ $f }}"> @endforeach
                <a href="{{ route('dashboard', ['view' => 'week', 'date' => $weekStart->copy()->subWeek()->format('Y-m-d'), 'floors' => $selectedFloors]) }}"
                   class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-indigo-900">&lt;</a>
                <input type="date" name="date" value="{{ $selectedDate }}"
                       onchange="this.form.submit()"
                       class="border rounded-lg px-3 py-1.5 text-sm font-medium cursor-pointer">
                <span class="text-sm text-gray-500">{{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}</span>
                <a href="{{ route('dashboard', ['view' => 'week', 'date' => $weekStart->copy()->addWeek()->format('Y-m-d'), 'floors' => $selectedFloors]) }}"
                   class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-indigo-900">&gt;</a>
            </form>

            <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
                <div class="min-w-[1000px]">
                    <div class="grid border-b" style="grid-template-columns: 180px repeat(7, 1fr);">
                        <div class="p-3 text-xs font-semibold text-gray-500">ROOMS</div>
                        @foreach ($weekDays as $day)
                            <div class="p-3 text-xs text-gray-500 border-l text-center {{ $day->isToday() ? 'bg-indigo-50 text-indigo-900 font-semibold' : '' }}">
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
                                        <a href="{{ route('bookings.index') }}"
                                           title="{{ $booking->title }} — {{ $booking->user->name }}"
                                           class="block rounded px-1.5 py-1 text-[11px] leading-tight truncate
                                                  {{ $booking->status === 'pending' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-indigo-900 text-white' }}">
                                            {{ $booking->start_time->format('H:i') }} {{ $booking->title }}
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ============== MONTH VIEW ============== --}}
        @if ($view === 'month')
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3 mb-4">
                <input type="hidden" name="view" value="month">
                @foreach ($selectedFloors as $f) <input type="hidden" name="floors[]" value="{{ $f }}"> @endforeach
                <a href="{{ route('dashboard', ['view' => 'month', 'date' => $monthCursor->copy()->subMonth()->format('Y-m-d'), 'floors' => $selectedFloors]) }}"
                   class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-indigo-900">&lt;</a>
                <input type="month" name="date" value="{{ $monthCursor->format('Y-m') }}"
                       onchange="this.form.submit()"
                       class="border rounded-lg px-3 py-1.5 text-sm font-medium cursor-pointer">
                <span class="text-sm text-gray-500">{{ $monthCursor->format('F Y') }}</span>
                <a href="{{ route('dashboard', ['view' => 'month', 'date' => $monthCursor->copy()->addMonth()->format('Y-m-d'), 'floors' => $selectedFloors]) }}"
                   class="border rounded-lg px-3 py-1.5 text-gray-500 hover:text-indigo-900">&gt;</a>
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
                            @endphp
                            <a href="{{ route('dashboard', ['view' => 'day', 'date' => $day->format('Y-m-d'), 'floors' => $selectedFloors]) }}"
                               class="border-l p-2 min-h-[100px] block hover:bg-gray-50 {{ !$isCurrentMonth ? 'bg-gray-50 text-gray-300' : '' }} {{ $day->isToday() ? 'bg-indigo-50' : '' }}">
                                <p class="text-xs font-medium mb-1 {{ $day->isToday() ? 'text-indigo-900 font-bold' : '' }}">{{ $day->format('j') }}</p>
                                @foreach ($dayBookings->take(3) as $booking)
                                    <p title="{{ $booking->title }} — {{ $booking->room->name }} — {{ $booking->user->name }}"
                                       class="text-[10px] truncate rounded px-1 py-0.5 mb-0.5
                                              {{ $booking->status === 'pending' ? 'bg-red-50 text-red-600' : 'bg-indigo-100 text-indigo-800' }}">
                                        {{ $booking->start_time->format('H:i') }} {{ $booking->title }}
                                    </p>
                                @endforeach
                                @if ($dayBookings->count() > 3)
                                    <p class="text-[10px] text-gray-400">+{{ $dayBookings->count() - 3 }} more</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection