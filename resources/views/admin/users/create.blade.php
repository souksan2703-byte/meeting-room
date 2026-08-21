@extends('layouts.admin')

@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500">&larr; Back to Users</a>

    <h1 class="text-2xl font-bold mt-2 mb-1">Add New User</h1>
    <p class="text-gray-500 mb-6">สร้างบัญชีให้พนักงานใหม่ (ไม่ต้องเปิดให้สมัครเองสาธารณะ)</p>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        @csrf

        <div>
            <label class="text-xs font-medium text-gray-600">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Temporary Password</label>
            <input type="text" name="password" value="{{ old('password') }}" placeholder="แนะนำให้พนักงานเปลี่ยนรหัสผ่านเองภายหลัง"
                   class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Role</label>
            <select name="role" class="w-full border rounded-lg p-2 text-sm">
                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t">
            <a href="{{ route('admin.users.index') }}" class="border rounded-lg px-4 py-2 text-sm">Cancel</a>
            <button type="submit" class="bg-red-700 text-white rounded-lg px-4 py-2 text-sm">Create User</button>
        </div>
    </form>
</div>
@endsection