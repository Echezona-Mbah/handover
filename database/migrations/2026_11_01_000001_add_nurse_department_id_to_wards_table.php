<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const UNIT_ASSIGNMENTS = [
        'Emergency Medicine Unit' => ['A/E/S', 'A/E/M', 'ISO WD'],
        'Paediatrics Unit' => ['CHOP Clinic', 'CHER', 'PICU', 'NBSC', '6A', '6B'],
        'Special Surgery 1' => ['WD 1', 'ENT Clinic', 'Dental Clinic'],
        'Special Surgery 2' => ['Eye WD', 'NSW', 'Eye Clinic'],
        'General Surgery 1' => ['SOP', 'WD 3', 'WD 4', 'WD 8'],
        'General Surgery 2' => ['WD 2', 'WD 9', 'Orth Clinic'],
        'Maternity 1' => ['AN Clinic', 'Gynae Clinic', 'LWD', 'PNW', 'ANW', 'SCW', 'WD 10'],
        'Maternity 2' => ['Family Planning', 'Family Planning / Old Site'],
        'General Medicine Unit' => ['MOP', 'Haemath Clinic', 'Haem WD', 'Endoscopy Rooms', 'Family Medicine', 'Chest Unit', 'Mini GOPD Old Site', 'ECG Room', 'XRAY', 'Health Visiting Unit', 'PEFER'],
        'Medicine Unit 2' => ['FMW', 'MMW', 'MWEX', 'PSYCH', 'P/S', 'Renal Unit'],
        'Oncology Units' => ['Radiotherapy', 'Onco Clinic', 'ONC/CL'],
        'Intensive Care' => ['ICU', 'Staff Clinic'],
        'Theaters' => ['A/E Theatre', 'Main Theatre', 'Eye Theatre', 'CTU', 'CAT Lab'],
        'Continuing Education and Research Unit' => ['CERU'],
    ];

    public function up(): void
    {
        Schema::table('wards', function (Blueprint $table) {
            $table->foreignId('nurse_department_id')
                ->nullable()
                ->after('hospital_id')
                ->constrained('nurse_departments')
                ->nullOnDelete();
        });

        foreach (array_keys(self::UNIT_ASSIGNMENTS) as $unitName) {
            DB::table('nurse_departments')->insertOrIgnore([
                'name' => $unitName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (self::UNIT_ASSIGNMENTS as $unitName => $wardNames) {
            $unitId = DB::table('nurse_departments')->where('name', $unitName)->value('id');
            DB::table('wards')->whereIn('name', $wardNames)->update(['nurse_department_id' => $unitId]);
        }
    }

    public function down(): void
    {
        Schema::table('wards', function (Blueprint $table) {
            $table->dropConstrainedForeignId('nurse_department_id');
        });
    }
};
