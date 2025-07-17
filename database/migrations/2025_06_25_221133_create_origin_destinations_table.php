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
        Schema::create('origin_destinations', function (Blueprint $table) {
            $table->id();
            $table->integer('origin_id');
            $table->integer('destination_id');
            $table->integer('mode_of_travel')->default(1)->comment('1:by_road, 2:by_air');
            $table->string('days_nights')->nullable();
            $table->integer('days')->nullable();
            $table->integer('nights')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('origin_destinations');
    }
};
