<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'slot_date',
        'start_time',
        'end_time',
        'is_booked',
    ];

    protected $casts = [
        'slot_date'  => 'date',
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
        'is_booked'  => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_booked', false);
    }
}
