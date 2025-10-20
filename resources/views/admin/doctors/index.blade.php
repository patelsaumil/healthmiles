@extends('layouts.app')

@section('content')
<div class="container py-4">
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Doctors</h3>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">+ Add Doctor</a>
  </div>

  <form class="mb-3" method="get">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search name, email, phone, specialty" value="{{ $search }}">
      <button class="btn btn-outline-secondary">Search</button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Specialty</th>
          <th>Active</th>
          <th>Services</th>
          <th>Time Slots</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($doctors as $doc)
          <tr>
            <td>{{ $doc->id }}</td>
            <td><a href="{{ route('admin.doctors.show', $doc) }}">{{ $doc->name }}</a></td>
            <td>{{ $doc->email }}</td>
            <td>{{ $doc->specialty }}</td>
            <td>{!! $doc->active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
            <td>{{ $doc->services_count }}</td>
            <td>{{ $doc->time_slots_count }}</td>
            <td class="text-end">
              <a href="{{ route('admin.doctors.edit', $doc) }}" class="btn btn-sm btn-warning">Edit</a>
              <form action="{{ route('admin.doctors.destroy', $doc) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this doctor?');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center py-4">No doctors found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $doctors->links() }}
</div>
@endsection
