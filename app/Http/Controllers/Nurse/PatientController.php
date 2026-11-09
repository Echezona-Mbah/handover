<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\NurseHandover;
use App\Models\Patient;
use App\Models\NursingReport;
use App\Models\NursingQuery;
use App\Models\NursingQueryResponse;
use App\Models\Nurses;
use App\Models\User;
use App\Models\Ward;
use App\Notifications\NursingReportSubmitted;
use App\Notifications\NursingWardTransferNotification;
use App\Services\NursingHierarchy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'full_name' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date', 'before_or_equal:today'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'email' => ['nullable', 'email', 'max:255', 'unique:patients,email'],
            'mobile_number' => ['nullable', 'string', 'max:15'],
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
            ->whereNull('discharged_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $pendingWardTransfers = collect();
        if (NursingHierarchy::role($nurse) === 'ward_supervision') {
            $pendingWardTransfers = NursingQuery::with('ward')
                ->where('hospital_id', $nurse->hospital_id)
                ->where('recipient_type', 'nurse')
                ->where('recipient_id', $nurse->id)
                ->where('status', 'pending')
                ->latest()
                ->get()
                ->map(function (NursingQuery $query) use ($nurse) {
                    $details = json_decode($query->message, true);
                    if (($details['workflow'] ?? null) !== 'ward_patient_transfer'
                        || (int) ($details['destination_ward_id'] ?? 0) !== (int) $nurse->ward_id) {
                        return null;
                    }

                    $patient = Patient::where('hospital_id', $nurse->hospital_id)
                        ->whereKey($details['patient_id'] ?? 0)
                        ->first();

                    return $patient ? ['query' => $query, 'patient' => $patient, 'details' => $details] : null;
                })
                ->filter()
                ->values();
        }

        return view('parklane.nurses.patient', compact('patients', 'nurse', 'pendingWardTransfers'));
    }

    public function show($patient_id)
    {
        // $patient = Patient::findOrFail($id);
        $nurse = Auth::guard('nurse')->user();

        $patient = Patient::with(['ward', 'doctorById'])->where('id', $patient_id)
            ->where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->whereNull('discharged_at')
            ->firstOrFail();

        $medications = Medication::where('patient_id', $patient_id)->latest()->get();
        $handoverNotes = NurseHandover::with('ward')->where('patient_id', $patient_id)->latest()->get();
        $nursingReports = NursingReport::with('nurse')
            ->where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->where('patient_id', $patient_id)
            ->latest('report_date')
            ->latest('id')
            ->get();
        $nursingProcessRecords = $patient->nursingProcessRecords()->with('nurse')->get();
        $transferWards = Ward::where('hospital_id', $nurse->hospital_id)
            ->where('id', '!=', $patient->ward_id)
            ->whereHas('nurses', fn ($query) => $query
                ->whereIn('nursing_hierarchy_role', ['ward_supervision', 'ward_supervisor']))
            ->orderBy('name')
            ->get();

        return view('parklane.nurses.show_patient', compact('patient', 'medications', 'handoverNotes', 'nursingReports', 'nursingProcessRecords', 'transferWards'));
    }

    public function dischargePatient(Request $request, Patient $patient): RedirectResponse
    {
        $nurse = Auth::guard('nurse')->user();
        $patient = $this->activeWardPatient($patient, $nurse);

        $validated = $request->validate([
            'disposition' => ['required', Rule::in(['home', 'transfer'])],
            'destination_ward_id' => ['required_if:disposition,transfer', 'nullable', 'integer',
                Rule::exists('wards', 'id')->where(fn ($query) => $query
                    ->where('hospital_id', $nurse->hospital_id)
                    ->where('id', '!=', $nurse->ward_id))],
            'discharge_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validated['disposition'] === 'home') {
            DB::transaction(function () use ($patient, $nurse, $validated) {
                $lockedPatient = Patient::where('hospital_id', $nurse->hospital_id)
                    ->where('ward_id', $nurse->ward_id)
                    ->whereNull('discharged_at')
                    ->lockForUpdate()
                    ->findOrFail($patient->id);
                abort_if($this->hasPendingWardTransfer($lockedPatient, $nurse), 409, 'Resolve the pending ward transfer before discharging this patient home.');
                $lockedPatient->update([
                    'status' => 'discharged',
                    'discharge_notes' => $validated['discharge_notes'] ?? null,
                    'discharged_at' => now(),
                ]);
            });

            return redirect()->route('nurse.patient')
                ->with('success', 'Patient discharged home and removed from the ward nursing list.');
        }

        $destinationWard = Ward::with(['nurses' => fn ($query) => $query
            ->whereIn('nursing_hierarchy_role', ['ward_supervision', 'ward_supervisor'])
            ->orderBy('id')])
            ->where('hospital_id', $nurse->hospital_id)
            ->findOrFail($validated['destination_ward_id']);
        $supervisor = $destinationWard->nurses->first();
        abort_unless($supervisor, 422, 'The receiving ward has no assigned supervisor to approve the transfer.');

        $query = DB::transaction(function () use ($patient, $nurse, $destinationWard, $supervisor, $validated) {
            $lockedPatient = Patient::where('hospital_id', $nurse->hospital_id)
                ->where('ward_id', $nurse->ward_id)
                ->whereNull('discharged_at')
                ->lockForUpdate()
                ->findOrFail($patient->id);
            abort_if($this->hasPendingWardTransfer($lockedPatient, $nurse), 409, 'This patient already has a ward transfer awaiting approval.');

            $details = [
                'workflow' => 'ward_patient_transfer',
                'patient_id' => $lockedPatient->id,
                'patient_name' => $lockedPatient->full_name,
                'source_ward_id' => $lockedPatient->ward_id,
                'source_ward_name' => $lockedPatient->ward?->name,
                'destination_ward_id' => $destinationWard->id,
                'requester_id' => $nurse->id,
                'requester_name' => $nurse->full_name,
                'reason' => $validated['discharge_notes'] ?? '',
            ];

            return NursingQuery::create([
                'hospital_id' => $nurse->hospital_id,
                'ward_id' => $destinationWard->id,
                'sender_type' => 'nurse',
                'sender_id' => $nurse->id,
                'recipient_type' => 'nurse',
                'recipient_id' => $supervisor->id,
                'message' => json_encode($details, JSON_THROW_ON_ERROR),
                'status' => 'pending',
            ]);
        });

        $supervisor->notify(new NursingWardTransferNotification(
            'Patient transfer awaiting approval',
            $nurse->full_name.' requested to transfer '.$patient->full_name.' from '.$patient->ward?->name.' to '.$destinationWard->name.'.',
            $patient->id
        ));

        return back()->with('success', 'Transfer request sent. The patient will remain in this ward until the receiving ward supervisor accepts.');
    }

    public function respondToWardTransfer(Request $request, NursingQuery $query, string $decision): RedirectResponse
    {
        abort_unless(in_array($decision, ['accept', 'reject'], true), 404);
        $nurse = Auth::guard('nurse')->user();
        $details = json_decode($query->message, true);
        abort_unless(
            NursingHierarchy::role($nurse) === 'ward_supervision'
                && $query->status === 'pending'
                && $query->recipient_type === 'nurse'
                && (int) $query->recipient_id === (int) $nurse->id
                && (int) $query->hospital_id === (int) $nurse->hospital_id
                && ($details['workflow'] ?? null) === 'ward_patient_transfer'
                && (int) ($details['requester_id'] ?? 0) === (int) $query->sender_id
                && $query->sender_type === 'nurse'
                && (int) ($details['destination_ward_id'] ?? 0) === (int) $nurse->ward_id,
            403
        );

        DB::transaction(function () use ($query, $decision, $nurse, $details) {
            $lockedQuery = NursingQuery::whereKey($query->id)->lockForUpdate()->firstOrFail();
            abort_unless($lockedQuery->status === 'pending', 409, 'This transfer request has already been answered.');
            $patient = Patient::where('hospital_id', $nurse->hospital_id)
                ->whereKey($details['patient_id'])
                ->lockForUpdate()
                ->firstOrFail();
            $sourceNurse = Nurses::where('hospital_id', $nurse->hospital_id)
                ->whereKey($details['requester_id'])
                ->firstOrFail();

            if ($decision === 'accept') {
                abort_unless(
                    (int) $patient->ward_id === (int) $details['source_ward_id']
                        && (int) $sourceNurse->ward_id === (int) $details['source_ward_id']
                        && is_null($patient->discharged_at)
                        && (int) $lockedQuery->ward_id === (int) $nurse->ward_id,
                    409,
                    'The patient is no longer available for transfer to this ward.'
                );
                $patient->update(['ward_id' => $nurse->ward_id, 'status' => 'active']);
            }

            $message = $decision === 'accept'
                ? 'Ward transfer accepted by '.$nurse->full_name.'.'
                : 'Ward transfer declined by '.$nurse->full_name.'.';
            NursingQueryResponse::create([
                'nursing_query_id' => $lockedQuery->id,
                'responder_type' => 'nurse',
                'responder_id' => $nurse->id,
                'message' => $message,
            ]);
            $lockedQuery->update(['status' => $decision === 'accept' ? 'accepted' : 'rejected']);
        });

        $sourceNurse = $query->sender_type === 'nurse' ? Nurses::find($query->sender_id) : null;
        $sourceNurse?->notify(new NursingWardTransferNotification(
            $decision === 'accept' ? 'Ward transfer accepted' : 'Ward transfer declined',
            $nurse->full_name.' '.($decision === 'accept' ? 'accepted' : 'declined').' the transfer request for '.$details['patient_name'].'.',
            (int) $details['patient_id']
        ));

        return back()->with('success', $decision === 'accept'
            ? 'Transfer accepted. The patient is now assigned to your ward.'
            : 'Transfer declined. The patient remains in the original ward.');
    }

    public function storePatientReport(Request $request, Patient $patient): RedirectResponse
    {
        $nurse = Auth::guard('nurse')->user();
        $patient = $this->activeWardPatient($patient, $nurse);
        $validated = $request->validate([
            'report_type' => ['required', Rule::in(['daily_shift', 'incident', 'administrative', 'referral'])],
            'summary' => ['required', 'string', 'max:20000'],
        ]);

        NursingReport::create([
            ...$validated,
            'hospital_id' => $nurse->hospital_id,
            'ward_id' => $nurse->ward_id,
            'nurse_id' => $nurse->id,
            'patient_id' => $patient->id,
            'report_date' => now()->toDateString(),
            'status' => 'draft',
        ]);

        return back()->with('success', 'Patient report saved to the ward. It will not be sent until you choose a recipient.');
    }

    public function updatePatientReport(Request $request, NursingReport $report): RedirectResponse
    {
        $nurse = Auth::guard('nurse')->user();
        abort_unless(
            (int) $report->hospital_id === (int) $nurse->hospital_id
                && (int) $report->ward_id === (int) $nurse->ward_id
                && $report->status === 'draft'
                && $report->patient_id
                && Patient::where('hospital_id', $nurse->hospital_id)
                    ->where('ward_id', $nurse->ward_id)
                    ->whereNull('discharged_at')
                    ->whereKey($report->patient_id)
                    ->exists(),
            403
        );
        $validated = $request->validate([
            'report_type' => ['required', Rule::in(['daily_shift', 'incident', 'administrative', 'referral'])],
            'summary' => ['required', 'string', 'max:20000'],
        ]);

        $report->update($validated + ['nurse_id' => $nurse->id]);

        return back()->with('success', 'Ward patient report updated.');
    }

    public function sendPatientReport(Request $request, NursingReport $report): RedirectResponse
    {
        $nurse = Auth::guard('nurse')->user();
        abort_unless(
            (int) $report->hospital_id === (int) $nurse->hospital_id
                && (int) $report->ward_id === (int) $nurse->ward_id
                && $report->status === 'draft'
                && $report->patient_id
                && Patient::where('hospital_id', $nurse->hospital_id)
                    ->where('ward_id', $nurse->ward_id)
                    ->whereNull('discharged_at')
                    ->whereKey($report->patient_id)
                    ->exists(),
            403
        );

        $unitHead = $nurse->ward?->unitHead;
        abort_unless($unitHead, 422, 'Your ward must have an assigned unit head before submitting a report.');
        $hnsRecipients = User::where('hospital_id', $nurse->hospital_id)
            ->get()
            ->filter(fn (User $user) => NursingHierarchy::isHns($user));
        abort_unless($hnsRecipients->isNotEmpty(), 422, 'No HNS Office account is configured for your hospital.');

        $report->update([
            'recipient_type' => 'user',
            'recipient_id' => $unitHead->id,
            'recipient_role' => 'hns_and_unit_head',
            'target_admin_role' => null,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        $report->load('nurse');

        $recipients = $hnsRecipients->push($unitHead)->unique('id');
        $recipients->each(fn ($recipient) => $recipient->notify(new NursingReportSubmitted($report)));

        return back()->with('success', 'Patient report sent to both the HNS Office and your unit head.');
    }

    private function activeWardPatient(Patient $patient, object $nurse): Patient
    {
        return Patient::where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->whereNull('discharged_at')
            ->findOrFail($patient->id);
    }

    private function hasPendingWardTransfer(Patient $patient, object $nurse): bool
    {
        return NursingQuery::where('hospital_id', $nurse->hospital_id)
            ->where('status', 'pending')
            ->where('message', 'like', '%"workflow":"ward_patient_transfer","patient_id":'.$patient->id.'%')
            ->exists();
    }

    public function editPatient(int $patientId): View
    {
        $nurse = Auth::guard('nurse')->user();
        $patient = Patient::where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->whereNull('discharged_at')
            ->findOrFail($patientId);

        return view('parklane.nurses.edit_patient', compact('patient'));
    }

    public function updatePatient(Request $request, int $patientId): RedirectResponse
    {
        $nurse = Auth::guard('nurse')->user();
        $patient = Patient::where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->whereNull('discharged_at')
            ->findOrFail($patientId);

        $maritalStatus = $request->input('marital_status');
        if (is_string($maritalStatus)) {
            $request->merge([
                'marital_status' => match (strtolower(trim($maritalStatus))) {
                    'married' => 'married',
                    'single', 'unmarried' => 'unmarried',
                    default => $maritalStatus,
                },
            ]);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'dob' => ['nullable', 'date', 'before_or_equal:today'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('patients', 'email')->ignore($patient->id)],
            'mobile_number' => ['nullable', 'string', 'max:15'],
            'marital_status' => ['nullable', Rule::in(['married', 'unmarried'])],
            'occupation' => ['nullable', 'string', 'max:255'],
            'current_condition' => ['nullable', 'string', 'max:255'],
            'current_medications' => ['nullable', 'string', 'max:1000'],
            'allergies' => ['nullable', 'string', 'max:1000'],
            'urgency_status' => ['nullable', Rule::in(['routine', 'urgent', 'emergency'])],
            'blood_group' => ['nullable', 'string', 'max:20'],
            'blood_pressure' => ['nullable', 'string', 'max:50'],
            'sugar_level' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
        ]);

        $patient->update($validated);

        return redirect()
            ->route('nurse.show_patient', $patient)
            ->with('success', 'Patient details updated successfully.');
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
