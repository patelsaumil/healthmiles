@extends('layouts.app')
@section('content')
<div class="container py-4">
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  <div class="d-flex justify-content-between mb-3">
    <h3 class="mb-0">Time Slots — {{ $doctor->name }}</h3>
    <div>
      <a class="btn btn-secondary" href="{{ route('admin.doctors.show',$doctor) }}">Back to Doctor</a>
      <a class="btn btn-primary" href="{{ route('admin.doctors.timeslots.create',$doctor) }}">+ Add Slot</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead><tr><th>Date</th><th>Start</th><th>End</th><th>Booked?</th><th class="text-end">Actions</th></tr></thead>
      <tbody>
        @forelse($slots as $s)
        <tr>
          <td>{{ \Carbon\Carbon::parse($s->slot_date)->format('M d, Y') }}</td>
          <td>{{ substr($s->start_time,0,5) }}</td>
          <td>{{ substr($s->end_time,0,5) }}</td>
          <td>{!! $s->is_booked ? '<span class="badge bg-danger">Yes</span>' : '<span class="badge bg-success">No</span>' !!}</td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.timeslots.show',$s) }}">View</a>
            <a class="btn btn-sm btn-warning" href="{{ route('admin.timeslots.edit',$s) }}">Edit</a>
            <form class="d-inline" method="post" action="{{ route('admin.timeslots.destroy',$s) }}" onsubmit="return confirm('Delete this slot?');">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center py-4">No time slots yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $slots->links() }}
</div>
@endsection
