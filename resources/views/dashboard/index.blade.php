<!DOCTYPE html>
<html lang="lo">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>

        body {
            background-color: #f5f7fb;
        }

        .dashboard-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .icon-box {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .booking-row {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .booking-row:last-child {
            border-bottom: none;
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
                ພາບລວມລະບົບຈອງຫ້ອງປະຊຸມ
            </p>

        </div>

    </div>


    <!-- Statistics -->

    <div class="row g-4 mb-4">


        <!-- Rooms -->

        <div class="col-xl-3 col-md-6">

            <div class="card dashboard-card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                ຫ້ອງປະຊຸມທັງໝົດ
                            </p>

                            <h2 class="fw-bold mb-0">
                                {{ $totalRooms }}
                            </h2>

                        </div>

                        <div class="icon-box bg-primary bg-opacity-10 text-primary">

                            <i class="bi bi-building"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Today bookings -->

        <div class="col-xl-3 col-md-6">

            <div class="card dashboard-card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                ການຈອງມື້ນີ້
                            </p>

                            <h2 class="fw-bold mb-0">
                                {{ $todayBookings }}
                            </h2>

                        </div>

                        <div class="icon-box bg-success bg-opacity-10 text-success">

                            <i class="bi bi-calendar-check"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- All bookings -->

        <div class="col-xl-3 col-md-6">

            <div class="card dashboard-card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                ການຈອງທັງໝົດ
                            </p>

                            <h2 class="fw-bold mb-0">
                                {{ $totalBookings }}
                            </h2>

                        </div>

                        <div class="icon-box bg-info bg-opacity-10 text-info">

                            <i class="bi bi-journal-text"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Pending -->

        <div class="col-xl-3 col-md-6">

            <div class="card dashboard-card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                ລໍຖ້າອະນຸມັດ
                            </p>

                            <h2 class="fw-bold mb-0">
                                {{ $pendingBookings }}
                            </h2>

                        </div>

                        <div class="icon-box bg-warning bg-opacity-10 text-warning">

                            <i class="bi bi-clock-history"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Latest Bookings -->

    <div class="card dashboard-card">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h5 class="fw-bold mb-1">
                        ການຈອງຫຼ້າສຸດ
                    </h5>

                    <p class="text-muted mb-0">
                        ລາຍການຈອງ 5 ລາຍການຫຼ້າສຸດ
                    </p>

                </div>

                <a
                    href="{{ route('bookings.index') }}"
                    class="btn btn-outline-primary btn-sm"
                >
                    ເບິ່ງທັງໝົດ
                </a>

            </div>


            @forelse($latestBookings as $booking)

                <div class="booking-row">

                    <div class="row align-items-center">

                        <!-- Room -->

                        <div class="col-md-3">

                            <div class="fw-bold">

                                <i class="bi bi-building text-primary"></i>

                                {{ $booking->room->name }}

                            </div>

                            <small class="text-muted">

                                {{ $booking->meeting_title }}

                            </small>

                        </div>


                        <!-- Booker -->

                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                ຜູ້ຈອງ
                            </small>

                            {{ $booking->booker_name }}

                        </div>


                        <!-- Date -->

                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                ວັນທີ
                            </small>

                            {{ $booking->booking_date }}

                        </div>


                        <!-- Time -->

                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                ເວລາ
                            </small>

                            {{ substr($booking->start_time, 0, 5) }}
                            -
                            {{ substr($booking->end_time, 0, 5) }}

                        </div>


                        <!-- Status -->

                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                ສະຖານະ
                            </small>

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


                        <!-- Action -->

                        <div class="col-md-1 text-end">

                            <a
                                href="{{ route('bookings.edit', $booking->id) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                <i class="bi bi-pencil"></i>
                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center py-5">

                    <i class="bi bi-calendar-x fs-1 text-muted"></i>

                    <p class="text-muted mt-3 mb-0">
                        ຍັງບໍ່ມີລາຍການຈອງ
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

</body>

</html>