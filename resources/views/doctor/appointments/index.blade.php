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
    @if(session('success')) <div class="alert alert-success rounded-3">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger rounded-3">{{ session('error') }}</div>   @endif

    @php
        use Carbon\Carbon;
        $upcoming = $upcoming ?? collect();
        $past     = $past ?? collect();

        // status → badge class
        $statusClass = [
            'pending'   => 'badge-pending',
            'confirmed' => 'badge-confirmed',
            'scheduled' => 'badge-confirmed',
            'rescheduled' => 'badge-pending',
            'completed' => 'badge-completed',
            'cancelled' => 'badge-cancelled',
        ];

        /**
         * Format a slot to ['date' => 'Oct 30, 2025', 'time' => '10:00 AM – 5:00 PM']
         * Supports either start_at/end_at or slot_date/date + start_time/end_time
         */
        function hm_slot_text($slot): array {
            if (!$slot) return ['date' => '—', 'time' => '—'];

            // Prefer timestamp columns when present
            if (!empty($slot->start_at)) {
                $start = Carbon::parse($slot->start_at);
                $end   = !empty($slot->end_at) ? Carbon::parse($slot->end_at) : null;
                return [
                    'date' => $start->format('M j, Y'),
                    'time' => $start->format('g:i A') . ($end ? ' – '.$end->format('g:i A') : '')
                ];
            }

            // Fallback: date + time strings
            $dateRaw = $slot->slot_date ?? $slot->date ?? null;
            $dateTxt = $dateRaw ? Carbon::parse($dateRaw)->format('M j, Y') : '—';

            $startTxt = !empty($slot->start_time) ? Carbon::parse($slot->start_time)->format('g:i A') : null;
            $endTxt   = !empty($slot->end_time)   ? Carbon::parse($slot->end_time)->format('g:i A')   : null;

            $timeTxt  = $startTxt ? ($endTxt ? "$startTxt – $endTxt" : $startTxt) : '—';

            return ['date' => $dateTxt, 'time' => $timeTxt];
        }
    @endphp

    <div class="row g-3">
        {{-- LEFT COLUMN: Calendar + Upcoming --}}
        <div class="col-lg-8">
            {{-- Calendar block (placeholder) --}}
            <div class="hm-card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Calendar View</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-soft">Week</button>
                        <button type="button" class="btn btn-sm btn-primary text-white">Month</button>
                    </div>
                </div>
                <div class="border rounded-3 p-5 text-center text-muted">
                    <i class="bi bi-calendar3"></i> Calendar integration placeholder
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0">Upcoming Appointments</h6>
                @if(isset($counts['upcoming']))
                    <span class="hm-badge badge-confirmed">{{ $counts['upcoming'] }}</span>
                @endif
            </div>

            @forelse($upcoming as $a)
                @php
                    $slotTxt = hm_slot_text(optional($a)->timeSlot);
                    $patient = $a->patient?->name ?? 'Patient';
                    $service = $a->service?->name ?? 'Service';
                    $status  = $a->status ?? 'pending';
                @endphp

                <div class="hm-apt-card mb-3">
                    <div class="d-flex justify-content-between">
                        {{-- Left: patient & meta --}}
                        <div class="hm-apt-header">
                            <img class="hm-apt-avatar"
                                 src="https://api.dicebear.com/9.x/initials/svg?seed={{ urlencode($patient) }}"
                                 alt="">
                            <div class="hm-apt-meta">
                                <strong>{{ $patient }}</strong>
                                @if(!empty($a->patient?->email))
                                    <small>{{ $a->patient->email }}</small>
                                @endif
                                <small>Service: {{ $service }}</small>
                                <small>Date: {{ $slotTxt['date'] }}</small>
                                <small>Time: {{ $slotTxt['time'] }}</small>
                                @if(!empty($a->notes))
                                    <small class="d-block mt-1">{{ $a->notes }}</small>
                                @endif
                            </div>
                        </div>

                        {{-- Right: status --}}
                        <div class="ms-2">
                            <span class="hm-badge {{ $statusClass[$status] ?? 'badge-pending' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
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
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0">Past Appointments</h6>
                @if(isset($counts['past']))
                    <span class="hm-badge badge-muted">{{ $counts['past'] }}</span>
                @endif
            </div>

            <div class="d-flex flex-column gap-2">
                @forelse($past as $a)
                    @php
                        $slotTxt = hm_slot_text(optional($a)->timeSlot);
                        $patient = $a->patient?->name ?? 'Patient';
                        $service = $a->service?->name ?? 'Service';
                        $status  = $a->status ?? 'completed';
                    @endphp
                    <div class="hm-card p-3">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <img class="hm-apt-avatar"
                                     src="https://api.dicebear.com/9.x/initials/svg?seed={{ urlencode($patient) }}"
                                     alt="">
                                <div>
                                    <strong class="d-block">{{ $patient }}</strong>
                                    <small class="text-muted d-block">Service: {{ $service }}</small>
                                    <small class="text-muted d-block">
                                        {{ $slotTxt['date'] }} · {{ $slotTxt['time'] }}
                                    </small>
                                </div>
                            </div>
                            <span class="hm-badge {{ $statusClass[$status] ?? 'badge-completed' }}">
                                {{ ucfirst($status) }}
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
