@extends('layouts.app')
@section('title', 'Book Appointment — Find a Doctor')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 role-patient">

  {{-- HERO --}}
  <div class="hm-hero rounded-16 shadow-soft mb-5">
    <div class="flex items-center justify-between">
      <div>
        <div class="hm-hero__title">Book a New Appointment</div>
        <div class="hm-hero__subtitle text-sm">Step 1 · Choose a doctor (you can filter by service)</div>
      </div>
      <a href="{{ route('patient.appointments.mine') }}" class="btn-ghost px-4 py-2 text-sm font-semibold">My Appointments</a>
    </div>
  </div>

  {{-- FILTER --}}
  <form method="GET" class="hm-card mb-4">
    <div class="font-semibold mb-2">Filter by Service</div>
    <div class="flex items-center gap-2">
      <select name="service_id" class="form-select">
        <option value="">All Services</option>
        @foreach($services as $srv)
          <option value="{{ $srv->id }}" @selected((string)request('service_id') === (string)$srv->id)>{{ $srv->name }}</option>
        @endforeach
      </select>
      <button class="btn-soft px-4 py-2">Apply Filter</button>
      <a class="btn-soft px-4 py-2" href="{{ route('patient.appointments.index') }}">Reset</a>
    </div>
  </form>

  {{-- DOCTORS GRID --}}
  @if ($doctors->count())
    <div class="doctors-grid">
      @foreach ($doctors as $doctor)
        <div class="hm-card doctor-card">
          <div class="top">
            <img class="hm-apt-avatar" src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($doctor->name) }}" alt="{{ $doctor->name }}">
            <div>
              <div class="font-semibold">{{ $doctor->name }}</div>
              <small class="hm-muted">{{ $doctor->email }}</small>
            </div>
          </div>

          <div class="services">
            @forelse ($doctor->services as $s)
              <span class="hm-chip mr-1 mb-1 inline-block">{{ $s->name }}</span>
            @empty
              <span class="hm-badge">No services linked</span>
            @endforelse
          </div>

          <a href="{{ route('patient.appointments.doctor', ['doctor' => $doctor->id, 'service_id' => request('service_id')]) }}"
             class="btn-ghost w-full mt-1 py-2 text-center font-semibold">
            View Slots
          </a>
        </div>
      @endforeach
    </div>

    <div class="mt-6">{{ $doctors->withQueryString()->links() }}</div>
  @else
    <div class="empty-info">
      No doctors found for the selected filter. Try choosing a different service.
    </div>
  @endif
</div>
@endsection
