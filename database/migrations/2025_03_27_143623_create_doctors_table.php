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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('specialization')->nullable();
            $table->string('doctor_departments')->nullable();
            $table->string('license_number')->unique()->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->foreignId('hospital_id')->nullable()->constrained()->onDelete('cascade'); // Foreign Key
            $table->foreignId('shift_schedule_id')->nullable()->constrained()->onDelete('set null'); // Foreign Key
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
        Schema::dropIfExists('doctors');
    }
};
