@extends('layouts.app')
@section('title','Appointment Booked')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 role-patient">
  <div class="text-center mb-4">
    <div class="inline-flex w-12 h-12 rounded-full bg-emerald-100 items-center justify-center text-emerald-700 text-xl">✓</div>
    <h1 class="text-xl font-semibold mt-2">Appointment Booked Successfully!</h1>
    <p class="hm-muted">We’ve sent you a confirmation email with all the details.</p>
  </div>

  <div class="hm-card p-4">
    <div class="flex items-center gap-3">
      <img class="hm-apt-avatar" src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($appointment->doctor->name) }}">
      <div class="flex-1">
        <div class="font-semibold">{{ $appointment->doctor->name }}</div>
        <small class="hm-muted block">{{ $appointment->service->name }}</small>
      </div>
      @php
        $start = optional($appointment->timeSlot)->start_at ?? (optional($appointment->timeSlot)->date.' '.optional($appointment->timeSlot)->start_time);
        $end   = optional($appointment->timeSlot)->end_at   ?? (optional($appointment->timeSlot)->date.' '.optional($appointment->timeSlot)->end_time);
        $s = $start ? \Carbon\Carbon::parse($start) : null;
        $e = $end   ? \Carbon\Carbon::parse($end)   : null;
      @endphp
      <div class="text-right">
        <div class="font-medium">{{ $s? $s->format('D, M d, Y'):'—' }}</div>
        <small class="hm-muted">{{ $s? $s->format('h:i A'):'' }}@if($e) – {{ $e->format('h:i A') }} @endif</small>
      </div>
    </div>
  </div>

  <div class="grid sm:grid-cols-2 gap-3 mt-4">
    <a href="{{ route('patient.appointments.mine') }}" class="btn-hm px-5 py-2 text-center">View My Appointments</a>
    <a href="{{ route('patient.appointments.index') }}" class="btn-soft px-5 py-2 text-center">Book Another Appointment</a>
  </div>

  <div class="hm-card mt-4 text-sm">
    <div class="font-semibold mb-1">What’s Next?</div>
    <ul class="list-disc list-inside hm-muted space-y-1">
      <li>Check your email for confirmation.</li>
      <li>Set reminders so you don’t miss your appointment.</li>
      <li>Arrive 10 minutes early for check-in.</li>
    </ul>
  </div>
</div>
@endsection
