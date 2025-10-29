<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{

    /** Ensure a doctor profile exists (link by email or create). */
    private function ensureDoctorProfile(Request $request): void
    {
        $user = $request->user();

        if (!$user || $user->role !== 'doctor') {
            return;
        }

        if (!$user->relationLoaded('doctor')) {
            $user->load('doctor');
        }

        if (!$user->doctor) {
            $existing = Doctor::where('email', $user->email)->first();

            if ($existing && !$existing->user_id) {
                $existing->user_id = $user->id;
                $existing->active  = $existing->active ?? 1;
                $existing->name    = $existing->name ?: $user->name;
                $existing->save();
            } elseif (!$existing) {
                Doctor::firstOrCreate(
                    ['user_id' => $user->id],
                    ['name' => $user->name, 'email' => $user->email, 'active' => 1]
                );
            }

            $user->unsetRelation('doctor');
            $user->load('doctor');
        }
    }

    /** Return Carbon or null for slot start, handling all schemas safely. */
    private function slotStartsAt(?TimeSlot $slot): ?Carbon
    {
        if (!$slot) return null;

        
        if (!empty($slot->start_at)) {
            return Carbon::parse($slot->start_at);
        }

        // 2) date + time variants
        $date = $slot->slot_date ?? $slot->date ?? null;
        $time = $slot->start_time ?? null;

        // If time already contains a date, just parse it directly
        if ($time && preg_match('/\d{4}-\d{2}-\d{2}/', (string)$time)) {
            return Carbon::parse($time);
        }

        // If we only have a date, no time → treat as start of that day
        if ($date && !$time) {
            return Carbon::parse($date)->startOfDay();
        }

        // If we only have time (rare), parse as-is
        if (!$date && $time) {
            return Carbon::parse($time);
        }

        // Combine sanitized "date + time"
        if ($date && $time) {
            // normalize time to HH:MM:SS
            try {
                $norm = Carbon::parse($time)->format('H:i:s');
            } catch (\Throwable $e) {
                $norm = $time;
            }
            return Carbon::parse(trim($date . ' ' . $norm));
        }

        return null;
    }

    /** Return Carbon or null for slot end, handling all schemas safely. */
    private function slotEndsAt(?TimeSlot $slot): ?Carbon
    {
        if (!$slot) return null;

        // 1) Preferred: timestamps
        if (!empty($slot->end_at)) {
            return Carbon::parse($slot->end_at);
        }

        // 2) date + time variants
        $date = $slot->slot_date ?? $slot->date ?? null;
        $time = $slot->end_time ?? null;

        // If time already contains a date, just parse it directly
        if ($time && preg_match('/\d{4}-\d{2}-\d{2}/', (string)$time)) {
            return Carbon::parse($time);
        }

        // If we only have a date, no time → treat as end of that day
        if ($date && !$time) {
            return Carbon::parse($date)->endOfDay();
        }

        // If we only have time, parse as-is
        if (!$date && $time) {
            return Carbon::parse($time);
        }

        // Combine sanitized "date + time"
        if ($date && $time) {
            try {
                $norm = Carbon::parse($time)->format('H:i:s');
            } catch (\Throwable $e) {
                $norm = $time;
            }
            return Carbon::parse(trim($date . ' ' . $norm));
        }

        return null;
    }



    /** Show the doctor's own appointments (split into upcoming / past). */
    public function index(Request $request)
    {
        $this->ensureDoctorProfile($request);

        $doctor = $request->user()->doctor;
        if (!$doctor) {
            return back()->with('error', 'Your doctor profile could not be created/linked. Please contact admin.');
        }

        // Pull all appointments and relations
        $all = Appointment::with(['patient:id,name,email', 'service:id,name', 'timeSlot'])
            ->where('doctor_id', $doctor->id)
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderByDesc('id')
            ->get();

        $now = now();

        // Filter using robust start/end computations
        $upcoming = $all->filter(function ($a) use ($now) {
            $status = $a->status ?? 'pending';
            if ($status === 'cancelled') return false;

            $start = $this->slotStartsAt(optional($a)->timeSlot);
            if ($start) return $start->greaterThanOrEqualTo($now);

            // If no start found, keep it out of upcoming
            return false;
        })->values();

        $past = $all->filter(function ($a) use ($now) {
            $end   = $this->slotEndsAt(optional($a)->timeSlot);
            $start = $this->slotStartsAt(optional($a)->timeSlot);

            // If we have an end, compare with end; else fallback to start
            if ($end)   return $end->lessThan($now);
            if ($start) return $start->lessThan($now);

            return false;
        })->values();

        // (Optional) counts for UI badges
        $counts = [
            'upcoming' => $upcoming->count(),
            'past'     => $past->count(),
        ];

        return view('doctor.appointments.index', compact('upcoming', 'past', 'doctor', 'counts'));
    }

    /** Allow doctor to delete one of their appointments. */
    public function destroy(Request $request, Appointment $appointment)
    {
        $this->ensureDoctorProfile($request);

        $doctor = $request->user()->doctor;
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized.');
        }

        $appointment->delete();

        return back()->with('success', 'Appointment deleted.');
    }
}
