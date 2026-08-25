<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $range = $request->input('range', '30'); // จำนวนวันย้อนหลัง
        $since = now()->subDays((int) $range);

        $bookings = Booking::with('room')
            ->where('created_at', '>=', $since)
            ->get();

        // จำนวนการจองต่อห้อง เรียงจากมากไปน้อย
        $byRoom = $bookings
            ->groupBy(fn ($b) => $b->room->name)
            ->map(fn ($group) => $group->count())
            ->sortDesc();

        // สัดส่วนตามสถานะ
        $byStatus = [
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
        ];

        // ผู้จองบ่อยที่สุด 5 อันดับ
        $topBookers = $bookings
            ->groupBy(fn ($b) => $b->user->name ?? 'ไม่ทราบชื่อ')
            ->map(fn ($group) => $group->count())
            ->sortDesc()
            ->take(5);

        // จำนวนผู้เข้าร่วมรวมทั้งหมดในช่วงเวลานี้
        $totalAttendees = $bookings->sum(fn ($b) => $b->attendeesCount());

        // ช่วงเวลาที่มีคนจองบ่อยที่สุด (แบ่งเป็นช่วงเช้า/บ่าย/เย็น)
        $byTimeSlot = [
            'เช้า (08:00-12:00)' => $bookings->filter(fn ($b) => $b->start_time->hour >= 8 && $b->start_time->hour < 12)->count(),
            'บ่าย (12:00-16:00)' => $bookings->filter(fn ($b) => $b->start_time->hour >= 12 && $b->start_time->hour < 16)->count(),
            'เย็น (16:00-20:00)' => $bookings->filter(fn ($b) => $b->start_time->hour >= 16 && $b->start_time->hour < 20)->count(),
        ];

        $totalRooms = Room::count();

        return view('admin.reports.index', [
            'range' => $range,
            'totalBookings' => $bookings->count(),
            'totalAttendees' => $totalAttendees,
            'totalRooms' => $totalRooms,
            'byRoom' => $byRoom,
            'byStatus' => $byStatus,
            'byTimeSlot' => $byTimeSlot,
            'topBookers' => $topBookers,
        ]);
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->role === 'admin', 403, 'สำหรับผู้ดูแลระบบเท่านั้น');
    }
}