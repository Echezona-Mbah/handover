<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (! Schema::hasColumn('patients', 'ward_id')) {
                $table->unsignedBigInteger('ward_id')->nullable();
            }

            if (! Schema::hasColumn('patients', 'pt_number')) {
                $table->string('pt_number')->nullable();
            }

            if (! Schema::hasColumn('patients', 'dentist_assign')) {
                $table->unsignedBigInteger('dentist_assign')->nullable();
            }

            if (! Schema::hasColumn('patients', 'admin_assign_dentist')) {
                $table->unsignedBigInteger('admin_assign_dentist')->nullable();
            }

            if (! Schema::hasColumn('patients', 'dentist_unit_id')) {
                $table->unsignedBigInteger('dentist_unit_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Keep assignment fields and existing patient assignments intact.
    }
};
