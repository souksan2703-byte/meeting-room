<?php

namespace App\Services;

use App\Mail\BookingNotificationMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * ส่งแจ้งเตือนไปหา Admin ทุกคนในระบบ (ทั้ง in-app + อีเมล)
     */
    public static function notifyAdmins(string $title, ?string $body = null, ?string $link = null): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => $title,
                'body' => $body,
                'link' => $link,
            ]);

            self::sendEmail($admin, $title, $body, $link);
        }
    }

    /**
     * ส่งแจ้งเตือนไปหา user คนใดคนหนึ่งโดยเฉพาะ (ทั้ง in-app + อีเมล)
     */
    public static function notifyUser(int $userId, string $title, ?string $body = null, ?string $link = null): void
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
            'link' => $link,
        ]);

        $user = User::find($userId);
        if ($user) {
            self::sendEmail($user, $title, $body, $link);
        }
    }

    private static function sendEmail(User $user, string $title, ?string $body, ?string $link): void
    {
        try {
            Mail::to($user->email)->send(new BookingNotificationMail($title, $body ?? '', $link));
        } catch (\Throwable $e) {
            // ถ้าส่งอีเมลไม่สำเร็จ (เช่นยังไม่ตั้งค่า mail server) ไม่ต้องให้ระบบล่ม
            // การแจ้งเตือนในเว็บ (in-app) ยังคงถูกบันทึกไว้ปกติ
            report($e);
        }
    }
}