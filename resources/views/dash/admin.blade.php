@extends('layouts.healthmiles')
@section('title', 'Admin Dashboard')

{{-- SIDEBAR --}}
@section('sidebar')
<div>
    <div class="title">Admin</div>

    {{-- Overview --}}
    <a class="hm-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Overview
    </a>

    {{-- Doctors --}}
    <a class="hm-nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"
       href="{{ route('admin.doctors.index') }}">
        <i class="bi bi-people"></i> Doctors
    </a>

    {{-- Services --}}
    <a class="hm-nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
       href="{{ route('admin.services.index') }}">
        <i class="bi bi-clipboard2-pulse"></i> Services
    </a>

    {{-- Time Slots (there is no global index; link to Doctors as the entry point) --}}
    <a class="hm-nav-link {{ request()->routeIs('admin.doctors.timeslots.*') ? 'active' : '' }}"
       href="{{ route('admin.doctors.index') }}">
        <i class="bi bi-clock-history"></i> Time Slots
    </a>

    {{-- Appointments --}}
    <a class="hm-nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}"
       href="{{ route('admin.appointments.index') }}">
        <i class="bi bi-calendar2-check"></i> Appointments
    </a>

    {{-- Reports (placeholder) --}}
    <a class="hm-nav-link" href="#">
        <i class="bi bi-graph-up-arrow"></i> Reports
    </a>
</div>
@endsection

{{-- CONTENT --}}
@section('content')
<div class="container-fluid py-0">
    {{-- Example content card (your existing dashboard UI can stay as-is) --}}
    <div class="hm-card p-3 mb-3">
        <h4 class="mb-0">Admin Dashboard</h4>
    </div>

    {{-- Your existing dashboard blocks go here... --}}
    {{-- (metrics, charts, recent activity, quick actions, etc.) --}}
</div>
@endsection
