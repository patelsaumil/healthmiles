<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $uid = auth()->id();

        $upcomingCount = Appointment::where('patient_id',$uid)
            ->whereHas('timeSlot', fn($q)=>$q->where('start_at','>', now()))
            ->where('status','!=','cancelled')
            ->count();

        $next = Appointment::with(['doctor','service','timeSlot'])
            ->where('patient_id',$uid)
            ->whereHas('timeSlot', fn($q)=>$q->where('start_at','>', now()))
            ->where('status','!=','cancelled')
            ->orderBy('time_slot_id','asc')
            ->first();

        $pastCount = Appointment::where('patient_id',$uid)
            ->whereHas('timeSlot', fn($q)=>$q->where('end_at','<', now()))
            ->count();

        return view('patient.dashboard', compact('upcomingCount','pastCount','next'));
    }
}
