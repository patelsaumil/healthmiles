<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Admin can see all appointments with optional search/status filters.
     */
    public function index(Request $request)
    {
        $search   = $request->q;
        $status   = $request->status;
        // Include whatever statuses your app uses
        $statuses = ['pending', 'scheduled', 'confirmed', 'completed', 'cancelled'];

        $appointments = Appointment::with(['doctor', 'patient', 'service', 'timeSlot'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('doctor', function ($d) use ($search) {
                    $d->where('name', 'like', "%{$search}%");
                })->orWhereHas('patient', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments', 'statuses', 'search', 'status'));
    }

    /**
     * Admin can delete any appointment.
     * (Optional: free the time slot if you want to mark it unbooked.)
     */
    public function destroy(Appointment $appointment)
    {
        // If you track booking on the time slot, free it:
        if ($appointment->timeSlot) {
            // If your column is named is_booked, uncomment next line:
            // $appointment->timeSlot->update(['is_booked' => false]);
        }

        $appointment->delete();

        return back()->with('success', 'Appointment deleted.');
    }
}
