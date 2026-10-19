<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         if (Schema::hasTable('nursing_statistics')) {
            return;
        }
        Schema::create('nursing_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->nullOnDelete();
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->foreignId('nurse_id')->constrained('nurses')->cascadeOnDelete();
            $table->date('stats_date');
            $table->unsignedInteger('admissions')->default(0);
            $table->unsignedInteger('discharges')->default(0);
            $table->unsignedInteger('transfers')->default(0);
            $table->unsignedInteger('deaths')->default(0);
            $table->unsignedInteger('bed_occupied')->default(0);
            $table->unsignedInteger('bed_vacant')->default(0);
            $table->unsignedInteger('clinic_attendance')->default(0);
            $table->unsignedInteger('specialized_service_count')->default(0);
            $table->longText('notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_statistics');
    }
};

