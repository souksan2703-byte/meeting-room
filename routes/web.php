<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('dashboard.index');
});

Route::resource('rooms', RoomController::class);
Route::resource('bookings', BookingController::class);