<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorDepartment;
use App\Models\DoctorUnit;
use App\Models\DoctorSubUnit;

class DoctorDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Medicine' => [
                'Cardiology' => 5,
                'Neurology' => 3,
                'Dermatology' => 0,
                'Endocrinology' => 0,
                'Nephrology' => 2,
                'Pharmacotherapeutics' => 0,
                'Gastroenterology' => 0,
                'Respiratory' => 0,
            ],
            'Obstetrics and Gynecology' => [
                'Maternal Health' => 3,
                'Gynecologic Oncology' => 0,
                'Reproductive Endocrinology' => 2,
            ],
            'Pediatrics' => [
                'Neonatology' => 2,
                'Pediatric Cardiology' => 0,
                'Pediatric Neurology' => 0,
                'Hematology' => 0,
                'Gastroenterology' => 0,
                'Pulmonology' => 0,
                'Infectious Disease' => 0,
                'Nephrology' => 0,
            ],
            'Surgery' => [
                'General Surgery' => 5,
                'Orthopedic Surgery' => 3,
                'Plastic Surgery' => 0,
                'Neurosurgery' => 0,
                'Radiology' => 0,
                'Ophthalmology' => 0,
                'Urology' => 0,
                'Anesthesiology' => 0,
                'Pediatrics Surgery' => 0,
                'Cardiothoracic Surgery' => 2,
                'Radiation Oncology' => 0,
            ],
            'Pathology' => [
                'Hematology' => 0,
                'Chemical Pathology' => 0,
                'Microbiology' => 0,
                'Morbid Anatomy' => 0,
            ],
        ];


        foreach ($departments as $deptName => $units) {
            // Create Department
            $department = DoctorDepartment::firstOrCreate(['name' => $deptName]);

            foreach ($units as $unitName => $subUnitCount) {
                // Create Unit under Department
                $unit = DoctorUnit::firstOrCreate([
                    'doctor_department_id' => $department->id,
                    'name' => $unitName,
                ]);

                // Create Sub-units if count > 0
                if ($subUnitCount > 0) {
                    for ($i = 1; $i <= $subUnitCount; $i++) {
                        DoctorSubUnit::firstOrCreate([
                            'doctor_unit_id' => $unit->id,
                            'name' => $unitName . ' Unit ' . $i,
                        ]);
                    }
                }
            }
        }
    }
}

