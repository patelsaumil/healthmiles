@extends('layouts.healthmiles')
@section('title', 'Edit Time Slot · ' . ($doctor->name ?? 'Doctor'))

{{-- Sidebar --}}
@section('sidebar')
<div>
    <div class="title">Admin</div>

    <a class="hm-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Overview
    </a>

    <a class="hm-nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"
       href="{{ route('admin.doctors.index') }}">
        <i class="bi bi-people"></i> Doctors
    </a>

    <a class="hm-nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
       href="{{ route('admin.services.index') }}">
        <i class="bi bi-clipboard2-pulse"></i> Services
    </a>

    <a class="hm-nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}"
       href="{{ route('admin.appointments.index') }}">
        <i class="bi bi-calendar2-check"></i> Appointments
    </a>
</div>
@endsection

{{-- Content --}}
@section('content')
<div class="container-fluid py-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-2 mb-sm-0">
            <i class="bi bi-clock"></i>
            Edit Time Slot <span class="text-muted">for</span> <span class="text-primary">{{ $doctor->name }}</span>
        </h4>
        <a href="{{ route('admin.doctors.timeslots.index', $doctor->id) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Time Slots
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Fix the errors below:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="hm-card p-4">
        <form action="{{ route('admin.timeslots.update', $slot->id) }}" method="POST" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-4">
                <label class="form-label fw-semibold">Slot Date</label>
                <input type="date" name="slot_date" class="form-control"
                       value="{{ old('slot_date', $slot->slot_date) }}" required>
                @error('slot_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Start Time</label>
                <input type="time" name="start_time" class="form-control"
                       value="{{ old('start_time', $slot->start_time) }}" required>
                @error('start_time') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">End Time</label>
                <input type="time" name="end_time" class="form-control"
                       value="{{ old('end_time', $slot->end_time) }}" required>
                @error('end_time') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <div class="form-check mt-1">
                    <input type="checkbox" name="is_booked" value="1" class="form-check-input" id="booked"
                           {{ old('is_booked', $slot->is_booked) ? 'checked' : '' }}>
                    <label for="booked" class="form-check-label">Mark as booked</label>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                <a href="{{ route('admin.doctors.timeslots.index', $doctor->id) }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Slot
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
