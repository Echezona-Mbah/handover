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
        Schema::create('doctor_notifications', function (Blueprint $table) {
               $table->id();
                $table->unsignedBigInteger('department_id');
                $table->unsignedBigInteger('hospital_id');
                $table->unsignedBigInteger('doctor_unit_id');
                $table->unsignedBigInteger('doctor_sub_unit_id')->nullable();
                $table->unsignedBigInteger('patient_id');
                $table->string('title');
                $table->text('message');
                $table->timestamp('seen_at')->nullable();
                $table->timestamps();

               $table->index(
                ['department_id', 'hospital_id', 'doctor_unit_id', 'doctor_sub_unit_id'],
                'dn_dept_hosp_unit_sub_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_notifications');
    }
};
