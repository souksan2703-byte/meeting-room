<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::orderBy('floor')->orderBy('name')->get();

        return view('rooms.index', compact('rooms'));
    }

    public function show(Request $request, Room $room)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        $allBookings = $room->bookingsForDate($date);
        // ล็อกช่องเวลาเฉพาะที่มีคน "อนุมัติแล้ว" (Confirmed) เท่านั้น
        // ช่องที่มีแค่คำขอ Pending ยังเปิดให้คนอื่นยื่นคำขอซ้อนได้ ให้ Admin เป็นคนตัดสินใจ
        $confirmedBookings = $allBookings->where('status', 'confirmed');

        // Build 30-min slots between 09:00 and 13:30 (mirrors the mock; adjust as needed)
        $slots = [];
        $slotStart = Carbon::parse($date)->setTime(9, 0);
        $slotEnd = Carbon::parse($date)->setTime(13, 30);

        while ($slotStart->lte($slotEnd)) {
            $slotFinish = $slotStart->copy()->addMinutes(30);

            $isBooked = $confirmedBookings->contains(function ($booking) use ($slotStart, $slotFinish) {
                return $slotStart->lt($booking->end_time) && $slotFinish->gt($booking->start_time);
            });

            $slots[] = [
                'time' => $slotStart->format('h:i A'),
                'value' => $slotStart->format('H:i'),
                'available' => !$isBooked,
            ];

            $slotStart->addMinutes(30);
        }

        $isAvailableNow = !$confirmedBookings->contains(function ($booking) {
            return now()->between($booking->start_time, $booking->end_time);
        });

        return view('rooms.show', [
            'room' => $room,
            'slots' => $slots,
            'selectedDate' => $selectedDate,
            'isAvailableNow' => $isAvailableNow,
        ]);
    }
}