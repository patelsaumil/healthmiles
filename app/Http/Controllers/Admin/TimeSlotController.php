<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    /** List slots for a given doctor (nested) */
    public function index(Doctor $doctor)
    {
        $slots = $doctor->timeSlots()
            ->orderBy('slot_date')
            ->orderBy('start_time')
            ->paginate(15);

        return view('admin.timeslots.index', compact('doctor', 'slots'));
    }

    /** Show create form (nested) */
    public function create(Doctor $doctor)
    {
        $slot = new TimeSlot(['is_booked' => false]);
        return view('admin.timeslots.create', compact('doctor', 'slot'));
    }

    /** Store (nested) */
    public function store(Request $request, Doctor $doctor)
    {
        $data = $this->validateData($request);
        $data['doctor_id'] = $doctor->id;
        $data['is_booked'] = (bool) $request->input('is_booked', false);

        $this->guardOverlap($doctor->id, $data['slot_date'], $data['start_time'], $data['end_time']);

        TimeSlot::create($data);

        return redirect()
            ->route('admin.doctors.timeslots.index', $doctor)
            ->with('success', 'Time slot created.');
    }

    /** Shallow show (optional) */
    public function show(TimeSlot $timeslot)
    {
        return view('admin.timeslots.show', [
            'slot'   => $timeslot,
            'doctor' => $timeslot->doctor,
        ]);
    }

    /** Shallow edit */
    public function edit(TimeSlot $timeslot)
    {
        return view('admin.timeslots.edit', [
            'slot'   => $timeslot,
            'doctor' => $timeslot->doctor,
        ]);
    }

    /** Shallow update */
    public function update(Request $request, TimeSlot $timeslot)
    {
        $data = $this->validateData($request);
        $data['is_booked'] = (bool) $request->input('is_booked', false);

        $this->guardOverlap($timeslot->doctor_id, $data['slot_date'], $data['start_time'], $data['end_time'], $timeslot->id);

        $timeslot->update($data);

        return redirect()
            ->route('admin.doctors.timeslots.index', $timeslot->doctor)
            ->with('success', 'Time slot updated.');
    }

    /** Shallow destroy */
    public function destroy(TimeSlot $timeslot)
    {
        $doctor = $timeslot->doctor;
        $timeslot->delete();

        return redirect()
            ->route('admin.doctors.timeslots.index', $doctor)
            ->with('success', 'Time slot deleted.');
    }

    /* --------------------------- helpers --------------------------- */

    private function validateData(Request $request): array
    {
        return $request->validate([
            'slot_date'  => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'is_booked'  => ['nullable', 'boolean'],
        ]);
    }

    /** Prevent overlapping slots for same doctor+date */
    private function guardOverlap(int $doctorId, $date, $start, $end, $ignoreId = null): void
    {
        $q = TimeSlot::where('doctor_id', $doctorId)
            ->where('slot_date', $date)
            ->where(function ($w) use ($start, $end) {
                $w->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function ($x) use ($start, $end) {
                      $x->where('start_time', '<=', $start)
                        ->where('end_time', '>=', $end);
                  });
            });

        if ($ignoreId) {
            $q->where('id', '!=', $ignoreId);
        }

        if ($q->exists()) {
            abort(422, 'This slot overlaps with an existing slot.');
        }
    }
}
