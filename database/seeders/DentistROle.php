<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DentistROle extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dentist_role = [
            'Consultant',
            'Senior Registrar',
            'Registrar',
            'Dental Officer',
            'Dental Assistant',
        ];

        $records = array_map(fn($name) => [
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now()
        ], $dentist_role);

        DB::table('dentist_roles')->insert($records);
    }
    
}
