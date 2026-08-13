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

Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');

Route::get('/calendar/events', [BookingController::class, 'calendarEvents'])
    ->name('calendar.events');

Route::patch('/bookings/{booking}/approve', [BookingController::class, 'approve'])
    ->name('bookings.approve');

Route::patch('/bookings/{booking}/reject', [BookingController::class, 'reject'])
    ->name('bookings.reject');

Route::resource('bookings', BookingController::class);