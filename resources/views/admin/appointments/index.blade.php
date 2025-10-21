@extends('layouts.healthmiles')

@section('title', 'Manage Appointments')

{{-- Sidebar for Admin --}}
@section('sidebar')
<div class="hm-sidebar">
    <div class="title">ADMIN</div>
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

{{-- Main Content --}}
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Appointments</h3>
    </div>

    {{-- Search + Filter --}}
    <form method="GET" action="{{ route('admin.appointments.index') }}" class="d-flex flex-wrap gap-2 mb-4">
        <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control w-auto" placeholder="Search by doctor name...">
        <select name="status" class="form-select w-auto">
            <option value="">All Status</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" {{ ($status ?? '') === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i> Filter
        </button>
    </form>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Doctor</th>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Slot Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appt)
                            <tr>
                                <td>{{ $appt->id }}</td>
                                <td>{{ $appt->doctor->name ?? 'N/A' }}</td>
                                <td>{{ $appt->patient->name ?? 'N/A' }}</td>
                                <td>{{ $appt->service->name ?? 'N/A' }}</td>
                                <td>{{ $appt->timeSlot->slot_date ?? 'N/A' }}</td>
                                <td>{{ $appt->timeSlot->start_time ?? '-' }}</td>
                                <td>{{ $appt->timeSlot->end_time ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ match($appt->status) {
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        'scheduled' => 'info',
                                        default => 'warning'
                                    } }}">
                                        {{ ucfirst($appt->status) }}
                                    </span>
                                </td>
                                <td>{{ $appt->notes ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.appointments.destroy', $appt->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this appointment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">
                                    <i class="bi bi-calendar-x"></i> No appointments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
