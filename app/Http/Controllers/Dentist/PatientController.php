<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use App\Models\DentistDepartment;
use App\Models\DentistPatientTransfer;
use App\Models\DentistPatientUnitInvite;
use App\Models\DentistUnit;
use App\Models\Dentists;
use App\Models\HandoverNotes;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\Ward;
use App\Services\DentistPatientActivityNotifier;
use App\Notifications\DentistPatientActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{


        public function addPatients()
        {
            $dentist = Auth::guard('dentist')->user();
            $wards = Ward::where('hospital_id', $dentist->hospital_id)->orderBy('name')->get();
            $DentistUnits = DentistUnit::where('hospital_id', $dentist->hospital_id)->orderBy('name')->get();

            return view('parklane.dentist.add_patient', compact('dentist', 'wards', 'DentistUnits'));
        }

        public function storeDentistAddPatients(Request $request, DentistPatientActivityNotifier $activityNotifier)
        {
            $dentist = Auth::guard('dentist')->user();
            $validated = $request->validate([
                'full_name' => ['nullable', 'string', 'max:255'],
                'dob' => ['nullable', 'date', 'before_or_equal:today'],
                'gender' => ['nullable', Rule::in(['male', 'female'])],
                'email' => ['nullable', 'email', 'max:255', 'unique:patients,email'],
                'mobile_number' => ['nullable', 'string', 'max:15'],
                'marital_status' => ['nullable', Rule::in(['Married', 'Single'])],
                'current_condition' => ['nullable', 'string', 'max:255'],
                'ward_id' => [
                    'nullable',
                    Rule::exists('wards', 'id')->where('hospital_id', $dentist->hospital_id),
                ],
                'dentist_unit_id' => [
                    'nullable',
                    Rule::exists('dentist_units', 'id')->where('hospital_id', $dentist->hospital_id),
                ],
                'current_medications' => ['nullable', 'string', 'max:1000'],
                'allergies' => ['nullable', 'string', 'max:1000'],
                'urgency_status' => ['nullable', Rule::in(['routine', 'urgent', 'emergency'])],
                'address' => ['nullable', 'string', 'max:255'],
            ]);

            if ($dentist->dentist_unit_id) {
                abort_unless(
                    empty($validated['dentist_unit_id']) ||
                        (int) $validated['dentist_unit_id'] === (int) $dentist->dentist_unit_id,
                    403
                );
            }

            do {
                $patientNumber = (string) random_int(100000, 999999);
            } while (Patient::where('patient_number', $patientNumber)->exists());

            $patient = Patient::create([
                ...$validated,
                'patient_number' => $patientNumber,
                'pt_number' => Patient::generatePtNumber(),
                'hospital_id' => $dentist->hospital_id,
                'dentist_assign' => $dentist->id,
                'dentist_unit_id' => $dentist->dentist_unit_id ?? ($validated['dentist_unit_id'] ?? null),
                'dentist_department_id' => $dentist->dentist_department_id,
                'ward_id' => $validated['ward_id'] ?? null,
                'marital_status' => match (strtolower((string) ($validated['marital_status'] ?? ''))) {
                    'married' => 'married',
                    'single' => 'unmarried',
                    default => null,
                },
                'urgency_status' => $validated['urgency_status'] ?? 'routine',
            ]);

            $activityNotifier->notify(
                $patient,
                $dentist,
                'New patient added',
                $dentist->full_name . ' added a new patient to your dental unit.',
                'patient_added'
            );

            return redirect()->route('dentist.show_patient', $patient)
                ->with('success', 'Patient added successfully.');
        }

        public function myPatients()
        {
            $dentist = Auth::guard('dentist')->user();

            $patientQuery = $this->patientsForDentist($dentist)->with('ward');
            if ($search = trim((string) request('search'))) {
                $patientQuery->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', '%' . $search . '%')
                        ->orWhere('patient_number', 'like', '%' . $search . '%')
                        ->orWhere('pt_number', 'like', '%' . $search . '%');
                });
            }

            $patients = $patientQuery->latest('id')->paginate(12)->withQueryString();
            foreach ($patients as $patient) {
                $patient->setAttribute('dentist_can_manage', $this->dentistCanManagePatient($dentist, $patient));
                $patient->setAttribute('dentist_invite_pending',
                    $this->invitesForDentist($dentist, $patient)->where('status', 'pending')->exists());
                $patient->setAttribute('dentist_transfer_pending',
                    $this->transfersForDentist($dentist, $patient)->where('status', 'pending')->exists());
                $patient->setAttribute('dentist_can_invite',
                    (int) $patient->dentist_assign === (int) $dentist->id
                    || ($dentist->dentist_unit_id
                        && (int) $patient->dentist_unit_id === (int) $dentist->dentist_unit_id
                        && (! $patient->dentist_department_id
                            || (int) $patient->dentist_department_id === (int) $dentist->dentist_department_id))
                    || ($dentist->dentist_department_id
                        && (int) $patient->dentist_department_id === (int) $dentist->dentist_department_id
                        && ! $patient->dentist_unit_id
                        && ! $dentist->dentist_unit_id));
            }
            $patientCount = $this->patientsForDentist($dentist)->count();
            $urgentCount = $this->patientsForDentist($dentist)
                ->whereIn('urgency_status', ['urgent', 'emergency'])
                ->count();

            $departments = DentistDepartment::whereIn('id', Dentists::where('hospital_id', $dentist->hospital_id)
                ->whereNotNull('dentist_department_id')->select('dentist_department_id'))
                ->orderBy('name')->get();
            $doctorDepartments = app(\App\Services\CrossRolePatientWorkflow::class)
                ->departments((int) $dentist->hospital_id, 'doctor');

            return view('parklane.dentist.patient_assign', compact('patients', 'patientCount', 'urgentCount', 'departments', 'dentist', 'doctorDepartments'));
    }

    public function store($patientId)
    {
        $patient = $this->patientsForDentist(Auth::guard('dentist')->user())->findOrFail($patientId);
        $this->authorizeCareManagement($patient);

        return view('parklane.dentist.add_medication', compact('patient'));
    }


    

    public function storeMedication(Request $request, DentistPatientActivityNotifier $activityNotifier)
    {
        $dentist = Auth::guard('dentist')->user();
        $validated = $request->validate([
            'patient_id' => ['required', 'integer'],
            'medication'   => 'required|string|max:255',
            'dosage'       => 'required|string|max:255',
            'frequency'       => 'required|string|max:255',
            'start_date'       => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'instructions' => 'nullable|string',
        ]);

        $patient = $this->patientsForDentist($dentist)->findOrFail($validated['patient_id']);
        $this->authorizeCareManagement($patient);

        $medication = Medication::create([
            'patient_id'   => $patient->id,
            'medication'   => $validated['medication'],
            'dosage'       => $validated['dosage'],
            'frequency'       => $validated['frequency'],
            'start_date'       => $validated['start_date'],
            'end_date'       => $validated['end_date'],

            'instructions' => $validated['instructions'] ?? null,
            'dentist_id'    => $dentist->id,
            'hospital_id'  => $dentist->hospital_id,
        ]);

        $activityNotifier->notify(
            $patient,
            $dentist,
            'Medication plan added',
            $dentist->full_name . ' added a medication plan for this patient.',
            'medication_added'
        );

        return redirect()->route('dentist.show_patient', $patient)
            ->with('success', 'Medication plan added successfully.');
    }



     // Show edit form
     public function editMedication($id)
     {
         $medication = Medication::where('hospital_id', Auth::guard('dentist')->user()->hospital_id)->findOrFail($id);
         $this->authorizeCareManagement($this->patientsForDentist(Auth::guard('dentist')->user())->findOrFail($medication->patient_id));
         return view('parklane.dentist.update_medication', compact('medication'));
     }
 
     // Update the medication
     public function updateMedication(Request $request, $id, DentistPatientActivityNotifier $activityNotifier)
     {
         $validated = $request->validate([
             'medication'   => 'required|string|max:255',
             'dosage'       => 'required|string|max:255',
             'frequency'       => 'required',
             'start_date'       => 'required',
             'end_date'       => 'required',
             'instructions' => 'nullable|string',
         ]);
 
         $medication = Medication::where('hospital_id', auth()->guard('dentist')->user()->hospital_id)->findOrFail($id);
         $patient = $this->patientsForDentist(auth()->guard('dentist')->user())->findOrFail($medication->patient_id);
         $this->authorizeCareManagement($patient);
 
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

         $activityNotifier->notify(
             $patient,
             Auth::guard('dentist')->user(),
             'Medication plan updated',
             Auth::guard('dentist')->user()->full_name . ' updated a medication plan for this patient.',
             'medication_updated'
         );
 
         return redirect()->back()->with('success', 'Medication updated successfully!');
     }
 
     public function edit($id)
     {
         $dentist = Auth::guard('dentist')->user();
         $patient = $this->patientsForDentist($dentist)->with('ward')->findOrFail($id);
         $this->authorizeCareManagement($patient);
         $wards = Ward::where('hospital_id', $dentist->hospital_id)->orderBy('name')->get();

         return view('parklane.dentist.update_patient', compact('patient', 'wards'));
     }
 
     public function patientUpdate(Request $request, $id, DentistPatientActivityNotifier $activityNotifier)
     {
         $dentist = Auth::guard('dentist')->user();
         $patient = $this->patientsForDentist($dentist)->findOrFail($id);
         $this->authorizeCareManagement($patient);
         $validated = $request->validate([
             'full_name' => ['required', 'string', 'max:255'],
             'dob' => ['required', 'date', 'before_or_equal:today'],
             'gender' => ['required', Rule::in(['male', 'female'])],
             'email' => ['required', 'email', 'max:255', Rule::unique('patients', 'email')->ignore($patient->id)],
             'mobile_number' => ['nullable', 'string', 'max:15'],
             'marital_status' => ['nullable', Rule::in(['Married', 'Single'])],
             'current_condition' => ['nullable', 'string', 'max:255'],
             'ward_id' => ['nullable', Rule::exists('wards', 'id')->where('hospital_id', $dentist->hospital_id)],
             'current_medications' => ['nullable', 'string', 'max:1000'],
             'allergies' => ['nullable', 'string', 'max:1000'],
             'urgency_status' => ['nullable', Rule::in(['routine', 'urgent', 'emergency'])],
             'occupation' => ['nullable', 'string', 'max:255'],
             'blood_group' => ['nullable', 'string', 'max:5'],
             'blood_pressure' => ['nullable', 'string', 'max:255'],
             'sugar_level' => ['nullable', 'string', 'max:255'],
             'address' => ['nullable', 'string', 'max:255'],
             'city' => ['nullable', 'string', 'max:255'],
             'state' => ['nullable', 'string', 'max:255'],
         ]);

         $validated['marital_status'] = match (strtolower((string) ($validated['marital_status'] ?? ''))) {
             'married' => 'married',
             'single' => 'unmarried',
             default => null,
         };

         $patient->update($validated);

         $activityNotifier->notify(
             $patient,
             $dentist,
             'Patient record updated',
             $dentist->full_name . ' updated this patient record.',
             'patient_updated'
         );

         return redirect()->route('dentist.edit_patients', $patient->id)
             ->with('success', 'Patient details updated successfully.');
     }
 
     public function show($patient_id)
     {
         // $patient = Patient::findOrFail($id);
         $dentist = Auth::guard('dentist')->user();
     
         $patient = $this->patientsForDentist($dentist)
             ->with(['ward', 'doctorById'])
             ->findOrFail($patient_id);
         $canManage = $this->dentistCanManagePatient($dentist, $patient);
         $receivedCareInvites = $this->invitesForDentist($dentist, $patient)->where('status', 'pending')->get();
         $receivedCareTransfers = $this->transfersForDentist($dentist, $patient)->where('status', 'pending')->get();
         $crossWorkflow = app(\App\Services\CrossRolePatientWorkflow::class);
         $doctorDepartments = $crossWorkflow->departments((int) $dentist->hospital_id, 'doctor');
         $incomingCrossRoleRequests = $crossWorkflow->incomingRequests($patient)->where('status', 'pending')->get();
         $dentistDepartments = DentistDepartment::whereIn('id', Dentists::where('hospital_id', $dentist->hospital_id)
             ->whereNotNull('dentist_department_id')->select('dentist_department_id'))
             ->orderBy('name')->get();

         $medications = Medication::with('dentist')
             ->where('patient_id', $patient->id)
             ->where('hospital_id', $dentist->hospital_id)
             ->latest()
             ->get();
         $handoverNotes = HandoverNotes::with(['dentist.DentistDepartment', 'doctor'])
             ->where('patient_id', $patient->id)
             ->where('hospital_id', $dentist->hospital_id)
             ->latest()
             ->get();
 
         return view('parklane.dentist.show_patient', compact(
             'patient',
             'medications',
             'handoverNotes',
             'canManage',
             'dentistDepartments',
             'doctorDepartments',
             'receivedCareInvites',
             'receivedCareTransfers',
             'incomingCrossRoleRequests'
         ));
     }

    private function patientsForDentist($dentist)
    {
        $actor = $dentist;
        $inviteIds = DentistPatientUnitInvite::where('hospital_id', $actor->hospital_id)
            ->where('dentist_department_id', $actor->dentist_department_id)
            ->where(function ($query) use ($actor) {
                $query->whereNull('dentist_unit_id');
                if ($actor->dentist_unit_id) {
                    $query->orWhere('dentist_unit_id', $actor->dentist_unit_id);
                }
            })
            ->pluck('patient_id');
        $transferIds = $this->transfersForDentist($actor)->where('status', 'pending')->pluck('patient_id');
        $crossRequestIds = app(\App\Services\CrossRolePatientWorkflow::class)
            ->incomingPatientIds('dentist', $actor);

        return Patient::where('hospital_id', $actor->hospital_id)
            ->where(function ($query) use ($actor, $inviteIds, $transferIds, $crossRequestIds) {
                $query->where('dentist_assign', $actor->id);
                if ($actor->dentist_unit_id) {
                    $query->orWhere('dentist_unit_id', $actor->dentist_unit_id);
                }
                if ($actor->dentist_department_id) {
                    $query->orWhere(function ($assigned) use ($actor) {
                        $assigned->where('dentist_department_id', $actor->dentist_department_id)
                            ->whereNull('dentist_unit_id');
                    });
                }
                $query->orWhereIn('id', $inviteIds)->orWhereIn('id', $transferIds)->orWhereIn('id', $crossRequestIds);
            });
    }

    private function dentistCanManagePatient(Dentists $dentist, Patient $patient): bool
    {
        if ((int) $dentist->hospital_id !== (int) $patient->hospital_id) {
            return false;
        }

        $isAssigned = (int) $patient->dentist_assign === (int) $dentist->id
            || ($dentist->dentist_unit_id
                && (int) $patient->dentist_unit_id === (int) $dentist->dentist_unit_id
                && (! $patient->dentist_department_id
                    || (int) $patient->dentist_department_id === (int) $dentist->dentist_department_id))
            || ($dentist->dentist_department_id
                && (int) $patient->dentist_department_id === (int) $dentist->dentist_department_id
                && ! $patient->dentist_unit_id);
        if ($isAssigned) {
            return true;
        }

        return $this->invitesForDentist($dentist, $patient)->where('status', 'accepted')->exists()
            || app(\App\Services\CrossRolePatientWorkflow::class)
                ->incomingRequests($patient, 'invite')->where('status', 'accepted')->exists();
    }

    private function invitesForDentist(Dentists $dentist, ?Patient $patient = null)
    {
        return DentistPatientUnitInvite::where('hospital_id', $dentist->hospital_id)
            ->where('dentist_department_id', $dentist->dentist_department_id)
            ->where(function ($query) use ($dentist) {
                $query->whereNull('dentist_unit_id');
                if ($dentist->dentist_unit_id) {
                    $query->orWhere('dentist_unit_id', $dentist->dentist_unit_id);
                }
            })
            ->when($patient, fn ($query) => $query->where('patient_id', $patient->id));
    }

    private function transfersForDentist(Dentists $dentist, ?Patient $patient = null)
    {
        return DentistPatientTransfer::where('hospital_id', $dentist->hospital_id)
            ->where('to_department_id', $dentist->dentist_department_id)
            ->when($dentist->dentist_unit_id,
                fn ($query) => $query->where(function ($unit) use ($dentist) {
                    $unit->whereNull('to_unit_id')->orWhere('to_unit_id', $dentist->dentist_unit_id);
                }),
                fn ($query) => $query->whereNull('to_unit_id'))
            ->when($patient, fn ($query) => $query->where('patient_id', $patient->id));
    }

    private function authorizeDentistManage(Dentists $dentist, Patient $patient): void
    {
        abort_unless($this->dentistCanManagePatient($dentist, $patient), 403,
            'Your dental unit can only manage this patient after accepting the invitation.');
    }

    private function dentistUnitMembers(int $hospitalId, int $departmentId, ?int $unitId = null)
    {
        return Dentists::where('hospital_id', $hospitalId)
            ->where('dentist_department_id', $departmentId)
            ->when($unitId, fn ($query) => $query->where('dentist_unit_id', $unitId))
            ->get();
    }

    private function notifyDentistMembers(iterable $members, Patient $patient, string $title, string $message, string $activity): void
    {
        $notification = new DentistPatientActivityNotification($patient, $title, $message, $activity);
        foreach ($members as $member) {
            $member->notify($notification);
        }
    }

    public function getUnitsForDepartment($departmentId)
    {
        $dentist = Auth::guard('dentist')->user();
        $units = DentistUnit::where('hospital_id', $dentist->hospital_id)
            ->whereIn('id', Dentists::where('hospital_id', $dentist->hospital_id)
                ->where('dentist_department_id', $departmentId)
                ->whereNotNull('dentist_unit_id')->select('dentist_unit_id'))
            ->orderBy('name')->get(['id', 'name']);

        return response()->json($units);
    }

    public function inviteUnit(Request $request)
    {
        $dentist = Auth::guard('dentist')->user();
        $validated = $request->validate([
            'patient_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
            'dentist_unit_id' => ['nullable', 'integer'],
            'instructions' => ['required', 'string', 'max:2000'],
        ]);
        $patient = Patient::where('hospital_id', $dentist->hospital_id)->findOrFail($validated['patient_id']);
        $this->authorizeDentistManage($dentist, $patient);
        $isPrimaryCareTeam = (int) $patient->dentist_assign === (int) $dentist->id
            || ($dentist->dentist_unit_id
                && (int) $patient->dentist_unit_id === (int) $dentist->dentist_unit_id
                && (! $patient->dentist_department_id
                    || (int) $patient->dentist_department_id === (int) $dentist->dentist_department_id))
            || ($dentist->dentist_department_id
                && (int) $patient->dentist_department_id === (int) $dentist->dentist_department_id
                && ! $patient->dentist_unit_id
                && ! $dentist->dentist_unit_id);
        abort_unless($isPrimaryCareTeam, 403);
        abort_unless((int) $validated['department_id'] !== (int) $dentist->dentist_department_id
            || ($validated['dentist_unit_id'] && (int) $validated['dentist_unit_id'] !== (int) $dentist->dentist_unit_id), 422,
            'Choose another dental department or unit.');

        $unitId = $validated['dentist_unit_id'] ? (int) $validated['dentist_unit_id'] : null;
        if ($unitId) {
            abort_unless(Dentists::where('hospital_id', $dentist->hospital_id)
                ->where('dentist_department_id', $validated['department_id'])
                ->where('dentist_unit_id', $unitId)->exists(), 422, 'The selected unit is not in that dental department.');
        } else {
            abort_unless(Dentists::where('hospital_id', $dentist->hospital_id)
                ->where('dentist_department_id', $validated['department_id'])->exists(), 422, 'The selected department is not available at this hospital.');
        }

        $invite = DentistPatientUnitInvite::where('patient_id', $patient->id)
            ->where('dentist_department_id', $validated['department_id'])
            ->where('dentist_unit_id', $unitId)
            ->whereIn('status', ['pending', 'accepted'])->first();
        abort_if($invite, 409, 'This dental department or unit has already been invited.');

        DentistPatientUnitInvite::create([
            'patient_id' => $patient->id,
            'hospital_id' => $dentist->hospital_id,
            'dentist_department_id' => $validated['department_id'],
            'dentist_unit_id' => $unitId,
            'dentist_id' => $dentist->id,
            'instructions' => $validated['instructions'],
            'status' => 'pending',
        ]);

        $this->notifyDentistMembers(
            $this->dentistUnitMembers($dentist->hospital_id, (int) $validated['department_id'], $unitId),
            $patient,
            'New patient unit invite',
            $dentist->full_name.' invited your dental '.($unitId ? 'unit' : 'department').' to manage '.$patient->full_name.'. Please accept or reject.',
            'dentist_unit_invite'
        );

        return back()->with('success', 'Dental department or unit invited. It must accept before managing this patient.');
    }

    public function respondInvite($inviteId, string $decision)
    {
        abort_unless(in_array($decision, ['accept', 'reject'], true), 404);
        $dentist = Auth::guard('dentist')->user();
        $invite = DentistPatientUnitInvite::where('hospital_id', $dentist->hospital_id)->findOrFail($inviteId);
        abort_unless((int) $invite->dentist_department_id === (int) $dentist->dentist_department_id
            && ($invite->dentist_unit_id === null
                || (int) $invite->dentist_unit_id === (int) $dentist->dentist_unit_id), 403);
        abort_unless($invite->status === 'pending', 409, 'This invite has already been responded to.');

        $invite->update([
            'status' => $decision === 'accept' ? 'accepted' : 'rejected',
            'responded_by_dentist_id' => $dentist->id,
            'responded_at' => now(),
        ]);
        $sender = Dentists::where('hospital_id', $dentist->hospital_id)->findOrFail($invite->dentist_id);
        $this->notifyDentistMembers(
            [$sender],
            $invite->patient,
            'Patient unit invite '.($decision === 'accept' ? 'accepted' : 'rejected'),
            $dentist->full_name.' '.$decision.'ed the invitation for '.$invite->patient->full_name.'.',
            'dentist_unit_invite_'.$decision
        );

        return back()->with('success', $decision === 'accept' ? 'Invite accepted. Your dental team can now manage this patient.' : 'Invite rejected.');
    }

    public function discharge(Request $request, DentistPatientActivityNotifier $activityNotifier)
    {
        $dentist = Auth::guard('dentist')->user();
        $validated = $request->validate([
            'patient_id' => ['required', 'integer'],
            'discharge_type' => ['required', 'in:home,transfer'],
            'department_id' => ['nullable', 'required_if:discharge_type,transfer', 'integer'],
            'dentist_unit_id' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $patient = Patient::where('hospital_id', $dentist->hospital_id)->findOrFail($validated['patient_id']);
        $this->authorizeDentistManage($dentist, $patient);

        if ($validated['discharge_type'] === 'home') {
            $pendingTransfers = DentistPatientTransfer::where('patient_id', $patient->id)
                ->where('status', 'pending')->get();
            DB::transaction(function () use ($patient, $validated) {
                $lockedPatient = Patient::where('hospital_id', $patient->hospital_id)
                    ->whereKey($patient->id)->lockForUpdate()->firstOrFail();
                DentistPatientTransfer::where('patient_id', $lockedPatient->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'cancelled', 'responded_at' => now()]);
                $lockedPatient->update([
                    'status' => 'discharged',
                    'discharge_notes' => $validated['notes'] ?? null,
                    'discharged_at' => now(),
                ]);
            });
            $activityNotifier->notify(
                $patient,
                $dentist,
                'Patient discharged home',
                $dentist->full_name . ' discharged this patient home.',
                'patient_discharged'
            );
            foreach ($pendingTransfers as $pendingTransfer) {
                $this->notifyDentistMembers(
                    $this->dentistUnitMembers(
                        $dentist->hospital_id,
                        (int) $pendingTransfer->to_department_id,
                        $pendingTransfer->to_unit_id
                    ),
                    $patient,
                    'Dental patient transfer cancelled',
                    $dentist->full_name.' discharged '.$patient->full_name.' home; the transfer request is cancelled.',
                    'dentist_transfer_cancelled'
                );
            }

            return back()->with('success', 'Patient discharged home successfully.');
        }

        $departmentId = (int) $validated['department_id'];
        $unitId = isset($validated['dentist_unit_id']) && $validated['dentist_unit_id'] !== ''
            ? (int) $validated['dentist_unit_id']
            : null;
        if ($unitId) {
           abort_unless(Dentists::where('hospital_id', $dentist->hospital_id)
               ->where('dentist_department_id', $departmentId)
               ->where('dentist_unit_id', $unitId)->exists(), 422, 'The selected unit is not in that dental department.');
        }
        $members = $this->dentistUnitMembers($dentist->hospital_id, $departmentId, $unitId);
        abort_if($members->isEmpty(), 422, 'Choose a dental department or unit at this hospital.');
        abort_if((int) $patient->dentist_department_id === $departmentId
            && (($patient->dentist_unit_id === null && $unitId === null)
                || ($patient->dentist_unit_id !== null && $unitId !== null
                    && (int) $patient->dentist_unit_id === $unitId)), 422, 'Choose a different dental team.');
        DB::transaction(function () use ($patient, $dentist, $departmentId, $unitId, $validated) {
            $lockedPatient = Patient::where('hospital_id', $dentist->hospital_id)
                ->whereKey($patient->id)->lockForUpdate()->firstOrFail();
            $this->authorizeDentistManage($dentist, $lockedPatient);
            abort_if(DentistPatientTransfer::where('patient_id', $lockedPatient->id)
                ->where('status', 'pending')->exists(), 409,
                'This patient already has a transfer awaiting acceptance.');

            DentistPatientTransfer::create([
                'patient_id' => $lockedPatient->id,
                'hospital_id' => $dentist->hospital_id,
                'from_department_id' => $lockedPatient->dentist_department_id,
                'from_unit_id' => $lockedPatient->dentist_unit_id,
                'to_department_id' => $departmentId,
                'to_unit_id' => $unitId,
                'requested_by_dentist_id' => $dentist->id,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        $this->notifyDentistMembers($members, $patient, 'Dental patient transfer pending',
            $dentist->full_name.' requested transfer of '.$patient->full_name.'. Please accept or reject.',
            'dentist_transfer_pending');

        return back()->with('success', 'Transfer request sent. The receiving dental team must accept it before ownership changes.');
    }

    public function respondTransfer($transferId, string $decision)
    {
        abort_unless(in_array($decision, ['accept', 'reject'], true), 404);
        $dentist = Auth::guard('dentist')->user();
        $transfer = DentistPatientTransfer::where('hospital_id', $dentist->hospital_id)->findOrFail($transferId);
        abort_unless((int) $transfer->to_department_id === (int) $dentist->dentist_department_id
            && ($transfer->to_unit_id === null
                || (int) $transfer->to_unit_id === (int) $dentist->dentist_unit_id), 403);
        abort_unless($transfer->status === 'pending', 409, 'This transfer has already been responded to.');

        DB::transaction(function () use ($transfer, $decision, $dentist) {
            $lockedTransfer = DentistPatientTransfer::whereKey($transfer->id)->lockForUpdate()->firstOrFail();
            abort_unless($lockedTransfer->status === 'pending', 409, 'This transfer has already been responded to.');
            if ($decision === 'accept') {
                Patient::where('hospital_id', $dentist->hospital_id)->whereKey($lockedTransfer->patient_id)
                    ->lockForUpdate()->firstOrFail()->update([
                        'dentist_department_id' => $lockedTransfer->to_department_id,
                        'dentist_unit_id' => $lockedTransfer->to_unit_id,
                        'dentist_assign' => null,
                        'status' => 'active',
                        'discharged_at' => null,
                        'discharge_notes' => null,
                    ]);
            }
            $lockedTransfer->update([
                'status' => $decision === 'accept' ? 'accepted' : 'rejected',
                'responded_by_dentist_id' => $dentist->id,
                'responded_at' => now(),
            ]);
        });

        $sourceTeam = $transfer->from_department_id
            ? $this->dentistUnitMembers($dentist->hospital_id, (int) $transfer->from_department_id, $transfer->from_unit_id)
            : collect([Dentists::where('hospital_id', $dentist->hospital_id)->findOrFail($transfer->requested_by_dentist_id)]);
        $this->notifyDentistMembers($sourceTeam, $transfer->patient, 'Dental patient transfer '.($decision === 'accept' ? 'accepted' : 'rejected'),
            $dentist->full_name.' '.$decision.'ed the transfer for '.$transfer->patient->full_name.'.',
            'dentist_transfer_'.$decision);

        return back()->with('success', $decision === 'accept'
            ? 'Transfer accepted. Patient now belongs to your dental team.'
            : 'Transfer rejected. Current assignment remains unchanged.');
    }

    private function authorizeCareManagement(Patient $patient): void
    {
        abort_unless($this->dentistCanManagePatient(Auth::guard('dentist')->user(), $patient), 403,
            'Accept the patient invitation before making changes.');
    }
 
     public function destroy($id, DentistPatientActivityNotifier $activityNotifier)
     {
         $dentist = Auth::guard('dentist')->user();
         $medication = Medication::where('hospital_id', $dentist->hospital_id)->findOrFail($id);
         $patientId = $medication->patient_id; 
         $patient = $this->patientsForDentist($dentist)->findOrFail($patientId);
         $this->authorizeCareManagement($patient);
         $medication->delete();

         $activityNotifier->notify(
             $patient,
             $dentist,
             'Medication plan removed',
             $dentist->full_name . ' removed a medication plan for this patient.',
             'medication_removed'
         );
     
         return redirect()->route('dentist.show_patient', $patientId)
                          ->with('success', 'Medication deleted successfully!');
     }
 
     public function showHandover($patient_id)
     {
         $dentist = Auth::guard('dentist')->user();

         $patient = $this->patientsForDentist($dentist)->findOrFail($patient_id);
         $this->authorizeCareManagement($patient);

         return view('parklane.dentist.handovernote', compact('patient'));
     }
 
     public function storeHandover(Request $request, DentistPatientActivityNotifier $activityNotifier)
     {
         $dentist = Auth::guard('dentist')->user();
         $validated = $request->validate([
             'patient_id' => 'required|integer',
             'summary' => 'required|string',
             'alerts' => 'required|string',
             'pending_tasks' => 'nullable|string',
         ]);

         $patient = $this->patientsForDentist($dentist)->findOrFail($validated['patient_id']);
         $this->authorizeCareManagement($patient);
         $validated['patient_id'] = $patient->id;
         $validated['dentist_id'] = $dentist->id;
         $validated['hospital_id'] = $dentist->hospital_id;
         $validated['pending_tasks'] = $validated['pending_tasks'] ?? '';

         HandoverNotes::create($validated);

         $activityNotifier->notify(
             $patient,
             $dentist,
             'New handover note',
             $dentist->full_name . ' submitted a handover note for this patient.',
             'handover_added'
         );

         return redirect()->route('dentist.show_patient', $patient)
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
         $this->authorizeCareManagement($this->patientsForDentist(Auth::guard('dentist')->user())->findOrFail($handoverNote->patient_id));
         
         return view('parklane.dentist.update_handoverNote', compact('handoverNote'));
     }
     public function updateHandoverNote(Request $request, $id, DentistPatientActivityNotifier $activityNotifier)
     {
         $validated = $request->validate([
             'summary' => 'required|string',
             'alerts' => 'required|string',
             'pending_tasks' => 'required|string',
         ]);
 
         $handoverNote = HandoverNotes::findOrFail($id);
         $dentist = Auth::guard('dentist')->user();
         $patient = $this->patientsForDentist($dentist)->findOrFail($handoverNote->patient_id);
         $this->authorizeCareManagement($patient);
         $handoverNote->update($validated);
         $activityNotifier->notify(
             $patient,
             $dentist,
             'Handover note updated',
             $dentist->full_name . ' updated a handover note for this patient.',
             'handover_updated'
         );
 
         return redirect()->route('dentist.show_patient', $handoverNote->patient_id)
                         ->with('success', 'Handover note updated successfully.');
     }
 














}
