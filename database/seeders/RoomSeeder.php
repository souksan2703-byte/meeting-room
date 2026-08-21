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
                'name' => 'Boardroom Alpha',
                'floor' => 1,
                'location' => 'HQ Campus, Building C, Floor 4. Access via Main Atrium elevators.',
                'capacity' => 12,
                'equipment' => ['Video Conference', '85" Display', 'Whiteboard', 'High-Speed WiFi'],
                'policies' => 'Maximum 4 hour booking duration. No outside catering without prior approval.',
                'it_support' => 'For AV issues, contact IT Helpdesk at ext 4432 or use the room phone.',
                'requires_approval' => true,
            ],
            [
                'name' => 'Meeting Room Beta',
                'floor' => 1,
                'location' => 'Floor 1',
                'capacity' => 6,
                'equipment' => ['Video Conference', 'Whiteboard'],
                'requires_approval' => false,
            ],
            [
                'name' => 'Huddle Gamma',
                'floor' => 2,
                'location' => 'Floor 2',
                'capacity' => 4,
                'equipment' => ['Whiteboard', 'High-Speed WiFi'],
                'requires_approval' => false,
            ],
            [
                'name' => 'Creative Lab',
                'floor' => 2,
                'location' => 'Floor 2',
                'capacity' => 8,
                'equipment' => ['Whiteboard', 'High-Speed WiFi'],
                'requires_approval' => false,
            ],
            [
                'name' => 'Executive Suite',
                'floor' => 3,
                'location' => 'Floor 3',
                'capacity' => 10,
                'equipment' => ['Video Conference', '85" Display', 'High-Speed WiFi'],
                'requires_approval' => true,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}