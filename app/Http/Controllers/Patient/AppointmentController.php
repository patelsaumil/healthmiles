<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $serviceId = $request->integer('service_id');
        $query = Doctor::query()->with('services');

        if ($serviceId) {
            $query->whereHas('services', fn($q)=>$q->where('services.id', $serviceId));
        }

        return view('patient.appointments.index', [
            'services' => Service::orderBy('name')->get(),
            'doctors'  => $query->orderBy('name')->paginate(12),
            'selectedServiceId' => $serviceId,
        ]);
    }

    public function showDoctor(Request $request, Doctor $doctor)
    {
        $serviceId = $request->integer('service_id');

        $takenSlotIds = Appointment::whereIn('status',['pending','confirmed','rescheduled'])
            ->pluck('time_slot_id')->filter()->all();

        $slots = TimeSlot::query()
            ->where('doctor_id',$doctor->id)
            ->where('is_active', true)
            ->where('start_at','>', now())
            ->when(!empty($takenSlotIds), fn($q)=>$q->whereNotIn('id',$takenSlotIds))
            ->orderBy('start_at')->take(50)->get();

        return view('patient.appointments.doctor_show', [
            'doctor'   => $doctor->load('services'),
            'services' => Service::orderBy('name')->get(),
            'serviceId'=> $serviceId,
            'slots'    => $slots,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id'   => ['required','exists:doctors,id'],
            'service_id'  => ['required','exists:services,id'],
            'time_slot_id'=> ['required','exists:time_slots,id'],
            'notes'       => ['nullable','string','max:500'],
        ]);

        $slot = TimeSlot::where('id',$data['time_slot_id'])
            ->where('doctor_id',$data['doctor_id'])
            ->where('is_active', true)
            ->where('start_at','>', now())
            ->firstOrFail();

        $isTaken = Appointment::where('time_slot_id',$slot->id)
            ->whereIn('status',['pending','confirmed','rescheduled'])
            ->exists();

        if ($isTaken) {
            return back()->withErrors('This time slot is no longer available.')->withInput();
        }

        $doctor = Doctor::findOrFail($data['doctor_id']);
        if (!$doctor->services()->where('services.id',$data['service_id'])->exists()) {
            return back()->withErrors('Selected doctor does not provide this service.')->withInput();
        }

        DB::transaction(function () use ($data) {
            Appointment::create([
                'patient_id'   => auth()->id(),
                'doctor_id'    => $data['doctor_id'],
                'service_id'   => $data['service_id'],
                'time_slot_id' => $data['time_slot_id'],
                'status'       => 'pending',
                'notes'        => $data['notes'] ?? null,
            ]);
        });

        return redirect()->route('patient.appointments.mine')->with('success','Appointment booked!');
    }

    public function myAppointments()
    {
        $uid = auth()->id();
        $all = Appointment::with(['doctor','service','timeSlot'])
            ->where('patient_id',$uid)->orderByDesc('id')->get();

        $upcoming = $all->filter(fn($a)=>optional($a->timeSlot)->start_at > now() && $a->status!=='cancelled');
        $past     = $all->filter(fn($a)=>optional($a->timeSlot)->end_at && $a->timeSlot->end_at < now());
        $cancelled= $all->filter(fn($a)=>$a->status==='cancelled');

        return view('patient.appointments.mine', compact('upcoming','past','cancelled'));
    }

    public function cancel(Appointment $appointment)
    {
        $this->owns($appointment);

        if ($appointment->timeSlot && $appointment->timeSlot->start_at <= now()) {
            return back()->withErrors('You cannot cancel a started or past appointment.');
        }

        $appointment->update(['status'=>'cancelled']);
        return back()->with('success','Appointment cancelled.');
    }

    public function rescheduleForm(Appointment $appointment)
    {
        $this->owns($appointment);

        $taken = Appointment::whereIn('status',['pending','confirmed','rescheduled'])
            ->pluck('time_slot_id')->all();

        $slots = TimeSlot::where('doctor_id',$appointment->doctor_id)
            ->where('is_active', true)
            ->where('start_at','>', now())
            ->when(!empty($taken), fn($q)=>$q->whereNotIn('id',$taken))
            ->orderBy('start_at')->take(50)->get();

        return view('patient.appointments.reschedule', compact('appointment','slots'));
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $this->owns($appointment);

        $data = $request->validate([
            'time_slot_id' => ['required','exists:time_slots,id'],
        ]);

        $slot = TimeSlot::where('id',$data['time_slot_id'])
            ->where('doctor_id',$appointment->doctor_id)
            ->where('is_active', true)
            ->where('start_at','>', now())
            ->firstOrFail();

        $isTaken = Appointment::where('time_slot_id',$slot->id)
            ->whereIn('status',['pending','confirmed','rescheduled'])
            ->exists();

        if ($isTaken) {
            return back()->withErrors('Selected time slot is already booked.');
        }

        $appointment->update([
            'time_slot_id' => $slot->id,
            'status'       => 'rescheduled',
        ]);

        return redirect()->route('patient.appointments.mine')->with('success','Appointment rescheduled.');
    }

    protected function owns(Appointment $appointment)
    {
        abort_unless($appointment->patient_id === auth()->id(), 403);
    }
}
