<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    /**
     * "My Bookings" page (Image 4)
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $search = $request->input('q');

        $upcoming = Booking::with('room')
            ->where('user_id', $userId)
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->upcoming()
            ->get();

        $past = Booking::with('room')
            ->where('user_id', $userId)
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->past()
            ->paginate(10)
            ->withQueryString();

        return view('bookings.index', compact('upcoming', 'past'));
    }

    /**
     * "Book Meeting Room" form (Image 3), pre-filled from the room page.
     */
    public function create(Request $request, Room $room)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $start = $request->input('start'); // e.g. "11:30"

        return view('bookings.create', [
            'room' => $room,
            'date' => $date,
            'start' => $start,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'attendees' => ['required', 'integer', 'min:1'],
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if ($validated['attendees'] > $room->capacity) {
            return back()
                ->withInput()
                ->withErrors(['attendees' => 'จำนวนผู้เข้าร่วมเกินความจุของห้อง (สูงสุด ' . $room->capacity . ' คน)']);
        }

        $start = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);

        // กันชนเฉพาะกับห้องที่ "อนุมัติแล้ว" (Confirmed) เท่านั้น — ถ้ายังเป็น Pending
        // หลายคนยื่นคำขอจองช่วงเวลาเดียวกันได้ ให้ Admin เป็นคนตัดสินใจว่าจะให้ใครได้ห้องไป
        $overlaps = $room->bookings()
            ->where('status', 'confirmed')
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($overlaps) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'ห้องนี้ถูกอนุมัติให้คนอื่นในช่วงเวลาดังกล่าวไปแล้ว']);
        }

        $booking = Booking::create([
            'room_id' => $room->id,
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => $start,
            'end_time' => $end,
            'status' => $room->requires_approval ? 'pending' : 'confirmed',
            'attendees' => $validated['attendees'],
        ]);

        // แจ้งเตือน Admin ทุกครั้งที่มีการจองใหม่เข้ามา
        NotificationService::notifyAdmins(
            title: 'มีการจองห้องประชุมใหม่',
            body: $request->user()->name . ' จอง ' . $room->name . ' — ' . $booking->title
                . ' (' . $start->format('d/m/Y H:i') . ' - ' . $end->format('H:i') . ')'
                . ($booking->status === 'pending' ? ' — รอการอนุมัติ' : ''),
            link: route('admin.bookings.index')
        );

        return redirect()
            ->route('dashboard')
            ->with('success', 'Booking confirmed for ' . $room->name . '.');
    }

    public function edit(Booking $booking)
    {
        $this->authorizeOwner($booking);

        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorizeOwner($booking);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'attendees' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['attendees'] > $booking->room->capacity) {
            return back()
                ->withInput()
                ->withErrors(['attendees' => 'จำนวนผู้เข้าร่วมเกินความจุของห้อง (สูงสุด ' . $booking->room->capacity . ' คน)']);
        }

        $newStart = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $newEnd = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);

        // เช็คว่าเวลาที่แก้ไขใหม่ไปทับกับ booking ที่ "อนุมัติแล้ว" (Confirmed) ของคนอื่นหรือไม่
        // (ยังคงให้ทับกับคำขอที่ยัง Pending ได้ ให้ Admin ตัดสินใจ)
        $overlaps = $booking->room->bookings()
            ->where('id', '!=', $booking->id)
            ->where('status', 'confirmed')
            ->where('start_time', '<', $newEnd)
            ->where('end_time', '>', $newStart)
            ->exists();

        if ($overlaps) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'ห้องนี้ถูกอนุมัติให้คนอื่นในช่วงเวลาดังกล่าวไปแล้ว']);
        }

        $booking->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => $newStart,
            'end_time' => $newEnd,
            'attendees' => $validated['attendees'],
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking updated.');
    }

    public function destroy(Booking $booking)
    {
        $this->authorizeOwner($booking);

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled.');
    }

    private function authorizeOwner(Booking $booking): void
    {
        abort_unless($booking->user_id === request()->user()->id, 403);
    }
}