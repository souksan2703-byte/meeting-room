<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * ส่งแจ้งเตือนไปหา Admin ทุกคนในระบบ
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
        }
    }

    /**
     * ส่งแจ้งเตือนไปหา user คนใดคนหนึ่งโดยเฉพาะ
     */
    public static function notifyUser(int $userId, string $title, ?string $body = null, ?string $link = null): void
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
            'link' => $link,
        ]);
    }
}