{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Profile • HealthMiles')

@section('content')
<div class="role-patient">
  <div class="container py-4 hm-shell">

    {{-- Page header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
      <a href="{{ url()->previous() }}" class="btn btn-soft">← Back</a>
      <h1 class="h4 mb-0">Profile</h1>
      <div></div>
    </div>

    {{-- Alerts --}}
    @if (session('status') === 'profile-updated')
      <div class="hm-alert hm-alert--ok">Your profile has been updated.</div>
    @elseif (session('status') === 'password-updated')
      <div class="hm-alert hm-alert--ok">Password updated successfully.</div>
    @elseif (session('status'))
      <div class="hm-alert hm-alert--ok">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
      <div class="hm-alert hm-alert--err">
        <strong>Fix the following:</strong>
        <ul class="mb-0 mt-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row g-3">
      {{-- Account details --}}
      <div class="col-xl-7">
        <div class="hm-card p-4 rounded-16 shadow-soft hm-section">
          <div class="d-flex align-items-center mb-3">
            <div class="hm-avatar me-3">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div>
              <h2 class="h5 mb-0">Account Details</h2>
              <div class="hm-muted small">Manage your name & email used for login and notifications.</div>
            </div>
          </div>

          <form method="post" action="{{ route('profile.update') }}" class="hm-form">
            @csrf
            @method('patch')

            <div class="mb-3">
              <label class="form-label fw-semibold">Full Name</label>
              <input id="name" name="name" type="text" class="form-control" value="{{ old('name', auth()->user()->name) }}" required autocomplete="name">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <input id="email" name="email" type="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required autocomplete="username">
            </div>

            <div class="d-flex justify-content-end">
              <button class="btn btn-hm px-4">Save changes</button>
            </div>
          </form>
        </div>
      </div>

      {{-- Password --}}
      <div class="col-xl-5">
        <div class="hm-card p-4 rounded-16 shadow-soft hm-section">
          <h2 class="h6 mb-1">Change Password</h2>
          <div class="hm-muted small mb-3">Use a strong, unique password (min 8 characters).</div>

          <form method="post" action="{{ route('password.update') }}" class="hm-form">
            @csrf
            @method('put')

            <div class="mb-3">
              <label class="form-label fw-semibold">Current Password</label>
              <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">New Password</label>
              <input id="password" name="password" type="password" class="form-control" autocomplete="new-password" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Confirm New Password</label>
              <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
            </div>

            <div class="d-flex justify-content-end">
              <button class="btn btn-hm px-4">Update password</button>
            </div>
          </form>
        </div>

        {{-- Danger Zone --}}
        <div class="hm-card p-4 rounded-16 shadow-soft mt-3 hm-section">
          <h2 class="h6 text-danger mb-2">Danger Zone</h2>
          <div class="hm-muted small mb-3">Permanently delete your account and all related data.</div>

          <form method="post" action="{{ route('profile.destroy') }}"
                onsubmit="return confirm('This will permanently delete your account. Continue?')">
            @csrf
            @method('delete')
            <button class="btn btn-outline-danger w-100">Delete Account</button>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
