<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('nursing_reports')) {
            return;
        }

        Schema::create('nursing_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->nullOnDelete();
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->foreignId('nurse_id')->constrained('nurses')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('report_type')->default('daily_shift');
            $table->date('report_date');
            $table->string('shift')->nullable();
            $table->longText('summary');
            $table->longText('incidents')->nullable();
            $table->longText('referrals')->nullable();
            $table->longText('administrative_notes')->nullable();
            $table->enum('status', ['submitted', 'needs_clarification', 'reviewed', 'approved'])->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_reports');
    }
};
