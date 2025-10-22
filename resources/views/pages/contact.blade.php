@extends('layouts.app')
@section('title','Contact — HealthMiles')

@section('content')
@include('partials.public-nav')

<section class="bg-gradient-to-b from-sky-50 to-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
    <h1 class="text-3xl font-extrabold text-slate-900">Get in Touch with HealthMiles</h1>
    <p class="text-slate-600 mt-2">We’re here to help with bookings and questions.</p>
  </div>
</section>

<section class="bg-white pb-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-6">
    <div class="hm-feature p-6">
      <h2 class="font-semibold mb-4">Send us a Message</h2>
      <form class="grid gap-3">
        <input class="form-input rounded-xl border-slate-300" placeholder="Full name">
        <input type="email" class="form-input rounded-xl border-slate-300" placeholder="Email address">
        <input class="form-input rounded-xl border-slate-300" placeholder="Subject">
        <select class="form-select rounded-xl border-slate-300"><option>General Inquiry</option><option>Appointment Support</option></select>
        <textarea rows="4" class="form-textarea rounded-xl border-slate-300" placeholder="Message"></textarea>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl">Send Message</button>
      </form>
    </div>

    <div class="hm-feature p-6">
      <h2 class="font-semibold mb-4">Contact Information</h2>
      <ul class="space-y-2 text-slate-700">
        <li>📍 123 Health St, Toronto, ON</li>
        <li>📞 +1 (555) 123-4567</li>
        <li>✉️ support@healthmiles.local</li>
        <li>🕘 Mon–Sat, 9am–6pm</li>
      </ul>
      <div class="mt-4 text-slate-500 text-sm">Follow</div>
      <div class="flex gap-2 mt-2">
        <a class="px-3 py-2 rounded-lg border border-slate-200">Twitter</a>
        <a class="px-3 py-2 rounded-lg border border-slate-200">Facebook</a>
        <a class="px-3 py-2 rounded-lg border border-slate-200">Instagram</a>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
      <img class="w-full h-72 object-cover" src="https://images.unsplash.com/photo-1526403222941-3ebd9d8a6b83?q=80&w=1400&auto=format&fit=crop">
    </div>
  </div>
</section>

@include('partials.public-footer')
@endsection
