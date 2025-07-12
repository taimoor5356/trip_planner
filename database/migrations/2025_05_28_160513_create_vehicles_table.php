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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_number')->nullable();
            $table->integer('capacity_adults')->default(0);
            $table->integer('capacity_children')->default(0);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->integer('region_id')->nullable();
            $table->integer('per_day_cost')->default(0);
            $table->integer('vehicle_type_id')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
};
