<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'status',
        'attendees',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'attendees' => 'array',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time');
    }

    public function scopePast($query)
    {
        return $query->where('end_time', '<', now())
            ->orderByDesc('start_time');
    }

    public function attendeesCount(): int
    {
        // รองรับข้อมูลเก่า (array รายชื่ออีเมล) และข้อมูลใหม่ (ตัวเลขจำนวนคนล้วนๆ)
        if (is_array($this->attendees)) {
            return count($this->attendees);
        }

        return (int) ($this->attendees ?? 0);
    }
}