{{-- resources/views/partials/public-nav.blade.php --}}
<header class="hm-nav">
  <div class="wrap">
    {{-- Brand + logo --}}
    <a href="{{ route('home') }}" class="hm-brand">
      <span class="hm-logo" aria-hidden="true">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <path d="M12 21s-6.5-4.35-9.33-7.18C.84 12 .5 9.27 2.34 7.43a5.1 5.1 0 0 1 7.22 0l.44.44.44-.44a5.1 5.1 0 0 1 7.22 0c1.84 1.84 1.5 4.57-.33 6.39C18.5 16.65 12 21 12 21Z" fill="white"/>
          <path d="M6.5 12.5h2.7l1.5-3 2.3 6 1.3-3h3.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      <span>HealthMiles</span>
    </a>

    {{-- Primary links --}}
    <nav class="hm-links">
      <a href="{{ route('home')   }}" class="{{ request()->routeIs('home')   ? 'active' : '' }}">Home</a>
      <a href="{{ route('about')  }}" class="{{ request()->routeIs('about')  ? 'active' : '' }}">About</a>
      <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </nav>

    {{-- Auth actions --}}
    <div class="hm-auth">
      @auth
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="btn-link">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}">Login</a>
        {{-- Patient register (Laravel Breeze default) --}}
        <a href="{{ route('register') }}" class="btn-register">Register</a>
        {{-- NEW: Doctor Register button --}}
        <a
          href="{{ route('doctor.register') }}"
          class="btn-register-outline"
        >
          Doctor Register
        </a>
      @endauth
    </div>
  </div>
</header>
