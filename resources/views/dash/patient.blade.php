@extends('layouts.app')
@section('title', 'Patient Dashboard — HealthMiles')

@section('content')
<div class="flex min-h-screen bg-gray-50 role-patient">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-md hidden md:flex flex-col">
        <div class="p-5 flex items-center gap-2 border-b border-gray-200">
            <span class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-lg text-lg font-bold">🩺</span>
            <h1 class="text-lg font-bold text-gray-800">HealthMiles</h1>
        </div>

        <nav class="flex-1 p-4 text-sm text-gray-700 space-y-2">
            <a href="{{ route('patient.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50 font-medium text-blue-600">
                <span>🏠</span> Dashboard
            </a>
            <a href="{{ route('patient.appointments.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50">
                <span>🔍</span> Find a Doctor
            </a>
            <a href="{{ route('patient.appointments.mine') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50">
                <span>📅</span> My Appointments
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50">
                <span>👤</span> Profile
            </a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50">
                <span>❓</span> Help
            </a>
        </nav>

        <div class="border-t border-gray-200 p-4 text-sm">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-50 text-red-600 font-medium">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 flex flex-col">
        {{-- Top Bar --}}
        <header class="flex items-center justify-between bg-white border-b border-gray-200 px-6 py-4 shadow-sm">
            <div class="flex items-center gap-3 w-full max-w-xl">
                <div class="hm-search flex items-center w-full bg-gray-100 border border-gray-200 rounded-xl px-3 py-2">
                    🔎
                    <input type="text" class="flex-1 bg-transparent outline-none text-sm px-2" placeholder="Search doctors, services, or appointments">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative bg-gray-100 p-2 rounded-full hover:bg-gray-200">🔔
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="flex items-center gap-2">
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode(auth()->user()->name) }}" class="w-8 h-8 rounded-full" alt="avatar">
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <section class="flex-1 p-6 space-y-6">
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 text-white p-6 rounded-2xl shadow">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name ?? 'Patient' }}!</h2>
                        <p class="text-blue-100 text-sm mt-1">You have 0 upcoming appointments this week.</p>
                    </div>
                    <a href="{{ route('patient.appointments.index') }}" class="bg-white text-blue-600 font-semibold px-4 py-2 rounded-xl shadow hover:bg-blue-50">
                        Book New
                    </a>
                </div>
            </div>

            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-5">
                <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-800 mb-1">Upcoming Appointments</h3>
                    <p class="text-sm text-gray-500">
                        No upcoming appointments yet.
                        <a href="{{ route('patient.appointments.index') }}" class="text-blue-600">Book one</a>.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-800 mb-1">Quick Actions</h3>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <a href="{{ route('patient.appointments.index') }}" class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-lg">Book</a>
                        <a href="{{ route('patient.appointments.mine') }}" class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-lg">Manage</a>
                        <a href="{{ route('profile.edit') }}" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg">Profile</a>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-800 mb-1">Notifications</h3>
                    <p class="text-sm text-gray-500">No new notifications</p>
                </div>

                <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-800 mb-1">Health Overview</h3>
                    <ul class="mt-2 text-sm text-gray-600 space-y-1">
                        <li>Total Appointments: <strong>0</strong></li>
                        <li>Upcoming: <strong>0</strong></li>
                        <li>Completed: <strong>0</strong></li>
                    </ul>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
                <h3 class="font-semibold text-gray-800 mb-3">Recent History</h3>
                <p class="text-sm text-gray-500">No recent visits yet.</p>
            </div>
        </section>
    </main>
</div>
@endsection
