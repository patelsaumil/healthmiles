@extends('layouts.healthmiles')
@section('title', $service->name)

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

@section('content')
<div class="container-fluid py-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="mb-2 mb-sm-0">{{ $service->name }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Edit Service
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="hm-card p-3 h-100">
                <div class="d-flex align-items-center gap-2 mb-2">
                    @php $badge = $service->status === 'active' ? 'success' : 'secondary'; @endphp
                    <span class="badge bg-{{ $badge }}">{{ ucfirst($service->status) }}</span>
                </div>

                <h6 class="text-muted mb-1">Description</h6>
                <p class="mb-0">{{ $service->description ?: '—' }}</p>

                @if(isset($service->updated_at))
                    <div class="text-muted small mt-3">
                        Last updated: {{ $service->updated_at->diffForHumans() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <div class="hm-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">
                        Doctors offering this service ({{ $service->doctors->count() }})
                    </h6>
                    <a class="btn btn-sm btn-soft"
                       href="{{ route('admin.doctors.index') }}">
                        <i class="bi bi-person-plus"></i> Manage Doctors
                    </a>
                </div>

                @if($service->doctors->isEmpty())
                    <div class="text-muted">No doctors linked yet.</div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($service->doctors as $doc)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $doc->name }}</strong>
                                    @if(!empty($doc->specialty))
                                        <span class="text-muted"> — {{ $doc->specialty }}</span>
                                    @endif
                                    @if(!empty($doc->email))
                                        <div class="small text-muted">{{ $doc->email }}</div>
                                    @endif
                                </div>
                                <a class="btn btn-sm btn-outline-secondary"
                                   href="{{ route('admin.doctors.edit', $doc) }}">
                                    Manage
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
