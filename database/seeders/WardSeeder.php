<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NurseDepartment;
use App\Models\Ward;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'Emergency Medicine Unit',
            'Paediatrics Unit',
            'Special Surgery 1',
            'Special Surgery 2',
            'General Surgery 1',
            'General Surgery 2',
            'Maternity 1',
            'Maternity 2',
            'General Medicine Unit',
            'Medicine Unit 2',
            'Oncology Units',
            'Intensive Care',
            'Theaters',
            'Continuing Education and Research Unit',
        ];

        foreach ($units as $name) {
            NurseDepartment::firstOrCreate(['name' => $name]);
        }

        $wards = [
            ['A/E/S', 'Ward', 'Yellow', 'Emergency Medicine Unit'],
            ['A/E/M', 'Ward', 'Yellow', 'Emergency Medicine Unit'],
            ['ISO WD', 'Ward', 'White', 'Emergency Medicine Unit'],
            ['CHOP Clinic', 'Clinic', null, 'Paediatrics Unit'],
            ['CHER', 'Ward', 'Yellow', 'Paediatrics Unit'],
            ['PICU', 'Ward', 'White', 'Paediatrics Unit'],
            ['NBSC', 'Ward', 'White', 'Paediatrics Unit'],
            ['6A', 'Ward', 'Pink / Yellow', 'Paediatrics Unit'],
            ['6B', 'Ward', 'Pink / Yellow', 'Paediatrics Unit'],
            ['WD 1', 'Ward', 'Purple / Yellow', 'Special Surgery 1'],
            ['ENT Clinic', 'Clinic', null, 'Special Surgery 1'],
            ['Dental Clinic', 'Clinic', null, 'Special Surgery 1'],
            ['Eye WD', 'Ward', 'White', 'Special Surgery 2'],
            ['NSW', 'Ward', 'Blue', 'Special Surgery 2'],
            ['Eye Clinic', 'Clinic', null, 'Special Surgery 2'],
            ['SOP', 'Clinic', null, 'General Surgery 1'],
            ['WD 3', 'Ward', 'Sky Blue', 'General Surgery 1'],
            ['WD 4', 'Ward', 'Sky Blue', 'General Surgery 1'],
            ['WD 8', 'Ward', 'Yellow / Pink', 'General Surgery 1'],
            ['WD 2', 'Ward', 'Sea Green', 'General Surgery 2'],
            ['WD 9', 'Ward', 'Pink / Ashley', 'General Surgery 2'],
            ['Orth Clinic', 'Clinic', null, 'General Surgery 2'],
            ['Wound Care Clinic', 'Clinic', null, 'General Surgery 2'],
            ['AN Clinic', 'Clinic', null, 'Maternity 1'],
            ['Gynae Clinic', 'Clinic', null, 'Maternity 1'],
            ['LWD', 'Ward', 'Light Blue', 'Maternity 1'],
            ['PNW', 'Ward', 'Sweet Pink', 'Maternity 1'],
            ['ANW', 'Ward', 'Sweet Pink', 'Maternity 1'],
            ['SCW', 'Ward', 'Sea Green', 'Maternity 1'],
            ['WD 10', 'Ward', 'Sea Green', 'Maternity 1'],
            ['Family Planning', 'Clinic', null, 'Maternity 2'],
            ['Family Planning / Old Site', 'Clinic', null, 'Maternity 2'],
            ['MOP', 'Clinic', null, 'General Medicine Unit'],
            ['Haemath Clinic', 'Clinic', null, 'General Medicine Unit'],
            ['ART Clinic', 'Clinic', null, 'General Medicine Unit'],
            ['Haem WD', 'Ward', 'Pink / Blue', 'General Medicine Unit'],
            ['Endoscopy Rooms', 'Clinic', null, 'General Medicine Unit'],
            ['Radiation Medicine', 'Clinic', null, 'General Medicine Unit'],
            ['Family Medicine', 'Clinic', null, 'General Medicine Unit'],
            ['Chest Unit', 'Clinic', null, 'General Medicine Unit'],
            ['Mini GOPD Old Site', 'Clinic', null, 'General Medicine Unit'],
            ['Mini GOPD', 'Clinic', null, 'General Medicine Unit'],
            ['Nurses Bay (Ward)', 'Ward', null, 'General Medicine Unit'],
            ['ECG Room', 'Clinic', null, 'General Medicine Unit'],
            ['XRAY', 'Clinic', null, 'General Medicine Unit'],
            ['Health Visiting Unit', 'Clinic', null, 'General Medicine Unit'],
            ['PEFER', 'Clinic', null, 'General Medicine Unit'],
            ['FMW', 'Ward', 'Pink', 'Medicine Unit 2'],
            ['MMW', 'Ward', 'Sea Green', 'Medicine Unit 2'],
            ['MWEX', 'Ward', 'Sea Green', 'Medicine Unit 2'],
            ['PSYCH', 'Ward', 'Sea Green', 'Medicine Unit 2'],
            ['P/S', 'Ward', 'White', 'Medicine Unit 2'],
            ['Renal Unit', 'Ward', null, 'Medicine Unit 2'],
            ['Radiotherapy', 'Clinic', null, 'Oncology Units'],
            ['Onco Clinic', 'Clinic', null, 'Oncology Units'],
            ['ONC/CL', 'Clinic', 'Pink / Blue', 'Oncology Units'],
            ['ONCO Ward', 'Ward', null, 'Oncology Units'],
            ['ONCO Wound Care Clinic', 'Clinic', null, 'Oncology Units'],
            ['ICU', 'Ward', 'White', 'Intensive Care'],
            ['Staff Clinic', 'Clinic', null, 'Intensive Care'],
            ['Clinical Measurement', 'Clinic', null, 'Intensive Care'],
            ['A/E Theatre', 'Theatre', null, 'Theaters'],
            ['Main Theatre', 'Theatre', null, 'Theaters'],
            ['Anesthesia Recovery Room', 'Theatre', null, 'Theaters'],
            ['CTU', 'Theatre', null, 'Theaters'],
            ['CAT Lab', 'Theatre', null, 'Theaters'],
            ['Eye Theatre', 'Theatre', null, 'Theaters'],
            ['CERU', 'Unit', null, 'Continuing Education and Research Unit'],
        ];


        foreach ($wards as [$name, $category, $linenColor, $unitName]) {
            $unit = NurseDepartment::where('name', $unitName)->firstOrFail();
            $ward = Ward::firstOrNew(['name' => $name]);
            $ward->hospital_id ??= 1;
            $ward->category = $category;
            $ward->linen_color = $linenColor;
            $ward->nurse_department_id = $unit->id;
            $ward->save();
        }
    }
}
