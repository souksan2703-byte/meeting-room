<!DOCTYPE html>
<html lang="lo">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ແກ້ໄຂການຈອງ</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card shadow-sm">

        <div class="card-body p-4">

            <h2 class="fw-bold mb-4">
                ແກ້ໄຂການຈອງຫ້ອງປະຊຸມ
            </h2>

            @if(session('error'))

                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>

            @endif

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                action="{{ route('bookings.update', $booking->id) }}"
                method="POST"
            >

                @csrf

                @method('PUT')

                <div class="row g-3">

                    {{-- ห้องประชุม --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            ຫ້ອງປະຊຸມ
                        </label>

                        <select
                            name="room_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- ເລືອກຫ້ອງ --
                            </option>

                            @foreach($rooms as $room)

                                <option
                                    value="{{ $room->id }}"
                                    {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}
                                >
                                    {{ $room->name }}
                                    ({{ $room->capacity }} ຄົນ)
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- ผู้จอง --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            ຊື່ຜູ້ຈອງ
                        </label>

                        <input
                            type="text"
                            name="booker_name"
                            class="form-control"
                            value="{{ old('booker_name', $booking->booker_name) }}"
                            required
                        >

                    </div>


                    {{-- หน่วยงาน --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            ພະແນກ / ໜ່ວຍງານ
                        </label>

                        <input
                            type="text"
                            name="department"
                            class="form-control"
                            value="{{ old('department', $booking->department) }}"
                            required
                        >

                    </div>


                    {{-- หัวข้อ --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            ຫົວຂໍ້ການປະຊຸມ
                        </label>

                        <input
                            type="text"
                            name="meeting_title"
                            class="form-control"
                            value="{{ old('meeting_title', $booking->meeting_title) }}"
                            required
                        >

                    </div>


                    {{-- วันที่ --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            ວັນທີ
                        </label>

                        <input
                            type="date"
                            name="booking_date"
                            class="form-control"
                            value="{{ old('booking_date', $booking->booking_date) }}"
                            required
                        >

                    </div>


                    {{-- เวลาเริ่ม --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            ເວລາເລີ່ມ
                        </label>

                        <input
                            type="time"
                            name="start_time"
                            class="form-control"
                            value="{{ old('start_time', substr($booking->start_time, 0, 5)) }}"
                            required
                        >

                    </div>


                    {{-- เวลาสิ้นสุด --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            ເວລາສິ້ນສຸດ
                        </label>

                        <input
                            type="time"
                            name="end_time"
                            class="form-control"
                            value="{{ old('end_time', substr($booking->end_time, 0, 5)) }}"
                            required
                        >

                    </div>


                    {{-- จำนวนผู้เข้าร่วม --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            ຈຳນວນຜູ້ເຂົ້າຮ່ວມ
                        </label>

                        <input
                            type="number"
                            name="attendees"
                            class="form-control"
                            value="{{ old('attendees', $booking->attendees) }}"
                            min="1"
                            required
                        >

                    </div>


                    {{-- สถานะ --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            ສະຖານະ
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="Pending"
                                {{ old('status', $booking->status) == 'Pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="Approved"
                                {{ old('status', $booking->status) == 'Approved' ? 'selected' : '' }}
                            >
                                Approved
                            </option>

                            <option
                                value="Rejected"
                                {{ old('status', $booking->status) == 'Rejected' ? 'selected' : '' }}
                            >
                                Rejected
                            </option>

                        </select>

                    </div>


                    {{-- หมายเหตุ --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            ໝາຍເຫດ
                        </label>

                        <input
                            type="text"
                            name="note"
                            class="form-control"
                            value="{{ old('note', $booking->note) }}"
                            placeholder="ລາຍລະອຽດເພີ່ມເຕີມ"
                        >

                    </div>


                    {{-- น้ำดื่ม --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            ນໍ້າດື່ມ
                        </label>

                        <input
                            type="text"
                            name="drinking_water"
                            class="form-control"
                            value="{{ old('drinking_water', $booking->drinking_water) }}"
                            placeholder="ເຊັ່ນ 20 ຂວດ / 2 ແພັກ"
                        >

                    </div>

                </div>


                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        ບັນທຶກການແກ້ໄຂ
                    </button>

                    <a
                        href="{{ route('bookings.index') }}"
                        class="btn btn-secondary"
                    >
                        ຍົກເລີກ
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>

</html>
