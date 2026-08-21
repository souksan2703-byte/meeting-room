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
                return back()->withErrors(['role' => 'ต้องมี Admin เหลืออย่างน้อย 1 คนในระบบเสมอ']);
            }
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'เปลี่ยนสิทธิ์ของ "' . $user->name . '" เป็น ' . ucfirst($validated['role']) . ' แล้ว');
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorizeAdmin($request);

        if ($user->id === $request->user()->id) {
            return back()->withErrors(['delete' => 'ไม่สามารถลบบัญชีของตัวเองได้']);
        }

        if ($user->bookings()->exists()) {
            return back()->withErrors(['delete' => 'ไม่สามารถลบ "' . $user->name . '" ได้ เพราะยังมีประวัติการจองอยู่ในระบบ']);
        }

        $user->delete();

        return back()->with('success', 'ลบบัญชี "' . $user->name . '" เรียบร้อยแล้ว');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->role === 'admin', 403, 'สำหรับผู้ดูแลระบบเท่านั้น');
    }
}