<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed columns that exist in your DB (name, description, status)
        $data = [
            ['name' => 'General Consultation', 'description' => 'Routine checkup', 'status' => 'active'],
            ['name' => 'Cardiology Consultation', 'description' => 'Heart specialist', 'status' => 'active'],
            ['name' => 'Dermatology Visit', 'description' => 'Skin consultation', 'status' => 'active'],
            ['name' => 'Flu Shot', 'description' => 'Seasonal influenza vaccine', 'status' => 'inactive'],
        ];

        foreach ($data as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
