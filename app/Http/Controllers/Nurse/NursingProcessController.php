<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\NursingProcessRecord;
use App\Models\Patient;
use App\Services\NursingHierarchy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NursingProcessController extends Controller
{
    public function create(Patient $patient): View
    {
        $nurse = Auth::guard('nurse')->user();
        $this->authorizeWardPatient($patient, $nurse);

        return view('parklane.nurses.nursing_process_create', compact('patient'));
    }

    public function store(Request $request, Patient $patient): RedirectResponse
    {
        $nurse = Auth::guard('nurse')->user();
        $this->authorizeWardPatient($patient, $nurse);

        $validated = $request->validate([
            'assessment' => ['required', 'string', 'max:10000'],
            'diagnosis' => ['required', 'string', 'max:10000'],
            'planning' => ['required', 'string', 'max:10000'],
            'implementation' => ['required', 'string', 'max:10000'],
            'evaluation' => ['required', 'string', 'max:10000'],
        ]);

        NursingProcessRecord::create([
            ...$validated,
            'hospital_id' => $nurse->hospital_id,
            'ward_id' => $nurse->ward_id,
            'patient_id' => $patient->id,
            'nurse_id' => $nurse->id,
        ]);

        return redirect()
            ->route('nurse.show_patient', $patient)
            ->with('success', 'Nursing process record saved.');
    }

    private function authorizeWardPatient(Patient $patient, object $nurse): void
    {
        abort_unless(
            (int) $patient->hospital_id === (int) $nurse->hospital_id
                && (int) $patient->ward_id === (int) $nurse->ward_id
                && is_null($patient->discharged_at)
                && in_array(NursingHierarchy::role($nurse), ['ward_nurse', 'ward_supervision'], true),
            403
        );
    }
}
