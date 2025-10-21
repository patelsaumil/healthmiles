@extends('layouts.healthmiles')
@section('title','Edit Doctor')

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
        <h4 class="fw-bold mb-0"><i class="bi bi-person-lines-fill"></i> Edit Doctor</h4>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Fix the errors below:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Edit Form --}}
    <div class="hm-card p-4">
        <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}">
            @csrf
            @method('PUT')

            {{-- Reusable partial --}}
            @include('admin.doctors.partials.form')

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Doctor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
