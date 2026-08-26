<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $search = $request->input('q');

        $bookings = Booking::with(['room', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('room', fn ($r) => $r->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        // --- Stat cards ---
        $totalRooms = Room::count();
        $bookingsToday = Booking::whereDate('start_time', now()->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->count();
        $bookingsYesterday = Booking::whereDate('start_time', now()->subDay()->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->count();
        $activeUsers = User::count();

        // จำนวนผู้เข้าร่วมประชุมจริงของวันนี้ (รวมทุกห้อง ไม่นับที่ถูกยกเลิก)
        $totalAttendeesToday = Booking::whereDate('start_time', now()->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->get()
            ->sum(fn ($booking) => $booking->attendeesCount());

        $stats = [
            'totalRooms' => $totalRooms,
            'bookingsToday' => $bookingsToday,
            'bookingsDelta' => $bookingsToday - $bookingsYesterday,
            'activeUsers' => $activeUsers,
            'totalAttendeesToday' => $totalAttendeesToday,
        ];

        return view('admin.bookings.index', compact('bookings', 'stats', 'search'));
    }

    public function approve(Request $request, Booking $booking)
    {
        $this->authorizeAdmin($request);

        $booking->update(['status' => 'confirmed']);

        NotificationService::notifyUser(
            userId: $booking->user_id,
            title: 'การจองของคุณได้รับการอนุมัติแล้ว',
            body: '"' . $booking->title . '" ที่ ' . $booking->room->name . ' ได้รับการยืนยันแล้ว',
            link: route('bookings.index')
        );

        // คำขออื่นที่ยังเป็น Pending และชนช่วงเวลาเดียวกันในห้องเดียวกัน ถือว่าตกไปโดยอัตโนมัติ
        $competingBookings = Booking::where('room_id', $booking->room_id)
            ->where('id', '!=', $booking->id)
            ->where('status', 'pending')
            ->where('start_time', '<', $booking->end_time)
            ->where('end_time', '>', $booking->start_time)
            ->get();

        foreach ($competingBookings as $competing) {
            $competing->update(['status' => 'cancelled']);

            NotificationService::notifyUser(
                userId: $competing->user_id,
                title: 'การจองของคุณไม่ได้รับการอนุมัติ',
                body: '"' . $competing->title . '" ที่ ' . $competing->room->name . ' ช่วงเวลานี้ถูกอนุมัติให้ผู้อื่นไปแล้ว',
                link: route('bookings.index')
            );
        }

        $message = 'อนุมัติการจอง "' . $booking->title . '" เรียบร้อยแล้ว';
        if ($competingBookings->isNotEmpty()) {
            $message .= ' และปฏิเสธคำขออื่นที่ชนเวลาเดียวกันอีก ' . $competingBookings->count() . ' รายการโดยอัตโนมัติ';
        }

        return back()->with('success', $message);
    }

    public function reject(Request $request, Booking $booking)
    {
        $this->authorizeAdmin($request);

        $booking->update(['status' => 'cancelled']);

        NotificationService::notifyUser(
            userId: $booking->user_id,
            title: 'การจองของคุณถูกปฏิเสธ',
            body: '"' . $booking->title . '" ที่ ' . $booking->room->name . ' ไม่ได้รับการอนุมัติ',
            link: route('bookings.index')
        );

        return back()->with('success', 'ปฏิเสธการจอง "' . $booking->title . '" แล้ว');
    }

    /**
     * เฉพาะ user ที่ role = admin เท่านั้นที่เข้าหน้านี้ได้
     */
    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->role === 'admin', 403, 'สำหรับผู้ดูแลระบบเท่านั้น');
    }
}