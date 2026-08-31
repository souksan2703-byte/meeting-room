<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'ຫ້ອງປະຊຸມໃຫຍ່',
                'floor' => 1,
                'location' => 'HQ Campus, Building C, Floor 4. Access via Main Atrium elevators.',
                'capacity' => 25,
                'equipment' => ['Video Conference', '85" Display', 'Whiteboard', 'High-Speed WiFi'],
                'policies' => 'Maximum 4 hour booking duration. No outside catering without prior approval.',
                'it_support' => 'For AV issues, contact IT Helpdesk at ext 4432 or use the room phone.',
                'requires_approval' => true,
            ],
            [
                'name' => 'ຫ້ອງປະຊຸມນ້ອຍ',
                'floor' => 1,
                'location' => 'Floor 1',
                'capacity' => 6,
                'equipment' => ['Video Conference', 'Whiteboard'],
                'requires_approval' => false,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}