<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name'=>'General Consultation','description'=>'Routine checkup','duration_minutes'=>30,'price'=>30],
            ['name'=>'Cardiology Consultation','description'=>'Heart specialist','duration_minutes'=>45,'price'=>60],
            ['name'=>'Dermatology Visit','description'=>'Skin consultation','duration_minutes'=>30,'price'=>40],
        ];
        foreach ($data as $s) {
            Service::updateOrCreate(['name'=>$s['name']], $s + ['active'=>true]);
        }
    }
}
