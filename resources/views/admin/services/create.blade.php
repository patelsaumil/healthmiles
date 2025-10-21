@extends('layouts.healthmiles')
@section('title', 'Add Service')

{{-- Sidebar --}}
@section('sidebar')
<div>
    <div class="title">Admin</div>

    <a href="{{ route('admin.dashboard') }}"
       class="hm-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
       <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="{{ route('admin.doctors.index') }}"
       class="hm-nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
       <i class="bi bi-person-badge"></i> Doctors
    </a>

    <a href="{{ route('admin.services.index') }}"
       class="hm-nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
       <i class="bi bi-clipboard2-pulse"></i> Services
    </a>

    <a href="{{ route('admin.appointments.index') }}"
       class="hm-nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
       <i class="bi bi-calendar2-check"></i> Appointments
    </a>
</div>
@endsection

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-plus-circle"></i> Add New Service</h4>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="hm-card p-4">
        <form method="POST" action="{{ route('admin.services.store') }}">
            @csrf
            @include('admin.services.partials.form')

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
                <button class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Service
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
