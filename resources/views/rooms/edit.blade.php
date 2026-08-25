<!DOCTYPE html>
<html lang="lo">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ແກ້ໄຂຫ້ອງປະຊຸມ</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            border-color: #007bff;
        }

        .error {
            margin-top: 5px;
            color: #dc3545;
            font-size: 14px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-save {
            background: #007bff;
            color: white;
        }

        .btn-save:hover {
            background: #0056b3;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #545b62;
        }
    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>ແກ້ໄຂຫ້ອງປະຊຸມ</h1>

        <form action="{{ route('rooms.update', $room->id) }}" method="POST">

            @csrf

            @method('PUT')

            <div class="form-group">

                <label for="name">
                    ຊື່ຫ້ອງປະຊຸມ
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $room->name) }}"
                    placeholder="ເຊັ່ນ ຫ້ອງປະຊຸມ A"
                    required
                >

                @error('name')

                    <div class="error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <div class="form-group">

                <label for="capacity">
                    ความจุ
                </label>

                <input
                    type="number"
                    id="capacity"
                    name="capacity"
                    value="{{ old('capacity', $room->capacity) }}"
                    placeholder="เช่น 20"
                    min="1"
                    required
                >

                @error('capacity')

                    <div class="error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <div class="form-group">

                <label for="location">
                    ສະຖານທີ່ / ທີ່ຕັ້ງ
                </label>

                <input
                    type="text"
                    id="location"
                    name="location"
                    value="{{ old('location', $room->location) }}"
                    placeholder="เช่น อาคาร A ชั้น 2"
                    required
                >

                @error('location')

                    <div class="error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <div class="buttons">

                <button
                    type="submit"
                    class="btn btn-save"
                >
                    ບັນທຶກການແກ້ໄຂ
                </button>


                <a
                    href="{{ route('rooms.index') }}"
                    class="btn btn-back"
                >
                    ยกเลิก
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>