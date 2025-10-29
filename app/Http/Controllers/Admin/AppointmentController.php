<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    public function index(Request $request)
    {
        $search   = $request->q;
        $status   = $request->status;
        
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

    public function destroy(Appointment $appointment)
    {
        
        if ($appointment->timeSlot) {

        }

        $appointment->delete();

        return back()->with('success', 'Appointment deleted.');
    }
}
