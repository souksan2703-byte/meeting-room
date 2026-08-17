@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold">My Bookings</h1>
        <p class="text-gray-500">Manage your upcoming and past room reservations.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="relative">
            <select onchange="window.location.href=this.value"
                    class="appearance-none border rounded-lg pl-4 pr-8 py-2 text-sm bg-white cursor-pointer">
                <option value="{{ route('bookings.index') }}" {{ !request('status') ? 'selected' : '' }}>All Bookings</option>
                <option value="{{ route('bookings.index', ['status' => 'upcoming']) }}" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming Only</option>
                <option value="{{ route('bookings.index', ['status' => 'past']) }}" {{ request('status') === 'past' ? 'selected' : '' }}>Past Only</option>
            </select>
        </div>
        <a href="{{ route('rooms.index') }}" class="bg-indigo-900 text-white px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap">
            + New Booking
        </a>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">{{ session('success') }}</div>
@endif

@if (request('status') !== 'past')
<h2 class="text-lg font-bold mb-3">Upcoming</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    @forelse ($upcoming as $booking)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 border-l-4 border-l-indigo-900 p-4">
            <div class="flex justify-between items-start">
                <h3 class="font-bold text-lg">{{ $booking->room->name }}</h3>
                <span class="text-xs px-2 py-1 rounded-full flex items-center gap-1 whitespace-nowrap
                    {{ match($booking->status) {
                        'confirmed' => 'bg-indigo-50 text-indigo-700',
                        'pending' => 'bg-yellow-50 text-yellow-700',
                        default => 'bg-gray-100 text-gray-600',
                    } }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mb-3 flex items-center gap-1">
                📍 {{ $booking->room->location }}
            </p>

            <div class="bg-gray-50 rounded-lg p-3 text-sm mb-3 space-y-1">
                <p class="flex items-center gap-2">📅 {{ $booking->start_time->format('M j, Y') }}</p>
                <p class="flex items-center gap-2">🕐 {{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}</p>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 flex items-center gap-1">👥 {{ is_array($booking->attendees) ? count($booking->attendees) : 0 }} Attendees</span>
                <div class="flex gap-2">
                    <a href="{{ route('bookings.edit', $booking) }}" class="border rounded-lg px-3 py-1 text-sm">Edit</a>
                    <form method="POST" action="{{ route('bookings.destroy', $booking) }}"
                          onsubmit="return confirm('Cancel this booking?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 text-sm px-2">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="text-gray-500 col-span-2">You have no upcoming bookings.</p>
    @endforelse
</div>
@endif

@if (request('status') !== 'upcoming')
<h2 class="text-lg font-bold mb-3">Past Bookings</h2>
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="p-3">Room</th>
                <th class="p-3">Location</th>
                <th class="p-3">Date &amp; Time</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($past as $booking)
                <tr class="border-t">
                    <td class="p-3 font-medium">{{ $booking->room->name }}</td>
                    <td class="p-3 text-gray-500">{{ $booking->room->location }}</td>
                    <td class="p-3">
                        {{ $booking->start_time->format('M d, Y') }}<br>
                        <span class="text-gray-500">{{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}</span>
                    </td>
                    <td class="p-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $booking->status === 'cancelled' ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $booking->status === 'cancelled' ? 'Cancelled' : 'Completed' }}
                        </span>
                    </td>
                    <td class="p-3 text-right">
                        <a href="{{ route('rooms.show', $booking->room) }}" class="text-indigo-700">Rebook</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-3 text-gray-500">No past bookings.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $past->links() }}</div>
@endif
@endsection