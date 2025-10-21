<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// --- Admin controllers
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController; // aliased

// --- Doctor controllers
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\AvailabilityController as DoctorAvailabilityController;

Route::get('/', fn () => view('welcome'));

// After login, send users to their role dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role ?? 'patient') {
        'admin'   => redirect()->route('admin.dashboard'),
        'doctor'  => redirect()->route('doctor.dashboard'),
        'patient' => redirect()->route('patient.dashboard'),
        default   => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ------------------------ Common Profile Routes ------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ------------------------ Admin ------------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        // Admin dashboard
        Route::view('/', 'dash.admin')->name('dashboard');

        // Optional: alias used by some views (safe redirect to the real dashboard)
        Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->name('dash.admin');

        // Doctors CRUD
        Route::resource('doctors', DoctorController::class);

        // Services CRUD
        Route::resource('services', ServiceController::class);

        // Time slots (nested under doctor, shallow for edit/update/destroy)
        Route::resource('doctors.timeslots', TimeSlotController::class)->shallow();

       

        // Appointments (view + delete only)
        Route::resource('appointments', AdminAppointmentController::class)->only(['index', 'destroy']);
    });

// ------------------------ Doctor ------------------------
Route::middleware(['auth', 'role:doctor'])
    ->prefix('doctor')
    ->as('doctor.')
    ->group(function () {

        // Doctor dashboard
        Route::view('/', 'doctor.dashboard')->name('dashboard');

        // Optional: alias used by some views (safe redirect to the real dashboard)
        Route::get('/dashboard', fn () => redirect()->route('doctor.dashboard'))->name('dash.doctor');

        // Doctor → My Appointments (view + delete only)
        Route::get('appointments', [DoctorAppointmentController::class, 'index'])
            ->name('appointments.index');
        Route::delete('appointments/{appointment}', [DoctorAppointmentController::class, 'destroy'])
            ->name('appointments.destroy');

        // Doctor → Availability (TimeSlots) CRUD (for their own doctor_id)
        Route::get('availability', [DoctorAvailabilityController::class, 'index'])->name('availability.index');
        Route::get('availability/create', [DoctorAvailabilityController::class, 'create'])->name('availability.create');
        Route::post('availability', [DoctorAvailabilityController::class, 'store'])->name('availability.store');
        Route::get('availability/{timeslot}/edit', [DoctorAvailabilityController::class, 'edit'])->name('availability.edit');
        Route::put('availability/{timeslot}', [DoctorAvailabilityController::class, 'update'])->name('availability.update');
        Route::delete('availability/{timeslot}', [DoctorAvailabilityController::class, 'destroy'])->name('availability.destroy');
    });

// ------------------------ Patient ------------------------
Route::middleware(['auth', 'role:patient'])
    ->as('patient.')
    ->group(function () {
        Route::view('/patient', 'dash.patient')->name('dashboard');

        // Optional: alias used by some views (safe redirect to the real dashboard)
        Route::get('/patient/dashboard', fn () => redirect()->route('patient.dashboard'))->name('dash.patient');
    });

require __DIR__ . '/auth.php';
