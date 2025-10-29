{{-- resources/views/pages/about.blade.php --}}
@extends('layouts.app')

@section('header')
    {{-- Keep empty. We’ll render our own hero below. --}}
@endsection

@section('content')
<style>
  :root{
    --hm-blue:#2563eb;--hm-blue-2:#1d4ed8;--hm-bg:#f6f7fb;--hm-muted:#64748b;
    --hm-border:#e5e7eb;--hm-card:#ffffff;--hm-ink:#0f172a;
  }
  .hm-wrap{max-width:1120px;margin:0 auto;padding:0 24px}
  .hm-hero{background:linear-gradient(180deg,#f8fbff, #eef4ff 50%, #fff 100%); padding:56px 0 28px}
  .hm-hero h1{font-size:40px;line-height:1.1;margin:0;color:#0f172a}
  .hm-hero p{margin:10px 0 0;color:#8aa0b6;font-size:18px}
  .hm-section{padding:44px 0}
  .hm-grid2{display:grid;grid-template-columns:1.1fr .9fr;gap:28px}
  .hm-stat-row{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:16px}
  .hm-stat{background:#fff;border:1px solid var(--hm-border);border-radius:16px;padding:20px;text-align:center}
  .hm-stat .v{font-size:26px;font-weight:800;color:var(--hm-ink)}
  .hm-stat .k{color:var(--hm-muted);font-size:12px;margin-top:6px}
  .hm-divider{height:1px;background:var(--hm-border);margin:36px 0}
  .hm-feats{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
  .hm-card{background:var(--hm-card);border:1px solid var(--hm-border);border-radius:16px;padding:18px}
  .hm-card h4{margin:0 0 6px;color:var(--hm-ink)}
  .hm-card p{margin:0;color:var(--hm-muted)}
  .hm-team{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
  .hm-member{border:1px solid var(--hm-border);background:#fff;border-radius:16px;padding:16px;text-align:center}
  .hm-avatar{width:76px;height:76px;border-radius:999px;margin:0 auto 10px;background:#e2e8f0;display:grid;place-items:center;font-weight:800;color:#334155}
  .hm-cta{background:linear-gradient(90deg,var(--hm-blue-2),var(--hm-blue));color:#fff;border-radius:24px;padding:28px}
  .btn{display:inline-block;padding:12px 18px;border-radius:12px;font-weight:700;text-decoration:none}
  .btn-light{background:#fff;color:var(--hm-blue)}
  .btn-outline{background:#fff;color:#0f172a;border:1px solid var(--hm-border)}
  @media (max-width:980px){
    .hm-grid2{grid-template-columns:1fr}
    .hm-feats{grid-template-columns:1fr 1fr}
    .hm-team{grid-template-columns:1fr 1fr}
    .hm-stat-row{grid-template-columns:1fr}
  }
</style>

{{-- HERO --}}
<section class="hm-hero">
  <div class="hm-wrap">
    <h1>About HealthMiles</h1>
    <p>Making healthcare booking simpler, faster, and more accessible.</p>
  </div>
</section>

{{-- INTRO + STATS --}}
<section class="hm-section">
  <div class="hm-wrap hm-grid2">
    <div>
      <h2 style="margin:0 0 10px;color:#0f172a;font-size:24px">Revolutionizing Healthcare Access</h2>
      <p style="color:var(--hm-muted);margin:0 0 16px">
        We reduce friction between patients and providers with real-time slots and a delightful booking flow.
        Secure, private, and built for modern clinics.
      </p>
      <div class="hm-divider"></div>
    </div>

    <div>
      <div class="hm-stat-row">
        <div class="hm-stat">
          <div class="v">99.9%</div>
          <div class="k">Uptime</div>
        </div>
        <div class="hm-stat">
          <div class="v">24/7</div>
          <div class="k">Support</div>
        </div>
        <div class="hm-stat">
          <div class="v">HIPAA</div>
          <div class="k">Compliant</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- WHAT DRIVES US --}}
<section class="hm-section">
  <div class="hm-wrap">
    <h3 style="text-align:center;margin:0 0 22px;font-size:22px;color:#0f172a">What Drives Us Forward</h3>
    <div class="hm-feats">
      <div class="hm-card"><h4>Accessibility</h4><p>Care for everyone, anywhere.</p></div>
      <div class="hm-card"><h4>Trust & Security</h4><p>Bank-grade encryption & privacy.</p></div>
      <div class="hm-card"><h4>Innovation</h4><p>Fast, modern UX designed for clinics.</p></div>
      <div class="hm-card"><h4>Patient Care</h4><p>Every decision starts with empathy.</p></div>
    </div>
  </div>
</section>

{{-- TEAM --}}
<section class="hm-section">
  <div class="hm-wrap">
    <h3 style="text-align:center;margin:0 0 22px;font-size:22px;color:#0f172a">Meet the Team</h3>
    <div class="hm-team">
      <div class="hm-member">
        <div class="hm-avatar">SJ</div>
        <div style="font-weight:700">Sarah Johnson</div>
        <div style="color:var(--hm-muted);font-size:12px">CEO</div>
      </div>
      <div class="hm-member">
        <div class="hm-avatar">MC</div>
        <div style="font-weight:700">Michael Chen</div>
        <div style="color:var(--hm-muted);font-size:12px">CTO</div>
      </div>
      <div class="hm-member">
        <div class="hm-avatar">EM</div>
        <div style="font-weight:700">Emily Moore</div>
        <div style="color:var(--hm-muted);font-size:12px">Design Lead</div>
      </div>
      <div class="hm-member">
        <div class="hm-avatar">AR</div>
        <div style="font-weight:700">Asha Rao</div>
        <div style="color:var(--hm-muted);font-size:12px">Product</div>
      </div>
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="hm-section">
  <div class="hm-wrap">
    <div class="hm-cta">
      <div class="hm-grid2" style="gap:20px;align-items:center">
        <div>
          <h3 style="margin:0 0 6px;font-size:22px">Ready to Take Control of Your Health?</h3>
          <p style="margin:0;opacity:.95">Join thousands of patients who’ve simplified their healthcare journey with HealthMiles.</p>
        </div>
        <div style="text-align:right">
          <a class="btn btn-light" href="{{ route('patient.appointments.index') }}">Book Appointment</a>
          <a class="btn btn-outline" style="margin-left:10px" href="{{ route('contact') }}">Contact Us</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
