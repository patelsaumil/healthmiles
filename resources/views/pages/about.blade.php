@extends('layouts.app')
@section('title','About — HealthMiles')

@section('content')
@include('partials.public-nav')

<section class="hm-hero-public">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-extrabold">About HealthMiles</h1>
    <p class="text-white/90 mt-2 max-w-2xl">Making healthcare booking simpler, faster, and more accessible.</p>
  </div>
</section>

<section class="py-12 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-8 items-center">
    <img class="rounded-2xl shadow-xl ring-1 ring-slate-200"
         src="https://images.unsplash.com/photo-1578496781908-72673b69d4ab?q=80&w=1200&auto=format&fit=crop" alt="">
    <div>
      <h2 class="text-xl font-bold">Revolutionizing Healthcare Access</h2>
      <p class="text-slate-600 mt-2">We reduce friction between patients and providers with real-time slots and a delightful booking flow.</p>
      <div class="grid grid-cols-3 gap-3 mt-4">
        @foreach([['99.9%','Uptime'],['24/7','Support'],['HIPAA','Mindful']] as [$n,$l])
          <div class="bg-slate-50 rounded-xl p-4 text-center"><div class="text-lg font-extrabold">{{ $n }}</div><div class="text-slate-600 text-sm">{{ $l }}</div></div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
    <h3 class="text-center text-xl font-bold mb-6">What Drives Us Forward</h3>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach([
        ['Accessibility','Care for everyone, anywhere.'],
        ['Trust & Security','Bank-grade security.'],
        ['Innovation','Cutting-edge tech & UX.'],
        ['Patient Care','Every decision starts with empathy.'],
      ] as [$t,$d])
        <div class="hm-feature p-5">
          <h4 class="font-semibold">{{ $t }}</h4><p class="text-slate-600 text-sm mt-1">{{ $d }}</p>
        </div>
      @endforeach
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
    <h3 class="text-center text-xl font-bold mb-6">Meet the Team</h3>
    <div class="grid md:grid-cols-4 gap-5">
      @foreach(['Samuel Patel','Luna Park','Liam Nguyen','Ava Patel'] as $n)
        <div class="hm-feature p-5 text-center">
          <img class="w-20 h-20 rounded-full mx-auto mb-3" src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($n) }}">
          <p class="font-semibold">{{ $n }}</p><p class="text-xs text-slate-600">Product & Engineering</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="hm-cta py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h3 class="text-2xl font-extrabold">Ready to Take Control of Your Health?</h3>
    <a href="{{ route('patient.appointments.index') }}" class="mt-4 inline-block bg-slate-900 text-white px-5 py-3 rounded-xl font-semibold">Book Appointment</a>
  </div>
</section>

@include('partials.public-footer')
@endsection
