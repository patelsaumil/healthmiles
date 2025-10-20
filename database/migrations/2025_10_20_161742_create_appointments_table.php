<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('time_slot_id')->constrained('time_slots')->cascadeOnDelete();

            $table->enum('status', ['pending','confirmed','completed','cancelled'])->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();

          
            $table->unique('time_slot_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
