@extends('layouts.app')

@section('title', 'Select a Time • HealthMiles')

@section('content')
<div class="container my-4 role-patient">
    {{-- Back + Doctor header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="{{ route('patient.appointments.index', ['service_id' => $serviceIdFixed]) }}" class="btn btn-soft">
            ← Back to doctors
        </a>
        <div></div>
    </div>

    {{-- Hero --}}
    <div class="hm-card shadow-soft p-3 p-md-4">
        <div class="d-flex align-items-center gap-3">
            <img
                class="hm-apt-avatar"
                src="https://api.dicebear.com/9.x/initials/svg?seed={{ urlencode($doctor->name) }}"
                alt="Avatar">
            <div>
                <h3 class="mb-0 text-capitalize">{{ $doctor->name }}</h3>
                <div class="hm-muted small mt-1">
                    @forelse($doctor->services as $s)
                        <span class="hm-chip me-1">{{ $s->name }}</span>
                    @empty
                        <span class="hm-chip me-1">General Consultation</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Booking Card --}}
    <div class="hm-card shadow-soft mt-3 p-3 p-md-4">
        <form method="POST" action="{{ route('patient.appointments.store') }}" id="bookForm">
            @csrf
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

            {{-- Service (doctor-only) --}}
            @php $readonly = $availableServices->count() === 1; @endphp
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Service</label>
                    <select name="service_id" class="form-select" {{ $readonly ? 'disabled' : '' }} required>
                        @foreach($availableServices as $s)
                            <option value="{{ $s->id }}" @selected((int)$serviceIdFixed === (int)$s->id)>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($readonly)
                        <input type="hidden" name="service_id" value="{{ $serviceIdFixed }}">
                    @endif
                </div>
            </div>

            <hr class="my-4">

            {{-- Slots --}}
            <h5 class="mb-3">Select a Date & Time</h5>

            @php
                use Carbon\Carbon;
                function hm_format_slot_ts($ts) {
                    if (!empty($ts->start_at)) {
                        $start = Carbon::parse($ts->start_at);
                        $end   = !empty($ts->end_at) ? Carbon::parse($ts->end_at) : null;
                        $dateText = $start->format('M j, Y');
                        $timeText = $start->format('g:i A') . ($end ? ' – '.$end->format('g:i A') : '');
                        return [$dateText, $timeText];
                    }
                    $d = $ts->slot_date ?? $ts->date ?? null;
                    $dateText = $d ? Carbon::parse($d)->format('M j, Y') : '—';
                    $fmt = fn($t) => $t ? Carbon::parse($t)->format('g:i A') : '';
                    $startT = $ts->start_time ?? null;
                    $endT   = $ts->end_time ?? null;
                    $timeText = trim($fmt($startT) . ($endT ? ' – '.$fmt($endT) : ''));
                    return [$dateText, $timeText];
                }
            @endphp

            @if($slots->isEmpty())
                <div class="empty-info">No upcoming slots are available for this doctor.</div>
            @else
                <div class="doctor-grid mt-2">
                    @foreach($slots as $i => $ts)
                        @php([$dateText, $timeText] = hm_format_slot_ts($ts))
                        <label class="doctor-card hm-quick p-3" style="cursor:pointer;">
                            <div class="top d-flex align-items-start">
                                <input
                                    type="radio"
                                    class="form-check-input me-2 mt-1"
                                    name="time_slot_id"
                                    value="{{ $ts->id }}"
                                    @checked($i===0)
                                    required
                                >
                                <div>
                                    <div class="fw-semibold">{{ $dateText }}</div>
                                    <div class="hm-muted">{{ $timeText }}</div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif

            {{-- Notes --}}
            <div class="mt-4">
                <label class="form-label fw-semibold">Notes (optional)</label>
                <textarea name="notes" rows="3" class="form-control"
                          placeholder="Anything your doctor should know in advance?"></textarea>
            </div>

            {{-- Submit --}}
            <div class="mt-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-hm px-4">Book Appointment</button>
            </div>
        </form>
    </div>
</div>
@endsection
