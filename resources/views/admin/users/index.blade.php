@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold">Users</h1>
        <p class="text-gray-500">จัดการผู้ใช้งานและสิทธิ์การเข้าถึงระบบ ({{ $totalAdmins }} Admin ทั้งหมด)</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Add New User
    </a>
</div>

@if (session('success'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm">
    <div class="flex items-center justify-between p-4 border-b">
        <h2 class="text-lg font-bold">All Users</h2>
        <form method="GET" action="{{ route('admin.users.index') }}">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search name or email..."
                   onchange="this.form.submit()"
                   class="border rounded-lg px-4 py-2 text-sm w-64">
        </form>
    </div>

    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="p-3">Name</th>
                <th class="p-3">Email</th>
                <th class="p-3">Role</th>
                <th class="p-3">Bookings</th>
                <th class="p-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr class="border-t">
                    <td class="p-3 font-medium flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-red-700 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                        {{ $user->name }}
                        @if ($user->id === auth()->id())
                            <span class="text-xs text-gray-400">(คุณ)</span>
                        @endif
                    </td>
                    <td class="p-3 text-gray-600">{{ $user->email }}</td>
                    <td class="p-3">
                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="inline">
                            @csrf @method('PATCH')
                            <select name="role" onchange="this.form.submit()"
                                    class="text-xs rounded-full px-2.5 py-1 border-0 font-medium cursor-pointer
                                           {{ $user->role === 'admin' ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                                <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td class="p-3 text-gray-600">{{ $user->bookings_count }}</td>
                    <td class="p-3 text-right">
                        @if ($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('ลบบัญชี {{ $user->name }}?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-600 border border-red-200 rounded-lg px-3 py-1 text-xs hover:bg-red-50">
                                    Delete
                                </button>
                            </form>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-4 text-center text-gray-500">ไม่พบผู้ใช้งาน</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="flex items-center justify-between p-4 border-t text-sm text-gray-500">
        <span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries</span>
        <div>{{ $users->links() }}</div>
    </div>
</div>
@endsection