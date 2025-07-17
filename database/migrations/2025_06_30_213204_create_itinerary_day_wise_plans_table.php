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
        Schema::create('itinerary_day_wise_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('itinerary_id');
            $table->string('day')->nullable();
            $table->string('date_time')->nullable();
            $table->string('origin')->nullable();
            $table->bigInteger('region_id')->nullable();
            $table->json('landmarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerary_day_wise_plans');
    }
};
