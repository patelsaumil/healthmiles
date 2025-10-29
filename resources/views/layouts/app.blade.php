<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'HealthMiles') }}</title>

  {{-- Breeze / Vite --}}
  @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    // ensure the patient theme is always available
    'resources/css/healthmiles.patient.css'
  ])

  {{-- Minimal shell styles for public header/footer used on landing pages --}}
  <style>
    :root{
      --hm-ink:#0f172a;--hm-blue:#14337a;--hm-blueDark:#0d1f5a;--hm-link:#334155;
      --hm-border:#e5e7eb;--hm-muted:#64748b;--hm-wrap:1120px;--hm-primary:#2563eb;--hm-primary-700:#1d4ed8;
    }

    .hm-nav{background:#fff;border-bottom:3px solid #133a9e;}
    .hm-nav .wrap{max-width:var(--hm-wrap);margin:0 auto;padding:12px 24px;display:flex;align-items:center;justify-content:space-between}
    .hm-brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--hm-ink);font-weight:700}
    .hm-logo{width:36px;height:36px;border-radius:10px;background:#0f2b78;display:grid;place-items:center}
    .hm-links a{margin:0 12px;text-decoration:none;color:#475569}
    .hm-links a.active{color:#1d4ed8;font-weight:600}

    /* ---------- Auth actions (Login / Register / Doctor Register / Dashboard / Logout) ---------- */
    .hm-auth{display:flex;align-items:center;gap:10px;}
    .hm-auth a{color:#475569;text-decoration:none;font-weight:600}
    .hm-auth a:hover{color:#0f172a}

    /* Button-style links */
    .btn-register{
      background:var(--hm-primary);color:#fff;border:1px solid var(--hm-primary);
      border-radius:10px;padding:8px 14px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px;
      box-shadow:0 4px 12px rgba(37,99,235,.15);
    }
    .btn-register:hover{background:var(--hm-primary-700);border-color:var(--hm-primary-700);color:#fff}

    .btn-register-outline{
      color:var(--hm-primary);border:2px solid var(--hm-primary);background:#fff;
      border-radius:10px;padding:7px 13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px;
    }
    .btn-register-outline:hover{background:var(--hm-primary);color:#fff}

    .btn-link{
      background:none;border:0;color:#475569;cursor:pointer;font-weight:600;padding:0;
    }
    .btn-link:hover{color:#0f172a}

    .hm-footer{background:#0b1220;color:#cbd5e1;margin-top:40px}
    .hm-footer .wrap{max-width:var(--hm-wrap);margin:0 auto;padding:36px 24px}
    .hm-footgrid{display:grid;grid-template-columns:2fr 1fr 1fr 1.2fr;gap:24px}
    .hm-footlinks{list-style:none;padding:0;margin:0}
    .hm-footlinks li{margin:8px 0}
    .hm-footbottom{display:flex;justify-content:space-between;align-items:center;border-top:1px solid #172033;margin-top:24px;padding-top:16px;font-size:14px}
    @media (max-width:980px){.hm-footgrid{grid-template-columns:1fr 1fr}.hm-footbottom{flex-direction:column;gap:10px}}

    /* tiny alert styles */
    .hm-alert{border:1px solid var(--hm-border);border-radius:12px;padding:.75rem 1rem;margin:.75rem 0}
    .hm-alert--ok{background:#ecfdf5;border-color:#bbf7d0;color:#065f46}
    .hm-alert--err{background:#fff1f2;border-color:#fecdd3;color:#9f1239}
  </style>
</head>

<body class="font-sans antialiased">
  {{-- NAV --}}
  @guest
      @include('partials.public-nav')
  @else
      @includeIf('layouts.navigation')
  @endguest

  {{-- Page Heading (Breeze section/slot support) --}}
  @if (isset($header))
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">{{ $header }}</div>
    </header>
  @elseif (View::hasSection('header'))
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">@yield('header')</div>
    </header>
  @endif

  {{-- CONTENT --}}
  <main>
    {{-- flash + errors --}}
    <div class="container my-3">
      @if (session('success'))
        <div class="hm-alert hm-alert--ok">{{ session('success') }}</div>
      @endif

      @if ($errors->any())
        <div class="hm-alert hm-alert--err">
          <strong>There was a problem:</strong>
          <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>

    @yield('content')
  </main>

  {{-- FOOTER --}}
  @guest
      @include('partials.public-footer')
  @endguest
</body>
</html>
