<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count();

        $todayBookings = Booking::whereDate(
            'booking_date',
            today()
        )->count();

        $availableRooms = max(
            $totalRooms - $todayBookings,
            0
        );

        $latestBookings = Booking::with('room')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalRooms',
            'todayBookings',
            'availableRooms',
            'latestBookings'
        ));
    }
}