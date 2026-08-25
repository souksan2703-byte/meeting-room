@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold">Reports</h1>
        <p class="text-gray-500">ສະຫຼຸບສະຖິຕິການນຳໃຊ້ຫ້ອງປະຊຸມ</p>
    </div>
    <form method="GET" action="{{ route('admin.reports.index') }}">
        <select name="range" onchange="this.form.submit()" class="border rounded-lg px-4 py-2 text-sm">
            <option value="7" {{ $range == 7 ? 'selected' : '' }}>7 ວັນລ່າສຸດ</option>
            <option value="30" {{ $range == 30 ? 'selected' : '' }}>30 ວັນລ່າສຸດ</option>
            <option value="90" {{ $range == 90 ? 'selected' : '' }}>90 ວັນລ່າສຸດ</option>
            <option value="365" {{ $range == 365 ? 'selected' : '' }}>1 ປີລ່າສຸດ</option>
        </select>
    </form>
</div>

{{-- Summary cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-xs font-semibold text-gray-400 tracking-wide">ການຈອງທັງໝົດ</p>
        <p class="text-3xl font-bold mt-1">{{ $totalBookings }}</p>
        <p class="text-xs text-gray-400 mt-1">ລາຍການໃນຊ່ວງທີ່ເລືອກ</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-xs font-semibold text-gray-400 tracking-wide">ຜູ້ເຂົ້າຮ່ວມທັງໝົດ</p>
        <p class="text-3xl font-bold mt-1">{{ $totalAttendees }}</p>
        <p class="text-xs text-gray-400 mt-1">ຄົນ ລວມທຸກການຈອງ</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-xs font-semibold text-gray-400 tracking-wide">ຫ້ອງທີ່ວ່າງ</p>
        <p class="text-3xl font-bold mt-1">{{ $totalRooms }}</p>
        <p class="text-xs text-gray-400 mt-1">ຫ້ອງໃນລະບົບ</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Bookings per room --}}
    <div class="bg-white rounded-lg shadow-sm p-5">
        <h2 class="font-bold mb-4">ການຈອງແຍກຕາມຫ້ອງ</h2>
        @forelse ($byRoom as $roomName => $count)
            @php $percent = $totalBookings > 0 ? round(($count / $totalBookings) * 100) : 0; @endphp
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span>{{ $roomName }}</span>
                    <span class="text-gray-500">{{ $count }} ครั้ง</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-red-700 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400">ບໍ່ມີຂໍ້ມູນໃນຊ່ວງເວລານີ້</p>
        @endforelse
    </div>

    {{-- Status breakdown --}}
    <div class="bg-white rounded-lg shadow-sm p-5">
        <h2 class="font-bold mb-4">ອັດຕາສ່ວນສະຖານະການຈອງ</h2>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Confirmed</span>
                <span class="text-sm font-semibold">{{ $byStatus['confirmed'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Pending</span>
                <span class="text-sm font-semibold">{{ $byStatus['pending'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Cancelled</span>
                <span class="text-sm font-semibold">{{ $byStatus['cancelled'] }}</span>
            </div>
        </div>
    </div>

    {{-- Busiest time slots --}}
    <div class="bg-white rounded-lg shadow-sm p-5">
        <h2 class="font-bold mb-4">ເວລາຈອງທີ່ນິຍົມ</h2>
        @foreach ($byTimeSlot as $slot => $count)
            @php $percent = $totalBookings > 0 ? round(($count / $totalBookings) * 100) : 0; @endphp
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span>{{ $slot }}</span>
                    <span class="text-gray-500">{{ $count }} ຄັ້ງ</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-red-400 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Top bookers --}}
    <div class="bg-white rounded-lg shadow-sm p-5">
        <h2 class="font-bold mb-4">ຄົນທີ່ຈອງຫຼາຍທີ່ສຸດ (Top 5)</h2>
        @forelse ($topBookers as $name => $count)
            <div class="flex items-center justify-between py-1.5 border-b last:border-0 text-sm">
                <span>{{ $name }}</span>
                <span class="text-gray-500">{{ $count }} ຄັ້ງ</span>
            </div>
        @empty
            <p class="text-sm text-gray-400">ບໍ່ມີຂໍ້ມູນໃນຊ່ວງເວລານີ້</p>
        @endforelse
    </div>

</div>
@endsection