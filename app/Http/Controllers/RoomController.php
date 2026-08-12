<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
        ]);

        Room::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'location' => $request->location,
        ]);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'ເພີ່ມຫ້ອງປະຊຸມສຳເລັດ');
    }

    public function show(string $id)
    {
        $room = Room::findOrFail($id);

        return view('rooms.show', compact('room'));
    }

    public function edit(string $id)
    {
        $room = Room::findOrFail($id);

        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
        ]);

        $room = Room::findOrFail($id);

        $room->update([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'location' => $request->location,
        ]);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'ແກ້ໄຂຫ້ອງປະຊຸມສຳເລັດ');
    }

    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);

        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'ລຶບຫ້ອງປະຊຸມສຳເລັດ');
    }
}