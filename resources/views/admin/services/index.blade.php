@extends('layouts.healthmiles')
@section('title','Services')

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
        <h4 class="mb-2 mb-sm-0">Services</h4>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Service
        </a>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <div class="hm-card p-3 mb-3">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <label class="form-label small text-muted">Search</label>
                <div class="input-group">
                    <input type="text" name="q" class="form-control"
                           placeholder="Search name/description..." value="{{ $q }}">
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                    @if($q)
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="hm-card p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Doctors</th>
                        <th>Updated</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($services as $svc)
                    <tr>
                        <td>{{ $svc->id }}</td>
                        <td>
                            <a href="{{ route('admin.services.show', $svc) }}" class="text-decoration-none">
                                {{ $svc->name }}
                            </a>
                        </td>
                        <td>
                            @php
                                $badge = $svc->status === 'active' ? 'success' : 'secondary';
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($svc->status) }}</span>
                        </td>
                        <td>{{ $svc->doctors_count }}</td>
                        <td>{{ optional($svc->updated_at)->diffForHumans() }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary"
                               href="{{ route('admin.services.edit', $svc) }}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('admin.services.destroy', $svc) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this service?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inboxes"></i> No services found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-3">
            {{ $services->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
