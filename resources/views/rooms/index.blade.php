@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold">ຫ້ອງປະຊຸມ</h1>
        <p class="text-gray-500">ເບິ່ງຫ້ອງປະຊຸມທັງໝົດ ແລະກວດເບິ່ງສະຖານະຫ້ອງ</p>
    </div>
    <a href="{{ route('dashboard') }}" class="border rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
        View Timeline
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($rooms as $room)
        <a href="{{ route('rooms.show', $room) }}"
           class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">

            <div class="h-32 bg-red-50 flex items-center justify-center">
                <span class="text-red-300 text-4xl">🏢</span>
            </div>

            <div class="p-4">
                <div class="flex items-start justify-between mb-1">
                    <h3 class="font-bold text-lg">{{ $room->name }}</h3>
                    <span class="text-xs border rounded-lg px-2 py-1 text-gray-600 whitespace-nowrap">
                        {{ $room->capacity }} ຄົນ
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-3">{{ $room->location }}</p>

                <div class="flex flex-wrap gap-1.5">
                    @foreach (array_slice($room->equipment ?? [], 0, 3) as $item)
                        <span class="text-xs bg-gray-100 text-gray-600 rounded-full px-2 py-1">{{ $item }}</span>
                    @endforeach
                    @if (count($room->equipment ?? []) > 3)
                        <span class="text-xs text-gray-400 px-1 py-1">+{{ count($room->equipment) - 3 }}</span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <p class="text-gray-500 col-span-full">ຍັງບໍ່ມີຫ້ອງປະຊຸມໃນລະບົບເທື່ອ</p>
    @endforelse
</div>
@endsection