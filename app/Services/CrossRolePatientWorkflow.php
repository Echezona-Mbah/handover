<?php

namespace App\Services;

use App\Models\DentistDepartment;
use App\Models\DentistUnit;
use App\Models\Dentists;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Models\DoctorSubUnit;
use App\Models\DoctorUnit;
use App\Models\Patient;
use App\Models\CrossRolePatientRequest;
use App\Models\PatientUnitInvite;
use App\Models\DentistPatientUnitInvite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CrossRolePatientWorkflow
{
    public function role(): string
    {
        if (request()->routeIs('dentist.*', 'handover.*')) {
            return 'dentist';
        }

        if (request()->routeIs('doctor.*')) {
            return 'doctor';
        }

        abort(403);
    }

    public function actor(): Doctor|Dentists
    {
        return auth()->guard($this->role())->user();
    }

    public function departments(int $hospitalId, string $role): Collection
    {
        if ($role === 'doctor') {
            return DoctorDepartment::whereIn(
                'id',
                Doctor::where('hospital_id', $hospitalId)->whereNotNull('doctor_departments')->select('doctor_departments')
            )->orderBy('name')->get();
        }

        return DentistDepartment::whereIn(
            'id',
            Dentists::where('hospital_id', $hospitalId)->whereNotNull('dentist_department_id')->select('dentist_department_id')
        )->orderBy('name')->get();
    }

    public function units(int $hospitalId, string $role, int $departmentId): Collection
    {
        if ($role === 'doctor') {
            return DoctorUnit::where('doctor_department_id', $departmentId)
                ->whereNull('parent_id')
                ->whereIn('id', Doctor::where('hospital_id', $hospitalId)->select('doctor_unit_id'))
                ->orderBy('name')->get(['id', 'name']);
        }

        return DentistUnit::whereIn('id', Dentists::where('hospital_id', $hospitalId)
            ->where('dentist_department_id', $departmentId)
            ->whereNotNull('dentist_unit_id')->select('dentist_unit_id'))
            ->orderBy('name')->get(['id', 'name']);
    }

    public function subUnits(int $hospitalId, int $unitId): Collection
    {
        return DoctorSubUnit::where('doctor_unit_id', $unitId)
            ->whereIn('id', Doctor::where('hospital_id', $hospitalId)->select('doctor_sub_unit_id'))
            ->orderBy('name')->get(['id', 'name']);
    }

    public function canManage(Patient $patient): bool
    {
        $role = $this->role();
        $actor = $this->actor();
        if ((int) $actor->hospital_id !== (int) $patient->hospital_id) {
            return false;
        }

        if ($role === 'doctor') {
            $assigned = ((int) $patient->doctor_id === (int) $actor->id)
                || ((int) $patient->doctor_assign === (int) $actor->id)
                || ($actor->doctor_unit_id
                    && (int) $patient->doctor_unit_id === (int) $actor->doctor_unit_id
                    && (empty($actor->doctor_sub_unit_id)
                        ? empty($patient->doctor_sub_unit_id)
                        : (int) $patient->doctor_sub_unit_id === (int) $actor->doctor_sub_unit_id))
                || ($actor->doctor_departments && ! $patient->doctor_unit_id
                    && (int) $patient->doctor_departments === (int) $actor->doctor_departments);
        } else {
            $assigned = ((int) $patient->dentist_assign === (int) $actor->id)
                || ($actor->dentist_unit_id && (int) $patient->dentist_unit_id === (int) $actor->dentist_unit_id)
                || ($actor->dentist_department_id && (int) $patient->dentist_department_id === (int) $actor->dentist_department_id
                    && ! $patient->dentist_unit_id);
        }

        if ($assigned || $this->incomingRequests($patient, 'invite')->where('status', 'accepted')->exists()) {
            return true;
        }

        if ($role === 'doctor') {
            return PatientUnitInvite::where('patient_id', $patient->id)
                ->where('status', 'accepted')
                ->where('doctor_unit_id', $actor->doctor_unit_id)
                ->when($actor->doctor_sub_unit_id,
                    fn (Builder $query) => $query->where('doctor_sub_unit_id', $actor->doctor_sub_unit_id),
                    fn (Builder $query) => $query->whereNull('doctor_sub_unit_id'))
                ->exists();
        }

        return DentistPatientUnitInvite::where('patient_id', $patient->id)
            ->where('status', 'accepted')
            ->where('dentist_department_id', $actor->dentist_department_id)
            ->when($actor->dentist_unit_id,
                fn (Builder $query) => $query->where(function (Builder $unit) use ($actor) {
                    $unit->whereNull('dentist_unit_id')->orWhere('dentist_unit_id', $actor->dentist_unit_id);
                }),
                fn (Builder $query) => $query->whereNull('dentist_unit_id'))
            ->exists();
    }

    public function incomingRequests(Patient $patient, ?string $type = null): Builder
    {
        $role = $this->role();
        $actor = $this->actor();

        return CrossRolePatientRequest::where('patient_id', $patient->id)
            ->where('hospital_id', $actor->hospital_id)
            ->where('target_role', $role)
            ->where(function (Builder $query) use ($role, $actor) {
                if ($role === 'doctor') {
                    $query->where('target_department_id', $actor->doctor_departments);
                    if ($actor->doctor_unit_id) {
                        $query->where(function (Builder $unitQuery) use ($actor) {
                            $unitQuery->whereNull('target_unit_id')->orWhere('target_unit_id', $actor->doctor_unit_id);
                        });
                    } else {
                        $query->whereNull('target_unit_id');
                    }
                    if ($actor->doctor_sub_unit_id) {
                        $query->where(function (Builder $subQuery) use ($actor) {
                            $subQuery->whereNull('target_sub_unit_id')->orWhere('target_sub_unit_id', $actor->doctor_sub_unit_id);
                        });
                    } else {
                        $query->whereNull('target_sub_unit_id');
                    }
                } else {
                    $query->where('target_department_id', $actor->dentist_department_id);
                    if ($actor->dentist_unit_id) {
                        $query->where(function (Builder $unitQuery) use ($actor) {
                            $unitQuery->whereNull('target_unit_id')->orWhere('target_unit_id', $actor->dentist_unit_id);
                        });
                    } else {
                        $query->whereNull('target_unit_id');
                    }
                }
            })
            ->when($type, fn (Builder $query) => $query->where('request_type', $type));
    }

    public function incomingPatientIds(string $role, Doctor|Dentists $actor): Collection
    {
        return CrossRolePatientRequest::where('hospital_id', $actor->hospital_id)
            ->where('target_role', $role)
            ->where(function (Builder $status) {
                $status->where('status', 'pending')
                    ->orWhere(function (Builder $acceptedInvite) {
                        $acceptedInvite->where('status', 'accepted')
                            ->where('request_type', 'invite');
                    });
            })
            ->where(function (Builder $query) use ($role, $actor) {
                if ($role === 'doctor') {
                    $query->where('target_department_id', $actor->doctor_departments)
                        ->where(function (Builder $unitQuery) use ($actor) {
                            $actor->doctor_unit_id
                                ? $unitQuery->whereNull('target_unit_id')->orWhere('target_unit_id', $actor->doctor_unit_id)
                                : $unitQuery->whereNull('target_unit_id');
                        })
                        ->where(function (Builder $subQuery) use ($actor) {
                            $actor->doctor_sub_unit_id
                                ? $subQuery->whereNull('target_sub_unit_id')->orWhere('target_sub_unit_id', $actor->doctor_sub_unit_id)
                                : $subQuery->whereNull('target_sub_unit_id');
                        });
                } else {
                    $query->where('target_department_id', $actor->dentist_department_id)
                        ->where(function (Builder $unitQuery) use ($actor) {
                            $actor->dentist_unit_id
                                ? $unitQuery->whereNull('target_unit_id')->orWhere('target_unit_id', $actor->dentist_unit_id)
                                : $unitQuery->whereNull('target_unit_id');
                        });
                }
            })->distinct()->pluck('patient_id');
    }

    public function targetTeam(string $targetRole, int $hospitalId, int $departmentId, ?int $unitId, ?int $subUnitId): Collection
    {
        if ($targetRole === 'doctor') {
            return Doctor::where('hospital_id', $hospitalId)
                ->where('doctor_departments', $departmentId)
                ->when($unitId, fn (Builder $query) => $query->where('doctor_unit_id', $unitId))
                ->when($subUnitId, fn (Builder $query) => $query->where('doctor_sub_unit_id', $subUnitId))
                ->get();
        }

        return Dentists::where('hospital_id', $hospitalId)
            ->where('dentist_department_id', $departmentId)
            ->when($unitId, fn (Builder $query) => $query->where('dentist_unit_id', $unitId))
            ->get();
    }

    public function validateTarget(string $role, int $hospitalId, int $departmentId, ?int $unitId, ?int $subUnitId): void
    {
        if (! $this->targetTeam($role, $hospitalId, $departmentId, $unitId, $subUnitId)->isNotEmpty()) {
            throw ValidationException::withMessages(['department_id' => 'The selected department or team is not available at this hospital.']);
        }

        if ($role === 'doctor') {
            if ($unitId && ! DoctorUnit::whereKey($unitId)->where('doctor_department_id', $departmentId)->whereNull('parent_id')->exists()) {
                throw ValidationException::withMessages(['doctor_unit_id' => 'Choose a unit in the selected doctor department.']);
            }
            if ($subUnitId && (! $unitId || ! DoctorSubUnit::whereKey($subUnitId)->where('doctor_unit_id', $unitId)->exists())) {
                throw ValidationException::withMessages(['doctor_sub_unit_id' => 'Choose a subunit in the selected doctor unit.']);
            }
        } elseif ($subUnitId) {
            throw ValidationException::withMessages(['doctor_sub_unit_id' => 'Dental units do not have subunits.']);
        }
    }

    public function recipientMatches(CrossRolePatientRequest $request, string $role, Doctor|Dentists $actor): bool
    {
        if ($role !== $request->target_role || (int) $actor->hospital_id !== (int) $request->hospital_id) {
            return false;
        }

        if ($role === 'doctor') {
            return (int) $actor->doctor_departments === (int) $request->target_department_id
                && (! $request->target_unit_id || (int) $actor->doctor_unit_id === (int) $request->target_unit_id)
                && (! $request->target_sub_unit_id || (int) $actor->doctor_sub_unit_id === (int) $request->target_sub_unit_id);
        }

        return (int) $actor->dentist_department_id === (int) $request->target_department_id
            && (! $request->target_unit_id || (int) $actor->dentist_unit_id === (int) $request->target_unit_id);
    }

    public function applyTransfer(CrossRolePatientRequest $request, Patient $patient): void
    {
        $patient->doctor_id = null;
        $patient->doctor_assign = null;
        $patient->doctor_departments = null;
        $patient->doctor_unit_id = null;
        $patient->doctor_sub_unit_id = null;
        $patient->dentist_assign = null;
        $patient->dentist_department_id = null;
        $patient->dentist_unit_id = null;

        if ($request->target_role === 'doctor') {
            $patient->doctor_departments = $request->target_department_id;
            $patient->doctor_unit_id = $request->target_unit_id;
            $patient->doctor_sub_unit_id = $request->target_sub_unit_id;
        } else {
            $patient->dentist_department_id = $request->target_department_id;
            $patient->dentist_unit_id = $request->target_unit_id;
        }

        $patient->status = 'active';
        $patient->discharged_at = null;
        $patient->discharge_notes = null;
        $patient->save();
    }
}
