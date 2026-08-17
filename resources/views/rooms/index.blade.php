<!DOCTYPE html>
<html lang="lo">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meeting Rooms</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fb;
        }

        .room-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }
    </style>

</head>

<body>

    @include('partials.navbar')

    <div class="container-fluid py-4 px-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    ຫ້ອງປະຊຸມ
                </h2>

                <p class="text-muted mb-0">
                    ຈັດການຫ້ອງປະຊຸມ
                </p>

            </div>

            <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                ເພີ່ມຫ້ອງ
            </a>

        </div>


        <!-- Success Message -->
        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        <!-- Rooms -->
        <div class="row g-4">

            @forelse($rooms as $room)

                <div class="col-lg-4 col-md-6">

                    <div class="card room-card h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start">

                                <div>

                                    <h5 class="fw-bold mb-2">
                                        {{ $room->name }}
                                    </h5>

                                    <div class="text-muted">

                                        <i class="bi bi-people"></i>

                                        ຄວາມຈຸ
                                        {{ $room->capacity }}
                                        ຄົນ

                                    </div>

                                </div>

                                <div class="fs-3 text-primary">

                                    <i class="bi bi-building"></i>

                                </div>

                            </div>


                            <div class="mt-4 d-flex gap-2">

                                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-outline-primary btn-sm">

                                    <i class="bi bi-pencil"></i>

                                    ແກ້ໄຂ

                                </a>


                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST"
                                    onsubmit="return confirm('ຕ້ອງການລຶບຫ້ອງນີ້ແມ່ນບໍ?')">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger btn-sm">

                                        <i class="bi bi-trash"></i>

                                        ລຶບ

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="card room-card">

                        <div class="card-body text-center py-5">

                            <i class="bi bi-building fs-1 text-muted"></i>

                            <h5 class="mt-3">
                                ຍັງບໍ່ມີຫ້ອງປະຊຸມ
                            </h5>

                            <p class="text-muted">
                                ກົດປຸ່ມ "ເພີ່ມຫ້ອງ" ເພື່ອເພີ່ມຫ້ອງແຫ່ງທຳອິດ
                            </p>

                        </div>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>