@extends('layouts.app')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">{{ $doctor->name }}</h3>
    <div class="d-flex gap-2">
      <a class="btn btn-warning" href="{{ route('admin.doctors.edit', $doctor) }}">Edit</a>
      <a class="btn btn-secondary" href="{{ route('admin.doctors.index') }}">Back</a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <p><strong>Email:</strong> {{ $doctor->email }}</p>
          <p><strong>Phone:</strong> {{ $doctor->phone }}</p>
          <p><strong>Specialty:</strong> {{ $doctor->specialty }}</p>
          <p><strong>Active:</strong> {!! $doctor->active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</p>
          <p><strong>Bio:</strong><br>{{ $doctor->bio }}</p>
          @if($doctor->photo_path)
            <p class="mb-0"><strong>Photo:</strong> <code>{{ $doctor->photo_path }}</code></p>
          @endif
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Upcoming Time Slots</span>
          <a class="btn btn-sm btn-primary" href="{{ route('admin.doctors.timeslots.index', $doctor) }}">Manage Time Slots</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table mb-0">
              <thead><tr><th>Date</th><th>Start</th><th>End</th><th>Booked?</th></tr></thead>
              <tbody>
                @forelse($doctor->timeSlots as $ts)
                  <tr>
                    <td>{{ \Carbon\Carbon::parse($ts->slot_date)->format('M d, Y') }}</td>
                    <td>{{ substr($ts->start_time,0,5) }}</td>
                    <td>{{ substr($ts->end_time,0,5) }}</td>
                    <td>{!! $ts->is_booked ? '<span class="badge bg-danger">Yes</span>' : '<span class="badge bg-success">No</span>' !!}</td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center py-3">No time slots.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">Services</div>
        <div class="card-body">
          @forelse($doctor->services as $s)
            <span class="badge bg-primary mb-1">{{ $s->name }}</span>
          @empty
            <p class="text-muted mb-0">No services linked.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
