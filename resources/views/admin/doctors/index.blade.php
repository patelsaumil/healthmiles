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
<div class="container-fluid admin-content p-0">

    {{-- Page header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <h4 class="mb-0">Doctors</h4>
            {{-- tiny chips (optional) --}}
            <span class="hm-badge badge-confirmed">{{ $counts['active'] ?? ($activeCount ?? 0) }} active</span>
            <span class="hm-badge badge-cancelled">{{ $counts['inactive'] ?? ($inactiveCount ?? 0) }} inactive</span>
        </div>
        <a href="{{ route('admin.doctors.create') }}" class="btn btn-hm rounded-16">
            <i class="bi bi-plus-lg"></i> Add Doctor
        </a>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="hm-alert hm-alert--ok mb-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="hm-alert hm-alert--err mb-3">{{ session('error') }}</div>
    @endif

    {{-- Search / Filter --}}
    <div class="hm-card p-3 mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-6 col-md-4">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="q" class="form-control" value="{{ $search }}"
                       placeholder="Search by name, email, or phone">
            </div>
            <div class="col-sm-4 col-md-3">
                <label class="form-label small text-muted">Status</label>
                <select class="form-select" name="active">
                    <option value="">All</option>
                    <option value="1" @selected(request('active')==='1')>Active</option>
                    <option value="0" @selected(request('active')==='0')>Inactive</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-hm rounded-16"><i class="bi bi-search"></i> Search</button>
                @if(!empty($search) || strlen((string)request('active')))
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-soft rounded-16 ms-2">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="hm-card p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 admin-table">
                <thead class="table-light sticky-top">
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Specialty</th>
                        <th style="width:90px">Active</th>
                        <th style="width:110px">Services</th>
                        <th style="width:170px">Time Slots</th>
                        <th class="text-end" style="width:160px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($doctors as $doc)
                    <tr>
                        <td class="text-muted">{{ $doc->id }}</td>

                        <td class="text-truncate" style="max-width:280px">
                            <div class="d-flex align-items-center gap-2">
                                <div class="hm-avatar">{{ strtoupper(mb_substr($doc->name,0,1)) }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $doc->name }}</div>
                                    @if($doc->phone)
                                        <div class="small text-muted">{{ $doc->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="text-nowrap">{{ $doc->email }}</td>
                        <td class="text-truncate" style="max-width:180px">{{ $doc->specialty ?: '—' }}</td>

                        <td>
                            @if($doc->active)
                                <span class="hm-badge badge-completed">Yes</span>
                            @else
                                <span class="hm-badge badge-cancelled">No</span>
                            @endif
                        </td>

                        {{-- Services count (keeps your logic) --}}
                        <td>{{ $doc->services_count ?? $doc->services()->count() }}</td>

                        {{-- Time slots: count + quick manage --}}
                        <td class="text-nowrap">
                            <a href="{{ route('admin.doctors.timeslots.index', $doc) }}"
                               class="btn btn-sm btn-soft rounded-16">
                                Manage ({{ $doc->time_slots_count ?? $doc->timeSlots()->count() }})
                            </a>
                            <a href="{{ route('admin.doctors.timeslots.create', $doc) }}"
                               class="btn btn-sm btn-hm rounded-16 ms-1">
                                + Slot
                            </a>
                        </td>

                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.doctors.edit', $doc) }}"
                                   class="btn btn-sm btn-soft rounded-16">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.doctors.destroy', $doc) }}"
                                      method="POST" onsubmit="return confirm('Delete this doctor?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-16">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
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
