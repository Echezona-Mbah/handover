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
        Schema::create('dentists', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('specialization')->nullable(); // e.g., Orthodontist, Periodontist
            $table->string('role')->nullable();
            $table->string('license_number')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->onDelete('cascade');
            $table->foreignId('shift_schedule_id')->nullable()->constrained('shift_schedules')->onDelete('cascade');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('profile_image')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dentists');
    }
};
