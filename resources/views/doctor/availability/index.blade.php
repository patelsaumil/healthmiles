@extends('layouts.healthmiles')
@section('title','My Availability')

{{-- Sidebar (same as appointments) --}}
@section('sidebar')
    <div>
        <div class="title">Doctor</div>
        <a class="hm-nav-link {{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}"
           href="{{ route('doctor.appointments.index') }}">
            <i class="bi bi-calendar2-check"></i> My Appointments
        </a>
        <a class="hm-nav-link {{ request()->routeIs('doctor.availability.*') ? 'active' : '' }}"
           href="{{ route('doctor.availability.index') }}">
            <i class="bi bi-clock-history"></i> Availability
        </a>
        <a class="hm-nav-link" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i> Profile</a>
    </div>
@endsection

@section('content')
<div class="container-fluid py-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">My Availability</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('doctor.appointments.index') }}" class="btn btn-soft">
                <i class="bi bi-journal-check"></i> My Appointments
            </a>
            <a href="{{ route('doctor.availability.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Slot
            </a>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <div class="hm-card p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0 hm-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Booked?</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($slots as $s)
                    <tr>
                        <td>{{ $s->slot_date }}</td>
                        <td>{{ $s->start_time }}</td>
                        <td>{{ $s->end_time }}</td>
                        <td>
                            @if($s->is_booked)
                                <span class="hm-badge badge-pending">Yes</span>
                            @else
                                <span class="hm-badge badge-confirmed">No</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('doctor.availability.edit', $s) }}" class="btn btn-sm btn-soft">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form class="d-inline" method="POST" action="{{ route('doctor.availability.destroy', $s) }}"
                                  onsubmit="return confirm('Delete this slot?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-soft text-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No slots yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($slots,'links'))
            <div class="p-3">
                {{ $slots->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
