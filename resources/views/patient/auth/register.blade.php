@extends('layouts.app')
@section('title','Patient Registration')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">Create Patient Account</h4>
        </div>
        <div class="card-body p-4">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          <form method="POST" action="{{ route('patient.register.store') }}">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full Name*</label>
                <input name="name" class="form-control" value="{{ old('name') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email*</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Password*</label>
                <input type="password" name="password" class="form-control" minlength="8" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Confirm Password*</label>
                <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
              </div>
            </div>
            <div class="d-grid mt-4">
              <button class="btn btn-primary btn-lg">Create Account</button>
            </div>
          </form>
        </div>
        <div class="card-footer text-center">
          <small>Already have an account? <a href="{{ route('login') }}">Login</a></small>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
