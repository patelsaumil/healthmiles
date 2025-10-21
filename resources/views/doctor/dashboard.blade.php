@extends('layouts.healthmiles')
@section('title', 'Doctor Dashboard')

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
        <a class="hm-nav-link" href="{{ route('profile.edit') }}">
            <i class="bi bi-person"></i> Profile
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid py-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Doctor Dashboard</h3>
        <div>
            <a href="{{ route('doctor.appointments.index') }}" class="btn btn-primary me-2">
                <i class="bi bi-calendar2-event"></i> My Appointments
            </a>
            <a href="{{ route('doctor.availability.index') }}" class="btn btn-soft">
                <i class="bi bi-clock"></i> My Availability
            </a>
        </div>
    </div>

    {{-- Overview Cards --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="hm-card p-4 text-center">
                <i class="bi bi-people text-primary fs-3 mb-2"></i>
                <h5 class="fw-semibold mb-1">Upcoming Appointments</h5>
                <p class="text-muted small mb-0">View and manage your scheduled appointments</p>
                <a href="{{ route('doctor.appointments.index') }}" class="btn btn-sm btn-primary mt-3">
                    Go to Appointments
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="hm-card p-4 text-center">
                <i class="bi bi-clock-history text-success fs-3 mb-2"></i>
                <h5 class="fw-semibold mb-1">Availability Slots</h5>
                <p class="text-muted small mb-0">Add, edit, or delete available time slots</p>
                <a href="{{ route('doctor.availability.index') }}" class="btn btn-sm btn-soft mt-3">
                    Manage Availability
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="hm-card p-4 text-center">
                <i class="bi bi-person-circle text-info fs-3 mb-2"></i>
                <h5 class="fw-semibold mb-1">Profile</h5>
                <p class="text-muted small mb-0">Update your contact and professional information</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-soft mt-3">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
