@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h3 class="mb-3">Add Slot — {{ $doctor->name }}</h3>
  @if($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif

  <form method="post" action="{{ route('admin.doctors.timeslots.store',$doctor) }}">@csrf
    @include('admin.timeslots.partials.form', ['slot'=>null])
    <div class="mt-3">
      <a class="btn btn-secondary" href="{{ route('admin.doctors.timeslots.index',$doctor) }}">Cancel</a>
      <button class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
@endsection
