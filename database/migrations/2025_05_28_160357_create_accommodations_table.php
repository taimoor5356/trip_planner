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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('building_type_id')->nullable();
            $table->integer('built_id')->nullable();
            $table->string('default_status')->default("no");
            $table->json('room_category_id')->nullable();
            $table->json('category_id')->nullable();
            $table->json('property_amenities_id')->nullable();
            $table->string('location')->nullable();
            $table->integer('town_id')->nullable();
            $table->integer('num_of_rooms')->nullable();
            $table->string('front_desk_contact')->nullable();
            $table->string('sales_contact')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('insta_link')->nullable();
            $table->string('website_link')->nullable();
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
        Schema::dropIfExists('accommodations');
    }
};
