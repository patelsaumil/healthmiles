@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h3 class="mb-3">Edit Service</h3>
  @if($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
  <form method="post" action="{{ route('admin.services.update',$service) }}">@csrf @method('PUT')
    @include('admin.services.partials.form')
    <div class="mt-3">
      <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
      <button class="btn btn-primary">Update</button>
    </div>
  </form>
</div>
@endsection
