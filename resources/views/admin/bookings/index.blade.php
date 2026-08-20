@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold">Overview</h1>
        <p class="text-gray-500">Manage corporate space utilization and bookings.</p>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">{{ session('success') }}</div>
@endif

{{-- Stat cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-semibold text-gray-400 tracking-wide">TOTAL ROOMS</p>
            <span class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600">🏢</span>
        </div>
        <p class="text-3xl font-bold">{{ $stats['totalRooms'] }}</p>
        <p class="text-xs text-gray-400 mt-1">All online</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-semibold text-gray-400 tracking-wide">BOOKINGS TODAY</p>
            <span class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600">📅</span>
        </div>
        <p class="text-3xl font-bold">{{ $stats['bookingsToday'] }}</p>
        <p class="text-xs mt-1 {{ $stats['bookingsDelta'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
            {{ $stats['bookingsDelta'] >= 0 ? '↗' : '↘' }} {{ $stats['bookingsDelta'] >= 0 ? '+' : '' }}{{ $stats['bookingsDelta'] }} from yesterday
        </p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-semibold text-gray-400 tracking-wide">ACTIVE USERS</p>
            <span class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600">👥</span>
        </div>
        <p class="text-3xl font-bold">{{ $stats['activeUsers'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Total registered</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-semibold text-gray-400 tracking-wide">ATTENDEES TODAY</p>
            <span class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600">👥</span>
        </div>
        <p class="text-3xl font-bold">{{ $stats['totalAttendeesToday'] }}</p>
        <p class="text-xs text-gray-400 mt-1">คน (รวมทุกห้องวันนี้)</p>
    </div>
</div>

{{-- All Bookings table --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="flex items-center justify-between p-4 border-b">
        <h2 class="text-lg font-bold">All Bookings</h2>
        <form method="GET" action="{{ route('admin.bookings.index') }}">
            <input type="text" name="q" value="{{ $search }}" placeholder="Filter by room, user..."
                   onchange="this.form.submit()"
                   class="border rounded-lg px-4 py-2 text-sm w-64">
        </form>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="p-3">ROOM NAME</th>
                <th class="p-3">USER</th>
                <th class="p-3">MEETING TITLE</th>
                <th class="p-3">DATE &amp; TIME</th>
                <th class="p-3">STATUS</th>
                <th class="p-3 text-right">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr class="border-t">
                    <td class="p-3 font-medium">🏢 {{ $booking->room->name }}</td>
                    <td class="p-3 text-gray-600">{{ $booking->user->name }}</td>
                    <td class="p-3 text-gray-600">{{ $booking->title }}</td>
                    <td class="p-3 text-gray-600">
                        {{ $booking->start_time->format('M d, H:i') }} - {{ $booking->end_time->format('H:i') }}
                    </td>
                    <td class="p-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ match($booking->status) {
                                'confirmed' => 'bg-green-50 text-green-700',
                                'pending' => 'bg-yellow-50 text-yellow-700',
                                'cancelled' => 'bg-red-50 text-red-600',
                                default => 'bg-gray-100 text-gray-600',
                            } }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="p-3 text-right">
                        @if ($booking->status === 'pending')
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-green-700 border border-green-200 rounded-lg px-3 py-1 text-xs hover:bg-green-50">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.bookings.reject', $booking) }}"
                                      onsubmit="return confirm('Reject this booking?')">
                                    @csrf @method('PATCH')
                                    <button class="text-red-600 border border-red-200 rounded-lg px-3 py-1 text-xs hover:bg-red-50">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-4 text-center text-gray-500">ไม่พบรายการจอง</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="flex items-center justify-between p-4 border-t text-sm text-gray-500">
        <span>Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} entries</span>
        <div>{{ $bookings->links() }}</div>
    </div>
</div>
@endsection