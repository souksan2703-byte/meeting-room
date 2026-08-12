<!DOCTYPE html>
<html lang="lo">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meeting Room Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>

    <style>
        body {
            background-color: #f5f7fb;
        }

        .dashboard-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .dashboard-card h2 {
            font-size: 32px;
            margin-top: 10px;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .icon-blue {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .icon-red {
            background: #ffe8e8;
            color: #dc3545;
        }

        .icon-green {
            background: #e8f7ee;
            color: #198754;
        }

        .icon-purple {
            background: #f0e8ff;
            color: #6f42c1;
        }

        .calendar-box {
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafbfc;
            border-radius: 12px;
        }

        .empty-booking {
            text-align: center;
            padding: 30px;
            color: #888;
        }

        /* FullCalendar */
        #calendar {
            width: 100%;
        }

        .fc {
            font-family: inherit;
            font-size: 13px;
        }

        .fc .fc-toolbar {
            margin-bottom: 12px;
        }

        .fc .fc-toolbar-title {
            font-size: 20px;
            font-weight: 600;
        }

        .fc .fc-button {
            border: none !important;
            background: #f1f3f5 !important;
            color: #333 !important;
            box-shadow: none !important;
            text-transform: none;
        }

        .fc .fc-button:hover {
            background: #e9ecef !important;
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: #dc3545 !important;
            color: white !important;
        }

        .fc .fc-timegrid-slot {
            height: 55px;
        }

        .fc .fc-timegrid-axis-cushion {
            font-size: 12px;
            color: #666;
        }

        .fc .fc-col-header-cell-cushion {
            font-weight: 600;
            color: #333;
            padding: 10px 4px;
        }

        .fc .fc-event {
            border-radius: 4px;
            padding: 4px 6px;
            border-width: 1px;
            font-size: 12px;
            cursor: pointer;
        }

        .fc .fc-event-title {
            font-weight: 600;
        }

        .fc .fc-timegrid-event {
            min-height: 35px;
        }

        .fc .fc-now-indicator-line {
            border-width: 2px;
        }
    </style>
</head>

<body>

    <div class="container-fluid py-4 px-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    Dashboard
                </h2>

                <p class="text-muted mb-0">
                    ໜ້າຈັດການລະບົບຈອງຫ້ອງປະຊຸມ
                </p>
            </div>

            <div>
                <span class="badge bg-primary px-3 py-2">
                    <i class="bi bi-speedometer2"></i>
                    Admin
                </span>
            </div>

        </div>


        <!-- ========================= -->
        <!-- Statistics Cards -->
        <!-- ========================= -->

        <div class="row g-4 mb-4">

            <!-- 1. ห้องทั้งหมด -->
            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h6 class="text-muted mb-2">
                                    ຫ້ອງປະຊຸມ
                                </h6>

                                <h2 class="fw-bold mb-1">
                                    {{ $totalRooms }}
                                </h2>

                                <small class="text-muted">
                                    ຫ້ອງທັງໝົດ
                                </small>

                            </div>

                            <div class="card-icon icon-blue">

                                <i class="bi bi-building"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- 2. จองแล้ว -->
            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h6 class="text-muted mb-2">
                                    ຈອງແລ້ວ
                                </h6>

                                <h2 class="fw-bold text-warning mb-1">
                                    {{ $bookedRoomsToday }}
                                </h2>

                                <small class="text-muted">
                                    ຫ້ອງທີ່ຈອງແລ້ວ
                                </small>

                            </div>

                            <div class="card-icon" style="background:#fff3cd; color:#ffc107;">

                                <i class="bi bi-calendar-check"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- 3. กำลังใช้งาน -->
            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h6 class="text-muted mb-2">
                                    ການໃຊ້ງານ
                                </h6>

                                <h2 class="fw-bold text-success mb-1">
                                    {{ $usedRoomsToday }}
                                </h2>

                                <small class="text-muted">
                                    ຫ້ອງກຳລັງໃຊ້ງານ
                                </small>

                            </div>

                            <div class="card-icon icon-green">

                                <i class="bi bi-door-open"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- 4. ห้องว่าง -->
            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h6 class="text-muted mb-2">
                                    ຫ້ອງວ່າງ
                                </h6>

                                <h2 class="fw-bold text-primary mb-1">
                                    {{ $availableRooms }}
                                </h2>

                                <small class="text-muted">
                                    ຫ້ອງທີ່ຍັງວ່າງ
                                </small>

                            </div>

                            <div class="card-icon icon-blue">

                                <i class="bi bi-door-closed"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="row g-4">

            <!-- Calendar -->
            <div class="col-lg-7">

                <div class="card dashboard-card h-100">

                    <div class="card-header bg-white border-0 pt-4 px-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-calendar3 text-primary"></i>
                                Calendar
                            </h5>

                            <span class="text-muted small">
                                ຕາຕະລາງການຈອງ
                            </span>

                        </div>

                    </div>

                    <div class="card-body p-4">

                        <div id="calendar" style="min-height: 550px;"></div>

                    </div>

                </div>

            </div>


            <!-- Latest Bookings -->
            <div class="col-lg-5">

                <div class="card dashboard-card h-100">

                    <div class="card-header bg-white border-0 pt-4 px-4">

                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-clock-history text-primary"></i>
                            ລາຍການຈອງລ່າສຸດ
                        </h5>

                    </div>

                    <div class="card-body px-4">

                        @if($latestBookings->count() > 0)

                            @foreach($latestBookings as $booking)

                                <div class="border-bottom pb-3 mb-3">

                                    <div class="d-flex justify-content-between">

                                        <strong>
                                            {{ $booking->room->name ?? 'ບໍ່ພົບຫ້ອງ' }}
                                        </strong>

                                        @php
                                            $bookingDate = $booking->booking_date;
                                            $startTime = $booking->start_time;
                                            $endTime = $booking->end_time;

                                            $now = \Carbon\Carbon::now();

                                            $start = \Carbon\Carbon::parse(
                                                $bookingDate . ' ' . $startTime
                                            );

                                            $end = \Carbon\Carbon::parse(
                                                $bookingDate . ' ' . $endTime
                                            );
                                        @endphp

                                        @if($now->lt($start))

                                            <span class="badge bg-warning text-dark">
                                                ຈອງແລ້ວ
                                            </span>

                                        @elseif($now->gte($start) && $now->lt($end))

                                            <span class="badge bg-success">
                                                ກຳລັງໃຊ້ງານ
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                ສິ້ນສຸດເວລາ
                                            </span>

                                        @endif

                                    </div>

                                    <div class="mt-2">
                                        <i class="bi bi-person"></i>
                                        {{ $booking->booker_name }}
                                    </div>

                                    <small class="text-muted">

                                        <i class="bi bi-calendar-event"></i>
                                        {{ $booking->booking_date }}

                                        &nbsp;

                                        <i class="bi bi-clock"></i>
                                        {{ $booking->start_time }}
                                        -
                                        {{ $booking->end_time }}

                                    </small>

                                </div>

                            @endforeach

                        @else

                            <div class="empty-booking">

                                <i class="bi bi-calendar-x fs-1"></i>

                                <h6 class="mt-3">
                                    ຍັງບໍ່ມີການຈອງ
                                </h6>

                                <p class="small mb-0">
                                    ເມື່ອມີການຈອງ
                                    ຂໍ້ມູນຈະສະແດງຢູ່ບ່ອນນີ້
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const calendarEl = document.getElementById('calendar');

            if (!calendarEl) {
                return;
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {

                // =========================
                // รูปแบบ Calendar
                // =========================
                initialView: 'timeGridWeek',

                height: 550,

                firstDay: 0,

                allDaySlot: false,

                nowIndicator: true,

                slotMinTime: '07:00:00',

                slotMaxTime: '18:00:00',

                slotDuration: '01:00:00',

                scrollTime: '07:00:00',

                expandRows: true,

                // =========================
                // Header
                // =========================
                headerToolbar: {
                    left: 'prev,today,next',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },

                // =========================
                // รูปแบบเวลา
                // =========================
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                },

                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                },

                // =========================
                // ข้อมูลจาก Database
                // =========================
                events: "{{ route('calendar.events') }}",

                // =========================
                // เมื่อคลิกการจอง
                // =========================
                eventClick: function (info) {

                    const start = info.event.start
                        ? info.event.start.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        })
                        : '';

                    const end = info.event.end
                        ? info.event.end.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        })
                        : '';

                    alert(
                        'ລາຍການຈອງ\n\n' +
                        info.event.title +
                        '\nເວລາ: ' +
                        start +
                        ' - ' +
                        end
                    );
                }

            });

            calendar.render();

        });

    </script>

</body>

</html>