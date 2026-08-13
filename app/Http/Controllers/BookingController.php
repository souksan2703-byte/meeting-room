<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
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

        $upcoming = Booking::with('room')
            ->where('user_id', $userId)
            ->upcoming()
            ->get();

        $past = Booking::with('room')
            ->where('user_id', $userId)
            ->past()
            ->paginate(10);

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
            'attendees' => ['nullable', 'array'],
            'attendees.*' => ['email'],
        ]);

        $room = Room::findOrFail($validated['room_id']);

        $start = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);

        // Prevent double-booking the same room/time
        $overlaps = $room->bookings()
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($overlaps) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'This room is already booked for part of that time range.']);
        }

        Booking::create([
            'room_id' => $room->id,
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => $start,
            'end_time' => $end,
            'status' => $room->requires_approval ? 'pending' : 'confirmed',
            'attendees' => $validated['attendees'] ?? [],
        ]);

        return redirect()
            ->route('bookings.index')
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
        ]);

        $booking->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => Carbon::parse($validated['date'] . ' ' . $validated['start_time']),
            'end_time' => Carbon::parse($validated['date'] . ' ' . $validated['end_time']),
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