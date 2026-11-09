<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('handover_notes')) {
            return;
        }

        Schema::table('handover_notes', function (Blueprint $table) {
            if (Schema::hasColumn('handover_notes', 'doctor_id')) {
                $table->foreignId('doctor_id')->nullable()->change();
            }

            if (! Schema::hasColumn('handover_notes', 'dentist_id')) {
                $table->foreignId('dentist_id')
                    ->nullable()
                    ->constrained('dentists')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        // Preserve existing handover notes and dentist attribution on rollback.
    }
};
