<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('medications')) {
            return;
        }

        Schema::table('medications', function (Blueprint $table) {
            if (! Schema::hasColumn('medications', 'dentist_id')) {
                $table->unsignedBigInteger('dentist_id')->nullable();
            }

            if (! Schema::hasColumn('medications', 'frequency')) {
                $table->string('frequency')->nullable();
            }

            if (! Schema::hasColumn('medications', 'start_date')) {
                $table->date('start_date')->nullable();
            }

            if (! Schema::hasColumn('medications', 'end_date')) {
                $table->date('end_date')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Preserve medication data when rolling back this additive migration.
    }
};
