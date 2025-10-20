@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3 class="mb-3">Add Service</h3>

  <form method="POST" action="{{ route('admin.services.store') }}">
    @csrf
    @include('admin.services.partials.form')

    <div class="mt-3">
      <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
      <button class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
@endsection
