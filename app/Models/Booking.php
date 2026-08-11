<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Room;    

class Booking extends Model
{
    protected $fillable = [
        'room_id',
        'booker_name',
        'department',
        'meeting_title',
        'booking_date',
        'start_time',
        'end_time',
        'attendees',
        'note',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}