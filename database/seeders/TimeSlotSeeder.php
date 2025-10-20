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
        // create 3 days of 30-min slots for each doctor, 10:00–12:00
        $doctors = Doctor::all();
        $startDate = Carbon::today()->addDay(); // from tomorrow

        foreach ($doctors as $doc) {
            for ($d = 0; $d < 3; $d++) {
                $date = $startDate->copy()->addDays($d)->toDateString();
                for ($h = 10; $h < 12; $h++) {
                    foreach ([0,30] as $m) {
                        $start = sprintf('%02d:%02d:00', $h, $m);
                        $end   = sprintf('%02d:%02d:00', $h, $m + 30);
                        TimeSlot::firstOrCreate([
                            'doctor_id'  => $doc->id,
                            'date'       => $date,
                            'start_time' => $start,
                            'end_time'   => $end,
                        ]);
                    }
                }
            }
        }
    }
}
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
        
        $doctors = Doctor::all();
        $startDate = Carbon::today()->addDay(); 

        foreach ($doctors as $doc) {
            for ($d = 0; $d < 3; $d++) {
                $date = $startDate->copy()->addDays($d)->toDateString();
                for ($h = 10; $h < 12; $h++) {
                    foreach ([0,30] as $m) {
                        $start = sprintf('%02d:%02d:00', $h, $m);
                        $end   = sprintf('%02d:%02d:00', $h, $m + 30);
                        TimeSlot::firstOrCreate([
                            'doctor_id'  => $doc->id,
                            'date'       => $date,
                            'start_time' => $start,
                            'end_time'   => $end,
                        ]);
                    }
                }
            }
        }
    }
}
