<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Models\DoctorNotification;
use App\Models\DoctorSubUnit;
use App\Models\DoctorUnit;
use App\Models\HandoverNotes;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\PatientUnitInvite;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str; 

class DoctorController extends Controller
{
    public function unitDoctors()
{
    $doctor    = Auth::guard('doctor')->user();
    $unitId    = $doctor->doctor_unit_id;
    $subUnitId = $doctor->doctor_sub_unit_id;

    // If doctor belongs to a sub-unit, show sub-unit doctors
    // Otherwise show all doctors in the main unit
    if ($subUnitId) {
        $doctors = Doctor::where('doctor_sub_unit_id', $subUnitId)
            ->where('hospital_id', $doctor->hospital_id)
            ->where('id', '!=', $doctor->id) // exclude self
            ->get();

        $unitLabel = optional(\App\Models\DoctorSubUnit::find($subUnitId))->name ?? 'Sub-Unit';
    } else {
        $doctors = Doctor::where('doctor_unit_id', $unitId)
            ->where('hospital_id', $doctor->hospital_id)
            ->where('id', '!=', $doctor->id) // exclude self
            ->get();

        $unitLabel = optional(\App\Models\DoctorUnit::find($unitId))->name ?? 'Unit';
    }

    return view('parklane.doctor.unit_doctors', compact('doctors', 'unitLabel', 'doctor'));
}
}
