<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (! Schema::hasColumn('patients', 'current_medications')) {
                $table->text('current_medications')->nullable();
            }

            if (! Schema::hasColumn('patients', 'allergies')) {
                $table->text('allergies')->nullable();
            }

            if (! Schema::hasColumn('patients', 'urgency_status')) {
                $table->string('urgency_status')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Keep clinical fields and data intact when rolling back this migration.
    }
};
