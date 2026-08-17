<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class Room extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'location',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}