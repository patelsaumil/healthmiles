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

    {{-- Custom HealthMiles styles --}}
    <link href="{{ asset('css/healthmiles.css') }}" rel="stylesheet">
    @stack('head')
</head>

<body class="bg-soft">
    <div class="d-flex min-vh-100">
        {{-- Sidebar (if defined) --}}
        @hasSection('sidebar')
            <aside class="hm-sidebar">
                @yield('sidebar')
            </aside>
        @endif

        <main class="flex-grow-1">
            {{-- Top navbar --}}
            <nav class="navbar navbar-light bg-white border-bottom sticky-top px-3">
                {{-- Left: Clickable logo (redirects to universal dashboard) --}}
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('dashboard') }}"
                       class="navbar-brand d-flex align-items-center gap-2 mb-0 text-decoration-none text-dark">
                        <i class="bi bi-heart-pulse-fill text-primary"></i>
                        <strong>HealthMiles</strong>
                    </a>
                </div>

                {{-- Right: user info + logout --}}
                @php $user = auth()->user(); @endphp
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small">Hello {{ $user->name ?? 'Guest' }}</span>
                    <img src="https://api.dicebear.com/9.x/initials/svg?seed={{ urlencode($user->name ?? 'HM') }}"
                         class="rounded-circle" style="width:32px;height:32px" alt="">

                    {{-- Logout button --}}
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit"
                                class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </nav>

            {{-- Page content --}}
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Bootstrap Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
