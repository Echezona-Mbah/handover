<?php

use App\Models\Hospital;
use App\Models\Nurses;
use App\Models\NursingReport;
use App\Models\User;
use App\Models\Ward;
use App\Services\NursingHierarchy;
use Illuminate\Support\Facades\Hash;

test('reports sent to both nursing recipients are hidden from unrelated unit heads', function () {
    $hospital = Hospital::create(['name' => 'Nursing report test hospital']);
    $unitHead = User::factory()->create([
        'hospital_id' => $hospital->id,
        'nursing_hierarchy_role' => 'unit_head',
    ]);
    $otherUnitHead = User::factory()->create([
        'hospital_id' => $hospital->id,
        'nursing_hierarchy_role' => 'unit_head',
    ]);
    $ward = Ward::create([
        'name' => 'Nursing report test ward',
        'hospital_id' => $hospital->id,
        'unit_head_id' => $unitHead->id,
    ]);
    Ward::create([
        'name' => 'Other nursing report test ward',
        'hospital_id' => $hospital->id,
        'unit_head_id' => $otherUnitHead->id,
    ]);
    $nurse = Nurses::create([
        'full_name' => 'Nursing report test nurse',
        'email' => 'nursing.report.test@example.test',
        'password' => Hash::make('temporary-password'),
        'hospital_id' => $hospital->id,
    ]);
    $report = NursingReport::create([
        'hospital_id' => $hospital->id,
        'ward_id' => $ward->id,
        'nurse_id' => $nurse->id,
        'report_type' => 'daily_shift',
        'report_date' => now()->toDateString(),
        'summary' => 'A report sent to the HNS Office and the assigned unit head.',
        'recipient_type' => 'user',
        'recipient_id' => $unitHead->id,
        'recipient_role' => 'hns_and_unit_head',
        'status' => 'submitted',
    ]);

    expect(NursingHierarchy::canViewReport($unitHead, $report))->toBeTrue()
        ->and(NursingHierarchy::canViewReport($otherUnitHead, $report))->toBeFalse();
});
