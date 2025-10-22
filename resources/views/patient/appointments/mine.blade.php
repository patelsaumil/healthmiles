@extends('layouts.app')
@section('title','My Appointments')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 role-patient">

  <div class="hm-hero rounded-16 shadow-soft mb-5">
    <div class="flex items-center justify-between">
      <div>
        <div class="hm-hero__title">My Appointments</div>
        <div class="hm-hero__subtitle text-sm">View upcoming, past, and cancelled bookings</div>
      </div>
      <a href="{{ route('patient.appointments.index') }}" class="btn-ghost px-4 py-2 text-sm font-semibold">Book New</a>
    </div>
  </div>

  {{-- TABS --}}
  <div class="tabs flex flex-wrap gap-2 mb-4">
    <button class="tab active" data-tab="tab-upcoming">Upcoming</button>
    <button class="tab" data-tab="tab-past">Past</button>
    <button class="tab" data-tab="tab-cancelled">Cancelled</button>
  </div>

  {{-- UPCOMING --}}
  <div id="tab-upcoming">
    @forelse ($upcoming as $appt)
      @php
        $start = optional($appt->timeSlot)->start_at ?? (optional($appt->timeSlot)->date.' '.optional($appt->timeSlot)->start_time);
        $end   = optional($appt->timeSlot)->end_at   ?? (optional($appt->timeSlot)->date.' '.optional($appt->timeSlot)->end_time);
        $s = $start ? \Carbon\Carbon::parse($start) : null;
        $e = $end   ? \Carbon\Carbon::parse($end)   : null;
      @endphp

      <div class="hm-apt-card mb-3">
        <div class="hm-apt-header">
          <img class="hm-apt-avatar" src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($appt->doctor->name) }}">
          <div class="hm-apt-meta">
            <div class="font-semibold">{{ $appt->doctor->name }}</div>
            <small>{{ $appt->service->name }}</small>
            <small class="hm-muted">{{ $s? $s->format('D, M d Y · h:i A') : '' }}@if($e) – {{ $e->format('h:i A') }} @endif</small>
          </div>
        </div>
        <div class="flex items-center gap-2 mt-3">
          <a class="btn-soft px-3 py-2"
             href="{{ route('patient.appointments.doctor', ['doctor'=>$appt->doctor_id,'service_id'=>$appt->service_id]) }}">
            Reschedule
          </a>
          <form method="POST" action="{{ route('patient.appointments.destroy', $appt->id) }}"
                onsubmit="return confirm('Cancel this appointment?');">
            @csrf @method('DELETE')
            <button class="btn-soft px-3 py-2 text-red-600">Cancel</button>
          </form>
        </div>
      </div>
    @empty
      <div class="empty-info">
        No upcoming appointments yet. <a class="underline" href="{{ route('patient.appointments.index') }}">Book one</a>.
      </div>
    @endforelse
  </div>

  {{-- PAST --}}
  <div id="tab-past" class="hidden">
    @forelse ($past as $appt)
      @php
        $start = optional($appt->timeSlot)->start_at ?? (optional($appt->timeSlot)->date.' '.optional($appt->timeSlot)->start_time);
        $s = $start ? \Carbon\Carbon::parse($start) : null;
      @endphp
      <div class="hm-apt-card mb-3">
        <div class="hm-apt-header">
          <img class="hm-apt-avatar" src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($appt->doctor->name) }}">
          <div class="hm-apt-meta">
            <div class="font-semibold">{{ $appt->doctor->name }}</div>
            <small>{{ $appt->service->name }}</small>
            <small class="hm-muted">{{ $s? $s->format('D, M d Y · h:i A') : '' }}</small>
          </div>
        </div>
        <span class="hm-badge badge-completed mt-3 inline-block">Completed</span>
      </div>
    @empty
      <div class="empty-info">No past appointments.</div>
    @endforelse
  </div>

  {{-- CANCELLED --}}
  <div id="tab-cancelled" class="hidden">
    @forelse ($cancelled as $appt)
      @php
        $start = optional($appt->timeSlot)->start_at ?? (optional($appt->timeSlot)->date.' '.optional($appt->timeSlot)->start_time);
        $s = $start ? \Carbon\Carbon::parse($start) : null;
      @endphp
      <div class="hm-apt-card mb-3">
        <div class="hm-apt-header">
          <img class="hm-apt-avatar" src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($appt->doctor->name) }}">
          <div class="hm-apt-meta">
            <div class="font-semibold">{{ $appt->doctor->name }}</div>
            <small>{{ $appt->service->name }}</small>
            <small class="hm-muted">{{ $s? $s->format('D, M d Y · h:i A') : '' }}</small>
          </div>
        </div>
        <span class="hm-badge badge-cancelled mt-3 inline-block">Cancelled</span>
      </div>
    @empty
      <div class="empty-info">No cancelled appointments.</div>
    @endforelse
  </div>
</div>

<script>
  const tabs = document.querySelectorAll('.tab');
  tabs.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      tabs.forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
      document.querySelectorAll('#tab-upcoming,#tab-past,#tab-cancelled').forEach(el=>el.classList.add('hidden'));
      document.getElementById(btn.dataset.tab).classList.remove('hidden');
    });
  });
</script>
@endsection
