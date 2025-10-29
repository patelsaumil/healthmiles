@extends('layouts.app')

@section('title', 'Doctor Registration • HealthMiles')

@section('content')
<div class="role-patient" style="background:#f6f8fb;">
  <style>
    
    .hm-hero--bar {
      background: linear-gradient(135deg, #1e40af 0%, #2563eb 70%);
      color: #fff;
      border-radius: 18px;
      padding: 22px 24px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, .12);
    }
    .hm-hero--bar h1 { font-size: clamp(22px, 3.2vw, 30px); line-height: 1.2; margin: 0 0 6px }
    .hm-hero--bar p { margin: 0; opacity: .95 }
    .hm-container { max-width: 820px; margin: 0 auto; padding: 28px 20px 56px }
    .hm-note { text-align:center; color:#64748b }
    .btn-ghost-sm {
      border: 1px solid rgba(255,255,255,.35); color:#fff; text-decoration:none;
      padding: 8px 12px; border-radius: 10px; font-weight: 600; display:inline-block
    }
    .btn-ghost-sm:hover { border-color:#fff; color:#fff }
  </style>

  <div class="hm-container">

    {{-- Top bar / hero --}}
    <div class="hm-hero--bar mb-4">
      <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
        <div>
          <h1>Join HealthMiles as a Doctor</h1>
          <p>Create your account and start accepting appointments.</p>
        </div>
        <a href="{{ route('login') }}" class="btn-ghost-sm">Already have an account? Login</a>
      </div>
    </div>

    {{-- Messages --}}
    @if (session('status'))
      <div class="alert alert-success rounded-16 shadow-sm">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger rounded-16 shadow-sm">
        <strong>Please fix the following:</strong>
        <ul class="mb-0 mt-2">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Form card --}}
    <div class="hm-card rounded-16 shadow-soft p-4 p-md-4" style="background:#fff;">
      <form method="POST" action="{{ route('doctor.register.store') }}" class="row g-3">
        @csrf

        <div class="col-12">
          <label class="form-label fw-semibold">Full Name</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
        </div>

        <div class="col-md-7">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
        </div>

        <div class="col-md-5">
          <label class="form-label fw-semibold">Phone (optional)</label>
          <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Specialty (optional)</label>
          <input type="text" name="specialty" value="{{ old('specialty') }}" class="form-control" placeholder="Cardiology, Family Medicine…">
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Short Bio (optional)</label>
          <input type="text" name="bio" value="{{ old('bio') }}" class="form-control" placeholder="e.g., 10+ yrs in primary care">
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Password</label>
          <input type="password" name="password" class="form-control" required autocomplete="new-password">
          <div class="form-text hm-muted">Min 8 characters (Breeze default).</div>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
        </div>

        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="terms" required>
            <label class="form-check-label hm-muted" for="terms">
              I agree to the <a href="#" class="text-decoration-none">Terms</a> & <a href="#" class="text-decoration-none">Privacy Policy</a>.
            </label>
          </div>
        </div>

        <div class="col-12 d-flex justify-content-center mt-2">
          <button type="submit" class="btn btn-hm px-4 py-2">Create Doctor Account</button>
        </div>
      </form>
    </div>

    {{-- Small explanatory note --}}
    <div class="hm-note small mt-3">
      This creates a <strong>users</strong> row with <strong>role = doctor</strong> and a linked <strong>doctors</strong> row.
    </div>
  </div>
</div>
@endsection
