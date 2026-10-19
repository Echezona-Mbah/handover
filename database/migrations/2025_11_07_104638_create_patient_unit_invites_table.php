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
        Schema::create('patient_unit_invites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospital_id')->nullable();  
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id')->nullable(); 
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('doctor_unit_id');
            $table->unsignedBigInteger('doctor_sub_unit_id')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
            
            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('set null');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_unit_invites');
    }
};
