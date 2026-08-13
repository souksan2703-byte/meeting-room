@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold">Booking Approvals</h1>
        <p class="text-gray-500">ตรวจสอบและอนุมัติ/ปฏิเสธคำขอจองห้องประชุม</p>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">{{ session('success') }}</div>
@endif

<div class="flex gap-2 mb-4">
    <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $status === 'pending' ? 'bg-indigo-900 text-white' : 'bg-white border text-gray-600' }}">
        Pending ({{ $counts['pending'] }})
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $status === 'confirmed' ? 'bg-indigo-900 text-white' : 'bg-white border text-gray-600' }}">
        Confirmed ({{ $counts['confirmed'] }})
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $status === 'cancelled' ? 'bg-indigo-900 text-white' : 'bg-white border text-gray-600' }}">
        Cancelled ({{ $counts['cancelled'] }})
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'all']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $status === 'all' ? 'bg-indigo-900 text-white' : 'bg-white border text-gray-600' }}">
        All
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="p-3">Meeting</th>
                <th class="p-3">Room</th>
                <th class="p-3">Requested By</th>
                <th class="p-3">Date &amp; Time</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr class="border-t">
                    <td class="p-3 font-medium">{{ $booking->title }}</td>
                    <td class="p-3 text-gray-500">{{ $booking->room->name }}</td>
                    <td class="p-3 text-gray-500">{{ $booking->user->name }}</td>
                    <td class="p-3">
                        {{ $booking->start_time->format('M d, Y') }}<br>
                        <span class="text-gray-500">
                            {{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}
                        </span>
                    </td>
                    <td class="p-3">
                        <span class="text-xs px-2 py-1 rounded-full
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
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-3 text-gray-500">ไม่มีรายการจองในสถานะนี้</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $bookings->links() }}</div>
@endsection