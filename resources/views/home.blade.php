<!DOCTYPE html>
<html>
<head>
    <title>ລະບົບຈອງຫ້ອງປະຊຸມ</title>
</head>
<body>

<h1>ລະບົບຈອງຫ້ອງປະຊຸມ</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>ຊື່ຫ້ອງ</th>
        <th>ຄວາມຈຸ</th>
    </tr>

    @forelse($rooms as $room)
    <tr>
        <td>{{ $room->id }}</td>
        <td>{{ $room->name }}</td>
        <td>{{ $room->capacity }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="3">ຍັງບໍ່ມີຫ້ອງປະຊຸມ</td>
    </tr>
    @endforelse

</table>

</body>
</html>