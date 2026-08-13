@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-lg shadow-sm p-6 mt-6">

    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-xl font-bold">Edit Booking</h2>
            <p class="text-sm text-gray-500">แก้ไขรายละเอียดการจองของคุณ</p>
        </div>
        <span class="text-xs px-2 py-1 rounded-full
            {{ match($booking->status) {
                'confirmed' => 'bg-green-50 text-green-700',
                'pending' => 'bg-yellow-50 text-yellow-700',
                'cancelled' => 'bg-red-50 text-red-600',
                default => 'bg-gray-100 text-gray-600',
            } }}">
            {{ ucfirst($booking->status) }}
        </span>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-indigo-50 rounded-lg p-3 flex justify-between items-center mb-4">
        <div>
            <p class="text-xs text-gray-500">Room</p>
            <p class="font-semibold">{{ $booking->room->name }}</p>
            <p class="text-xs text-gray-500">{{ $booking->room->location }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('bookings.update', $booking) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="text-xs font-medium text-gray-600">Date</label>
                <input type="date" name="date"
                       value="{{ old('date', $booking->start_time->format('Y-m-d')) }}"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Start Time</label>
                <input type="time" name="start_time"
                       value="{{ old('start_time', $booking->start_time->format('H:i')) }}"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">End Time</label>
                <input type="time" name="end_time"
                       value="{{ old('end_time', $booking->end_time->format('H:i')) }}"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Meeting Title</label>
            <input type="text" name="title" value="{{ old('title', $booking->title) }}"
                   class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Description (Optional)</label>
            <textarea name="description" rows="3"
                      class="w-full border rounded-lg p-2 text-sm">{{ old('description', $booking->description) }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t">
            <a href="{{ route('bookings.index') }}" class="border rounded-lg px-4 py-2 text-sm">Cancel</a>
            <button type="submit" class="bg-indigo-900 text-white rounded-lg px-4 py-2 text-sm">Save Changes</button>
        </div>
    </form>
</div>
@endsection