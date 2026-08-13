<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Visible timeline window (matches the mock: 09:00 - 18:00)
    private int $startHour = 9;
    private int $endHour = 18;

    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $selectedFloors = $request->input('floors', [1, 2]); // checkbox filter, default Floor 1 & 2

        $rooms = Room::when(!empty($selectedFloors), function ($q) use ($selectedFloors) {
                $q->whereIn('floor', $selectedFloors);
            })
            ->orderBy('floor')
            ->orderBy('name')
            ->get();

        // Attach today's bookings + pixel positioning info to each room
        $rooms->each(function (Room $room) use ($date) {
            $bookings = $room->bookingsForDate($date)->map(function ($booking) {
                $booking->position = $this->calculatePosition($booking->start_time, $booking->end_time);
                return $booking;
            });
            $room->setRelation('todayBookings', $bookings);
        });

        $hours = range($this->startHour, $this->endHour);

        return view('dashboard.index', [
            'rooms' => $rooms,
            'hours' => $hours,
            'selectedDate' => $date,
            'selectedFloors' => $selectedFloors,
            'now' => now(),
            'startHour' => $this->startHour,
            'endHour' => $this->endHour,
        ]);
    }

    /**
     * Turn a start/end datetime into a left/width percentage
     * relative to the visible timeline (startHour - endHour).
     */
    private function calculatePosition(Carbon $start, Carbon $end): array
    {
        $totalMinutes = ($this->endHour - $this->startHour) * 60;

        $startMinutes = max(0, $start->hour * 60 + $start->minute - $this->startHour * 60);
        $endMinutes = min($totalMinutes, $end->hour * 60 + $end->minute - $this->startHour * 60);

        $left = ($startMinutes / $totalMinutes) * 100;
        $width = max(2, (($endMinutes - $startMinutes) / $totalMinutes) * 100);

        return ['left' => round($left, 2), 'width' => round($width, 2)];
    }
}