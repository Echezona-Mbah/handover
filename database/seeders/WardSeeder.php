<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ward;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wards = [
            ['A/E/S', 'Ward', 'Yellow'], ['A/E/M', 'Ward', 'Yellow'], ['CHER', 'Ward', 'Yellow'],
            ['PICU', 'Ward', 'White'], ['WD 1', 'Ward', 'Purple / Yellow'], ['WD 2', 'Ward', 'Sea Green'],
            ['WD 3', 'Ward', 'Sky Blue'], ['WD 4', 'Ward', 'Sky Blue'], ['LWD', 'Ward', 'Light Blue'],
            ['Eye WD', 'Ward', 'White'], ['NBSC', 'Ward', 'White'], ['PNW', 'Ward', 'Sweet Pink'],
            ['ANW', 'Ward', 'Sweet Pink'], ['SCW', 'Ward', 'Sea Green'], ['6A', 'Ward', 'Pink / Yellow'],
            ['6B', 'Ward', 'Pink / Yellow'], ['FMW', 'Ward', 'Pink'], ['MMW', 'Ward', 'Sea Green'],
            ['MWEX', 'Ward', 'Sea Green'], ['PSYCH', 'Ward', 'Sea Green'], ['ONC/CL', 'Ward', 'Pink / Blue'],
            ['NSW', 'Ward', 'Blue'], ['P/S', 'Ward', 'White'], ['ICU', 'Ward', 'White'],
            ['WD 8', 'Ward', 'Yellow / Pink'], ['WD 9', 'Ward', 'Pink / Ashley'], ['WD 10', 'Ward', 'Sea Green'],
            ['ISO WD', 'Ward', 'White'], ['Haem WD', 'Ward', 'Pink / Blue'],
            ['MOP', 'Clinic', null], ['SOP', 'Clinic', null], ['Gynae Clinic', 'Clinic', null],
            ['Orth Clinic', 'Clinic', null], ['Eye Clinic', 'Clinic', null], ['Onco Clinic', 'Clinic', null],
            ['ENT Clinic', 'Clinic', null], ['AN Clinic', 'Clinic', null], ['Staff Clinic', 'Clinic', null],
            ['CHOP Clinic', 'Clinic', null], ['Haemath Clinic', 'Clinic', null], ['PEFER', 'Clinic', null],
            ['Dental Clinic', 'Clinic', null], ['ECG Room', 'Clinic', null], ['Family Medicine', 'Clinic', null],
            ['XRAY', 'Clinic', null], ['Radiotherapy', 'Clinic', null], ['Renal Unit', 'Clinic', null],
            ['Endoscopy Rooms', 'Clinic', null], ['Family Planning', 'Clinic', null], ['CERU', 'Clinic', null],
            ['Mini GOPD Old Site', 'Clinic', null], ['Family Planning / Old Site', 'Clinic', null],
            ['Chest Unit', 'Clinic', null], ['Health Visiting Unit', 'Clinic', null],
            ['Main Theatre', 'Theatre', null], ['A/E Theatre', 'Theatre', null], ['Eye Theatre', 'Theatre', null],
            ['CTU', 'Theatre', null], ['CAT Lab', 'Theatre', null],
        ];


        foreach ($wards as [$name, $category, $linenColor]) {
            Ward::updateOrCreate(
                ['name' => $name],
                ['hospital_id' => 1, 'category' => $category, 'linen_color' => $linenColor]
            );
        }
    }
}
