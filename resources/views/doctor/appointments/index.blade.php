@extends('layouts.healthmiles')
@section('title','Doctor’s dashboard')

{{-- Left sidebar --}}
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
    {{-- Flash messages --}}
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    @php
        $upcoming = $upcoming ?? collect();
        $past     = $past ?? collect();

        // Map status → badge class from healthmiles.css
        $statusClass = [
            'pending'   => 'badge-pending',
            'confirmed' => 'badge-confirmed',
            'completed' => 'badge-completed',
            'cancelled' => 'badge-cancelled',
        ];
    @endphp

    <div class="row g-3">
        {{-- LEFT COLUMN: Calendar + Upcoming --}}
        <div class="col-lg-8">
            {{-- Calendar block (placeholder) --}}
            <div class="hm-card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Calendar View</h5>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-soft">Week</button>
                        <button class="btn btn-sm btn-primary text-white">Month</button>
                    </div>
                </div>
                <div class="border rounded-3 p-5 text-center text-muted">
                    <i class="bi bi-calendar3"></i> Calendar integration placeholder
                </div>
            </div>

            <h6 class="mb-2">Upcoming Appointments</h6>

            @forelse($upcoming as $a)
                <div class="hm-apt-card mb-3">
                    <div class="d-flex justify-content-between">
                        {{-- Left: patient & meta --}}
                        <div class="hm-apt-header">
                            <img class="hm-apt-avatar"
                                 src="https://api.dicebear.com/9.x/initials/svg?seed={{ urlencode($a->patient->name ?? 'PT') }}"
                                 alt="">
                            <div class="hm-apt-meta">
                                <strong>{{ $a->patient->name ?? 'Patient' }}</strong>
                                @if(!empty($a->patient?->email))
                                    <small>{{ $a->patient->email }}</small>
                                @endif
                                <small>Service: {{ $a->service->name ?? '' }}</small>
                                <small>
                                    Date: {{ optional($a->timeSlot)->slot_date }}
                                </small>
                                <small>
                                    Time: {{ optional($a->timeSlot)->start_time }} - {{ optional($a->timeSlot)->end_time }}
                                </small>
                                @if($a->notes)
                                    <small class="d-block mt-1">{{ $a->notes }}</small>
                                @endif
                            </div>
                        </div>

                        {{-- Right: status --}}
                        <div>
                            <span class="hm-badge {{ $statusClass[$a->status] ?? 'badge-pending' }}">
                                {{ ucfirst($a->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- Actions (keep exactly what you had: delete only) --}}
                    <div class="mt-3 d-flex gap-2">
                        <form action="{{ route('doctor.appointments.destroy', $a->id) }}"
                              method="POST" onsubmit="return confirm('Delete this appointment?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-muted">No upcoming appointments.</div>
            @endforelse
        </div>

        {{-- RIGHT COLUMN: Past --}}
        <div class="col-lg-4">
            <h6 class="mb-2">Past Appointments</h6>

            <div class="d-flex flex-column gap-2">
                @forelse($past as $a)
                    <div class="hm-card p-3">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <img class="hm-apt-avatar"
                                     src="https://api.dicebear.com/9.x/initials/svg?seed={{ urlencode($a->patient->name ?? 'PT') }}"
                                     alt="">
                                <div>
                                    <strong class="d-block">{{ $a->patient->name ?? 'Patient' }}</strong>
                                    <small class="text-muted d-block">
                                        Service: {{ $a->service->name ?? '' }}
                                    </small>
                                    <small class="text-muted d-block">
                                        Date: {{ optional($a->timeSlot)->slot_date }}
                                        · {{ optional($a->timeSlot)->start_time }} - {{ optional($a->timeSlot)->end_time }}
                                    </small>
                                </div>
                            </div>
                            <span class="hm-badge {{ $statusClass[$a->status] ?? 'badge-pending' }}">
                                {{ ucfirst($a->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">No past appointments.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
