<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DentistDepartment extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dentist_departments = [
            'General Dentistry',
            'Orthodontics',
            'Periodontics',
            'Endodontics',
            'Prosthodontics',
            'Oral and Maxillofacial Surgery',
            'Pediatric Dentistry (Pedodontics)',
            'Oral Pathology',
            'Oral Medicine $ Radiology',
            'Public Health Dentistry',
            'Cosmetic Dentistry',
            'Geriatric Dentistry',


        ];

        $records = array_map(fn($name) => [
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now()
        ], $dentist_departments);

        DB::table('dentist_departments')->insert($records);
    }
    
}
