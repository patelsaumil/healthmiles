@extends('layouts.app')

@section('title','Patient Dashboard • HealthMiles')

@section('content')
<div class="role-patient" style="background:#f6f8fb;">
  <style>
    /* Page-local styles for a tight, consistent UI */
    .pd-wrap{max-width:1220px;margin:0 auto;padding:22px 18px 48px}
    .pd-shell{display:grid;grid-template-columns:240px 1fr;gap:18px}
    @media (max-width:980px){.pd-shell{grid-template-columns:1fr}}

    /* Sidebar */
    .pd-side{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:16px}
    .pd-brand{display:flex;gap:10px;align-items:center;margin-bottom:14px}
    .pd-brand .logo{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;background:#e0e7ff;color:#1e40af;font-weight:800}
    .pd-nav{display:grid;gap:6px}
    .pd-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:#334155;text-decoration:none;border:1px solid transparent}
    .pd-link:hover{background:#f8fafc;border-color:#e2e8f0}
    .pd-link.active{background:#eef2ff;border-color:#c7d2fe;color:#1e3a8a;font-weight:700}

    /* Cards */
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:16px;box-shadow:0 8px 24px rgba(15,23,42,.06)}
    .card-title{font-weight:800;margin:0 0 10px;color:#0f172a}
    .muted{color:#64748b}
    .btn-hm{background:#2563eb;color:#fff;border:none;border-radius:10px;padding:10px 14px;font-weight:700}
    .btn-soft{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:10px 14px;font-weight:700;color:#334155}
    .chip{display:inline-block;padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700}
    .chip-blue{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af}
    .chip-green{background:#ecfdf5;border:1px solid #bbf7d0;color:#065f46}
    .chip-amber{background:#fff7ed;border:1px solid #fed7aa;color:#92400e}

    /* Hero */
    .pd-hero{background:linear-gradient(135deg,#1e3a8a 0%, #2563eb 75%);color:#fff;border-radius:16px;padding:18px 20px;display:flex;align-items:center;justify-content:space-between;gap:12px}
    .pd-hero h2{margin:0;font-weight:900;font-size:clamp(18px,3vw,22px)}
    .pd-hero p{margin:6px 0 0;opacity:.96}

    /* Top row widgets */
    .pd-grid{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;margin-top:14px}
    @media (max-width:1100px){.pd-grid{grid-template-columns:1fr 1fr}}
    @media (max-width:600px){.pd-grid{grid-template-columns:1fr}}

    .stat{display:flex;justify-content:space-between;align-items:center}
    .stat .num{font-weight:900;font-size:26px;color:#0f172a}
    .stat .lbl{font-size:13px;color:#64748b}

    /* Recent panel */
    .pd-panel{margin-top:14px}
    .list{display:grid;gap:10px}
    .row{display:flex;align-items:center;justify-content:space-between;border:1px solid #e5e7eb;border-radius:12px;padding:10px 12px;background:#fff}
    .row .l{display:flex;align-items:center;gap:10px}
    .badge{padding:3px 8px;border-radius:999px;font-size:12px;font-weight:800}
    .b-blue{background:#e0e7ff;color:#1d4ed8}
    .b-green{background:#dcfce7;color:#166534}
    .b-amber{background:#ffedd5;color:#92400e}
  </style>

  <div class="pd-wrap">
    <div class="pd-shell">
      {{-- SIDEBAR --}}
      <aside class="pd-side">
        <div class="pd-brand">
          <div class="logo">🩺</div>
          <strong>HealthMiles</strong>
        </div>
        <nav class="pd-nav">
          <a class="pd-link active" href="{{ route('patient.dashboard') ?? url('/patient') }}">Dashboard</a>
          <a class="pd-link" href="{{ route('patient.appointments.index') }}"><span>Find a Doctor</span></a>
          <a class="pd-link" href="{{ route('patient.appointments.mine') }}"><span>My Appointments</span></a>
          <a class="pd-link" href="{{ route('profile.edit') }}"><span>Profile</span></a>
          <a class="pd-link" href="{{ route('contact') }}"><span>Help</span></a>
        </nav>
      </aside>

      {{-- MAIN --}}
      <section>
        {{-- HERO --}}
        <div class="pd-hero card">
          <div>
            <h2>Welcome back, {{ Str::of(auth()->user()->name ?? 'Patient')->headline() }}!</h2>
            @php
              $upcomingCount = $upcomingCount ?? 0;
            @endphp
            <p>You have <strong>{{ $upcomingCount }}</strong> upcoming appointment{{ $upcomingCount==1?'':'s' }}.</p>
          </div>
          <div>
            <a href="{{ route('patient.appointments.index') }}" class="btn-hm">Book New</a>
          </div>
        </div>

        {{-- TOP WIDGETS --}}
        @php
          $totalAppointments = $totalAppointments ?? 0;
          $completedCount = $completedCount ?? 0;
          $notifications = $notifications ?? [];
        @endphp

        <div class="pd-grid">
          <div class="card stat">
            <div>
              <div class="lbl">Total Appointments</div>
              <div class="num">{{ $totalAppointments }}</div>
            </div>
            <span class="chip chip-blue">All</span>
          </div>

          <div class="card stat">
            <div>
              <div class="lbl">Upcoming</div>
              <div class="num">{{ $upcomingCount }}</div>
            </div>
            <span class="chip chip-amber">Next</span>
          </div>

          <div class="card stat">
            <div>
              <div class="lbl">Completed</div>
              <div class="num">{{ $completedCount }}</div>
            </div>
            <span class="chip chip-green">Done</span>
          </div>

          <div class="card">
            <div class="card-title">Quick Actions</div>
            <div class="d-flex flex-wrap gap-2">
              <a href="{{ route('patient.appointments.index') }}" class="btn-hm">Book</a>
              <a href="{{ route('patient.appointments.mine') }}" class="btn-soft">Manage</a>
              <a href="{{ route('profile.edit') }}" class="btn-soft">Profile</a>
            </div>
          </div>
        </div>

        {{-- SECOND ROW: Upcoming / Notifications --}}
        <div class="pd-grid" style="margin-top:14px">
          {{-- Upcoming --}}
          <div class="card">
            <div class="card-title">Upcoming Appointments</div>
            @php
              // Expecting $upcoming to be a collection of appointment models with ->doctor, ->service, ->timeSlot
              $upcoming = $upcoming ?? collect();
            @endphp

            @if($upcoming->isEmpty())
              <div class="muted">No upcoming appointments yet.
                <a href="{{ route('patient.appointments.index') }}">Book one</a>.
              </div>
            @else
              <div class="list">
                @foreach($upcoming->take(5) as $a)
                  <div class="row">
                    <div class="l">
                      <span class="badge b-blue">
                        {{ \Carbon\Carbon::parse(optional($a->timeSlot)->start_at ?? optional($a->timeSlot)->slot_date ?? optional($a->timeSlot)->date)->format('M d') }}
                      </span>
                      <div>
                        <div><strong>{{ optional($a->doctor)->name }}</strong></div>
                        <div class="muted" style="font-size:13px">
                          {{ optional($a->service)->name ?? 'Consultation' }}
                        </div>
                      </div>
                    </div>
                    <a class="btn-soft" href="{{ route('patient.appointments.mine') }}">Details</a>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

          {{-- Notifications --}}
          <div class="card">
            <div class="card-title">Notifications</div>
            @if(empty($notifications))
              <div class="muted">No new notifications.</div>
            @else
              <div class="list">
                @foreach($notifications as $n)
                  <div class="row">
                    <div class="l">
                      <span class="badge b-amber">Info</span>
                      <div>{{ $n['text'] ?? '' }}</div>
                    </div>
                    @if(!empty($n['action_url']))
                      <a class="btn-soft" href="{{ $n['action_url'] }}">Open</a>
                    @endif
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        {{-- Recent / History --}}
        <div class="pd-panel card">
          <div class="card-title">Recent History</div>
          @php $recent = $recent ?? collect(); @endphp
          @if($recent->isEmpty())
            <div class="muted">No recent visits yet.</div>
          @else
            <div class="list">
              @foreach($recent->take(6) as $r)
                <div class="row">
                  <div class="l">
                    <span class="badge b-green">
                      {{ \Carbon\Carbon::parse($r->created_at)->format('M d') }}
                    </span>
                    <div>
                      <div><strong>{{ optional($r->doctor)->name }}</strong></div>
                      <div class="muted" style="font-size:13px">{{ optional($r->service)->name ?? 'Consultation' }}</div>
                    </div>
                  </div>
                  <span class="muted" style="font-size:13px">
                    {{ ucfirst($r->status ?? 'completed') }}
                  </span>
                </div>
              @endforeach
            </div>
          @endif
        </div>

      </section>
    </div>
  </div>
</div>
@endsection
