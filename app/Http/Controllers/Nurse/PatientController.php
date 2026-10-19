<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\NurseHandover;
use App\Models\Patient;
use App\Models\Ward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function createPatient(): View
    {
        $nurse = Auth::guard('nurse')->user();
        abort_unless($nurse->hospital_id && $nurse->ward_id, 403, 'Your nurse account must be assigned to a hospital ward before registering patients.');

        $ward = Ward::where('hospital_id', $nurse->hospital_id)
            ->findOrFail($nurse->ward_id);

        return view('parklane.nurses.add_patient', compact('ward'));
    }

    public function storePatient(Request $request): RedirectResponse
    {
        $nurse = Auth::guard('nurse')->user();
        abort_unless($nurse->hospital_id && $nurse->ward_id, 403, 'Your nurse account must be assigned to a hospital ward before registering patients.');

        $maritalStatus = $request->input('marital_status');
        if (is_string($maritalStatus)) {
            $normalizedMaritalStatus = strtolower((string) preg_replace('/[\s_-]+/', '', trim($maritalStatus)));
            $request->merge([
                'marital_status' => match ($normalizedMaritalStatus) {
                    'married' => 'Married',
                    'unmarried' => 'single',
                    default => $maritalStatus,
                },
            ]);
        }

        Ward::where('hospital_id', $nurse->hospital_id)
            ->findOrFail($nurse->ward_id);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date', 'before_or_equal:today'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'email' => ['required', 'email', 'max:255', 'unique:patients,email'],
            'mobile_number' => ['required', 'string', 'max:15'],
            'marital_status' => ['nullable', Rule::in(['Married', 'single'])],
            'current_condition' => ['nullable', 'string', 'max:255'],
            'current_medications' => ['nullable', 'string', 'max:1000'],
            'allergies' => ['nullable', 'string', 'max:1000'],
            'urgency_status' => ['nullable', Rule::in(['routine', 'urgent', 'emergency'])],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        do {
            $patientNumber = (string) random_int(100000, 999999);
        } while (Patient::where('patient_number', $patientNumber)->exists());

        $patient = Patient::create([
            ...$validated,
            'patient_number' => $patientNumber,
            'pt_number' => Patient::generatePtNumber(),
            'hospital_id' => $nurse->hospital_id,
            'nurse_id' => $nurse->id,
            'ward_id' => $nurse->ward_id,
            'urgency_status' => $validated['urgency_status'] ?? 'routine',
        ]);

        return redirect()
            ->route('nurse.show_patient', $patient)
            ->with('success', 'Patient registered successfully.');
    }

    public function myPatients()
    {
        $nurse = Auth::guard('nurse')->user();
        $nurse->load('ward');

        $patients = Patient::where('ward_id', $nurse->ward_id)
            ->where('hospital_id', $nurse->hospital_id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('parklane.nurses.patient', compact('patients', 'nurse'));
    }

    public function show($patient_id)
    {
        // $patient = Patient::findOrFail($id);
        $nurse = Auth::guard('nurse')->user();

        $patient = Patient::with(['ward', 'doctorById'])->where('id', $patient_id)
            ->where('hospital_id', $nurse->hospital_id)
            ->firstOrFail();

        $medications = Medication::where('patient_id', $patient_id)->latest()->get();
        $handoverNotes = NurseHandover::with('ward')->where('patient_id', $patient_id)->latest()->get();

        return view('parklane.nurses.show_patient', compact('patient', 'medications', 'handoverNotes'));
    }

    public function showHandover($patient_id)
    {
        $nurse = Auth::guard('nurse')->user();

        $patient = Patient::where('id', $patient_id)
            ->where('hospital_id', $nurse->hospital_id)
            ->firstOrFail();

        return view('parklane.nurses.handovernote', compact('patient'));
    }

    public function storeHandover(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'summary' => 'required|string',
            'alerts' => 'required|string',
            'pending_tasks' => 'required|string',
        ]);

        $validated['nurse_id'] = Auth::guard('nurse')->id();
        $validated['hospital_id'] = Auth::guard('nurse')->user()->hospital_id;
        $validated['ward_id'] = Auth::guard('nurse')->user()->ward_id;

        NurseHandover::create($validated);

        return redirect()->route('nurse.patient', $request->patient_id)
            ->with('success', 'Handover notes submitted successfully.');
    }

    public function SeeHandoverNote($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $handoverNotes = NurseHandover::where('patient_id', $patient_id)->latest()->get();

        return view('nurse.handovershow', compact('patient', 'handoverNotes'));
    }

    public function editHandoverNote($id)
    {
        $handoverNote = NurseHandover::findOrFail($id);

        return view('parklane.nurses.update_handoverNote', compact('handoverNote'));
    }

    public function updateHandoverNote(Request $request, $id)
    {
        $validated = $request->validate([
            'summary' => 'required|string',
            'alerts' => 'required|string',
            'pending_tasks' => 'required|string',
        ]);

        $handoverNote = NurseHandover::findOrFail($id);
        $handoverNote->update($validated);

        return redirect()->route('nurse.show_patient', $handoverNote->patient_id)
            ->with('success', 'Handover note updated successfully.');
    }

    public function store($patientId)
    {
        $patient = Patient::findOrFail($patientId);

        return view('parklane.nurses.add_medication', compact('patient'));
    }

    public function storeMedication(Request $request)
    {
        $validated = $request->validate([
            'medication' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'instructions' => 'nullable|string',
        ]);

        Medication::create([
            'patient_id' => $request->patient_id,
            'medication' => $validated['medication'],
            'dosage' => $validated['dosage'],
            'frequency' => $validated['frequency'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],

            'instructions' => $request->instructions,
            'nurse_id' => Auth::guard('nurse')->user()->id,
            'hospital_id' => Auth::guard('nurse')->user()->hospital_id,
        ]);

        return redirect()->back()->with('success', 'Medication plan added successfully.');
    }

    // Show edit form
    public function editMedication($id)
    {
        $medication = Medication::findOrFail($id);

        return view('parklane.nurses.update_medication', compact('medication'));
    }

    // Update the medication
    public function updateMedication(Request $request, $id)
    {
        $validated = $request->validate([
            'medication' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'instructions' => 'nullable|string',
        ]);

        $medication = Medication::findOrFail($id);

        $medication->update([
            'medication' => $validated['medication'],
            'dosage' => $validated['dosage'],
            'frequency' => $validated['frequency'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'instructions' => $request->instructions,
            'nurse_id ' => auth()->guard('nurse')->id(),
            'hospital_id' => auth()->guard('nurse')->user()->hospital_id,
        ]);

        return redirect()->back()->with('success', 'Medication updated successfully!');
    }

    public function NursePatientdestroy($id)
    {
        $medication = Medication::findOrFail($id);
        $patientId = $medication->patient_id;
        $medication->delete();

        return redirect()->route('nurse.show_patient', $patientId)
            ->with('success', 'Medication deleted successfully!');
    }
}
