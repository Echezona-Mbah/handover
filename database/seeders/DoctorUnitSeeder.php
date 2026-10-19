<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorUnit;
use App\Models\DoctorDepartment;

class DoctorUnitSeeder extends Seeder
{
    public function run(): void
    {
        $department = DoctorDepartment::first(); // get first department

        // Major Units
        $medicine = DoctorUnit::firstOrCreate([
            'name' => 'Medicine',
            'parent_id' => null,
            'doctor_department_id' => $department->id
        ]);

        $og = DoctorUnit::firstOrCreate([
            'name' => 'Obstetrics and Gynecology',
            'parent_id' => null,
            'doctor_department_id' => $department->id
        ]);

        $pediatrics = DoctorUnit::firstOrCreate([
            'name' => 'Pediatrics',
            'parent_id' => null,
            'doctor_department_id' => $department->id
        ]);

        $surgery = DoctorUnit::firstOrCreate([
            'name' => 'Surgery',
            'parent_id' => null,
            'doctor_department_id' => $department->id
        ]);

        // Medicine Sub Units
        $medicineSubs = [
            'Cardiology',
            'Neurology',
            'Dermatology',
            'Endocrinology',
            'Nephrology',
            'Pharmacotherapeutics',
            'Gastroenterology',
            'Respiratory',
            'Rheumatology',
            'Family Medicine',
            'Psychiatry'
        ];

        foreach ($medicineSubs as $unit) {
            DoctorUnit::firstOrCreate([
                'name' => $unit,
                'parent_id' => $medicine->id,
                'doctor_department_id' => $department->id
            ]);
        }

    }
}