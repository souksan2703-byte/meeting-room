@extends('layouts.guest')

@section('title', 'Reset Password - RoomReserve')

@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-1">Reset your password</h2>
<p class="text-sm text-gray-500 mb-6">ຕັ້ງລະຫັດຜ່ານໃໝ່ສຳລັບບັນຊີຂອງທ່ານ.</p>

@if ($errors->any())
    <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('password.store') }}" class="space-y-4">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') ?? $token ?? '' }}">

    <div>
        <label class="text-xs font-medium text-gray-600">Email</label>
        <input type="email" name="email" value="{{ old('email', $request->email ?? '') }}"
               class="w-full border rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-red-200 focus:border-red-500 outline-none"
               required autofocus>
    </div>

    <div>
        <label class="text-xs font-medium text-gray-600">New Password</label>
        <input type="password" name="password"
               class="w-full border rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-red-200 focus:border-red-500 outline-none"
               required autocomplete="new-password">
    </div>

    <div>
        <label class="text-xs font-medium text-gray-600">Confirm New Password</label>
        <input type="password" name="password_confirmation"
               class="w-full border rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-red-200 focus:border-red-500 outline-none"
               required autocomplete="new-password">
    </div>

    <button type="submit"
            class="w-full bg-red-700 text-white rounded-lg py-2.5 text-sm font-semibold hover:bg-red-600 transition-colors duration-150 active:scale-[0.98]">
        Reset Password
    </button>
</form>
@endsection