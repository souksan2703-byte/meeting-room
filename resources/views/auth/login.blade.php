@extends('layouts.guest')

@section('title', 'Login - RoomReserve')

@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-1">Welcome back</h2>
<p class="text-sm text-gray-500 mb-6">ເຂົ້າສູ່ລະບົບເພື່ອຈອງຫ້ອງປະຊຸມ</p>

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

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
        <label class="text-xs font-medium text-gray-600">Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               class="w-full border rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-red-200 focus:border-red-500 outline-none"
               required autofocus autocomplete="username">
    </div>

    <div>
        <label class="text-xs font-medium text-gray-600">Password</label>
        <input type="password" name="password"
               class="w-full border rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-red-200 focus:border-red-500 outline-none"
               required autocomplete="current-password">
    </div>

    <div class="flex items-center justify-between text-sm">
        <label class="flex items-center gap-2 text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-700 focus:ring-red-500">
            Remember me
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-red-700 hover:underline">
                Forgot password?
            </a>
        @endif
    </div>

    <button type="submit"
            class="w-full bg-red-700 text-white rounded-lg py-2.5 text-sm font-semibold hover:bg-red-600 transition-colors duration-150 active:scale-[0.98]">
        Log in
    </button>
</form>
@endsection