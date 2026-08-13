<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Profile routes (จาก Breeze เดิม — ถ้าโปรเจกต์คุณไม่มี ProfileController ให้ลบ 3 บรรทัดนี้ทิ้ง)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ระบบจองห้องประชุม
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

    Route::get('/rooms/{room}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Admin — approve/reject bookings (เข้าได้เฉพาะ role = admin เท่านั้น เช็คใน controller)
    Route::get('/admin/bookings', [AdminDashboardController::class, 'index'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{booking}/approve', [AdminDashboardController::class, 'approve'])->name('admin.bookings.approve');
    Route::patch('/admin/bookings/{booking}/reject', [AdminDashboardController::class, 'reject'])->name('admin.bookings.reject');
});

require __DIR__.'/auth.php';