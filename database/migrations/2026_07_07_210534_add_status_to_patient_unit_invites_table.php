<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('patient_unit_invites', 'status')) {
            Schema::table('patient_unit_invites', function (Blueprint $table) {
                $table->string('status')->default('pending')->after('instructions');
            });
        }

        if (!Schema::hasColumn('patient_unit_invites', 'responded_by_doctor_id')) {
            Schema::table('patient_unit_invites', function (Blueprint $table) {
                $table->foreignId('responded_by_doctor_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('doctors')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('patient_unit_invites', 'responded_at')) {
            Schema::table('patient_unit_invites', function (Blueprint $table) {
                $table->timestamp('responded_at')->nullable()->after('responded_by_doctor_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('patient_unit_invites', 'responded_by_doctor_id')) {
            Schema::table('patient_unit_invites', function (Blueprint $table) {
                $table->dropConstrainedForeignId('responded_by_doctor_id');
            });
        }

        Schema::table('patient_unit_invites', function (Blueprint $table) {
            if (Schema::hasColumn('patient_unit_invites', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('patient_unit_invites', 'responded_at')) {
                $table->dropColumn('responded_at');
            }
        });
    }
};