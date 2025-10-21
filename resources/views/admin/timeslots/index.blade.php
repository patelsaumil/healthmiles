@extends('layouts.healthmiles')
@section('title', 'Time Slots · ' . ($doctor->name ?? 'Doctor'))

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

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="mb-2 mb-sm-0">
            Time Slots for <span class="text-primary">{{ $doctor->name }}</span>
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Doctors
            </a>
            <a href="{{ route('admin.doctors.timeslots.create', $doctor->id) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Time Slot
            </a>
        </div>
    </div>

    <div class="hm-card p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Booked?</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($slots as $slot)
                        <tr>
                            <td>{{ $slot->id }}</td>
                            <td>{{ $slot->slot_date }}</td>
                            <td>{{ $slot->start_time }}</td>
                            <td>{{ $slot->end_time }}</td>
                            <td>
                                @if ($slot->is_booked)
                                    <span class="badge bg-danger">Yes</span>
                                @else
                                    <span class="badge bg-success">No</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.timeslots.edit', $slot->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.timeslots.destroy', $slot->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this slot?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inboxes"></i> No time slots found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination (if provided) --}}
        @if(method_exists($slots, 'links'))
            <div class="p-3">
                {{ $slots->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
