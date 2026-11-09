<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasColumn('patient_unit_invites', 'doctor_sub_unit_id')) {
        $afterDoctorUnit = Schema::hasColumn('patient_unit_invites', 'doctor_unit_id');
        Schema::table('patient_unit_invites', function (Blueprint $table) use ($afterDoctorUnit) {
            $column = $table->unsignedBigInteger('doctor_sub_unit_id')->nullable();
            if ($afterDoctorUnit) {
                $column->after('doctor_unit_id');
            }
        });
    }
}


    public function down(): void
    {
        Schema::table('patient_unit_invites', function (Blueprint $table) {
            $table->dropColumn('doctor_sub_unit_id');
        });
    }
};
