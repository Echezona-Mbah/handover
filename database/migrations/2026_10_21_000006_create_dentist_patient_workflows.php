<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('patients', 'dentist_department_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->unsignedBigInteger('dentist_department_id')->nullable();
            });
        }

        if (! Schema::hasTable('dentist_patient_unit_invites')) {
            Schema::create('dentist_patient_unit_invites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
                $table->unsignedBigInteger('hospital_id')->index();
                $table->unsignedBigInteger('dentist_department_id');
                $table->unsignedBigInteger('dentist_unit_id')->nullable();
                $table->unsignedBigInteger('dentist_id');
                $table->text('instructions')->nullable();
                $table->string('status')->default('pending')->index();
                $table->unsignedBigInteger('responded_by_dentist_id')->nullable();
                $table->timestamp('responded_at')->nullable();
                $table->timestamps();
                $table->index(['dentist_department_id', 'dentist_unit_id', 'status'], 'dpiu_target_status_idx');
            });
        }

        if (! Schema::hasTable('dentist_patient_transfers')) {
            Schema::create('dentist_patient_transfers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
                $table->unsignedBigInteger('hospital_id')->index();
                $table->unsignedBigInteger('from_department_id')->nullable();
                $table->unsignedBigInteger('from_unit_id')->nullable();
                $table->unsignedBigInteger('to_department_id')->nullable();
                $table->unsignedBigInteger('to_unit_id')->nullable();
                $table->unsignedBigInteger('requested_by_dentist_id');
                $table->unsignedBigInteger('responded_by_dentist_id')->nullable();
                $table->string('status')->default('pending')->index();
                $table->text('notes')->nullable();
                $table->timestamp('responded_at')->nullable();
                $table->timestamps();
                $table->index(['to_department_id', 'to_unit_id', 'status'], 'dpt_target_status_idx');
            });
        }
    }

    public function down(): void
    {
        // Keep patient assignments and workflow history intact on rollback.
    }
};
