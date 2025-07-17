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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->string('head_line')->nullable();
            $table->string('tag_line')->nullable();
            $table->tinyInteger('mode_of_travel')->nullable();
            $table->bigInteger('origin_id')->nullable();
            $table->bigInteger('destination_id')->nullable();
            $table->string('trip_duration')->nullable();
            $table->integer('days')->nullable();
            $table->integer('nights')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
