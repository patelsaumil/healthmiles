<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'name',
        'description',
        'status',   
    ];

    protected $casts = [
        
    ];

    

    public function doctors()
    {
        
        return $this->belongsToMany(Doctor::class, 'doctor_service')->withTimestamps();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }
}
