<!DOCTYPE html>
<html>
<head>
    <title>Meeting Room Booking</title>
</head>
<body>

<h1>ระบบจองห้องประชุม</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>ชื่อห้อง</th>
        <th>ความจุ</th>
    </tr>

    @forelse($rooms as $room)
    <tr>
        <td>{{ $room->id }}</td>
        <td>{{ $room->name }}</td>
        <td>{{ $room->capacity }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="3">ยังไม่มีข้อมูลห้องประชุม</td>
    </tr>
    @endforelse

</table>

</body>
</html>