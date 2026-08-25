<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->orderByDesc('created_at')
            ->paginate(15);

        // เปิดหน้านี้ = ถือว่าอ่านแล้วทั้งหมด
        $request->user()->notifications()->unread()->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }
}