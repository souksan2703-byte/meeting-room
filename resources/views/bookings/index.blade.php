<!DOCTYPE html>
<html lang="lo">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ລາຍການຈອງ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container-fluid py-4 px-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    ລາຍການຈອງຫ້ອງປະຊຸມ
                </h2>

                <p class="text-muted">
                    ຈັດການການຈອງຫ້ອງປະຊຸມ
                </p>

            </div>

            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                + ເພີ່ມການຈອງ
            </a>

        </div>

        @if(session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>ຫ້ອງ</th>

                                <th>ຜູ້ຈອງ</th>

                                <th>ຫົວຂໍ້</th>

                                <th>ວັນທີ</th>

                                <th>ເວລາ</th>

                                <th>ຜູ້ເຂົ້າຮ່ວມ</th>

                                <th>ນໍ້າດື່ມ</th>

                                <th>ສະຖານະ</th>

                                <th>ຈັດການ</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($bookings as $booking)

                                <tr>

                                    <td>
                                        {{ $booking->room->name }}
                                    </td>

                                    <td>
                                        {{ $booking->booker_name }}
                                    </td>

                                    <td>
                                        {{ $booking->meeting_title }}
                                    </td>

                                    <td>
                                        {{ $booking->booking_date }}
                                    </td>

                                    <td>
                                        {{ substr($booking->start_time, 0, 5) }}
                                        -
                                        {{ substr($booking->end_time, 0, 5) }}
                                    </td>

                                    <td>
                                        {{ $booking->attendees }}
                                    </td>

                                    {{-- น้ำดื่ม --}}
                                    <td>
                                        {{ $booking->drinking_water ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{ $booking->status }}
                                        </span>
                                    </td>

                                    <td>

                                        <a href="{{ route('bookings.edit', $booking->id) }}"
                                            class="btn btn-sm btn-outline-primary mb-1">
                                            ແກ້ໄຂ
                                        </a>


                                        @if($booking->status === 'Pending')

                                            <form action="{{ route('bookings.approve', $booking->id) }}" method="POST"
                                                class="d-inline">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-success mb-1"
                                                    onclick="return confirm('ຕ້ອງການອະນຸມັດການຈອງນີ້ບໍ?')">
                                                    <i class="bi bi-check-circle"></i>
                                                    ອະນຸມັດ
                                                </button>

                                            </form>


                                            <form action="{{ route('bookings.reject', $booking->id) }}" method="POST"
                                                class="d-inline">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-warning mb-1"
                                                    onclick="return confirm('ຕ້ອງການປະຕິເສດການຈອງນີ້ບໍ?')">
                                                    <i class="bi bi-x-circle"></i>
                                                    ປະຕິເສດ
                                                </button>

                                            </form>

                                        @endif


                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('ຕ້ອງການລຶບການຈອງນີ້ບໍ?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger mb-1">
                                                ລຶບ
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center py-5 text-muted">
                                        ຍັງບໍ່ມີລາຍການຈອງ
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>

</html>