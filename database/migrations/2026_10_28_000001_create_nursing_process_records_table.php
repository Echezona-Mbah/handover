<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_process_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospital_id')->nullable()->index();
            $table->unsignedBigInteger('ward_id')->nullable()->index();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('nurse_id')->nullable()->index();
            $table->longText('assessment');
            $table->longText('diagnosis');
            $table->longText('planning');
            $table->longText('implementation');
            $table->longText('evaluation');
            $table->timestamps();
            $table->index(['patient_id', 'created_at'], 'nursing_process_patient_history_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_process_records');
    }
};
