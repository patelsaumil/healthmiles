<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'HealthMiles')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Your shared styles (admin/doctor/patient) --}}
    <link href="{{ asset('css/healthmiles.css') }}" rel="stylesheet">

    @stack('head')
</head>

@php
    /** @var \App\Models\User|null $authUser */
    $authUser = auth()->user();
    $role = $authUser->role ?? 'guest';

    // Choose a dashboard route per role (fallback to generic dashboard)
    $dashboardRoute = match ($role) {
        'admin'   => 'admin.dashboard',
        'doctor'  => 'doctor.dashboard',
        'patient' => 'patient.dashboard',
        default   => 'dashboard',
    };
@endphp

<body class="bg-soft">

@guest
    {{-- ===================== PUBLIC PAGES (Home / About / Contact) ===================== --}}
    @include('partials.public-nav')

    <main>
        {{-- Flash messages --}}
        @if (session('status'))
            <div class="container py-3">
                <div class="alert alert-success mb-0">{{ session('status') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.public-footer')

@else
    {{-- ===================== AUTHENTICATED APP LAYOUT ===================== --}}
    <div class="d-flex min-vh-100">

        {{-- Optional sidebar slot --}}
        @hasSection('sidebar')
            <aside class="hm-sidebar">
                @yield('sidebar')
            </aside>
        @endif

        <main class="flex-grow-1">
            {{-- Top App Bar --}}
            <nav class="navbar navbar-light bg-white border-bottom sticky-top px-3">
                <div class="d-flex align-items-center gap-3">
                    {{-- Brand goes to the user's dashboard per role --}}
                    <a href="{{ route($dashboardRoute) }}"
                       class="navbar-brand d-flex align-items-center gap-2 mb-0 text-decoration-none text-dark">
                        <span class="text-primary d-inline-flex align-items-center justify-content-center rounded-3"
                              style="width:28px;height:28px;background:rgba(37,99,235,.1)">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </span>
                        <strong>HealthMiles</strong>
                    </a>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small">
                        Hello {{ $authUser->name ?? 'User' }}
                    </span>

                    {{-- Avatar from initials --}}
                    <img src="https://api.dicebear.com/9.x/initials/svg?seed={{ urlencode($authUser->name ?? 'HM') }}"
                         class="rounded-circle" style="width:32px;height:32px" alt="Avatar">

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>

            {{-- Flash messages --}}
            @if (session('status'))
                <div class="container-fluid mt-3">
                    <div class="alert alert-success">{{ session('status') }}</div>
                </div>
            @endif

            {{-- Page Content --}}
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </main>
    </div>
@endguest

{{-- Bootstrap Bundle --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
