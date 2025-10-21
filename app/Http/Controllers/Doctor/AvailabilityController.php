<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = $request->user()->doctor?->id ?? null;
        if (!$doctorId) abort(403);

        $slots = TimeSlot::where('doctor_id', $doctorId)
            ->orderBy('slot_date')->orderBy('start_time')
            ->paginate(15);

        return view('doctor.availability.index', compact('slots'));
    }

    public function create()
    {
        return view('doctor.availability.create');
    }

    public function store(Request $request)
    {
        $doctorId = $request->user()->doctor?->id ?? null;
        if (!$doctorId) abort(403);

        $data = $request->validate([
            'slot_date'  => ['required','date'],
            'start_time' => ['required','date_format:H:i'],
            'end_time'   => ['required','date_format:H:i','after:start_time'],
        ]);

        $data['doctor_id'] = $doctorId;
        $data['is_booked'] = false;

        $this->guardOverlap($doctorId, $data['slot_date'], $data['start_time'], $data['end_time']);

        TimeSlot::create($data);

        return redirect()->route('doctor.availability.index')->with('success','Time slot added.');
    }

    public function edit(Request $request, TimeSlot $timeslot)
    {
        $doctorId = $request->user()->doctor?->id ?? null;
        if (!$doctorId || $timeslot->doctor_id !== $doctorId) abort(403);

        return view('doctor.availability.edit', compact('timeslot'));
    }

    public function update(Request $request, TimeSlot $timeslot)
    {
        $doctorId = $request->user()->doctor?->id ?? null;
        if (!$doctorId || $timeslot->doctor_id !== $doctorId) abort(403);

        $data = $request->validate([
            'slot_date'  => ['required','date'],
            'start_time' => ['required','date_format:H:i'],
            'end_time'   => ['required','date_format:H:i','after:start_time'],
        ]);

        $this->guardOverlap($doctorId, $data['slot_date'], $data['start_time'], $data['end_time'], $timeslot->id);

        $timeslot->update($data);

        return redirect()->route('doctor.availability.index')->with('success','Time slot updated.');
    }

    public function destroy(Request $request, TimeSlot $timeslot)
    {
        $doctorId = $request->user()->doctor?->id ?? null;
        if (!$doctorId || $timeslot->doctor_id !== $doctorId) abort(403);

        if ($timeslot->is_booked) {
            return back()->with('error','This slot is booked; cannot delete.');
        }

        $timeslot->delete();

        return back()->with('success','Time slot deleted.');
    }

    private function guardOverlap(int $doctorId, $date, $start, $end, $ignoreId = null): void
    {
        $q = TimeSlot::where('doctor_id',$doctorId)
            ->where('slot_date',$date)
            ->where(function ($w) use ($start,$end) {
                $w->whereBetween('start_time',[$start,$end])
                  ->orWhereBetween('end_time',[$start,$end])
                  ->orWhere(function($x) use ($start,$end){
                      $x->where('start_time','<=',$start)->where('end_time','>=',$end);
                  });
            });

        if ($ignoreId) $q->where('id','!=',$ignoreId);

        abort_if($q->exists(), 422, 'This slot overlaps with an existing slot.');
    }
}
