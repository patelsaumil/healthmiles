<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// >>> Admin controllers for CRUD
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TimeSlotController;


Route::get('/', fn () => view('welcome'));

// After login, send users to their role dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role ?? 'patient') {
        'admin'   => redirect()->route('dash.admin'),
        'doctor'  => redirect()->route('dash.doctor'),
        'patient' => redirect()->route('dash.patient'),
        default   => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::view('/admin', 'dash.admin')->name('dash.admin');
});
Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::view('/doctor', 'dash.doctor')->name('dash.doctor');
});
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::view('/patient', 'dash.patient')->name('dash.patient');
});


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        // Doctors
        Route::resource('doctors', DoctorController::class);

        // Services
        Route::resource('services', ServiceController::class);

        // Time Slots (nested under a doctor, shallow for edit/update/destroy)
        Route::resource('doctors.timeslots', TimeSlotController::class)->shallow();
    });

require __DIR__.'/auth.php';
