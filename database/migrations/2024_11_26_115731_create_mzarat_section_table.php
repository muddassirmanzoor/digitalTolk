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
        Schema::create('mzarat_section', function (Blueprint $table) {
            $table->id();
            $table->integer('operational_id');
            $table->integer('operational_information_id');
            $table->string('travel_from')->nullable();
            $table->string('travel_to')->nullable();
            $table->date('travel_date')->nullable();
            $table->string('travel_time')->nullable();
            $table->string('transport_time')->nullable();
            $table->string('transport_company')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mzarat_section');
    }
};
