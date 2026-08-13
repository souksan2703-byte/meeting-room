@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-lg shadow-sm p-6 mt-6">

    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-xl font-bold">Book Meeting Room</h2>
            <p class="text-sm text-gray-500">Complete the details below to finalize your reservation.</p>
        </div>
        <span class="bg-indigo-50 text-indigo-700 text-xs px-2 py-1 rounded-full">Draft</span>
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
            <p class="text-xs text-gray-500">Selected Space</p>
            <p class="font-semibold">{{ $room->name }} - Floor {{ $room->floor }}</p>
            <p class="text-xs text-gray-500">Capacity: {{ $room->capacity }}</p>
        </div>
        <a href="{{ route('rooms.index') }}" class="text-xs text-indigo-700">Change Room</a>
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
            <label class="text-xs font-medium text-gray-600">Invite Attendees</label>
            <div class="flex gap-2">
                <input type="email" id="attendee-input" placeholder="Enter email address"
                       class="flex-1 border rounded-lg p-2 text-sm">
                <button type="button" onclick="addAttendee()" class="border rounded-lg px-3 text-sm">+ Add</button>
            </div>
            <div id="attendee-list" class="flex flex-wrap gap-2 mt-2"></div>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t">
            <a href="{{ route('rooms.show', $room) }}" class="border rounded-lg px-4 py-2 text-sm">Cancel</a>
            <button type="submit" class="bg-indigo-900 text-white rounded-lg px-4 py-2 text-sm">Confirm Booking</button>
        </div>
    </form>
</div>

<script>
    const attendees = [];
    function addAttendee() {
        const input = document.getElementById('attendee-input');
        const email = input.value.trim();
        if (!email) return;
        attendees.push(email);
        input.value = '';
        renderAttendees();
    }
    function renderAttendees() {
        const list = document.getElementById('attendee-list');
        list.innerHTML = '';
        attendees.forEach((email, i) => {
            const chip = document.createElement('span');
            chip.className = 'bg-gray-100 text-xs rounded-full px-3 py-1';
            chip.textContent = email;
            list.appendChild(chip);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'attendees[]';
            hidden.value = email;
            list.appendChild(hidden);
        });
    }
</script>
@endsection