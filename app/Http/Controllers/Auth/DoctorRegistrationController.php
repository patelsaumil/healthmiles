<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;

class DoctorRegistrationController extends Controller
{
    // GET /register/doctor
    public function create()
    {
        // View path matches: resources/views/patient/auth/doctor-register.blade.php
        return view('patient.auth.doctor-register');
    }

    // POST /register/doctor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'confirmed', Password::defaults()],
            'phone'      => ['nullable', 'string', 'max:40'],
            'specialty'  => ['nullable', 'string', 'max:120'],
            'bio'        => ['nullable', 'string', 'max:1000'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'doctor',
            ]);

            Doctor::create([
                'user_id'   => $user->id,
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'] ?? null,
                'specialty' => $validated['specialty'] ?? null,
                'bio'       => $validated['bio'] ?? null,
                'photo_path'=> null,
                'active'    => 1,
            ]);

            return $user;
        });

        event(new Registered($user));

        return redirect()->route('login')
            ->with('status', 'Doctor account created! Please verify your email (if required) and log in.');
    }
}
