@extends('layouts.healthmiles')
@section('title','Add Time Slot')

{{-- Sidebar --}}
@section('sidebar')
    <div>
        <div class="title">Doctor</div>
        <a class="hm-nav-link {{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}"
           href="{{ route('doctor.appointments.index') }}">
            <i class="bi bi-calendar2-check"></i> My Appointments
        </a>
        <a class="hm-nav-link {{ request()->routeIs('doctor.availability.*') ? 'active' : '' }}"
           href="{{ route('doctor.availability.index') }}">
            <i class="bi bi-clock-history"></i> Availability
        </a>
        <a class="hm-nav-link" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i> Profile</a>
    </div>
@endsection

@section('content')
<div class="container-fluid py-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Add Time Slot</h3>
        <a href="{{ route('doctor.availability.index') }}" class="btn btn-soft">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="hm-card p-4">
        <form method="POST" action="{{ route('doctor.availability.store') }}" class="row g-3">
            @csrf

            <div class="col-md-4">
                <label class="form-label fw-semibold">Date</label>
                <input type="date" name="slot_date" value="{{ old('slot_date') }}" class="form-control" required>
                @error('slot_date')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}" class="form-control" required>
                @error('start_time')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">End Time</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}" class="form-control" required>
                @error('end_time')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-3">
                <a href="{{ route('doctor.availability.index') }}" class="btn btn-soft">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Slot
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
