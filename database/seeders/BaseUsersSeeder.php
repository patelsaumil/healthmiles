<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BaseUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@healthmiles.local'],
            ['name' => 'Admin User', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'doctor@healthmiles.local'],
            ['name' => 'Dr. Demo', 'password' => Hash::make('password'), 'role' => 'doctor']
        );

        User::updateOrCreate(
            ['email' => 'patient@healthmiles.local'],
            ['name' => 'Demo Patient', 'password' => Hash::make('password'), 'role' => 'patient']
        );
    }
}
