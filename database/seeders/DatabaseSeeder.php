<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
            BaseUsersSeeder::class,  
            ServiceSeeder::class,    
            DoctorSeeder::class,     
            TimeSlotSeeder::class,   
        ]);
    }
}
