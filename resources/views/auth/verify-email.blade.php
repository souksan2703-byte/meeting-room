@extends('layouts.guest')

@section('title', 'Verify Email - RoomReserve')

@section('content')
<div class="text-center mb-4">
    <div class="w-14 h-14 rounded-full bg-red-50 text-red-700 flex items-center justify-center text-2xl mx-auto mb-3">
        ✉️
    </div>
    <h2 class="text-xl font-bold text-gray-800 mb-1">Verify your email</h2>
    <p class="text-sm text-gray-500">
        ພວກເຮົາໄດ້ສົ່ງລິ້ງຢືນຢັນໄປຫາອີເມວຂອງທ່ານແລ້ວ. ກະລຸນາຄລິກລິ້ງໃນອີເມວກ່ອນທີ່ຈະນຳໃຊ້ລະບົບ.
    </p>
</div>

@if (session('status') === 'verification-link-sent')
    <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4 text-center">
       ລິ້ງຢືນຢັນໃໝ່ໄດ້ຖືກສົ່ງໄປຫາອີເມວຂອງທ່ານສຳເລັດແລ້ວ.
    </div>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit"
            class="w-full bg-red-700 text-white rounded-lg py-2.5 text-sm font-semibold hover:bg-red-600 transition-colors duration-150 active:scale-[0.98]">
        Resend Verification Email
    </button>
</form>

<form method="POST" action="{{ route('logout') }}" class="mt-3">
    @csrf
    <button type="submit" class="w-full text-center text-sm text-gray-500 hover:text-red-700 py-2">
        Log out
    </button>
</form>
@endsection