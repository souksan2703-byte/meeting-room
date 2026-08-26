@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold">Rooms</h1>
        <p class="text-gray-500">จัดการห้องประชุมทั้งหมดในระบบ</p>
    </div>
    <a href="{{ route('admin.rooms.create') }}" class="bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Add New Room
    </a>
</div>

@if (session('success'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="p-3">Name</th>
                <th class="p-3">Location</th>
                <th class="p-3">Capacity</th>
                <th class="p-3">Requires Approval</th>
                <th class="p-3">Bookings</th>
                <th class="p-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
                <tr class="border-t">
                    <td class="p-3 font-medium">🏢 {{ $room->name }}</td>
                    <td class="p-3 text-gray-600">{{ $room->location ?: '—' }}</td>
                    <td class="p-3 text-gray-600">{{ $room->capacity }} คน</td>
                    <td class="p-3">
                        @if ($room->requires_approval)
                            <span class="text-xs bg-yellow-50 text-yellow-700 px-2 py-1 rounded-full">Yes</span>
                        @else
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">No</span>
                        @endif
                    </td>
                    <td class="p-3 text-gray-600">{{ $room->bookings_count }}</td>
                    <td class="p-3 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="border rounded-lg px-3 py-1 text-xs text-gray-600 hover:bg-gray-50">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                                  onsubmit="return confirm('ลบห้อง {{ $room->name }}? การจองทั้งหมด ({{ $room->bookings_count }} รายการ) ในห้องนี้จะถูกลบไปด้วย')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 border border-red-200 rounded-lg px-3 py-1 text-xs hover:bg-red-50">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-4 text-center text-gray-500">ยังไม่มีห้องประชุมในระบบ</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection