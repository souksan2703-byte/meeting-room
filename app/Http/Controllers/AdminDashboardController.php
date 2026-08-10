<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;

class AdminDashboardController extends Controller
{
   public function index()
{
    // จำนวนห้องทั้งหมด
    $totalRooms = Room::count();

    // จำนวนการจองของวันนี้
    $todayBookings = Booking::whereDate(
        'booking_date',
        today()
    )->count();

    // จำนวนห้องที่ถูกใช้งานในวันนี้
    // distinct() ป้องกันกรณีห้องเดียวกันมีหลายการจอง
    $usedRoomsToday = Booking::whereDate(
        'booking_date',
        today()
    )
    ->distinct('room_id')
    ->count('room_id');

    // จำนวนห้องว่างวันนี้
    $availableRooms = max(
        $totalRooms - $usedRoomsToday,
        0
    );

    // เปอร์เซ็นต์การใช้งาน
    $usagePercentage = $totalRooms > 0
        ? round(($usedRoomsToday / $totalRooms) * 100)
        : 0;

    // รายการจองล่าสุด
    $latestBookings = Booking::with('room')
        ->latest()
        ->take(5)
        ->get();

    return view('dashboard.index', compact(
        'totalRooms',
        'todayBookings',
        'usedRoomsToday',
        'availableRooms',
        'usagePercentage',
        'latestBookings'
    ));
}
}