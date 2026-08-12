<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [AdminDashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('rooms', RoomController::class);

Route::resource('bookings', BookingController::class);

Route::get('/calendar/events', [BookingController::class, 'calendarEvents'])
    ->name('calendar.events');