<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $dr1 = Doctor::updateOrCreate(
            ['email' => 'sarah.wilson@healthmiles.local'],
            ['name' => 'Dr. Sarah Wilson','specialty'=>'Cardiology','phone'=>'+1 555-0101','active'=>true]
        );

        $dr2 = Doctor::updateOrCreate(
            ['email' => 'michael.chen@healthmiles.local'],
            ['name' => 'Dr. Michael Chen','specialty'=>'General Medicine','phone'=>'+1 555-0102','active'=>true]
        );

        // attach services
        $cardio   = Service::where('name','Cardiology Consultation')->first();
        $general  = Service::where('name','General Consultation')->first();

        if ($cardio)  { $dr1->services()->syncWithoutDetaching([$cardio->id]); }
        if ($general) { $dr2->services()->syncWithoutDetaching([$general->id]); }
    }
}
