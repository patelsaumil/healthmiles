@extends('layouts.app')

@section('title','Book Appointment • HealthMiles')

@section('content')
<div class="role-patient" style="background:#f6f8fb;">
  <style>
    /* Page-local polish */
    .apt-wrap{max-width:1120px;margin:0 auto;padding:28px 18px 48px}
    .apt-hero{
      background:linear-gradient(135deg,#1e3a8a 0%, #2563eb 70%);
      color:#fff;border-radius:18px;padding:20px 22px;margin-bottom:16px;
      box-shadow:0 10px 30px rgba(15,23,42,.12)
    }
    .apt-hero h2{margin:0;font-weight:800;font-size:clamp(18px,3vw,22px)}
    .apt-hero p{margin:6px 0 0;opacity:.95}

    .filter-bar{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px 14px 8px;margin-bottom:16px}
    .filter-grid{display:grid;grid-template-columns:1.1fr 1fr auto auto;gap:10px;align-items:center}
    .filter-grid .form-control,.filter-grid .form-select{height:42px}

    .btn-hm{background:#2563eb;color:#fff;border:none;border-radius:10px;padding:10px 14px;font-weight:700}
    .btn-soft{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:10px 14px;font-weight:700;color:#334155}
    .btn-hm:hover{filter:brightness(.95)}

    .grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px}
    @media (max-width:1200px){.grid{grid-template-columns:repeat(4,1fr)}}
    @media (max-width:980px){.grid{grid-template-columns:repeat(3,1fr)} .filter-grid{grid-template-columns:1fr 1fr 1fr auto}}
    @media (max-width:720px){.grid{grid-template-columns:repeat(2,1fr)} .filter-grid{grid-template-columns:1fr 1fr;gap:8px}}
    @media (max-width:460px){.grid{grid-template-columns:1fr}}

    .card{
      background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:16px;text-align:center;
      box-shadow:0 8px 24px rgba(15,23,42,.06)
    }
    .card img{width:86px;height:86px;border-radius:50%;display:block;margin:6px auto 10px}
    .doc-name{font-weight:800;margin:6px 0 2px;text-transform:capitalize}
    .doc-sub{color:#64748b;font-size:14px;margin:0 0 10px}
    .chips{display:flex;flex-wrap:wrap;gap:6px;justify-content:center;min-height:28px;margin-bottom:12px}
    .chip{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700}
    .card .btn{width:100%}
  </style>

  <div class="apt-wrap">
    {{-- HERO --}}
    <div class="apt-hero">
      <h2>Book a New Appointment</h2>
      <p>Step 1 — Choose a doctor (you can filter by service)</p>
    </div>

    {{-- FILTERS --}}
    <form class="filter-bar" method="GET" action="{{ route('patient.appointments.index') }}">
      <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('patient.appointments.mine') }}" class="btn-soft">My Appointments</a>
      </div>

      <div class="filter-grid">
        {{-- Service --}}
        <select class="form-select" name="service_id">
          <option value="">All Services</option>
          @foreach($services as $s)
            <option value="{{ $s->id }}" {{ (int)$selectedServiceId === (int)$s->id ? 'selected' : '' }}>
              {{ $s->name }}
            </option>
          @endforeach
        </select>

        {{-- Search --}}
        <input class="form-control" type="text" name="q" value="{{ request('q') }}" placeholder="Search by doctor name…">

        <button class="btn-hm" type="submit">Apply Filter</button>
        <a class="btn-soft" href="{{ route('patient.appointments.index') }}">Reset</a>
      </div>
    </form>

    {{-- DOCTORS GRID --}}
    @if($doctors->count() === 0)
      <div class="alert alert-info">No doctors found for the current filter.</div>
    @else
      <div class="grid">
        @foreach($doctors as $doc)
          <div class="card">
            <img src="https://api.dicebear.com/9.x/avataaars/svg?seed={{ urlencode($doc->name) }}" alt="Avatar">
            <div class="doc-name">{{ $doc->name }}</div>
            <div class="doc-sub">{{ $doc->specialty ?? 'General Practitioner' }}</div>

            <div class="chips">
              @forelse(($doc->services ?? collect())->take(3) as $svc)
                <span class="chip">{{ $svc->name }}</span>
              @empty
                <span class="chip" style="opacity:.75">No services</span>
              @endforelse
            </div>

            <a class="btn btn-hm"
               href="{{ route('patient.appointments.doctor', $doc) }}?service_id={{ $selectedServiceId ?? '' }}">
              Select Doctor
            </a>
          </div>
        @endforeach
      </div>

      <div class="mt-3">
        {{ $doctors->withQueryString()->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
