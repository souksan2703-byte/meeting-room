<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background:#f9fafb; padding:24px;">
    <div style="max-width:480px; margin:0 auto; background:#ffffff; border-radius:8px; padding:24px; border:1px solid #eee;">
        <h2 style="color:#b91c1c; margin-top:0;">RoomReserve</h2>
        <h3 style="margin-bottom:8px;">{{ $mailTitle }}</h3>
        <p style="color:#374151; line-height:1.6;">{{ $mailBody }}</p>

        @if ($mailLink)
            <p style="margin-top:24px;">
                <a href="{{ $mailLink }}" style="background:#b91c1c; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none; font-size:14px;">
                    เปิดดูรายละเอียด
                </a>
            </p>
        @endif

        <p style="color:#9ca3af; font-size:12px; margin-top:32px;">อีเมลนี้ส่งอัตโนมัติจากระบบ RoomReserve ไม่ต้องตอบกลับ</p>
    </div>
</body>
</html>