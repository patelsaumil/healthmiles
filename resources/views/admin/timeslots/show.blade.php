@extends('layouts.healthmiles')
@section('title', 'Time Slot Details · ' . ($doctor->name ?? 'Doctor'))

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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-clock-history"></i>
            Time Slot Details
            <span class="text-muted">for</span>
            <span class="text-primary">{{ $doctor->name }}</span>
        </h4>

        <a href="{{ route('admin.doctors.timeslots.index', $doctor->id) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Time Slots
        </a>
    </div>

    <div class="hm-card p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted">Doctor</label>
                <div class="fs-6">{{ $doctor->name }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted">Date</label>
                <div class="fs-6">{{ $slot->slot_date }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted">Start Time</label>
                <div class="fs-6">{{ $slot->start_time }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted">End Time</label>
                <div class="fs-6">{{ $slot->end_time }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold text-muted">Booked</label>
                <div>
                    @if ($slot->is_booked)
                        <span class="badge bg-danger">Yes</span>
                    @else
                        <span class="badge bg-success">No</span>
                    @endif
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end mt-4">
                <a href="{{ route('admin.timeslots.edit', $slot->id) }}" class="btn btn-primary me-2">
                    <i class="bi bi-pencil-square"></i> Edit Slot
                </a>
                <a href="{{ route('admin.doctors.timeslots.index', $doctor->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
