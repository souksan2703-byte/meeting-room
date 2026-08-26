<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminRoomController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// ต้อง login และยืนยันอีเมลแล้วเท่านั้น ถึงจะใช้ระบบจองห้องได้
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

    Route::get('/rooms/{room}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Admin — เข้าได้เฉพาะ role = admin เท่านั้น เช็คใน controller
    Route::get('/admin/bookings', [AdminDashboardController::class, 'index'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{booking}/approve', [AdminDashboardController::class, 'approve'])->name('admin.bookings.approve');
    Route::patch('/admin/bookings/{booking}/reject', [AdminDashboardController::class, 'reject'])->name('admin.bookings.reject');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::patch('/admin/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.role');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');

    // Admin — จัดการห้องประชุม
    Route::get('/admin/rooms', [AdminRoomController::class, 'index'])->name('admin.rooms.index');
    Route::get('/admin/rooms/create', [AdminRoomController::class, 'create'])->name('admin.rooms.create');
    Route::post('/admin/rooms', [AdminRoomController::class, 'store'])->name('admin.rooms.store');
    Route::get('/admin/rooms/{room}/edit', [AdminRoomController::class, 'edit'])->name('admin.rooms.edit');
    Route::put('/admin/rooms/{room}', [AdminRoomController::class, 'update'])->name('admin.rooms.update');
    Route::delete('/admin/rooms/{room}', [AdminRoomController::class, 'destroy'])->name('admin.rooms.destroy');
});

require __DIR__.'/auth.php';