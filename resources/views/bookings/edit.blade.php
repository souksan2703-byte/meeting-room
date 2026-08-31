@extends('layouts.app')

@php
    $startVal = old('start_time', $booking->start_time->format('H:i'));
    $endVal = old('end_time', $booking->end_time->format('H:i'));
    [$sh, $sm] = explode(':', $startVal);
    [$eh, $em] = explode(':', $endVal);

    // เผื่อข้อมูลเดิมมีนาทีที่ไม่ตรงกับตัวเลือกมาตรฐาน (00,15,30,45) เช่นเคยพิมพ์เอง ให้เพิ่มเป็นตัวเลือกพิเศษไว้ ไม่ให้ข้อมูลเพี้ยน
    $startMinuteOptions = collect(['00', '15', '30', '45'])->push($sm)->unique()->sort()->values();
    $endMinuteOptions = collect(['00', '15', '30', '45'])->push($em)->unique()->sort()->values();
@endphp

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-lg shadow-sm p-6 mt-6">

    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-xl font-bold">Edit Booking</h2>
            <p class="text-sm text-gray-700">ແກ້ໄຂລາຍລະອຽດການຈອງຂອງທ່ານ</p>
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

    <div class="bg-red-50 rounded-lg p-3 flex justify-between items-center mb-4">
        <div>
            <p class="text-xs text-gray-700">Room</p>
            <p class="font-semibold">{{ $booking->room->name }}</p>
            <p class="text-xs text-gray-700">{{ $booking->room->location }}</p>
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
                <div class="flex gap-1">
                    <select id="start_time_h" class="w-full border rounded-lg p-2 text-sm">
                        @for ($h = 0; $h < 24; $h++)
                            <option value="{{ sprintf('%02d', $h) }}" {{ $sh === sprintf('%02d', $h) ? 'selected' : '' }}>{{ sprintf('%02d', $h) }}</option>
                        @endfor
                    </select>
                    <span class="self-center text-gray-400">:</span>
                    <select id="start_time_m" class="w-full border rounded-lg p-2 text-sm">
                        @foreach ($startMinuteOptions as $m)
                            <option value="{{ $m }}" {{ $sm === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="start_time" id="start_time" value="{{ $startVal }}">
            </div>

            <div>
                <label class="text-xs font-medium text-gray-600">End Time</label>
                <div class="flex gap-1">
                    <select id="end_time_h" class="w-full border rounded-lg p-2 text-sm">
                        @for ($h = 0; $h < 24; $h++)
                            <option value="{{ sprintf('%02d', $h) }}" {{ $eh === sprintf('%02d', $h) ? 'selected' : '' }}>{{ sprintf('%02d', $h) }}</option>
                        @endfor
                    </select>
                    <span class="self-center text-gray-400">:</span>
                    <select id="end_time_m" class="w-full border rounded-lg p-2 text-sm">
                        @foreach ($endMinuteOptions as $m)
                            <option value="{{ $m }}" {{ $em === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="end_time" id="end_time" value="{{ $endVal }}">
            </div>
        </div>
        <p id="time-warning" class="hidden text-xs text-red-600 -mt-2">
            ⚠️ เวลาสิ้นสุดต้องอยู่หลังเวลาเริ่ม
        </p>

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

        <div>
            <label class="text-xs font-medium text-gray-600">ຈຳນວນຜູ້ເຂົ້າຮ່ວມ</label>
            <div class="flex items-center gap-3 mt-1">
                <button type="button" onclick="changeAttendees(-1)"
                        class="w-10 h-10 border rounded-lg text-lg font-bold text-gray-600 hover:bg-gray-50">−</button>

                <input type="number" id="attendee-count" name="attendees"
                       value="{{ old('attendees', $booking->attendees ?? 1) }}" min="1" max="{{ $booking->room->capacity }}"
                       class="w-20 text-center border rounded-lg p-2 text-sm font-semibold" required
                       onfocus="this.select()">

                <button type="button" onclick="changeAttendees(1)"
                        class="w-10 h-10 border rounded-lg text-lg font-bold text-gray-600 hover:bg-gray-50">+</button>

                <span class="text-xs text-gray-600">สูงสุด {{ $booking->room->capacity }}ຄົນ (ຄວາມຈຸຂອງຫ້ອງ)</span>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t">
            <a href="{{ route('bookings.index') }}" class="border rounded-lg px-4 py-2 text-sm">Cancel</a>
            <button type="submit" class="bg-red-700 text-white rounded-lg px-4 py-2 text-sm">Save Changes</button>
        </div>
    </form>
</div>

<script>
    const maxCapacity = {{ $booking->room->capacity }};
    const attendeeInput = document.getElementById('attendee-count');

    function changeAttendees(delta) {
        let value = parseInt(attendeeInput.value || '1', 10) + delta;
        if (value < 1) value = 1;
        if (value > maxCapacity) value = maxCapacity;
        attendeeInput.value = value;
    }

    attendeeInput.addEventListener('blur', () => {
        let value = parseInt(attendeeInput.value || '1', 10);
        if (isNaN(value) || value < 1) value = 1;
        if (value > maxCapacity) value = maxCapacity;
        attendeeInput.value = value;
    });

    // ===== Time dropdown แบบ 24 ชั่วโมง (ไม่มี AM/PM) =====
    const startH = document.getElementById('start_time_h');
    const startM = document.getElementById('start_time_m');
    const endH = document.getElementById('end_time_h');
    const endM = document.getElementById('end_time_m');
    const startHidden = document.getElementById('start_time');
    const endHidden = document.getElementById('end_time');
    const warningEl = document.getElementById('time-warning');

    function syncStart() { startHidden.value = startH.value + ':' + startM.value; }
    function syncEnd() { endHidden.value = endH.value + ':' + endM.value; }

    function checkTimeOrder() {
        warningEl.classList.toggle('hidden', endHidden.value > startHidden.value);
    }

    [startH, startM].forEach(el => el.addEventListener('change', () => { syncStart(); checkTimeOrder(); }));
    [endH, endM].forEach(el => el.addEventListener('change', () => { syncEnd(); checkTimeOrder(); }));

    document.querySelector('form').addEventListener('submit', (e) => {
        checkTimeOrder();
        if (!warningEl.classList.contains('hidden')) {
            e.preventDefault();
            endH.focus();
        }
    });

    checkTimeOrder();
</script>
@endsection