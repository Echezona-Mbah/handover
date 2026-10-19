<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use App\Models\DentistUnit;
use App\Models\HandoverNotes;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{


        public function addPatients()
    {
        $dentist = Auth::guard('dentist')->user();
        $wards = Ward::all();
         $DentistUnits = DentistUnit::all();


    
        return view('parklane.dentist.add_patient', compact('wards','DentistUnits'));
    }





    public function storeDentistAddPatients(Request $request)
{
    
    $request->validate([
        'full_name' => 'required|string|max:255',
        'dob' => 'required|date',
        'gender' => 'required|in:male,female',
        'email' => 'required|email|unique:patients,email',
        'mobile_number' => 'required|string|max:15',
        'marital_status' => 'nullable|string',
        'current_condition' => 'required|string',
        'ward' => 'required|exists:wards,id',
        'DentistUnit' => 'required',
        'current_medications' => 'nullable|string',
        'allergies' => 'nullable|string',
        'urgency_status' => 'required|string',
        'address' => 'nullable|string',
    ]);

    $dentist = Auth::guard('dentist')->user();

    Patient::create([
        // 'doctor_assign' => $doctor->id,
        'hospital_id' => $dentist->hospital_id,
        'full_name' => $request->full_name,
        'dob' => $request->dob,
        'gender' => $request->gender,
        'email' => $request->email,
        'mobile_number' => $request->mobile_number,
        'marital_status' => $request->marital_status,
        'current_condition' => $request->current_condition,
        'ward_id' => $request->ward,
        'dentist_unit_id' => $request->DentistUnit,
        'current_medications' => $request->current_medications,
        'allergies' => $request->allergies,
        'urgency_status' => $request->urgency_status,
        'address' => $request->address,
    ]);

    return redirect()->route('dentist.add_patient')->with('success', 'Patient added successfully!');
}












    public function myPatients()
    {
        $dentist = Auth::guard('dentist')->user();
    
        $patients = Patient::where('doctor_assign', $dentist->id)
            ->where('hospital_id', $dentist->hospital_id)
            ->orderBy('id', 'desc')
            ->paginate(10);
    
        return view('parklane.dentist.patient_assign', compact('patients'));
    }

    public function store($patientId)
    {
        $patient = Patient::findOrFail($patientId);
    
        return view('parklane.dentist.add_medication', compact('patient'));
    }


    

    public function storeMedication(Request $request)
    {
        $validated = $request->validate([
            'medication'   => 'required|string|max:255',
            'dosage'       => 'required|string|max:255',
            'frequency'       => 'required',
            'start_date'       => 'required',
            'end_date'       => 'required',
            'instructions' => 'nullable|string',
        ]);
    
        Medication::create([
            'patient_id'   => $request->patient_id,
            'medication'   => $validated['medication'],
            'dosage'       => $validated['dosage'],
            'frequency'       => $validated['frequency'],
            'start_date'       => $validated['start_date'],
            'end_date'       => $validated['end_date'],

            'instructions' => $request->instructions,
            'dentist_id'    => Auth::guard('dentist')->user()->id,
            'hospital_id'  => Auth::guard('dentist')->user()->hospital_id,
        ]);
    
        return redirect()->back()->with('success', 'Medication plan added successfully.');
    }



     // Show edit form
     public function editMedication($id)
     {
         $medication = Medication::findOrFail($id);
         return view('parklane.dentist.update_medication', compact('medication'));
     }
 
     // Update the medication
     public function updateMedication(Request $request, $id)
     {
         $validated = $request->validate([
             'medication'   => 'required|string|max:255',
             'dosage'       => 'required|string|max:255',
             'frequency'       => 'required',
             'start_date'       => 'required',
             'end_date'       => 'required',
             'instructions' => 'nullable|string',
         ]);
 
         $medication = Medication::findOrFail($id);
 
         $medication->update([
             'medication'   => $validated['medication'],
             'dosage'       => $validated['dosage'],
             'frequency'       => $validated['frequency'],
             'start_date'       => $validated['start_date'],
             'end_date'       => $validated['end_date'],
             'instructions' => $request->instructions,
             'dentist_id'    => auth()->guard('dentist')->id(),
             'hospital_id'  => auth()->guard('dentist')->user()->hospital_id,
         ]);
 
         return redirect()->back()->with('success', 'Medication updated successfully!');
     }
 
     public function edit($id)
     {
         $patient = Patient::findOrFail($id);
         
         return view('parklane.dentist.update_patient', compact('patient'));
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
             'ward' => 'nullable|string|max:20',
             'current_medications' => 'nullable|string|max:20',
             'allergies' => 'nullable|string|max:20',
             'urgency_status' => 'nullable|string|max:20',
 
             'occupation' => 'nullable|string|max:255',
             'blood_group' => 'required|string|max:5',
             'blood_pressure' => 'nullable|string|max:255',
             'sugar_level' => 'nullable|string|max:255',
             'address' => 'nullable|string|max:255',
             'city' => 'nullable|string|max:255',
             'state' => 'nullable|string|max:255',
         ]);
         $patient = Patient::findOrFail($id);
 
         $patient->update($validated);
         return redirect()->route('dentist.edit_patients', $patient->id)->with('success', 'Patient updated successfully!');
     }
 
     public function show($patient_id)
     {
         // $patient = Patient::findOrFail($id);
         $dentist = Auth::guard('dentist')->user();
     
         $patient = Patient::where('id', $patient_id)
             ->where('hospital_id', $dentist->hospital_id)
             ->firstOrFail();
     
         $medications = Medication::where('patient_id', $patient_id)->get();
         $handoverNotes = HandoverNotes::where('patient_id', $patient_id)->latest()->get();
 
         return view('parklane.dentist.show_patient', compact('patient', 'medications','handoverNotes'));
     }
 
     public function destroy($id)
     {
         $medication = Medication::findOrFail($id);
         $patientId = $medication->patient_id; 
         $medication->delete();
     
         return redirect()->route('dentist.show_patient', $patientId)
                          ->with('success', 'Medication deleted successfully!');
     }
 
     public function showHandover($patient_id)
     {
         $dentist = Auth::guard('dentist')->user();
     
         $patient = Patient::where('id', $patient_id)
             ->where('hospital_id', $dentist->hospital_id)
             ->firstOrFail();
     
         return view('parklane.dentist.handovernote', compact('patient'));
     }
 
     public function storeHandover(Request $request)
     {
         $validated = $request->validate([
             'patient_id' => 'required|exists:patients,id',
             'summary' => 'required|string',
             'alerts' => 'required|string',
             'pending_tasks' => 'required|string',
         ]);
 
         $validated['dentist_id'] = Auth::guard('dentist')->id();
         $validated['hospital_id'] = Auth::guard('dentist')->user()->hospital_id;
 
         HandoverNotes::create($validated);
 
         return redirect()->route('dentist.patient', $request->patient_id)
                         ->with('success', 'Handover notes submitted successfully.');
     }
 
     public function SeeHandoverNote($patient_id)
     {
         $patient = Patient::findOrFail($patient_id);
         $handoverNotes = HandoverNotes::where('patient_id', $patient_id)->latest()->get();
 
         return view('handover.show', compact('patient', 'handoverNotes'));
     }
 
     public function editHandoverNote($id)
     {
         $handoverNote = HandoverNotes::findOrFail($id);
         
         return view('parklane.dentist.update_handoverNote', compact('handoverNote'));
     }
     public function updateHandoverNote(Request $request, $id)
     {
         $validated = $request->validate([
             'summary' => 'required|string',
             'alerts' => 'required|string',
             'pending_tasks' => 'required|string',
         ]);
 
         $handoverNote = HandoverNotes::findOrFail($id);
         $handoverNote->update($validated);
 
         return redirect()->route('dentist.show_patient', $handoverNote->patient_id)
                         ->with('success', 'Handover note updated successfully.');
     }
 














}
