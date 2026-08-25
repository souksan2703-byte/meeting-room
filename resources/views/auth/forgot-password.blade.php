@extends('layouts.guest')

@section('title', 'Forgot Password - RoomReserve')

@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-1">Forgot your password?</h2>
<p class="text-sm text-gray-500 mb-6">กรอกอีเมลที่ใช้สมัคร เราจะส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ให้</p>

@if (session('status'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">
        {{ session('status') }}
    </div>
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

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
    @csrf

    <div>
        <label class="text-xs font-medium text-gray-600">Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               class="w-full border rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-red-200 focus:border-red-500 outline-none"
               required autofocus>
    </div>

    <button type="submit"
            class="w-full bg-red-700 text-white rounded-lg py-2.5 text-sm font-semibold hover:bg-red-600 transition-colors duration-150 active:scale-[0.98]">
        Email Password Reset Link
    </button>

    <a href="{{ route('login') }}" class="block text-center text-sm text-gray-500 hover:text-red-700 mt-2">
        &larr; Back to login
    </a>
</form>
@endsection