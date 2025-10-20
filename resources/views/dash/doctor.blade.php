<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Doctor Dashboard</h2></x-slot>
    <div class="p-6 space-y-3">
        <div class="rounded-lg bg-green-50 border border-green-200 p-4">
            ✅ Logged in as <strong>{{ auth()->user()->name }}</strong> (role: <strong>{{ auth()->user()->role }}</strong>)
        </div>
        <p>This is the Doctor dashboard. No other links/routes are used yet.</p>
    </div>
</x-app-layout>
