<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /* =========================================================
     | Helpers
     |=========================================================*/

    /** Apply "available" filters to a TimeSlot query regardless of schema. */
    protected function applyAvailableSlotFilters($query)
    {
        if (Schema::hasColumn('time_slots', 'is_active')) {
            $query->where('is_active', 1);
        }
        if (Schema::hasColumn('time_slots', 'start_at')) {
            $query->where('start_at', '>', now());
        } elseif (Schema::hasColumn('time_slots', 'slot_date')) {
            $query->whereDate('slot_date', '>=', Carbon::today());
        } elseif (Schema::hasColumn('time_slots', 'date')) {
            $query->whereDate('date', '>=', Carbon::today());
        }
        if (Schema::hasColumn('time_slots', 'is_booked')) {
            $query->where('is_booked', 0);
        }
        return $query;
    }

    /** Order slots by earliest date/time based on available columns. */
    protected function orderSlots($query)
    {
        if (Schema::hasColumn('time_slots', 'start_at')) {
            return $query->orderBy('start_at', 'asc');
        }
        if (Schema::hasColumn('time_slots', 'slot_date')) {
            return $query->orderBy('slot_date', 'asc');
        }
        if (Schema::hasColumn('time_slots', 'date')) {
            return $query->orderBy('date', 'asc');
        }
        return $query->orderBy('id', 'asc');
    }

    /** Add "not cancelled" constraint if appointments.status exists. */
    protected function notCancelled($query)
    {
        if (Schema::hasColumn('appointments', 'status')) {
            $query->whereIn('status', ['pending', 'confirmed', 'rescheduled']);
        }
        return $query;
    }

    /** Collection-level check: slot in the future (supports both schemas). */
    protected function slotIsFuture(?TimeSlot $slot): bool
    {
        if (!$slot) return false;

        if (Schema::hasColumn('time_slots', 'start_at') && $slot->start_at) {
            return Carbon::parse($slot->start_at)->greaterThan(now());
        }

        $d = null;
        if (Schema::hasColumn('time_slots', 'slot_date') && $slot->slot_date) {
            $d = Carbon::parse($slot->slot_date);
        } elseif (Schema::hasColumn('time_slots', 'date') && $slot->date) {
            $d = Carbon::parse($slot->date);
        }

        return $d ? $d->endOfDay()->greaterThanOrEqualTo(now()) : true;
    }

    /** Collection-level check: slot in the past (supports both schemas). */
    protected function slotIsPast(?TimeSlot $slot): bool
    {
        if (!$slot) return false;

        if (Schema::hasColumn('time_slots', 'end_at') && $slot->end_at) {
            return Carbon::parse($slot->end_at)->lessThan(now());
        }
        if (Schema::hasColumn('time_slots', 'start_at') && $slot->start_at) {
            return Carbon::parse($slot->start_at)->lessThan(now());
        }

        $d = null;
        if (Schema::hasColumn('time_slots', 'slot_date') && $slot->slot_date) {
            $d = Carbon::parse($slot->slot_date);
        } elseif (Schema::hasColumn('time_slots', 'date') && $slot->date) {
            $d = Carbon::parse($slot->date);
        }

        return $d ? $d->endOfDay()->lessThan(now()) : false;
    }


    
    public function index(Request $request)
    {
        $serviceId = $request->integer('service_id');
        $query = Doctor::query()->with('services');

        if ($serviceId) {
            $query->whereHas('services', fn ($q) => $q->where('services.id', $serviceId));
        }

        return view('patient.appointments.index', [
            'services'          => Service::orderBy('name')->get(),
            'doctors'           => $query->orderBy('name')->paginate(12),
            'selectedServiceId' => $serviceId,
        ]);
    }

    /**  show doctor and available time slots. */
    public function showDoctor(Request $request, Doctor $doctor)
    {
        // Services this doctor actually provides
        $availableServices = $doctor->services()->orderBy('name')->get();

        // If none, attach (or ensure) "General Consultation"
        if ($availableServices->isEmpty()) {
            $fallback = Service::firstOrCreate(['name' => 'General Consultation']);
            if (!$doctor->services()->where('services.id', $fallback->id)->exists()) {
                $doctor->services()->attach($fallback->id);
            }
            $availableServices = collect([$fallback]);
        }

        // Normalize incoming service_id to a doctor-valid one
        $requestedId   = (int) $request->integer('service_id');
        $serviceIdFixed = $availableServices->pluck('id')->contains($requestedId)
            ? $requestedId
            : (int) $availableServices->first()->id;

        // Taken slot IDs (status-aware if column exists)
        $takenSlotIds = Appointment::query()
            ->when(Schema::hasColumn('appointments', 'status'), function ($q) {
                $q->whereIn('status', ['pending', 'confirmed', 'rescheduled']);
            })
            ->pluck('time_slot_id')
            ->filter()
            ->all();

        $slotsQuery = TimeSlot::query()->where('doctor_id', $doctor->id);
        $this->applyAvailableSlotFilters($slotsQuery);

        if (!empty($takenSlotIds)) {
            $slotsQuery->whereNotIn('id', $takenSlotIds);
        }

        $this->orderSlots($slotsQuery);
        $slots = $slotsQuery->limit(50)->get();

        return view('patient.appointments.doctor_show', [
            'doctor'            => $doctor->load('services'),
            'availableServices' => $availableServices,
            'serviceIdFixed'    => $serviceIdFixed,
            'slots'             => $slots,
        ]);
    }

    /**  store appointment. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id'    => ['required', 'exists:doctors,id'],
            'service_id'   => ['required', 'exists:services,id'],
            'time_slot_id' => ['required', 'exists:time_slots,id'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ]);

        // Validate slot belongs to doctor and is still available
        $slotQuery = TimeSlot::query()
            ->where('id', $data['time_slot_id'])
            ->where('doctor_id', $data['doctor_id']);

        $this->applyAvailableSlotFilters($slotQuery);
        $slot = $slotQuery->firstOrFail();

        // Check if already taken by another appointment (status-aware)
        $taken = Appointment::query()->where('time_slot_id', $slot->id);
        $this->notCancelled($taken);
        if ($taken->exists()) {
            return back()->withErrors('This time slot is no longer available.')->withInput();
        }

        // Ensure doctor offers the selected service
        $doctor = Doctor::findOrFail($data['doctor_id']);
        if (!$doctor->services()->where('services.id', $data['service_id'])->exists()) {
            return back()->withErrors('Selected doctor does not provide this service.')->withInput();
        }

        DB::transaction(function () use ($data) {
            Appointment::create([
                'patient_id'   => auth()->id(),
                'doctor_id'    => $data['doctor_id'],
                'service_id'   => $data['service_id'],
                'time_slot_id' => $data['time_slot_id'],
                'status'       => Schema::hasColumn('appointments', 'status') ? 'pending' : null,
                'notes'        => $data['notes'] ?? null,
            ]);
        });

        return redirect()->route('patient.appointments.mine')->with('success', 'Appointment booked!');
    }

    /** My appointments (tabs: upcoming / past / cancelled). */
    public function myAppointments()
    {
        $uid = auth()->id();

        $all = Appointment::with(['doctor', 'service', 'timeSlot'])
            ->where('patient_id', $uid)
            ->orderByDesc('id')
            ->get();

        $upcoming = $all->filter(function ($a) {
            $notCancelled = !Schema::hasColumn('appointments', 'status')
                ? true
                : ($a->status !== 'cancelled');
            return $notCancelled && $this->slotIsFuture(optional($a)->timeSlot);
        });

        $past = $all->filter(function ($a) {
            return $this->slotIsPast(optional($a)->timeSlot);
        });

        $cancelled = $all->filter(function ($a) {
            return Schema::hasColumn('appointments', 'status') && $a->status === 'cancelled';
        });

        return view('patient.appointments.mine', compact('upcoming', 'past', 'cancelled'));
    }

    /** Cancel appointment (or delete when no status column). */
    public function cancel(Appointment $appointment)
    {
        $this->owns($appointment);

        if (!$this->slotIsFuture(optional($appointment)->timeSlot)) {
            return back()->withErrors('You cannot cancel a started or past appointment.');
        }

        if (Schema::hasColumn('appointments', 'status')) {
            $appointment->update(['status' => 'cancelled']);
        } else {
            $appointment->delete();
        }

        return back()->with('success', 'Appointment cancelled.');
    }

    /** Reschedule form (choose a new slot). */
    public function rescheduleForm(Appointment $appointment)
    {
        $this->owns($appointment);

        $taken = Appointment::query();
        $this->notCancelled($taken);
        $taken = $taken->pluck('time_slot_id')->filter()->all();

        $slotsQuery = TimeSlot::query()->where('doctor_id', $appointment->doctor_id);
        $this->applyAvailableSlotFilters($slotsQuery);

        if (!empty($taken)) {
            $slotsQuery->whereNotIn('id', $taken);
        }

        $this->orderSlots($slotsQuery);
        $slots = $slotsQuery->limit(50)->get();

        return view('patient.appointments.reschedule', compact('appointment', 'slots'));
    }

    /** Persist reschedule. */
    public function reschedule(Request $request, Appointment $appointment)
    {
        $this->owns($appointment);

        $data = $request->validate([
            'time_slot_id' => ['required', 'exists:time_slots,id'],
        ]);

        $slotQuery = TimeSlot::query()
            ->where('id', $data['time_slot_id'])
            ->where('doctor_id', $appointment->doctor_id);

        $this->applyAvailableSlotFilters($slotQuery);
        $slot = $slotQuery->firstOrFail();

        $taken = Appointment::query()->where('time_slot_id', $slot->id);
        $this->notCancelled($taken);
        if ($taken->exists()) {
            return back()->withErrors('Selected time slot is already booked.');
        }

        $payload = ['time_slot_id' => $slot->id];
        if (Schema::hasColumn('appointments', 'status')) {
            $payload['status'] = 'rescheduled';
        }

        $appointment->update($payload);

        return redirect()->route('patient.appointments.mine')->with('success', 'Appointment rescheduled.');
    }

    /** Ownership guard. */
    protected function owns(Appointment $appointment)
    {
        abort_unless($appointment->patient_id === auth()->id(), 403);
    }
}
