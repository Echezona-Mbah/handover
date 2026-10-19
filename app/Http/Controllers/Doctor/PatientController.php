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

class PatientController extends Controller
{
    /**
     * Maximum number of units/sub-units that can be invited to
     * collaborate on a single patient.
     */
    const MAX_INVITES_PER_PATIENT = 10;

    public function addPatients()
    {
        $doctor = Auth::guard('doctor')->user();
        $wards = Ward::all();

        // Get only parent units (main units, where parent_id is NULL)
        $units = DoctorUnit::whereNull('parent_id')->with('subUnits')->get();

        return view('parklane.doctor.add_patient', compact('doctor', 'wards', 'units'));
    }

    public static function generatePtNumber(): string
    {
        do {
            $pt = 'PT' . now()->format('Ymd') . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('pt_number', $pt)->exists());

        return $pt;
    }

    public function storeDoctorAddPatients(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'email' => 'nullable|email|unique:patients,email',
            'mobile_number' => 'required|string|max:15',
            'marital_status' => 'nullable|string',
            'current_condition' => 'required|string',
            'ward' => 'required|exists:wards,id',
            'current_medications' => 'nullable|string',
            'allergies' => 'nullable|string',
            'urgency_status' => 'required|string',
            'address' => 'nullable|string',
        ]);

        $doctor = Auth::guard('doctor')->user();

        $patient = Patient::create([
            'pt_number' => Patient::generatePtNumber(),
            'doctor_id' => $doctor->id,
            'hospital_id' => $doctor->hospital_id,
            'full_name' => $request->full_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'marital_status' => $request->marital_status,
            'current_condition' => $request->current_condition,
            'ward_id' => $request->ward,
            'doctor_departments' => $doctor->doctor_departments,
            'doctor_unit_id' => $doctor->doctor_unit_id,
            'doctor_sub_unit_id' => $doctor->doctor_sub_unit_id ?? null,
            'current_medications' => $request->current_medications,
            'allergies' => $request->allergies,
            'urgency_status' => $request->urgency_status,
            'address' => $request->address,
        ]);

        $targetDoctors = $this->getTargetDoctorsForPatient($patient, $doctor);

        $actorCadre = $doctor->cadre ? ucwords(str_replace('_', ' ', $doctor->cadre)) : 'Doctor';
        $actorName = $doctor->full_name;
        $isEmergency = strtolower($patient->urgency_status) === 'emergency';

        foreach ($targetDoctors as $doc) {
            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'New Patient Added',
                'message' => 'A new patient has been added: ' . $patient->full_name,
                'type' => 'patient_added',
            ]);

            Mail::to($doc->email)->send(new \App\Mail\PatientAddedMail($patient, $doc));

            if ($isEmergency) {
                DoctorNotification::create([
                    'department_id' => $doc->doctor_departments,
                    'hospital_id' => $doc->hospital_id,
                    'doctor_unit_id' => $doc->doctor_unit_id,
                    'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                    'patient_id' => $patient->id,
                    'title' => 'Emergency Patient Alert',
                    'message' => $actorCadre . ' ' . $actorName . ' added an emergency patient: ' . $patient->full_name,
                    'type' => 'emergency_alert',
                ]);

                Mail::to($doc->email)->send(
                    new \App\Mail\EmergencyPatientAlertMail($patient, $doc, $doctor, 'added')
                );
            }
        }

        return redirect()->route('doctor.add_patient')->with('success', 'Patient added successfully!');
    }

    public function getUnits($department_id)
    {
        $units = DoctorUnit::where('doctor_department_id', $department_id)
            ->whereNull('parent_id') // only top-level
            ->get();

        return response()->json($units);
    }

    public function getSubUnits($unit_id)
    {
        $subUnits = DoctorSubUnit::where('doctor_unit_id', $unit_id)->get();
        return response()->json($subUnits);
    }

    /**
     * Build the full set of doctors who should be notified about a patient:
     * the home unit/sub-unit PLUS every unit/sub-unit ever invited via
     * PatientUnitInvite (regardless of accept/reject status — invited
     * doctors can still view the patient, so they still get notified).
     */
    private function getTargetDoctorsForPatient(Patient $patient, Doctor $actor)
    {
        $doctorIds = collect();

        // Home unit / sub-unit (the patient's primary assignment)
        $doctorIds = $doctorIds->merge(
            $this->getUnitDoctors($patient->doctor_unit_id, $patient->doctor_sub_unit_id, $actor->hospital_id)
                ->pluck('id')
        );

        // Every unit/sub-unit ever invited to collaborate on this patient
        $invites = PatientUnitInvite::where('patient_id', $patient->id)->get();

        foreach ($invites as $invite) {
            $doctorIds = $doctorIds->merge(
                $this->getUnitDoctors($invite->doctor_unit_id, $invite->doctor_sub_unit_id, $actor->hospital_id)
                    ->pluck('id')
            );
        }

        return Doctor::whereIn('id', $doctorIds->unique())->get();
    }

    /**
     * Fetch doctors for a single unit/sub-unit pair, scoped to hospital.
     * Sub-unit takes precedence over unit when present, matching the
     * existing convention used throughout this controller.
     */
    private function getUnitDoctors($unitId, $subUnitId, $hospitalId)
    {
        if (!empty($subUnitId)) {
            return Doctor::where('doctor_sub_unit_id', $subUnitId)
                ->where('hospital_id', $hospitalId)
                ->get();
        }

        return Doctor::where('doctor_unit_id', $unitId)
            ->where('hospital_id', $hospitalId)
            ->get();
    }

    /**
     * Can this doctor make changes (medication, handover, discharge, etc.)
     * to this patient? True if they belong to the patient's home unit/
     * sub-unit, OR if their unit/sub-unit has an ACCEPTED invite for this
     * patient. Pending or rejected invites grant view-only access.
     */
    private function doctorCanManagePatient(Doctor $doctor, Patient $patient): bool
    {
        $isHomeUnit = !empty($doctor->doctor_sub_unit_id)
            ? $doctor->doctor_sub_unit_id == $patient->doctor_sub_unit_id
            : ($doctor->doctor_unit_id == $patient->doctor_unit_id && empty($patient->doctor_sub_unit_id));

        if ($isHomeUnit) {
            return true;
        }

        $query = PatientUnitInvite::where('patient_id', $patient->id)
            ->where('status', 'accepted');

        if (!empty($doctor->doctor_sub_unit_id)) {
            $query->where('doctor_sub_unit_id', $doctor->doctor_sub_unit_id);
        } else {
            $query->where('doctor_unit_id', $doctor->doctor_unit_id)
                ->whereNull('doctor_sub_unit_id');
        }

        return $query->exists();
    }

    /**
     * Abort with 403 if the acting doctor doesn't have management rights
     * over this patient (i.e. their unit was invited but hasn't accepted,
     * or was rejected, or has no relationship to the patient at all).
     */
    private function authorizeManagePatient(Doctor $doctor, Patient $patient): void
    {
        if (!$this->doctorCanManagePatient($doctor, $patient)) {
            abort(403, 'Your unit can only view this patient until the invite is accepted.');
        }
    }

    public function myPatients()
    {
        $doctor = Auth::guard('doctor')->user();
        $unitId = $doctor->doctor_unit_id;
        $subUnitId = $doctor->doctor_sub_unit_id;

        // Assigned patients
        $assigned = Patient::where('doctor_unit_id', $unitId)
            ->where('status', 'active');

        if ($subUnitId) {
            $assigned->where('doctor_sub_unit_id', $subUnitId);
        } else {
            $assigned->whereNull('doctor_sub_unit_id');
        }

        // Invited patients
        $invited = Patient::whereIn('id', function ($q) use ($unitId, $subUnitId) {
            $q->select('patient_id')
                ->from('patient_unit_invites')
                ->where('doctor_unit_id', $unitId);

            if ($subUnitId) {
                $q->where('doctor_sub_unit_id', $subUnitId);
            } else {
                $q->whereNull('doctor_sub_unit_id');
            }
        });

        // Pending transferred patients for this unit/subunit
        $pendingTransfers = Patient::where('transfer_status', 'pending')
            ->where(function ($q) use ($unitId, $subUnitId) {
                if ($subUnitId) {
                    $q->where('pending_transfer_sub_unit_id', $subUnitId);
                } else {
                    $q->where('pending_transfer_unit_id', $unitId)
                      ->whereNull('pending_transfer_sub_unit_id');
                }
            });

        // Merge all and remove duplicates
        $patients = Patient::where(function ($query) use ($assigned, $invited, $pendingTransfers) {
                $query->whereIn('id', $assigned->select('id'))
                    ->orWhereIn('id', $invited->select('id'))
                    ->orWhereIn('id', $pendingTransfers->select('id'));
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $departments = DoctorDepartment::all();
        $doctorUnits = \App\Models\DoctorUnit::with('subUnits')->whereNull('parent_id')->get();

        return view('parklane.doctor.patient_assign', compact('patients', 'departments', 'doctorUnits', 'doctor'));
    }

    public function inviteUnit(Request $request)
    {
        $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'department_id'     => 'required',
            'doctor_unit_id'    => 'required',
            'instructions'      => 'required',
        ]);

        $actor = Auth::guard('doctor')->user();
        $patient = Patient::findOrFail($request->patient_id);

           // ── Only the HOME unit can invite — accepted-invite units cannot ───────
        $isHomeUnit = !empty($actor->doctor_sub_unit_id)
            ? $actor->doctor_sub_unit_id == $patient->doctor_sub_unit_id
            : ($actor->doctor_unit_id == $patient->doctor_unit_id && empty($patient->doctor_sub_unit_id));

        if (!$isHomeUnit) {
            return back()->with('error', 'Only the primary assigned unit can invite other units to collaborate on this patient.');
        }

        // Only doctors who can already manage this patient (home unit or
        // an accepted-invite unit) may invite further units.
        $this->authorizeManagePatient($actor, $patient);

        $existingInviteCount = PatientUnitInvite::where('patient_id', $patient->id)->count();

        if ($existingInviteCount >= self::MAX_INVITES_PER_PATIENT) {
            return back()->with('error', 'This patient has already reached the maximum of ' . self::MAX_INVITES_PER_PATIENT . ' invited units.');
        }

        // Prevent duplicate invites to the exact same unit/sub-unit
        $duplicate = PatientUnitInvite::where('patient_id', $patient->id)
            ->where('doctor_unit_id', $request->doctor_unit_id)
            ->where('doctor_sub_unit_id', $request->doctor_sub_unit_id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'This unit has already been invited (or has already accepted) for this patient.');
        }

        $invite = PatientUnitInvite::create([
            'patient_id'        => $request->patient_id,
            'department_id'     => $request->department_id,
            'doctor_unit_id'    => $request->doctor_unit_id,
            'doctor_sub_unit_id'=> $request->doctor_sub_unit_id,
            'doctor_id'         => $actor->id,
            'hospital_id'       => $actor->hospital_id,
            'instructions'      => $request->instructions,
            'status'            => 'pending',
        ]);

        $invitedUnit = $request->doctor_sub_unit_id
            ? DoctorSubUnit::find($request->doctor_sub_unit_id)
            : DoctorUnit::find($request->doctor_unit_id);
        $invitedUnitName = $invitedUnit->name ?? 'Unit';

        // Find target doctors of the invited unit/sub-unit
        $targetDoctors = $this->getUnitDoctors(
            $request->doctor_unit_id,
            $request->doctor_sub_unit_id,
            $actor->hospital_id
        );

        foreach ($targetDoctors as $doc) {
            DoctorNotification::create([
                'department_id'      => $doc->doctor_departments,
                'hospital_id'        => $doc->hospital_id,
                'doctor_unit_id'     => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id'         => $patient->id,
                'title'              => 'New Patient Unit Invite',
                'message'            => 'You have been invited to manage patient: ' . $patient->full_name . '. Please accept or reject.',
                'type'               => 'unit_invite',
            ]);

            Mail::to($doc->email)->send(new \App\Mail\UnitInviteMail($patient, $doc, $invite));
        }

        // Confirmation email to the doctor who sent the invite
        Mail::to($actor->email)->send(
            new \App\Mail\UnitInviteSentMail($patient, $actor, $invite, $invitedUnitName)
        );

        return back()->with('success', 'Unit invited successfully. They can now view this patient and must accept before making changes.');
    }

    /**
     * Invited unit accepts the invite — grants them full management
     * rights over the patient from this point on.
     */
    public function acceptInvite($inviteId)
    {
        $doctor = Auth::guard('doctor')->user();
        $invite = PatientUnitInvite::findOrFail($inviteId);

        $this->authorizeInviteResponder($doctor, $invite);

        if (!$invite->isPending()) {
            return back()->with('error', 'This invite has already been responded to.');
        }

        $invite->update([
            'status' => 'accepted',
            'responded_by_doctor_id' => $doctor->id,
            'responded_at' => now(),
        ]);

        $this->notifyInviteDecision($invite, $doctor, 'accepted');

        return back()->with('success', 'Invite accepted. Your unit can now manage this patient.');
    }

    /**
     * Invited unit rejects the invite — they retain view-only access
     * (or lose access entirely, depending on how you want to handle
     * visibility elsewhere) but can never manage the patient via this
     * invite.
     */
    public function rejectInvite($inviteId)
    {
        $doctor = Auth::guard('doctor')->user();
        $invite = PatientUnitInvite::findOrFail($inviteId);

        $this->authorizeInviteResponder($doctor, $invite);

        if (!$invite->isPending()) {
            return back()->with('error', 'This invite has already been responded to.');
        }

        $invite->update([
            'status' => 'rejected',
            'responded_by_doctor_id' => $doctor->id,
            'responded_at' => now(),
        ]);

        $this->notifyInviteDecision($invite, $doctor, 'rejected');

        return back()->with('success', 'Invite rejected.');
    }

    /**
     * Only a doctor belonging to the exact invited unit/sub-unit may
     * accept or reject that invite.
     */
    private function authorizeInviteResponder(Doctor $doctor, PatientUnitInvite $invite): void
    {
        $belongsToInvitedUnit = !empty($invite->doctor_sub_unit_id)
            ? $doctor->doctor_sub_unit_id == $invite->doctor_sub_unit_id
            : ($doctor->doctor_unit_id == $invite->doctor_unit_id && empty($doctor->doctor_sub_unit_id));

        if (!$belongsToInvitedUnit) {
            abort(403, 'Only doctors in the invited unit can respond to this invite.');
        }
    }

    /**
     * Notify + email both sides of an accept/reject decision:
     * the doctor who originally sent the invite, and the rest of the
     * responding unit (so everyone in that unit knows the outcome).
     */
    private function notifyInviteDecision(PatientUnitInvite $invite, Doctor $respondingDoctor, string $decision): void
    {
        $patient = $invite->patient;
        $inviter = $invite->doctor;

        if ($inviter) {
            DoctorNotification::create([
                'department_id' => $inviter->doctor_departments,
                'hospital_id' => $inviter->hospital_id,
                'doctor_unit_id' => $inviter->doctor_unit_id,
                'doctor_sub_unit_id' => $inviter->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Unit Invite ' . ucfirst($decision),
                'message' => $respondingDoctor->full_name . ' has ' . $decision . ' the invite for patient ' . $patient->full_name,
                'type' => 'unit_invite_' . $decision,
            ]);

            Mail::to($inviter->email)->send(
                new \App\Mail\UnitInviteDecisionMail($patient, $inviter, $respondingDoctor, $invite, $decision)
            );
        }

        // Let the rest of the responding unit know too (in case someone
        // else on the team wants to see the outcome)
        $unitDoctors = $this->getUnitDoctors($invite->doctor_unit_id, $invite->doctor_sub_unit_id, $invite->hospital_id);

        foreach ($unitDoctors as $doc) {
            if ($doc->id === $respondingDoctor->id) {
                continue;
            }

            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Unit Invite ' . ucfirst($decision),
                'message' => $respondingDoctor->full_name . ' ' . $decision . ' the invite for patient ' . $patient->full_name . ' on behalf of your unit.',
                'type' => 'unit_invite_' . $decision,
            ]);
        }
    }

    public function store($patientId)
    {
        $patient = Patient::findOrFail($patientId);

        return view('parklane.doctor.add_medication', compact('patient'));
    }

    public function storeMedication(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medication' => 'required|array|min:1',
            'medication.*' => 'required|string|max:255',
            'dosage_amount' => 'required|array|min:1',
            'dosage_amount.*' => 'required|numeric|min:0.01',
            'dosage_unit' => 'required|array|min:1',
            'dosage_unit.*' => 'required|in:mg,g,ml',
            'frequency' => 'required|array|min:1',
            'frequency.*' => 'required|string|max:255',
            'start_date' => 'required|array|min:1',
            'start_date.*' => 'required|date',
            'end_date' => 'required|array|min:1',
            'end_date.*' => 'required|date|after_or_equal:start_date.*',
            'instructions' => 'nullable|array',
            'instructions.*' => 'nullable|string',
        ]);

        $actor = Auth::guard('doctor')->user();
        $patient = Patient::findOrFail($validated['patient_id']);

        $this->authorizeManagePatient($actor, $patient);

        $createdMedications = [];

        foreach ($validated['medication'] as $index => $medicationName) {
            $createdMedications[] = Medication::create([
                'patient_id' => $validated['patient_id'],
                'medication' => $medicationName,
                'dosage' => $validated['dosage_amount'][$index] . ' ' . $validated['dosage_unit'][$index],
                'frequency' => $validated['frequency'][$index],
                'start_date' => $validated['start_date'][$index],
                'end_date' => $validated['end_date'][$index],
                'instructions' => $validated['instructions'][$index] ?? null,
                'doctor_id' => $actor->id,
                'hospital_id' => $actor->hospital_id,
            ]);
        }

        $actorName = $actor->full_name;
        $actorCadre = $actor->cadre ? ucwords(str_replace('_', ' ', $actor->cadre)) : 'Doctor';
        $targetDoctors = $this->getTargetDoctorsForPatient($patient, $actor);

        foreach ($targetDoctors as $doc) {
            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Medication Plan Added',
                'message' => $actorCadre . ' ' . $actorName . ' added medication plan(s) for ' . $patient->full_name,
                'type' => 'medication_plan',
            ]);

            Mail::to($doc->email)->send(
                new \App\Mail\MedicationPlanAddedMail($patient, $doc, $createdMedications[0], $actor)
            );
        }

        return redirect()->route('doctor.patients.plan', $validated['patient_id'])
            ->with('success', 'Medication plan added successfully.');
    }

    // Show edit form
    public function editMedication($id)
    {
        $medication = Medication::findOrFail($id);
        return view('parklane.doctor.update_medication', compact('medication'));
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

        $actor = auth()->guard('doctor')->user();
        $medication = Medication::findOrFail($id);
        $patient = Patient::findOrFail($medication->patient_id);

        $this->authorizeManagePatient($actor, $patient);

        $medication->update([
            'medication' => $validated['medication'],
            'dosage' => $validated['dosage'],
            'frequency' => $validated['frequency'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'instructions' => $request->instructions,
            'doctor_id' => $actor->id,
            'hospital_id' => $actor->hospital_id,
        ]);

        $actorName = $actor->full_name;
        $actorCadre = $actor->cadre ? ucwords(str_replace('_', ' ', $actor->cadre)) : 'Doctor';

        $targetDoctors = $this->getTargetDoctorsForPatient($patient, $actor);

        foreach ($targetDoctors as $doc) {
            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Medication Plan Updated',
                'message' => $actorCadre . ' ' . $actorName . ' updated the medication plan for ' . $patient->full_name,
                'type' => 'medication_plan_updated',
            ]);

            Mail::to($doc->email)->send(
                new \App\Mail\MedicationPlanUpdatedMail($patient, $doc, $medication, $actor)
            );
        }

        return redirect()->back()->with('success', 'Medication updated successfully!');
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        $wards = Ward::all();
        $units = DoctorUnit::whereNull('parent_id')->with('subUnits')->get();

        return view('parklane.doctor.update_patient', compact('patient', 'wards', 'units'));
    }

    public function patientUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'email' => 'nullable|email',
            'mobile_number' => 'required|string|max:15',
            'marital_status' => 'nullable|string|max:10',
            'current_condition' => 'nullable|string|max:20',
            'ward_id' => 'nullable|integer|exists:wards,id',
            'doctor_unit_id' => 'nullable|integer|exists:doctor_units,id',
            'current_medications' => 'nullable|string|max:20',
            'allergies' => 'nullable|string|max:20',
            'urgency_status' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $patient = Patient::findOrFail($id);
        $doctor = Auth::guard('doctor')->user();

        $this->authorizeManagePatient($doctor, $patient);

        $patient->update($validated);

        if (strtolower($patient->urgency_status) === 'emergency') {
            $this->sendEmergencyAlertToUnit($patient, $doctor, 'updated');
        }

        return redirect()->route('doctor.edit_patients', $patient->id)->with('success', 'Patient updated successfully!');
    }

    public function show($patient_id)
    {
        $doctor = Auth::guard('doctor')->user();

        $patient = Patient::where('id', $patient_id)
            ->where('hospital_id', $doctor->hospital_id)
            ->firstOrFail();

        $medications = Medication::where('patient_id', $patient_id)->get();
        $handoverNotes = HandoverNotes::where('patient_id', $patient_id)->latest()->get();

        $departments = DoctorDepartment::all();

        // Views can use this to hide/disable action buttons for
        // doctors whose unit hasn't accepted an invite yet.
        $canManage = $this->doctorCanManagePatient($doctor, $patient);

        return view('parklane.doctor.show_patient', compact(
            'patient',
            'medications',
            'handoverNotes',
            'departments',
            'canManage'
        ));
    }

    public function destroy($id)
    {
        $medication = Medication::findOrFail($id);
        $patientId = $medication->patient_id;

        $actor = Auth::guard('doctor')->user();
        $patient = Patient::findOrFail($patientId);
        $this->authorizeManagePatient($actor, $patient);

        $medication->delete();

        return redirect()->route('doctor.show_patient', $patientId)
                         ->with('success', 'Medication deleted successfully!');
    }

    public function showHandover($patient_id)
    {
        $doctor = Auth::guard('doctor')->user();

        $patient = Patient::where('id', $patient_id)
            ->where('hospital_id', $doctor->hospital_id)
            ->firstOrFail();

        return view('parklane.doctor.handovernote', compact('patient'));
    }

    public function storeHandover(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'summary' => 'required|string',
            'alerts' => 'required|string',
            'pending_tasks' => 'required|string',
        ]);

        $actor = Auth::guard('doctor')->user();
        $patient = Patient::findOrFail($validated['patient_id']);

        $this->authorizeManagePatient($actor, $patient);

        $validated['doctor_id'] = $actor->id;
        $validated['hospital_id'] = $actor->hospital_id;

        $handover = HandoverNotes::create($validated);

        $actorName = $actor->full_name;
        $actorCadre = $actor->cadre ? ucwords(str_replace('_', ' ', $actor->cadre)) : 'Doctor';
        $targetDoctors = $this->getTargetDoctorsForPatient($patient, $actor);

        foreach ($targetDoctors as $doc) {
            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'New Handover Note',
                'message' => $actorCadre . ' ' . $actorName . ' submitted a handover note for ' . $patient->full_name,
                'type' => 'handover_note',
            ]);

            Mail::to($doc->email)->send(
                new \App\Mail\HandoverNoteAddedMail($patient, $doc, $handover, $actor)
            );
        }

        return redirect()->route('doctor.patient', $request->patient_id)
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

        return view('parklane.doctor.update_handoverNote', compact('handoverNote'));
    }

    public function updateHandoverNote(Request $request, $id)
    {
        $validated = $request->validate([
            'summary' => 'required|string',
            'alerts' => 'required|string',
            'pending_tasks' => 'required|string',
        ]);

        $actor = auth()->guard('doctor')->user();
        $handoverNote = HandoverNotes::findOrFail($id);
        $patient = Patient::findOrFail($handoverNote->patient_id);

        $this->authorizeManagePatient($actor, $patient);

        $handoverNote->update([
            'summary' => $validated['summary'],
            'alerts' => $validated['alerts'],
            'pending_tasks' => $validated['pending_tasks'],
            'doctor_id' => $actor->id,
            'hospital_id' => $actor->hospital_id,
        ]);

        $actorName = $actor->full_name;
        $actorCadre = $actor->cadre ? ucwords(str_replace('_', ' ', $actor->cadre)) : 'Doctor';

        $targetDoctors = $this->getTargetDoctorsForPatient($patient, $actor);

        foreach ($targetDoctors as $doc) {
            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Handover Note Updated',
                'message' => $actorCadre . ' ' . $actorName . ' updated the handover note for ' . $patient->full_name,
                'type' => 'handover_note_updated',
            ]);

            Mail::to($doc->email)->send(
                new \App\Mail\HandoverNoteUpdatedMail($patient, $doc, $handoverNote, $actor)
            );
        }

        return redirect()->route('doctor.show_patient', $handoverNote->patient_id)
            ->with('success', 'Handover note updated successfully.');
    }

    public function discharge(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'discharge_type' => 'required|in:home,transfer',
            'notes' => 'nullable|string',
            'department_id' => 'nullable|required_if:discharge_type,transfer|exists:doctor_departments,id',
            'doctor_unit_id' => 'nullable|required_if:discharge_type,transfer|exists:doctor_units,id',
            'doctor_sub_unit_id' => 'nullable|exists:doctor_sub_units,id',
        ]);

        $actor = Auth::guard('doctor')->user();
        $patient = Patient::findOrFail($request->patient_id);

        $this->authorizeManagePatient($actor, $patient);

        if ($request->discharge_type === 'home') {
            $patient->update([
                'status' => 'discharged',
                'discharge_notes' => $request->notes,
                'discharged_at' => now(),
            ]);

            return back()->with('success', 'Patient discharged home successfully!');
        }

        $newUnit = DoctorUnit::findOrFail($request->doctor_unit_id);
        $newSubUnit = $request->doctor_sub_unit_id ? DoctorSubUnit::find($request->doctor_sub_unit_id) : null;

        $actorCadre = $actor->cadre ? ucwords(str_replace('_', ' ', $actor->cadre)) : 'Doctor';
        $actorName = $actor->full_name;

        $oldUnitName = optional(DoctorUnit::find($patient->doctor_unit_id))->name ?? 'Unit';
        $oldSubUnitName = $patient->doctor_sub_unit_id ? optional(DoctorSubUnit::find($patient->doctor_sub_unit_id))->name : null;
        $newUnitName = $newUnit->name;
        $newSubUnitName = $newSubUnit?->name;

        $patient->update([
            'transfer_from_unit_id' => $patient->doctor_unit_id,
            'transfer_from_sub_unit_id' => $patient->doctor_sub_unit_id,
            'pending_transfer_unit_id' => $request->doctor_unit_id,
            'pending_transfer_sub_unit_id' => $request->doctor_sub_unit_id,
            'transfer_status' => 'pending',
            'discharge_notes' => $request->notes,
        ]);

        if (!empty($request->doctor_sub_unit_id)) {
            $receivingDoctors = Doctor::where('doctor_sub_unit_id', $request->doctor_sub_unit_id)
                ->where('hospital_id', $actor->hospital_id)
                ->get();
        } else {
            $receivingDoctors = Doctor::where('doctor_unit_id', $request->doctor_unit_id)
                ->where('hospital_id', $actor->hospital_id)
                ->get();
        }

        foreach ($receivingDoctors as $doc) {
            DoctorNotification::create([
                'department_id' => $newUnit->doctor_department_id,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Transfer Request Pending',
                'message' => $actorCadre . ' ' . $actorName . ' transferred patient ' . $patient->full_name .
                    ' from ' . $oldUnitName . ($oldSubUnitName ? ' - ' . $oldSubUnitName : '') .
                    ' to your unit. Please accept or reject.',
                'type' => 'transfer_pending',
            ]);

            Mail::to($doc->email)->send(
                new \App\Mail\DischargeTransferMail(
                    $patient,
                    $doc,
                    $request->notes,
                    $actor,
                    $newUnitName,
                    $newSubUnitName,
                    'pending',
                    $oldUnitName,
                    $oldSubUnitName
                )
            );
        }

        return back()->with('success', 'Transfer request sent successfully.');
    }

    public function acceptTransfer($patientId)
    {
        $doctor = Auth::guard('doctor')->user();
        $patient = Patient::findOrFail($patientId);

        $acceptorCadre = $doctor->cadre ? ucwords(str_replace('_', ' ', $doctor->cadre)) : 'Doctor';
        $acceptorName = $doctor->full_name;

        $oldUnit = DoctorUnit::find($patient->transfer_from_unit_id);
        $oldSubUnit = $patient->transfer_from_sub_unit_id ? DoctorSubUnit::find($patient->transfer_from_sub_unit_id) : null;

        $newUnit = DoctorUnit::find($patient->pending_transfer_unit_id);
        $newSubUnit = $patient->pending_transfer_sub_unit_id ? DoctorSubUnit::find($patient->pending_transfer_sub_unit_id) : null;

        $oldUnitName = $oldUnit?->name ?? 'Unit';
        $oldSubUnitName = $oldSubUnit?->name;
        $newUnitName = $newUnit?->name ?? 'Unit';
        $newSubUnitName = $newSubUnit?->name;

        $patient->update([
            'doctor_unit_id' => $patient->pending_transfer_unit_id,
            'doctor_sub_unit_id' => $patient->pending_transfer_sub_unit_id,
            'pending_transfer_unit_id' => null,
            'pending_transfer_sub_unit_id' => null,
            'transfer_status' => 'accepted',
            'status' => 'active',
        ]);

        DoctorNotification::create([
            'department_id' => $doctor->doctor_departments,
            'hospital_id' => $doctor->hospital_id,
            'doctor_unit_id' => $doctor->doctor_unit_id,
            'doctor_sub_unit_id' => $doctor->doctor_sub_unit_id,
            'patient_id' => $patient->id,
            'title' => 'Transfer Accepted',
            'message' => 'You accepted transfer of patient ' . $patient->full_name . ' into ' . $newUnitName . ($newSubUnitName ? ' - ' . $newSubUnitName : ''),
            'type' => 'transfer_accepted',
        ]);

        Mail::to($doctor->email)->send(
            new \App\Mail\TransferDecisionMail(
                $patient,
                $doctor,
                'accepted',
                $doctor,
                $oldUnitName,
                $oldSubUnitName,
                $newUnitName,
                $newSubUnitName
            )
        );

        $sourceDoctors = !empty($patient->transfer_from_sub_unit_id)
            ? Doctor::where('doctor_sub_unit_id', $patient->transfer_from_sub_unit_id)->where('hospital_id', $doctor->hospital_id)->get()
            : Doctor::where('doctor_unit_id', $patient->transfer_from_unit_id)->where('hospital_id', $doctor->hospital_id)->get();

        foreach ($sourceDoctors as $doc) {
            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Transfer Accepted By Receiving Unit',
                'message' => $acceptorCadre . ' ' . $acceptorName . ' accepted patient ' . $patient->full_name . ' into ' . $newUnitName . ($newSubUnitName ? ' - ' . $newSubUnitName : ''),
                'type' => 'transfer_accepted_source',
            ]);

            Mail::to($doc->email)->send(
                new \App\Mail\TransferDecisionMail(
                    $patient,
                    $doc,
                    'accepted',
                    $doctor,
                    $oldUnitName,
                    $oldSubUnitName,
                    $newUnitName,
                    $newSubUnitName
                )
            );
        }

        $originDoctor = Doctor::find($patient->doctor_id);
        if ($originDoctor) {
            DoctorNotification::create([
                'department_id' => $originDoctor->doctor_departments,
                'hospital_id' => $originDoctor->hospital_id,
                'doctor_unit_id' => $originDoctor->doctor_unit_id,
                'doctor_sub_unit_id' => $originDoctor->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Transfer Accepted',
                'message' => $acceptorCadre . ' ' . $acceptorName . ' accepted transfer of patient ' . $patient->full_name,
                'type' => 'transfer_accepted_origin',
            ]);

            Mail::to($originDoctor->email)->send(
                new \App\Mail\TransferDecisionMail(
                    $patient,
                    $originDoctor,
                    'accepted',
                    $doctor,
                    $oldUnitName,
                    $oldSubUnitName,
                    $newUnitName,
                    $newSubUnitName
                )
            );
        }

        return back()->with('success', 'Patient transfer accepted successfully.');
    }

    public function rejectTransfer($patientId)
    {
        $doctor = Auth::guard('doctor')->user();
        $patient = Patient::findOrFail($patientId);

        $rejectorCadre = $doctor->cadre ? ucwords(str_replace('_', ' ', $doctor->cadre)) : 'Doctor';
        $rejectorName = $doctor->full_name;

        $oldUnit = DoctorUnit::find($patient->transfer_from_unit_id);
        $oldSubUnit = $patient->transfer_from_sub_unit_id ? DoctorSubUnit::find($patient->transfer_from_sub_unit_id) : null;

        $pendingUnit = DoctorUnit::find($patient->pending_transfer_unit_id);
        $pendingSubUnit = $patient->pending_transfer_sub_unit_id ? DoctorSubUnit::find($patient->pending_transfer_sub_unit_id) : null;

        $oldUnitName = $oldUnit?->name ?? 'Unit';
        $oldSubUnitName = $oldSubUnit?->name;
        $pendingUnitName = $pendingUnit?->name ?? 'Unit';
        $pendingSubUnitName = $pendingSubUnit?->name;

        $patient->update([
            'pending_transfer_unit_id' => null,
            'pending_transfer_sub_unit_id' => null,
            'transfer_status' => 'rejected',
        ]);

        DoctorNotification::create([
            'department_id' => $doctor->doctor_departments,
            'hospital_id' => $doctor->hospital_id,
            'doctor_unit_id' => $doctor->doctor_unit_id,
            'doctor_sub_unit_id' => $doctor->doctor_sub_unit_id,
            'patient_id' => $patient->id,
            'title' => 'Transfer Rejected',
            'message' => 'You rejected transfer of patient ' . $patient->full_name,
            'type' => 'transfer_rejected',
        ]);

        Mail::to($doctor->email)->send(
            new \App\Mail\TransferDecisionMail(
                $patient,
                $doctor,
                'rejected',
                $doctor,
                $oldUnitName,
                $oldSubUnitName,
                $pendingUnitName,
                $pendingSubUnitName
            )
        );

        $sourceDoctors = !empty($patient->transfer_from_sub_unit_id)
            ? Doctor::where('doctor_sub_unit_id', $patient->transfer_from_sub_unit_id)->where('hospital_id', $doctor->hospital_id)->get()
            : Doctor::where('doctor_unit_id', $patient->transfer_from_unit_id)->where('hospital_id', $doctor->hospital_id)->get();

        foreach ($sourceDoctors as $doc) {
            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Transfer Rejected By Receiving Unit',
                'message' => $rejectorCadre . ' ' . $rejectorName . ' rejected transfer of patient ' . $patient->full_name,
                'type' => 'transfer_rejected_source',
            ]);

            Mail::to($doc->email)->send(
                new \App\Mail\TransferDecisionMail(
                    $patient,
                    $doc,
                    'rejected',
                    $doctor,
                    $oldUnitName,
                    $oldSubUnitName,
                    $pendingUnitName,
                    $pendingSubUnitName
                )
            );
        }

        $originDoctor = Doctor::find($patient->doctor_id);
        if ($originDoctor) {
            DoctorNotification::create([
                'department_id' => $originDoctor->doctor_departments,
                'hospital_id' => $originDoctor->hospital_id,
                'doctor_unit_id' => $originDoctor->doctor_unit_id,
                'doctor_sub_unit_id' => $originDoctor->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Transfer Rejected',
                'message' => $rejectorCadre . ' ' . $rejectorName . ' rejected transfer of patient ' . $patient->full_name,
                'type' => 'transfer_rejected_origin',
            ]);

            Mail::to($originDoctor->email)->send(
                new \App\Mail\TransferDecisionMail(
                    $patient,
                    $originDoctor,
                    'rejected',
                    $doctor,
                    $oldUnitName,
                    $oldSubUnitName,
                    $pendingUnitName,
                    $pendingSubUnitName
                )
            );
        }

        return back()->with('success', 'Patient transfer rejected successfully.');
    }

    public function markNotificationsSeen()
    {
        $doctor = Auth::guard('doctor')->user();

        DoctorNotification::query()
            ->where('doctor_unit_id', $doctor->doctor_unit_id)
            ->when($doctor->doctor_sub_unit_id, function ($q) use ($doctor) {
                $q->where(function ($qq) use ($doctor) {
                    $qq->whereNull('doctor_sub_unit_id')
                       ->orWhere('doctor_sub_unit_id', $doctor->doctor_sub_unit_id);
                });
            })
            ->whereNull('seen_at')
            ->update([
                'seen_at' => now(),
            ]);

        return response()->json(['status' => 'ok']);
    }

    private function sendEmergencyAlertToUnit(Patient $patient, Doctor $actor, string $action = 'added')
    {
        $targetDoctors = $this->getTargetDoctorsForPatient($patient, $actor);

        $actorCadre = $actor->cadre ? ucwords(str_replace('_', ' ', $actor->cadre)) : 'Doctor';
        $actorName = $actor->full_name;

        foreach ($targetDoctors as $doc) {
            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => $patient->id,
                'title' => 'Emergency Patient Alert',
                'message' => $actorCadre . ' ' . $actorName . ' ' . $action . ' an emergency patient: ' . $patient->full_name,
                'type' => 'emergency_alert',
            ]);

            Mail::to($doc->email)->send(new \App\Mail\EmergencyPatientAlertMail($patient, $doc, $actor, $action));
        }
    }
}