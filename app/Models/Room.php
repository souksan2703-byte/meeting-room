<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'floor',
        'location',
        'capacity',
        'image_path',
        'equipment',
        'policies',
        'it_support',
        'requires_approval',
    ];

    protected $casts = [
        'equipment' => 'array',
        'requires_approval' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Bookings for a given date (Y-m-d string), excluding cancelled ones.
     */
    public function bookingsForDate(string $date)
    {
        return $this->bookings()
            ->whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();
    }
}