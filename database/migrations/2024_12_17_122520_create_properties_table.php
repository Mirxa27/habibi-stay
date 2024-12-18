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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('price_per_night', 10, 2);
            $table->json('amenities');
            $table->json('rules')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->string('property_type')->default('apartment'); // apartment, villa, compound, traditional_house, farm_house, etc.
            $table->json('images');
            $table->boolean('is_halal_friendly')->default(false);
            $table->boolean('is_family_friendly')->default(false);
            $table->boolean('is_segregated')->default(false); // For gender-segregated properties
            $table->boolean('has_driver_room')->default(false);
            $table->boolean('has_maid_room')->default(false);
            $table->boolean('has_majlis')->default(false); // Traditional Saudi sitting room
            $table->boolean('has_prayer_room')->default(false);
            $table->float('distance_to_haram')->nullable(); // Distance to nearest Haram (in km)
            $table->float('distance_to_metro')->nullable(); // Distance to nearest metro station
            $table->json('nearby_landmarks')->nullable(); // Array of nearby important locations
            $table->string('district')->nullable(); // Saudi district name
            $table->string('neighborhood')->nullable(); // Saudi neighborhood name
            $table->json('saudi_amenities')->nullable(); // Saudi-specific amenities array
            $table->boolean('has_family_section')->default(false);
            $table->boolean('has_bachelor_section')->default(false);
            $table->boolean('is_halal_certified')->default(false);
            $table->boolean('provides_prayer_essentials')->default(false);
            $table->json('available_languages')->nullable(); // Languages spoken by staff
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
