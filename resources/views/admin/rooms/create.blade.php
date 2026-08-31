@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto">
    <a href="{{ route('admin.rooms.index') }}" class="text-sm text-gray-500">&larr; Back to Rooms</a>

    <h1 class="text-2xl font-bold mt-2 mb-1">Add New Room</h1>
    <p class="text-gray-500 mb-6">ກອກລາຍລະອຽດຫ້ອງປະຊຸມໃໝ່</p>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.rooms.store') }}" class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        @csrf

        <div>
            <label class="text-xs font-medium text-gray-600">Room Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., Boardroom Alpha"
                   class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-medium text-gray-600">Floor</label>
                <input type="number" name="floor" value="{{ old('floor', 1) }}" min="1"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Capacity (ຄົນ)</label>
                <input type="number" name="capacity" value="{{ old('capacity', 4) }}" min="1"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Location</label>
            <input type="text" name="location" value="{{ old('location') }}" placeholder="........................"
                   class="w-full border rounded-lg p-2 text-sm">
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Equipment (ແຍກດ້ວຍເຄື່ອງໝາຍຈຸດ)</label>
            <input type="text" name="equipment" value="{{ old('equipment') }}"
                   placeholder="Video Conference, 85&quot; Display, Whiteboard, High-Speed WiFi"
                   class="w-full border rounded-lg p-2 text-sm">
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Usage Policies (Optional)</label>
            <textarea name="policies" rows="2" class="w-full border rounded-lg p-2 text-sm">{{ old('policies') }}</textarea>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">IT Support Note (Optional)</label>
            <textarea name="it_support" rows="2" class="w-full border rounded-lg p-2 text-sm">{{ old('it_support') }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="requires_approval" value="1" {{ old('requires_approval') ? 'checked' : '' }}>
            ການຈອງຫ້ອງນີ້ຕ້ອງຖ້າ Admin ອະນຸມັດເທົ່ານັ້ນ (Requires Approval)
        </label>

        <div class="flex justify-end gap-3 pt-3 border-t">
            <a href="{{ route('admin.rooms.index') }}" class="border rounded-lg px-4 py-2 text-sm">Cancel</a>
            <button type="submit" class="bg-red-700 text-white rounded-lg px-4 py-2 text-sm">Save Room</button>
        </div>
    </form>
</div>
@endsection