@extends('layouts.app')

@php
    $startVal = old('start_time', $start ?? '');
    $endVal = old('end_time', '');
    [$sh, $sm] = $startVal ? explode(':', $startVal) : ['', ''];
    [$eh, $em] = $endVal ? explode(':', $endVal) : ['', ''];
@endphp

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-lg shadow-sm p-6 mt-6">

    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-xl font-bold">Book Meeting Room</h2>
            <p class="text-sm text-gray-700">ກະລຸນາຕື່ມຂໍ້ມູນລາຍລະອຽດຂ້າງລຸ່ມນີ້ເພື່ອສຳເລັດການຈອງຂອງທ່ານ</p>
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
            <p class="text-xs text-gray-700">Selected Space</p>
            <p class="font-semibold">{{ $room->name }}</p>
            <p class="text-xs text-gray-700">Capacity: {{ $room->capacity }}</p>
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

            {{-- Start Time — dropdown 24 ชั่วโมงเอง ไม่ใช้ native time picker เพื่อไม่ให้มี AM/PM --}}
            <div>
                <label class="text-xs font-medium text-gray-600">Start Time</label>
                <div class="flex gap-1">
                    <select id="start_time_h" class="w-full border rounded-lg p-2 text-sm">
                        <option value="">--</option>
                        @for ($h = 0; $h < 24; $h++)
                            <option value="{{ sprintf('%02d', $h) }}" {{ $sh === sprintf('%02d', $h) ? 'selected' : '' }}>{{ sprintf('%02d', $h) }}</option>
                        @endfor
                    </select>
                    <span class="self-center text-gray-400">:</span>
                    <select id="start_time_m" class="w-full border rounded-lg p-2 text-sm">
                        <option value="">--</option>
                        @foreach (['00','15','30','45'] as $m)
                            <option value="{{ $m }}" {{ $sm === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="start_time" id="start_time" value="{{ $startVal }}">
            </div>

            {{-- End Time --}}
            <div>
                <label class="text-xs font-medium text-gray-600">End Time</label>
                <div class="flex gap-1">
                    <select id="end_time_h" class="w-full border rounded-lg p-2 text-sm">
                        <option value="">--</option>
                        @for ($h = 0; $h < 24; $h++)
                            <option value="{{ sprintf('%02d', $h) }}" {{ $eh === sprintf('%02d', $h) ? 'selected' : '' }}>{{ sprintf('%02d', $h) }}</option>
                        @endfor
                    </select>
                    <span class="self-center text-gray-400">:</span>
                    <select id="end_time_m" class="w-full border rounded-lg p-2 text-sm">
                        <option value="">--</option>
                        @foreach (['00','15','30','45'] as $m)
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
            <input type="text" name="title" value="{{ old('title') }}" placeholder="ກາລຸນາຂຽນຫົວຂໍ້ໃນການປະຊົມຄັ້ງນີ້..."
                   class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Description (Optional)</label>
            <textarea name="description" rows="3" placeholder="ລາຍລະອຽດໃນການປະຊຸມ..."
                      class="w-full border rounded-lg p-2 text-sm">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">ຈຳນວນຜູ້ເຂົ້າຮ່ວມ</label>
            <div class="flex items-center gap-3 mt-1">
                <button type="button" onclick="changeAttendees(-1)"
                        class="w-10 h-10 border rounded-lg text-lg font-bold text-gray-600 hover:bg-gray-50">−</button>

                <input type="number" id="attendee-count" name="attendees"
                       value="{{ old('attendees', 1) }}" min="1" max="{{ $room->capacity }}"
                       class="w-20 text-center border rounded-lg p-2 text-sm font-semibold" required
                       onfocus="this.select()">

                <button type="button" onclick="changeAttendees(1)"
                        class="w-10 h-10 border rounded-lg text-lg font-bold text-gray-600 hover:bg-gray-50">+</button>

                <span class="text-xs text-gray-600">สูงสุด {{ $room->capacity }}ຄົນ (ຄວາມຈຸຂອງຫ້ອງ)</span>
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

    // ===== Time dropdown แบบ 24 ชั่วโมง (ไม่มี AM/PM แน่นอน เพราะไม่ใช้ native picker) =====
    const startH = document.getElementById('start_time_h');
    const startM = document.getElementById('start_time_m');
    const endH = document.getElementById('end_time_h');
    const endM = document.getElementById('end_time_m');
    const startHidden = document.getElementById('start_time');
    const endHidden = document.getElementById('end_time');
    const warningEl = document.getElementById('time-warning');

    let endTouchedManually = endHidden.value !== '';

    function syncStart() {
        if (startH.value && startM.value) {
            startHidden.value = startH.value + ':' + startM.value;
        } else {
            startHidden.value = '';
        }
    }

    function syncEnd() {
        if (endH.value && endM.value) {
            endHidden.value = endH.value + ':' + endM.value;
        } else {
            endHidden.value = '';
        }
    }

    function checkTimeOrder() {
        if (!startHidden.value || !endHidden.value) {
            warningEl.classList.add('hidden');
            return;
        }
        warningEl.classList.toggle('hidden', endHidden.value > startHidden.value);
    }

    [startH, startM].forEach(el => el.addEventListener('change', () => {
        syncStart();
        // ถ้ายังไม่เคยตั้งเวลาสิ้นสุดเอง เติมให้อัตโนมัติ +1 ชั่วโมงจากเวลาเริ่ม
        if (startHidden.value && !endTouchedManually) {
            const [h, m] = startHidden.value.split(':').map(Number);
            const newEndHour = (h + 1) % 24;
            endH.value = String(newEndHour).padStart(2, '0');
            endM.value = String(m).padStart(2, '0');
            syncEnd();
        }
        checkTimeOrder();
    }));

    [endH, endM].forEach(el => el.addEventListener('change', () => {
        endTouchedManually = true;
        syncEnd();
        checkTimeOrder();
    }));

    document.querySelector('form').addEventListener('submit', (e) => {
        checkTimeOrder();
        if (!warningEl.classList.contains('hidden') || !startHidden.value || !endHidden.value) {
            e.preventDefault();
            if (!startHidden.value) { startH.focus(); }
            else if (!endHidden.value) { endH.focus(); }
        }
    });

    syncStart();
    syncEnd();
    checkTimeOrder();
</script>
@endsection