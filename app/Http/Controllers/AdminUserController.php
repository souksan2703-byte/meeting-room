<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $search = $request->input('q');

        $users = User::withCount('bookings')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalAdmins = User::where('role', 'admin')->count();

        return view('admin.users.index', compact('users', 'search', 'totalAdmins'));
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin($request);

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:staff,admin'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(), // Admin เป็นคนสร้างเอง ถือว่ายืนยันตัวตนแล้ว ไม่ต้องให้ยืนยันอีเมลซ้ำ
        ]);

        return redirect()->route('admin.users.index')->with('success', 'สร้างบัญชีให้ "' . $validated['name'] . '" เรียบร้อยแล้ว');
    }

    public function updateRole(Request $request, User $user)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'role' => ['required', 'in:staff,admin'],
        ]);

        // กันไม่ให้ระบบเหลือ admin 0 คน (ลด role ของ admin คนสุดท้ายไม่ได้)
        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['role' => 'ຕ້ອງມີ Admin ຢ່າງໜ້ອຍໜຶ່ງຄົນຢູ່ໃນລະບົບສະເໝີ.']);
            }
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'ປ່ຽນສິດອະນຸຍາດຂອງ "' . $user->name . '" ເປັນ ' . ucfirst($validated['role']) . ' ແລ້ວ');
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorizeAdmin($request);

        if ($user->id === $request->user()->id) {
            return back()->withErrors(['delete' => 'ທ່ານບໍ່ສາມາດລຶບບັນຊີຂອງທ່ານເອງໄດ້']);
        }

        if ($user->bookings()->exists()) {
            return back()->withErrors(['delete' => 'ບໍ່ສາມາດລຶບໄດ້ "' . $user->name . '" ໄດ້ , ເພາະວ່າປະຫວັດການຈອງຍັງຢູ່ໃນລະບົບ']);
        }

        $user->delete();

        return back()->with('success', 'ລຶບບັນຊີ "' . $user->name . '" ສຳເລັດແລ້ວ');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->role === 'admin', 403, 'ສຳລັບຜູ້ເບິ່ງແຍງລະບົບເທົ່ານັ້ນ');
    }
}