<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// --- Admin controllers
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;

// --- Doctor controllers
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\AvailabilityController as DoctorAvailabilityController;

// --- Patient controllers
use App\Http\Controllers\Patient\PatientRegistrationController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;

/*
|--------------------------------------------------------------------------
| Public marketing pages
|--------------------------------------------------------------------------
| These match your Figma home/about/contact pages under resources/views/pages/
*/
Route::view('/', 'pages.home')->name('home');                // resources/views/pages/home.blade.php
Route::view('/about', 'pages.about')->name('about');         // resources/views/pages/about.blade.php
Route::view('/contact', 'pages.contact')->name('contact');   // resources/views/pages/contact.blade.php

/*
|--------------------------------------------------------------------------
| After login, send users to their role dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role ?? 'patient') {
        'admin'   => redirect()->route('admin.dashboard'),
        'doctor'  => redirect()->route('doctor.dashboard'),
        'patient' => redirect()->route('patient.dashboard'),
        default   => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Common Profile Routes (Breeze/Jetstream default)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->as('admin.')
    ->group(function () {
        Route::view('/', 'dash.admin')->name('dashboard');
        Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->name('dash.admin');

        Route::resource('doctors',  DoctorController::class);
        Route::resource('services', ServiceController::class);

        // Time slots nested under doctor (shallow for edit/update/destroy)
        Route::resource('doctors.timeslots', TimeSlotController::class)->shallow();

        // Appointments (index + delete only)
        Route::resource('appointments', AdminAppointmentController::class)->only(['index', 'destroy']);
    });

/*
|--------------------------------------------------------------------------
| Doctor
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:doctor'])
    ->prefix('doctor')->as('doctor.')
    ->group(function () {
        Route::view('/', 'doctor.dashboard')->name('dashboard');
        Route::get('/dashboard', fn () => redirect()->route('doctor.dashboard'))->name('dash.doctor');

        Route::get('appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
        Route::delete('appointments/{appointment}', [DoctorAppointmentController::class, 'destroy'])->name('appointments.destroy');

        Route::get('availability', [DoctorAvailabilityController::class, 'index'])->name('availability.index');
        Route::get('availability/create', [DoctorAvailabilityController::class, 'create'])->name('availability.create');
        Route::post('availability', [DoctorAvailabilityController::class, 'store'])->name('availability.store');
        Route::get('availability/{timeslot}/edit', [DoctorAvailabilityController::class, 'edit'])->name('availability.edit');
        Route::put('availability/{timeslot}', [DoctorAvailabilityController::class, 'update'])->name('availability.update');
        Route::delete('availability/{timeslot}', [DoctorAvailabilityController::class, 'destroy'])->name('availability.destroy');
    });

/*
|--------------------------------------------------------------------------
| Patient Registration (guest) — uses users table (role=patient)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/patient/register', [PatientRegistrationController::class, 'create'])->name('patient.register');
    Route::post('/patient/register', [PatientRegistrationController::class, 'store'])->name('patient.register.store');
});

/*
|--------------------------------------------------------------------------
| Patient (auth, role:patient)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:patient'])
    ->prefix('patient')->as('patient.')
    ->group(function () {
        // Dashboard
        Route::view('/', 'dash.patient')->name('dashboard');
        Route::get('/dashboard', fn () => redirect()->route('patient.dashboard'))->name('dash.patient');

        // Booking flow
        Route::get('/appointments',                 [PatientAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/doctor/{doctor}', [PatientAppointmentController::class, 'showDoctor'])->name('appointments.doctor');
        Route::post('/appointments',                [PatientAppointmentController::class, 'store'])->name('appointments.store');

        // My Appointments
        Route::get('/my-appointments', [PatientAppointmentController::class, 'myAppointments'])->name('appointments.mine');
        Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::get('/appointments/{appointment}/reschedule', [PatientAppointmentController::class, 'rescheduleForm'])->name('appointments.reschedule.form');
        Route::post('/appointments/{appointment}/reschedule', [PatientAppointmentController::class, 'reschedule'])->name('appointments.reschedule');
        Route::delete('/appointments/{appointment}', [PatientAppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

require __DIR__.'/auth.php';
