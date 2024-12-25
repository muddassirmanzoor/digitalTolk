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
        Schema::create('operation_information', function (Blueprint $table) {
            $table->id();
            $table->string('operational_id');
            $table->integer('agent_id');
            $table->integer('people_quantity')->default(0);
            $table->string('nationality');
            $table->string('voucher_number')->unique();
            $table->string('group_leader_name')->nullable();
            $table->string('group_leader_number')->nullable();
            $table->string('created_by')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_information');
    }
};
