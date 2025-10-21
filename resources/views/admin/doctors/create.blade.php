@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h3 class="mb-3">Add Doctor</h3>
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif
  <form method="post" action="{{ route('admin.doctors.store') }}">
    @csrf
    @include('admin.doctors.partials.form')
    <div class="mt-3">
      <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
      <button class="btn btn-primary">Save</button>
    </div>
  </form>@extends('layouts.healthmiles')
@section('title','Add Doctor')

{{-- Sidebar --}}
@section('sidebar')
<div>
    <div class="title">Admin</div>

    <a class="hm-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Overview
    </a>

    <a class="hm-nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"
       href="{{ route('admin.doctors.index') }}">
        <i class="bi bi-people"></i> Doctors
    </a>

    <a class="hm-nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
       href="{{ route('admin.services.index') }}">
        <i class="bi bi-clipboard2-pulse"></i> Services
    </a>

    <a class="hm-nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}"
       href="{{ route('admin.appointments.index') }}">
        <i class="bi bi-calendar2-check"></i> Appointments
    </a>
</div>
@endsection

{{-- Content --}}
@section('content')
<div class="container-fluid py-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-person-plus"></i> Add Doctor</h4>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="hm-card p-4">
        <form method="POST" action="{{ route('admin.doctors.store') }}">
            @csrf

            {{-- Reusable fields --}}
            @include('admin.doctors.partials.form')

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
                <button class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Doctor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

</div>
@endsection
