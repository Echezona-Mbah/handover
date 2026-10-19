<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dentists;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Ward;
use Illuminate\Http\Request;
use App\Models\DoctorDepartment;
use App\Models\DoctorUnit;
use App\Models\DoctorSubUnit;

class PatientController extends Controller
{
    public function create()
    {
        $wards = Ward::all(); 
        $units = DoctorUnit::whereNull('parent_id')->with('subUnits')->get();

        return view('parklane.add_patient', compact('wards', 'units'));
    }

    public function store(Request $request)
    {
            // dd ($request->all());     
         $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'email' => 'required|email|unique:patients,email',
            'mobile_number' => 'required|string|max:15',
            'marital_status' => 'required',
            'current_condition' => 'nullable|string',
            'ward' => 'required|exists:wards,id',   // ✅ ensure ward exists
            'doctor_unit_id' => 'required|string',
            'current_medications' => 'nullable|string',
            'allergies' => 'nullable|string',
            'urgency_status' => 'nullable|string',
            'address' => 'required|string',
        ]);
        // dd ($request->all());     
        // dd(auth()->user()->hospital_id);
        $selected = $request->doctor_unit_id;

        $doctorUnitId = null;
        $doctorSubUnitId = null;

        if (str_starts_with($selected, 'sub:')) {
            $doctorSubUnitId = (int) str_replace('sub:', '', $selected);
            $doctorUnitId = \App\Models\DoctorSubUnit::findOrFail($doctorSubUnitId)->doctor_unit_id;
        } else {
            $doctorUnitId = (int) str_replace('unit:', '', $selected);
            \App\Models\DoctorUnit::findOrFail($doctorUnitId);
        }

        $randomPatientNumber = rand(100000, 999999);        //  dd($randomPatientNumber);
        $patient = new Patient([
            'hospital_id'     => auth()->user()->hospital_id ?? null,
            'patient_number' =>  $randomPatientNumber,
            'full_name' => $request->full_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'marital_status' => $request->marital_status,
            'current_condition' => $request->current_condition,
            'ward_id'           => $request->ward,  // ✅ fixed to ward_id
            'doctor_unit_id'     => $doctorUnitId,
            'doctor_sub_unit_id' => $doctorSubUnitId, // null if none
                'current_medications' => $request->current_medications,
            'allergies' => $request->allergies,
            'urgency_status' => $request->urgency_status,
            'address' => $request->address,
        ]);
        
        $patient->save();

        return redirect()->route('add_patient')->with('success', 'Patient created successfully!');
    }

    public function showDoctorList()
{
    $hospitalId = auth()->user()->hospital_id;

    $patients = Patient::with('hospital')
        ->where('hospital_id', $hospitalId)
        ->orderBy('id', 'desc')
        ->paginate(10);

    $departments = DoctorDepartment::orderBy('name')->get();
    $doctorUnits = DoctorUnit::orderBy('name')->get();
    $doctorSubUnits = DoctorSubUnit::orderBy('name')->get();

    return view('parklane.patient_list', compact('patients', 'departments', 'doctorUnits', 'doctorSubUnits'));
}   

public function show($id)
{
    $patient = Patient::with([
        'ward',
        'doctorUnit.doctorDepartment',
        'doctorSubUnit',
        'doctorById',
    ])->findOrFail($id);

    $wards = Ward::all();
    $units = DoctorUnit::whereNull('parent_id')->with('subUnits')->get();

    $handoverNotes = \App\Models\HandoverNotes::where('patient_id', $patient->id)
        ->with('doctor.doctorDepartment')
        ->latest()
        ->get();

    $medications = \App\Models\Medication::where('patient_id', $patient->id)
        ->get();

    return view('parklane.show_patient', compact('patient', 'wards', 'units', 'handoverNotes', 'medications'));
}
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
           $wards = Ward::all(); 
        $units = DoctorUnit::whereNull('parent_id')->with('subUnits')->get();
        return view('parklane.update_patient', compact('patient','wards','units'));
    }

    public function patientUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'email' => 'required|email',
            'mobile_number' => 'required|string|max:15',
            'marital_status' => 'nullable|string|max:10',

            'current_condition' => 'nullable|string|max:20',
            'ward' => 'required|exists:wards,id',   // ✅ ensure ward exists
            'doctor_unit_id' => 'required|exists:doctor_units,id', // ✅ ensure unit exists
             'current_medications' => 'nullable|string|max:20',
            'allergies' => 'nullable|string|max:20',
            'urgency_status' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        $patient = Patient::findOrFail($id);

        $patient->update($validated);
        return redirect()->route('edit_patients', $patient->id)->with('success', 'Patient updated successfully!');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        // dd($doctor);
        $patient->delete();

        return redirect()->route('patient_list')->with('success', 'patient deleted successfully!');
    }

    public function assignToDoctor($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $doctors = Doctor::where('hospital_id', $patient->hospital_id)
        ->with('doctorDepartment')
        ->get();
        return view('parklane.patient_assign_doctor', compact('patient', 'doctors'));
    }

    public function saveAssignment(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $patient->doctor_assign = $request->doctor_id; 
        $patient->admin_assign_doctor = auth()->id();
        $patient->save();

        return redirect()->route('patient_list')->with('success', 'Patient assigned to doctor successfully!');
    }



    public function assignToDentist($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $dentists = Dentists::where('hospital_id', $patient->hospital_id)
        ->with('DentistDepartment')
        ->get();
        return view('parklane.patient_assign_dentist', compact('patient', 'dentists'));
    }

    public function saveAssignmentDentist(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $patient->dentist_assign = $request->dentist_id; 
        $patient->admin_assign_dentist = auth()->id();
        $patient->save();

        return redirect()->route('patient_list')->with('success', 'Patient assigned to Dentist successfully!');
    }




    
    



}
