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
        Schema::create('room_category_costs', function (Blueprint $table) {
            $table->id();
            $table->integer('accommodation_id')->default(1);
            // $table->json('room_amenity_id')->nullable();
            $table->integer('room_category_id')->nullable();
            // $table->string('from')->nullable();
            // $table->string('to')->nullable();
            $table->double('price',8,2)->default(0.00);
            $table->tinyInteger('is_default')->default(0);
            // $table->double('single_off_season', 8,2)->default(0.00);
            // $table->double('single_mid_season', 8,2)->default(0.00);
            // $table->double('single_peak_season', 8,2)->default(0.00);
            // $table->double('compact_deluxe_off_season', 8,2)->default(0.00);
            // $table->double('compact_deluxe_mid_season', 8,2)->default(0.00);
            // $table->double('compact_deluxe_peak_season', 8,2)->default(0.00);
            // $table->double('deluxe_off_season', 8,2)->default(0.00);
            // $table->double('deluxe_mid_season', 8,2)->default(0.00);
            // $table->double('deluxe_peak_season', 8,2)->default(0.00);
            // $table->double('executive_off_season', 8,2)->default(0.00);
            // $table->double('executive_mid_season', 8,2)->default(0.00);
            // $table->double('executive_peak_season', 8,2)->default(0.00);
            // $table->double('executive_plus_off_season', 8,2)->default(0.00);
            // $table->double('executive_plus_mid_season', 8,2)->default(0.00);
            // $table->double('executive_plus_peak_season', 8,2)->default(0.00);
            // $table->double('family_room_off_season', 8,2)->default(0.00);
            // $table->double('family_room_mid_season', 8,2)->default(0.00);
            // $table->double('family_room_peak_season', 8,2)->default(0.00);
            // $table->double('luxury_room_off_season', 8,2)->default(0.00);
            // $table->double('luxury_room_mid_season', 8,2)->default(0.00);
            // $table->double('luxury_room_peak_season', 8,2)->default(0.00);
            // $table->double('suite_room_off_season', 8,2)->default(0.00);
            // $table->double('suite_room_mid_season', 8,2)->default(0.00);
            // $table->double('suite_room_peak_season', 8,2)->default(0.00);
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_category_costs');
    }
};
