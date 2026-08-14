<!DOCTYPE html>
<html lang="lo">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .stat-card {
            border: none;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            min-height: 140px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .calendar-card,
        .latest-card {
            border: none;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
        }

        #dashboardCalendar {
            min-height: 600px;
        }

        .booking-item {
            padding: 16px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .booking-item:last-child {
            border-bottom: none;
        }

        .room-name {
            font-size: 17px;
            font-weight: 600;
        }

        .small-label {
            font-size: 13px;
            color: #888;
        }

        .fc {
            font-size: 14px;
        }

        .fc .fc-toolbar-title {
            font-size: 22px;
            font-weight: 600;
        }

        .fc .fc-button {
            border-radius: 8px;
        }
    </style>

</head>


<body>

    @include('partials.navbar')

    <div class="container-fluid px-4 py-4">


        <!-- Header -->

        <div class="mb-4">

            <h2 class="fw-bold mb-1">
                Dashboard
            </h2>

            <p class="text-muted mb-0">
                ພາບລວມລະບົບຈອງຫ້ອງປະຊຸມ
            </p>

        </div>


        <!-- Statistics -->

        <div class="row g-4 mb-4">


            <!-- ห้องประชุม -->

            <div class="col-xl-3 col-md-6">

                <div class="card stat-card">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-muted mb-2">
                                    ຫ້ອງປະຊຸມ
                                </div>

                                <div class="fs-1 fw-bold">
                                    {{ $totalRooms }}
                                </div>

                                <small class="text-muted">
                                    ຫ້ອງທັງໝົດ
                                </small>

                            </div>

                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">

                                <i class="bi bi-building"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- จองแล้ว -->

            <div class="col-xl-3 col-md-6">

                <div class="card stat-card">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-muted mb-2">
                                    ຈອງແລ້ວ
                                </div>

                                <div class="fs-1 fw-bold text-warning">
                                    {{ $totalBookings }}
                                </div>

                                <small class="text-muted">
                                    ການຈອງທັງໝົດ
                                </small>

                            </div>

                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">

                                <i class="bi bi-calendar-check"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ใช้งานวันนี้ -->

            <div class="col-xl-3 col-md-6">

                <div class="card stat-card">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-muted mb-2">
                                    ການໃຊ້ງານ
                                </div>

                                <div class="fs-1 fw-bold text-success">
                                    {{ $todayBookings }}
                                </div>

                                <small class="text-muted">
                                    ການຈອງມື້ນີ້
                                </small>

                            </div>

                            <div class="stat-icon bg-success bg-opacity-10 text-success">

                                <i class="bi bi-door-open"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- รออนุมัติ -->

            <div class="col-xl-3 col-md-6">

                <div class="card stat-card">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-muted mb-2">
                                    ຫ້ອງວ່າງ
                                </div>

                                <div class="fs-1 fw-bold text-primary">
                                    {{ $pendingBookings }}
                                </div>

                                <small class="text-muted">
                                    ລໍຖ້າອະນຸມັດ
                                </small>

                            </div>

                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">

                                <i class="bi bi-door-closed"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Calendar + Latest Bookings -->

        <div class="row g-4">


            <!-- Calendar -->

            <div class="col-xl-7">

                <div class="card calendar-card h-100">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>

                                <h4 class="fw-bold mb-1">

                                    <i class="bi bi-calendar3 text-primary"></i>

                                    Calendar

                                </h4>

                            </div>

                            <a href="{{ route('calendar') }}" class="text-decoration-none">
                                ດາວຄະວາມການຈອງ
                            </a>

                        </div>


                        <div id="dashboardCalendar"></div>

                    </div>

                </div>

            </div>


            <!-- Latest Bookings -->

            <div class="col-xl-5">

                <div class="card latest-card h-100">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>

                                <h4 class="fw-bold mb-1">

                                    <i class="bi bi-clock-history text-primary"></i>

                                    ລາຍການຈອງລ່າສຸດ

                                </h4>

                            </div>

                        </div>


                        @forelse($latestBookings as $booking)

                            <div class="booking-item">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <div class="room-name">

                                            <i class="bi bi-building text-primary"></i>

                                            {{ $booking->room->name }}

                                        </div>

                                        <div class="small-label mt-1">

                                            {{ $booking->meeting_title }}

                                        </div>

                                    </div>


                                    @if($booking->status === 'Approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    @elseif($booking->status === 'Rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @endif

                                </div>


                                <div class="row mt-3">

                                    <div class="col-6">

                                        <div class="small-label">
                                            ຜູ້ຈອງ
                                        </div>

                                        <div>
                                            {{ $booking->booker_name }}
                                        </div>

                                    </div>


                                    <div class="col-6">

                                        <div class="small-label">
                                            ວັນທີ
                                        </div>

                                        <div>
                                            {{ $booking->booking_date }}
                                        </div>

                                    </div>

                                </div>


                                <div class="row mt-2">

                                    <div class="col-6">

                                        <div class="small-label">
                                            ເວລາ
                                        </div>

                                        <div>
                                            {{ substr($booking->start_time, 0, 5) }}
                                            -
                                            {{ substr($booking->end_time, 0, 5) }}
                                        </div>

                                    </div>


                                    <div class="col-6">

                                        <div class="small-label">
                                            ຜູ້ເຂົ້າຮ່ວມ
                                        </div>

                                        <div>
                                            {{ $booking->attendees }} ຄົນ
                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-5">

                                <i class="bi bi-calendar-x fs-1 text-muted"></i>

                                <p class="text-muted mt-3 mb-0">
                                    ຍັງບໍ່ມີການຈອງ
                                </p>

                            </div>

                        @endforelse


                        @if($latestBookings->count() > 0)

                            <div class="text-center mt-3">

                                <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary btn-sm">
                                    ເບິ່ງການຈອງທັງໝົດ
                                </a>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Booking Modal -->

    <div class="modal fade" id="bookingModal" tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="bi bi-calendar-event"></i>
                        ລາຍລະອຽດການຈອງ
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <strong>ຫ້ອງ:</strong>
                            <span id="modalRoom">-</span>
                        </div>

                        <div class="col-md-6">
                            <strong>ຫົວຂໍ້:</strong>
                            <span id="modalTitle">-</span>
                        </div>

                        <div class="col-md-6">
                            <strong>ຜູ້ຈອງ:</strong>
                            <span id="modalBooker">-</span>
                        </div>

                        <div class="col-md-6">
                            <strong>ພະແນກ:</strong>
                            <span id="modalDepartment">-</span>
                        </div>

                        <div class="col-md-6">
                            <strong>ເວລາ:</strong>
                            <span id="modalTime">-</span>
                        </div>

                        <div class="col-md-6">
                            <strong>ຜູ້ເຂົ້າຮ່ວມ:</strong>
                            <span id="modalAttendees">-</span>
                        </div>

                        <div class="col-md-6">
                            <strong>ນໍ້າດື່ມ:</strong>
                            <span id="modalWater">-</span>
                        </div>

                        <div class="col-md-6">
                            <strong>ສະຖານະ:</strong>
                            <span id="modalStatus">-</span>
                        </div>

                        <div class="col-12">
                            <strong>ໝາຍເຫດ:</strong>
                            <span id="modalNote">-</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const calendarElement =
                document.getElementById('dashboardCalendar');


            const calendar =
                new FullCalendar.Calendar(calendarElement, {

                    initialView: 'timeGridWeek',

                    locale: 'th',

                    height: 'auto',

                    slotMinTime: '07:00:00',

                    slotMaxTime: '22:00:00',

                    nowIndicator: true,

                    allDaySlot: false,

                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridDay,timeGridWeek,dayGridMonth'
                    },

                    buttonText: {
                        today: 'today',
                        day: 'day',
                        week: 'week',
                        month: 'month'
                    },

                    events: '{{ route("calendar.events") }}',


                    eventClick: function (info) {

                        const event = info.event;

                        const props = event.extendedProps;


                        const parts =
                            event.title.split(' - ');


                        document.getElementById('modalRoom').textContent =
                            parts[0] || '-';


                        document.getElementById('modalTitle').textContent =
                            parts.slice(1).join(' - ') || '-';


                        document.getElementById('modalBooker').textContent =
                            props.booker_name || '-';


                        document.getElementById('modalDepartment').textContent =
                            props.department || '-';


                        document.getElementById('modalAttendees').textContent =
                            props.attendees
                                ? props.attendees + ' ຄົນ'
                                : '-';


                        document.getElementById('modalWater').textContent =
                            props.drinking_water || '-';


                        document.getElementById('modalStatus').textContent =
                            props.status || '-';


                        document.getElementById('modalNote').textContent =
                            props.note || '-';


                        if (event.start && event.end) {

                            const start =
                                event.start.toLocaleTimeString(
                                    'th-TH',
                                    {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }
                                );

                            const end =
                                event.end.toLocaleTimeString(
                                    'th-TH',
                                    {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }
                                );

                            document.getElementById('modalTime').textContent =
                                start + ' - ' + end;

                        }


                        const modal =
                            new bootstrap.Modal(
                                document.getElementById('bookingModal')
                            );

                        modal.show();

                    }

                });


            calendar.render();

        });

    </script>


</body>

</html>