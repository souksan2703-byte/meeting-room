@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <h1 class="text-3xl font-bold">ໂປຣໄຟລ໌</h1>
        <p class="text-gray-500">ຈັດການຂໍ້ມູນບັນຊີຂອງທ່ານ ແລະ ຄວາມປອດໄພ</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3">ບັນທຶກຂໍ້ມູນສຳເລັດແລ້ວ</div>
    @endif
    @if (session('status') === 'password-updated')
        <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3">ປ່ຽນລະຫັດຜ່ານສຳເລັດແລ້ວ</div>
    @endif

    {{-- Profile Information --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="font-bold text-lg mb-1">ຂໍ້ມູນໂປຣໄຟລ໌</h2>
        <p class="text-sm text-gray-500 mb-4">ແກ້ໄຂຊື່ບັນຊີ ແລະ ທີ່ຢູ່ອີເມວ</p>

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
                <label class="text-xs font-medium text-gray-600">ຊື່</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full border rounded-lg p-2 text-sm" required>
            </div>

            <div>
                <label class="text-xs font-medium text-gray-600">ອີເມວ</label>
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
        <h2 class="font-bold text-lg mb-1">ປ່ຽນລະຫັດຜ່ານ</h2>
        <p class="text-sm text-gray-500 mb-4">ໃຊ້ລະຫັດຜ່ານທີ່ຍາວ ແລະ ຄາດເດົາຍາກເພື່ອຄວາມປອດໄພ.</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-xs font-medium text-gray-600">ລະຫັດຜ່ານປັດຈຸບັນ</label>
                <input type="password" name="current_password" class="w-full border rounded-lg p-2 text-sm">
            </div>

            <div>
                <label class="text-xs font-medium text-gray-600">ລະຫັດຜ່ານໃໝ່</label>
                <input type="password" name="password" class="w-full border rounded-lg p-2 text-sm">
            </div>

            <div>
                <label class="text-xs font-medium text-gray-600">ຢືນຢັນລະຫັດຜ່ານໃໝ່</label>
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
        <h2 class="font-bold text-lg mb-1 text-red-700">ລຶບບັນຊີ</h2>
        <p class="text-sm text-gray-500 mb-4">ການລຶບບັນຊີຂອງທ່ານຢ່າງຖາວອນຈະເປັນການລຶບຂໍ້ມູນການຈອງທັງໝົດຂອງທ່ານແລະບໍ່ສາມາດກູ້ຄືນໄດ້</p>

        <form method="POST" action="{{ route('profile.destroy') }}"
              onsubmit="return confirm('ยืนยันลบบัญชีถาวร? ไม่สามารถกู้คืนได้')" class="space-y-4">
            @csrf
            @method('DELETE')

            <div>
                <label class="text-xs font-medium text-gray-600">ລະຫັດຜ່ານ (ຢືນຢັນຕົວຕົນກ່ອນລຶບ)</label>
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