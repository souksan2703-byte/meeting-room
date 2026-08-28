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

        // Build 30-min slots between 08:00 and 18:00
        $slots = [];
        $slotStart = Carbon::parse($date)->setTime(8, 0);
        $slotEnd = Carbon::parse($date)->setTime(18, 0);

        while ($slotStart->lte($slotEnd)) {
            $slotFinish = $slotStart->copy()->addHour();

            $isBooked = $confirmedBookings->contains(function ($booking) use ($slotStart, $slotFinish) {
                return $slotStart->lt($booking->end_time) && $slotFinish->gt($booking->start_time);
            });

            $slots[] = [
                'time' => $slotStart->format('h:i A'),
                'value' => $slotStart->format('H:i'),
                'available' => !$isBooked,
            ];

            $slotStart->addHour();
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