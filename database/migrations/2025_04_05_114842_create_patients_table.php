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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospital_id')->nullable();  
            $table->unsignedBigInteger('doctor_id')->nullable(); 
            $table->unsignedBigInteger('nurse_id')->nullable(); 
            $table->string('patient_number')->unique();
            $table->string('full_name')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('email')->unique();
            $table->string('mobile_number')->nullable();
            $table->enum('marital_status', ['married', 'unmarried'])->nullable();
            $table->string('occupation')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('sugar_level')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();
            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('set null');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
            $table->foreign('nurse_id')->references('id')->on('nurses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
