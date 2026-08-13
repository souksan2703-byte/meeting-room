<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $status = $request->input('status', 'pending');

        $bookings = Booking::with(['room', 'user'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->orderBy('start_time')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'status', 'counts'));
    }

    public function approve(Request $request, Booking $booking)
    {
        $this->authorizeAdmin($request);

        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'อนุมัติการจอง "' . $booking->title . '" เรียบร้อยแล้ว');
    }

    public function reject(Request $request, Booking $booking)
    {
        $this->authorizeAdmin($request);

        // schema มีแค่ confirmed/pending/cancelled ไม่มี "rejected" แยก
        // จึงใช้ cancelled แทนการปฏิเสธ
        $booking->update(['status' => 'cancelled']);

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