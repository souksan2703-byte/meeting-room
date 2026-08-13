<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // จำนวนห้องทั้งหมด
        $totalRooms = Room::count();

        // จำนวนการจองทั้งหมด
        $totalBookings = Booking::count();

        // จำนวนการจองวันนี้
        $todayBookings = Booking::whereDate(
            'booking_date',
            Carbon::today()
        )->count();

        // จำนวนการจองที่รออนุมัติ
        $pendingBookings = Booking::where(
            'status',
            'Pending'
        )->count();

        // รายการจองล่าสุด
        $latestBookings = Booking::with('room')
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalRooms',
            'totalBookings',
            'todayBookings',
            'pendingBookings',
            'latestBookings'
        ));
    }
}