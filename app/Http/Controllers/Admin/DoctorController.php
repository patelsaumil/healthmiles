<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        $doctors = Doctor::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('specialty', 'like', "%{$search}%");
                });
            })
            ->withCount(['services','timeSlots'])   // requires doctor_service pivot
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.doctors.index', compact('doctors','search'));
    }

    public function create()
    {
        $services = Service::orderBy('name')->pluck('name','id'); // <-- FIX
        $doctor = new Doctor();
        return view('admin.doctors.create', compact('doctor','services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required','string','max:120'],
            'email'      => ['nullable','email','max:150','unique:doctors,email'],
            'phone'      => ['nullable','string','max:30'],
            'specialty'  => ['nullable','string','max:120'],
            'bio'        => ['nullable','string'],
            'photo_path' => ['nullable','string','max:255'],
            'active'     => ['nullable','boolean'],
            'services'   => ['array'],
            'services.*' => ['integer','exists:services,id'],
        ]);

        $data['active'] = (bool) $request->input('active');

        $doctor = Doctor::create($data);
        $doctor->services()->sync($request->input('services', []));

        return redirect()->route('admin.doctors.index')->with('success','Doctor created.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load([
            'services:id,name',
            'timeSlots' => fn($q) => $q->orderBy('slot_date')->orderBy('start_time'),
        ]);

        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(\App\Models\Doctor $doctor)
{
    $services = \App\Models\Service::orderBy('name')->pluck('name','id');
    $doctor->loadMissing('services');

    return view('admin.doctors.edit', compact('doctor','services'));
}




    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'name'       => ['required','string','max:120'],
            'email'      => ['nullable','email','max:150', Rule::unique('doctors','email')->ignore($doctor->id)],
            'phone'      => ['nullable','string','max:30'],
            'specialty'  => ['nullable','string','max:120'],
            'bio'        => ['nullable','string'],
            'photo_path' => ['nullable','string','max:255'],
            'active'     => ['nullable','boolean'],
            'services'   => ['array'],
            'services.*' => ['integer','exists:services,id'],
        ]);

        $data['active'] = (bool) $request->input('active');

        $doctor->update($data);
        $doctor->services()->sync($request->input('services', []));

        return redirect()->route('admin.doctors.index')->with('success','Doctor updated.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success','Doctor deleted.');
    }
}
