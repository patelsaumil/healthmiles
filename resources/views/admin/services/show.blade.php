@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">{{ $service->name }}</h3>
    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-outline-primary">Edit Service</a>
  </div>

  <div class="mb-3">
    <span class="badge bg-{{ $service->status === 'active' ? 'success' : 'secondary' }}">
      {{ ucfirst($service->status) }}
    </span>
  </div>

  <div class="mb-4">
    <h6>Description</h6>
    <p class="mb-0">{{ $service->description ?: '—' }}</p>
  </div>

  <div>
    <h6>Doctors offering this service ({{ $service->doctors->count() }})</h6>
    @if($service->doctors->isEmpty())
      <p class="text-muted mb-0">No doctors linked yet.</p>
    @else
      <ul class="list-group">
        @foreach($service->doctors as $doc)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong>{{ $doc->name }}</strong>
              <span class="text-muted"> — {{ $doc->specialty }}</span>
              <div class="small text-muted">{{ $doc->email }}</div>
            </div>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.doctors.edit', $doc) }}">
              Manage Doctor
            </a>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</div>
@endsection
