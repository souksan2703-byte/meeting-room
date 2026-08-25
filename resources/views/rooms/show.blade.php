@extends('layouts.app')

@section('content')
<a href="{{ route('rooms.index') }}" class="text-sm text-gray-500">&larr; ກັບໄປຫາຫ້ອງປະຊຸມ</a>

<div class="flex items-center justify-between mt-2">
    <div>
        <h1 class="text-3xl font-bold">{{ $room->name }}</h1>
        <p class="text-gray-500">{{ $room->location }}</p>
    </div>
    <div class="flex gap-2">
        <span class="border rounded-lg px-3 py-1 text-sm">ຄວາມຈຸ: {{ $room->capacity }}</span>
        @if ($isAvailableNow)
            <span class="bg-gray-100 rounded-lg px-3 py-1 text-sm">ວ່າງໃນຕອນນີ້</span>
        @endif
    </div>
</div>

<div class="grid grid-cols-3 gap-6 mt-6">
    {{-- Room image + equipment --}}
    <div class="col-span-2">
        @if ($room->image_path)
            <img src="{{ asset('storage/' . $room->image_path) }}" class="rounded-lg w-full h-96 object-cover">
        @endif

        <div class="flex gap-3 mt-4 flex-wrap">
            @foreach ($room->equipment ?? [] as $item)
                <span class="border rounded-lg px-3 py-2 text-sm">{{ $item }}</span>
            @endforeach
        </div>
    </div>

    {{-- Date / time slot picker --}}
    <div class="space-y-4">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="font-semibold mb-3">ເລືອກວັນທີ</h3>
            <div class="flex items-center justify-between mb-2">
                <a href="{{ route('rooms.show', ['room' => $room, 'date' => $selectedDate->copy()->subDay()->format('Y-m-d')]) }}"
                   class="text-gray-400 hover:text-red-700 px-2">&lt;</a>
                <span class="font-medium">{{ $selectedDate->format('D, M j, Y') }}</span>
                <a href="{{ route('rooms.show', ['room' => $room, 'date' => $selectedDate->copy()->addDay()->format('Y-m-d')]) }}"
                   class="text-gray-400 hover:text-red-700 px-2">&gt;</a>
            </div>
            <form method="GET" action="{{ route('rooms.show', $room) }}">
                <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}"
                       onchange="this.form.submit()"
                       class="w-full border rounded-lg p-2 text-sm cursor-pointer">
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex justify-between mb-3">
                <h3 class="font-semibold">ຊ່ວງເວລາ</h3>
                <span class="text-xs text-gray-400">ເວລາທ້ອງຖິ່ນ</span>
            </div>

            <form method="GET" action="{{ route('bookings.create', $room) }}" id="slot-form">
                <input type="hidden" name="date" value="{{ $selectedDate->format('Y-m-d') }}">
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($slots as $slot)
                        <button type="submit" name="start" value="{{ $slot['value'] }}"
                                {{ !$slot['available'] ? 'disabled' : '' }}
                                class="border rounded-lg py-2 text-sm
                                       {{ !$slot['available'] ? 'bg-red-50 text-red-300 cursor-not-allowed' : 'hover:bg-red-50' }}">
                            {{ !$slot['available'] ? '🔒 ' : '' }}{{ $slot['time'] }}
                        </button>
                    @endforeach
                </div>
            </form>
        </div>

        <a href="{{ route('bookings.create', $room) }}?date={{ $selectedDate->format('Y-m-d') }}"
           class="block text-center bg-red-700 text-white rounded-lg py-3 font-medium">
            Book Now &rarr;
        </a>
        @if ($room->requires_approval)
            <p class="text-center text-xs text-gray-400">ຕ້ອງລໍຖ້າການອະນຸມັດຈາກຜູ້ຈັດການ</p>
        @endif
    </div>
</div>

<div class="grid grid-cols-3 gap-6 mt-6">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h4 class="font-semibold mb-1">ສະຖານທີ່ອາຄານ</h4>
        <p class="text-sm text-gray-500">{{ $room->location }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h4 class="font-semibold mb-1">ລະບຽບການໃຊ້ງານ</h4>
        <p class="text-sm text-gray-500">{{ $room->policies }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h4 class="font-semibold mb-1">IT Support</h4>
        <p class="text-sm text-gray-500">{{ $room->it_support }}</p>
    </div>
</div>
@endsection