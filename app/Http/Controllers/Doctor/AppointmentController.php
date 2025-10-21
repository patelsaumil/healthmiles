<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Show the doctor's own appointments.
     */
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor; // requires users.id -> doctors.user_id link

        if (!$doctor) {
            abort(403, 'You are not linked to a doctor profile.');
        }

        $appointments = Appointment::with([
                'patient:id,name,email',
                'service:id,name',
                'timeSlot'  // assumes relation timeSlot() exists on Appointment
            ])
            ->where('doctor_id', $doctor->id)
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderByRaw("FIELD(status,'scheduled','pending','completed','cancelled')")
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('doctor.appointments.index', compact('appointments', 'doctor'));
    }

    /**
     * Allow doctor to delete one of **their** appointments.
     */
    public function destroy(Request $request, Appointment $appointment)
    {
        $doctor = $request->user()->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized.');
        }

        $appointment->delete();

        return back()->with('success', 'Appointment deleted.');
    }
}
