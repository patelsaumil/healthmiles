<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- Convenience role helpers ---
    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isDoctor(): bool { return $this->role === 'doctor'; }
    public function isPatient(): bool { return $this->role === 'patient'; }

    // Patient's appointments
    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class, 'patient_id');
    }

    // <-- Add this: link a User to their Doctor profile
    public function doctor()
    {
        return $this->hasOne(\App\Models\Doctor::class, 'user_id');
    }
}
