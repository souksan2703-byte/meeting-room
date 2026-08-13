<!DOCTYPE html>
<html lang="lo">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>ປະຕິທິນການຈອງ</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f7fb;
        }

        .calendar-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        #calendar {
            min-height: 700px;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .detail-value {
            font-size: 16px;
            color: #212529;
        }

    </style>

</head>


<body>

<div class="container-fluid py-4 px-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                <i class="bi bi-calendar3"></i>
                ປະຕິທິນການຈອງ
            </h2>

            <p class="text-muted mb-0">
                ເບິ່ງລາຍການຈອງຫ້ອງປະຊຸມຕາມວັນທີ
            </p>

        </div>


        <a
            href="{{ route('bookings.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-lg"></i>
            ເພີ່ມການຈອງ
        </a>

    </div>


    <div class="card calendar-card">

        <div class="card-body p-4">

            <div id="calendar"></div>

        </div>

    </div>

</div>


<!-- Booking Detail Modal -->

<div
    class="modal fade"
    id="bookingModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-calendar-event"></i>

                    ລາຍລະອຽດການຈອງ

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="row g-4">

                    <!-- ห้อง -->

                    <div class="col-md-6">

                        <div class="detail-label">
                            ຫ້ອງປະຊຸມ
                        </div>

                        <div
                            class="detail-value"
                            id="modalRoom"
                        >
                            -
                        </div>

                    </div>


                    <!-- หัวข้อ -->

                    <div class="col-md-6">

                        <div class="detail-label">
                            ຫົວຂໍ້ການປະຊຸມ
                        </div>

                        <div
                            class="detail-value"
                            id="modalMeetingTitle"
                        >
                            -
                        </div>

                    </div>


                    <!-- ผู้จอง -->

                    <div class="col-md-6">

                        <div class="detail-label">
                            ຜູ້ຈອງ
                        </div>

                        <div
                            class="detail-value"
                            id="modalBooker"
                        >
                            -
                        </div>

                    </div>


                    <!-- หน่วยงาน -->

                    <div class="col-md-6">

                        <div class="detail-label">
                            ພະແນກ / ໜ່ວຍງານ
                        </div>

                        <div
                            class="detail-value"
                            id="modalDepartment"
                        >
                            -
                        </div>

                    </div>


                    <!-- วันที่ -->

                    <div class="col-md-6">

                        <div class="detail-label">
                            ວັນທີ
                        </div>

                        <div
                            class="detail-value"
                            id="modalDate"
                        >
                            -
                        </div>

                    </div>


                    <!-- เวลา -->

                    <div class="col-md-6">

                        <div class="detail-label">
                            ເວລາ
                        </div>

                        <div
                            class="detail-value"
                            id="modalTime"
                        >
                            -
                        </div>

                    </div>


                    <!-- จำนวนคน -->

                    <div class="col-md-6">

                        <div class="detail-label">
                            ຈຳນວນຜູ້ເຂົ້າຮ່ວມ
                        </div>

                        <div
                            class="detail-value"
                            id="modalAttendees"
                        >
                            -
                        </div>

                    </div>


                    <!-- น้ำดื่ม -->

                    <div class="col-md-6">

                        <div class="detail-label">
                            ນໍ້າດື່ມ
                        </div>

                        <div
                            class="detail-value"
                            id="modalDrinkingWater"
                        >
                            -
                        </div>

                    </div>


                    <!-- สถานะ -->

                    <div class="col-md-6">

                        <div class="detail-label">
                            ສະຖານະ
                        </div>

                        <div>
                            <span
                                class="badge bg-warning text-dark"
                                id="modalStatus"
                            >
                                -
                            </span>
                        </div>

                    </div>


                    <!-- หมายเหตุ -->

                    <div class="col-12">

                        <div class="detail-label">
                            ໝາຍເຫດ
                        </div>

                        <div
                            class="detail-value"
                            id="modalNote"
                        >
                            -
                        </div>

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    ປິດ
                </button>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const bookingModalElement =
        document.getElementById('bookingModal');

    const bookingModal =
        new bootstrap.Modal(bookingModalElement);


    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        locale: 'th',

        height: 'auto',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        buttonText: {
            today: 'วันนี้',
            month: 'เดือน',
            week: 'สัปดาห์',
            day: 'วัน'
        },

        events: '{{ route("calendar.events") }}',


        eventClick: function(info) {

            const event = info.event;

            const props = event.extendedProps;


            // ห้องประชุม
            const roomName =
                event.title.split(' - ')[0];

            document.getElementById('modalRoom').textContent =
                roomName;


            // หัวข้อ
            const meetingTitle =
                event.title.split(' - ').slice(1).join(' - ');

            document.getElementById('modalMeetingTitle').textContent =
                meetingTitle || '-';


            // ผู้จอง
            document.getElementById('modalBooker').textContent =
                props.booker_name || '-';


            // หน่วยงาน
            document.getElementById('modalDepartment').textContent =
                props.department || '-';


            // วันที่
            if (event.start) {

                document.getElementById('modalDate').textContent =
                    event.start.toLocaleDateString('th-TH');

            } else {

                document.getElementById('modalDate').textContent =
                    '-';

            }


            // เวลา
            if (event.start && event.end) {

                const startTime =
                    event.start.toLocaleTimeString(
                        'th-TH',
                        {
                            hour: '2-digit',
                            minute: '2-digit'
                        }
                    );

                const endTime =
                    event.end.toLocaleTimeString(
                        'th-TH',
                        {
                            hour: '2-digit',
                            minute: '2-digit'
                        }
                    );

                document.getElementById('modalTime').textContent =
                    startTime + ' - ' + endTime;

            } else {

                document.getElementById('modalTime').textContent =
                    '-';

            }


            // จำนวนผู้เข้าร่วม
            document.getElementById('modalAttendees').textContent =
                props.attendees
                    ? props.attendees + ' ຄົນ'
                    : '-';


            // น้ำดื่ม
            document.getElementById('modalDrinkingWater').textContent =
                props.drinking_water || '-';


            // สถานะ
            const statusElement =
                document.getElementById('modalStatus');

            statusElement.textContent =
                props.status || '-';


            // เปลี่ยนสีสถานะ
            statusElement.className =
                'badge';


            if (props.status === 'Approved') {

                statusElement.classList.add(
                    'bg-success'
                );

            } else if (props.status === 'Rejected') {

                statusElement.classList.add(
                    'bg-danger'
                );

            } else {

                statusElement.classList.add(
                    'bg-warning',
                    'text-dark'
                );

            }


            // หมายเหตุ
            document.getElementById('modalNote').textContent =
                props.note || '-';


            // เปิด Modal
            bookingModal.show();

        }

    });


    calendar.render();

});

</script>

</body>

</html>