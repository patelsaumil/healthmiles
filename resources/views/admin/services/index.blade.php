@extends('layouts.app')

@section('content')
<div class="container py-4">

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Services</h3>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Add Service</a>
  </div>

  <form method="get" class="mb-3">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search name/description..."
             value="{{ $q }}">
      <button class="btn btn-outline-secondary">Search</button>
      @if($q)
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-dark">Clear</a>
      @endif
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Status</th>
          <th>Doctors</th>
          <th>Updated</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($services as $svc)
          <tr>
            <td>{{ $svc->id }}</td>
            <td>
              <a href="{{ route('admin.services.show', $svc) }}" class="text-decoration-none">
                {{ $svc->name }}
              </a>
            </td>
            <td>
              <span class="badge bg-{{ $svc->status === 'active' ? 'success' : 'secondary' }}">
                {{ ucfirst($svc->status) }}
              </span>
            </td>
            <td>{{ $svc->doctors_count }}</td>
            <td>{{ $svc->updated_at?->diffForHumans() }}</td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.services.edit', $svc) }}">Edit</a>
              <form action="{{ route('admin.services.destroy', $svc) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete this service?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-4">No services found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $services->links() }}
</div>
@endsection
