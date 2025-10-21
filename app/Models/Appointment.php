<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'time_slot_id',
        'status',
        'notes',
    ];

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(\App\Models\User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(\App\Models\Doctor::class);
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(\App\Models\TimeSlot::class);
    }
}
