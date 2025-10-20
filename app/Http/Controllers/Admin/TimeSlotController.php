<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    // /admin/doctors/{doctor}/timeslots
    public function index(Doctor $doctor)
    {
        $slots = $doctor->timeSlots()->orderBy('slot_date')->orderBy('start_time')->paginate(12);
        return view('admin.timeslots.index', compact('doctor','slots'));
    }

    // /admin/doctors/{doctor}/timeslots/create
    public function create(Doctor $doctor)
    {
        return view('admin.timeslots.create', compact('doctor'));
    }

    // POST /admin/doctors/{doctor}/timeslots
    public function store(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'slot_date'  => ['required','date'],
            'start_time' => ['required','date_format:H:i'],
            'end_time'   => ['required','date_format:H:i','after:start_time'],
            'is_booked'  => ['nullable','boolean'],
        ]);

        $data['doctor_id'] = $doctor->id;

        // optional: avoid overlaps for same doctor/date/time
        $exists = TimeSlot::where('doctor_id',$doctor->id)
            ->where('slot_date',$data['slot_date'])
            ->where(function($q) use ($data){
                $q->whereBetween('start_time', [$data['start_time'],$data['end_time']])
                  ->orWhereBetween('end_time',   [$data['start_time'],$data['end_time']]);
            })->exists();

        if ($exists) {
            return back()->withErrors(['start_time' => 'Overlapping slot for this doctor on selected date.'])->withInput();
        }

        TimeSlot::create($data);
        return redirect()->route('admin.doctors.timeslots.index',$doctor)->with('success','Time slot created.');
    }

    // GET /admin/timeslots/{timeslot}
    public function show(TimeSlot $timeslot)
    {
        $timeslot->load('doctor:id,name');
        return view('admin.timeslots.show', ['slot'=>$timeslot, 'doctor'=>$timeslot->doctor]);
    }

    // GET /admin/timeslots/{timeslot}/edit
    public function edit(TimeSlot $timeslot)
    {
        $timeslot->load('doctor:id,name');
        return view('admin.timeslots.edit', ['slot'=>$timeslot,'doctor'=>$timeslot->doctor]);
    }

    // PUT /admin/timeslots/{timeslot}
    public function update(Request $request, TimeSlot $timeslot)
    {
        $data = $request->validate([
            'slot_date'  => ['required','date'],
            'start_time' => ['required','date_format:H:i'],
            'end_time'   => ['required','date_format:H:i','after:start_time'],
            'is_booked'  => ['nullable','boolean'],
        ]);

        // (optional) overlap check here as well…
        $timeslot->update($data);
        return redirect()->route('admin.doctors.timeslots.index',$timeslot->doctor_id)->with('success','Time slot updated.');
    }

    // DELETE /admin/timeslots/{timeslot}
    public function destroy(TimeSlot $timeslot)
    {
        $doctorId = $timeslot->doctor_id;
        $timeslot->delete();
        return redirect()->route('admin.doctors.timeslots.index',$doctorId)->with('success','Time slot deleted.');
    }
}
