@extends('layouts.healthmiles')
@section('title', 'Doctors')

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
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="mb-2 mb-sm-0">Doctors</h4>
        <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Doctor
        </a>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <div class="hm-card p-3 mb-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-6 col-md-4">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="q" class="form-control" value="{{ $search }}"
                       placeholder="Search name, email, phone">
            </div>
            <div class="col-auto d-flex align-items-end">
                <button class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
                @if(!empty($search))
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary ms-2">Clear</a>
                @endif
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
                        <th>Email</th>
                        <th>Specialty</th>
                        <th>Active</th>
                        <th>Services</th>
                        <th>Time Slots</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($doctors as $doc)
                    <tr>
                        <td>{{ $doc->id }}</td>
                        <td>{{ $doc->name }}</td>
                        <td class="text-nowrap">{{ $doc->email }}</td>
                        <td>{{ $doc->specialty }}</td>
                        <td>
                            @if($doc->active)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>

                        {{-- services count (keeps your logic) --}}
                        <td>{{ $doc->services_count ?? $doc->services()->count() }}</td>

                        {{-- time slots: count + quick add --}}
                        <td class="text-nowrap">
                            <a href="{{ route('admin.doctors.timeslots.index', $doc) }}"
                               class="btn btn-sm btn-soft">
                                Manage ({{ $doc->time_slots_count ?? $doc->timeSlots()->count() }})
                            </a>
                            <a href="{{ route('admin.doctors.timeslots.create', $doc) }}"
                               class="btn btn-sm btn-primary ms-1">
                                + Slot
                            </a>
                        </td>

                        <td class="text-end">
                            <a href="{{ route('admin.doctors.edit', $doc) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('admin.doctors.destroy', $doc) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this doctor?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inboxes"></i> No doctors found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-3">
            {{ $doctors->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
