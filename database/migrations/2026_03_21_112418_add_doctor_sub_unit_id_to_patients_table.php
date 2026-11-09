<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Some long-lived installations predate patients.doctor_unit_id.  MySQL
        // rejects an `after()` reference to a missing column, even though the
        // new nullable column itself is valid.  Keep the migration additive in
        // both schema versions.
        $afterDoctorUnit = Schema::hasColumn('patients', 'doctor_unit_id');

        Schema::table('patients', function (Blueprint $table) use ($afterDoctorUnit) {
            $column = $table->unsignedBigInteger('doctor_sub_unit_id')->nullable();
            if ($afterDoctorUnit) {
                $column->after('doctor_unit_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('doctor_sub_unit_id');
        });
    }
};
