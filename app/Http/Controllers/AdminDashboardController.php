<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // เวลาปัจจุบัน
        $now = Carbon::now();

        // วันที่วันนี้
        $today = $now->toDateString();

        // เวลาปัจจุบัน
        $currentTime = $now->format('H:i:s');


        // ==========================================
        // จำนวนห้องทั้งหมด
        // ==========================================

        $totalRooms = Room::count();


        // ==========================================
        // การจองทั้งหมดของวันนี้
        // ==========================================

        $todayBookings = Booking::whereDate(
            'booking_date',
            $today
        )->count();


        // ==========================================
        // ห้องที่กำลังใช้งานอยู่ตอนนี้
        // start_time <= เวลาปัจจุบัน
        // end_time   >  เวลาปัจจุบัน
        // ==========================================

        $usedRoomsToday = Booking::whereDate(
            'booking_date',
            $today
        )
        ->where('start_time', '<=', $currentTime)
        ->where('end_time', '>', $currentTime)
        ->distinct('room_id')
        ->count('room_id');


        // ==========================================
        // ห้องที่จองแล้ว แต่ยังไม่ถึงเวลาใช้งาน
        // ==========================================

        $bookedRoomsToday = Booking::whereDate(
            'booking_date',
            $today
        )
        ->where('start_time', '>', $currentTime)
        ->distinct('room_id')
        ->count('room_id');


        // ==========================================
        // ห้องว่างตอนนี้
        // ==========================================

        $availableRooms = max(
            $totalRooms - $usedRoomsToday - $bookedRoomsToday,
            0
        );


        // ==========================================
        // รายการจองล่าสุด
        // ==========================================

        $latestBookings = Booking::with('room')
            ->latest()
            ->take(5)
            ->get();


        // ==========================================
        // ส่งข้อมูลไป Dashboard
        // ==========================================

        return view('dashboard.index', compact(
            'totalRooms',
            'todayBookings',
            'usedRoomsToday',
            'bookedRoomsToday',
            'availableRooms',
            'latestBookings'
        ));
    }
}