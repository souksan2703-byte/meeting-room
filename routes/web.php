<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;

Route::get('/', [RoomController::class, 'index']);

Route::resource('rooms', RoomController::class);
Route::resource('bookings', BookingController::class);