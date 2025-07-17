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
        Schema::create('region_seasons', function (Blueprint $table) {
            $table->id();
            $table->integer('region_id');
            $table->integer('season_id');
            $table->tinyInteger('mode_of_travel')->nullable();
            $table->string('no_of_days')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_seasons');
    }
};
