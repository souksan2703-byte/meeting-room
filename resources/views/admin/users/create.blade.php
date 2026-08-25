@extends('layouts.admin')

@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500">&larr; ກັບໄປຫາຜູ້ໃຊ້</a>

    <h1 class="text-2xl font-bold mt-2 mb-1">ເພີ່ມຜູ້ໃຊ້ໃໝ່</h1>
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
            <label class="text-xs font-medium text-gray-600">ຊື່-ນາມສະກຸນ</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">ອີເມວ</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">ລະຫັດຜ່ານຊົ່ວຄາວ</label>
            <input type="text" name="password" value="{{ old('password') }}" placeholder="แนะนำให้พนักงานเปลี่ยนรหัสผ่านเองภายหลัง"
                   class="w-full border rounded-lg p-2 text-sm" required>
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">ສິດຜູ້ໃຊ້</label>
            <select name="role" class="w-full border rounded-lg p-2 text-sm">
                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>ພະນັກງານ</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>ຜູ້ດູແລລະບົບ</option>
            </select>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t">
            <a href="{{ route('admin.users.index') }}" class="border rounded-lg px-4 py-2 text-sm">ຍົກເລີກ</a>
            <button type="submit" class="bg-red-700 text-white rounded-lg px-4 py-2 text-sm">ສ້າງຜູ້ໃຊ້</button>
        </div>
    </form>
</div>
@endsection