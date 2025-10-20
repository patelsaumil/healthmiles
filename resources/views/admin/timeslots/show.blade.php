@extends('layouts.app')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between mb-3">
    <h3 class="mb-0">Slot — {{ $doctor->name }}</h3>
    <a class="btn btn-secondary" href="{{ route('admin.doctors.timeslots.index',$doctor) }}">Back</a>
  </div>
  <div class="card">
    <div class="card-body">
      <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($slot->slot_date)->format('M d, Y') }}</p>
      <p><strong>Start:</strong> {{ substr($slot->start_time,0,5) }}</p>
      <p><strong>End:</strong> {{ substr($slot->end_time,0,5) }}</p>
      <p><strong>Booked:</strong> {!! $slot->is_booked ? '<span class="badge bg-danger">Yes</span>' : '<span class="badge bg-success">No</span>' !!}</p>
    </div>
  </div>
</div>
@endsection
