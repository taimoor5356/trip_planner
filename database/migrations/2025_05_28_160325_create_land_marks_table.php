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
        Schema::create('land_marks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('city_id');
            $table->longText('location')->nullable();
            $table->json('season_availability')->nullable();
            $table->json('activity_ids')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('land_marks');
    }
};
