<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>HealthMiles — Smart Healthcare Booking</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{--blue:#2563eb;--blue2:#1d4ed8;}
    *{box-sizing:border-box}
    body{margin:0;font-family:ui-sans-serif,system-ui,Segoe UI,Roboto,Helvetica,Arial}
    .container{max-width:1120px;margin:0 auto;padding:0 24px}
    /* Nav */
    .nav{background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.06)}
    .nav .row{display:flex;align-items:center;justify-content:space-between;padding:16px 0}
    .nav a{color:#334155;text-decoration:none;margin:0 10px;font-weight:600}
    .btn{padding:10px 16px;border-radius:10px;font-weight:700;display:inline-block;text-decoration:none}
    .btn-primary{background:var(--blue);color:#fff}
    .btn-ghost{background:#fff;color:var(--blue);border:1px solid #e5e7eb}
    .btn-outline{color:var(--blue);border:2px solid var(--blue);background:#fff}
    .btn-outline:hover{background:var(--blue);color:#fff}
    /* Hero */
    .hero{background:linear-gradient(90deg,var(--blue2),var(--blue));color:#fff;padding:64px 0}
    .hero .grid{display:grid;grid-template-columns:1.1fr .9fr;gap:32px;align-items:center}
    .hero h1{font-size:40px;line-height:1.1;margin:0 0 12px}
    .hero p{opacity:.92;font-size:18px;margin:0 0 20px}
    /* Cards */
    .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin:48px 0}
    .card{border:1px solid #e5e7eb;border-radius:14px;padding:20px;box-shadow:0 1px 2px rgba(0,0,0,.04)}
    .card h3{margin:0 0 8px}
    .card p{margin:0;color:#475569;font-size:14px}
    /* Stats */
    .stats{background:#0b3b98;color:#fff;padding:48px 0}
    .stats .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;text-align:center}
    .big{font-size:32px;font-weight:800}
    /* Footer */
    .footer{background:#0f172a;color:#cbd5e1;padding:40px 0}
    .footer h3,.footer h4{color:#fff;margin:0 0 8px}
    @media (max-width:900px){
      .hero .grid{grid-template-columns:1fr}
      .cards{grid-template-columns:1fr}
      .stats .grid{grid-template-columns:repeat(2,1fr)}
    }
  </style>
</head>
<body>
  <!-- NAV -->
  <header class="nav">
    <div class="container row">
      <div style="display:flex;gap:10px;align-items:center">
        <div style="width:36px;height:36px;border-radius:10px;background:#e0e7ff;color:#1d4ed8;display:grid;place-items:center;font-weight:800">🩺</div>
        <strong>HealthMiles</strong>
      </div>
      <nav>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('login') }}">Login</a>
        <a class="btn btn-primary" href="{{ route('register') }}">Register</a>
        <a class="btn btn-outline" href="{{ route('doctor.register') }}">Doctor Register</a>
      </nav>
    </div>
  </header>

  <!-- HERO -->
  <section class="hero">
    <div class="container">
      <div class="grid">
        <div>
          <h1>Smart Healthcare Booking, Anytime</h1>
          <p>Book appointments, manage your health, and get the care you deserve with our intuitive platform.</p>
          <div style="display:flex;gap:12px;flex-wrap:wrap">
            <a class="btn btn-primary" href="{{ route('patient.appointments.index') }}">Book Appointment</a>
            <a class="btn btn-ghost" href="{{ route('about') }}">Learn More</a>
            <a class="btn btn-outline" href="{{ route('doctor.register') }}">Become a Doctor</a>
          </div>
        </div>
        <div style="display:grid;place-items:center">
          <img src="https://cdn-icons-png.flaticon.com/512/3784/3784184.png" alt="Doctor"
               style="width:320px;border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,.25)">
        </div>
      </div>
    </div>
  </section>

  <!-- WHY CHOOSE -->
  <section class="container">
    <h2 style="text-align:center;margin:36px 0 18px;font-size:24px">Why Choose HealthMiles?</h2>
    <div class="cards">
      <div class="card"><h3>⚡ Instant Booking</h3><p>Real-time availability. No calls, no waiting—just click and confirm.</p></div>
      <div class="card"><h3>🔒 Secure & Private</h3><p>Enterprise security & HIPAA compliance keep your data safe.</p></div>
      <div class="card"><h3>📱 Mobile Friendly</h3><p>Book and manage on the go with our responsive experience.</p></div>
    </div>
  </section>

  <!-- STATS -->
  <section class="stats">
    <div class="container">
      <h2 style="text-align:center;margin:0 0 18px;font-size:22px">Trusted by Thousands</h2>
      <div class="grid">
        <div><div class="big">50K+</div><div>Happy Patients</div></div>
        <div><div class="big">500+</div><div>Healthcare Providers</div></div>
        <div><div class="big">100K+</div><div>Appointments Booked</div></div>
        <div><div class="big">98%</div><div>Satisfaction Rate</div></div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container" style="display:grid;gap:24px;grid-template-columns:repeat(3,1fr)">
      <div>
        <h3>HealthMiles</h3>
        <p>Your trusted partner in healthcare booking and management.</p>
      </div>
      <div>
        <h4>Quick Links</h4>
        <p>
          <a style="color:#cbd5e1" href="{{ route('home') }}">Home</a> ·
          <a style="color:#cbd5e1" href="{{ route('about') }}">About</a> ·
          <a style="color:#cbd5e1" href="{{ route('contact') }}">Contact</a>
        </p>
      </div>
      <div>
        <h4>Contact</h4>
        <p>📞 +1 (555) 123-4567<br>📧 support@healthmiles.com<br>🏥 123 Health Ave, Toronto</p>
      </div>
    </div>
  </footer>
</body>
</html>
