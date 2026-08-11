<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // แสดงรายการจองทั้งหมด
    public function index()
    {
        $bookings = Booking::with('room')
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    // หน้าเพิ่มการจอง
    public function create()
    {
        $rooms = Room::orderBy('name')->get();

        return view('bookings.create', compact('rooms'));
    }

    // บันทึกการจอง
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booker_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'meeting_title' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'attendees' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'status' => 'nullable|in:Pending,Approved,Rejected',
        ]);

        // ตรวจสอบเวลาซ้ำ
        $overlap = Booking::where('room_id', $validated['room_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'ห้องนี้มีการจองในช่วงเวลาดังกล่าวแล้ว กรุณาเลือกเวลาอื่น',
                ]);
        }

        Booking::create($validated);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'เพิ่มรายการจองเรียบร้อยแล้ว');
    }

    // แสดงรายละเอียดการจอง
    public function show(Booking $booking)
    {
        $booking->load('room');

        return view('bookings.show', compact('booking'));
    }

    // หน้าแก้ไข
    public function edit(Booking $booking)
    {
        $rooms = Room::orderBy('name')->get();

        return view('bookings.edit', compact('booking', 'rooms'));
    }

    // อัปเดตการจอง
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booker_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'meeting_title' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'attendees' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'status' => 'nullable|in:Pending,Approved,Rejected',
        ]);

        // ตรวจสอบเวลาซ้ำ โดยไม่ตรวจรายการปัจจุบันของตัวเอง
        $overlap = Booking::where('room_id', $validated['room_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'ห้องนี้มีการจองในช่วงเวลาดังกล่าวแล้ว กรุณาเลือกเวลาอื่น',
                ]);
        }

        $booking->update($validated);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'แก้ไขรายการจองเรียบร้อยแล้ว');
    }

    // ลบการจอง
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'ลบรายการจองเรียบร้อยแล้ว');
    }

    // ข้อมูลสำหรับ FullCalendar
    public function calendarEvents()
    {
        $bookings = Booking::with('room')->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->room->name . ' - ' . $booking->meeting_title,
                'start' => $booking->booking_date . 'T' . $booking->start_time,
                'end' => $booking->booking_date . 'T' . $booking->end_time,
                'extendedProps' => [
                    'booker_name' => $booking->booker_name,
                    'department' => $booking->department,
                    'status' => $booking->status,
                    'attendees' => $booking->attendees,
                ],
            ];
        });

        return response()->json($events);
    }
}   