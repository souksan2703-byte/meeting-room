<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class AdminRoomController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $rooms = Room::withCount('bookings')->orderBy('name')->get();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin($request);

        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $this->validateRoom($request);

        Room::create($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'เເພີ່ມຫ້ອງ "' . $validated['name'] . '" ສຳເລັດແລ້ວ');
    }

    public function edit(Request $request, Room $room)
    {
        $this->authorizeAdmin($request);

        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $this->authorizeAdmin($request);

        $validated = $this->validateRoom($request);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'ແກ້ໄຂ "' . $room->name . '" ສຳເລັດແລ້ວ');
    }

    public function destroy(Request $request, Room $room)
    {
        $this->authorizeAdmin($request);

        $name = $room->name;
        $room->delete(); // การจองในห้องนี้ทั้งหมดจะถูกลบตามไปด้วย (cascade)

        return redirect()->route('admin.rooms.index')->with('success', 'ລຶບຫ້ອງ "' . $name . '" ສຳເລັດແລ້ວ');
    }

    private function validateRoom(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'floor' => ['required', 'integer', 'min:1'],
            'capacity' => ['required', 'integer', 'min:1'],
            'equipment' => ['nullable', 'string'],
            'policies' => ['nullable', 'string'],
            'it_support' => ['nullable', 'string'],
            'requires_approval' => ['nullable', 'boolean'],
        ]);

        // equipment กรอกเป็นข้อความคั่นด้วยจุลภาค (Video Conference, Whiteboard) -> แปลงเป็น array เพื่อเก็บใน column json
        $validated['equipment'] = $validated['equipment']
            ? array_values(array_filter(array_map('trim', explode(',', $validated['equipment']))))
            : [];

        $validated['requires_approval'] = $request->boolean('requires_approval');

        return $validated;
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->role === 'admin', 403, 'ສຳລັບຜູ້ເບິ່ງແຍງລະບົບເທົ່ານັ້ນ');
    }
}