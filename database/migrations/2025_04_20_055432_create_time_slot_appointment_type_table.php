<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_slot_appointment_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_slot_id')->constrained('time_slots');
            $table->foreignId('appointment_type_id')->constrained('appointment_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slot_appointment_type');
    }
};
