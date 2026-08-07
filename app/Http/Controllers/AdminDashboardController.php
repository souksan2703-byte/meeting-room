<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count();

        $totalBookings = Booking::count();

        $pendingBookings = Booking::where('status', 'pending')->count();

        $latestBookings = Booking::latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalRooms',
            'totalBookings',
            'pendingBookings',
            'latestBookings'
        ));
    }
}