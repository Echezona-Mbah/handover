<?php

namespace App\Http\Controllers;

use App\Models\CrossRolePatientRequest;
use App\Models\Dentists;
use App\Models\Doctor;
use App\Models\Patient;
use App\Notifications\DentistPatientActivityNotification;
use App\Services\CrossRolePatientWorkflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrossRolePatientWorkflowController extends Controller
{
    public function departments(CrossRolePatientWorkflow $workflow)
    {
        return response()->json($workflow->departments(
            (int) $workflow->actor()->hospital_id,
            $workflow->role() === 'doctor' ? 'dentist' : 'doctor'
        ));
    }

    public function units(int $department, CrossRolePatientWorkflow $workflow)
    {
        return response()->json($workflow->units(
            (int) $workflow->actor()->hospital_id,
            $workflow->role() === 'doctor' ? 'dentist' : 'doctor',
            $department
        ));
    }

    public function subUnits(int $unit, CrossRolePatientWorkflow $workflow)
    {
        abort_unless($workflow->role() === 'dentist', 404);

        return response()->json($workflow->subUnits((int) $workflow->actor()->hospital_id, $unit));
    }

    public function storeInvite(Request $request, CrossRolePatientWorkflow $workflow): RedirectResponse
    {
        $validated = $this->validateRequest($request, $workflow);
        $patient = Patient::where('hospital_id', $workflow->actor()->hospital_id)->findOrFail($validated['patient_id']);
        abort_unless($workflow->canManage($patient), 403, 'Only the assigned or accepted care team can invite another department or unit.');

        $crossRole = $workflow->role() === 'doctor' ? 'dentist' : 'doctor';
        $targetDepartmentId = (int) $validated['target_department_id'];
        $targetUnitId = ! empty($validated['target_unit_id']) ? (int) $validated['target_unit_id'] : null;
        $targetSubUnitId = $crossRole === 'doctor' && ! empty($validated['target_sub_unit_id'])
            ? (int) $validated['target_sub_unit_id']
            : null;
        $workflow->validateTarget($crossRole, (int) $patient->hospital_id, $targetDepartmentId, $targetUnitId, $targetSubUnitId);
        abort_if($this->duplicatePendingOrAccepted($patient, 'invite', $crossRole, $targetDepartmentId, $targetUnitId, $targetSubUnitId),
            409, 'This department, unit, or subunit already has an active invitation for this patient.');

        $crossRequest = $this->createRequest($workflow, $patient, 'invite', $crossRole, $targetDepartmentId, $targetUnitId, $targetSubUnitId, $validated['message'] ?? null);
        $this->notifyTeam($workflow, $crossRequest, 'Cross-team patient invitation', 'Your department or unit has been invited to share care for '.$patient->full_name.'.');

        return back()->with('success', 'Invitation sent to the selected department or unit.');
    }

    public function storeTransfer(Request $request, CrossRolePatientWorkflow $workflow): RedirectResponse
    {
        $validated = $this->validateRequest($request, $workflow);
        $patient = Patient::where('hospital_id', $workflow->actor()->hospital_id)->findOrFail($validated['patient_id']);
        abort_unless($workflow->canManage($patient), 403, 'Only the assigned or accepted care team can transfer this patient.');

        $crossRole = $workflow->role() === 'doctor' ? 'dentist' : 'doctor';
        $targetDepartmentId = (int) $validated['target_department_id'];
        $targetUnitId = ! empty($validated['target_unit_id']) ? (int) $validated['target_unit_id'] : null;
        $targetSubUnitId = $crossRole === 'doctor' && ! empty($validated['target_sub_unit_id'])
            ? (int) $validated['target_sub_unit_id']
            : null;
        $workflow->validateTarget($crossRole, (int) $patient->hospital_id, $targetDepartmentId, $targetUnitId, $targetSubUnitId);

        abort_if(CrossRolePatientRequest::where('patient_id', $patient->id)
            ->where('request_type', 'transfer')->where('status', 'pending')->exists(), 409,
            'This patient already has a cross-department transfer awaiting acceptance.');

        $crossRequest = $this->createRequest($workflow, $patient, 'transfer', $crossRole, $targetDepartmentId, $targetUnitId, $targetSubUnitId, $validated['message'] ?? null);
        $this->notifyTeam($workflow, $crossRequest, 'Patient transfer awaiting acceptance', 'A transfer of '.$patient->full_name.' is waiting for your department or unit to accept.');

        return back()->with('success', 'Transfer request sent. The receiving department or unit must accept before ownership changes.');
    }

    public function respond(CrossRolePatientRequest $crossRequest, string $decision, CrossRolePatientWorkflow $workflow): RedirectResponse
    {
        abort_unless(in_array($decision, ['accept', 'reject'], true), 404);
        $role = $workflow->role();
        $actor = $workflow->actor();
        abort_unless($workflow->recipientMatches($crossRequest, $role, $actor), 403);
        abort_unless($crossRequest->status === 'pending', 409, 'This request has already been answered.');

        DB::transaction(function () use ($crossRequest, $decision, $workflow, $role, $actor) {
            $lockedRequest = CrossRolePatientRequest::whereKey($crossRequest->id)->lockForUpdate()->firstOrFail();
            abort_unless($lockedRequest->status === 'pending', 409, 'This request has already been answered.');
            $patient = Patient::where('hospital_id', $lockedRequest->hospital_id)
                ->whereKey($lockedRequest->patient_id)->lockForUpdate()->firstOrFail();
            if ($decision === 'accept' && $lockedRequest->request_type === 'transfer') {
                $workflow->applyTransfer($lockedRequest, $patient);
            }
            $lockedRequest->update([
                'status' => $decision === 'accept' ? 'accepted' : 'rejected',
                'responded_by_role' => $role,
                'responded_by_id' => $actor->id,
                'responded_at' => now(),
            ]);
        });

        $crossRequest->refresh();
        $this->notifySource($crossRequest,
            $crossRequest->request_type === 'invite' ? 'Patient invitation '.$decision.'ed' : 'Patient transfer '.$decision.'ed',
            $actor->full_name.' '.$decision.'ed the '.$crossRequest->request_type.' for '.$crossRequest->patient->full_name.'.');

        return back()->with('success', $decision === 'accept'
            ? ($crossRequest->request_type === 'invite'
                ? 'Invitation accepted. Your team can now manage this patient together.'
                : 'Transfer accepted. Patient ownership has been updated.')
            : 'Request declined. Patient ownership remains unchanged.');
    }

    private function validateRequest(Request $request, CrossRolePatientWorkflow $workflow): array
    {
        $rules = [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'target_department_id' => ['required', 'integer'],
            'target_unit_id' => ['nullable', 'integer'],
            'target_sub_unit_id' => ['nullable', 'integer'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
        if ($workflow->role() === 'doctor') {
            $rules['target_sub_unit_id'] = ['prohibited'];
        }

        return $request->validate($rules);
    }

    private function createRequest(
        CrossRolePatientWorkflow $workflow,
        Patient $patient,
        string $type,
        string $targetRole,
        int $targetDepartmentId,
        ?int $targetUnitId,
        ?int $targetSubUnitId,
        ?string $message
    ): CrossRolePatientRequest {
        $actor = $workflow->actor();
        $sourceRole = $workflow->role();

        return CrossRolePatientRequest::create([
            'patient_id' => $patient->id,
            'hospital_id' => $patient->hospital_id,
            'request_type' => $type,
            'source_role' => $sourceRole,
            'source_actor_id' => $actor->id,
            'source_department_id' => $sourceRole === 'doctor' ? $actor->doctor_departments : $actor->dentist_department_id,
            'source_unit_id' => $sourceRole === 'doctor' ? $actor->doctor_unit_id : $actor->dentist_unit_id,
            'source_sub_unit_id' => $sourceRole === 'doctor' ? $actor->doctor_sub_unit_id : null,
            'target_role' => $targetRole,
            'target_department_id' => $targetDepartmentId,
            'target_unit_id' => $targetUnitId,
            'target_sub_unit_id' => $targetSubUnitId,
            'message' => $message,
            'status' => 'pending',
        ]);
    }

    private function duplicatePendingOrAccepted(
        Patient $patient,
        string $type,
        string $targetRole,
        int $departmentId,
        ?int $unitId,
        ?int $subUnitId
    ): bool {
        return CrossRolePatientRequest::where('patient_id', $patient->id)
            ->where('request_type', $type)
            ->where('target_role', $targetRole)
            ->where('target_department_id', $departmentId)
            ->where('target_unit_id', $unitId)
            ->where('target_sub_unit_id', $subUnitId)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();
    }

    private function notifyTeam(CrossRolePatientWorkflow $workflow, CrossRolePatientRequest $crossRequest, string $title, string $message): void
    {
        $members = $workflow->targetTeam(
            $crossRequest->target_role,
            (int) $crossRequest->hospital_id,
            (int) $crossRequest->target_department_id,
            $crossRequest->target_unit_id ? (int) $crossRequest->target_unit_id : null,
            $crossRequest->target_sub_unit_id ? (int) $crossRequest->target_sub_unit_id : null
        );
        $notification = new DentistPatientActivityNotification(
            $crossRequest->patient,
            $title,
            $message.($crossRequest->message ? ' Note: '.$crossRequest->message : ''),
            'cross_role_'.$crossRequest->request_type
        );
        foreach ($members as $member) {
            $member->notify($notification);
        }
    }

    private function notifySource(CrossRolePatientRequest $crossRequest, string $title, string $message): void
    {
        $members = $crossRequest->source_role === 'doctor'
            ? Doctor::where('hospital_id', $crossRequest->hospital_id)
                ->where('doctor_departments', $crossRequest->source_department_id)
                ->when($crossRequest->source_unit_id, fn ($query) => $query->where('doctor_unit_id', $crossRequest->source_unit_id))
                ->when($crossRequest->source_sub_unit_id, fn ($query) => $query->where('doctor_sub_unit_id', $crossRequest->source_sub_unit_id))
                ->get()
            : Dentists::where('hospital_id', $crossRequest->hospital_id)
                ->where('dentist_department_id', $crossRequest->source_department_id)
                ->when($crossRequest->source_unit_id, fn ($query) => $query->where('dentist_unit_id', $crossRequest->source_unit_id))
                ->get();

        if ($members->isEmpty()) {
            $member = $crossRequest->source_role === 'doctor'
                ? Doctor::where('hospital_id', $crossRequest->hospital_id)->findOrFail($crossRequest->source_actor_id)
                : Dentists::where('hospital_id', $crossRequest->hospital_id)->findOrFail($crossRequest->source_actor_id);
            $members = collect([$member]);
        }

        $notification = new DentistPatientActivityNotification($crossRequest->patient, $title, $message, 'cross_role_response');
        foreach ($members as $member) {
            $member->notify($notification);
        }
    }
}
