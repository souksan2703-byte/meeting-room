<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('room')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::orderBy('name')->get();

        return view('bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booker_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'meeting_title' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'attendees' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'drinking_water' => 'nullable|string|max:255',
        ]);

        // ตรวจสอบว่าห้องถูกจองซ้ำในช่วงเวลาเดียวกันหรือไม่
        $conflict = Booking::where('room_id', $request->room_id)
            ->where('booking_date', $request->booking_date)
            ->where(function ($query) use ($request) {

                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);

            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->with('error', 'ຫ້ອງນີ້ຖືກຈອງໃນເວລານີ້ແລ້ວ');
        }

        // ตรวจจำนวนผู้เข้าร่วมไม่ให้เกินความจุห้อง
        $room = Room::findOrFail($request->room_id);

        if ($request->attendees > $room->capacity) {
            return back()
                ->withInput()
                ->with('error', 'ຈຳນວນຜູ້ເຂົ້າຮ່ວມເກີນຄວາມຈຸຂອງຫ້ອງ');
        }

        Booking::create([
            'room_id' => $request->room_id,
            'booker_name' => $request->booker_name,
            'department' => $request->department,
            'meeting_title' => $request->meeting_title,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'attendees' => $request->attendees,
            'note' => $request->note,
            'drinking_water' => $request->drinking_water,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'ເພີ່ມການຈອງຫ້ອງສຳເລັດ');
    }

    public function show(string $id)
    {
        $booking = Booking::with('room')->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    public function edit(string $id)
    {
        $booking = Booking::findOrFail($id);
        $rooms = Room::orderBy('name')->get();

        return view('bookings.edit', compact('booking', 'rooms'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booker_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'meeting_title' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'attendees' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'drinking_water' => 'nullable|string|max:255',
            'status' => 'required|string',
        ]);

        $booking = Booking::findOrFail($id);

        $conflict = Booking::where('room_id', $request->room_id)
            ->where('booking_date', $request->booking_date)
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($request) {

                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);

            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->with('error', 'ຫ້ອງນີ້ຖືກຈອງໃນເວລານີ້ແລ້ວ');
        }

        $room = Room::findOrFail($request->room_id);

        if ($request->attendees > $room->capacity) {
            return back()
                ->withInput()
                ->with('error', 'ຈຳນວນຜູ້ເຂົ້າຮ່ວມເກີນຄວາມຈຸຂອງຫ້ອງ');
        }

        $booking->update([
            'room_id' => $request->room_id,
            'booker_name' => $request->booker_name,
            'department' => $request->department,
            'meeting_title' => $request->meeting_title,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'attendees' => $request->attendees,
            'note' => $request->note,
            'drinking_water' => $request->drinking_water,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'ແກ້ໄຂການຈອງສຳເລັດ');
    }

    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'ລຶບການຈອງສຳເລັດ');
    }
}