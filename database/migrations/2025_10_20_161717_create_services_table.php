<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_minutes')->default(30); // default service duration
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Pivot: which services a doctor offers (many-to-many)
        Schema::create('doctor_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->unique(['doctor_id','service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_service');
        Schema::dropIfExists('services');
    }
};
