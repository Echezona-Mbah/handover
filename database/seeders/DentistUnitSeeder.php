<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DentistUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            'Oral maxillofacial surgery',
            'Restorative dentistry',
            'Preventive dentistry',
            'Child dental health',
            'Oral pathology and medicine',
        ];

        foreach ($units as $unit) {
            DB::table('dentist_units')->insert([
                'name' => $unit,
                'hospital_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
