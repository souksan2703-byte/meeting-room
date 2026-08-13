@extends('layouts.app')

@section('content')
<div class="flex">

    {{-- Sidebar filters --}}
    <aside class="w-64 shrink-0 pr-6">
        <h2 class="text-lg font-bold mb-1">Quick Filters</h2>
        <p class="text-sm text-gray-500 mb-4">Refine room availability</p>

        <form method="GET" action="{{ route('dashboard') }}" class="bg-white rounded-lg p-4 shadow-sm">
            <input type="hidden" name="date" value="{{ $selectedDate }}">
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

    {{-- Timeline --}}
    <div class="flex-1">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <p class="text-gray-500">Today's Room Availability</p>
            </div>
            <a href="{{ route('rooms.index') }}" class="bg-indigo-900 text-white px-4 py-2 rounded-lg text-sm font-medium">
                + New Booking
            </a>
        </div>

        <div class="flex items-center gap-4 mb-3 text-sm text-gray-500">
            <span><span class="inline-block w-2 h-2 rounded-full bg-gray-300 mr-1"></span>Available</span>
            <span><span class="inline-block w-2 h-2 rounded-full bg-indigo-900 mr-1"></span>Confirmed</span>
            <span><span class="inline-block w-2 h-2 rounded-full bg-red-400 mr-1"></span>Pending</span>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
            <div class="min-w-[900px]">

                {{-- Hour header --}}
                <div class="grid border-b" style="grid-template-columns: 220px repeat({{ count($hours) - 1 }}, 1fr);">
                    <div class="p-3 text-xs font-semibold text-gray-500">ROOMS</div>
                    @foreach ($hours as $hour)
                        @if (!$loop->last)
                            <div class="p-3 text-xs text-gray-500 border-l">{{ sprintf('%02d:00', $hour) }}</div>
                        @endif
                    @endforeach
                </div>

                {{-- Room rows --}}
                @foreach ($rooms as $room)
                    <div class="grid border-b relative" style="grid-template-columns: 220px 1fr; min-height: 90px;">
                        <div class="p-3 border-r">
                            <a href="{{ route('rooms.show', $room) }}" class="font-semibold hover:underline">{{ $room->name }}</a>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $room->capacity }} &bull; Fl {{ $room->floor }}
                            </p>
                        </div>

                        <div class="relative">
                            {{-- vertical grid lines per hour --}}
                            <div class="absolute inset-0 grid" style="grid-template-columns: repeat({{ count($hours) - 1 }}, 1fr);">
                                @for ($i = 0; $i < count($hours) - 1; $i++)
                                    <div class="border-l h-full"></div>
                                @endfor
                            </div>

                            {{-- current time indicator --}}
                            @if ($now->format('Y-m-d') === $selectedDate && $now->hour >= $startHour && $now->hour < $endHour)
                                @php
                                    $nowLeft = (($now->hour * 60 + $now->minute - $startHour * 60) / (($endHour - $startHour) * 60)) * 100;
                                @endphp
                                <div class="absolute top-0 bottom-0 w-px bg-indigo-500 z-10" style="left: {{ $nowLeft }}%"></div>
                            @endif

                            {{-- booking blocks --}}
                            @foreach ($room->todayBookings as $booking)
                                <a href="{{ route('bookings.index') }}"
                                   class="absolute top-2 bottom-2 rounded-md px-3 py-2 text-white text-xs
                                          {{ $booking->status === 'pending' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-indigo-900' }}"
                                   style="left: {{ $booking->position['left'] }}%; width: {{ $booking->position['width'] }}%;">
                                    <p class="font-semibold uppercase tracking-wide">
                                        @if ($booking->status === 'pending') &#9200; PENDING @else {{ $booking->title }} @endif
                                    </p>
                                    <p>{{ $booking->status === 'pending' ? $booking->title : $booking->user->name }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection