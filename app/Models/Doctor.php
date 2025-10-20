<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','specialty','email','phone','bio','photo_path','active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function services()
    {
        // defaults to doctor_service pivot (OK)
        return $this->belongsToMany(Service::class)->withTimestamps();
    }

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}