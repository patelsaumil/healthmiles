@extends('layouts.healthmiles')
@section('title', $doctor->name)

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
            <i class="bi bi-person-circle text-primary"></i> {{ $doctor->name }}
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left column --}}
        <div class="col-lg-8">
            <div class="hm-card p-4 mb-3">
                <h6 class="text-muted mb-3">Doctor Profile</h6>
                <div class="row g-2">
                    <div class="col-md-6">
                        <p><strong>Email:</strong> {{ $doctor->email }}</p>
                        <p><strong>Phone:</strong> {{ $doctor->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Specialty:</strong> {{ $doctor->specialty }}</p>
                        <p>
                            <strong>Active:</strong>
                            @if($doctor->active)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </p>
                    </div>
                </div>
                <p class="mb-0"><strong>Bio:</strong><br>{{ $doctor->bio ?: '—' }}</p>

                @if($doctor->photo_path)
                    <div class="mt-3">
                        <strong>Photo:</strong><br>
                        <img src="{{ asset('storage/' . $doctor->photo_path) }}"
                             alt="Doctor photo" class="rounded border"
                             style="max-width: 180px;">
                    </div>
                @endif
            </div>

            {{-- Time Slots --}}
            <div class="hm-card p-0">
                <div class="hm-card-header d-flex justify-content-between align-items-center px-3 py-2">
                    <h6 class="mb-0"><i class="bi bi-clock-history text-primary"></i> Upcoming Time Slots</h6>
                    <a href="{{ route('admin.doctors.timeslots.index', $doctor) }}"
                       class="btn btn-sm btn-primary">
                        <i class="bi bi-calendar-plus"></i> Manage Slots
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Booked?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doctor->timeSlots as $ts)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($ts->slot_date)->format('M d, Y') }}</td>
                                    <td>{{ substr($ts->start_time,0,5) }}</td>
                                    <td>{{ substr($ts->end_time,0,5) }}</td>
                                    <td>
                                        @if($ts->is_booked)
                                            <span class="badge bg-danger">Yes</span>
                                        @else
                                            <span class="badge bg-success">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="bi bi-inboxes"></i> No time slots available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="col-lg-4">
            <div class="hm-card p-3">
                <h6 class="mb-3 text-muted"><i class="bi bi-clipboard2-pulse"></i> Services</h6>
                @forelse($doctor->services as $s)
                    <span class="badge bg-primary mb-1">{{ $s->name }}</span>
                @empty
                    <p class="text-muted mb-0">No services linked.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
