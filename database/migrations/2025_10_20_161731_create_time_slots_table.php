<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->date('date');                       
            $table->time('start_time');                 
            $table->time('end_time');                  
            $table->boolean('is_booked')->default(false);
            $table->timestamps();

            $table->unique(['doctor_id','date','start_time','end_time'], 'unique_slot_per_doctor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
