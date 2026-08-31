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
            title: 'ການຈອງຂອງທ່ານໄດ້ຮັບການອະນຸມັດ',
            body: '"' . $booking->title . '" ທີ່ ' . $booking->room->name . ' ຢືນຢັນແລ້ວ',
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
                title: 'ການຈອງຂອງທ່ານບໍ່ໄດ້ຮັບການອະນຸມັດ',
                body: '"' . $competing->title . '" ທີ່ ' . $competing->room->name . ' ຊ່ວງເວລານີ້ໄດ້ຖືກມອບໃຫ້ຄົນອື່ນແລ້ວ',
                link: route('bookings.index')
            );
        }

        $message = 'ອະນຸມັດການຈອງແລ້ວ "' . $booking->title . '" ສຳເລັດແລ້ວ';
        if ($competingBookings->isNotEmpty()) {
            $message .= ' ແລະມັນຍັງໄດ້ປະຕິເສດຄຳຂໍອີກອັນໜຶ່ງທີ່ເກີດຂຶ້ນໃນເວລາດຽວກັນ. ' . $competingBookings->count() . 'ລາຍຊື່ອັດຕະໂນມັດ';
        }

        return back()->with('success', $message);
    }

    public function reject(Request $request, Booking $booking)
    {
        $this->authorizeAdmin($request);

        $booking->update(['status' => 'cancelled']);

        NotificationService::notifyUser(
            userId: $booking->user_id,
            title: 'ການຈອງຂອງທ່ານບໍ່ໄດ້ຮັບການອະນຸມັດ',
            body: '"' . $booking->title . '" ທີ່ ' . $booking->room->name . ' ບໍ່ໄດ້ຮັບການອະນຸມັດ',
            link: route('bookings.index')
        );

        return back()->with('success', 'ການຈອງຖືກປະຕິເສດ "' . $booking->title . '" ສຳເລັດແລ້ວ');
    }

    /**
     * เฉพาะ user ที่ role = admin ເທົ່ານັ້ນທี่เข้าหน้านี้ได้
     */
    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->role === 'admin', 403, 'ສຳລັບຜູ້ເບິ່ງແຍງລະບົບເທົ່ານັ້ນ');
    }
}