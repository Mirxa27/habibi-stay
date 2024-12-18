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
        Schema::create('property_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->text('bio');
            $table->string('phone');
            $table->string('license_number')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->json('languages')->nullable();
            $table->json('certifications')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('years_of_experience')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_managers');
    }
};
