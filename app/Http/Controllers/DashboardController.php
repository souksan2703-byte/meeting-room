<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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
        $view = $request->input('view', 'day'); // day | week | month
        $date = $request->input('date', now()->format('Y-m-d'));
        $selectedFloors = $request->input('floors', [1, 2]);

        $rooms = Room::when(!empty($selectedFloors), function ($q) use ($selectedFloors) {
                $q->whereIn('floor', $selectedFloors);
            })
            ->orderBy('floor')
            ->orderBy('name')
            ->get();

        $data = [
            'rooms' => $rooms,
            'view' => $view,
            'selectedDate' => $date,
            'selectedFloors' => $selectedFloors,
            'now' => now(),
        ];

        if ($view === 'week') {
            $data += $this->buildWeekView($rooms, $date);
        } elseif ($view === 'month') {
            $data += $this->buildMonthView($rooms, $date);
        } else {
            $data += $this->buildDayView($rooms, $date);
        }

        return view('dashboard.index', $data);
    }

    /**
     * DAY VIEW — timeline grid ต่อห้อง แสดงชั่วโมง 09:00-18:00 (ของเดิม)
     */
    private function buildDayView($rooms, string $date): array
    {
        $rooms->each(function (Room $room) use ($date) {
            $bookings = $room->bookingsForDate($date)->map(function ($booking) {
                $booking->position = $this->calculatePosition($booking->start_time, $booking->end_time);
                return $booking;
            });
            $room->setRelation('todayBookings', $bookings);
        });

        return [
            'hours' => range($this->startHour, $this->endHour),
            'startHour' => $this->startHour,
            'endHour' => $this->endHour,
        ];
    }

    /**
     * WEEK VIEW — ตาราง 7 วัน (จันทร์-อาทิตย์) x ห้อง แสดงรายการจองย่อยในแต่ละวัน
     */
    private function buildWeekView($rooms, string $date): array
    {
        $start = Carbon::parse($date)->startOfWeek(Carbon::MONDAY);
        $end = Carbon::parse($date)->endOfWeek(Carbon::SUNDAY);

        $weekDays = collect();
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $weekDays->push($d->copy());
        }

        $roomIds = $rooms->pluck('id');

        $bookings = Booking::with(['room', 'user'])
            ->whereIn('room_id', $roomIds)
            ->whereBetween('start_time', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($b) => $b->room_id . '_' . $b->start_time->format('Y-m-d'));

        return [
            'weekDays' => $weekDays,
            'weekStart' => $start,
            'weekEnd' => $end,
            'weekBookings' => $bookings,
        ];
    }

    /**
     * MONTH VIEW — ปฏิทินรายเดือนแบบ grid มาตรฐาน (6 สัปดาห์ x 7 วัน)
     */
    private function buildMonthView($rooms, string $date): array
    {
        $monthStart = Carbon::parse($date)->startOfMonth();
        $monthEnd = Carbon::parse($date)->endOfMonth();

        $gridStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

        $roomIds = $rooms->pluck('id');

        $bookings = Booking::with(['room', 'user'])
            ->whereIn('room_id', $roomIds)
            ->whereBetween('start_time', [$gridStart->copy()->startOfDay(), $gridEnd->copy()->endOfDay()])
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($b) => $b->start_time->format('Y-m-d'));

        $weeks = collect();
        $cursor = $gridStart->copy();
        while ($cursor->lte($gridEnd)) {
            $week = collect();
            for ($i = 0; $i < 7; $i++) {
                $week->push($cursor->copy());
                $cursor->addDay();
            }
            $weeks->push($week);
        }

        return [
            'monthWeeks' => $weeks,
            'monthCursor' => $monthStart,
            'monthBookings' => $bookings,
        ];
    }

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