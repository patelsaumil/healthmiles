{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Profile • HealthMiles')

@section('content')
<div class="role-patient">
  <div class="container py-5" style="max-width: 860px;">
    <div class="hm-card rounded-16 shadow-soft p-4 mb-4">
      <h2 class="h5 mb-3">Update Profile</h2>

      <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
        @csrf
        @method('patch')

        <div class="col-md-6">
          <label class="form-label fw-semibold">Name</label>
          <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" required>
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-hm px-4">Save changes</button>
        </div>
      </form>
    </div>

    {{-- OPTIONAL: change password only if you have the route set up (Breeze default is password.update) --}}
    @if (Route::has('password.update'))
    <div class="hm-card rounded-16 shadow-soft p-4 mb-4">
      <h2 class="h5 mb-3">Change Password</h2>

      <form method="POST" action="{{ route('password.update') }}" class="row g-3">
        @csrf
        @method('put')

        <div class="col-md-4">
          <label class="form-label fw-semibold">Current Password</label>
          <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">New Password</label>
          <input type="password" name="password" class="form-control" required autocomplete="new-password">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Confirm New Password</label>
          <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-hm px-4">Update password</button>
        </div>
      </form>
    </div>
    @endif

    <div class="hm-card rounded-16 shadow-soft p-4">
      <h2 class="h6 mb-2 text-danger">Danger Zone</h2>
      <p class="hm-muted small mb-3">Permanently delete your account and all related data.</p>

      <form method="POST" action="{{ route('profile.destroy') }}"
            onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-outline-danger">Delete Account</button>
      </form>
    </div>
  </div>
</div>
@endsection
