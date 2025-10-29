{{-- resources/views/pages/contact.blade.php --}}
@extends('layouts.app')

@section('header')
    {{-- leave empty --}}
@endsection

@section('content')
<style>
  :root{
    --hm-blue:#2563eb;--hm-blue-2:#1d4ed8;--hm-bg:#f6f7fb;--hm-muted:#64748b;
    --hm-border:#e5e7eb;--hm-card:#ffffff;--hm-ink:#0f172a;
  }
  .hm-wrap{max-width:1120px;margin:0 auto;padding:0 24px}
  .hm-hero{background:linear-gradient(180deg,#f6fbff, #eef4ff 55%, #fff 100%);padding:56px 0 24px;text-align:center}
  .hm-hero h1{margin:0;font-size:36px;color:#0f172a}
  .hm-hero p{margin:10px 0 0;color:#8596ac}
  .hm-section{padding:36px 0}
  .hm-grid2{display:grid;grid-template-columns:1.2fr .8fr;gap:28px}
  .hm-card{background:#fff;border:1px solid var(--hm-border);border-radius:16px;padding:18px}
  .hm-label{display:block;font-weight:600;color:#334155;margin:10px 0 6px}
  .hm-input,.hm-text{width:100%;padding:12px 14px;border:1px solid var(--hm-border);border-radius:12px;font:inherit}
  .hm-select{width:100%;padding:12px 14px;border:1px solid var(--hm-border);border-radius:12px;background:#fff}
  .btn{display:inline-block;padding:12px 18px;border-radius:12px;font-weight:700;text-decoration:none}
  .btn-primary{background:var(--hm-blue);color:#fff}
  .hm-pill{display:inline-block;background:#f3f6ff;border:1px solid #e5ebff;color:#0f1d6b;padding:8px 12px;border-radius:999px;font-weight:600}
  .hm-info li{margin:8px 0;color:#334155}
  @media (max-width:980px){.hm-grid2{grid-template-columns:1fr}}
</style>

{{-- HERO --}}
<section class="hm-hero">
  <div class="hm-wrap">
    <h1>Get in Touch with HealthMiles</h1>
    <p>We’re here to help with bookings and questions.</p>
  </div>
</section>

{{-- FORM + INFO --}}
<section class="hm-section">
  <div class="hm-wrap hm-grid2">
    {{-- Form --}}
    <div class="hm-card">
      <form method="post" action="#" onsubmit="event.preventDefault(); alert('Demo form');">
        <div>
          <label class="hm-label">Full name</label>
          <input class="hm-input" type="text" placeholder="Your full name">
        </div>
        <div>
          <label class="hm-label">Email address</label>
          <input class="hm-input" type="email" placeholder="name@example.com">
        </div>
        <div>
          <label class="hm-label">Subject</label>
          <input class="hm-input" type="text" placeholder="How can we help?">
        </div>
        <div>
          <label class="hm-label">Category</label>
          <select class="hm-select">
            <option>General Inquiry</option>
            <option>Booking Support</option>
            <option>Technical Issue</option>
            <option>Feedback</option>
          </select>
        </div>
        <div>
          <label class="hm-label">Message</label>
          <textarea class="hm-text" rows="6" placeholder="Write your message here..."></textarea>
        </div>
        <div style="margin-top:14px">
          <button class="btn btn-primary" type="submit">Send Message</button>
        </div>
      </form>
    </div>

    {{-- Contact details --}}
    <div>
      <div class="hm-card">
        <h3 style="margin:0 0 10px">Contact Information</h3>
        <ul class="hm-info" style="list-style:none;padding:0;margin:0">
          <li>📍 123 Health St, Toronto, ON</li>
          <li>📞 +1 (555) 123-4567</li>
          <li>✉️ support@healthmiles.local</li>
          <li>⏰ Mon–Sat, 9am–6pm</li>
        </ul>

        <div style="margin:14px 0 8px;color:#334155;font-weight:600">Follow</div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <span class="hm-pill">Twitter</span>
          <span class="hm-pill">Facebook</span>
          <span class="hm-pill">Instagram</span>
        </div>
      </div>

      <div class="hm-card" style="margin-top:16px">
        <h3 style="margin:0 0 10px">Find Us</h3>
        <div style="background:#eef2ff;border:1px dashed #c7d2fe;border-radius:12px;height:220px;display:grid;place-items:center;color:#475569">
          Map placeholder
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
