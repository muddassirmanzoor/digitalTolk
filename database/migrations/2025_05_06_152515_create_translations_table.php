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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale');
            $table->string('key');
            $table->text('value');
            $table->json('tags')->nullable();
            $table->timestamps();
        });
            // Add indexes for faster querying
            Schema::table('translations', function (Blueprint $table) {
                $table->index(['locale']); // Index on locale
                $table->index(['key']); // Index on key
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
