@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <h1 class="text-3xl font-bold">Profile</h1>
        <p class="text-gray-500">จัดการข้อมูลบัญชีและความปลอดภัยของคุณ</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3">บันทึกข้อมูลเรียบร้อยแล้ว</div>
    @endif
    @if (session('status') === 'password-updated')
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3">เปลี่ยนรหัสผ่านเรียบร้อยแล้ว</div>
    @endif

    {{-- Profile Information --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="font-bold text-lg mb-1">Profile Information</h2>
        <p class="text-sm text-gray-500 mb-4">แก้ไขชื่อและอีเมลของบัญชี</p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="text-xs font-medium text-gray-600">Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>

            <div>
                <label class="text-xs font-medium text-gray-600">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-red-700 text-white rounded-lg px-4 py-2 text-sm font-medium">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="font-bold text-lg mb-1">Update Password</h2>
        <p class="text-sm text-gray-500 mb-4">ใช้รหัสผ่านที่ยาวและคาดเดายากเพื่อความปลอดภัย</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-xs font-medium text-gray-600">Current Password</label>
                <input type="password" name="current_password" class="w-full border rounded-lg p-2 text-sm">
            </div>

            <div>
                <label class="text-xs font-medium text-gray-600">New Password</label>
                <input type="password" name="password" class="w-full border rounded-lg p-2 text-sm">
            </div>

            <div>
                <label class="text-xs font-medium text-gray-600">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded-lg p-2 text-sm">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-red-700 text-white rounded-lg px-4 py-2 text-sm font-medium">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- Delete Account --}}
    <div class="bg-white rounded-lg shadow-sm p-6 border border-red-100">
        <h2 class="font-bold text-lg mb-1 text-red-700">Delete Account</h2>
        <p class="text-sm text-gray-500 mb-4">ลบบัญชีถาวร ข้อมูลการจองทั้งหมดของคุณจะถูกลบไปด้วย ทำแล้วกู้คืนไม่ได้</p>

        <form method="POST" action="{{ route('profile.destroy') }}"
              onsubmit="return confirm('ยืนยันลบบัญชีถาวร? ไม่สามารถกู้คืนได้')" class="space-y-4">
            @csrf
            @method('DELETE')

            <div>
                <label class="text-xs font-medium text-gray-600">Password (ยืนยันตัวตนก่อนลบ)</label>
                <input type="password" name="password" class="w-full border rounded-lg p-2 text-sm">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-red-700 text-white rounded-lg px-4 py-2 text-sm font-medium">
                    Delete Account
                </button>
            </div>
        </form>
    </div>

</div>
@endsection