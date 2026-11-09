<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cross_role_patient_requests')) {
            Schema::create('cross_role_patient_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
                $table->unsignedBigInteger('hospital_id')->index();
                $table->string('request_type')->index();
                $table->string('source_role');
                $table->unsignedBigInteger('source_actor_id');
                $table->unsignedBigInteger('source_department_id')->nullable();
                $table->unsignedBigInteger('source_unit_id')->nullable();
                $table->unsignedBigInteger('source_sub_unit_id')->nullable();
                $table->string('target_role');
                $table->unsignedBigInteger('target_department_id');
                $table->unsignedBigInteger('target_unit_id')->nullable();
                $table->unsignedBigInteger('target_sub_unit_id')->nullable();
                $table->text('message')->nullable();
                $table->string('status')->default('pending')->index();
                $table->string('responded_by_role')->nullable();
                $table->unsignedBigInteger('responded_by_id')->nullable();
                $table->timestamp('responded_at')->nullable();
                $table->timestamps();
                $table->index(
                    ['target_role', 'target_department_id', 'target_unit_id', 'target_sub_unit_id', 'status'],
                    'crpr_target_team_status_idx'
                );
            });
        }
    }

    public function down(): void
    {
        // Retain patient handover history if this migration is rolled back.
    }
};
