<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        
        $doctors   = Doctor::all();
        $startDate = Carbon::today()->addDay(); 

        foreach ($doctors as $doc) {
            for ($d = 0; $d < 3; $d++) {
                $date = $startDate->copy()->addDays($d);

                
                foreach ([['h' => 10, 'm' => 0], ['h' => 10, 'm' => 30], ['h' => 11, 'm' => 0], ['h' => 11, 'm' => 30]] as $t) {
                    $start = $date->copy()->setTime($t['h'], $t['m'], 0);
                    $end   = $start->copy()->addMinutes(30);

                    TimeSlot::firstOrCreate(
                        [
                            'doctor_id'  => $doc->id,
                            'slot_date'  => $start->toDateString(),   
                            'start_time' => $start->format('H:i'),
                            'end_time'   => $end->format('H:i'),
                        ],
                        [
                            'is_booked'  => false,                     
                        ]
                    );
                }
            }
        }
    }
}
