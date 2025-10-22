@extends('layouts.app')
@section('title', 'Select Service & Time — Book Appointment')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 role-patient">

  {{-- PROGRESS BREADCRUMB --}}
  <div class="text-sm hm-muted mb-3">
    Book Appointment · <span class="text-slate-700">Select Doctor</span> · <span class="text-slate-900 font-semibold">Select Service & Time</span>
  </div>

  {{-- DOCTOR HEADER --}}
  <div class="hm-card mb-4">
    <div class="flex items-center gap-3">
      <img class="hm-apt-avatar" src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($doctor->name) }}">
      <div class="flex-1">
        <div class="font-semibold">{{ $doctor->name }}</div>
        <small class="hm-muted block">
          {{ $doctor->email }}
          @if($doctor->services->count())
            · {{ $doctor->services->pluck('name')->take(2)->implode(', ') }}@if($doctor->services->count()>2) +{{ $doctor->services->count()-2 }}@endif
          @endif
        </small>
      </div>
      <a href="{{ route('patient.appointments.index') }}" class="btn-soft px-3 py-2 text-sm">Change Doctor</a>
    </div>
  </div>

  {{-- SELECT SERVICE --}}
  <div class="grid lg:grid-cols-2 gap-4 mb-4">
    @foreach ($services as $s)
      <a class="hm-service-card {{ (int)$serviceId === (int)$s->id ? 'is-active' : '' }}"
         href="{{ route('patient.appointments.doctor', ['doctor' => $doctor->id, 'service_id' => $s->id]) }}">
        <div class="w-9 h-9 rounded-lg bg-blue-50 grid place-items-center">🩺</div>
        <div class="flex-1">
          <div class="flex items-center justify-between">
            <div class="font-semibold">{{ $s->name }}</div>
            @if(!is_null($s->price)) <div class="hm-price">${{ number_format($s->price, 2) }}</div> @endif
          </div>
          <small class="hm-muted block">{{ $s->duration ?? 30 }} minutes</small>
        </div>
      </a>
    @endforeach
  </div>

  {{-- SELECT SLOT + BOOK --}}
  <form method="POST" action="{{ route('patient.appointments.store') }}">
    @csrf
    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
    <input type="hidden" name="service_id" id="serviceInput" value="{{ $serviceId }}">
    <input type="hidden" name="time_slot_id" id="slotInput">

    <div class="hm-card">
      <div class="flex items-center justify-between mb-2">
        <div class="font-semibold">Available Time Slots</div>
        <div class="hm-muted text-sm">Pick one to continue</div>
      </div>

      @if ($slots->count())
        <div class="grid md:grid-cols-2 gap-2">
          @foreach ($slots as $slot)
            @php
              // Accept either start_at/end_at or date + start_time/end_time shapes
              $start = $slot->start_at ?? ($slot->date.' '.$slot->start_time);
              $end   = $slot->end_at   ?? ($slot->date.' '.$slot->end_time);
              $s = \Carbon\Carbon::parse($start);
              $e = \Carbon\Carbon::parse($end);
            @endphp
            <label class="hm-apt-card cursor-pointer">
              <div class="flex items-center justify-between">
                <div>
                  <div class="font-medium">{{ $s->format('D, M d, Y') }}</div>
                  <small class="hm-muted">{{ $s->format('h:i A') }} – {{ $e->format('h:i A') }}</small>
                </div>
                <input type="radio" name="slot_pick" value="{{ $slot->id }}" class="slot-radio">
              </div>
            </label>
          @endforeach
        </div>
      @else
        <div class="empty-info">No upcoming slots for this doctor and service yet.</div>
      @endif
    </div>

    <div class="flex items-center gap-2 mt-4">
      <a href="{{ route('patient.appointments.index') }}" class="btn-soft px-4 py-2">Previous</a>
      <button type="submit" class="btn-hm px-5 py-2"
              onclick="return ensureSlot()">Next: Confirm Booking</button>
    </div>
  </form>
</div>

<script>
  const slotRadios = document.querySelectorAll('.slot-radio');
  const slotInput  = document.getElementById('slotInput');
  function ensureSlot(){
    const checked = Array.from(slotRadios).find(r => r.checked);
    if(!checked){ alert('Please select a time slot.'); return false; }
    slotInput.value = checked.value;
    return true;
  }
</script>
@endsection
