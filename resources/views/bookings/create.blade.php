@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-lg shadow-sm p-6 mt-6">

    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-xl font-bold">Book Meeting Room</h2>
            <p class="text-sm text-gray-500">Complete the details below to finalize your reservation.</p>
        </div>
        <span class="bg-red-50 text-red-600 text-xs px-2 py-1 rounded-full">Draft</span>
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

    <div class="bg-red-50 rounded-lg p-3 flex justify-between items-center mb-4">
        <div>
            <p class="text-xs text-gray-500">Selected Space</p>
            <p class="font-semibold">{{ $room->name }}</p>
            <p class="text-xs text-gray-500">Capacity: {{ $room->capacity }}</p>
        </div>
        <a href="{{ route('rooms.index') }}" class="text-xs text-red-600">Change Room</a>
    </div>

    <form method="POST" action="{{ route('bookings.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">

        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="text-xs font-medium text-gray-600">Date</label>
                <input type="date" name="date" value="{{ old('date', $date) }}"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time', $start) }}"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">End Time</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Meeting Title</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g., Q3 Planning Session"
                   class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Description (Optional)</label>
            <textarea name="description" rows="3" placeholder="Brief agenda or context..."
                      class="w-full border rounded-lg p-2 text-sm">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">จำนวนผู้เข้าร่วมประชุม</label>
            <div class="flex items-center gap-3 mt-1">
                <button type="button" onclick="changeAttendees(-1)"
                        class="w-10 h-10 border rounded-lg text-lg font-bold text-gray-600 hover:bg-gray-50">−</button>

                <input type="number" id="attendee-count" name="attendees"
                       value="{{ old('attendees', 1) }}" min="1" max="{{ $room->capacity }}"
                       class="w-20 text-center border rounded-lg p-2 text-sm font-semibold" required readonly>

                <button type="button" onclick="changeAttendees(1)"
                        class="w-10 h-10 border rounded-lg text-lg font-bold text-gray-600 hover:bg-gray-50">+</button>

                <span class="text-xs text-gray-400">สูงสุด {{ $room->capacity }} คน (ความจุห้อง)</span>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t">
            <a href="{{ route('rooms.show', $room) }}" class="border rounded-lg px-4 py-2 text-sm">Cancel</a>
            <button type="submit" class="bg-red-700 text-white rounded-lg px-4 py-2 text-sm">Confirm Booking</button>
        </div>
    </form>
</div>

<script>
    const maxCapacity = {{ $room->capacity }};

    function changeAttendees(delta) {
        const input = document.getElementById('attendee-count');
        let value = parseInt(input.value || '1', 10) + delta;
        if (value < 1) value = 1;
        if (value > maxCapacity) value = maxCapacity;
        input.value = value;
    }
</script>
@endsection